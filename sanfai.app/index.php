<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - db.Sanfai</title>
    <style>
       
       * {
        margin: 0;
        padding:0;
        box-sizing: border-box;
        font-family: 'poppins', sans-serif;

       }
        body {
            font-family: sans-serif;
            background: url('GFAR.jpeg') no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
             background-position: center;
            
        }

        .wrapper {
         position: relative;
         width: 400px;
         height: 440px;
         background: transparent;
         border: 2px solid rgba(225, 225, 225, .5);
         border-radius: 20px;
         backdrop-filter: blur(20px);
         box-shadow: 0 0 30px rgba(0, 0, 0, .5);
            
        }

        .tampilan {
           padding: 70px 50px;
            display: flex;
            align-items: center;
        }

        .form-box { width: 100%; }

        .avatar {
            width: 20px; height: 50px;
            
            display: flex; align-items: center; justify-content: center;
            margin: 20PX auto 20px;
        }

       

        .form-box h2 {
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 10px  5PX;
        }

        

        .field { margin-bottom: 16px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
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

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #1068cd;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .1s;
           
        }

        .btn-login:hover { background: #1c50c9; }
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

       
    </style>
</head>
<body>

<?php
    require_once 'konek.php';
    if (isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM login_admin WHERE username='$username' AND password='$password'";
        $result = conn()->query($sql);
        if($result->num_rows > 0) {
            echo "<script> location.href='menu.php';</script>";
        } else {
            echo "<script>alert('Akun belum terdaftar atau password salah.');</script>";
        }
    }
?>

<div class="wrapper">
  
    <div class="tampilan">
        <div class="form-box">

            <div class="avatar">
              <img src="pot.png" alt="">
            </div>

            <h2>Login Pengguna</h2>
           

            <form action="index.php" method="post">

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

