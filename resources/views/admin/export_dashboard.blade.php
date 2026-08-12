<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AZZAHRA COMPUTER - Dashboard Report</title>
    <!-- Google Fonts Inter matching Admin Dashboard -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            color: #374151;
            background: #fff;
            padding: 20px 28px;
            line-height: 1.6;
        }

        /* ── Header ── */
        .report-header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 16px;
            margin-bottom: 28px;
            position: relative;
        }
        .report-header h1 {
            font-size: 24px;
            color: #1e40af;
            font-weight: 700;
            letter-spacing: -0.01em;
        }
        .report-header .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 6px;
        }

        .print-btn {
            position: fixed;
            top: 16px;
            right: 20px;
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            z-index: 9999;
            box-shadow: 0 2px 6px rgba(37,99,235,0.25);
            transition: background 0.2s;
        }
        .print-btn:hover { background: #1d4ed8; }

        /* ── Section titles ── */
        .section-title {
            font-size: 17px;
            font-weight: 700;
            color: #1d4ed8;
            margin: 28px 0 14px 0;
            border-bottom: 2px solid #bfdbfe;
            padding-bottom: 8px;
        }

        /* ── Performance Metrics ── */
        .metrics-row {
            display: flex;
            gap: 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            background: #fff;
        }
        .metric-box {
            flex: 1;
            text-align: center;
            padding: 20px 14px;
            border-right: 1px solid #e5e7eb;
        }
        .metric-box:last-child { border-right: none; }
        .metric-box .val {
            font-size: 26px;
            font-weight: 700;
            color: #1e40af;
        }
        .metric-box .lbl {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
            margin-top: 6px;
        }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-bottom: 16px;
        }
        table th {
            background: #f8fafc;
            color: #1e293b;
            font-weight: 700;
            padding: 10px 14px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        table th.right, table td.right { text-align: right; }
        table td {
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            color: #334155;
        }
        table tr:nth-child(even) td { background: #f8fafc; }

        /* ── Progress bar ── */
        .progress-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .progress-bar-bg {
            flex: 1;
            height: 14px;
            background: #f1f5f9;
            border-radius: 7px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 7px;
        }

        /* ── Activity Summary box ── */
        .summary-box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 8px;
            padding: 18px 24px;
            margin-top: 14px;
        }
        .summary-box p {
            margin-bottom: 10px;
            font-size: 15px;
        }
        .summary-box p:last-child { margin-bottom: 0; }
        .summary-box p strong { color: #1e293b; }
        .summary-box span.val { font-weight: 700; color: #dc2626; }

        /* ── Footer ── */
        .report-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
        }
        .report-footer a { color: #64748b; }

        /* ── Badges & Styles ── */
        td.rank { font-weight: 700; color: #2563eb; text-align: center; font-size: 15px; }
        td.tech-link { color: #2563eb; font-weight: 600; }
        td.services-val { text-align: right; color: #dc2626; font-weight: 700; font-size: 15px; }
        td.act-time { color: #64748b; font-size: 13px; }

        .period-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .report-card {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }
        .report-card table {
            background: #ffffff;
        }
        .card-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 16px;
            margin-bottom: 4px;
        }
        .card-section-title:first-child {
            margin-top: 0;
        }
        .card-month-title {
            font-size: 14px;
            font-weight: 700;
            color: #1d4ed8;
            margin-bottom: 10px;
        }

        @media print {
            .print-btn { display: none; }
            body { padding: 10px; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨 Print Report</button>

<div class="report-header">
    <h1>AZZAHRA COMPUTER - Dashboard Report</h1>
    <div class="subtitle">{{ \Carbon\Carbon::now('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY HH:mm:ss') }}</div>
    <div class="subtitle">Generated by: {{ $generated_by }}</div>
</div>

@if($section === 'all' || $section === 'performance')
<div class="section-title">Performance Metrics</div>
<div class="metrics-row">
    <div class="metric-box">
        <div class="val">Rp {{ number_format($revenue_today, 0, ',', '.') }}</div>
        <div class="lbl">Revenue Today</div>
    </div>
    <div class="metric-box">
        <div class="val">{{ $pembayaran_lunas }}</div>
        <div class="lbl">Pembayaran Lunas</div>
    </div>
    <div class="metric-box">
        <div class="val">{{ $total_customers }}</div>
        <div class="lbl">Total Customers</div>
    </div>
    <div class="metric-box">
        <div class="val">{{ $dp_pending }}</div>
        <div class="lbl">Pembayaran DP</div>
    </div>
</div>
@endif

@if($section === 'all' || $section === 'payment')
<div class="section-title">Payment Methods Overview</div>
<div class="report-card">
    <!-- Diagram Batang (Bar Chart) -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 20px;">
        <div style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
            <span>📊 Diagram Batang Metode Pembayaran</span>
            <span style="font-size: 13px; font-weight: 500; color: #64748b;">Persentase (%) & Total Pending</span>
        </div>

        <div style="display: flex; height: 230px; align-items: flex-end; gap: 20px; padding: 0 10px 10px 10px; border-bottom: 2px solid #cbd5e1; position: relative;">
            
            <!-- Grid lines background -->
            <div style="position: absolute; top: 0; left: 0; right: 0; border-top: 1px dashed #e2e8f0; pointer-events: none;">
                <span style="position: absolute; right: 8px; top: -10px; font-size: 10px; color: #94a3b8; font-weight: 600;">100%</span>
            </div>
            <div style="position: absolute; top: 25%; left: 0; right: 0; border-top: 1px dashed #f1f5f9; pointer-events: none;">
                <span style="position: absolute; right: 8px; top: -10px; font-size: 10px; color: #94a3b8;">75%</span>
            </div>
            <div style="position: absolute; top: 50%; left: 0; right: 0; border-top: 1px dashed #e2e8f0; pointer-events: none;">
                <span style="position: absolute; right: 8px; top: -10px; font-size: 10px; color: #94a3b8; font-weight: 600;">50%</span>
            </div>
            <div style="position: absolute; top: 75%; left: 0; right: 0; border-top: 1px dashed #f1f5f9; pointer-events: none;">
                <span style="position: absolute; right: 8px; top: -10px; font-size: 10px; color: #94a3b8;">25%</span>
            </div>

            <!-- Bar 1: Bank BCA -->
            @php $bca_h = max(min($bca_pct, 100), 4); @endphp
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                <div style="font-size: 12px; font-weight: 700; color: #2563eb; margin-bottom: 6px;">{{ $bca_pct }}%</div>
                <div style="width: 100%; max-width: 52px; height: {{ $bca_h }}%; background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 6px 6px 0 0; box-shadow: 0 4px 6px rgba(37,99,235,0.25); min-height: 6px;"></div>
                <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155; text-align: center;">Bank BCA</div>
            </div>

            <!-- Bar 2: Bank Mandiri -->
            @php $mandiri_h = max(min($mandiri_pct, 100), 4); @endphp
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                <div style="font-size: 12px; font-weight: 700; color: #4f46e5; margin-bottom: 6px;">{{ $mandiri_pct }}%</div>
                <div style="width: 100%; max-width: 52px; height: {{ $mandiri_h }}%; background: linear-gradient(180deg, #6366f1 0%, #4338ca 100%); border-radius: 6px 6px 0 0; box-shadow: 0 4px 6px rgba(79,70,229,0.25); min-height: 6px;"></div>
                <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155; text-align: center;">Bank Mandiri</div>
            </div>

            <!-- Bar 3: Bank BRI -->
            @php $bri_h = max(min($bri_pct, 100), 4); @endphp
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                <div style="font-size: 12px; font-weight: 700; color: #0d9488; margin-bottom: 6px;">{{ $bri_pct }}%</div>
                <div style="width: 100%; max-width: 52px; height: {{ $bri_h }}%; background: linear-gradient(180deg, #14b8a6 0%, #0f766e 100%); border-radius: 6px 6px 0 0; box-shadow: 0 4px 6px rgba(13,148,136,0.25); min-height: 6px;"></div>
                <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155; text-align: center;">Bank BRI</div>
            </div>

            <!-- Bar 4: Tunai -->
            @php $tunai_h = max(min($tunai_pct, 100), 4); @endphp
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                <div style="font-size: 12px; font-weight: 700; color: #059669; margin-bottom: 6px;">{{ $tunai_pct }}%</div>
                <div style="width: 100%; max-width: 52px; height: {{ $tunai_h }}%; background: linear-gradient(180deg, #10b981 0%, #047857 100%); border-radius: 6px 6px 0 0; box-shadow: 0 4px 6px rgba(5,150,105,0.25); min-height: 6px;"></div>
                <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155; text-align: center;">Tunai</div>
            </div>

            <!-- Bar 5: Payment Pending -->
            @php $pending_h = $total_pending_transfers > 0 ? min($total_pending_transfers * 15, 100) : 4; @endphp
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                <div style="font-size: 12px; font-weight: 700; color: #d97706; margin-bottom: 6px;">{{ $total_pending_transfers }}</div>
                <div style="width: 100%; max-width: 52px; height: {{ $pending_h }}%; background: linear-gradient(180deg, #f59e0b 0%, #b45309 100%); border-radius: 6px 6px 0 0; box-shadow: 0 4px 6px rgba(217,119,6,0.25); min-height: 6px;"></div>
                <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155; text-align: center;">Pending</div>
            </div>

            <!-- Bar 6: Voucher Used -->
            @php $voucher_h = max(min($voucher_pct, 100), 4); @endphp
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; position: relative; z-index: 2;">
                <div style="font-size: 12px; font-weight: 700; color: #8b5cf6; margin-bottom: 6px;">{{ $voucher_pct }}%</div>
                <div style="width: 100%; max-width: 52px; height: {{ $voucher_h }}%; background: linear-gradient(180deg, #a855f7 0%, #6b21a8 100%); border-radius: 6px 6px 0 0; shadow: 0 4px 6px rgba(139,92,246,0.25); min-height: 6px;"></div>
                <div style="margin-top: 10px; font-size: 12px; font-weight: 600; color: #334155; text-align: center;">Voucher</div>
            </div>

        </div>
    </div>

    <!-- Ringkasan Data Pembayaran -->
    <table style="margin-bottom:0;">
        <thead>
            <tr>
                <th style="width:220px;">Metode Pembayaran</th>
                <th style="width:120px;" class="right">Nilai / Persentase</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Bank BCA</strong></td>
                <td class="right" style="color:#2563eb;font-weight:600;">{{ $bca_pct }}%</td>
                <td>Transfer Bank BCA</td>
            </tr>
            <tr>
                <td><strong>Bank Mandiri</strong></td>
                <td class="right" style="color:#4f46e5;font-weight:600;">{{ $mandiri_pct }}%</td>
                <td>Transfer Bank Mandiri</td>
            </tr>
            <tr>
                <td><strong>Bank BRI</strong></td>
                <td class="right" style="color:#0d9488;font-weight:600;">{{ $bri_pct }}%</td>
                <td>Transfer Bank BRI</td>
            </tr>
            <tr>
                <td><strong>Tunai</strong></td>
                <td class="right" style="color:#059669;font-weight:600;">{{ $tunai_pct }}%</td>
                <td>Pembayaran Cash / Tunai</td>
            </tr>
            <tr>
                <td><strong>Payment Pending</strong></td>
                <td class="right" style="color:#d97706;font-weight:600;">{{ $total_pending_transfers }}</td>
                <td>Menunggu Verifikasi Stor</td>
            </tr>
            <tr>
                <td><strong>Voucher Used</strong></td>
                <td class="right" style="color:#8b5cf6;font-weight:600;">{{ $voucher_pct }}%</td>
                <td>Penggunaan Vocer Diskon</td>
            </tr>
        </tbody>
    </table>
</div>
@endif

@if($section === 'all' || $section === 'weekly')
<div class="section-title">Weekly Performance</div>

<div class="report-card">
    <div class="card-section-title">Revenue Performance (Last {{ $weeklyPeriodParam }})</div>
    @php
        $monthLabel = $weekly_revenue->isNotEmpty()
            ? \Carbon\Carbon::parse($weekly_revenue->first()->day)->isoFormat('MMMM YYYY')
            : \Carbon\Carbon::now()->isoFormat('MMMM YYYY');
    @endphp
    <div class="card-month-title">{{ $monthLabel }}</div>
    @if($weekly_revenue->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th class="right">Revenue (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($weekly_revenue as $row)
            <tr>
                <td>{{ \Carbon\Carbon::parse($row->day)->isoFormat('ddd, DD MMM YYYY') }}</td>
                <td class="right">{{ number_format($row->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="color:#94a3b8; font-style:italic; font-size:13px; margin-bottom:12px;">Tidak ada data revenue pada periode ini.</p>
    @endif

    <div class="card-section-title" style="margin-top:20px;">Transaction Performance (Last {{ $weeklyPeriodParam }})</div>
    @php
        $txMonthLabel = $weekly_transactions->isNotEmpty()
            ? \Carbon\Carbon::parse($weekly_transactions->first()->day)->isoFormat('MMMM YYYY')
            : \Carbon\Carbon::now()->isoFormat('MMMM YYYY');
    @endphp
    <div class="card-month-title">{{ $txMonthLabel }}</div>
    @if($weekly_transactions->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th class="right">Transactions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($weekly_transactions as $row)
            <tr>
                <td>{{ \Carbon\Carbon::parse($row->day)->isoFormat('ddd, DD MMM YYYY') }}</td>
                <td class="right">{{ $row->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="color:#94a3b8; font-style:italic; font-size:13px; margin-bottom:12px;">Tidak ada data transaksi pada periode ini.</p>
    @endif

    <div class="card-section-title" style="margin-top:20px;">New Customers (Last {{ $weeklyPeriodParam }})</div>
    @php
        $cusMonthLabel = $weekly_customers->isNotEmpty()
            ? \Carbon\Carbon::parse($weekly_customers->first()->day)->isoFormat('MMMM YYYY')
            : \Carbon\Carbon::now()->isoFormat('MMMM YYYY');
    @endphp
    <div class="card-month-title">{{ $cusMonthLabel }}</div>
    @if($weekly_customers->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th class="right">New Customers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($weekly_customers as $row)
            <tr>
                <td>{{ \Carbon\Carbon::parse($row->day)->isoFormat('ddd, DD MMM YYYY') }}</td>
                <td class="right">{{ $row->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="color:#94a3b8; font-style:italic; font-size:13px; margin-bottom:12px;">Tidak ada customer baru pada periode ini.</p>
    @endif
</div>
@endif

@if($section === 'all' || $section === 'technicians')
<div class="section-title">Top Performers Teknisi</div>
<div class="report-card">
    <div style="margin-bottom:14px; font-size:14px; color:#475569;"><strong>Period:</strong> Last {{ $technicianPeriodParam === '7D' ? '7 Days' : ($technicianPeriodParam === '1M' ? '1 Month' : ($technicianPeriodParam === '1Y' ? '1 Year' : $technicianPeriodParam)) }}</div>
    @if($technician_stats->isNotEmpty())
    <table style="margin-bottom:0;">
        <thead>
            <tr>
                <th style="width:70px; text-align:center;">Rank</th>
                <th>Technician Name</th>
                <th class="right">Services Completed</th>
            </tr>
        </thead>
        <tbody>
            @foreach($technician_stats as $i => $tech)
            <tr>
                <td class="rank">{{ $i + 1 }}</td>
                <td class="tech-link">{{ $tech->technician_name }}</td>
                <td class="services-val">{{ $tech->services_completed }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="color:#94a3b8; font-style:italic; font-size:14px; margin-bottom:0;">Tidak ada data teknisi pada periode ini.</p>
    @endif
</div>
@endif

@if($section === 'all' || $section === 'activity')
<div class="section-title">Recent Activity Details</div>

<div class="report-card">
    <div class="card-section-title">Recent Customers (Last Activity)</div>
    @if($recent_customers->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Activity</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_customers as $cus)
            <tr>
                <td><span style="color:#2563eb;font-weight:500;">{{ $cus->cos_nama ?? $cus->name ?? '-' }}</span></td>
                <td>Customer baru dikonfirmasi</td>
                <td class="act-time">{{ isset($cus->created_at) ? \Carbon\Carbon::parse($cus->created_at)->format('Y-m-d H:i:s') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="color:#94a3b8; font-style:italic; font-size:13px; margin-bottom:12px;">Tidak ada aktivitas customer terbaru.</p>
    @endif

    <div class="card-section-title" style="margin-top:20px;">Recent Users (Last Activity)</div>
    @if($recent_users->isNotEmpty())
    <table style="margin-bottom:0;">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Activity</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_users as $usr)
            <tr>
                <td>{{ $usr->name ?? '-' }}</td>
                <td>User terdaftar</td>
                <td class="act-time">{{ isset($usr->created_at) ? \Carbon\Carbon::parse($usr->created_at)->format('Y-m-d H:i:s') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="color:#94a3b8; font-style:italic; font-size:13px; margin-bottom:0;">No recent user activity.</p>
    @endif
</div>

<div class="section-title">Activity Summary</div>
<div class="summary-box">
    <p><strong>Konfirmasi Pending:</strong> <span class="val">{{ $konf_pending }}</span> transaksi menunggu konfirmasi</p>
    <p><strong>Voucher Aktif:</strong> <span class="val">{{ $voucher_aktif }}</span> voucher tersedia</p>
    <p><strong>Customer Baru Hari Ini:</strong> <span class="val">{{ $customer_baru_hari_ini }}</span> customer</p>
    <p><strong>User Baru Hari Ini:</strong> <span class="val">{{ $user_baru_hari_ini }}</span> user</p>
</div>
@endif

<div class="report-footer">
    <p>
        Report generated on <span style="color:#d97706;font-weight:500;">{{ $generated_at }}</span>
        by <span style="color:#2563eb;font-weight:500;">Azzahra Computer Management System</span>
    </p>
    <p style="margin-top:4px;">
        This report contains <a href="#">confidential</a> business information
    </p>
</div>

</body>
</html>


