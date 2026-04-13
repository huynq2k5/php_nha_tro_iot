<?php

namespace App\Models;

class ThietBi
{
    public $idThietBi;
    public $idPhong;
    public $tenThietBi;
    public $loaiThietBi;
    public $diaChiIp;
    public $macAddress;
    public $topicMqtt;
    public $trangThaiKetNoi;
    public $lastHeartbeat;
    public $firmwareVersion;
    public $ngayTao;
    public $ngayCapNhat;

    public function __construct($data = [])
    {
        $this->idThietBi = $data['idThietBi'] ?? null;
        $this->idPhong = $data['idPhong'] ?? null;
        $this->tenThietBi = $data['tenThietBi'] ?? null;
        $this->loaiThietBi = $data['loaiThietBi'] ?? null;
        $this->diaChiIp = $data['diaChiIp'] ?? null;
        $this->macAddress = $data['macAddress'] ?? null;
        $this->topicMqtt = $data['topicMqtt'] ?? null;
        $this->trangThaiKetNoi = $data['trangThaiKetNoi'] ?? 0;
        $this->lastHeartbeat = $data['lastHeartbeat'] ?? null;
        $this->firmwareVersion = $data['firmwareVersion'] ?? null;
        $this->ngayTao = $data['ngayTao'] ?? null;
        $this->ngayCapNhat = $data['ngayCapNhat'] ?? null;
    }

    public function isOnline()
    {
        return $this->trangThaiKetNoi == 1;
    }
}