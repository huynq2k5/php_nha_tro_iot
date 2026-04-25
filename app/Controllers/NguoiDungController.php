<?php
namespace  App\Controllers;
use App\Services\UserService;
use App\Services\NhomService;
use App\Services\QuyenService;

class NguoiDungController{
    private $userService;
    private $nhomService;
    private $quyenService;
    public function __construct()
    {
        $this-> userService = new UserService();
        $this-> nhomService = new NhomService();
        $this-> quyenService = new QuyenService();
    }

    public function layDuLieuNguoiDung() {
        return $this->userService->hienThiUser();
    }
    public function layDuLieuNguoiDungBangId($id) {
        return $this->userService->getUserById($id);
    }

    public function layDuLieuNhom(){
        return $this->nhomService->hienThiDSNhom();
    }

    public function layDuLieuQuyen() {
        return $this->quyenService->hienthiDSQuyen();
    }

    public function layThongTinSua($id) {
        $user = $this->userService->getUserById($id);

        $danhSachNhom = $this->nhomService->hienThiDSNhom();

        return [
            'user' => $user,
            'danhSachNhom' => $danhSachNhom
        ];
    }


    public function layThongTinSuaNhom($id) {
        return $this->nhomService->getRoleById($id);
    }

    public function layThongTinSuaQuyen($id) {
        return $this->quyenService->getQuyenById($id);
    }

    public function layDSThanhVienNhom($id) {
        return $this->userService->getUserByIdRole($id);
    }

    public function layNguoiDungKhaDung($idNhom) {
        return $this->userService->getUserCoSan($idNhom);
    }

    public function htQuyenCuaNhom($id){
        return $quyenNhom = $this->quyenService->layQuyenDaGan($id);
    }

    public function apiTimKiemChucNang() {
        $tuKhoa = $_GET['q'] ?? '';
        
        $tatCaChucNang = [
            // Trang chính
            ['ten' => 'Trang chủ', 'url' => 'index.php?page=dashboard', 'quyen' => 'dashboard.view', 'mo_ta' => 'Bảng điều khiển chính'],
            ['ten' => 'Dashboard', 'url' => 'index.php?page=dashboard', 'quyen' => 'dashboard.view', 'mo_ta' => 'Tổng quan hệ thống'],
            
            // Thiết bị
            ['ten' => 'Quản lý thiết bị', 'url' => 'index.php?page=thietbi', 'quyen' => 'thietbi.view', 'mo_ta' => 'Danh sách thiết bị IoT'],
            ['ten' => 'Thêm thiết bị', 'url' => 'index.php?page=thietbi_them', 'quyen' => 'thietbi.view', 'mo_ta' => 'Thêm thiết bị mới'],
            ['ten' => 'Sửa thiết bị', 'url' => 'index.php?page=thietbi_sua', 'quyen' => 'thietbi.view', 'mo_ta' => 'Cập nhật thông tin thiết bị'],
            ['ten' => 'Xóa thiết bị', 'url' => 'index.php?page=thietbi_xoa', 'quyen' => 'thietbi.view', 'mo_ta' => 'Xóa thiết bị khỏi hệ thống'],
            
            // Tự động hóa
            ['ten' => 'Tự động hóa', 'url' => 'index.php?page=tudong', 'quyen' => 'tudong.view', 'mo_ta' => 'Quản lý kịch bản tự động'],
            ['ten' => 'Thêm kịch bản tự động', 'url' => 'index.php?page=tudong_them', 'quyen' => 'tudong.view', 'mo_ta' => 'Tạo kịch bản tự động mới'],
            ['ten' => 'Sửa kịch bản', 'url' => 'index.php?page=tudong_sua', 'quyen' => 'tudong.view', 'mo_ta' => 'Chỉnh sửa kịch bản tự động'],
            ['ten' => 'Quy tắc tự động', 'url' => 'index.php?page=tudong', 'quyen' => 'tudong.view', 'mo_ta' => 'Thiết lập quy tắc tự động'],
            
            // Phân tích & Báo cáo
            ['ten' => 'Phân tích dữ liệu', 'url' => 'index.php?page=phantich', 'quyen' => 'phantich.view', 'mo_ta' => 'Thống kê và biểu đồ'],
            
            // Cảnh báo
            ['ten' => 'Nhật ký cảnh báo', 'url' => 'index.php?page=alert_log', 'quyen' => 'canhbao.view', 'mo_ta' => 'Lịch sử cảnh báo'],
            
            // Quản lý người dùng
            ['ten' => 'Quản lý người dùng', 'url' => 'index.php?page=users', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Tài khoản và phân quyền'],
            ['ten' => 'Thêm người dùng', 'url' => 'index.php?page=nguoidung_them', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Tạo tài khoản mới'],
            ['ten' => 'Sửa người dùng', 'url' => 'index.php?page=nguoidung_sua', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Cập nhật thông tin'],
            ['ten' => 'Xóa người dùng', 'url' => 'index.php?page=nguoidung_xoa', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Xóa tài khoản'],
            ['ten' => 'Danh sách người dùng', 'url' => 'index.php?page=users', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Xem tất cả tài khoản'],
            
            // Quản lý nhóm
            ['ten' => 'Quản lý nhóm', 'url' => 'index.php?page=users', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Nhóm người dùng'],
            ['ten' => 'Thêm nhóm', 'url' => 'index.php?page=nhom_them', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Tạo nhóm mới'],
            ['ten' => 'Sửa nhóm', 'url' => 'index.php?page=nhom_sua', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Cập nhật thông tin nhóm'],
            ['ten' => 'Xóa nhóm', 'url' => 'index.php?page=nhom_xoa', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Xóa nhóm'],
            ['ten' => 'Thành viên nhóm', 'url' => 'index.php?page=nhom_sua', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Quản lý thành viên'],
            ['ten' => 'Chuyển nhóm', 'url' => 'index.php?page=nhom_chuyen_thanhvien', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Di chuyển người dùng'],
            
            // Quản lý quyền
            ['ten' => 'Quản lý quyền', 'url' => 'index.php?page=users', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Phân quyền hệ thống'],
            ['ten' => 'Thêm quyền', 'url' => 'index.php?page=quyen_them', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Tạo quyền mới'],
            ['ten' => 'Sửa quyền', 'url' => 'index.php?page=quyen_sua', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Cập nhật quyền'],
            ['ten' => 'Xóa quyền', 'url' => 'index.php?page=quyen_xoa', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Xóa quyền'],
            ['ten' => 'Phân quyền nhóm', 'url' => 'index.php?page=nhom_sua', 'quyen' => 'nguoidung.view', 'mo_ta' => 'Gán quyền cho nhóm'],
            
            // Hồ sơ cá nhân
            ['ten' => 'Hồ sơ cá nhân', 'url' => 'index.php?page=profile', 'quyen' => 'all', 'mo_ta' => 'Thông tin tài khoản'],
        
        ];

        $ketQua = [];
        foreach ($tatCaChucNang as $item) {
            $khopTuKhoa = stripos($item['ten'], $tuKhoa) !== false;
            
            $coQuyen = ($item['quyen'] === 'all' || hasPermission($item['quyen']));

            if ($khopTuKhoa && $coQuyen) {
                $ketQua[] = [
                    'ten' => $item['ten'],
                    'url' => $item['url']
                ];
            }
        }

        header('Content-Type: application/json');
        echo json_encode(array_slice($ketQua, 0, 5));
        exit;
    }

    public function webThemNguoiDung() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'maNguoiDung' => $_POST['maNguoiDung'] ?? null,
                'tenDangNhap' => $_POST['tenDangNhap'] ?? null,
                'hoTen'       => $_POST['hoTen'] ?? null,
                'idNhom'      => $_POST['idNhom'] ?? null,
                'matKhau'     => $_POST['matKhau'] ?? '12345678'
            ];

            $kq = $this->userService->themUser($data);

            $_SESSION['msg'] = $kq ? 'add_success' : 'add_error';
            header('Location: index.php?page=users');
            exit;
        }
    }

    public function webSuaNguoiDung() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['idNguoiDung'] ?? null;
            $data = [
                'tenDangNhap' => $_POST['tenDangNhap'] ?? null,
                'hoTen'       => $_POST['hoTen'] ?? null,
                'idNhom'      => $_POST['idNhom'] ?? null
            ];

            $kq = $this->userService->suaUser($id, $data);

            $_SESSION['msg'] = ($kq !== false) ? 'edit_success' : 'edit_error';
            
            if ($kq !== false) {
                header('Location: index.php?page=users');
            } else {
                header('Location: index.php?page=nguoidung_sua&id=' . $id);
            }
            exit;
        }
    }

    public function webXoaNguoiDung() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $kq = $this->userService->xoaUser($id);
            $_SESSION['msg'] = $kq ? 'del_success' : 'del_error';
            header('Location: index.php?page=users');
            exit;
        }
    }

    public function webResetPass(){
        $id = $_GET['id'] ?? null;
        if($id){
            $kq = $this->userService->thucHienResetPass($id);
            $_SESSION['msg'] = $kq ? 'res_thanhcong' : 'res_thatbai';
            header('Location: index.php?page=users');
            exit;
        }
    }

    public function webCapNhatThongTinCaNhan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'] ?? null; 
            
            if ($id) {
                $userCu = $this->userService->getUserById($id);
                
                $data = [
                    'hoTen'       => $_POST['hoTen'] ?? $userCu->hoTen,
                    'tenDangNhap' => $userCu->tenDangNhap,
                    'idNhom'      => $userCu->idNhom
                ];

                $kq = $this->userService->suaUser($id, $data);

                $_SESSION['msg'] = ($kq !== false) ? 'edit_success' : 'edit_error';
            }
            header('Location: index.php?page=profile');
            exit;
        }
    }

    public function webDoiMatKhauCaNhan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'] ?? null;
            $matKhauCu = $_POST['old_password'] ?? '';
            $matKhauMoi = $_POST['new_password'] ?? '';
            $xacNhanMatKhau = $_POST['confirm_password'] ?? '';

            if ($matKhauMoi !== $xacNhanMatKhau) {
                $_SESSION['msg'] = 'pass_ko_khop'; 
                header('Location: index.php?page=profile');
                exit;
            }

            $kq = $this->userService->doiMatKhau($id, $matKhauCu, $matKhauMoi);

            if ($kq === true) {
                $_SESSION['msg'] = 'edit_success';
            } else {
                $_SESSION['msg'] = 'pass_sai'; 
            }

            header('Location: index.php?page=profile');
            exit;
        }
    }

    public function webChuyenNhom() {
        $idNguoiDung = $_GET['idNguoiDung'] ?? null;
        $idNhomMoi = $_GET['idNhom'] ?? null;
    
        if ($idNguoiDung && $idNhomMoi) {
            $kq = $this->userService->chuyenNhomMoi($idNguoiDung, $idNhomMoi);
            
            $_SESSION['msg'] = $kq ? 'edit_success' : 'edit_error';
            
            $idNhomHienTai = $_GET['idNhomCu'] ?? $idNhomMoi; 
            header("Location: index.php?page=nhom_sua&id=" . $idNhomHienTai);
        } else {
            $_SESSION['msg'] = 'dulieu_khonghople';
            header('Location: index.php?page=users');
        }
        exit;
    }

    public function webSuaNhom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['idNhom'] ?? null;
            $data = [
                'tenNhom' => $_POST['tenNhom'] ?? null,
                'moTa' => $_POST['moTa'] ?? null,
            ];
            
            $permissionIds = $_POST['permissions'] ?? [];
            
            $newMemberIds = $_POST['new_members'] ?? [];

            $kqGroup = $this->nhomService->capNhatNhomVaQuyen($id, $data, $permissionIds);

            $kqMembers = true;
            if (!empty($newMemberIds)) {
                foreach ($newMemberIds as $userId) {
                    if (!$this->userService->chuyenNhomMoi($userId, $id)) {
                        $kqMembers = false;
                    }
                }
            }

            $_SESSION['msg'] = ($kqGroup && $kqMembers) ? 'edit_success' : 'edit_error';
            
            header("Location: index.php?page=nhom_sua&id=" . $id);
            exit;
        }
    }

    public function webXoaNhom() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $kq = $this->nhomService->xoaNhom($id);
            $_SESSION['msg'] = $kq ? 'del_success' : 'del_error';
            header('Location: index.php?page=users&tab=groups');
            exit;
        }
    }

    public function webThemNhom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'maNhom' => $_POST['maNhom'] ?? null,
                'tenNhom' => $_POST['tenNhom'] ?? null,
                'moTa'       => $_POST['moTa'] ?? null,
            ];

            $kq = $this->nhomService->themNhom($data);

            $_SESSION['msg'] = $kq ? 'add_success' : 'add_error';
            header('Location: index.php?page=users&tab=groups');
            exit;
        }
    }

    public function webThemQuyen() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'maQuyen' => $_POST['maQuyen'] ?? null,
                'tenQuyen' => $_POST['tenQuyen'] ?? null
            ];

            $kq = $this->quyenService->themQuyen($data);

            $_SESSION['msg'] = $kq ? 'add_success' : 'add_error';
            header('Location: index.php?page=users&tab=permissions');
            exit;
        }
    }

    public function webSuaQuyen() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['idQuyen'] ?? null;
            $data = [
                'tenQuyen' => $_POST['tenQuyen'] ?? null
            ];

            $kq = $this->quyenService->suaQuyen($id, $data);

            $_SESSION['msg'] = ($kq !== false) ? 'edit_success' : 'edit_error';
            
            if ($kq !== false) {
                header('Location: index.php?page=users');
            } else {
                header('Location: index.php?page=quyen_sua&id=' . $id);
            }
            exit;
        }
    }

    public function webXoaQuyen() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $kq = $this->quyenService->xoaQuyen($id);
            $_SESSION['msg'] = $kq ? 'del_success' : 'del_error';
            header('Location: index.php?page=users&tab=permissions');
            exit;
        }
    }

    public function apiTimKiem() {
        $keyword = $_GET['keyword'] ?? '';
        $role = $_GET['role'] ?? ''; 

        $danhSachNguoiDung = $this->userService->timKiemNguoiDung($keyword, $role);
        
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($danhSachNguoiDung);
            exit;
        }

        return $danhSachNguoiDung;
    }
}
?>
