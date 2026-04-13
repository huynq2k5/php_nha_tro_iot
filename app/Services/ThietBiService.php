<?php
namespace App\Services;

use App\Repositories\ThietBiRepository;

class ThietBiService {
    private $thietBiRepo;

    public function __construct()
    {
        $this->thietBiRepo = new ThietBiRepository();
    }

    public function hienThiTatCaThietBi()
    {
        return $this->thietBiRepo->layTatCaThietBi();
    }

    public function getThietBiById($id)
    {
        return $this->thietBiRepo->layThietBiTheoId($id);
    }

    public function themThietBi($data)
    {
        return $this->thietBiRepo->insertThietBi($data);
    }

    public function suaThietBi($id, $data)
    {
        $thietBi = $this->thietBiRepo->layThietBiTheoId($id);
        if (!$thietBi) {
            return -1;
        }
        return $this->thietBiRepo->updateThietBi($id, $data);
    }

    public function xoaThietBi($id)
    {
        $thietBi = $this->thietBiRepo->layThietBiTheoId($id);
        if (!$thietBi) {
            return -1;
        }
        return $this->thietBiRepo->deleteThietBi($id);
    }

    public function timKiemThietBi($tuKhoa)
    {
        if (empty($tuKhoa)) {
            return $this->hienThiTatCaThietBi();
        }
        return $this->thietBiRepo->timKiemThietBi($tuKhoa);
    }

    public function layIdThietBiTuMac($macAddress) {
        return $this->thietBiRepo->layIdTuMacAddress($macAddress);
    }
}