<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Scanner Booth - Open House</title>

    <!-- FAVICON -->
    <link rel="shortcut icon"
          href="{{ asset('images/user/telu-logo.png') }}"
          type="image/x-icon">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;500;700&display=swap"
          rel="stylesheet">

    <!-- MAIN CSS -->
    <link rel="stylesheet"
          href="{{ asset('css/user/templatemo-electric-xtra.css') }}">

    <!-- SCANNER CSS -->
    <link rel="stylesheet"
          href="{{ asset('css/user/scanner.css') }}">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- HTML5 QR -->
    <script src="https://unpkg.com/html5-qrcode"></script>

</head>

<body>

    <!-- BACKGROUND -->
    <div class="grid-bg"></div>

    <div class="scanlines"></div>

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

        </div>

    </nav>

    <!-- CONTENT -->
    <section class="features"
             id="features">

        <div class="features-container">

            <center>

                <div class="content-panel">

                    <h3>📷 Scan Booth</h3>

                </div>

            </center>

        </div>

        <!-- LOADING -->
        <div id="loading">

            Klik tombol di bawah untuk mengaktifkan kamera...

        </div>

        <!-- QR READER -->
        <div id="reader">

            <div class="scan-laser"></div>

        </div>

        <!-- BUTTON -->
        <button id="startScanBtn">

            🚀 Mulai Scan

        </button>

        <button id="switchCamBtn"
                style="display:none; margin-top:10px;">

            🔄 Ganti Kamera

        </button>

        <!-- RESULT -->
        <div id="result-container">

            <p>Hasil Pemindaian:</p>

            <strong id="result">

                Menunggu...

            </strong>

        </div>

    </section>

    <!-- FOOTER -->
    <footer>

        <div class="footer-content">

            <p class="copyright">

                © 2025 ELECTRIC XTRA.
                All rights reserved.

            </p>

        </div>

    </footer>

    <!-- SCRIPT -->
    <script>

        const html5QrCode =
            new Html5Qrcode("reader");

        const resultElement =
            document.getElementById("result");

        const startScanBtn =
            document.getElementById("startScanBtn");

        const loadingElement =
            document.getElementById("loading");

        const switchCamBtn =
            document.getElementById("switchCamBtn");

        let isScanning = false;

        let cameraList = [];

        let currentCameraId = null;

        /*
        =========================
        SEND TO LARAVEL
        =========================
        */

        function sendDataToLaravel(qrData) {

            resultElement.innerHTML = `
                <i class="fas fa-satellite-dish"></i>
                Mengirim data ke server...
            `;

            fetch('/process-qr', {

                method: 'POST',

                headers: {

                    'Content-Type':
                        'application/x-www-form-urlencoded',

                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}'

                },

                body:
                    'qr_data=' +
                    encodeURIComponent(qrData)

            })

            .then(res => res.json())

            .then(data => {

                if (data.success) {

                    resultElement.innerHTML = `
                        <i class="fas fa-check-circle"></i>
                        ${data.message}
                    `;

                } else {

                    resultElement.innerHTML = `
                        <i class="fas fa-exclamation-circle"></i>
                        ${data.message}
                    `;

                }

            })

            .catch(err => {

                resultElement.innerHTML = `
                    <i class="fas fa-times-circle"></i>
                    Gagal kirim ke server.
                `;

                console.error(err);

            });

        }

        /*
        =========================
        QR SUCCESS
        =========================
        */

        function onScanSuccess(decodedText) {

            resultElement.innerHTML = `
                <i class="fas fa-search"></i>
                <b>${decodedText}</b>
            `;

            html5QrCode.stop()

                .then(() => {

                    sendDataToLaravel(decodedText);

                });

        }

        /*
        =========================
        START CAMERA
        =========================
        */

        function startScanner(cameraId) {

            const config = {

                fps: 10,

                qrbox: {

                    width: 250,
                    height: 250

                }

            };

            html5QrCode.start(

                cameraId,
                config,
                onScanSuccess

            )

            .then(() => {

                resultElement.innerHTML = `
                    <i class="fas fa-camera"></i>
                    Arahkan kamera ke QR Code booth...
                `;

                loadingElement.style.display = "none";

            })

            .catch(err => {

                loadingElement.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    Gagal membuka kamera:
                    ${err.message}
                `;

                console.error(err);

            });

        }

        /*
        =========================
        START / STOP
        =========================
        */

        startScanBtn.addEventListener('click', () => {

            if (!isScanning) {

                startScanBtn.disabled = true;

                startScanBtn.innerHTML = `
                    <i class="fas fa-sync-alt fa-spin"></i>
                    Mengaktifkan Kamera...
                `;

                loadingElement.innerHTML = `
                    <i class="fas fa-spinner fa-spin"></i>
                    Mengakses kamera perangkat...
                `;

                Html5Qrcode.getCameras()

                    .then(devices => {

                        if (devices.length === 0) {

                            resultElement.innerHTML = `
                                <i class="fas fa-exclamation-circle"></i>
                                Tidak ada kamera ditemukan.
                            `;

                            return;

                        }

                        const backCam = devices.find(cam =>

                            cam.label.toLowerCase().includes("back")
                            ||
                            cam.label.toLowerCase().includes("rear")

                        );

                        const selectedCam =
                            backCam
                            ? backCam.id
                            : devices[0].id;

                        startScanner(selectedCam);

                        isScanning = true;

                        startScanBtn.disabled = false;

                        startScanBtn.innerHTML =
                            '❌ Matikan Kamera';

                        cameraList = devices;

                        currentCameraId = selectedCam;

                        if (cameraList.length > 1) {

                            switchCamBtn.style.display =
                                "block";

                        }

                    })

                    .catch(err => {

                        resultElement.innerHTML = `
                            <i class="fas fa-times-circle"></i>
                            Kamera tidak bisa dibuka:
                            ${err.message}
                        `;

                    });

            } else {

                html5QrCode.stop()

                    .then(() => {

                        html5QrCode.clear();

                        isScanning = false;

                        startScanBtn.innerHTML =
                            '🚀 Mulai Scan';

                        resultElement.innerHTML =
                            'Menunggu...';

                        loadingElement.style.display =
                            "block";

                    })

                    .catch(err => {

                        console.error(
                            "Gagal menghentikan kamera:",
                            err
                        );

                    });

            }

        });

        /*
        =========================
        SWITCH CAMERA
        =========================
        */

        async function switchCamera() {

            if (!isScanning || cameraList.length < 2)
                return;

            const currentIndex =
                cameraList.findIndex(
                    cam => cam.id === currentCameraId
                );

            const nextIndex =
                (currentIndex + 1)
                %
                cameraList.length;

            const nextCameraId =
                cameraList[nextIndex].id;

            await html5QrCode.stop();

            html5QrCode.clear();

            startScanner(nextCameraId);

            currentCameraId = nextCameraId;

        }

        switchCamBtn.addEventListener('click', () => {

            switchCamBtn.innerHTML = `
                <i class="fas fa-sync-alt fa-spin"></i>
                Switching...
            `;

            switchCamera()

                .then(() => {

                    switchCamBtn.innerHTML =
                        '🔄 Ganti Kamera';

                });

        });

    </script>

</body>

</html>