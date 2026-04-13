<?php

namespace App\Models;

class DuLieuCamBien
{
    public $idDuLieu;
    public $idCamBien;
    public $giaTri;
    public $thoiGianDo;
    public $trangThai;

    public function __construct($data = [])
    {
        $this->idDuLieu = $data['idDuLieu'] ?? null;
        $this->idCamBien = $data['idCamBien'] ?? null;
        $this->giaTri = $data['giaTri'] ?? 0.0;
        $this->thoiGianDo = $data['thoiGianDo'] ?? null;
        $this->trangThai = $data['trangThai'] ?? 1;
    }
}