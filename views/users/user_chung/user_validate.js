document.addEventListener('DOMContentLoaded', function() {
    const userForm = document.getElementById('userForm');
    const getEl = (id) => document.getElementById(id);
    const submitBtn = userForm ? userForm.querySelector('button[type="submit"]') : null;

    // 1. Lấy các phần tử Input
    const inputs = {
        maNguoiDung: getEl('maNguoiDung'),
        fullname: getEl('fullname'),
        email: getEl('email'),
        role: getEl('role')
    };

    let initialValues = {};

    // 2. Chụp ảnh trạng thái ban đầu để so sánh
    function captureInitialState() {
        initialValues = {
            ma: inputs.maNguoiDung ? inputs.maNguoiDung.value : '',
            name: inputs.fullname ? inputs.fullname.value : '',
            mail: inputs.email ? inputs.email.value : '',
            role: inputs.role ? inputs.role.value : ''
        };
        checkStatus(); // Kiểm tra ngay để khóa nút khi vừa vào trang
    }

    // 3. Hàm gán class và hiển thị lỗi
    function setStatus(element, errorId, isValid) {
        if (!element) return true;
        const errorMsg = document.getElementById(errorId);
        
        if (isValid) {
            element.classList.remove('input-invalid');
            element.classList.add('input-valid');
            if (errorMsg) errorMsg.classList.add('hidden');
        } else {
            element.classList.remove('input-valid');
            element.classList.add('input-invalid');
            if (errorMsg) errorMsg.classList.remove('hidden');
        }
        return isValid;
    }

    // 4. Các hàm Validate từng trường
    const validators = {
        maNguoiDung: () => {
            if (!inputs.maNguoiDung) return true;
            return setStatus(inputs.maNguoiDung, 'maNguoiDung_error', inputs.maNguoiDung.value.trim().length > 0);
        },
        fullname: () => {
            if (!inputs.fullname) return true;
            return setStatus(inputs.fullname, 'fullname_error', inputs.fullname.value.trim().length > 0);
        },
        email: () => {
            if (!inputs.email) return true;
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return setStatus(inputs.email, 'email_error', regex.test(inputs.email.value));
        },
        role: () => {
            if (!inputs.role) return true;
            return setStatus(inputs.role, 'role_error', inputs.role.value !== "");
        }
    };

    // 5. Hàm tổng hợp: Kiểm tra Thay đổi + Hợp lệ
    function checkStatus() {
        const hasChanged = 
            (inputs.maNguoiDung && inputs.maNguoiDung.value !== initialValues.ma) ||
            (inputs.fullname && inputs.fullname.value !== initialValues.name) ||
            (inputs.email && inputs.email.value !== initialValues.mail) ||
            (inputs.role && inputs.role.value !== initialValues.role);

        // Chạy validate để cập nhật viền xanh/đỏ
        const isMaValid = validators.maNguoiDung();
        const isNameValid = validators.fullname();
        const isEmailValid = validators.email();
        const isRoleValid = validators.role();

        const isValid = isMaValid && isNameValid && isEmailValid && isRoleValid;

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

    // 6. Gán sự kiện lắng nghe Realtime
    if (inputs.maNguoiDung && !inputs.maNguoiDung.readOnly) inputs.maNguoiDung.addEventListener('input', checkStatus);
    if (inputs.fullname) inputs.fullname.addEventListener('input', checkStatus);
    if (inputs.email) inputs.email.addEventListener('input', checkStatus);
    if (inputs.role) inputs.role.addEventListener('change', checkStatus);

    // 7. Logic ẩn/hiện mật khẩu (Chỉ dùng cho trang Thêm mới)
    const toggleBtn = getEl('togglePassword');
    const passwordInput = getEl('password');
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // 8. Chốt chặn Submit
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            if (submitBtn && submitBtn.disabled) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Khởi tạo
    captureInitialState();
});