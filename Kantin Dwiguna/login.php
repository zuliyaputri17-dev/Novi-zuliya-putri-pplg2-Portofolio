<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kantin Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f0f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrapper {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            width: 900px;
            min-height: 560px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        }

        /* ── LEFT PANEL ── */
        .left {
            width: 52%;
            background: linear-gradient(160deg, rgba(0,0,0,0.35) 0%, rgba(0,0,0,0.10) 100%),
                        url('https://images.unsplash.com/photo-1567521464027-f127ff144326?w=800&q=80') center/cover no-repeat;
            position: relative;
            padding: 32px 36px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 42px; height: 42px;
            background: #E8510A;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        .logo-icon svg { width: 22px; height: 22px; fill: #fff; }

        .logo-text { line-height: 1.1; }
        .logo-text .top { font-weight: 800; font-size: 16px; color: #fff; letter-spacing: 1px; }
        .logo-text .bot { font-weight: 600; font-size: 11px; color: #ffb07a; letter-spacing: 2px; text-transform: uppercase; }

        .left-copy {
            color: #fff;
        }

        .left-copy h1 {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .left-copy h1 span { color: #FF6B1A; }

        .left-copy p {
            font-size: 14px;
            color: rgba(255,255,255,0.85);
            line-height: 1.6;
            max-width: 260px;
        }

        /* ── RIGHT PANEL ── */
        .right {
            width: 48%;
            padding: 40px 44px;
            display: flex;
            align-items: center;
        }

        .form-box { width: 100%; }

        .avatar {
            width: 64px; height: 64px;
            background: #FF6B1A;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }

        .avatar svg { width: 32px; height: 32px; fill: #fff; }

        .form-box h2 {
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .form-box .subtitle {
            text-align: center;
            font-size: 13px;
            color: #888;
            margin-bottom: 28px;
        }

        .field { margin-bottom: 16px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrap .icon {
            position: absolute;
            left: 14px;
            color: #aaa;
            display: flex;
        }

        .input-wrap .icon svg { width: 17px; height: 17px; }

        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: #333;
            background: #fafafa;
            outline: none;
            transition: border-color .2s, background .2s;
        }

        .input-wrap input:focus {
            border-color: #FF6B1A;
            background: #fff;
        }

        .input-wrap input::placeholder { color: #bbb; }

        .toggle-pw {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            display: flex;
            padding: 0;
        }

        .toggle-pw svg { width: 18px; height: 18px; }

        .row-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            margin-top: 4px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: #555;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #FF6B1A;
            cursor: pointer;
        }

        .forgot {
            font-size: 13px;
            font-weight: 600;
            color: #FF6B1A;
            text-decoration: none;
        }

        .forgot:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #FF6B1A;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .1s;
            letter-spacing: 0.3px;
        }

        .btn-login:hover { background: #e05a0c; }
        .btn-login:active { transform: scale(0.99); }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0 16px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .back-home {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 13px;
            color: #555;
            text-decoration: none;
            font-weight: 500;
        }

        .back-home svg { width: 16px; height: 16px; color: #FF6B1A; }
        .back-home:hover { color: #FF6B1A; }
    </style>
</head>
<body>

<?php
    require_once 'koneksi.php';
    if (isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = conn()->query($sql);
        if($result->num_rows > 0) {
            echo "<script>alert('Berhasil login!'); location.href='lihat.php';</script>";
        } else {
            echo "<script>alert('Akun belum terdaftar atau password salah.');</script>";
        }
    }
?>

<div class="wrapper">

    <!-- LEFT -->
    <div class="left">
        <div class="logo">
            <div class="logo-icon">
                <!-- fork & knife icon -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 3v7H9V3H7v7H5V3H3v8a4 4 0 0 0 3.5 3.97V21h2v-6.03A4 4 0 0 0 12 11V3h-1zm8-1c-1.66 0-3 1.34-3 3v5h2v11h2V3a2 2 0 0 0-1-1.73V2z"/>
                </svg>
            </div>
            <div class="logo-text">
                <div class="top">KANTIN</div>
                <div class="bot">SEKOLAH</div>
            </div>
        </div>

        <div class="left-copy">
            <h1>Selamat Datang<br><span>Kembali!</span></h1>
            <p>Login untuk mengakses dan mengelola data menu kantin sekolah.</p>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <div class="form-box">

            <div class="avatar">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
            </div>

            <h2>Login Pengguna</h2>
            <p class="subtitle">Silakan masuk untuk melanjutkan</p>

            <form action="login.php" method="post">

                <div class="field">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        </span>
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/></svg>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                        <button type="button" class="toggle-pw" onclick="togglePw()" title="Tampilkan password">
                            <svg id="eye-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 17 4.5 12 4.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg>
                        </button>
                    </div>
                </div>


                <button type="submit" name="login" class="btn-login">Login</button>

            </form>

            <div class="divider"></div>

            <a href="index.php" class="back-home">
                Kembali ke beranda
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            </a>

        </div>
    </div>

</div>

<script>
    function togglePw() {
        const pw = document.getElementById('password');
        pw.type = pw.type === 'password' ? 'text' : 'password';
    }
</script>

</body>
</html>