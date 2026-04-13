<?php
namespace App\Services;

use App\Repositories\CamBienRepository;

class CamBienService
{
    private $camBienRepo;

    public function __construct()
    {
        $this->camBienRepo = new CamBienRepository();
    }

    public function danhSachCamBienCuaThietBi($idThietBi)
    {
        return $this->camBienRepo->layCamBienTheoThietBi($idThietBi);
    }
}
?>