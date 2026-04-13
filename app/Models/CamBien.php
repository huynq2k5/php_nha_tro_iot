<?php
namespace App\Models;

class CamBien
{
    public $idCamBien;
    public $idThietBi;
    public $tenCamBien;
    public $loaiCamBien;
    public $donVi;
    public $trangThai;
    public $ngayTao;

    public function __construct($data = [])
    {
        $this->idCamBien = $data['idCamBien'] ?? null;
        $this->idThietBi = $data['idThietBi'] ?? null;
        $this->tenCamBien = $data['tenCamBien'] ?? null;
        $this->loaiCamBien = $data['loaiCamBien'] ?? null;
        $this->donVi = $data['donVi'] ?? null;
        $this->trangThai = $data['trangThai'] ?? 1;
        $this->ngayTao = $data['ngayTao'] ?? null;
    }
}
?>