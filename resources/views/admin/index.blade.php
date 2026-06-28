<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $pageTitle }} - Open House
    </title>

    <!-- FAVICON -->
    <link rel="icon" href="{{ asset('images/user/telu-logo.png') }}" type="image/png">

    <!-- GOOGLE FONT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;700;900&display=swap"
        rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/ui.css') }}">

    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- PATCH TOPBAR / DROPDOWN -->
    <style>
        /*
        =========================
        FIX BACKGROUND LAYER
        =========================
        */

        .bg-wrapper,
        .grid-bg,
        .gradient-overlay,
        .scanlines {
            pointer-events: none !important;
        }

        /*
        =========================
        TOPBAR
        =========================
        */

        #topbar {
            position: relative !important;
            z-index: 9999 !important;
        }

        .profile {
            position: relative !important;
            z-index: 10000 !important;
        }

        .profile-btn {
            position: relative !important;
            z-index: 10001 !important;
            cursor: pointer !important;
            border: none;
            outline: none;
        }

        /*
        =========================
        DROPDOWN
        =========================
        */

        .dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 170px;
            z-index: 10002 !important;

            background: rgba(15, 15, 15, 0.98);
            border: 1px solid rgba(255, 51, 51, 0.35);
            border-radius: 10px;
            padding: 8px;

            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.45),
                0 0 18px rgba(255, 0, 0, 0.15);
        }

        .dropdown.show {
            display: block !important;
        }

        .dropdown form {
            margin: 0;
        }

        .logout-btn {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            color: white;

            padding: 10px 12px;
            text-align: left;

            cursor: pointer;
            border-radius: 8px;

            font-size: 14px;
            font-family: inherit;

            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: rgba(255, 51, 51, 0.18);
            color: #ff5555;
        }

        /*
        =========================
        SAFETY CLICK AREA
        =========================
        */

        main,
        footer {
            position: relative;
            z-index: 1;
        }
    </style>

</head>

<body>

    <!-- BACKGROUND -->
    <div class="bg-wrapper">

        <div class="grid-bg"></div>

        <div class="gradient-overlay"></div>

        <div class="scanlines"></div>

    </div>

    <!-- TOPBAR -->
    <header id="topbar">

        <div class="dashboard-title">

            <i class="fas fa-bolt"></i>

            {{ $pageTitle }}

        </div>

        <div class="profile">

            <button class="profile-btn" id="profileBtn" type="button">

                <i class="fas fa-user-circle"></i>

                <span>
                    {{ $username }}
                </span>

                <i class="fas fa-chevron-down"></i>

            </button>

            <div class="dropdown" id="profileDropdown">

                <form action="{{ route('admin.logout') }}" method="POST">

                    @csrf

                    <button type="submit" class="logout-btn">

                        <i class="fas fa-sign-out-alt"></i>

                        <span>
                            Logout
                        </span>

                    </button>

                </form>

            </div>

        </div>

    </header>

    <!-- CONTENT -->
    <main>

        @if($role === 'superadmin')

            @include('admin.super.dashboard_super')

        @else

            @include('admin.staff.dashboard_staff')

        @endif

    </main>

    <!-- FOOTER -->
    <!-- Footer -->
    <footer>
        <div class="footer-content">

            <h3>Open House Telkom University</h3>

            <p>
                Website Open House Telkom University sebagai media informasi,
                registrasi peserta, presensi kegiatan, serta implementasi konsep
                <b>Phygital Experience</b> untuk mendukung kegiatan Open House.
            </p>

            <div class="footer-links">
                <a href="#home">Home</a>
                <a href="#about">Tentang</a>
                <a href="#features">Kegiatan</a>
                <a href="#contact">Kontak</a>
            </div>

            <hr style="margin:25px auto;opacity:.2;max-width:800px;">

            <p class="copyright">
                © {{ date('Y') }} Open House Telkom University. All Rights Reserved.
            </p>

        </div>
    </footer>

    <!-- JS -->
    <script src="{{ asset('js/admin/script.js') }}"></script>

    <script src="{{ asset('js/admin/super/ui.js') }}" defer></script>

    <script src="{{ asset('js/admin/super/staff.js') }}" defer></script>

    <script src="{{ asset('js/admin/super/booth.js') }}" defer></script>

    <script src="{{ asset('js/admin/super/main.js') }}" defer></script>

    <script src="{{ asset('js/admin/super/content-loader.js') }}" defer></script>

    <!-- DROPDOWN SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const profileBtn =
                document.getElementById('profileBtn');

            const profileDropdown =
                document.getElementById('profileDropdown');

            if (!profileBtn || !profileDropdown) {

                console.warn(
                    'Profile dropdown element tidak ditemukan.'
                );

                return;

            }

            profileBtn.addEventListener('click', (e) => {

                e.preventDefault();

                e.stopPropagation();

                profileDropdown.classList.toggle('show');

            });

            profileDropdown.addEventListener('click', (e) => {

                e.stopPropagation();

            });

            document.addEventListener('click', () => {

                profileDropdown.classList.remove('show');

            });

            document.addEventListener('keydown', (e) => {

                if (e.key === 'Escape') {

                    profileDropdown.classList.remove('show');

                }

            });

        });
    </script>

</body>

</html>