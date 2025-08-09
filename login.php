<?php
    session_start();

    // kalau udah login, langsung lempar ke dashboard
    if (isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
    }

    // proses login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = strtolower(trim($_POST['username']));
        $password = trim($_POST['password']);

        // akun statis (bisa diganti ambil dari database)
        $users = [
            'owner'   => 'owner123',
            'kasir'   => 'kasir123',
            'pelayan' => 'pelayan123',
        ];

        if (isset($users[$username]) && $users[$username] === $password) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI-RERE</title>
    <style>
        /* ambil CSS login lu yang kemarin */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: #333030;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 380px;
            background: black;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
            padding: 40px 30px;
            text-align: center;
        }
        .title {
            font-size: 36px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 30px;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            text-align: left;
        }
        label {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
        }
        input {
            padding: 14px;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        input:focus {
            border-color: #4a6fcb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 111, 203, 0.2);
        }
        .login-btn {
            background: linear-gradient(to right, #4a6fcb, #6a11cb);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(106, 17, 203, 0.4);
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #ffffff;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="title">SI-RERE</div>

        <?php if (! empty($error)): ?>
            <div class="error"><?php echo $error ?></div>
        <?php endif; ?>

        <form class="login-form" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="footer">Created By Tari Suanggi</div>
    </div>
</body>
</html>
