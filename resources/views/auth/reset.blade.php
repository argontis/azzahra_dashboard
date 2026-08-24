<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Azzahra Computer</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/file/alert/animet.css') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #090d16;
            color: #1e293b;
            position: relative;
            overflow-x: hidden;
            padding: 20px;
        }

        /* DYNAMIC CYBER GRID & PARTICLES BACKDROP */
        .cyber-grid-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-image: 
                linear-gradient(rgba(56, 189, 248, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56, 189, 248, 0.05) 1px, transparent 1px);
            background-size: 45px 45px;
            background-position: center center;
            z-index: 1;
            pointer-events: none;
        }

        #techCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 2;
            pointer-events: none;
        }

        /* Glowing Blobs */
        .glow-blob {
            position: absolute;
            filter: blur(90px);
            z-index: 3;
            opacity: 0.55;
            pointer-events: none;
            border-radius: 50%;
            animation: floatBlob 14s infinite alternate ease-in-out;
        }

        .blob-1 {
            top: -10%;
            left: -5%;
            width: 600px;
            height: 600px;
            background: linear-gradient(135deg, #0284c7, #6366f1);
        }

        .blob-2 {
            bottom: -15%;
            right: -5%;
            width: 650px;
            height: 650px;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            animation-duration: 20s;
            animation-delay: -6s;
        }

        .blob-3 {
            top: 35%;
            left: 30%;
            width: 450px;
            height: 450px;
            background: linear-gradient(135deg, #2563eb, #10b981);
            animation-duration: 18s;
            animation-delay: -9s;
        }

        @keyframes floatBlob {
            0% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(40px, -50px) scale(1.1); }
            100% { transform: translate(-30px, 30px) scale(0.95); }
        }

        /* Main Card Container */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 92%;
            max-width: 1080px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5), 0 0 45px rgba(56, 189, 248, 0.25);
            animation: wrapperEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
        }

        @keyframes wrapperEntrance {
            from {
                opacity: 0;
                transform: translateY(25px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* LEFT PANEL */
        .login-left {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #1e40af 100%);
            color: #fff;
            padding: 24px 32px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 60%);
            pointer-events: none;
            animation: rotateBg 25s linear infinite;
        }

        @keyframes rotateBg {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .left-header {
            position: relative;
            z-index: 2;
        }

        /* ULTRA HIGH-GLOW LOGO STYLES (PURE 2D) */
        .logo-glow-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .logo-aura-ring-1 {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, #38bdf8, #818cf8, #c084fc, #f472b6, #34d399, #38bdf8);
            filter: blur(10px);
            opacity: 0.9;
            animation: rotateAura1 7s linear infinite;
        }

        .logo-aura-ring-2 {
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            background: conic-gradient(from 180deg, #f472b6, #38bdf8, #34d399, #818cf8, #f472b6);
            filter: blur(7px);
            opacity: 0.8;
            animation: rotateAura2 4.5s linear infinite reverse;
        }

        @keyframes rotateAura1 {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes rotateAura2 {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .logo-box {
            position: relative;
            z-index: 2;
            background: #ffffff;
            padding: 8px 14px;
            border-radius: 18px;
            box-shadow: 
                0 0 25px rgba(255, 255, 255, 1),
                0 0 50px rgba(56, 189, 248, 0.8),
                0 0 75px rgba(168, 85, 247, 0.6),
                inset 0 0 10px rgba(255, 255, 255, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            animation: logoFloat 4s ease-in-out infinite alternate;
            overflow: hidden;
        }

        .logo-glow-wrapper:hover .logo-box {
            transform: scale(1.06) rotate(-2deg);
            box-shadow: 
                0 0 35px rgba(255, 255, 255, 1),
                0 0 70px rgba(56, 189, 248, 0.95),
                0 0 100px rgba(244, 114, 182, 0.85);
        }

        @keyframes logoFloat {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-6px); }
        }

        .logo-img {
            height: 42px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 3px 5px rgba(0,0,0,0.1));
        }

        .login-left h1 {
            font-size: 21px;
            font-weight: 800;
            line-height: 1.35;
            margin-top: 4px;
            margin-bottom: 8px;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
            animation: fadeInText 0.8s ease-out 0.2s both;
        }

        .superapps-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11.5px;
            font-weight: 600;
            color: #e0e7ff;
            max-width: fit-content;
            line-height: 1.4;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            animation: fadeInText 0.8s ease-out 0.4s both;
        }

        .superapps-badge span {
            display: inline-block;
            width: 7px;
            height: 7px;
            background: #34d399;
            border-radius: 50%;
            box-shadow: 0 0 10px #34d399;
            animation: badgeGlow 1.5s infinite alternate;
        }

        @keyframes badgeGlow {
            from { opacity: 0.4; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1.2); }
        }

        @keyframes fadeInText {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* TECH SVG HUB */
        .tech-service-container {
            position: relative;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 8px;
            padding: 4px 0;
        }

        .computer-station-svg-wrapper {
            width: 100%;
            max-width: 350px;
            position: relative;
            filter: drop-shadow(0 12px 25px rgba(0, 0, 0, 0.4));
            animation: stationFloat 5s ease-in-out infinite alternate;
        }

        @keyframes stationFloat {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-12px) rotate(1deg); }
        }

        .computer-station-svg {
            width: 100%;
            height: auto;
            overflow: visible;
        }

        .spinning-fan {
            transform-origin: 48px 48px;
            animation: spinFan 1.5s linear infinite;
        }

        .spinning-fan.fan-bottom {
            transform-origin: 48px 110px;
            animation: spinFan 1.2s linear infinite reverse;
        }

        @keyframes spinFan {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .rotating-gear.gear-left {
            transform-origin: 90px 80px;
            animation: spinGear 8s linear infinite;
        }

        .rotating-gear.gear-right {
            transform-origin: 410px 75px;
            animation: spinGear 6s linear infinite reverse;
        }

        @keyframes spinGear {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .pulse-dot {
            animation: dotGlow 2s ease-in-out infinite alternate;
        }
        .dot1 { animation-delay: 0s; }
        .dot2 { animation-delay: 0.6s; }
        .dot3 { animation-delay: 1.2s; }

        @keyframes dotGlow {
            0% { opacity: 0.3; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1.4); }
        }

        /* Floating Badges */
        .tech-badge {
            position: absolute;
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(56, 189, 248, 0.4);
            backdrop-filter: blur(8px);
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 11.5px;
            font-weight: 700;
            color: #f8fafc;
            display: flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            z-index: 10;
            animation: badgeFloat 3.5s ease-in-out infinite alternate;
        }

        .badge-1 { top: 10px; left: -10px; animation-delay: 0s; }
        .badge-2 { top: 48px; right: -12px; animation-delay: 1.2s; border-color: rgba(168, 85, 247, 0.4); }
        .badge-3 { bottom: 15px; left: -8px; animation-delay: 2.4s; border-color: rgba(52, 211, 153, 0.4); }

        @keyframes badgeFloat {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(-8px) scale(1.04); }
        }

        /* RIGHT PANEL */
        .login-right {
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            background: #ffffff;
            position: relative;
            z-index: 2;
        }

        .form-header {
            margin-bottom: 14px;
        }

        .form-header h2 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }

        .form-header p {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.4;
        }

        .alert-box {
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 12.5px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        /* Clean Input Styling */
        .input-group {
            margin-bottom: 12px;
            position: relative;
        }

        .input-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 5px;
        }

        .login__input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 13px;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 13.5px;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login__input:focus {
            background-color: #ffffff;
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.18), 0 8px 16px -4px rgba(99, 102, 241, 0.15);
            transform: translateY(-2px);
        }

        .login__input::placeholder {
            color: #94a3b8;
            font-size: 13px;
        }

        /* Buttons Container */
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 8px;
            margin-bottom: 14px;
        }

        .btn-change {
            flex: 1.6;
            padding: 13px;
            border-radius: 13px;
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 50%, #0284c7 100%);
            background-size: 200% auto;
            color: #ffffff;
            border: none;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px -4px rgba(79, 70, 229, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-change:hover {
            background-position: right center;
            box-shadow: 0 12px 25px -4px rgba(79, 70, 229, 0.6);
            transform: translateY(-2px);
        }

        .btn-change:active {
            transform: translateY(0);
        }

        .btn-login {
            flex: 1;
            padding: 13px;
            border-radius: 13px;
            background: #f1f5f9;
            color: #334155;
            border: 1.5px solid #cbd5e1;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn-login:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: translateY(-2px);
        }

        /* Footer Info */
        .footer-card {
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
            margin-top: 6px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .admin-note {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
            text-align: center;
        }

        .live-widget {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            border: 1.5px solid #dbeafe;
            padding: 9px 15px;
            border-radius: 13px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.06);
        }

        #greetingText {
            font-size: 13px;
            font-weight: 700;
            color: #1e40af;
        }

        #clockText {
            font-family: 'JetBrains Mono', monospace;
            color: #0284c7;
            font-size: 12.5px;
            font-weight: 800;
            background: #ffffff;
            padding: 3px 8px;
            border-radius: 7px;
            border: 1px solid #e0f2fe;
        }

        @media (max-width: 992px) {
            .login-wrapper {
                grid-template-columns: 1fr;
                max-width: 500px;
                max-height: none;
            }
            .login-left {
                display: none;
            }
            .login-right {
                padding: 36px 28px;
            }
        }
    </style>
</head>
<body>

    <!-- Cyber Matrix Grid Background -->
    <div class="cyber-grid-bg"></div>

    <!-- Tech Interactive Particles Canvas -->
    <canvas id="techCanvas"></canvas>

    <!-- Glowing Dynamic Color Blobs -->
    <div class="glow-blob blob-1"></div>
    <div class="glow-blob blob-2"></div>
    <div class="glow-blob blob-3"></div>

    <!-- Main Card -->
    <div class="login-wrapper">

        <!-- LEFT PANEL -->
        <div class="login-left">
            <div class="left-header">
                <!-- DUAL-RING ULTRA HIGH GLOW LOGO CONTAINER -->
                <div class="logo-glow-wrapper">
                    <div class="logo-aura-ring-1"></div>
                    <div class="logo-aura-ring-2"></div>
                    <div class="logo-box">
                        <img src="{{ asset('assets/image/logo.png') }}" alt="Logo Azzahra Computer" class="logo-img">
                    </div>
                </div>

                <h1>Demi keamanan account anda,<br>rubahlah password account anda secara berkala.</h1>
                <div class="superapps-badge">
                    <span></span>
                    Sistem Manajemen Informasi Azzahra Computer Tegal
                </div>
            </div>

            <!-- TECH SERVICE STATION SVG GRAPHIC -->
            <div class="tech-service-container">
                <!-- Badges -->
                <div class="tech-badge badge-1">
                    <span>⚡</span> Keamanan Akun
                </div>
                <div class="tech-badge badge-2">
                    <span>🛡️</span> Proteksi Kredensial
                </div>
                <div class="tech-badge badge-3">
                    <span>🔒</span> Enkripsi Sandi
                </div>

                <!-- Animated SVG Station -->
                <div class="computer-station-svg-wrapper">
                    <svg class="computer-station-svg" viewBox="0 0 500 350" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="cyberGrad1" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#0284c7" />
                                <stop offset="100%" stop-color="#6366f1" />
                            </linearGradient>
                            <linearGradient id="neonCyan" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#38bdf8" />
                                <stop offset="100%" stop-color="#818cf8" />
                            </linearGradient>
                            <filter id="neonGlowFilter" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="5" result="blur" />
                                <feComposite in="SourceGraphic" in2="blur" operator="over" />
                            </filter>
                        </defs>

                        <!-- Shadow -->
                        <ellipse cx="250" cy="335" rx="200" ry="16" fill="rgba(0,0,0,0.3)" filter="blur(8px)" />

                        <!-- Circuit Tracks -->
                        <g stroke="#475569" stroke-width="1.5" opacity="0.45" stroke-dasharray="6,6">
                            <path d="M 60 120 L 150 120 L 190 160" />
                            <path d="M 440 100 L 350 100 L 310 140" />
                            <path d="M 80 260 L 140 260 L 170 230" />
                            <path d="M 420 280 L 360 280 L 330 250" />
                        </g>

                        <!-- Pulsing Circuit Dots -->
                        <circle class="pulse-dot dot1" cx="60" cy="120" r="4" fill="#38bdf8" filter="url(#neonGlowFilter)" />
                        <circle class="pulse-dot dot2" cx="440" cy="100" r="4" fill="#a855f7" filter="url(#neonGlowFilter)" />
                        <circle class="pulse-dot dot3" cx="80" cy="260" r="4" fill="#34d399" filter="url(#neonGlowFilter)" />

                        <!-- ROTATING GEAR 1 (Left) -->
                        <g class="rotating-gear gear-left" transform="translate(70, 75)">
                            <circle cx="0" cy="0" r="22" fill="none" stroke="#38bdf8" stroke-width="3" opacity="0.85" />
                            <circle cx="0" cy="0" r="10" fill="none" stroke="#38bdf8" stroke-width="2" />
                            <rect x="-3" y="-28" width="6" height="8" rx="2" fill="#38bdf8" />
                            <rect x="-3" y="20" width="6" height="8" rx="2" fill="#38bdf8" />
                            <rect x="-28" y="-3" width="8" height="6" rx="2" fill="#38bdf8" />
                            <rect x="20" y="-3" width="8" height="6" rx="2" fill="#38bdf8" />
                        </g>

                        <!-- ROTATING GEAR 2 (Right) -->
                        <g class="rotating-gear gear-right" transform="translate(430, 75)">
                            <circle cx="0" cy="0" r="18" fill="none" stroke="#818cf8" stroke-width="3" opacity="0.85" />
                            <circle cx="0" cy="0" r="8" fill="none" stroke="#818cf8" stroke-width="2" />
                            <rect x="-2.5" y="-23" width="5" height="7" rx="1.5" fill="#818cf8" />
                            <rect x="-2.5" y="16" width="5" height="7" rx="1.5" fill="#818cf8" />
                            <rect x="-23" y="-2.5" width="7" height="5" rx="1.5" fill="#818cf8" />
                            <rect x="16" y="-2.5" width="7" height="5" rx="1.5" fill="#818cf8" />
                        </g>

                        <!-- DESK -->
                        <rect x="90" y="245" width="320" height="14" rx="7" fill="#1e293b" stroke="#38bdf8" stroke-width="1.5" />
                        <rect x="130" y="259" width="14" height="75" rx="4" fill="#0f172a" stroke="#334155" stroke-width="1" />
                        <rect x="356" y="259" width="14" height="75" rx="4" fill="#0f172a" stroke="#334155" stroke-width="1" />

                        <!-- MAIN MONITOR -->
                        <rect x="175" y="105" width="150" height="100" rx="10" fill="#090d16" stroke="#38bdf8" stroke-width="2.5" filter="url(#neonGlowFilter)" />
                        <rect x="183" y="113" width="134" height="84" rx="6" fill="#0f172a" />
                        
                        <!-- Screen Lock Icon Graphics -->
                        <circle cx="250" cy="145" r="16" fill="none" stroke="#38bdf8" stroke-width="2" />
                        <rect x="240" y="145" width="20" height="16" rx="3" fill="#38bdf8" />
                        <circle cx="250" cy="152" r="2" fill="#0f172a" />
                        <text x="250" y="180" text-anchor="middle" font-family="'JetBrains Mono', monospace" font-size="9" fill="#38bdf8" font-weight="700">SECURE PASSWORD</text>

                        <!-- Monitor Stand -->
                        <polygon points="240,205 260,205 264,245 236,245" fill="#1e293b" stroke="#38bdf8" stroke-width="1" />
                        <rect x="220" y="243" width="60" height="6" rx="3" fill="#38bdf8" />

                        <!-- GAMING PC TOWER -->
                        <rect x="100" y="145" width="65" height="100" rx="8" fill="#090d16" stroke="#a855f7" stroke-width="2" filter="url(#neonGlowFilter)" />
                        <rect x="106" y="152" width="53" height="86" rx="5" fill="#1e1b4b" opacity="0.85" />
                        
                        <!-- Spinning Fan in PC -->
                        <g class="spinning-fan" transform="translate(132, 195)">
                            <circle cx="0" cy="0" r="18" fill="#0f172a" stroke="#38bdf8" stroke-width="2" />
                            <circle cx="0" cy="0" r="5" fill="#38bdf8" />
                            <path d="M 0 0 L -3 -15 A 15 15 0 0 1 3 -15 Z" fill="#38bdf8" opacity="0.9" />
                            <path d="M 0 0 L 15 -3 A 15 15 0 0 1 15 3 Z" fill="#38bdf8" opacity="0.9" />
                            <path d="M 0 0 L 3 15 A 15 15 0 0 1 -3 15 Z" fill="#38bdf8" opacity="0.9" />
                            <path d="M 0 0 L -15 3 A 15 15 0 0 1 -15 -3 Z" fill="#38bdf8" opacity="0.9" />
                        </g>

                        <!-- Floating Badges -->
                        <g class="tool-badge-floating">
                            <circle cx="50" cy="180" r="24" fill="#1e1b4b" stroke="#38bdf8" stroke-width="2" filter="url(#neonGlowFilter)" />
                            <text x="50" y="187" text-anchor="middle" font-size="20">🔑</text>
                        </g>
                        
                        <g class="chip-badge-floating">
                            <rect x="330" y="280" width="38" height="38" rx="8" fill="#0f172a" stroke="#34d399" stroke-width="2" filter="url(#neonGlowFilter)" />
                            <text x="349" y="305" text-anchor="middle" font-size="18">🛡️</text>
                        </g>
                    </svg>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="login-right">
            <div class="form-header">
                <h2>Reset Password</h2>
                <p>Perbarui kata sandi akun Anda untuk menjaga keamanan data</p>
            </div>

            @if(session('gagal'))
                <div class="alert-box alert-danger">
                    <span>⚠️</span>
                    <span>{{ session('gagal') }}</span>
                </div>
            @endif
            @if(session('sukses'))
                <div class="alert-box alert-success">
                    <span>✅</span>
                    <span>{{ session('sukses') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="alert-box alert-danger">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="post" action="{{ route('password.reset.post') }}">
                @csrf
                <div class="input-group">
                    <label class="input-label">Username</label>
                    <input type="text" name="username" class="login__input" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus autocomplete="username">
                </div>

                <div class="input-group">
                    <label class="input-label">Password Baru</label>
                    <input type="password" name="pswd" class="login__input" placeholder="Masukkan password baru" required autocomplete="new-password">
                </div>

                <div class="input-group">
                    <label class="input-label">Konfirmasi Password Baru</label>
                    <input type="password" name="pswd_confirmation" class="login__input" placeholder="Ulangi password baru" required autocomplete="new-password">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-change">
                        <span>Change Password</span>
                    </button>
                    <a href="{{ route('login') }}" class="btn-login">
                        <span>Login</span>
                    </a>
                </div>
            </form>

            <div class="footer-card">
                <div class="admin-note">
                    Jika Anda lupa username atau mengalami kendala,<br>
                    silahkan hubungi administrator sistem.
                </div>

                <div class="live-widget">
                    <div id="greetingText">Reset Password Center</div>
                    <div id="clockText">🕒 <span id="clock">00:00:00</span></div>
                </div>
            </div>
        </div>
    </div>

<script>
    function updateGreeting() {
        const hour = new Date().getHours();
        let greeting = "Selamat datang";

        if (hour < 11) greeting = "Selamat pagi ☀️";
        else if (hour < 15) greeting = "Selamat siang 🌤️";
        else if (hour < 18) greeting = "Selamat sore 🌇";
        else greeting = "Selamat malam 🌙";

        const elem = document.getElementById("greetingText");
        if(elem) elem.innerText = greeting;
    }

    function updateClock() {
        const now = new Date();
        const clockElem = document.getElementById("clock");
        if(clockElem) {
            clockElem.innerText = now.toLocaleTimeString('id-ID', { hour12: false });
        }
    }

    updateGreeting();
    updateClock();
    setInterval(updateClock, 1000);

    // =====================================================
    //  INTERACTIVE HIGH-TECH CYBER CANVAS ANIMATION
    //  (Digital Circuit Nodes + Laser Lines + Data Streams)
    // =====================================================
    const canvas = document.getElementById('techCanvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];
        let dataPackets = [];
        let mouse = { x: null, y: null, radius: 160 };

        function resizeCanvas() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        window.addEventListener('mouseleave', () => {
            mouse.x = null;
            mouse.y = null;
        });

        // Digital Circuit Particle Class
        class CyberNode {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.9;
                this.vy = (Math.random() - 0.5) * 0.9;
                this.radius = Math.random() * 2.2 + 1.2;
                this.baseAlpha = Math.random() * 0.5 + 0.35;
                this.alpha = this.baseAlpha;
                
                const colors = ['#38bdf8', '#818cf8', '#34d399', '#f472b6', '#a78bfa'];
                this.color = colors[Math.floor(Math.random() * colors.length)];
                this.pulseSpeed = Math.random() * 0.03 + 0.01;
                this.pulseAngle = Math.random() * Math.PI * 2;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                // Bounce from borders
                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;

                // Gentle pulsing glow
                this.pulseAngle += this.pulseSpeed;
                this.alpha = this.baseAlpha + Math.sin(this.pulseAngle) * 0.2;

                // Interactive mouse repulsion/connection
                if (mouse.x !== null && mouse.y !== null) {
                    const dx = mouse.x - this.x;
                    const dy = mouse.y - this.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < mouse.radius) {
                        const force = (mouse.radius - dist) / mouse.radius;
                        this.x -= (dx / dist) * force * 1.5;
                        this.y -= (dy / dist) * force * 1.5;
                        this.alpha = Math.min(this.alpha + 0.3, 1);
                    }
                }
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.globalAlpha = Math.max(0.1, this.alpha);
                ctx.shadowBlur = 10;
                ctx.shadowColor = this.color;
                ctx.fill();
                ctx.globalAlpha = 1;
                ctx.shadowBlur = 0;
            }
        }

        // Floating Tech Code Data Particle Class
        class DataBit {
            constructor() {
                this.reset();
                this.y = Math.random() * height;
            }

            reset() {
                this.x = Math.random() * width;
                this.y = height + Math.random() * 50;
                this.vy = -(Math.random() * 0.6 + 0.3);
                this.text = ['01', '10', '101', '010', 'CPU', 'RAM', 'AZZAHRA', 'SYS', '⚡', 'OK', '1100'][Math.floor(Math.random() * 11)];
                this.color = Math.random() > 0.4 ? '#38bdf8' : '#34d399';
                this.alpha = Math.random() * 0.3 + 0.15;
                this.size = Math.random() * 3 + 9;
            }

            update() {
                this.y += this.vy;
                if (this.y < -30) this.reset();
            }

            draw() {
                ctx.font = `600 ${this.size}px 'JetBrains Mono', monospace`;
                ctx.fillStyle = this.color;
                ctx.globalAlpha = this.alpha;
                ctx.fillText(this.text, this.x, this.y);
                ctx.globalAlpha = 1;
            }
        }

        // Initialize particles
        const nodeCount = Math.min(Math.floor((window.innerWidth * window.innerHeight) / 16000), 75);
        for (let i = 0; i < nodeCount; i++) {
            particles.push(new CyberNode());
        }

        for (let i = 0; i < 18; i++) {
            dataPackets.push(new DataBit());
        }

        // Main Animation Loop
        function animateBackground() {
            ctx.clearRect(0, 0, width, height);

            // 1. Draw floating data bits
            for (let i = 0; i < dataPackets.length; i++) {
                dataPackets[i].update();
                dataPackets[i].draw();
            }

            // 2. Draw nodes & connections
            for (let i = 0; i < particles.length; i++) {
                particles[i].update();
                particles[i].draw();

                // Connect nodes with laser mesh lines
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < 135) {
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        
                        const alpha = (1 - dist / 135) * 0.35;
                        ctx.strokeStyle = particles[i].color;
                        ctx.globalAlpha = alpha;
                        ctx.lineWidth = 0.85;
                        ctx.stroke();
                        ctx.globalAlpha = 1;
                    }
                }

                // Connect nearby particles to cursor
                if (mouse.x !== null && mouse.y !== null) {
                    const dx = particles[i].x - mouse.x;
                    const dy = particles[i].y - mouse.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < mouse.radius) {
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(mouse.x, mouse.y);
                        
                        const alpha = (1 - dist / mouse.radius) * 0.6;
                        ctx.strokeStyle = '#38bdf8';
                        ctx.globalAlpha = alpha;
                        ctx.lineWidth = 1.2;
                        ctx.shadowBlur = 8;
                        ctx.shadowColor = '#38bdf8';
                        ctx.stroke();
                        ctx.globalAlpha = 1;
                        ctx.shadowBlur = 0;
                    }
                }
            }

            requestAnimationFrame(animateBackground);
        }

        animateBackground();
    }
</script>

<script src="{{ asset('assets/template/beck/dist/js/app.js') }}"></script>
<script src="{{ asset('assets/file/alert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/file/alert/alertscript.js') }}"></script>

</body>
</html>
