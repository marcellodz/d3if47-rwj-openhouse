<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard User - Open House</title>

    <!-- FAVICON -->
    <link rel="shortcut icon"
        href="{{ asset('images/user/telu-logo.png') }}"
        type="image/x-icon">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <!-- MAIN CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/user/templatemo-electric-xtra.css') }}">

    <!-- DASHBOARD CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/user/dashboard.css') }}">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<!-- CLAIM POPUP -->
<div id="claimPopup"
    style="
        display:none;
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.75);
        z-index:9999;
        justify-content:center;
        align-items:center;">

    <div style="
        width:420px;
        max-width:90%;
        background:#fff;
        border-radius:15px;
        padding:25px;
        position:relative;
        text-align:center;">

        <button
            onclick="closeClaimQR()"
            style="
                position:absolute;
                top:12px;
                right:15px;
                border:none;
                background:none;
                font-size:22px;
                cursor:pointer;">

            ×

        </button>

        <div id="claimPopupBody">

            Memuat...

        </div>

    </div>

</div>

<script>

async function showClaimQR()
{
    const popup =
        document.getElementById("claimPopup");

    const body =
        document.getElementById("claimPopupBody");

    popup.style.display = "flex";

    body.innerHTML =
        "<i class='fas fa-spinner fa-spin'></i> Memuat QR...";

    try{

        const res =
            await fetch("/user/reward/claim");

        const html =
            await res.text();

        body.innerHTML =
            html;

    }catch(e){

        body.innerHTML =
            "<p style='color:red'>Gagal memuat QR.</p>";

    }
}

function closeClaimQR()
{
    document.getElementById("claimPopup").style.display="none";
}

</script>

<body>

    <!-- BACKGROUND -->
    <div class="grid-bg"></div>
    <div class="scanlines"></div>

    <!-- SHAPES -->
    <div class="shapes-container">

        <div class="shape shape-circle"></div>
        <div class="shape shape-triangle"></div>
        <div class="shape shape-square"></div>

    </div>

    <!-- NAVBAR -->
    <nav id="navbar">

        <div class="nav-container">

            <a href="{{ route('dashboard') }}"
                class="logo-link">

                <img src="{{ asset('images/user/logo-openhouse.png') }}"
                    alt="Logo"
                    class="logo-svg">

            </a>

            <div class="user-nav">

                <div class="user-menu-dropdown">

                    <button class="user-icon-btn"
                        id="userIconBtn">

                        <i class="fas fa-user-circle"></i>

                    </button>

                    <div class="dropdown-content"
                        id="userDropdown">

                        <form action="{{ route('logout') }}"
                            method="POST">

                            @csrf

                            <button type="submit"
                                class="logout-btn">

                                Logout

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </nav>

    <!-- DASHBOARD -->
    <div class="dashboard-container">

        <div class="grid-2">

            <!-- WELCOME -->
            <div class="card">

                <h2>
                    Selamat Datang,
                    {{ session('nama') }}!
                </h2>

                <br>

                <p>
                    Kamu telah terdaftar dalam kegiatan
                    <b>Open House Telkom University 2025</b>.
                    <br>
                    Gunakan halaman ini untuk:
                </p>

                <ul>

                    <li>
                        Menunjukkan QR Code ke petugas untuk presensi.
                    </li>

                    <li>
                        Scan QR Booth untuk mendapatkan hadiah menarik.
                    </li>

                    <li>
                        Melihat kegiatan dan riwayat presensimu.
                    </li>

                </ul>

            </div>

            <!-- QR -->
            <div class="card">

                <h3>
                    <i class="fas fa-qrcode"></i>
                    QR Code Presensiku
                </h3>

                <div id="qr-wrapper"
                    style="margin-top:15px;text-align:center;">

                    <div class="loading">

                        <i class="fas fa-spinner fa-spin"></i>
                        Memuat QR...

                    </div>

                </div>

            </div>

        </div>

        <!-- TABS -->
        <div class="tabs-container">

            <div class="tabs-header">

                <div class="tab-item active"
                    data-tab="scan">

                    <i class="fas fa-qrcode"></i>
                    Scan Booth

                </div>

                <div class="tab-item"
                    data-tab="kegiatan">

                    <i class="fas fa-calendar-check"></i>
                    Kegiatan Saya

                </div>

                <div class="tab-item"
                    data-tab="presensi">

                    <i class="fas fa-user-check"></i>
                    Presensi Saya

                </div>

                <div class="tab-item"
                    data-tab="reward">

                    <i class="fas fa-gift"></i>
                    Point & Reward

                </div>

            </div>

            <!-- TAB CONTENT -->
            <div id="tab-content">

                <div class="loading">

                    <i class="fas fa-spinner fa-spin"></i>
                    Memuat data...

                </div>

            </div>

        </div>

    </div>

    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DROPDOWN -->
    <script>

        document.addEventListener('DOMContentLoaded', () => {

            const btn = document.getElementById('userIconBtn');

            const dropdown =
                document.getElementById('userDropdown');

            btn.addEventListener('click', () => {

                dropdown.style.display =
                    dropdown.style.display === 'block'
                    ? 'none'
                    : 'block';

            });

            window.addEventListener('click', e => {

                if (
                    !btn.contains(e.target)
                    &&
                    !dropdown.contains(e.target)
                ) {

                    dropdown.style.display = 'none';

                }

            });

        });

    </script>

    <!-- LOAD QR -->
    <script>

        fetch('/user/content/qr')

            .then(res => res.text())

            .then(html => {

                document.getElementById('qr-wrapper')
                    .innerHTML = html;

            });

    </script>

    <!-- LOAD TAB -->
    <script>

        const tabs =
            document.querySelectorAll('.tab-item');

        const container =
            document.getElementById('tab-content');

        function loadTab(type) {

            container.classList.add('fade-out');

            container.classList.remove('fade-in');

            setTimeout(() => {

                fetch(`/user/content/${type}`)

                    .then(res => res.text())

                    .then(html => {

                        container.innerHTML = html;

                        container.classList.remove('fade-out');

                        container.classList.add('fade-in');

                    })

                    .catch(() => {

                        container.innerHTML = `
                            <p style="color:red;text-align:center;">
                                ❌ Gagal memuat data.
                            </p>
                        `;

                    });

            }, 150);

        }

        tabs.forEach(tab => {

            tab.addEventListener('click', () => {

                if (tab.classList.contains('active'))
                    return;

                tabs.forEach(t =>
                    t.classList.remove('active'));

                tab.classList.add('active');

                loadTab(tab.dataset.tab);

            });

        });

        loadTab('scan');

    </script>

</body>

</html>