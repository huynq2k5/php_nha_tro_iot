<?php
namespace App\Repositories;

use App\Models\ThietBi;
use Config\KetNoi;

class ThietBiRepository {
    private $db;

    public function __construct() {
        $this->db = new KetNoi();
    }

    public function layTatCaThietBi() {
        $sql = "SELECT t.*, p.tenPhong 
                FROM thiet_bi t 
                LEFT JOIN phong p ON t.idPhong = p.idPhong
                ORDER BY t.idThietBi DESC";
        
        $kq = $this->db->truyVan($sql);

        $dsThietBi = [];
        if($kq && $kq->num_rows > 0) {
            while($row = $kq->fetch_assoc()) {
                $dsThietBi[] = new ThietBi($row);
            }
        }
        return $dsThietBi;
    }

    public function layThietBiTheoId($id) {
        $sql = "SELECT t.*, p.tenPhong
                FROM thiet_bi t 
                LEFT JOIN phong p ON t.idPhong = p.idPhong
                WHERE t.idThietBi = ?";
        
        $kq = $this->db->truyVan($sql, [$id]);
        
        if ($kq && $kq->num_rows > 0) {
            $row = $kq->fetch_assoc();
            return new ThietBi($row);
        }
        
        return null;
    }

    public function insertThietBi($data) {
        $sql = "INSERT INTO thiet_bi (idPhong, tenThietBi, loaiThietBi, diaChiIp, macAddress, topicMqtt, trangThaiKetNoi, firmwareVersion, ngayTao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        return $this->db->capNhat($sql, [
            $data['idPhong'],
            $data['tenThietBi'],
            $data['loaiThietBi'],
            $data['diaChiIp'] ?? null,
            $data['macAddress'] ?? null,
            $data['topicMqtt'] ?? null,
            $data['trangThaiKetNoi'] ?? 0,
            $data['firmwareVersion'] ?? null
        ]);
    }

    public function updateThietBi($id, $data) {
        $sql = "UPDATE thiet_bi 
                SET idPhong = ?, 
                    tenThietBi = ?, 
                    loaiThietBi = ?, 
                    diaChiIp = ?, 
                    macAddress = ?, 
                    topicMqtt = ?, 
                    trangThaiKetNoi = ?, 
                    firmwareVersion = ?, 
                    ngayCapNhat = NOW()
                WHERE idThietBi = ?";
        
        return $this->db->capNhat($sql, [
            $data['idPhong'],
            $data['tenThietBi'],
            $data['loaiThietBi'],
            $data['diaChiIp'],
            $data['macAddress'],
            $data['topicMqtt'],
            $data['trangThaiKetNoi'],
            $data['firmwareVersion'],
            $id
        ]);
    }

    public function deleteThietBi($id) {
        $sql = "DELETE FROM thiet_bi WHERE idThietBi = ?";
        return $this->db->capNhat($sql, [$id]);
    }

    public function timKiemThietBi($tuKhoa) {
        $sql = "SELECT t.*, p.tenPhong 
                FROM thiet_bi t 
                LEFT JOIN phong p ON t.idPhong = p.idPhong
                WHERE t.tenThietBi LIKE ? 
                   OR t.macAddress LIKE ? 
                   OR t.loaiThietBi LIKE ?
                ORDER BY t.idThietBi DESC";
        
        $tuKhoa = "%{$tuKhoa}%";
        $kq = $this->db->truyVan($sql, [$tuKhoa, $tuKhoa, $tuKhoa]);

        $dsThietBi = [];
        if($kq && $kq->num_rows > 0) {
            while($row = $kq->fetch_assoc()) {
                $dsThietBi[] = new ThietBi($row);
            }
        }
        return $dsThietBi;
    }

    public function layIdTuMacAddress($mac) {
        $sql = "SELECT idThietBi FROM thiet_bi WHERE macAddress = ? LIMIT 1";
        $kq = $this->db->truyVan($sql, [$mac]);
        if ($kq && $kq->num_rows > 0) {
            $row = $kq->fetch_assoc();
            return $row['idThietBi'];
        }
        return null;
    }
}