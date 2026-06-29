@php
    $qrUrl = 'https://quickchart.io/qr?size=400&text=' . urlencode($qrCode);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Booth</title>

    <style>
        body {
            background: #090909;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .qr-card {
            background: #151515;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid rgba(255, 0, 0, .35);
            box-shadow: 0 0 25px rgba(255, 0, 0, .2);
        }

        img {
            background: white;
            padding: 12px;
            border-radius: 12px;
            width: 320px;
            height: 320px;
        }

        .code {
            margin-top: 18px;
            font-size: 24px;
            font-weight: bold;
            color: #ff5555;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: white;
            text-decoration: none;
            background: #ff3333;
            padding: 10px 18px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
<div class="qr-card">

    <h2>{{ $booth->nama_booth }}</h2>

    <img
        id="qrImage"
        src="{{ $qrUrl }}"
        alt="QR Booth">

    <div class="code">
        {{ $qrCode }}
    </div>

    <div class="btn-group">

        <button
            class="btn btn-download"
            onclick="downloadQR()">

            ⬇ Download QR

        </button>

        <button
            class="btn btn-print"
            onclick="window.print()">

            🖨 Print

        </button>

    </div>

    <a
        href="{{ url('/admin') }}"
        class="btn-back">

        ← Kembali ke Dashboard

    </a>

</div>

<script>

function downloadQR() {

    const img = document.getElementById("qrImage");

    fetch(img.src)
        .then(response => response.blob())
        .then(blob => {

            const url = window.URL.createObjectURL(blob);

            const a = document.createElement("a");

            a.href = url;

            a.download = "{{ $booth->nama_booth }}.png";

            document.body.appendChild(a);

            a.click();

            a.remove();

            window.URL.revokeObjectURL(url);

        });

}

</script>

<style>

.btn-group{

    display:flex;

    justify-content:center;

    gap:12px;

    margin-top:20px;

    margin-bottom:20px;

}

.btn{

    border:none;

    cursor:pointer;

    padding:12px 22px;

    border-radius:10px;

    font-size:15px;

    font-weight:600;

    transition:.25s;

}

.btn-download{

    background:#28a745;

    color:#fff;

}

.btn-download:hover{

    background:#218838;

}

.btn-print{

    background:#007bff;

    color:#fff;

}

.btn-print:hover{

    background:#0069d9;

}

.btn-back{

    display:inline-block;

    text-decoration:none;

    padding:12px 24px;

    border-radius:10px;

    background:#444;

    color:#fff;

    transition:.25s;

}

.btn-back:hover{

    background:#222;

}

@media print{

    .btn-group,
    .btn-back{

        display:none;

    }

}

</style>
</body>
</html>