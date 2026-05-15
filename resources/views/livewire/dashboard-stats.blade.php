<div class="p-6 space-y-6" style="font-family: 'Inter', sans-serif;">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ now()->format('l, d F Y') }}</p>
        </div>
        @if(!auth()->user()->is_admin)
        <a href="{{ route('pos') }}" wire:navigate
            class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 active:scale-95 shadow-md shadow-orange-200 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 7h.01M15 7h.01M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4M9 21v-4a2 2 0 012-2h2a2 2 0 012 2v4"></path></svg>
            Buka Kasir
        </a>
        @endif
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Omzet Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:-translate-y-[1px] transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Omzet Hari Ini</span>
                <div class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total pendapatan hari ini</p>
        </div>

        {{-- Transaksi Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:-translate-y-[1px] transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Transaksi Hari Ini</span>
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $todayOrdersCount }} <span class="text-base font-medium text-gray-400">order</span></p>
            <p class="text-xs text-gray-400 mt-1">Jumlah pesanan selesai</p>
        </div>

        {{-- Omzet Bulan Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:-translate-y-[1px] transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Omzet Bulan Ini</span>
                <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Akumulasi bulan {{ now()->format('F') }}</p>
        </div>
    </div>

    {{-- Chart + Top Products --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Grafik Penjualan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">7 hari terakhir</p>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-gray-400">
                    <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                    Omzet Harian
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-base font-bold text-gray-900 mb-4">🏆 Menu Terlaris</h3>
            <ul class="space-y-3">
                @forelse($topProducts as $index => $item)
                    <li class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-6 h-6 rounded-lg flex-shrink-0 flex items-center justify-center text-xs font-black
                                {{ $index === 0 ? 'bg-amber-100 text-amber-600' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-400')) }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $item->product->category->name ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-black text-orange-500 flex-shrink-0">{{ $item->total_sold }}<span class="text-xs font-medium text-gray-400 ml-0.5">x</span></span>
                    </li>
                @empty
                    <li class="text-center py-8 text-gray-400">
                        <svg class="w-10 h-10 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <p class="text-sm">Belum ada data</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    @if(auth()->user()->is_admin)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
        {{-- Laporan Per Kasir --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-base font-bold text-gray-900 mb-4">👥 Pantauan Kinerja Kasir Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-sm font-semibold text-gray-700">
                            <th class="py-3 px-4">Nama Kasir / Usaha</th>
                            <th class="py-3 px-4 text-center">Transaksi Hari Ini</th>
                            <th class="py-3 px-4 text-right">Omzet Hari Ini</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cashierStats as $cashier)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $cashier->name }}</td>
                            <td class="py-3 px-4 text-center font-bold text-blue-600">{{ $cashier->orders_count }} Order</td>
                            <td class="py-3 px-4 text-right font-black text-green-600">Rp {{ number_format($cashier->today_revenue ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-center">
                                <button 
                                    wire:click="deleteUser({{ $cashier->id }})"
                                    wire:confirm="Yakin ingin menghapus usaha/kasir ini?\n\nPERHATIAN: Semua data transaksi (orders & items) yang pernah dibuat oleh {{ $cashier->name }} akan TERHAPUS PERMANEN!"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-bold transition"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400 text-sm">Belum ada kasir yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pie Chart Perbandingan Omzet --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <h3 class="text-base font-bold text-gray-900 mb-4">🍩 Porsi Omzet (Bulan Ini)</h3>
            <div class="relative flex-1 w-full min-h-[250px] flex items-center justify-center">
                <canvas id="cashierPieChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    {{-- Chart Script --}}
    <script>
        document.addEventListener('livewire:navigated', () => { initChart(); });
        document.addEventListener('DOMContentLoaded', () => { initChart(); });

        function initChart() {
            const canvas = document.getElementById('salesChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(249, 115, 22, 0.18)');
            gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');

            if (window.mySalesChart) { window.mySalesChart.destroy(); }

            window.mySalesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! $chartLabels !!},
                    datasets: [{
                        label: 'Omzet Harian (Rp)',
                        data: {!! $chartData !!},
                        borderColor: '#f97316',
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#f97316',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#9ca3af',
                            bodyColor: '#f9fafb',
                            padding: 10,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(context) {
                                    return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6', drawBorder: false },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 11 },
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af', font: { size: 11 } }
                        }
                    }
                }
            });

            // Init Pie Chart (Admin Only)
            const pieCanvas = document.getElementById('cashierPieChart');
            if (pieCanvas) {
                const pieCtx = pieCanvas.getContext('2d');
                if (window.myPieChart) { window.myPieChart.destroy(); }
                
                const pieLabels = @json(isset($pieChartLabels) ? json_decode($pieChartLabels) : []);
                const pieData = @json(isset($pieChartData) ? json_decode($pieChartData) : []);

                window.myPieChart = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: pieData,
                            backgroundColor: [
                                '#f97316', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6'
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: { size: 11, family: "'Inter', sans-serif" },
                                    color: '#6b7280'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                titleColor: '#9ca3af',
                                bodyColor: '#f9fafb',
                                padding: 10,
                                cornerRadius: 10,
                                callbacks: {
                                    label: function(context) {
                                        return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }
        }
    </script>
</div>
