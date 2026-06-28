<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Staff Scanner - Open House
    </title>

    <link rel="icon" href="{{ asset('images/user/telu-logo.png') }}" type="image/png">

    <meta http-equiv="Permissions-Policy" content="autoplay=*">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #050505;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .topbar {
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border-bottom: 1px solid rgba(255, 51, 51, 0.4);
            background: rgba(15, 15, 15, 0.95);
        }

        .back-btn {
            position: absolute;
            left: 20px;
            padding: 9px 14px;
            border-radius: 8px;
            border: 1px solid #ff3333;
            background: transparent;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
        }

        .back-btn:hover {
            background: rgba(255, 51, 51, 0.18);
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #ff3333;
            letter-spacing: 1px;
        }

        .container {
            max-width: 900px;
            margin: 24px auto;
            padding: 0 16px;
        }

        .hint {
            color: #ddd;
            font-size: 16px;
            margin-bottom: 18px;
        }

        #reader-box {
            display: none;
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            border: 1px solid #ff3333;
            border-radius: 16px;
            overflow: hidden;
            background: #111;
        }

        #reader {
            width: 100%;
            min-height: 360px;
        }

        #reader video {
            object-fit: cover !important;
        }

        .btn-row {
            margin-top: 22px;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        button.main-btn,
        button.switch-btn {
            border: none;
            border-radius: 10px;
            padding: 13px 28px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button.main-btn {
            background: linear-gradient(90deg, #ff3333, #cc0000);
        }

        button.switch-btn {
            background: #333;
            display: none;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        #resultBox {
            margin: 28px auto 0;
            max-width: 100%;
            min-height: 60px;
            padding: 16px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            color: #eee;
            line-height: 1.6;
            overflow-x: auto;
        }

        .success {
            color: #00ff8f;
            font-weight: bold;
        }

        .error {
            color: #ff6666;
            font-weight: bold;
        }

        .raw-box {
            margin-top: 12px;
            padding: 10px;
            border-radius: 8px;
            background: #111;
            color: #aaa;
            word-break: break-all;
            font-size: 13px;
        }

        .participant-info,
        .kegiatan-list {
            text-align: left;
            background: rgba(20, 20, 20, 0.95);
            border: 1px solid rgba(255, 51, 51, 0.25);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .participant-info h3,
        .kegiatan-list h3 {
            color: #ff4444;
            margin-top: 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            color: #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            color: #fff;
        }

        th,
        td {
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 10px;
            font-size: 14px;
        }

        th {
            background: rgba(255, 51, 51, 0.2);
            color: #ff6666;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 13px;
        }

        .status.hadir {
            background: rgba(0, 255, 143, 0.12);
            color: #00ff8f;
            border: 1px solid rgba(0, 255, 143, 0.3);
        }

        .status.belum-hadir {
            background: rgba(255, 255, 255, 0.08);
            color: #ccc;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .btn-hadir,
        .btn-close {
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-hadir {
            background: #e60000;
        }

        .btn-hadir.active {
            background: #555;
        }

        .btn-close {
            background: #333;
        }

        @media (max-width: 700px) {
            .title {
                font-size: 19px;
            }

            .back-btn {
                left: 10px;
                padding: 8px 10px;
                font-size: 13px;
            }

            #reader {
                min-height: 300px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 8px;
            }
        }
    </style>
</head>

<body>

    <div class="topbar">

        <button class="back-btn" onclick="window.location.href='{{ route('admin.dashboard') }}'">
            ← Kembali
        </button>

        <div class="title">
            Staff Scanner
        </div>

    </div>

    <div class="container">

        <p class="hint">
            Arahkan kamera ke QR peserta.
        </p>

        <div id="reader-box">
            <div id="reader"></div>
        </div>

        <div class="btn-row">

            <button id="startScanBtn" class="main-btn">
                Mulai Scan
            </button>

            <button id="switchCamBtn" class="switch-btn">
                Ganti Kamera
            </button>

        </div>

        <div id="resultBox">
            Menunggu scan...
        </div>

    </div>

    <script>
        const startBtn =
            document.getElementById("startScanBtn");

        const switchBtn =
            document.getElementById("switchCamBtn");

        const resultBox =
            document.getElementById("resultBox");

        const readerBox =
            document.getElementById("reader-box");

        const csrfToken =
            document.querySelector('meta[name="csrf-token"]').getAttribute("content");

        const html5QrCode =
            new Html5Qrcode("reader");

        let cameras = [];

        let currentCameraIndex = 0;

        let isScanning = false;

        let isProcessing = false;

        function setResult(html) {

            resultBox.innerHTML = html;

        }

        <div id="rewardPopup" class="popup-overlay" style="display:none;">
            <div class="popup-content">

                <button onclick="closeRewardPopup()">✖</button>

                <div id="rewardPopupBody"></div>

            </div>
        </div>

        function extractIdUser(decodedText) {

            const clean =
                String(decodedText).trim();

            /*
            =========================
            FORMAT JSON
            =========================
            */

            try {

                const obj = JSON.parse(clean);

                if (obj.type && obj.iduser) {

                    return obj;

                }

            } catch (e) { }

            /*
            =========================
            FORMAT ANGKA LANGSUNG
            =========================
            */

            if (/^\d+$/.test(clean)) {

                return clean;

            }

            return null;

        }

        function getBackCameraIndex(devices) {

            const index =
                devices.findIndex(cam => {

                    const label =
                        (cam.label || "").toLowerCase();

                    return (
                        label.includes("back") ||
                        label.includes("rear") ||
                        label.includes("environment") ||
                        label.includes("belakang")
                    );

                });

            return index >= 0 ? index : 0;

        }

        function getScannerConfig() {

            return {

                fps: 60,

                qrbox: function (viewfinderWidth, viewfinderHeight) {

                    const minEdge =
                        Math.min(
                            viewfinderWidth,
                            viewfinderHeight
                        );

                    const qrboxSize =
                        Math.floor(minEdge * 0.85);

                    return {
                        width: qrboxSize,
                        height: qrboxSize
                    };

                },

                aspectRatio: 1.0,

                disableFlip: false,

                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                },

                rememberLastUsedCamera: true

            };

        }

        async function initCameras() {

            if (cameras.length > 0) {

                return;

            }

            try {

                cameras =
                    await Html5Qrcode.getCameras();

                if (!cameras || cameras.length === 0) {

                    setResult(`
                        <span class="error">
                            Kamera tidak ditemukan.
                        </span>
                    `);

                    startBtn.disabled = true;

                    return;

                }

                currentCameraIndex =
                    getBackCameraIndex(cameras);

                if (cameras.length > 1) {

                    switchBtn.style.display =
                        "inline-block";

                }

            } catch (err) {

                console.error(err);

                setResult(`
                    <span class="error">
                        Gagal mengakses kamera.
                        Cek izin kamera browser.
                    </span>
                `);

            }

        }

        async function startScanner() {

            if (isScanning) {

                return;

            }

            await initCameras();

            if (!cameras[currentCameraIndex]) {

                return;

            }

            const cameraId =
                cameras[currentCameraIndex].id;

            readerBox.style.display =
                "block";

            startBtn.disabled =
                true;

            startBtn.innerHTML =
                "Membuka Kamera...";

            setResult("Membuka kamera...");

            try {

                await html5QrCode.start(
                    cameraId,
                    getScannerConfig(),
                    onScanSuccess,
                    onScanFailure
                );

                isScanning =
                    true;

                isProcessing =
                    false;

                startBtn.disabled =
                    false;

                startBtn.innerHTML =
                    "Hentikan Scan";

                setResult(
                    "Arahkan kamera ke QR peserta..."
                );

            } catch (err) {

                console.error(err);

                startBtn.disabled =
                    false;

                startBtn.innerHTML =
                    "Mulai Scan";

                readerBox.style.display =
                    "none";

                setResult(`
                    <span class="error">
                        Kamera gagal dibuka.
                    </span>
                    <br>
                    <small>
                        Pastikan izin kamera sudah di-allow.
                    </small>
                `);

            }

        }

        async function stopScanner(message = "Scan dihentikan.") {

            if (!isScanning) {

                return;

            }

            try {

                await html5QrCode.stop();

                html5QrCode.clear();

            } catch (err) {

                console.warn(err);

            }

            isScanning =
                false;

            startBtn.innerHTML =
                "Mulai Scan";

            readerBox.style.display =
                "none";

            setResult(message);

        }

        async function onScanSuccess(decodedText) {

            if (isProcessing) {

                return;

            }

            isProcessing =
                true;

            const qr = extractIdUser(decodedText);

            if (!qr) {

                setResult("QR tidak dikenali.");
                return;

            }

            if (qr.type === "claim") {

                await loadRewardContent(qr.iduser);

            } else {

                await loadStaffContent(qr.iduser);

            }

            await stopScanner(
                "QR terbaca. Memproses..."
            );

        }

        function onScanFailure(errorMessage) {

            /*
            Diabaikan biar scanner ringan.
            */

        }

        async function loadStaffContent(iduser) {

            try {

                setResult(`
                    Mengambil data peserta ID
                    <span class="success">${iduser}</span>...
                `);

                const url =
                    "{{ url('/admin/staff/content') }}/" + encodeURIComponent(iduser);

                const response =
                    await fetch(url, {
                        method: "GET",
                        headers: {
                            "Accept": "text/html"
                        }
                    });

                const html =
                    await response.text();

                setResult(html);

            } catch (error) {

                console.error(error);

                setResult(`
                    <span class="error">
                        Gagal mengambil data peserta.
                    </span>
                `);

            }

        }

        async function toggleHadir(idKegiatan, idUser, btn) {

            console.log("toggleHadir:", {
                idKegiatan,
                idUser
            });

            if (!idKegiatan || !idUser) {

                alert("Data tombol tidak lengkap.");

                return;

            }

            const isActive =
                btn.classList.contains("active");

            const action =
                isActive ? "belum" : "hadir";

            try {

                btn.disabled =
                    true;

                const response =
                    await fetch("{{ route('admin.staff.presensi.update') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            iduser: idUser,
                            id_kegiatan: idKegiatan,
                            action: action
                        })
                    });

                const data =
                    await response.json();

                if (!data.success) {

                    alert(data.message || "Gagal update presensi.");

                    btn.disabled =
                        false;

                    return;

                }

                const row =
                    btn.closest("tr");

                const statusEl =
                    row.querySelector(".status");

                if (data.status === "Hadir") {

                    btn.classList.add("active");

                    btn.innerHTML =
                        "<i class='fas fa-undo'></i> Batalkan";

                    statusEl.textContent =
                        "Hadir";

                    statusEl.className =
                        "status hadir";

                } else {

                    btn.classList.remove("active");

                    btn.innerHTML =
                        "<i class='fas fa-check'></i> Hadir";

                    statusEl.textContent =
                        "Belum Hadir";

                    statusEl.className =
                        "status belum-hadir";

                }

                btn.disabled =
                    false;

            } catch (error) {

                console.error(error);

                btn.disabled =
                    false;

                alert("Gagal memperbarui status.");

            }

        }

        function closeScanResult() {

            setResult("Menunggu scan...");

            isProcessing =
                false;

        }

        startBtn.addEventListener("click", async () => {

            if (isScanning) {

                await stopScanner();

            } else {

                await startScanner();

            }

        });

        switchBtn.addEventListener("click", async () => {

            if (cameras.length < 2) {

                return;

            }

            const wasScanning =
                isScanning;

            if (wasScanning) {

                await stopScanner(
                    "Mengganti kamera..."
                );

            }

            currentCameraIndex =
                (currentCameraIndex + 1) %
                cameras.length;

            if (wasScanning) {

                await startScanner();

            }

        });

        window.toggleHadir =
            toggleHadir;

        window.closeScanResult =
            closeScanResult;

        async function loadRewardContent(iduser) {

            const response =
                await fetch("/admin/staff/reward/" + iduser);

            const html =
                await response.text();

            setResult(html);

        }

        function closeRewardPopup() {

            document.getElementById("rewardPopup").style.display = "none";

            closeScanResult();

        }

        async function confirmReward(iduser) {

            if (!confirm("Konfirmasi klaim hadiah?")) {
                return;
            }

            const response = await fetch(
                "{{ route('admin.staff.reward.confirm') }}",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        iduser: iduser
                    })
                }
            );

            const data = await response.json();

            alert(data.message);

            if (data.success) {
                loadRewardContent(iduser);
            }
        }
    </script>


</body>

</html>