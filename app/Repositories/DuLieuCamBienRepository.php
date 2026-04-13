<?php

namespace App\Repositories;

use App\Models\DuLieuCamBien;
use Config\KetNoi;

class DuLieuCamBienRepository {
    private $db;

    public function __construct() {
        $this->db = new KetNoi();
    }

    public function layLichSuTheoCamBien($idCamBien) {
        $sql = "SELECT * FROM du_lieu_cam_bien 
                WHERE idCamBien = ? 
                ORDER BY thoiGianDo DESC";
        
        $kq = $this->db->truyVan($sql, [$idCamBien]);

        $dsLichSu = [];
        if($kq && $kq->num_rows > 0) {
            while($row = $kq->fetch_assoc()) {
                $dsLichSu[] = new DuLieuCamBien($row);
            }
        }
        return $dsLichSu;
    }

    public function layLichSuMoiNhat($idCamBien) {
        $sql = "SELECT * FROM du_lieu_cam_bien 
                WHERE idCamBien = ? 
                ORDER BY thoiGianDo DESC 
                LIMIT 1";
        
        $kq = $this->db->truyVan($sql, [$idCamBien]);

        if($kq && $kq->num_rows > 0) {
            $row = $kq->fetch_assoc();
            return new DuLieuCamBien($row);
        }
        return null;
    }

    public function layDuLieuBieuDo($idCamBien, $period = 'day') {
        $dateCondition = "";
        $limit = 130;

        switch ($period) {
            case 'week':
                $dateCondition = "AND thoiGianDo >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                break;
            case 'month':
                $dateCondition = "AND thoiGianDo >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                break;
            default: 
                $dateCondition = "AND thoiGianDo >= CURDATE()";
                break;
        }

        $sqlCount = "SELECT COUNT(*) as tong FROM du_lieu_cam_bien WHERE idCamBien = ? $dateCondition";
        $kqCount = $this->db->truyVan($sqlCount, [$idCamBien]);
        $row = $kqCount->fetch_assoc();
        $tongRecords = $row['tong'];

        $step = ($tongRecords > $limit) ? ceil($tongRecords / $limit) : 1;

        $sql = "SELECT * FROM (
                    SELECT *, @row := @row + 1 AS rownum 
                    FROM du_lieu_cam_bien, (SELECT @row := 0) r
                    WHERE idCamBien = ? $dateCondition 
                    ORDER BY thoiGianDo ASC
                ) ranked
                WHERE rownum % $step = 0
                LIMIT $limit";
        
        $kq = $this->db->truyVan($sql, [$idCamBien]);
        $dsLichSu = [];
        if($kq && $kq->num_rows > 0) {
            while($row = $kq->fetch_assoc()) {
                $dsLichSu[] = new DuLieuCamBien($row);
            }
        }
        return $dsLichSu;
    }

    public function xoaLichSuTheoCamBien($idCamBien) {
        $sql = "DELETE FROM du_lieu_cam_bien WHERE idCamBien = ?";
        return $this->db->capNhat($sql, [$idCamBien]);
    }

    public function luuLichSu($data) {
        $sql = "INSERT INTO du_lieu_cam_bien (idCamBien, giaTri, thoiGianDo, trangThai) 
                VALUES (?, ?, ?, ?)";
        
        $thoiGianHienTai = date('Y-m-d H:i:s');
        return $this->db->capNhat($sql, [
            $data['idCamBien'],
            $data['giaTri'],
            $thoiGianHienTai,
            $data['trangThai'] ?? 1
        ]);
    }

    public function layGiaTriTrungBinhTheoLoai($idCamBien) {
        $sql = "SELECT AVG(giaTri) as giaTriTrungBinh, MAX(thoiGianDo) as thoiGian 
                FROM du_lieu_cam_bien 
                WHERE idCamBien = ? 
                AND thoiGianDo >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        
        $kq = $this->db->truyVan($sql, [$idCamBien]);
        if ($kq && $kq->num_rows > 0) {
            return $kq->fetch_assoc();
        }
        return ['giaTriTrungBinh' => 0, 'thoiGian' => null];
    }
}