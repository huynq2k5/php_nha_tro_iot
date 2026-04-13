<?php
namespace App\Repositories;

use App\Models\CamBien;
use Config\KetNoi;

class CamBienRepository
{
    private $db;

    public function __construct()
    {
        $this->db = new KetNoi();
    }

    public function layCamBienTheoThietBi($idThietBi)
    {
        $sql = "SELECT * FROM cam_bien WHERE idThietBi = ? ORDER BY idCamBien ASC";
        $kq = $this->db->truyVan($sql, [$idThietBi]);
        $ds = [];
        if ($kq && $kq->num_rows > 0) {
            while ($row = $kq->fetch_assoc()) {
                $ds[] = new CamBien($row);
            }
        }
        return $ds;
    }

    public function layCamBienTheoId($idCamBien)
    {
        $sql = "SELECT * FROM cam_bien WHERE idCamBien = ?";
        $kq = $this->db->truyVan($sql, [$idCamBien]);
        if ($kq && $kq->num_rows > 0) {
            return new CamBien($kq->fetch_assoc());
        }
        return null;
    }
}
?>