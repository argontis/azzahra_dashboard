<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="{{ asset('assets/template/beck/dist/images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Azzahra Computer</title>

    <link rel="stylesheet" href="{{ asset('assets/template/beck/dist/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/file/alert/animet.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        html, body.login {
            height: 100vh;
            max-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #090d16 0%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* =========================================
           INTRO CURTAIN OPENING ANIMATION OVERLAY
           ========================================= */
        .intro-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #080d19;
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .intro-overlay.hide-intro {
            opacity: 0;
            pointer-events: none;
        }

        /* Dark cinematic background behind curtains */
        .intro-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, #0f172a 0%, #050914 100%);
            z-index: 0;
        }

        /* Subtle star-dots in background */
        .intro-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(56,189,248,0.25) 1px, transparent 1px),
                radial-gradient(circle, rgba(129,140,248,0.2) 1px, transparent 1px);
            background-size: 60px 60px, 90px 90px;
            background-position: 0 0, 30px 30px;
            animation: starsDrift 30s linear infinite;
        }

        @keyframes starsDrift {
            0%   { background-position: 0 0, 30px 30px; }
            100% { background-position: 60px 60px, 90px 90px; }
        }

        /* ---- CURTAIN PANELS ---- */
        .curtain-left,
        .curtain-right {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            z-index: 4;
            transition: transform 1.1s cubic-bezier(0.76, 0, 0.24, 1);
        }

        .curtain-left {
            left: 0;
            background: linear-gradient(160deg, #1e1b4b 0%, #312e81 60%, #1e40af 100%);
            border-right: 3px solid rgba(56, 189, 248, 0.5);
            box-shadow: inset -20px 0 60px rgba(0,0,0,0.5), 6px 0 30px rgba(56,189,248,0.3);
        }

        .curtain-right {
            right: 0;
            background: linear-gradient(200deg, #1e1b4b 0%, #312e81 60%, #1e40af 100%);
            border-left: 3px solid rgba(56, 189, 248, 0.5);
            box-shadow: inset 20px 0 60px rgba(0,0,0,0.5), -6px 0 30px rgba(56,189,248,0.3);
        }

        /* Curtain decorative rope-light strips */
        .curtain-left::before  { content: ''; position: absolute; top:0; right:0;  width: 6px; height: 100%; background: linear-gradient(180deg,#38bdf8,#818cf8,#34d399,#38bdf8); opacity: 0.7; }
        .curtain-right::before { content: ''; position: absolute; top:0; left:0;  width: 6px; height: 100%; background: linear-gradient(180deg,#38bdf8,#818cf8,#34d399,#38bdf8); opacity: 0.7; }

        /* Curtain logo stamp */
        .curtain-logo {
            position: absolute;
            top: 50%;
            right: 30px;
            transform: translateY(-50%);
            opacity: 0.12;
            font-size: 80px;
        }

        /* Opened state: panels fly off-screen */
        .intro-overlay.curtain-open .curtain-left  { transform: translateX(-102%); }
        .intro-overlay.curtain-open .curtain-right { transform: translateX(102%); }

        /* ---- PERFECTLY CENTERED HERO CONTAINER ---- */
        .intro-center-hero {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 90%;
            max-width: 480px;
            text-align: center;
            pointer-events: none;
        }

        /* ---- TECHNICIAN CHARACTER (Orang Servis) ---- */
        .intro-tech-character {
            position: relative;
            z-index: 5;
            width: 200px;
            height: auto;
            margin-bottom: -15px;
            opacity: 0;
            animation: techHeroIn 0.9s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s forwards;
        }

        @keyframes techHeroIn {
            0%   { opacity: 0; transform: translateY(25px) scale(0.88); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Wave arm keyframe */
        @keyframes waveArm {
            0%   { transform: rotate(0deg); }
            25%  { transform: rotate(-28deg); }
            50%  { transform: rotate(0deg); }
            75%  { transform: rotate(-22deg); }
            100% { transform: rotate(0deg); }
        }
        .wave-arm {
            transform-origin: 90px 60px;
            animation: waveArm 1s ease-in-out 1.2s 3;
        }

        /* Speech bubble */
        .intro-speech-bubble {
            position: relative;
            z-index: 12;
            background: rgba(255, 255, 255, 0.96);
            color: #0f172a;
            font-size: 15px;
            font-weight: 800;
            padding: 12px 24px;
            border-radius: 20px;
            white-space: nowrap;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4), 0 0 25px rgba(56,189,248,0.45);
            margin-bottom: 8px;
            opacity: 0;
            animation: bubbleAppear 0.5s ease-out 0.6s forwards;
        }

        .intro-speech-bubble::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 12px 9px 0;
            border-style: solid;
            border-color: rgba(255,255,255,0.96) transparent transparent;
        }

        @keyframes bubbleAppear {
            from { opacity: 0; transform: translateY(-10px) scale(0.85); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ---- CENTRE INTRO PANEL (logo + progress) ---- */
        .intro-center-panel {
            position: relative;
            z-index: 11;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            width: 100%;
            opacity: 0;
            animation: introFadeUp 0.6s ease-out 0.5s forwards;
        }

        .intro-logo-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
            animation: introLogoZoom 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
        }

        @keyframes introLogoZoom {
            from { opacity: 0; transform: scale(0.65); }
            to   { opacity: 1; transform: scale(1); }
        }

        .intro-aura-ring {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .intro-aura-ring.ring-1 {
            inset: -18px;
            background: conic-gradient(from 0deg, #38bdf8, #818cf8, #c084fc, #f472b6, #34d399, #38bdf8);
            filter: blur(14px);
            opacity: 0.9;
            animation: rotateAura1 6s linear infinite;
        }

        .intro-aura-ring.ring-2 {
            inset: -8px;
            background: conic-gradient(from 180deg, #f472b6, #38bdf8, #34d399, #818cf8, #f472b6);
            filter: blur(10px);
            opacity: 0.8;
            animation: rotateAura2 4s linear infinite reverse;
        }

        @keyframes rotateAura1 { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes rotateAura2 { 0% { transform: rotate(0deg); } 100% { transform: rotate(-360deg); } }

        .intro-logo-box {
            position: relative;
            z-index: 2;
            background: #ffffff;
            padding: 20px 36px;
            border-radius: 28px;
            box-shadow: 
                0 0 40px rgba(255, 255, 255, 0.9),
                0 0 80px rgba(56, 189, 248, 0.85),
                0 0 110px rgba(168, 85, 247, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: introLogoPulse 2.5s ease-in-out infinite alternate;
        }

        @keyframes introLogoPulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        .intro-logo-img {
            height: 72px;
            width: auto;
            object-fit: contain;
        }

        .intro-brand-badge {
            font-size: 12.5px;
            font-weight: 800;
            color: #38bdf8;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            text-shadow: 0 0 12px rgba(56, 189, 248, 0.6);
        }

        /* Skip button */
        .skip-intro-btn {
            position: absolute;
            top: 24px;
            right: 28px;
            z-index: 10000;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            padding: 9px 22px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            backdrop-filter: blur(14px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3), 0 0 12px rgba(56,189,248,0.35);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .skip-intro-btn:hover {
            background: rgba(56, 189, 248, 0.35);
            border-color: #38bdf8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(56, 189, 248, 0.5);
        }

        /* Progress Bar */
        .intro-progress-container {
            width: 250px;
            height: 6px;
            background: rgba(255, 255, 255, 0.14);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(56, 189, 248, 0.3);
        }

        .intro-progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #38bdf8, #818cf8, #34d399);
            border-radius: 10px;
            transition: width 0.1s linear;
        }

        @keyframes introFadeUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* FULLSCREEN ANIMATED TECH CANVAS & GRID BACKGROUND */
        #techCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 5;
            pointer-events: none;
        }

        /* Animated Cyber Grid Overlay Across Entire Screen */
        .cyber-grid-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            background-image: 
                linear-gradient(to right, rgba(56, 189, 248, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(56, 189, 248, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
            pointer-events: none;
        }

        @keyframes gridMove {
            0% { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }

        /* Ambient Glowing Color Blobs in Background */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: floatBlob 14s ease-in-out infinite alternate;
            z-index: 1;
            pointer-events: none;
            will-change: transform;
        }

        .blob-1 {
            top: -15%;
            left: -10%;
            width: 550px;
            height: 550px;
            background: linear-gradient(135deg, #4f46e5, #0284c7);
            animation-duration: 16s;
        }

        .blob-2 {
            bottom: -20%;
            right: -10%;
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

        /* Dual Rotating Glowing Aura Rings */
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

        /* Logo Card Box */
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

        .logo-box::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.85), transparent);
            transform: skewX(-25deg);
            animation: shimmerSweep 3.5s infinite;
        }

        @keyframes shimmerSweep {
            0% { left: -100%; }
            30% { left: 200%; }
            100% { left: 200%; }
        }

        /* Text content on left */
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

        /* TECH COMPUTER SERVICE ANIMATIONS (PURE 2D SVG HUB) */
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

        /* Spinning Fans Animation */
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

        /* Rotating Gears Animation */
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

        /* Scanning Beam on Monitor */
        .scan-beam {
            animation: scanScreen 3s ease-in-out infinite alternate;
        }

        @keyframes scanScreen {
            0% { transform: translateY(0px); opacity: 0.3; }
            100% { transform: translateY(130px); opacity: 0.95; }
        }

        /* Circuit Pulse Dots */
        .pulse-dot {
            animation: pulseCircuit 2s ease-in-out infinite alternate;
        }
        .pulse-dot.dot2 { animation-delay: 0.6s; }
        .pulse-dot.dot3 { animation-delay: 1.2s; }

        @keyframes pulseCircuit {
            0% { opacity: 0.3; r: 3px; }
            100% { opacity: 1; r: 6px; }
        }

        /* ANIMATED TECHNICIAN ENGINEER CHARACTER (ORANG TEKNISI SERVIS) */
        .tech-head {
            animation: techHeadNod 2.8s ease-in-out infinite alternate;
            transform-origin: 250px 165px;
        }

        @keyframes techHeadNod {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-4deg) translateY(2px); }
        }

        .tech-arm-working {
            animation: techArmMove 1.6s ease-in-out infinite alternate;
            transform-origin: 290px 220px;
        }

        @keyframes techArmMove {
            0% { transform: rotate(0deg); }
            50% { transform: rotate(-6deg) translateY(-2px); }
            100% { transform: rotate(3deg) translateY(2px); }
        }

        .spark-flash {
            animation: sparkBlink 0.5s ease-in-out infinite alternate;
        }

        @keyframes sparkBlink {
            0% { opacity: 0.2; transform: scale(0.7); }
            100% { opacity: 1; transform: scale(1.3); }
        }

        .hologram-code-float {
            animation: codeRise 3.5s ease-in-out infinite;
        }

        @keyframes codeRise {
            0% { opacity: 0; transform: translateY(8px); }
            50% { opacity: 0.95; }
            100% { opacity: 0; transform: translateY(-20px); }
        }

        /* RGB Strip Lighting on PC Tower Case */
        .rgb-strip {
            animation: rgbShift 4s linear infinite alternate;
        }

        @keyframes rgbShift {
            0% { filter: hue-rotate(0deg) blur(4px); }
            100% { filter: hue-rotate(360deg) blur(8px); }
        }

        /* Signal Wave Motion */
        .signal-wave {
            stroke-dasharray: 200;
            stroke-dashoffset: 0;
            animation: waveMove 3s linear infinite;
        }

        @keyframes waveMove {
            0% { stroke-dashoffset: 200; }
            100% { stroke-dashoffset: 0; }
        }

        /* Floating Tool Badges */
        .tool-badge-floating {
            animation: floatBadge1 4s ease-in-out infinite alternate;
        }

        .chip-badge-floating {
            animation: floatBadge2 3.5s ease-in-out infinite alternate 0.5s;
        }

        @keyframes floatBadge1 {
            0% { transform: translate(50px, 180px) rotate(0deg); }
            100% { transform: translate(45px, 168px) rotate(-8deg); }
        }

        @keyframes floatBadge2 {
            0% { transform: translate(300px, 270px) scale(1); }
            100% { transform: translate(305px, 258px) scale(1.1); }
        }

        /* Floating Tech Badges (Pills) */
        .tech-badge {
            position: absolute;
            z-index: 5;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(15, 23, 42, 0.88);
            border: 1px solid rgba(56, 189, 248, 0.4);
            padding: 7px 14px;
            border-radius: 30px;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), 0 0 15px rgba(56, 189, 248, 0.3);
            white-space: nowrap;
        }

        .badge-1 {
            top: -10px;
            left: 5px;
            animation: badgeFloat 4s ease-in-out infinite alternate;
        }

        .badge-2 {
            bottom: 10px;
            right: -5px;
            border-color: rgba(168, 85, 247, 0.5);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), 0 0 15px rgba(168, 85, 247, 0.3);
            animation: badgeFloat 4.5s ease-in-out infinite alternate 1s;
        }

        .badge-3 {
            top: 48%;
            left: -15px;
            border-color: rgba(52, 211, 153, 0.5);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), 0 0 15px rgba(52, 211, 153, 0.3);
            animation: badgeFloat 3.8s ease-in-out infinite alternate 0.5s;
        }

        @keyframes badgeFloat {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(-8px) scale(1.04); }
        }

        /* RIGHT PANEL */
        .login-right {
            padding: 32px 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            background: #ffffff;
            position: relative;
            z-index: 2;
        }

        .form-header {
            margin-bottom: 16px;
        }

        .form-header h2 {
            font-size: 30px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 6px 0;
            letter-spacing: -0.5px;
        }

        .form-header p {
            color: #64748b;
            font-size: 13.5px;
            margin: 0;
            line-height: 1.5;
        }

        /* Alert notifications */
        .alert-box {
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 14px;
            animation: fadeInText 0.4s ease-out;
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
            margin-bottom: 14px;
            position: relative;
        }

        .input-label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }

        .login__input {
            width: 100%;
            padding: 13px 18px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 14px;
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
            font-size: 13.5px;
        }

        .forgot-link {
            display: flex;
            justify-content: flex-end;
            margin-top: -2px;
            margin-bottom: 16px;
        }

        .forgot-link a {
            font-size: 13px;
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
            cursor: pointer;
        }

        .forgot-link a:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        /* Enhanced Animated Button */
        .login-btn {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 50%, #0284c7 100%);
            background-size: 200% auto;
            color: #ffffff;
            border: none;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px -4px rgba(79, 70, 229, 0.45);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn:hover {
            background-position: right center;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(37, 99, 235, 0.55);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Footer styling */
        .footer-card {
            margin-top: 20px;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }

        .admin-note {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 12px;
        }

        .admin-note a {
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
        }

        .admin-note a:hover {
            text-decoration: underline;
        }

        /* Status Badge & Live Clock */
        .live-widget {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            border: 1.5px solid #dbeafe;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
        }

        #greetingText {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e40af;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #clockText {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 800;
            color: #0284c7;
            background: #ffffff;
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid #e0f2fe;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Responsive Breakpoints */
        @media(max-width: 868px) {
            .login-wrapper {
                grid-template-columns: 1fr;
                max-width: 480px;
            }
            .login-left {
                display: none;
            }
            .login-right {
                padding: 40px 30px;
            }
            .skip-intro-btn {
                top: 20px;
                right: 20px;
                padding: 8px 16px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body class="login">

    <!-- INTRO CURTAIN OPENING ANIMATION OVERLAY -->
    <div id="introOverlay" class="intro-overlay">

        <!-- Dark Cinematic BG -->
        <div class="intro-bg"></div>

        <!-- Skip Button -->
        <button id="skipIntroBtn" class="skip-intro-btn" onclick="closeIntro()">
            <span>Lewati</span> ⏭️
        </button>

        <!-- LEFT CURTAIN PANEL -->
        <div class="curtain-left" id="curtainLeft">
            <div class="curtain-logo">🔵</div>
            <!-- Curtain brand text -->
            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
                <div style="font-size:13px;font-weight:800;color:rgba(255,255,255,0.35);letter-spacing:3px;writing-mode:vertical-rl;">AZZAHRA COMPUTER</div>
            </div>
        </div>

        <!-- RIGHT CURTAIN PANEL -->
        <div class="curtain-right" id="curtainRight">
            <div class="curtain-logo" style="left:30px;right:auto;">🔵</div>
            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
                <div style="font-size:13px;font-weight:800;color:rgba(255,255,255,0.35);letter-spacing:3px;writing-mode:vertical-rl;">SERVIS KOMPUTER</div>
            </div>
        </div>

        <!-- PERFECTLY CENTERED INTRO HERO CONTAINER -->
        <div class="intro-center-hero">
            <!-- 1. AZZAHRA LOGO & BRAND BADGE AT TOP (ABOVE MASCOT) -->
            <div class="intro-center-panel">
                <!-- Glowing Logo -->
                <div class="intro-logo-wrapper">
                    <div class="intro-aura-ring ring-1"></div>
                    <div class="intro-aura-ring ring-2"></div>
                    <div class="intro-logo-box">
                        <img src="{{ asset('assets/image/logo.png') }}" class="intro-logo-img" alt="Azzahra Computer Logo">
                    </div>
                </div>
                <div class="intro-brand-badge">AZZAHRA COMPUTER &mdash; SUPER-APPS</div>
            </div>

            <!-- 2. SPEECH BUBBLE: "Selamat Datang!" -->
            <div class="intro-speech-bubble" id="introSpeech">👋 Selamat Datang di Azzahra Computer!</div>

            <!-- 3. ANIMATED TECHNICIAN CHARACTER (BELOW LOGO) -->
            <div class="intro-tech-character" id="introTechChar">
                <svg viewBox="0 0 320 380" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:auto;">

                    <!-- Shadow under character -->
                    <ellipse cx="160" cy="370" rx="90" ry="12" fill="rgba(0,0,0,0.4)"/>

                    <!-- LEGS -->
                    <rect x="120" y="270" width="32" height="90" rx="14" fill="#1e293b"/>
                    <rect x="168" y="270" width="32" height="90" rx="14" fill="#1e293b"/>
                    <!-- Shoes -->
                    <ellipse cx="136" cy="358" rx="22" ry="9" fill="#0f172a"/>
                    <ellipse cx="184" cy="358" rx="22" ry="9" fill="#0f172a"/>

                    <!-- BODY / UNIFORM -->
                    <rect x="100" y="155" width="120" height="125" rx="22" fill="#1e293b"/>
                    <!-- Shirt stripe center -->
                    <rect x="154" y="155" width="12" height="125" fill="#38bdf8"/>
                    <!-- Azzahra badge on chest -->
                    <rect x="108" y="180" width="52" height="28" rx="6" fill="#34d399"/>
                    <text x="134" y="199" fill="#0f172a" font-size="9" font-weight="900" text-anchor="middle" font-family="monospace">AZZAHRA</text>
                    <!-- Tool belt -->
                    <rect x="100" y="265" width="120" height="16" rx="7" fill="#0f172a"/>
                    <rect x="140" y="263" width="40" height="20" rx="6" fill="#374151"/>
                    <text x="160" y="276" fill="#38bdf8" font-size="8" text-anchor="middle">🔧</text>

                    <!-- WAVING ARM (right) -->
                    <g class="wave-arm">
                        <!-- Upper arm -->
                        <path d="M 220 175 Q 265 150 280 110" stroke="#fed7aa" stroke-width="22" stroke-linecap="round" fill="none"/>
                        <!-- Forearm -->
                        <path d="M 280 110 Q 295 80 290 55" stroke="#fed7aa" stroke-width="18" stroke-linecap="round" fill="none"/>
                        <!-- Hand / fist with wave -->
                        <ellipse cx="290" cy="50" rx="18" ry="20" fill="#fed7aa"/>
                        <!-- Wave emoji sparkle -->
                        <text x="295" y="28" font-size="22" text-anchor="middle">👋</text>
                    </g>

                    <!-- LEFT ARM holding wrench -->
                    <path d="M 100 175 Q 62 200 52 240" stroke="#fed7aa" stroke-width="20" stroke-linecap="round" fill="none"/>
                    <path d="M 52 240 Q 40 265 48 285" stroke="#fed7aa" stroke-width="16" stroke-linecap="round" fill="none"/>
                    <text x="38" y="305" font-size="26" text-anchor="middle">🔧</text>

                    <!-- NECK -->
                    <rect x="146" y="135" width="28" height="28" rx="8" fill="#fed7aa"/>

                    <!-- HEAD -->
                    <circle cx="160" cy="110" r="52" fill="#fed7aa"/>
                    <!-- Neon aura halo around head -->
                    <circle cx="160" cy="110" r="55" fill="none" stroke="#38bdf8" stroke-width="3" opacity="0.4" stroke-dasharray="8 6"/>

                    <!-- Hair / IT Cap -->
                    <path d="M 108 100 C 108 65 130 52 160 52 C 190 52 212 65 212 100 Z" fill="#0f172a"/>
                    <path d="M 105 95 Q 160 85 215 95 L 212 103 Q 160 92 108 103 Z" fill="#0284c7"/>
                    <!-- Cap logo -->
                    <circle cx="160" cy="74" r="10" fill="#38bdf8"/>
                    <text x="160" y="79" fill="#0f172a" font-size="8" font-weight="900" text-anchor="middle">A</text>

                    <!-- AR Smart Visor -->
                    <rect x="128" y="100" width="64" height="18" rx="5" fill="#0f172a" stroke="#38bdf8" stroke-width="2.5"/>
                    <circle cx="148" cy="109" r="5" fill="#34d399"/>
                    <circle cx="172" cy="109" r="5" fill="#38bdf8"/>
                    <!-- Visor reflection glint -->
                    <line x1="130" y1="103" x2="145" y2="103" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-linecap="round"/>

                    <!-- Smile face -->
                    <path d="M 148 125 Q 160 136 172 125" stroke="#92400e" stroke-width="2.5" fill="none" stroke-linecap="round"/>

                    <!-- Headset microphone -->
                    <path d="M 108 108 Q 100 122 116 126" stroke="#94a3b8" stroke-width="3" fill="none"/>
                    <circle cx="116" cy="126" r="5" fill="#38bdf8"/>
                </svg>
            </div>

            <!-- 4. PROGRESS BAR AT BOTTOM -->
            <div class="intro-progress-container">
                <div class="intro-progress-bar" id="introProgressBar"></div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Cyber Grid Background -->
    <div class="cyber-grid-bg"></div>

    <!-- Tech Interactive Particles & Digital Circuit Canvas -->
    <canvas id="techCanvas"></canvas>

    <!-- Glowing Dynamic Ambient Blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <!-- Main Login Card -->
    <div class="login-wrapper">

        <!-- LEFT PANEL -->
        <div class="login-left">
            <div class="left-header">
                <!-- DUAL-RING ULTRA HIGH GLOW LOGO CONTAINER -->
                <div class="logo-glow-wrapper">
                    <div class="logo-aura-ring-1"></div>
                    <div class="logo-aura-ring-2"></div>
                    <div class="logo-box">
                        <img src="{{ asset('assets/image/logo.png') }}" class="logo-img" alt="Azzahra Computer Logo">
                    </div>
                </div>

                <h1>
                    Tidak perlu hebat untuk memulai,<br>
                    tetapi kamu perlu memulai untuk menjadi hebat.
                </h1>

                <div class="superapps-badge">
                    <span></span>
                    Super-Apps Integrasi Sistem Azzahra Computer Tegal &amp; Cibubur
                </div>
            </div>

            <!-- ANIMATED COMPUTER REPAIR SERVICE GRAPHICS HUB -->
            <div class="tech-service-container">
                <!-- Floating Tech Repair Badges -->
                <div class="tech-badge badge-1">
                    <span>💻</span>
                    <span>Servis Laptop &amp; PC</span>
                </div>
                <div class="tech-badge badge-2">
                    <span>🛠️</span>
                    <span>Perbaikan Hardware</span>
                </div>
                <div class="tech-badge badge-3">
                    <span>⚡</span>
                    <span>Diagnostic System</span>
                </div>

                <!-- MAIN ANIMATED COMPUTER SERVICE & TECHNICIAN SVG HUB -->
                <div class="computer-station-svg-wrapper">
                    <svg viewBox="0 0 500 360" fill="none" xmlns="http://www.w3.org/2000/svg" class="computer-station-svg">
                        <defs>
                            <linearGradient id="screenGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#0f172a"/>
                                <stop offset="100%" stop-color="#1e1b4b"/>
                            </linearGradient>
                            <linearGradient id="caseGrad" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#1e293b"/>
                                <stop offset="100%" stop-color="#0f172a"/>
                            </linearGradient>
                            <linearGradient id="accentGlow" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#f43f5e"/>
                                <stop offset="50%" stop-color="#a855f7"/>
                                <stop offset="100%" stop-color="#3b82f6"/>
                            </linearGradient>

                            <filter id="neonGlowFilter" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="5" result="blur" />
                                <feComposite in="SourceGraphic" in2="blur" operator="over" />
                            </filter>
                        </defs>

                        <!-- Desk Base Shadow -->
                        <ellipse cx="250" cy="335" rx="200" ry="16" fill="rgba(0,0,0,0.3)" filter="blur(8px)" />

                        <!-- Circuit Board Background Tracks -->
                        <g stroke="#475569" stroke-width="1.5" opacity="0.45" stroke-dasharray="6,6">
                            <path d="M 60 120 L 150 120 L 190 160" />
                            <path d="M 440 100 L 350 100 L 310 140" />
                            <path d="M 80 260 L 140 260 L 170 230" />
                            <path d="M 420 280 L 360 280 L 330 250" />
                        </g>

                        <!-- Pulsing Circuit Energy Dots -->
                        <circle class="pulse-dot dot1" cx="60" cy="120" r="4" fill="#38bdf8" filter="url(#neonGlowFilter)" />
                        <circle class="pulse-dot dot2" cx="440" cy="100" r="4" fill="#a855f7" filter="url(#neonGlowFilter)" />
                        <circle class="pulse-dot dot3" cx="80" cy="260" r="4" fill="#34d399" filter="url(#neonGlowFilter)" />

                        <!-- ROTATING REPAIR GEAR 1 (Left) -->
                        <g class="rotating-gear gear-left" transform="translate(70, 75)">
                            <circle cx="0" cy="0" r="22" fill="none" stroke="#38bdf8" stroke-width="3" opacity="0.85" />
                            <circle cx="0" cy="0" r="10" fill="none" stroke="#38bdf8" stroke-width="2" />
                            <rect x="-3" y="-28" width="6" height="8" rx="2" fill="#38bdf8" />
                            <rect x="-3" y="20" width="6" height="8" rx="2" fill="#38bdf8" />
                            <rect x="-28" y="-3" width="8" height="6" rx="2" fill="#38bdf8" />
                            <rect x="20" y="-3" width="8" height="6" rx="2" fill="#38bdf8" />
                        </g>

                        <!-- ROTATING REPAIR GEAR 2 (Right) -->
                        <g class="rotating-gear gear-right" transform="translate(430, 75)">
                            <circle cx="0" cy="0" r="18" fill="none" stroke="#818cf8" stroke-width="3" opacity="0.85" />
                            <circle cx="0" cy="0" r="8" fill="none" stroke="#818cf8" stroke-width="2" />
                            <rect x="-2.5" y="-23" width="5" height="7" rx="1.5" fill="#818cf8" />
                            <rect x="-2.5" y="16" width="5" height="7" rx="1.5" fill="#818cf8" />
                            <rect x="-23" y="-2.5" width="7" height="5" rx="1.5" fill="#818cf8" />
                            <rect x="16" y="-2.5" width="7" height="5" rx="1.5" fill="#818cf8" />
                        </g>

                        <!-- ANIMATED TECHNICIAN ENGINEER CHARACTER AT WORKBENCH -->
                        <g class="technician-character-wrapper">
                            <!-- Technician Body & Uniform -->
                            <path d="M 200 330 L 200 240 C 200 215 220 200 250 200 C 280 200 300 215 300 240 L 300 330 Z" fill="#1e293b" stroke="#38bdf8" stroke-width="2.5"/>
                            <!-- Shirt Stripe & Logo Badge -->
                            <path d="M 245 200 L 255 200 L 252 260 L 248 260 Z" fill="#38bdf8"/>
                            <rect x="262" y="225" width="22" height="14" rx="3" fill="#34d399"/>
                            <text x="273" y="235" fill="#0f172a" font-size="7" font-weight="bold" text-anchor="middle">AZZAHRA</text>

                            <!-- Head & Cap (Nodding Animation) -->
                            <g class="tech-head">
                                <circle cx="250" cy="165" r="30" fill="#38bdf8" opacity="0.15" filter="url(#neonGlowFilter)"/>
                                <!-- Skin Face -->
                                <circle cx="250" cy="165" r="26" fill="#fed7aa"/>
                                <!-- Hair / IT Cap -->
                                <path d="M 224 160 C 224 135 238 128 250 128 C 262 128 276 135 276 160 Z" fill="#0f172a"/>
                                <path d="M 220 155 Q 250 148 280 155 L 276 162 Q 250 154 224 162 Z" fill="#0284c7"/>
                                <!-- AR Visor / Smart Glasses -->
                                <rect x="230" y="156" width="40" height="13" rx="4" fill="#0f172a" stroke="#38bdf8" stroke-width="2"/>
                                <circle cx="242" cy="162.5" r="3" fill="#34d399"/>
                                <circle cx="258" cy="162.5" r="3" fill="#38bdf8"/>
                                <!-- Headset Microphone -->
                                <path d="M 224 165 Q 216 180 234 182" stroke="#94a3b8" stroke-width="2.5" fill="none"/>
                                <circle cx="234" cy="182" r="4" fill="#38bdf8"/>
                            </g>

                            <!-- ANIMATED REPAIR ARM WITH SOLDERING GUN / SCREWDRIVER -->
                            <g class="tech-arm-working">
                                <!-- Upper Arm & Forearm -->
                                <path d="M 290 220 L 330 250 L 315 270" stroke="#fed7aa" stroke-width="12" stroke-linecap="round" fill="none"/>
                                <!-- Tool Handle -->
                                <rect x="305" y="260" width="26" height="8" rx="3" fill="#64748b" transform="rotate(-35 305 260)"/>
                                <line x1="324" y1="248" x2="345" y2="234" stroke="#f1f5f9" stroke-width="3"/>
                                <!-- Repair Tip Sparks -->
                                <circle class="spark-flash" cx="345" cy="234" r="7" fill="#fbbf24"/>
                                <circle class="spark-flash" cx="348" cy="231" r="4" fill="#f43f5e"/>
                            </g>
                        </g>

                        <!-- MONITOR STAND & SCREEN FRAME -->
                        <rect x="120" y="160" width="150" height="130" rx="10" fill="#1e293b" stroke="#38bdf8" stroke-width="2.5" filter="url(#neonGlowFilter)" />
                        <rect x="126" y="166" width="138" height="118" rx="6" fill="url(#screenGrad)" />

                        <!-- MONITOR SCREEN TERMINAL -->
                        <rect x="126" y="166" width="138" height="18" fill="#0f172a" />
                        <circle cx="135" cy="175" r="3" fill="#f43f5e" />
                        <circle cx="144" cy="175" r="3" fill="#fbbf24" />
                        <circle cx="153" cy="175" r="3" fill="#34d399" />
                        <text x="164" y="178" fill="#94a3b8" font-size="8" font-family="monospace" font-weight="bold">DIAGNOSTIC TERMINAL</text>

                        <!-- Live Diagnostic Code Lines -->
                        <g>
                            <text x="134" y="196" fill="#38bdf8" font-size="9" font-family="monospace">> CPU Status: OK ⚡</text>
                            <text x="134" y="210" fill="#34d399" font-size="9" font-family="monospace">> RAM Test: 16GB PASSED</text>
                            <text x="134" y="224" fill="#fbbf24" font-size="9" font-family="monospace">> GPU Load: 12% Normal</text>
                            <text x="134" y="238" fill="#a855f7" font-size="9" font-family="monospace">> Technician Active 🛠️</text>
                        </g>

                        <!-- Scanning Line Beam on Terminal -->
                        <rect class="scan-beam" x="126" y="166" width="138" height="6" fill="rgba(56, 189, 248, 0.35)" />

                        <!-- PC TOWER CASE WITH RGB FANS (Far Right) -->
                        <g transform="translate(380, 160)">
                            <!-- Tower Case -->
                            <rect x="0" y="0" width="75" height="140" rx="8" fill="url(#caseGrad)" stroke="#475569" stroke-width="2" />
                            <rect x="5" y="6" width="65" height="128" rx="5" fill="#020617" opacity="0.85" />
                            
                            <!-- Front RGB Strip -->
                            <rect class="rgb-strip" x="8" y="10" width="3" height="120" rx="1.5" fill="url(#accentGlow)" filter="url(#neonGlowFilter)" />

                            <!-- SPINNING RGB COOLING FANS -->
                            <g class="spinning-fan" transform="translate(42, 40)">
                                <circle cx="0" cy="0" r="20" fill="#0f172a" stroke="#38bdf8" stroke-width="2" />
                                <circle cx="0" cy="0" r="6" fill="#38bdf8" />
                                <path d="M 0 0 L -4 -16 A 16 16 0 0 1 4 -16 Z" fill="#38bdf8" opacity="0.9" />
                                <path d="M 0 0 L 16 -4 A 16 16 0 0 1 16 4 Z" fill="#38bdf8" opacity="0.9" />
                                <path d="M 0 0 L 4 16 A 16 16 0 0 1 -4 16 Z" fill="#38bdf8" opacity="0.9" />
                                <path d="M 0 0 L -16 4 A 16 16 0 0 1 -16 -4 Z" fill="#38bdf8" opacity="0.9" />
                            </g>

                            <g class="spinning-fan fan-bottom" transform="translate(42, 95)">
                                <circle cx="0" cy="0" r="20" fill="#0f172a" stroke="#a855f7" stroke-width="2" />
                                <circle cx="0" cy="0" r="6" fill="#a855f7" />
                                <path d="M 0 0 L -4 -16 A 16 16 0 0 1 4 -16 Z" fill="#a855f7" opacity="0.9" />
                                <path d="M 0 0 L 16 -4 A 16 16 0 0 1 16 4 Z" fill="#a855f7" opacity="0.9" />
                                <path d="M 0 0 L 4 16 A 16 16 0 0 1 -4 16 Z" fill="#a855f7" opacity="0.9" />
                                <path d="M 0 0 L -16 4 A 16 16 0 0 1 -16 -4 Z" fill="#a855f7" opacity="0.9" />
                            </g>
                        </g>

                        <!-- Floating Tool & CPU Badges -->
                        <g class="tool-badge-floating">
                            <circle cx="50" cy="180" r="24" fill="#1e1b4b" stroke="#38bdf8" stroke-width="2" filter="url(#neonGlowFilter)" />
                            <text x="50" y="187" text-anchor="middle" font-size="20">🛠️</text>
                        </g>
                        
                        <g class="chip-badge-floating">
                            <rect x="330" y="280" width="38" height="38" rx="8" fill="#0f172a" stroke="#34d399" stroke-width="2" filter="url(#neonGlowFilter)" />
                            <text x="349" y="305" text-anchor="middle" font-size="18">💻</text>
                        </g>
                    </svg>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="login-right">
            <div class="form-header">
                <h2>Sign In</h2>
                <p>Masukkan kredensial akun Anda untuk masuk ke sistem</p>
            </div>

            @if(session('gagal'))
                <div class="alert-box alert-danger">{{ session('gagal') }}</div>
            @endif
            @if(session('sukses'))
                <div class="alert-box alert-success">{{ session('sukses') }}</div>
            @endif
            
            <div class="gagal" data-gagal="{{ session('gagal') }}"></div>
            <div class="sukses" data-sukses="{{ session('sukses') }}"></div>

            <form id="loginForm" method="post" action="{{ route('login.post') }}">
                @csrf
                <div class="input-group">
                    <label class="input-label">Username</label>
                    <input type="text" name="username" class="login__input" placeholder="Masukkan username" required autofocus autocomplete="username">
                </div>

                <div class="input-group">
                    <label class="input-label">Password</label>
                    <input type="password" name="pswd" class="login__input" placeholder="Masukkan password" required autocomplete="current-password">
                </div>

                <div class="forgot-link">
                    <a href="{{ route('password.reset') }}">Lupa Password?</a>
                </div>

                <button type="submit" class="login-btn">
                    Sign In
                </button>
            </form>

            <div class="footer-card">
                <div class="admin-note">
                    Jika anda belum mempunyai akun <br>
                    <a role="button">Silahkan hubungi administrator</a>
                </div>

                <div class="live-widget">
                    <div id="greetingText">Selamat pagi ☀️</div>
                    <div id="clockText">🕒 <span id="clock">00:00:00</span></div>
                </div>
            </div>
        </div>
    </div>

<script>
    // =====================================================
    //  CURTAIN OPENING INTRO CONTROLLER
    // =====================================================
    let progressInterval = null;
    const INTRO_DURATION = 6000; // 6 seconds total

    function closeIntro() {
        const overlay = document.getElementById('introOverlay');
        if (overlay && !overlay.classList.contains('hide-intro')) {
            overlay.classList.add('hide-intro');
            setTimeout(() => { overlay.style.display = 'none'; }, 700);
        }
        if (progressInterval) clearInterval(progressInterval);
    }

    function startIntroProgress() {
        const progressBar = document.getElementById('introProgressBar');
        let elapsed = 0;
        const intervalTime = 50;

        progressInterval = setInterval(() => {
            elapsed += intervalTime;
            const percent = Math.min((elapsed / INTRO_DURATION) * 100, 100);
            if (progressBar) progressBar.style.width = percent + '%';
            if (elapsed >= INTRO_DURATION) {
                clearInterval(progressInterval);
                closeIntro();
            }
        }, intervalTime);
    }

    // Trigger curtain-open sequence on load
    window.addEventListener('DOMContentLoaded', () => {
        const overlay = document.getElementById('introOverlay');
        // Step 1: open curtains after short pause (dramatic effect)
        setTimeout(() => {
            if (overlay) overlay.classList.add('curtain-open');
        }, 700);
        // Step 2: start auto-close countdown
        startIntroProgress();
    });

    function updateGreeting() {
        const hour = new Date().getHours();
        let greeting = "Selamat datang";

        if (hour >= 4 && hour < 11) greeting = "Selamat pagi ☀️";
        else if (hour >= 11 && hour < 15) greeting = "Selamat siang 🌤️";
        else if (hour >= 15 && hour < 18) greeting = "Selamat sore 🌇";
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




