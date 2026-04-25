document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('mainForm');
    const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
    const groupCode = document.getElementById('maNhom');
    const groupName = document.getElementById('tenNhom');
    const groupDesc = document.getElementById('moTa');

    let initialValues = {};

    function getPermissionsState() {
        const checkedPerms = Array.from(document.querySelectorAll('.perm-checkbox:checked')).map(cb => cb.value);
        const checkedNewMembers = Array.from(document.querySelectorAll('input[name="new_members[]"]:checked')).map(cb => cb.value);
        return JSON.stringify({ perms: checkedPerms, members: checkedNewMembers });
    }

    function captureInitialState() {
        initialValues = {
            ma: groupCode ? groupCode.value : '',
            ten: groupName ? groupName.value : '',
            mota: groupDesc ? groupDesc.value : '',
            complex: getPermissionsState()
        };
        checkStatus();
    }

    function validateGroupCode() {
        const errorSpan = document.getElementById('maNhom_error');
        if (!groupCode || groupCode.readOnly || groupCode.type === 'hidden') return true;

        const value = groupCode.value.trim();
        const regex = /^[A-Z0-9_]+$/;

        if (!value) return showError(groupCode, errorSpan, 'Mã nhóm không được để trống');
        if (value.includes(' ')) return showError(groupCode, errorSpan, 'Mã nhóm không được chứa dấu cách');
        if (!regex.test(value)) return showError(groupCode, errorSpan, 'Chỉ dùng chữ hoa, số và gạch dưới');
        if (value.length < 3) return showError(groupCode, errorSpan, 'Mã nhóm phải có ít nhất 3 ký tự');
        
        showSuccess(groupCode, errorSpan);
        return true;
    }

    function validateGroupName() {
        const errorSpan = document.getElementById('tenNhom_error');
        if (!groupName) return true;
        const value = groupName.value.trim();

        if (!value) return showError(groupName, errorSpan, 'Tên nhóm không được để trống');
        if (value.length < 3) return showError(groupName, errorSpan, 'Tên nhóm phải có ít nhất 3 ký tự');
        
        showSuccess(groupName, errorSpan);
        return true;
    }

    function showError(input, errorEl, message) {
        input.classList.add('border-red-600');
        input.classList.remove('border-green-600');
        if (errorEl) {
            errorEl.classList.remove('hidden');
            errorEl.textContent = message;
        }
        return false;
    }

    function showSuccess(input, errorEl) {
        input.classList.remove('border-red-600');
        input.classList.add('border-green-600');
        if (errorEl) errorEl.classList.add('hidden');
        return true;
    }

    function checkStatus() {
        const hasChanged = 
            (groupCode && groupCode.value !== initialValues.ma) ||
            (groupName && groupName.value !== initialValues.ten) ||
            (groupDesc && groupDesc.value !== initialValues.mota) ||
            (getPermissionsState() !== initialValues.complex);

        const isValid = validateGroupCode() && validateGroupName();

        if (submitBtn) {
            if (hasChanged && isValid) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    function updatePreview() {
        const previewText = document.getElementById('previewText');
        const badge = document.getElementById('badgePreview');
        if (!groupName || !previewText || !badge) return;

        previewText.textContent = groupName.value.trim() || "Tên nhóm...";
    }

    form.addEventListener('input', checkStatus);
    form.addEventListener('change', checkStatus);

    if (groupCode) {
        groupCode.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    if (groupName) {
        groupName.addEventListener('input', updatePreview);
    }

    const originalCheckAll = window.checkAllPermissions;
    window.checkAllPermissions = function() {
        if (originalCheckAll) originalCheckAll();
        checkStatus();
    };

    const originalUncheckAll = window.uncheckAllPermissions;
    window.uncheckAllPermissions = function() {
        if (originalUncheckAll) originalUncheckAll();
        checkStatus();
    };

    const originalToggleModule = window.toggleModule;
    window.toggleModule = function(m, c) {
        if (originalToggleModule) originalToggleModule(m, c);
        checkStatus();
    };

    captureInitialState();

    form.addEventListener('submit', (e) => {
        if (submitBtn.disabled) e.preventDefault();
    });
});