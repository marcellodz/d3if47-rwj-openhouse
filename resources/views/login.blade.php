<!-- openhouse.smbbtelkom.ac.id/login.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open House Telkom University</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/templatemo-electric-xtra.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="../images/telu-logo.png" type="image/x-icon">
    <style>
        .alert {
            margin: 10px 0 20px 0;
            padding: 12px 15px;
            border-radius: 8px;
            font-family: 'Rajdhani', sans-serif;
            text-align: center;
            font-size: 15px;
        }

        .alert.error {
            background-color: rgba(255, 99, 99, 0.1);
            border: 1px solid #ff6363;
            color: #ff6363;
        }

        .alert.warning {
            background-color: rgba(255, 200, 99, 0.1);
            border: 1px solid #ffc163;
            color: #ffc163;
        }
    </style>
</head>

<body>
    <div class="grid-bg"></div>
    <div class="gradient-overlay"></div>
    <div class="scanlines"></div>

    <div class="shapes-container">
        <div class="shape shape-circle"></div>
        <div class="shape shape-triangle"></div>
        <div class="shape shape-square"></div>
    </div>
    <div id="particles"></div>

    <nav id="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo-link">
                <img src="{{ asset('images/asset-telu.png') }}">
                <span class="logo-text">OPEN HOUSE TELKOM UNIVERSITY</span>
            </a>
        </div>
    </nav>

    <section class="hero" id="home">
        <div class="hero-content">
            <h2 class="section-title">Login Open House</h2>
            <div class="contact-form">
                @php
                    $err = request('err');
                @endphp

                @if($err == 'wrong')
                    <div class="alert error">
                        Email atau password salah.
                    </div>
                @endif

                @if($err == 'inactive')
                    <div class="alert warning">
                        Akun Anda belum diaktivasi.
                    </div>
                @endif

                @if($err == 'empty')
                    <div class="alert warning">
                        Silakan isi semua kolom sebelum login.
                    </div>
                @endif

                <form action="{{ route('login.action') }}" method="post" class="loginFormInline">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Masukkan email Anda" required />
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-container">
                            <input type="password" name="password" id="password" placeholder="Masukkan password Anda"
                                required />
                            <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const togglePassword = document.getElementById('togglePassword');
                            const password = document.getElementById('password');
                            togglePassword.addEventListener('click', function () {
                                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                                password.setAttribute('type', type);
                                this.classList.toggle('fa-eye');
                                this.classList.toggle('fa-eye-slash');
                            });
                        });
                    </script>

                    <button type="submit" name="command" value="Login" class="submit-btn">Login</button>
                    <p>Belum punya akun? <a href="{{ url('/register') }}" style='color:#ff6363'>Daftar</a></p>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#privacy">Privacy Policy</a>
                <a href="#terms">Terms of Service</a>
                <a href="#careers">Careers</a>
            </div>
            <p class="copyright">© 2025 ELECTRIC XTRA. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('js/templatemo-electric-scripts.js') }}"></script>
</body>

</html>