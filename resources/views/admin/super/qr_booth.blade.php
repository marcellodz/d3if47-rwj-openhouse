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

        <img src="{{ $qrUrl }}" alt="QR Booth">

        <div class="code">
            {{ $qrCode }}
        </div>

        <a href="{{ url('/admin') }}">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>