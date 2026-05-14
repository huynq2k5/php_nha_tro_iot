<?php

namespace App\Services;

use App\Repositories\DuLieuCamBienRepository;

class DuLieuCamBienService {
    private $duLieuRepo;

    public function __construct()
    {
        $this->duLieuRepo = new DuLieuCamBienRepository();
    }

    public function layTatCaLichSu($idCamBien)
    {
        return $this->duLieuRepo->layLichSuTheoCamBien($idCamBien);
    }

    public function layThongSoMoiNhat($idCamBien)
    {
        return $this->duLieuRepo->layLichSuMoiNhat($idCamBien);
    }

    public function xoaToanBoLichSu($idCamBien)
    {
        return $this->duLieuRepo->xoaLichSuTheoCamBien($idCamBien);
    }

    private function calculateEMA($data, $period) {
        if (empty($data)) return [];
        $k = 2 / ($period + 1);
        $ema = [$data[0]];
        for ($i = 1; $i < count($data); $i++) {
            $ema[] = $data[$i] * $k + $ema[$i - 1] * (1 - $k);
        }
        return $ema;
    }

    public function getMACD($data) {
        $ema12 = $this->calculateEMA($data, 12);
        $ema26 = $this->calculateEMA($data, 26);
        
        $macdLine = [];
        foreach ($ema12 as $i => $val) {
            $macdLine[] = $val - $ema26[$i];
        }
        
        $signalLine = $this->calculateEMA($macdLine, 9);
        $histogram = [];
        foreach ($macdLine as $i => $val) {
            $histogram[] = $val - ($signalLine[$i] ?? 0);
        }

        return [
            'macd' => $macdLine,
            'signal' => $signalLine,
            'histogram' => $histogram
        ];
    }

    public function getRSI($data, $period = 14) {
        if (count($data) < $period) return array_fill(0, count($data), 50);
        
        $rsi = [];
        for ($i = 0; $i < count($data); $i++) {
            if ($i < $period) {
                $rsi[] = 50; 
                continue;
            }
            
            $slice = array_slice($data, $i - $period, $period);
            $gains = 0; $losses = 0;
            for ($j = 1; $j < count($slice); $j++) {
                $diff = $slice[$j] - $slice[$j-1];
                if ($diff > 0) $gains += $diff; else $losses += abs($diff);
            }
            
            $rs = ($losses == 0) ? 100 : ($gains / $losses);
            $rsi[] = 100 - (100 / (1 + $rs));
        }
        return $rsi;
    }

    public function layDuLieuVeBieuDo($idCamBien, $period) {
        $rawDataObjects = $this->duLieuRepo->layDuLieuBieuDo($idCamBien, $period);
        
        $labels = [];
        $values = [];

        foreach ($rawDataObjects as $item) {
            if ($period === 'week') {
                $labels[] = date('d/m H:i', strtotime($item->thoiGianDo)); 
            } elseif ($period === 'month') {
                $labels[] = date('d/m', strtotime($item->thoiGianDo)); 
            } else {
                $labels[] = date('H:i', strtotime($item->thoiGianDo)); 
            }
            
            $values[] = (float)$item->giaTri;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'macd' => $this->getMACD($values),
            'rsi' => $this->getRSI($values)
        ];
    }

    public function phanTichTrangThai($loai, $giaTri) {
        switch ($loai) {
            case 'temp':
                if ($giaTri > 35) return ['text' => 'Quá nóng - Cần bật quạt/điều hòa', 'class' => 'text-red-600'];
                if ($giaTri < 18) return ['text' => 'Quá lạnh', 'class' => 'text-blue-500'];
                return ['text' => 'Nhiệt độ phòng lý tưởng', 'class' => 'text-green-600'];

            case 'hum':
                if ($giaTri > 80) return ['text' => 'Độ ẩm cao - Dễ ẩm mốc tường', 'class' => 'text-red-500'];
                if ($giaTri < 35) return ['text' => 'Không khí khô', 'class' => 'text-orange-600'];
                return ['text' => 'Độ ẩm dễ chịu', 'class' => 'text-green-600'];

            case 'co2':
                if ($giaTri > 1500) return ['text' => 'Không khí bí - Cần mở cửa sổ', 'class' => 'text-red-700'];
                if ($giaTri > 800) return ['text' => 'Nồng độ CO2 tăng nhẹ', 'class' => 'text-orange-500'];
                return ['text' => 'Không khí trong lành', 'class' => 'text-teal-600'];

            case 'light':
                if ($giaTri > 200) return ['text' => 'Đang bật đèn hoặc có nắng', 'class' => 'text-orange-500'];
                return ['text' => 'Phòng đang tối/Tắt đèn', 'class' => 'text-gray-500'];

            default:
                return ['text' => 'Đang theo dõi', 'class' => 'text-gray-400'];
        }
    }

    public function phanTichChiSo($values, $macdData, $rsiData) {
        $default = [
            'evaluation' => 'Đang thu thập dữ liệu...',
            'suggestion' => 'Vui lòng chờ thiết bị cập nhật thêm dữ liệu.',
            'statusClass' => 'text-gray-400',
            'bgClass' => 'bg-gray-500',
            'lastRSI' => '--',
            'trendText' => '--',
            'momentum' => '--'
        ];

        if (empty($macdData['macd']) || empty($rsiData) || count($values) < 26) {
            return $default;
        }

        $lastRSI = end($rsiData);
        $lastMACD = end($macdData['macd']);
        $lastSignal = end($macdData['signal']);
        $lastHist = end($macdData['histogram']);

        $isBullish = $lastMACD > $lastSignal;
        $trend = $isBullish ? "tăng" : "giảm";
        $momentum = abs($lastHist) > 0.1 ? "mạnh" : "yếu";

        if ($lastHist > 0 && $lastRSI >= 80) {
            $statusClass = "text-red-700 font-bold";
            $bgClass = "bg-red-700";
            $evaluation = "KHẨN CẤP";
            $suggestion = "Sự cố rò rỉ hoặc quá nhiệt đang diễn ra mạnh. Ngắt nguồn điện và sơ tán ngay.";
        } elseif ($isBullish && ($lastRSI >= 70 && $lastRSI < 80)) {
            $statusClass = "text-orange-600 font-bold";
            $bgClass = "bg-orange-500";
            $evaluation = "CẢNH BÁO SỚM";
            $suggestion = "Nguy cơ sự cố trong tương lai gần. Hãy kiểm tra các van khí và thiết bị nhiệt.";
        } elseif ($isBullish || $lastRSI > 70) {
            $statusClass = "text-yellow-600";
            $bgClass = "bg-yellow-500";
            $evaluation = "THEO DÕI SÁT SAO";
            $suggestion = "Phát hiện xu hướng tăng bất thường. Nhân viên kỹ thuật cần lưu ý giám sát.";
        } else {
            $statusClass = "text-green-600";
            $bgClass = "bg-green-600";
            $evaluation = "BÌNH THƯỜNG";
            $suggestion = "Môi trường ổn định, các chỉ số đang trong ngưỡng an toàn.";
        }

        return [
            'evaluation' => $evaluation,
            'suggestion' => $suggestion,
            'statusClass' => $statusClass,
            'bgClass' => $bgClass,
            'lastRSI' => round($lastRSI, 2),
            'trendText' => $trend,
            'momentum' => $momentum,
            'histogram' => $lastHist
        ];
    }
}