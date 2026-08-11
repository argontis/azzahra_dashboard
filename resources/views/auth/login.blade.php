<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="{{ asset('assets/template/beck/dist/images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Azzahra Computer</title>

    <link rel="stylesheet" href="{{ asset('assets/template/beck/dist/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/file/alert/animet.css') }}">

    <style>
        body.login {
            min-height: 100vh;
            background: linear-gradient(135deg, #4338ca, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1200px;
            background: #ffffff;
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 35px 80px rgba(0,0,0,0.25);
            animation: fadeSlide 0.9s ease-out;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-left {
            background: linear-gradient(135deg, #4338ca, #2563eb);
            color: #fff;
            padding: 60px;
            position: relative;
        }

        .login-left h1 {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.4;
            margin-top: 50px;
        }

        .superapps-text {
            margin-top: 28px;
            font-size: 15px;
            color: #e0e7ff;
            opacity: 0.95;
            max-width: 420px;
            line-height: 1.5;
        }

        .illustration {
            margin-top: 60px;
            text-align: center;
        }

        .illustration img {
            max-width: 85%;
            animation: floatImage 4s ease-in-out infinite;
        }

        @keyframes floatImage {
            0% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
            100% { transform: translateY(0); }
        }

        .login-right {
            padding: 70px;
        }

        .login-right h2 {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 35px;
            color: #111827;
        }

        .login__input {
            width: 100%;
            padding: 15px 18px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            transition: all .25s ease;
            margin-bottom: 1rem;
        }

        .login__input:focus {
            border-color: #6366f1;
            outline: none;
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(99,102,241,.25);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6d28d9, #2563eb);
            color: #fff;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .3s ease;
            animation: pulse 2.8s infinite;
            margin-top: 1.5rem;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(99,102,241,.5); }
            70% { box-shadow: 0 0 0 14px rgba(99,102,241,0); }
            100% { box-shadow: 0 0 0 0 rgba(99,102,241,0); }
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 35px rgba(99,102,241,.45);
        }

        .forgot {
            font-size: 13px;
            margin-top: 10px;
            color: #6b7280;
        }

        .footer-text {
            margin-top: 40px;
            font-size: 13px;
            color: #6b7280;
        }

        .footer-text a {
            color: #2563eb;
            font-weight: 600;
        }

        #greetingText {
            margin-top: 14px;
            font-size: 13px;
            color: #374151;
            font-weight: 500;
        }

        #clockText {
            font-size: 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        @media(max-width: 768px){
            .login-left { display: none; }
            .login-right { padding: 40px; }
            .login-wrapper { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body class="login">

<div class="login-wrapper">

    <!-- LEFT -->
    <div class="login-left">
        <img style="background-color: #f0f0f0; padding-top: 10px; padding-bottom: 10px; margin-bottom: 10px; border-radius: 500px;" src="{{ asset('assets/image/logo.png') }}" width="87">

        <h1>
            Tidak perlu hebat untuk memulai,<br>
            tetapi kamu perlu memulai untuk menjadi hebat.
        </h1>

        <p class="superapps-text">
            Super-Apps Integrasi Sistem Azzahra Computer Tegal & Cibubur
        </p>

        <div class="illustration">
            <!-- Asset fallback as we migrate images later -->
            <img src="{{ asset('assets/template/beck/dist/images/illustration.svg') }}"
                 alt="Pelayanan Ramah Azzahra Computer">
        </div>
    </div>

    <!-- RIGHT -->
    <div class="login-right">
        @if(session('gagal'))
            <div style="color: red; margin-bottom: 10px;">{{ session('gagal') }}</div>
        @endif
        @if(session('sukses'))
            <div style="color: green; margin-bottom: 10px;">{{ session('sukses') }}</div>
        @endif
        
        <div class="gagal" data-gagal="{{ session('gagal') }}"></div>
        <div class="sukses" data-sukses="{{ session('sukses') }}"></div>

        <h2>Sign In</h2>

        <form method="post" action="{{ route('login.post') }}">
            @csrf
            <input type="text" name="username" class="login__input" placeholder="Username" required>
            <input type="password" name="pswd" class="login__input" placeholder="Password" required>

            <div class="forgot">
                <a role="button">Forgot Password?</a>
            </div>

            <button type="submit" class="login-btn">
                Login
            </button>
        </form>

        <div class="footer-text">
            Jika anda belum mempunyai akun <br>
            <a role="button">Silahkan hubungi administrator</a>

            <div id="greetingText"></div>
            <div id="clockText">🕒 <span id="clock"></span></div>
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
        else greeting = "Selamat malam 🌙 Terima kasih atas dedikasimu";

        document.getElementById("greetingText").innerText = greeting;
    }

    function updateClock() {
        const now = new Date();
        document.getElementById("clock").innerText =
            now.toLocaleTimeString('id-ID', { hour12: false });
    }

    updateGreeting();
    updateClock();
    setInterval(updateClock, 1000);
</script>

<script src="{{ asset('assets/template/beck/dist/js/app.js') }}"></script>
<script src="{{ asset('assets/file/alert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/file/alert/alertscript.js') }}"></script>

</body>
</html>
