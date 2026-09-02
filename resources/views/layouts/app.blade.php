<!DOCTYPE html>
<html lang="en">
   <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
        <!-- Security Headers -->
        <meta http-equiv="X-Content-Type-Options" content="nosniff">
        <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
        
        <!-- SEO -->
        <meta name="description" content="Dashboard Admin - Azzahra Computer Tegal">
        <meta name="author" content="LEFT4CODE">
        <meta name="robots" content="noindex, nofollow">
        
        <!-- Favicon -->
        <link href="{{ asset('assets/template/beck/dist/images/logo.svg') }}" rel="shortcut icon">
        
        <!-- Title -->
        <title>{{ $title ?? 'Dashboard' }} - Azzahra Computer</title>
        
        <!-- Preconnect -->
        <link rel="preconnect" href="https://cdn.jsdelivr.net">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <!-- BEGIN: CSS Assets -->
        <link rel="stylesheet" href="{{ asset('assets/template/beck/dist/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/file/alert/animet.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
        
        <!-- Dashboard CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
        
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- JS Pola -->
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        
        <!-- Feather Icons -->
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <!-- END: CSS Assets -->
    </head>

<body class="app">
    <!-- BEGIN: Preloader -->
    <div id="midone-preloader" style="position:fixed; top:0; left:0; width:100%; height:100%; background:white; z-index:9999; display:flex; justify-content:center; align-items:center; transition:opacity 0.5s ease;">
        <svg width="40" height="40" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" stroke="#4472C4">
            <style><![CDATA[.spinner_V8m1{transform-origin:center;animation:spinner_zKoa 2s linear infinite}.spinner_V8m1 circle{stroke-linecap:round;animation:spinner_YpZS 1.5s ease-in-out infinite}@keyframes spinner_zKoa{100%{transform:rotate(360deg)}}@keyframes spinner_YpZS{0%{stroke-dasharray:0 150;stroke-dashoffset:0}47.5%{stroke-dasharray:42 150;stroke-dashoffset:-16}95%,100%{stroke-dasharray:42 150;stroke-dashoffset:-59}}]]></style>
            <g class="spinner_V8m1"><circle cx="12" cy="12" r="9.5" fill="none" stroke-width="3"></circle></g>
        </svg>
    </div>
    <!-- END: Preloader -->

    @if(session('show_curtain'))
    <!-- =====================================================
         DASHBOARD CURTAIN REVEAL OVERLAY
         Tirai menutup saat halaman load, lalu membuka
         Hanya muncul saat pertama masuk dari halaman login
         ===================================================== -->
    <div id="dashCurtainOverlay" style="
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        z-index: 99999;
        pointer-events: none;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
        <!-- Left Curtain Panel -->
        <div id="dashCurtainLeft" style="
            position: absolute;
            top: 0; left: 0;
            width: 50%; height: 100%;
            background: linear-gradient(160deg, #1e1b4b 0%, #312e81 50%, #1e40af 100%);
            border-right: 3px solid #38bdf8;
            box-shadow: inset -20px 0 60px rgba(0,0,0,0.6), 10px 0 30px rgba(56, 189, 248, 0.5);
            transform: translateX(0%);
            transition: transform 0.9s cubic-bezier(0.77, 0, 0.175, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
        ">
            <div style="
                position: absolute; right: 0; top: 0;
                width: 6px; height: 100%;
                background: linear-gradient(180deg, #38bdf8, #818cf8, #34d399, #38bdf8);
                box-shadow: 0 0 12px #38bdf8;
                opacity: 0.95;
            "></div>
            <div style="font-size: 54px; margin-bottom: 14px; filter: drop-shadow(0 0 18px rgba(56,189,248,0.9));">🔵</div>
            <div style="font-size: 12px; font-weight: 800; letter-spacing: 4px; color: #38bdf8; text-transform: uppercase; text-shadow: 0 0 12px rgba(56,189,248,0.6);">Azzahra Computer</div>
        </div>
        <!-- Right Curtain Panel -->
        <div id="dashCurtainRight" style="
            position: absolute;
            top: 0; right: 0;
            width: 50%; height: 100%;
            background: linear-gradient(200deg, #1e1b4b 0%, #312e81 50%, #1e40af 100%);
            border-left: 3px solid #38bdf8;
            box-shadow: inset 20px 0 60px rgba(0,0,0,0.6), -10px 0 30px rgba(56, 189, 248, 0.5);
            transform: translateX(0%);
            transition: transform 0.9s cubic-bezier(0.77, 0, 0.175, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
        ">
            <div style="
                position: absolute; left: 0; top: 0;
                width: 6px; height: 100%;
                background: linear-gradient(180deg, #38bdf8, #818cf8, #34d399, #38bdf8);
                box-shadow: 0 0 12px #38bdf8;
                opacity: 0.95;
            "></div>
            <div style="font-size: 54px; margin-bottom: 14px; filter: drop-shadow(0 0 18px rgba(56,189,248,0.9));">🔵</div>
            <div style="font-size: 12px; font-weight: 800; letter-spacing: 4px; color: #38bdf8; text-transform: uppercase; text-shadow: 0 0 12px rgba(56,189,248,0.6);">Super-Apps System</div>
        </div>
        <!-- Center Badge -->
        <div id="dashCurtainBadge" style="
            position: relative; z-index: 10;
            background: rgba(15, 23, 42, 0.97);
            border: 1.5px solid rgba(56, 189, 248, 0.7);
            padding: 15px 32px;
            border-radius: 40px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 12px 50px rgba(0,0,0,0.7), 0 0 40px rgba(56,189,248,0.5);
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.4s ease, transform 0.4s ease;
        ">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2.5" style="animation: dashSpin 0.7s linear infinite;">
                <style>@keyframes dashSpin { to { transform: rotate(360deg); } }</style>
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
            </svg>
            <span>Memuat Dashboard...</span>
        </div>
    </div>
    @endif
    <!-- END: Dashboard Curtain Reveal Overlay -->

    {{-- ============================================================
         GLOBAL TOP-RIGHT MESSAGE DROPDOWN PANEL
         Muncul di pojok kanan atas saat icon mail diklik
         ============================================================ --}}
    @auth
    <style>
    /* Dropdown Panel Pesan & Notifikasi Top Right */
    #topbar-message-panel,
    #topbar-notification-panel {
        position: fixed !important;
        top: 72px !important;
        right: 28px !important;
        z-index: 99999 !important;
        width: 380px !important;
        max-width: calc(100vw - 32px) !important;
        background: #ffffff !important;
        border-radius: 18px !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.22), 0 4px 16px rgba(0,0,0,0.08) !important;
        border: 1px solid #e5e7eb !important;
        overflow: hidden !important;
        transform: scale(0.85) translateY(-20px) !important;
        transform-origin: top right !important;
        opacity: 0 !important;
        pointer-events: none !important;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        display: block !important;
    }
    #topbar-message-panel.open,
    #topbar-notification-panel.open {
        transform: scale(1) translateY(0) !important;
        opacity: 1 !important;
        pointer-events: all !important;
    }
    .fab-panel-header {
        background: linear-gradient(135deg, #0041c3, #0063f0);
        color: white;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .fab-panel-header.notif-header {
        background: linear-gradient(135deg, #1e1b4b, #312e81);
    }
    .fab-panel-header h3 {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .fab-panel-header a {
        font-size: 11px;
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 6px;
        padding: 3px 8px;
        transition: all 0.2s;
    }
    .fab-panel-header a:hover { background: rgba(255,255,255,0.2); color: white; }
    .fab-panel-msgs {
        max-height: 320px;
        overflow-y: auto;
        padding: 8px 0;
    }
    .fab-panel-msg-item {
        padding: 12px 18px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: background 0.15s;
    }
    .fab-panel-msg-item:last-child { border-bottom: none; }
    .fab-panel-msg-item:hover { background: #f8fafc; }
    .fab-panel-msg-item .msg-title { font-size: 13.5px; font-weight: 600; color: #1f2937; margin-bottom: 2px; }
    .fab-panel-msg-item .msg-from  { font-size: 11.5px; color: #6b7280; }
    .fab-panel-msg-item .msg-time  { font-size: 10.5px; color: #9ca3af; float: right; margin-top: 2px; }
    .fab-panel-msg-item.unread { background: #eff6ff; }
    .fab-panel-msg-item.unread .msg-title { color: #0041c3; }
    
    .notif-tag {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 6px;
        margin-right: 6px;
    }
    .notif-tag.maintenance { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .notif-tag.penting { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .notif-tag.info { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

    .fab-compose {
        padding: 12px 16px;
        border-top: 1px solid #f3f4f6;
        background: #fafbfc;
    }
    .fab-compose a {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: linear-gradient(135deg, #0041c3, #0063f0);
        color: white;
        text-decoration: none;
        padding: 8px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: opacity 0.2s;
    }
    .fab-compose a.notif-admin-btn {
        background: linear-gradient(135deg, #1e1b4b, #312e81);
    }
    .fab-compose a:hover { opacity: 0.9; }
    .fab-panel-empty {
        padding: 24px;
        text-align: center;
        color: #9ca3af;
        font-size: 13px;
    }
    </style>

    <!-- Top Right Dropdown Panel Pesan -->
    <div id="topbar-message-panel">
        <div class="fab-panel-header">
            <h3>✉️ Kotak Pesan</h3>
            <a href="{{ route('messages.index') }}">Lihat Semua →</a>
        </div>
        <div class="fab-panel-msgs" id="fab-panel-msgs-list">
            <div class="fab-panel-empty">Memuat pesan...</div>
        </div>
        <div class="fab-compose">
            <a href="{{ route('messages.index') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                Tulis Pesan Baru
            </a>
        </div>
    </div>

    <!-- Top Right Dropdown Panel Notifikasi / Pemberitahuan Sistem -->
    <div id="topbar-notification-panel">
        <div class="fab-panel-header notif-header">
            <h3>🔔 Pemberitahuan Sistem</h3>
            <a href="{{ route('announcements.index') }}">Lihat Semua →</a>
        </div>
        <div class="fab-panel-msgs" id="fab-panel-notif-list">
            <div class="fab-panel-empty">Memuat pemberitahuan...</div>
        </div>
        @if(Auth::check() && (Auth::user()->kry_level === 'Admin' || Auth::user()->kry_level === 'Pimpinan'))
        <div class="fab-compose">
            <a href="{{ route('announcements.index') }}" class="notif-admin-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                + Buat Pemberitahuan / Maintenance Baru
            </a>
        </div>
        @else
        <div class="fab-compose" style="text-align: center; font-size: 11.5px; color: #64748b; background: #f8fafc; padding: 10px 14px;">
            📢 Pemberitahuan &amp; Jadwal Maintenance Resmi dari Administrator
        </div>
        @endif
    </div>

    <script>
    (function () {
        const MESSAGES_URL = '{{ route("messages.index") }}';
        const ANNOUNCEMENTS_URL = '{{ route("announcements.index") }}';
        let msgPanelOpen = false;
        let notifPanelOpen = false;

        // Toggle Pesan
        window.toggleTopbarMsgPanel = function (e) {
            if (e) {
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
                if (typeof e.preventDefault === 'function') e.preventDefault();
            }
            const msgPanel = document.getElementById('topbar-message-panel');
            const notifPanel = document.getElementById('topbar-notification-panel');
            if (notifPanel) notifPanel.classList.remove('open');
            notifPanelOpen = false;

            if (!msgPanel) return;
            msgPanelOpen = !msgPanel.classList.contains('open');
            if (msgPanelOpen) {
                msgPanel.classList.add('open');
                loadTopbarMessages();
            } else {
                msgPanel.classList.remove('open');
            }
        };

        // Toggle Notifikasi
        window.toggleTopbarNotifPanel = function (e) {
            if (e) {
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
                if (typeof e.preventDefault === 'function') e.preventDefault();
            }
            const msgPanel = document.getElementById('topbar-message-panel');
            const notifPanel = document.getElementById('topbar-notification-panel');
            if (msgPanel) msgPanel.classList.remove('open');
            msgPanelOpen = false;

            if (!notifPanel) return;
            notifPanelOpen = !notifPanel.classList.contains('open');
            if (notifPanelOpen) {
                notifPanel.classList.add('open');
                loadTopbarNotifications();
            } else {
                notifPanel.classList.remove('open');
            }
        };

        // Helper to check if element is a bell button or inside it
        function getBellButton(target) {
            if (!target) return null;
            return target.closest('#topbar-bell-btn, .header-btn-bell') || 
                   (target.closest('.header-btn, button') && target.closest('.header-btn, button').querySelector('[data-feather="bell"], svg.feather-bell, i.feather-bell'));
        }

        // Helper to check if element is a mail button or inside it
        function getMailButton(target) {
            if (!target) return null;
            return target.closest('#topbar-mail-btn, .header-btn-mail') || 
                   (target.closest('.header-btn, button') && target.closest('.header-btn, button').querySelector('[data-feather="mail"], svg.feather-mail, i.feather-mail'));
        }

        // Global delegated click handler for Bell & Mail buttons across all roles and pages
        document.addEventListener('click', function (e) {
            const bellBtn = getBellButton(e.target);
            if (bellBtn) {
                e.preventDefault();
                e.stopPropagation();
                toggleTopbarNotifPanel(e);
                return;
            }

            const mailBtn = getMailButton(e.target);
            if (mailBtn) {
                e.preventDefault();
                e.stopPropagation();
                toggleTopbarMsgPanel(e);
                return;
            }

            // Close panels when clicking outside
            const msgPanel = document.getElementById('topbar-message-panel');
            const notifPanel = document.getElementById('topbar-notification-panel');
            
            if (msgPanel && msgPanel.classList.contains('open') && !msgPanel.contains(e.target) && !getMailButton(e.target)) {
                msgPanel.classList.remove('open');
                msgPanelOpen = false;
            }
            if (notifPanel && notifPanel.classList.contains('open') && !notifPanel.contains(e.target) && !getBellButton(e.target)) {
                notifPanel.classList.remove('open');
                notifPanelOpen = false;
            }
        });

        // Universal real-time live search across all tables and cards for any role/page
        function handleUniversalSearch(query) {
            const filter = (query || '').toLowerCase().trim();
            
            // Filter all tables
            const tables = document.querySelectorAll('table');
            tables.forEach(table => {
                if (table.closest('#topbar-message-panel, #topbar-notification-panel')) return;
                const rows = table.querySelectorAll('tbody tr');
                if (rows.length === 0) return;
                
                let visibleCount = 0;
                rows.forEach(row => {
                    if (row.id === 'noSearchResultRow' || row.id === 'emptyInitialRow') return;
                    const text = row.textContent || row.innerText;
                    if (filter === '' || text.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const noResultRow = table.querySelector('#noSearchResultRow');
                if (noResultRow) {
                    noResultRow.style.display = (filter !== '' && visibleCount === 0) ? '' : 'none';
                }
            });

            // Filter searchable cards if any
            const cards = document.querySelectorAll('.searchable-card, .search-card-item');
            cards.forEach(card => {
                const text = card.textContent || card.innerText;
                card.style.display = (filter === '' || text.toLowerCase().indexOf(filter) > -1) ? '' : 'none';
            });
        }

        document.addEventListener('input', function (e) {
            if (e.target && (e.target.classList.contains('search-input') || e.target.id === 'search-input' || e.target.id === 'rekapSearchInput' || e.target.id === 'interviewSearchInput')) {
                handleUniversalSearch(e.target.value);
            }
        });

        function wireTopbarMailButtons() {
            document.querySelectorAll('#topbar-mail-btn, .header-btn-mail').forEach(function(mailBtn) {
                mailBtn.style.cursor = 'pointer';
            });
        }

        function wireTopbarBellButtons() {
            document.querySelectorAll('#topbar-bell-btn, .header-btn-bell').forEach(function(bellBtn) {
                bellBtn.style.cursor = 'pointer';
            });
        }

        function loadTopbarMessages() {
            fetch('/messages?limit=5', { headers: { 'Accept': 'application/json' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => {
                    renderTopbarMessages(data);
                    fetchTopbarUnreadCount();
                })
                .catch(() => {
                    const list = document.getElementById('fab-panel-msgs-list');
                    if (list) {
                        list.innerHTML = '<div class="fab-panel-empty"><a href="' + MESSAGES_URL + '" style="color:#0041c3;font-weight:600;">Lihat semua pesan →</a></div>';
                    }
                });
        }

        function renderTopbarMessages(messages) {
            const list = document.getElementById('fab-panel-msgs-list');
            if (!list) return;
            if (!messages || messages.length === 0) {
                list.innerHTML = '<div class="fab-panel-empty">📭 Belum ada pesan masuk</div>';
                return;
            }
            list.innerHTML = messages.map(function (msg) {
                const timeAgo = formatTimeAgo(msg.created_at);
                return '<div class="fab-panel-msg-item ' + (msg.is_unread ? 'unread' : '') + '" onclick="window.location.href=\'' + MESSAGES_URL + '\'">'
                    + '<span class="msg-time">' + timeAgo + '</span>'
                    + '<div class="msg-title">' + escHtml(msg.judul) + '</div>'
                    + '<div class="msg-from">Dari: ' + escHtml(msg.sender_nama) + ' · ' + escHtml(msg.target_role === 'all' ? '📢 Semua' : msg.target_role) + '</div>'
                    + '</div>';
            }).join('');
        }

        function loadTopbarNotifications() {
            fetch('/announcements', { headers: { 'Accept': 'application/json' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => {
                    renderTopbarNotifications(data);
                    // Mark as read after open
                    fetch('{{ route("announcements.mark_all_read") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        fetchTopbarNotifCount();
                    });
                })
                .catch(() => {
                    const list = document.getElementById('fab-panel-notif-list');
                    if (list) {
                        list.innerHTML = '<div class="fab-panel-empty"><a href="' + ANNOUNCEMENTS_URL + '" style="color:#0041c3;font-weight:600;">Lihat semua pemberitahuan →</a></div>';
                    }
                });
        }

        function renderTopbarNotifications(items) {
            const list = document.getElementById('fab-panel-notif-list');
            if (!list) return;
            if (!items || items.length === 0) {
                list.innerHTML = '<div class="fab-panel-empty">🔔 Belum ada pemberitahuan atau jadwal maintenance aktif</div>';
                return;
            }
            list.innerHTML = items.map(function (item) {
                const timeAgo = formatTimeAgo(item.created_at);
                let tagClass = 'info';
                let tagLabel = '📢 Info';
                if (item.tipe === 'maintenance') {
                    tagClass = 'maintenance';
                    tagLabel = '🛠️ Maintenance';
                } else if (item.tipe === 'penting') {
                    tagClass = 'penting';
                    tagLabel = '⚠️ Penting';
                }

                let timeInfo = '';
                if (item.mulai_pada) {
                    timeInfo = '<div style="font-size:10.5px;color:#d97706;margin-top:3px;font-weight:600;">🕒 ' + escHtml(item.mulai_pada) + (item.selesai_pada ? ' s/d ' + escHtml(item.selesai_pada) : '') + '</div>';
                }

                return '<div class="fab-panel-msg-item ' + (item.is_unread ? 'unread' : '') + '" onclick="window.location.href=\'' + ANNOUNCEMENTS_URL + '\'">'
                    + '<span class="msg-time">' + timeAgo + '</span>'
                    + '<div class="msg-title"><span class="notif-tag ' + tagClass + '">' + tagLabel + '</span>' + escHtml(item.judul) + '</div>'
                    + '<div class="msg-from">' + escHtml(item.isi.length > 80 ? item.isi.substring(0, 80) + '...' : item.isi) + '</div>'
                    + timeInfo
                    + '</div>';
            }).join('');
        }

        function formatTimeAgo(dateStr) {
            const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
            if (diff < 60) return diff + 'd lalu';
            if (diff < 3600) return Math.floor(diff/60) + 'm lalu';
            if (diff < 86400) return Math.floor(diff/3600) + 'j lalu';
            return Math.floor(diff/86400) + 'hr lalu';
        }

        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        function fetchTopbarUnreadCount() {
            fetch('{{ route("messages.unread_count") }}')
                .then(r => r.json())
                .then(data => {
                    const count = data.count || 0;
                    document.querySelectorAll('#topbar-mail-btn, .header-btn-mail, .header-btn').forEach(btn => {
                        if (!btn.querySelector('[data-feather="mail"], svg.feather-mail, i.feather-mail') && !btn.classList.contains('header-btn-mail') && btn.id !== 'topbar-mail-btn') return;
                        let badge = btn.querySelector('.topbar-mail-badge');
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'topbar-mail-badge';
                            badge.style.cssText = 'display:none;position:absolute;top:-4px;right:-4px;background:#ef4444;color:white;border-radius:9999px;font-size:10px;font-weight:bold;min-width:16px;height:16px;line-height:16px;text-align:center;padding:0 4px;';
                            btn.style.position = 'relative';
                            btn.appendChild(badge);
                        }
                        if (count > 0) {
                            badge.textContent = count > 99 ? '99+' : count;
                            badge.style.display = 'block';
                        } else {
                            badge.style.display = 'none';
                        }
                    });
                }).catch(() => {});
        }

        function fetchTopbarNotifCount() {
            fetch('{{ route("announcements.unread_count") }}')
                .then(r => r.json())
                .then(data => {
                    const count = data.count || 0;
                    document.querySelectorAll('#topbar-bell-btn, .header-btn-bell, .header-btn').forEach(btn => {
                        if (!btn.querySelector('[data-feather="bell"], svg.feather-bell, i.feather-bell') && !btn.classList.contains('header-btn-bell') && btn.id !== 'topbar-bell-btn') return;
                        let dot = btn.querySelector('.badge-dot');
                        if (!dot) {
                            dot = document.createElement('div');
                            dot.className = 'badge-dot';
                            btn.style.position = 'relative';
                            btn.appendChild(dot);
                        }
                        dot.style.display = (count > 0) ? 'block' : 'none';
                    });
                }).catch(() => {});
        }

        document.addEventListener('DOMContentLoaded', function () {
            function initTopbar() {
                wireTopbarMailButtons();
                wireTopbarBellButtons();
                fetchTopbarUnreadCount();
                fetchTopbarNotifCount();
            }
            setTimeout(initTopbar, 200);
            setTimeout(initTopbar, 1000);
            setInterval(function () {
                fetchTopbarUnreadCount();
                fetchTopbarNotifCount();
            }, 60000);
        });

        window.addEventListener('load', function () {
            wireTopbarMailButtons();
            wireTopbarBellButtons();
            fetchTopbarUnreadCount();
            fetchTopbarNotifCount();
        });
    })();
    </script>
    @endauth

    <div class="app-layout">
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
            <!-- Logo -->
            <div class="logo-section">
                <div class="logo-mark">
                    <img src="{{ asset('assets/image/logo.png') }}" alt="Logo">
                </div>
                <div class="logo-name">
                    <h1>Azzahra Computer</h1>
                    
                </div>
                <div class="sidebar-toggle" onclick="toggleSidebar()">
                    <i data-feather="chevron-left"></i>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="nav-menu">
                @php 
                    $level = Auth::check() ? Auth::user()->kry_level : ''; 
                    $title = $title ?? '';
                @endphp

                @if ($level == 'Customer Service')
                <div class="nav-group">
                    <div class="nav-group-title">Menu</div>
                    <a href="{{ route('service.index') }}" class="nav-link {{ $title == 'Dashboard' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="home"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="{{ route('service.antrean', 'baru') }}" class="nav-link {{ $title == 'Customer' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="users"></i></div>
                        <span class="nav-text">Customer</span>
                    </a>
                    <a href="{{ route('quickservice.index') }}" class="nav-link {{ $title == 'Quick Service' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="zap"></i></div>
                        <span class="nav-text">Quick Service</span>
                    </a>
                    <a href="{{ url('Service/pembayaran') }}" class="nav-link {{ $title == 'Pembayaran' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="credit-card"></i></div>
                        <span class="nav-text">Pembayaran</span>
                    </a>
                    <a href="{{ url('Service/laporan') }}" class="nav-link {{ $title == 'Laporan' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="activity"></i></div>
                        <span class="nav-text">Laporan</span>
                    </a>
                    <a href="{{ url('Admin/mou') }}" class="nav-link {{ $title == 'Mou' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="file-text"></i></div>
                        <span class="nav-text">Mou</span>
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Management E-Service</div>
                    <a href="{{ url('Admin/order') }}" class="nav-link {{ $title == 'Order' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="shopping-cart"></i></div>
                        <span class="nav-text">Order</span>
                    </a>
                    @php
                        // To be migrated: count sparepart
                        $count_sparepart = 0;
                    @endphp
                    <a href="{{ url('Admin/ketersediaan_sparepart') }}" class="nav-link {{ $title == 'Ketersediaan Sparepart' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="package"></i></div>
                        <span class="nav-text">Ketersediaan Sparepart
                            @if($count_sparepart > 0)
                                <span style="background:red;color:white;border-radius:10px;padding:2px 8px;font-size:12px;margin-left:5px;vertical-align:middle;">
                                    {{ $count_sparepart }}
                                </span>
                            @endif
                        </span>
                    </a>
                    <a href="{{ url('Admin/voucher') }}" class="nav-link {{ $title == 'Voucher Discount' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="percent"></i></div>
                        <span class="nav-text">Voucher Discount</span>
                    </a>
                </div>
                
                @elseif ($level == 'Kasir')
                <div class="nav-group">
                    <div class="nav-group-title">Menu</div>
                    <a href="{{ url('Kasir') }}" class="nav-link {{ $title == 'Customer' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="users"></i></div>
                        <span class="nav-text">Customer</span>
                    </a>
                    <a href="{{ route('quickservice.index') }}" class="nav-link {{ $title == 'Quick Service' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="zap"></i></div>
                        <span class="nav-text">Quick Service</span>
                    </a>
                    <a href="{{ url('Kasir/pembayaran') }}" class="nav-link {{ $title == 'Pembayaran' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="credit-card"></i></div>
                        <span class="nav-text">Pembayaran</span>
                    </a>
                    <a href="{{ url('Kasir/laporan') }}" class="nav-link {{ $title == 'Laporan' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="activity"></i></div>
                        <span class="nav-text">Laporan</span>
                    </a>
                    <a href="{{ url('Admin/mou') }}" class="nav-link {{ $title == 'Mou' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="file-text"></i></div>
                        <span class="nav-text">Mou</span>
                    </a>
                </div>
                
                @elseif ($level == 'Teknisi')
                <div class="nav-group">
                    <div class="nav-group-title">Menu Teknisi</div>
                    <a href="{{ url('Teknisi') }}" class="nav-link {{ $title == 'Dashboard Teknisi' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="home"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                
                @elseif ($level == 'HR')
                 <div class="nav-group">
                        <div class="nav-group-title">Menu HR</div>
                        <a href="{{ url('HR') }}" class="nav-link {{ $title == 'HR Dashboard' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="home"></i></div>
                                <span class="nav-text">Overview</span>
                            </a>
                            <a href="{{ url('HR/karyawan') }}" class="nav-link {{ $title == 'Data Karyawan' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="users"></i></div>
                                <span class="nav-text">Karyawan</span>
                            </a>
                            <a href="{{ url('Customer') }}" class="nav-link {{ $title == 'Customer' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="user-check"></i></div>
                                <span class="nav-text">Customer</span>
                            </a>
                            <a href="{{ url('HR/absensi') }}" class="nav-link {{ $title == 'Absensi Karyawan' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="clock"></i></div>
                                <span class="nav-text">Absensi</span>
                            </a>
                            <a href="{{ url('HR/kpi') }}" class="nav-link {{ ($title == 'KPI Karyawan' || $title == 'KPI' || $title == 'Laporan Mingguan') ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="bar-chart-2"></i></div>
                                <span class="nav-text">KPI</span>
                            </a>
                            @php
                                $sidebar_upcoming_interview = \App\Models\Interview::where('status', 'Menunggu')
                                    ->where('tanggal_waktu', '>=', \Carbon\Carbon::now()->subHours(2))
                                    ->where('tanggal_waktu', '<=', \Carbon\Carbon::now()->addHours(24))
                                    ->count();
                            @endphp
                            <a href="{{ url('HR/interview') }}" class="nav-link {{ $title == 'Interview Kandidat' || $title == 'Jadwal & Hasil Interview' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="user-check"></i></div>
                                <span class="nav-text">Interview</span>
                                @if($sidebar_upcoming_interview > 0)
                                    <span style="background:#f59e0b;color:white;border-radius:10px;padding:1px 7px;font-size:11px;margin-left:auto;font-weight:bold;" title="{{ $sidebar_upcoming_interview }} jadwal mendekati jamnya">
                                        {{ $sidebar_upcoming_interview }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ url('HR/rekap') }}" class="nav-link {{ $title == 'Rekap HR' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="file-text"></i></div>
                                <span class="nav-text">Rekap</span>
                            </a>
                            <a href="{{ url('HR/certificate_generator') }}" class="nav-link {{ $title == 'Generator Sertifikat' ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="award"></i></div>
                                <span class="nav-text">Sertifikat</span>
                            </a>
                             <a href="{{ url('Admin/mou') }}" class="nav-link {{ ($title == 'Mou' || $title == 'Rekap MOU') ? 'active' : '' }}">
                                <div class="nav-icon"><i data-feather="file-text"></i></div>
                                <span class="nav-text">Mou</span>
                            </a>
                        </div>
                @else
                <div class="nav-group">
                    <div class="nav-group-title">Menu</div>
                    <a href="{{ url('Admin') }}" class="nav-link {{ $title == 'Dashboard' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="home"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="{{ url('HR/karyawan') }}" class="nav-link {{ $title == 'Data Karyawan' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="user"></i></div>
                        <span class="nav-text">Karyawan</span>
                    </a>
                    <a href="{{ url('Customer') }}" class="nav-link {{ $title == 'Customer' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="users"></i></div>
                        <span class="nav-text">Customer</span>
                    </a>
                    <a role="button" class="nav-link laporan-toggle {{ $title == 'Laporan' ? 'active' : '' }}" onclick="toggleLaporanDropdown()">
                        <div class="nav-icon"><i data-feather="activity"></i></div>
                        <span class="nav-text">Laporan</span>
                        <div class="dropdown-chevron">
                            <i data-feather="chevron-up" class="w-4 h-4"></i>
                        </div>
                    </a>
                    <div class="nav-dropdown-menu" id="laporanDropdownMenu">
                        <a href="{{ url('Admin/lap_perhari') }}" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="book-open"></i></div>
                            <span class="nav-text">Harian</span>
                        </a>
                        <a href="{{ url('Admin/laporan') }}" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="book"></i></div>
                            <span class="nav-text">Bulanan</span>
                        </a>
                    </div>
                    <a href="{{ url('Admin/mou') }}" class="nav-link {{ $title == 'Mou' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="file-text"></i></div>
                        <span class="nav-text">Mou</span>
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Transaksi</div>
                    <a href="{{ url('Admin/cus_baru') }}" class="nav-link {{ $title == 'Transaksi-Baru' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="user-plus"></i></div>
                        <span class="nav-text">Baru</span>
                    </a>
                    <a href="{{ url('Admin/cus_konf_bank') }}" class="nav-link {{ $title == 'Transaksi-Bank Transfer' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="credit-card"></i></div>
                        <span class="nav-text">Bank Transfer</span>
                    </a>
                    <a href="{{ url('Admin/cus_proses') }}" class="nav-link {{ $title == 'Transaksi-Proses' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="play-circle"></i></div>
                        <span class="nav-text">Proses</span>
                    </a>

                    @php
                        // To be migrated: count transaksi diproses
                        $konf = 0;
                    @endphp
                    <a href="{{ url('Admin/cus_konf') }}" class="nav-link {{ $title == 'Transaksi-Konfirmasi' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="bell"></i></div>
                        <span class="nav-text">Konfirmasi</span>
                        @if($konf > 0)
                            <span class="nav-badge">{{ $konf }}</span>
                        @endif
                    </a>
                     <a href="{{ url('Admin/voucher') }}" class="nav-link {{ $title == 'Discount' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="message-square"></i></div>
                        <span class="nav-text">Discount</span>
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Management E-Service</div>                   
                    <a href="{{ url('Produk') }}" class="nav-link {{ $title == 'Produk' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="package"></i></div>
                        <span class="nav-text">Produk</span>
                    </a>                    
                    <a href="{{ url('Admin/order') }}" class="nav-link {{ $title == 'Order' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="shopping-cart"></i></div>
                        <span class="nav-text">Order</span>
                    </a>
                    <a role="button" class="nav-link order-approval-toggle {{ str_contains($title ?? '', 'Order Approval') ? 'active' : '' }}" onclick="document.getElementById('orderApprovalDropdownMenu').classList.toggle('active'); this.classList.toggle('active');">
                        <div class="nav-icon"><i data-feather="check-circle"></i></div>
                        <span class="nav-text">Order Approval</span>
                        <div class="dropdown-chevron"><i data-feather="chevron-down" class="w-4 h-4"></i></div>
                    </a>
                    <div class="nav-dropdown-menu" id="orderApprovalDropdownMenu" style="{{ str_contains($title ?? '', 'Order Approval') ? 'display:block;' : '' }}">
                        <a href="{{ route('admin.order_approval.oow') }}" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="corner-down-right"></i></div>
                            <span class="nav-text">Out of Warranty</span>
                        </a>
                        <a href="{{ route('admin.order_approval.iw') }}" class="dropdown-menu-item">
                            <div class="nav-icon"><i data-feather="corner-down-right"></i></div>
                            <span class="nav-text">In Warranty</span>
                        </a>
                    </div>
                    <a href="{{ url('Admin/voucher') }}" class="nav-link {{ $title == 'Voucher Discount' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="percent"></i></div>
                        <span class="nav-text">Voucher Discount</span>
                    </a>
                    @php
                        // To be migrated
                        $count_sparepart = 0;
                    @endphp
                    <a href="{{ url('Admin/ketersediaan_sparepart') }}" class="nav-link {{ $title == 'Ketersediaan Sparepart' ? 'active' : '' }}">
                        <div class="nav-icon"><i data-feather="package"></i></div>
                        <span class="nav-text">Ketersediaan Sparepart
                            @if($count_sparepart > 0)
                                <span style="background:red;color:white;border-radius:10px;padding:2px 8px;font-size:12px;margin-left:5px;vertical-align:middle;">
                                    {{ $count_sparepart }}
                                </span>
                            @endif
                        </span>
                    </a>
                </div>
                @endif
            </nav>

            <!-- Footer -->
            <div class="sidebar-footer">
                <div class="user-profile" onclick="toggleUserDropdown()">
                    <div class="user-avatar">{{ Auth::check() ? strtoupper(substr(Auth::user()->kry_nama, 0, 1)) : '' }}</div>
                    <div class="user-info">
                        <h4>{{ Auth::check() ? Auth::user()->kry_nama : '' }}</h4>
                        <p>{{ Auth::check() ? Auth::user()->kry_level : '' }}</p>
                    </div>
                    <div class="dropdown-chevron">
                        <i data-feather="chevron-up" class="w-4 h-4"></i>
                    </div>
                </div>
                
                <!-- Dropdown Menu -->
                <div class="user-dropdown-menu" id="userDropdownMenu">
                    @if ($level != 'Customer Service' && $level != 'Kasir' && $level != 'Teknisi')
                    <a href="{{ url('HR/karyawan') }}" class="dropdown-menu-item">
                        <div class="nav-icon"><i data-feather="user-plus"></i></div>
                        <span class="nav-text">Add Account</span>
                    </a>
                    @endif
                    <a href="{{ url('Auth/reset') }}" class="dropdown-menu-item">
                        <div class="nav-icon"><i data-feather="lock"></i></div>
                        <span class="nav-text">Reset Password</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="dropdown-menu-item logout">
                            <div class="nav-icon"><i data-feather="log-out"></i></div>
                            <span class="nav-text">Logout</span>
                        </a>
                    </form>
                </div>
            </div>
        </aside>
        
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        // Function to toggle sidebar collapse
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        // Function to toggle mobile sidebar
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('mobile-active');
            overlay.classList.toggle('active');
        }

        // Function to close mobile sidebar
        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('mobile-active');
            overlay.classList.remove('active');
        }

        // Function to toggle laporan dropdown
        function toggleLaporanDropdown() {
            const dropdown = document.getElementById('laporanDropdownMenu');
            const toggle = document.querySelector('.laporan-toggle');
            dropdown.classList.toggle('active');
            toggle.classList.toggle('active');
        }

        // Function to toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdownMenu');
            const profile = document.querySelector('.user-profile');
            dropdown.classList.toggle('active');
            profile.classList.toggle('active');
        }

        // Hide preloader on load
        window.addEventListener('load', function() {
            setTimeout(function() {
                var preloader = document.getElementById('midone-preloader');
                if(preloader) {
                    preloader.style.opacity = '0';
                    setTimeout(function() { preloader.style.display = 'none'; }, 500);
                }
            }, 300); // Add a tiny delay for visual effect
        });

        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
            
    <!-- BEGIN: JS Assets-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/template/beck/dist/js/app.js') }}"></script>
    <!-- SweetAlert -->
    <script src="{{ asset('assets/file/alert/alertscript.js') }}"></script>
    <!-- myjs -->
    <script src="{{ asset('assets/file/js/rupiah.js') }}"></script>
    <script src="{{ asset('assets/file/js/modal.js') }}"></script>
    
    @if(session('just_logged_in'))
    <script>
        sessionStorage.setItem('just_logged_in', 'true');
        {{ session()->forget('just_logged_in') }}
    </script>
    @endif

    <script>
        // Init Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
            
            // Re-init setiap 1 detik untuk icon yang dimuat via AJAX
            setInterval(function() {
                feather.replace();
            }, 1000);
        }
    </script>

    <!-- Wizard Step Tab Active State Override -->
    <script>
    (function($) {
        // Override tab click handler to support wizard active step coloring
        $('body').off('click', 'a[data-toggle="tab"]').on('click', 'a[data-toggle="tab"]', function(e) {
            var $this = $(this);
            var $navTabs = $this.closest('.nav-tabs');

            // Set active class on nav
            $navTabs.find('a[data-toggle="tab"]').removeClass('active');
            $this.addClass('active');

            // If inside a wizard, toggle blue/gray styling
            if ($navTabs.hasClass('wizard')) {
                $navTabs.find('a[data-toggle="tab"]')
                    .removeClass('text-white bg-theme-1')
                    .addClass('text-gray-600 bg-gray-200');
                $this
                    .removeClass('text-gray-600 bg-gray-200')
                    .addClass('text-white bg-theme-1');
            }

            // Activate target pane
            var elementId = $this.attr('data-target');
            $(elementId).closest('.tab-content').find('.tab-content__pane').removeClass('active');
            $(elementId).addClass('active');
        });

        // Disable DataTables responsive collapsing to keep table rows horizontal
        $(document).ready(function() {
            if (typeof $.fn.DataTable !== 'undefined') {
                $('.datatable').each(function() {
                    if ($.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable().destroy();
                    }
                    $(this).DataTable({
                        responsive: false,
                        autoWidth: false,
                        paging: false,
                        info: false
                    });
                });
            }
        });
    })(jQuery);
    </script>
    <!-- END: JS Assets-->
     
    <!-- Dashboard Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        feather.replace();
        
        // Initialize Weekly Chart (only if not already initialized by page-specific script)
        const ctx = document.getElementById('weeklyChart');
        if (ctx && !ctx.chart) {
            const weekly_labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
            const weekly_data = [12, 19, 15, 25, 22, 30, 28];

            ctx.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weekly_labels,                            
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Animate traffic progress bars
        setTimeout(() => {
            document.querySelectorAll('.traffic-progress-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => bar.style.width = width, 100);
            });
        }, 500);
        
        // Filter buttons functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
    </script>

    <!-- Script for Sidebar and User Dropdown -->
    <script>
        // Toggle User Dropdown
        function toggleUserDropdown() {
            const userProfile = document.querySelector('.user-profile');
            const dropdownMenu = document.getElementById('userDropdownMenu');

            if (userProfile) userProfile.classList.toggle('active');
            if (dropdownMenu) dropdownMenu.classList.toggle('active');

            // Refresh feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Toggle Laporan Dropdown
        function toggleLaporanDropdown() {
            const laporanToggle = document.querySelector('.laporan-toggle');
            const dropdownMenu = document.getElementById('laporanDropdownMenu');

            if (laporanToggle) laporanToggle.classList.toggle('active');
            if (dropdownMenu) dropdownMenu.classList.toggle('active');

            // Refresh feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const sidebarFooter = document.querySelector('.sidebar-footer');
            const userProfile = document.querySelector('.user-profile');
            const userDropdownMenu = document.getElementById('userDropdownMenu');

            if (sidebarFooter && !sidebarFooter.contains(event.target)) {
                if (userProfile) userProfile.classList.remove('active');
                if (userDropdownMenu) userDropdownMenu.classList.remove('active');
            }
        });

        // Close laporan dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const navMenu = document.querySelector('.nav-menu');
            const laporanToggle = document.querySelector('.laporan-toggle');
            const laporanDropdownMenu = document.getElementById('laporanDropdownMenu');

            if (!navMenu || !laporanToggle || !laporanDropdownMenu) return;

            if (!navMenu.contains(event.target)) {
                laporanToggle.classList.remove('active');
                laporanDropdownMenu.classList.remove('active');
            }
        });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const userProfile = document.querySelector('.user-profile');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            const laporanToggle = document.querySelector('.laporan-toggle');
            const laporanDropdownMenu = document.getElementById('laporanDropdownMenu');

            if (sidebar) sidebar.classList.toggle('collapsed');

            // Close dropdown when collapsing sidebar
            if (sidebar && sidebar.classList.contains('collapsed')) {
                if (userProfile) userProfile.classList.remove('active');
                if (userDropdownMenu) userDropdownMenu.classList.remove('active');
                if (laporanToggle) laporanToggle.classList.remove('active');
                if (laporanDropdownMenu) laporanDropdownMenu.classList.remove('active');
            }

            if (sidebar) localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        // Toggle Mobile Sidebar
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.toggle('mobile-active');
            if (overlay) overlay.classList.toggle('active');
        }

        // Remember sidebar state
        window.addEventListener('DOMContentLoaded', () => {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const sidebar = document.getElementById('sidebar');
            if (isCollapsed && window.innerWidth > 1024 && sidebar) {
                sidebar.classList.add('collapsed');
            }

            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // =====================================================
            //  DASHBOARD CURTAIN REVEAL — only fires on first login
            // =====================================================
            @if(session('show_curtain'))
            const curtainLeft  = document.getElementById('dashCurtainLeft');
            const curtainRight = document.getElementById('dashCurtainRight');
            const curtainBadge = document.getElementById('dashCurtainBadge');
            const curtainOverlay = document.getElementById('dashCurtainOverlay');

            // Step 1: After 350ms, fade badge and open curtains
            setTimeout(() => {
                if (curtainBadge) {
                    curtainBadge.style.opacity = '0';
                    curtainBadge.style.transform = 'scale(0.85)';
                }
                if (curtainLeft)  curtainLeft.style.transform  = 'translateX(-102%)';
                if (curtainRight) curtainRight.style.transform = 'translateX(102%)';
            }, 350);

            // Step 2: After curtains fully open, hide overlay entirely
            setTimeout(() => {
                if (curtainOverlay) {
                    curtainOverlay.style.opacity = '0';
                    curtainOverlay.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => {
                        if (curtainOverlay) curtainOverlay.style.display = 'none';
                    }, 300);
                }
            }, 1300);
            @endif
        });
    </script>
</body>
</html>
