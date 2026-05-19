<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Admin Login - Open House
    </title>

    <!-- FAVICON -->
    <link rel="icon"
        href="{{ asset('images/user/telu-logo.png') }}"
        type="image/png">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet"
        href="{{ asset('css/admin/login.css') }}">

</head>

<body>

    <form class="login-box"
        method="POST"
        action="{{ route('admin.login.action') }}">

        @csrf

        <h2>
            Admin Login
        </h2>

        <input type="text"
            name="username"
            placeholder="Username"
            required
            autocomplete="off">

        <input type="password"
            name="password"
            placeholder="Password"
            required>

        <button type="submit">

            Masuk

        </button>

        @if(session('error'))

            <p class="error">

                {{ session('error') }}

            </p>

        @endif

    </form>

    <footer>

        © {{ date('Y') }}
        Open House Telkom University

    </footer>

</body>

</html>