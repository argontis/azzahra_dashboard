<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Report - {{ ucfirst($section ?? 'all') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f9fafb;
            color: #1f2937;
            margin: 0;
            padding: 2rem;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
        }

        .report-header h1 {
            margin: 0 0 0.25rem 0;
            font-size: 1.5rem;
        }

        .report-meta {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .print-btn {
            background: #6366f1;
            color: #fff;
            border: none;
            padding: 0.6rem 1.1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .print-btn:hover {
            background: #4f46e5;
        }

        .report-section {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .report-section h2 {
            font-size: 1.125rem;
            margin: 0 0 1rem 0;
            color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 0.6rem 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
        }

        th {
            color: #6b7280;
            font-weight: 600;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .kpi-box {
            background: #f9fafb;
            border-radius: 6px;
            padding: 1rem;
        }

        .kpi-box .label {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .kpi-box .value {
            font-size: 1.25rem;
            font-weight: 700;
            margin-top: 0.25rem;
        }

        .chart-wrap {
            max-width: 700px;
            height: 320px;
        }

        .empty-note {
            color: #9ca3af;
            font-size: 0.875rem;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                background: #fff;
                padding: 0;
            }

            .report-section {
                box-shadow: none;
                border: 1px solid #e5e7eb;
            }
        }
    </style>
</head>

<body>

    <?php
    // Ambil parameter dari URL, contoh:
    // /Admin/export_dashboard?weekly_period=7D&technician_period=7D&section=payment
    $section = request('section', 'all');
    $weekly_period = request('weekly_period', '7D');
    $technician_period = request('technician_period', '7D');
    
    $sectionTitles = [
        'all' => 'Semua Section',
        'performance' => 'Performance Metrics',
        'weekly' => 'Weekly Performance',
        'technicians' => 'Top Performers Teknisi',
        'payment' => 'Payment Methods',
        'activity' => 'Recent Activity',
    ];
    ?>

    <div class="report-header">
        <div>
            <h1>Dashboard Report — {{ $sectionTitles[$section] ?? ucfirst($section) }}</h1>
            <div class="report-meta">
                Generated: {{ date('l, d F Y H:i') }} &middot;
                Weekly period: {{ $weekly_period }} &middot;
                Technician period: {{ $technician_period }}
            </div>
        </div>
        <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
    </div>

    {{-- =====================================================
     PERFORMANCE METRICS
     ===================================================== --}}
    @if ($section === 'all' || $section === 'performance')
        <div class="report-section">
            <h2>Performance Metrics</h2>
            <div class="kpi-grid">
                <div class="kpi-box">
                    <div class="label">Total Konfirmasi</div>
                    <div class="value">{{ $konf ?? '0' }}</div>
                </div>
                <div class="kpi-box">
                    <div class="label">Total Customers</div>
                    <div class="value">{{ $total_customers ?? '0' }}</div>
                </div>
                <div class="kpi-box">
                    <div class="label">Pembayaran Lunas</div>
                    <div class="value">{{ $service_completion_rate ?? '0' }}</div>
                </div>
                <div class="kpi-box">
                    <div class="label">Pembayaran DP</div>
                    <div class="value">{{ $dp_pending ?? '0' }}</div>
                </div>
                <div class="kpi-box">
                    <div class="label">Revenue Today</div>
                    <div class="value">Rp {{ number_format($revenue_today ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    @endif

    {{-- =====================================================
     WEEKLY PERFORMANCE
     ===================================================== --}}
    @if ($section === 'all' || $section === 'weekly')
        <div class="report-section">
            <h2>Weekly Performance ({{ $weekly_period }})</h2>
            @if (!empty($weekly_revenue))
                <div class="chart-wrap">
                    <canvas id="weeklyReportChart"></canvas>
                </div>
                <table style="margin-top:1rem;">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Revenue (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($weekly_revenue as $row)
                            <tr>
                                <td>{{ date('D, d M', strtotime($row->day)) }}</td>
                                <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="empty-note">Tidak ada data weekly performance untuk periode ini.</p>
            @endif
        </div>
    @endif

    {{-- =====================================================
     TOP PERFORMERS TEKNISI
     ===================================================== --}}
    @if ($section === 'all' || $section === 'technicians')
        <div class="report-section">
            <h2>Top Performers Teknisi ({{ $technician_period }})</h2>
            <?php
            $techMap = [];
            if (!empty($technician_details)) {
                foreach ($technician_details as $tech) {
                    $name = $tech['technician_name'] ?? 'Teknisi Tidak Diketahui';
                    $techMap[$name] = ($techMap[$name] ?? 0) + ($tech['services_completed'] ?? 0);
                }
                arsort($techMap);
            }
            ?>
            @if (!empty($techMap))
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Teknisi</th>
                            <th>Services Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; ?>
                        @foreach ($techMap as $name => $total)
                            <tr>
                                <td>{{ $rank++ }}</td>
                                <td>{{ $name }}</td>
                                <td>{{ $total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="empty-note">Tidak ada data teknisi untuk periode ini.</p>
            @endif
        </div>
    @endif

    {{-- =====================================================
     PAYMENT METHODS
     ===================================================== --}}
    @if ($section === 'all' || $section === 'payment')
        <div class="report-section">
            <h2>Payment Methods Overview</h2>
            <?php
            $bca_pct = $bank_percentages['BCA'] ?? 0;
            $mandiri_pct = $bank_percentages['MANDIRI'] ?? 0;
            $bri_pct = $bank_percentages['BRI'] ?? 0;
            $tunai_pct = $tunai_percentage ?? 0;
            $voucher_pct = $voucher_usage_percentage ?? 0;
            $pending_val = $total_pending_transfers ?? 0;
            ?>

            <!-- Diagram Batang (Bar Chart) -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 20px;">
                <div style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    <span>📊 Diagram Batang Metode Pembayaran</span>
                    <span style="font-size: 13px; font-weight: 500; color: #64748b;">Persentase (%) & Total Pending</span>
                </div>

                <div style="display: flex; height: 220px; align-items: flex-end; gap: 20px; padding: 0 10px 10px 10px; border-bottom: 2px solid #cbd5e1; position: relative;">
                    <!-- Grid lines -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; border-top: 1px dashed #e2e8f0; pointer-events: none;">
                        <span style="position: absolute; right: 8px; top: -10px; font-size: 10px; color: #94a3b8; font-weight: 600;">100%</span>
                    </div>
                    <div style="position: absolute; top: 50%; left: 0; right: 0; border-top: 1px dashed #e2e8f0; pointer-events: none;">
                        <span style="position: absolute; right: 8px; top: -10px; font-size: 10px; color: #94a3b8; font-weight: 600;">50%</span>
                    </div>

                    <!-- Bar 1: BCA -->
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                        <div style="font-size: 12px; font-weight: 700; color: #2563eb; margin-bottom: 6px;">{{ $bca_pct }}%</div>
                        <div style="width: 100%; max-width: 48px; height: {{ max(min($bca_pct, 100), 4) }}%; background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 6px 6px 0 0;"></div>
                        <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155;">Bank BCA</div>
                    </div>

                    <!-- Bar 2: Mandiri -->
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                        <div style="font-size: 12px; font-weight: 700; color: #4f46e5; margin-bottom: 6px;">{{ $mandiri_pct }}%</div>
                        <div style="width: 100%; max-width: 48px; height: {{ max(min($mandiri_pct, 100), 4) }}%; background: linear-gradient(180deg, #6366f1 0%, #4338ca 100%); border-radius: 6px 6px 0 0;"></div>
                        <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155;">Mandiri</div>
                    </div>

                    <!-- Bar 3: BRI -->
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                        <div style="font-size: 12px; font-weight: 700; color: #0d9488; margin-bottom: 6px;">{{ $bri_pct }}%</div>
                        <div style="width: 100%; max-width: 48px; height: {{ max(min($bri_pct, 100), 4) }}%; background: linear-gradient(180deg, #14b8a6 0%, #0f766e 100%); border-radius: 6px 6px 0 0;"></div>
                        <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155;">Bank BRI</div>
                    </div>

                    <!-- Bar 4: Tunai -->
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                        <div style="font-size: 12px; font-weight: 700; color: #059669; margin-bottom: 6px;">{{ $tunai_pct }}%</div>
                        <div style="width: 100%; max-width: 48px; height: {{ max(min($tunai_pct, 100), 4) }}%; background: linear-gradient(180deg, #10b981 0%, #047857 100%); border-radius: 6px 6px 0 0;"></div>
                        <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155;">Tunai</div>
                    </div>

                    <!-- Bar 5: Pending -->
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                        <div style="font-size: 12px; font-weight: 700; color: #d97706; margin-bottom: 6px;">{{ $pending_val }}</div>
                        <div style="width: 100%; max-width: 48px; height: {{ $pending_val > 0 ? min($pending_val * 15, 100) : 4 }}%; background: linear-gradient(180deg, #f59e0b 0%, #b45309 100%); border-radius: 6px 6px 0 0;"></div>
                        <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155;">Pending</div>
                    </div>

                    <!-- Bar 6: Voucher -->
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                        <div style="font-size: 12px; font-weight: 700; color: #8b5cf6; margin-bottom: 6px;">{{ $voucher_pct }}%</div>
                        <div style="width: 100%; max-width: 48px; height: {{ max(min($voucher_pct, 100), 4) }}%; background: linear-gradient(180deg, #a855f7 0%, #6b21a8 100%); border-radius: 6px 6px 0 0;"></div>
                        <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155;">Voucher</div>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Metode</th>
                        <th>Persentase / Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Bank BCA</td>
                        <td>{{ $bca_pct }}%</td>
                    </tr>
                    <tr>
                        <td>Bank Mandiri</td>
                        <td>{{ $mandiri_pct }}%</td>
                    </tr>
                    <tr>
                        <td>Bank BRI</td>
                        <td>{{ $bri_pct }}%</td>
                    </tr>
                    <tr>
                        <td>Payment Pending</td>
                        <td>{{ $total_pending_transfers ?? '0' }}</td>
                    </tr>
                    <tr>
                        <td>Tunai</td>
                        <td>{{ $tunai_percentage ?? '0' }}%</td>
                    </tr>
                    <tr>
                        <td>Voucher Used</td>
                        <td>{{ $voucher_usage_percentage ?? '0' }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    {{-- =====================================================
     RECENT ACTIVITY
     ===================================================== --}}
    @if ($section === 'all' || $section === 'activity')
        <div class="report-section">
            <h2>Recent Activity</h2>
            <div class="kpi-grid" style="margin-bottom: 1.5rem;">
                <div class="kpi-box">
                    <div class="label">Total Customers</div>
                    <div class="value">{{ $total_customers ?? '0' }}</div>
                </div>
                <div class="kpi-box">
                    <div class="label">Konfirmasi Pending</div>
                    <div class="value">{{ $konf ?? '0' }}</div>
                </div>
                <div class="kpi-box">
                    <div class="label">Total Tunai</div>
                    <div class="value">Rp {{ number_format($total_tunai ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>

            <h2>Recent Customers ({{ count($baru ?? []) }})</h2>
            @if (!empty($baru) && count($baru) > 0)
                <table style="margin-bottom:1.5rem;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($baru as $row)
                            <tr>
                                <td>{{ $row->cos_nama ?? '-' }}</td>
                                <td>{{ $row->created_at ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="empty-note">Belum ada customer baru.</p>
            @endif

            <h2>Recent Users ({{ count($users_baru ?? []) }})</h2>
            @if (!empty($users_baru) && count($users_baru) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users_baru as $row)
                            <tr>
                                <td>{{ $row->cos_nama ?? '-' }}</td>
                                <td>{{ $row->created_at ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="empty-note">Belum ada user baru.</p>
            @endif
        </div>
    @endif

    @if (!in_array($section, ['all', 'performance', 'weekly', 'technicians', 'payment', 'activity']))
        <div class="report-section">
            <p class="empty-note">Section "{{ $section }}" tidak dikenali. Gunakan salah satu: all, performance,
                weekly, technicians, payment, activity.</p>
        </div>
    @endif

    {{-- Chart weekly hanya di-render kalau section weekly/all dan datanya ada --}}
    @if (($section === 'all' || $section === 'weekly') && !empty($weekly_revenue))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('weeklyReportChart');
                if (!ctx || typeof Chart === 'undefined') return;

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [
                            @foreach ($weekly_revenue as $row)
                                "{{ date('D, d M', strtotime($row->day)) }}",
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Revenue (Rp)',
                            data: [
                                @foreach ($weekly_revenue as $row)
                                    {{ $row->total }},
                                @endforeach
                            ],
                            borderColor: '#6366f1',
                            backgroundColor: '#6366f120',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (v) => 'Rp ' + Number(v).toLocaleString('id-ID')
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endif

</body>

</html>
