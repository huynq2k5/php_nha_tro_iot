<?php

namespace App\Controllers;

use App\Services\ThietBiService;
use App\Services\DuLieuCamBienService;
use App\Services\CamBienService;

class PhanTichController {
    private $thietBiService;
    private $duLieuService;
    private $camBienService;

    public function __construct()
    {
        $this->thietBiService = new ThietBiService();
        $this->duLieuService = new DuLieuCamBienService();
        $this->camBienService = new CamBienService();
    }

    public function hienThiTrangPhanTich() {
        return $this->thietBiService->hienThiTatCaThietBi();
    }

    public function apiLayDanhSachCamBien() {
        $idThietBi = $_GET['idThietBi'] ?? null;
        $dsCamBien = $this->camBienService->danhSachCamBienCuaThietBi($idThietBi);
        header('Content-Type: application/json');
        echo json_encode($dsCamBien);
        exit;
    }

    public function apiLayDuLieuBieuDo() {
        $idCamBien = $_GET['idCamBien'] ?? null;
        $period = $_GET['period'] ?? 'day';

        $data = $this->duLieuService->layDuLieuVeBieuDo($idCamBien, $period);

        $data['analysis'] = $this->duLieuService->phanTichChiSo(
            $data['values'], 
            $data['macd'], 
            $data['rsi']
        );

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}