document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('mainForm');
    const keyInput = document.getElementById('permissionKey');
    const nameInput = document.getElementById('permissionName');
    const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

    const errorKey = document.getElementById('permissionKey_error');
    const helperKey = document.getElementById('permissionKey_helper');
    const errorName = document.getElementById('permissionName_error');

    let initialValues = {};

    function captureInitialState() {
        initialValues = {
            key: keyInput ? keyInput.value : document.querySelector('input[name="maQuyen"]')?.value || '',
            name: nameInput ? nameInput.value : ''
        };
        checkStatus();
    }

    function validatePermissionKey() {
        if (!keyInput) return true;

        const value = keyInput.value.trim();
        if (!value) return showError(keyInput, errorKey, helperKey, 'Mã quyền không được để trống');
        if (value.includes(' ')) return showError(keyInput, errorKey, helperKey, 'Không được chứa dấu cách');
        if (!value.includes('.')) return showError(keyInput, errorKey, helperKey, 'Phải có dạng "resource.action"');
        if (!/^[a-z0-9._]+$/.test(value)) return showError(keyInput, errorKey, helperKey, 'Chỉ dùng chữ thường, số, dấu chấm và gạch dưới');

        showSuccess(keyInput, errorKey, helperKey, 'Mã quyền hợp lệ');
        return true;
    }

    function validatePermissionName() {
        if (!nameInput) return true;
        const value = nameInput.value.trim();

        if (!value) {
            return showError(nameInput, errorName, null, 'Tên hiển thị không được để trống');
        } else if (value.length < 3) {
            return showError(nameInput, errorName, null, 'Tên hiển thị phải từ 3 ký tự');
        }
        
        nameInput.classList.remove('border-red-600');
        nameInput.classList.add('border-green-600');
        errorName.classList.add('hidden');
        return true;
    }

    function showError(input, errorEl, helperEl, msg) {
        input.classList.add('border-red-600');
        input.classList.remove('border-green-600');
        if (errorEl) {
            errorEl.classList.remove('hidden');
            errorEl.textContent = msg;
        }
        if (helperEl) helperEl.classList.add('hidden');
        return false;
    }

    function showSuccess(input, errorEl, helperEl, msg) {
        input.classList.remove('border-red-600');
        input.classList.add('border-green-600');
        if (errorEl) errorEl.classList.add('hidden');
        if (helperEl) {
            helperEl.classList.remove('hidden');
            helperEl.classList.add('text-green-600');
            helperEl.innerHTML = ` ${msg}`;
        }
        return true;
    }

    function checkStatus() {
        const currentKey = keyInput ? keyInput.value : initialValues.key;
        const currentName = nameInput ? nameInput.value : '';

        const hasChanged = currentKey !== initialValues.key || currentName !== initialValues.name;
        const isValid = validatePermissionKey() && validatePermissionName();

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

    if (keyInput) {
        keyInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase();
            checkStatus();
        });
    }

    if (nameInput) {
        nameInput.addEventListener('input', checkStatus);
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            if (submitBtn && submitBtn.disabled) {
                e.preventDefault();
                return false;
            }
        });
    }

    captureInitialState();
});