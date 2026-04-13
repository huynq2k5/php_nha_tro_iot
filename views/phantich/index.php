<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div x-data="{ activeTab: 'realtime' }">

    <div class="flex flex-col items-start justify-between w-full gap-4 my-6 sm:flex-row sm:items-center">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Phân tích dữ liệu
        </h2>
        
        <div class="flex flex-wrap w-full gap-3 sm:w-auto sm:flex-nowrap">
            <select id="deviceFilter" class="block w-full text-sm rounded-md border-gray-300 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-select sm:w-40">
                <?php foreach ($danhSachThietBi as $index => $tb): ?>
                    <option value="<?= $tb->idThietBi ?>" <?= $index === 0 ? 'selected' : '' ?>>
                        <?= $tb->tenThietBi ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select id="globalSensorType" class="block w-full text-sm rounded-md border-gray-300 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-select sm:w-32"></select>

            <select id="periodFilter" class="block w-full text-sm rounded-md border-gray-300 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-select sm:w-32">
                <option value="day">Hôm nay</option>
                <option value="week">7 ngày qua</option>
                <option value="month">Tháng này</option>
            </select>

            <button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                <i class="fas fa-filter mr-1"></i> Lọc
            </button>
        </div>
    </div>

    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <button @click="activeTab = 'realtime'" 
                        :class="{ 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500': activeTab === 'realtime', 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': activeTab !== 'realtime' }"
                        class="inline-block p-4 border-b-2 rounded-t-lg focus:outline-none transition-colors duration-150">
                    <i class="fas fa-chart-line me-2"></i> Giám sát Thời gian thực
                </button>
            </li>
            <li class="mr-2">
                <button @click="activeTab = 'advanced'" 
                        :class="{ 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500': activeTab === 'advanced', 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': activeTab !== 'advanced' }"
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg focus:outline-none transition-colors duration-150">
                    <i class="fas fa-project-diagram me-2"></i> Phân tích chỉ số
                </button>
            </li>
        </ul>
    </div>

    <div x-show="activeTab === 'realtime'" x-transition>
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border dark:border-gray-700">
            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">Biến thiên dữ liệu theo chu kỳ</h4>
            <div class="relative w-full" style="height: 400px;">
                <canvas id="realtimeChart"></canvas>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'advanced'" x-transition style="display: none;">
        <div class="min-w-0 p-4 mb-8 bg-white rounded-lg shadow-xs dark:bg-gray-800 border dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-semibold text-gray-800 dark:text-gray-300">
                    1. Chỉ số MACD (Xu hướng biến động)
                </h4>
                
            </div>
            <div class="relative w-full" style="height: 350px;">
                <canvas id="macdChart"></canvas>
            </div>
            <div class="flex gap-4 mt-2 justify-center text-[10px] font-bold">
                <span class="text-blue-600"><i class="fas fa-minus mr-1"></i> MACD Line</span>
                <span class="text-gray-400"><i class="fas fa-minus mr-1"></i> Signal Line</span>
                <span class="text-blue-300"><i class="fas fa-columns mr-1"></i> Histogram</span>
            </div>
        </div>

        <div class="min-w-0 p-4 mb-8 bg-white rounded-lg shadow-xs dark:bg-gray-800 border dark:border-gray-700">
            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">Chỉ số RSI (Mức độ biến động)</h4>
            <div class="relative w-full" style="height: 200px;">
                <canvas id="rsiChart"></canvas>
            </div>
        </div>
        
        <div class="grid gap-6 mb-8 md:grid-cols-2">
    
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 border dark:border-gray-700">
                <h4 class="mb-4 font-semibold text-gray-700 dark:text-gray-200 text-lg">
                    Đánh giá hệ thống
                </h4>
                
                <div id="eval-alert-box" class="p-3 mb-4 text-sm rounded-lg border-l-4">
                    <div class="flex items-center font-bold mb-1">
                        <i class="fas fa-microchip mr-2"></i> Trạng thái phân tích
                    </div>
                    <p id="eval-description">Đang phân tích dữ liệu...</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Xu hướng (MACD):</span>
                        <span id="eval-trend-val" class="font-bold">--</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Cường độ (RSI):</span>
                        <span id="eval-rsi-val" class="px-2 py-1 font-semibold leading-tight rounded-full">--</span>
                    </div>
                </div>
            </div>

            <div id="suggestion-card" class="min-w-0 p-4 text-white rounded-lg shadow-xs transition-colors duration-300">
                <h4 class="mb-4 font-semibold text-white text-lg">
                    Gợi ý tối ưu vận hành
                </h4>
                
                <p id="suggestion-text" class="mb-4 text-white text-sm opacity-90">
                    Hệ thống đang thu thập thêm dữ liệu để đưa ra gợi ý chính xác nhất.
                </p>

                <div class="flex items-center justify-between p-4 bg-black bg-opacity-20 rounded-lg border border-white border-opacity-20">
                    <div class="text-center w-full">
                        <p class="text-xs font-bold text-white uppercase tracking-wide opacity-80">Thông tin phản hồi</p>
                        <p id="suggestion-brief" class="text-lg font-bold text-white">Chế độ tự động</p>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deviceFilter = document.getElementById('deviceFilter');
        const sensorFilter = document.getElementById('globalSensorType');
        const periodFilter = document.getElementById('periodFilter');
        const filterBtn = document.querySelector('button i.fa-filter').parentElement;

        let realtimeChart, macdChart, rsiChart, stabilityChart;

        function initCharts() {
            const commonOptions = { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } };

            realtimeChart = new Chart(document.getElementById('realtimeChart'), {
                type: 'line',
                data: { 
                    labels: [], 
                    datasets: [{ 
                        label: 'Giá trị cảm biến', 
                        data: [], 
                        borderColor: '#dc2626', 
                        backgroundColor: 'rgba(220, 38, 38, 0.1)', 
                        fill: true, 
                        tension: 0,
                        stepped: true 
                    }] 
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    if (value === 1) return 'Có người (1)';
                                    if (value === 0) return 'Trống (0)';
                                    return '';
                                }
                            },
                            min: 0,
                            max: 1.1
                        }
                    }
                }
            });

            macdChart = new Chart(document.getElementById('macdChart'), {
                type: 'bar',
                data: { labels: [], datasets: [
                    { label: 'MACD', type: 'line', data: [], borderColor: '#dc2626', borderWidth: 2, pointRadius: 0, tension: 0.3 },
                    { label: 'Signal', type: 'line', data: [], borderColor: '#9ca3af', borderDash: [5, 5], pointRadius: 0, tension: 0.3 },
                    { label: 'Histogram', data: [], backgroundColor: [] }
                ]},
                options: { ...commonOptions, scales: { x: { stacked: true } } }
            });

            rsiChart = new Chart(document.getElementById('rsiChart'), {
                type: 'line',
                data: { labels: [], datasets: [{ label: 'Chỉ số RSI', data: [], borderColor: '#9333ea', borderWidth: 2, pointRadius: 0, tension: 0.3 }] },
                options: { ...commonOptions, scales: { y: { min: 0, max: 100 } } }
            });
        }

        async function loadSensors(idThietBi) {
            if (!idThietBi) return;
            try {
                const response = await fetch(`index.php?page=api_lay_cam_bien&idThietBi=${idThietBi}`);
                const sensors = await response.json();

                sensorFilter.innerHTML = '';
                sensors.forEach((item, index) => {
                    const option = document.createElement('option');
                    option.value = item.idCamBien; 
                    option.textContent = item.tenCamBien; 
                    if (index === 0) option.selected = true;
                    sensorFilter.appendChild(option);
                });

                updateDashboard();
            } catch (error) {
                console.error("Lỗi tải cảm biến:", error);
            }
        }

        async function updateDashboard() {
            const idTB = deviceFilter.value;
            const idCB = sensorFilter.value;
            const period = periodFilter.value;

            if (!idTB || !idCB) return;

            filterBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch(`index.php?page=phantich_api_data&idCamBien=${idCB}&period=${period}`);
                const res = await response.json();

                [realtimeChart, macdChart, rsiChart].forEach(chart => {
                    if (chart) {
                        chart.data.labels = res.labels;
                    }
                });

                if (realtimeChart) {
                    const sensorName = sensorFilter.options[sensorFilter.selectedIndex].text.toLowerCase();
                    // Kiểm tra nếu là cảm biến Digital (0/1)
                    const isDigital = sensorName.includes('chuyển động') || sensorName.includes('pir') || sensorName.includes('nút');

                    // 1. Cập nhật nhãn và dữ liệu
                    realtimeChart.data.datasets[0].label = sensorFilter.options[sensorFilter.selectedIndex].text;
                    realtimeChart.data.datasets[0].data = res.values;

                    // 2. Chuyển đổi linh hoạt giữa đường cong (Analog) và bậc thang (Digital)
                    realtimeChart.data.datasets[0].stepped = isDigital;
                    realtimeChart.data.datasets[0].tension = isDigital ? 0 : 0.4; // Đường cong cho Gas/Nhiệt độ, vuông góc cho PIR

                    // 3. Cấu hình lại trục Y dựa trên loại dữ liệu
                    if (isDigital) {
                        realtimeChart.options.scales.y.min = 0;
                        realtimeChart.options.scales.y.max = 1.1;
                        realtimeChart.options.scales.y.ticks.stepSize = 1;
                        realtimeChart.options.scales.y.ticks.display = true; // Hiện nhãn Trống/Có người
                    } else {
                        // Reset về mặc định để Chart.js tự tính toán thang đo cho Gas, Nhiệt độ...
                        realtimeChart.options.scales.y.min = undefined;
                        realtimeChart.options.scales.y.max = undefined;
                        realtimeChart.options.scales.y.ticks.stepSize = undefined;
                        // Trả lại nhãn số bình thường cho trục Y
                        realtimeChart.options.scales.y.ticks.callback = function(value) {
                            return value; 
                        };
                    }

                    realtimeChart.update();
                }

                if (macdChart) {
                    macdChart.data.datasets[0].data = res.macd.macd;
                    macdChart.data.datasets[1].data = res.macd.signal;
                    macdChart.data.datasets[2].data = res.macd.histogram;
                    macdChart.data.datasets[2].backgroundColor = res.macd.histogram.map(v => v >= 0 ? 'rgba(220, 38, 38, 0.3)' : 'rgba(156, 163, 175, 0.3)');
                    macdChart.update();
                }

                if (rsiChart) {
                    rsiChart.data.datasets[0].data = res.rsi;
                    rsiChart.update();
                }

                updateUIAnalysis(res);

            } catch (error) {
                console.error("Lỗi fetch dữ liệu:", error);
            } finally {
                filterBtn.innerHTML = '<i class="fas fa-filter mr-1"></i> Lọc';
            }
        }

        function updateUIAnalysis(res) {
            const analysis = res.analysis;
            if (!analysis) return;

            const evalDesc = document.getElementById('eval-description');
            const evalAlert = document.getElementById('eval-alert-box');
            const trendVal = document.getElementById('eval-trend-val');
            const rsiVal = document.getElementById('eval-rsi-val');

            if (evalDesc) evalDesc.textContent = analysis.evaluation;
            if (evalAlert) evalAlert.className = `p-3 mb-4 text-sm rounded-lg border-l-4 ${analysis.bgClass} bg-opacity-20 ${analysis.statusClass} border-current`;
            if (trendVal) {
                trendVal.textContent = analysis.trendText;
                trendVal.className = `font-bold ${analysis.statusClass}`;
            }
            if (rsiVal) {
                rsiVal.textContent = analysis.lastRSI;
                rsiVal.className = `px-2 py-1 font-semibold leading-tight rounded-full ${analysis.bgClass} text-white`;
            }

            const suggestCard = document.getElementById('suggestion-card');
            const suggestText = document.getElementById('suggestion-text');
            const suggestBrief = document.getElementById('suggestion-brief');

            if (suggestCard) suggestCard.className = `min-w-0 p-4 text-white rounded-lg shadow-xs transition-colors duration-300 ${analysis.bgClass}`;
            if (suggestText) suggestText.textContent = analysis.suggestion;
            if (suggestBrief) {
                if (analysis.lastRSI > 70) suggestBrief.textContent = "Cần kiểm tra thiết bị";
                else if (analysis.lastRSI < 30) suggestBrief.textContent = "Biến động giảm mạnh";
                else suggestBrief.textContent = "Vận hành ổn định";
            }
        }

        initCharts();

        deviceFilter.addEventListener('change', () => loadSensors(deviceFilter.value));
        sensorFilter.addEventListener('change', updateDashboard);
        periodFilter.addEventListener('change', updateDashboard);
        filterBtn.addEventListener('click', updateDashboard);

        if (deviceFilter.value) loadSensors(deviceFilter.value);
    });
</script>