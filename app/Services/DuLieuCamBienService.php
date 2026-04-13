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
            'evaluation' => 'Đang thu thập dữ liệu phân tích...',
            'suggestion' => 'Vui lòng chờ thiết bị cập nhật thêm dữ liệu.',
            'statusClass' => 'text-gray-400',
            'bgClass' => 'bg-gray-500',
            'lastRSI' => '--',
            'trendText' => '--',
            'momentum' => '--'
        ];

        if (empty($macdData['macd']) || empty($rsiData) || count($values) < 2) {
            return $default;
        }

        $lastRSI = end($rsiData);
        $lastMACD = end($macdData['macd']);
        $lastSignal = end($macdData['signal']);
        $lastHist = end($macdData['histogram']);

        $isBullish = $lastMACD > $lastSignal;
        $trend = $isBullish ? "tăng" : "giảm";
        $momentum = abs($lastHist) > 0.1 ? "mạnh" : "yếu";

        if ($lastRSI > 70) {
            $statusClass = "text-red-600";
            $bgClass = "bg-red-600";
            $evaluation = "Chỉ số đang tăng cao đột biến (RSI: " . round($lastRSI) . ").";
            $suggestion = "Có biến động lớn trong phòng, hãy kiểm tra thiết bị liên quan.";
        } elseif ($lastRSI < 30) {
            $statusClass = "text-blue-600";
            $bgClass = "bg-blue-600";
            $evaluation = "Chỉ số đang giảm sâu (RSI: " . round($lastRSI) . ").";
            $suggestion = "Môi trường đang thay đổi nhanh theo chiều hướng giảm.";
        } else {
            $statusClass = "text-green-600";
            $bgClass = "bg-green-600";
            $evaluation = "Trạng thái ổn định (RSI: " . round($lastRSI) . ").";
            $suggestion = "Môi trường nhà trọ đang duy trì ở mức bình thường.";
        }

        return [
            'evaluation' => $evaluation,
            'suggestion' => $suggestion,
            'statusClass' => $statusClass,
            'bgClass' => $bgClass,
            'lastRSI' => round($lastRSI),
            'trendText' => $trend,
            'momentum' => $momentum
        ];
    }
}