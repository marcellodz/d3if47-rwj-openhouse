<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open House Telkom University</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/templatemo-electric-xtra.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <link rel="shortcut icon" href="images/user/telu-logo.png" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!--

TemplateMo 596 Electric Xtra

https://templatemo.com/tm-596-electric-xtra

-->
</head>

<body>
    <!-- Animated Grid Background -->
    <div class="grid-bg"></div>
    <div class="gradient-overlay"></div>
    <div class="scanlines"></div>

    <!-- Animated Shapes -->
    <div class="shapes-container">
        <div class="shape shape-circle"></div>
        <div class="shape shape-triangle"></div>
        <div class="shape shape-square"></div>
    </div>

    <!-- Floating Particles -->
    <div id="particles"></div>

    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="index" class="logo-link">
                <!--<svg class="logo-svg" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#e74646;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#00B2FF;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <polygon points="20,2 38,14 38,26 20,38 2,26 2,14" fill="none" stroke="url(#logoGradient)" stroke-width="2"/>
                    <polygon points="20,8 32,16 32,24 20,32 8,24 8,16" fill="url(#logoGradient)" opacity="0.3"/>
                    <circle cx="20" cy="20" r="3" fill="url(#logoGradient)"/>
                </svg>-->
                <img src="images/user/asset-telu.png" alt="" class="logo-svg">
                <span class="logo-text">OPEN HOUSE TELKOM UNIVERSITY</span>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#features" class="nav-link">Features</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="text-rotator">
                <div class="text-set active">
                    <h1 class="glitch-text" data-text="FUTURE IS NOW">FUTURE IS NOW</h1>
                    <p class="subtitle">Creating The Future with <b style='color:#ff6363'>Telkom University</b></p>
                </div>
                <!--<div class="text-set">-->
                <!--    <h1 class="glitch-text" data-text="BEYOND LIMITS">BEYOND LIMITS</h1>-->
                <!--    <p class="subtitle">Where technology meets infinite possibilities</p>-->
                <!--</div>-->
            </div>
        </div>

        <div class="cta-container">
            <a href="{{ url('/register') }}" class="cta-button cta-primary">Daftar</a>
            <a href="{{ url('/login') }}" class="cta-button cta-secondary">Login</a>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-content" style="display:flex;
            justify-content:center;
            align-items:center;
            text-align:center;">
            <div class="about-text" style="max-width:900px;">
                <h2>Open House Telkom University</h2>
                <p>Open House Telkom University merupakan kegiatan yang diselenggarakan untuk memperkenalkan
                    lingkungan kampus, program studi, fasilitas, serta berbagai aktivitas akademik dan nonakademik
                    kepada
                    calon mahasiswa. Melalui kegiatan ini, pengunjung dapat memperoleh informasi secara langsung
                    mengenai
                    kehidupan perkuliahan, prospek karier, serta berbagai peluang yang ditawarkan oleh Telkom
                    University.</p>
                <p>Mengusung konsep <b>Phygital Experience</b>, Open House menggabungkan pengalaman interaksi secara
                    langsung
                    dengan pemanfaatan teknologi digital. Peserta dapat mengikuti berbagai rangkaian kegiatan seperti
                    Opening Ceremony,
                    Tel-U Explore, Seminar, Trial Class, Campus Tour, hingga mengunjungi booth fakultas melalui satu
                    platform yang memudahkan proses
                    registrasi, presensi, serta pengumpulan poin selama acara berlangsung.</p>
                <p>JMelalui Open House Telkom University, calon mahasiswa diharapkan dapat mengenal lebih dekat budaya
                    akademik, inovasi, dan ekosistem
                    pembelajaran di Telkom University sehingga memperoleh gambaran yang lebih jelas dalam menentukan
                    pilihan pendidikan tinggi yang sesuai dengan
                    minat dan cita-cita mereka.</p>
            </div>
        </div>

        <!-- Features Section with Tabs -->
        <section class="features" id="features">
            <h2 class="section-title">Kegiatan Open House</h2>
            <div class="features-container">
                <div class="feature-tabs">
                    <div class="tab-item active" data-tab="performance">
                        <span class="tab-icon">⚡</span>
                        <span>Opening Ceremony</span>
                    </div>
                    <div class="tab-item" data-tab="security">
                        <span class="tab-icon">🔒</span>
                        <span>Tel-U Explore</span>
                    </div>
                    <div class="tab-item" data-tab="network">
                        <span class="tab-icon">🌐</span>
                        <span>Seminar</span>
                    </div>
                    <div class="tab-item" data-tab="analytics">
                        <span class="tab-icon">📊</span>
                        <span>Trial Class</span>
                    </div>
                    <div class="tab-item" data-tab="integration">
                        <span class="tab-icon">🔧</span>
                        <span>Campus Tour</span>
                    </div>
                </div>
                <div class="feature-content">

                    <!-- Opening Ceremony -->
                    <div class="content-panel active" id="performance">
                        <h3>Opening Ceremony</h3>

                        <p>
                            Opening Ceremony merupakan rangkaian pembuka yang menandai dimulainya
                            kegiatan Open House Telkom University. Pada sesi ini peserta akan
                            mendapatkan sambutan dari pimpinan universitas, pengenalan mengenai
                            Telkom University, serta informasi terkait seluruh rangkaian kegiatan
                            yang dapat diikuti selama acara berlangsung.
                        </p>

                        <ul class="feature-list">
                            <li>Sambutan dari pimpinan Telkom University</li>
                            <li>Pengenalan profil dan keunggulan kampus</li>
                            <li>Informasi jadwal kegiatan Open House</li>
                            <li>Penampilan pembuka dan hiburan</li>
                        </ul>
                    </div>

                    <!-- Tel-U Explore -->
                    <div class="content-panel" id="security">
                        <h3>Tel-U Explore</h3>

                        <p>
                            Tel-U Explore memberikan kesempatan kepada peserta untuk mengunjungi
                            berbagai booth fakultas, program studi, serta unit pendukung di
                            Telkom University. Peserta dapat berdiskusi secara langsung dengan
                            dosen dan mahasiswa untuk memperoleh informasi mengenai kurikulum,
                            fasilitas, beasiswa, hingga prospek karier setiap program studi.
                        </p>

                        <ul class="feature-list">
                            <li>Mengunjungi booth fakultas dan program studi</li>
                            <li>Konsultasi langsung bersama dosen dan mahasiswa</li>
                            <li>Informasi fasilitas, beasiswa, dan prestasi</li>
                            <li>Mengumpulkan poin untuk program reward</li>
                        </ul>
                    </div>

                    <!-- Seminar -->
                    <div class="content-panel" id="network">
                        <h3>Seminar</h3>

                        <p>
                            Seminar menghadirkan pembicara dari kalangan akademisi maupun praktisi
                            industri yang membahas perkembangan teknologi, inovasi, dunia kerja,
                            serta berbagai peluang karier di masa depan. Kegiatan ini bertujuan
                            memberikan wawasan baru sekaligus inspirasi kepada calon mahasiswa.
                        </p>

                        <ul class="feature-list">
                            <li>Narasumber dari akademisi dan industri</li>
                            <li>Materi mengenai teknologi dan inovasi</li>
                            <li>Sesi diskusi interaktif dan tanya jawab</li>
                            <li>Menambah wawasan mengenai dunia perkuliahan</li>
                        </ul>
                    </div>

                    <!-- Trial Class -->
                    <div class="content-panel" id="analytics">
                        <h3>Trial Class</h3>

                        <p>
                            Trial Class merupakan simulasi proses pembelajaran yang memberikan
                            pengalaman kepada peserta untuk merasakan suasana perkuliahan di
                            Telkom University. Kelas dipandu langsung oleh dosen sesuai bidang
                            keilmuan sehingga peserta memperoleh gambaran nyata mengenai metode
                            pembelajaran yang diterapkan.
                        </p>

                        <ul class="feature-list">
                            <li>Simulasi perkuliahan bersama dosen</li>
                            <li>Pengenalan metode pembelajaran</li>
                            <li>Pengalaman belajar layaknya mahasiswa</li>
                            <li>Interaksi langsung dengan pengajar</li>
                        </ul>
                    </div>

                    <!-- Campus Tour -->
                    <div class="content-panel" id="integration">
                        <h3>Campus Tour</h3>

                        <p>
                            Campus Tour mengajak peserta berkeliling lingkungan Telkom University
                            untuk mengenal berbagai fasilitas kampus seperti ruang kuliah,
                            laboratorium, perpustakaan, pusat kegiatan mahasiswa, hingga area
                            penunjang lainnya. Kegiatan ini memberikan gambaran langsung mengenai
                            kehidupan akademik di lingkungan kampus.
                        </p>

                        <ul class="feature-list">
                            <li>Mengelilingi area kampus bersama pemandu</li>
                            <li>Mengunjungi laboratorium dan ruang kuliah</li>
                            <li>Melihat fasilitas pendukung mahasiswa</li>
                            <li>Mengenal lingkungan kampus secara langsung</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        <!-- Poster Open House -->
        <div class="about-content" style="margin-top:80px; display:flex; justify-content:center;">
            <img src="{{ asset('images/user/poster.jpeg') }}" alt="Poster Open House Telkom University"
                style="width:500px; max-width:100%; height:auto; border-radius:18px; box-shadow:0 10px 30px rgba(0,0,0,.2);">
        </div>



        <!-- Contact Section -->
        <section class="contact" id="contact">
            <div class="contact-info">
                <h3>Connect With Us</h3>
                <div class="info-item">
                    <div class="info-icon">📧</div>
                    <div class="info-details">
                        <h4>Email</h4>
                        <p>info@smbbtelkom.ac.id</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📱</div>
                    <div class="info-details">
                        <h4>Phone</h4>
                        <p>(022) 7565930</p>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-details">
                        <h4>Location</h4>
                        <p>Jl. Telekomunikasi No. 1,. Terusan Buah Batu. Bandung 40257,. Jawa Barat, Indonesia</p>
                    </div>
                </div>

                <div class="map-container">
                    <div class="map-placeholder">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.3389366293327!2d107.62558207572332!3d-6.9692819930313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9bc3974981d%3A0x613eec0feec9fcf7!2sTelkom%20University%20Landmark%20Tower%20(TULT)!5e0!3m2!1sen!2sid!4v1759571561672!5m2!1sen!2sid"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="map-overlay"></div>
                </div>
            </div>

        </section>

        <!-- Footer -->
        <!-- Footer -->
        <footer>
            <div class="footer-content">

                <hr style="margin:25px auto;opacity:.2;max-width:800px;">

                <p class="copyright">
                    © {{ date('Y') }} Open House Telkom University. All Rights Reserved.
                </p>

            </div>
        </footer>
        <script src="{{ asset('js/templatemo-electric-scripts.js') }}"></script>
</body>

</html>