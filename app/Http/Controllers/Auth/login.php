<?php
include 'koneksi.php';


// Cek jika form login sudah disubmit
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Cek user berdasarkan username
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $cek = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($cek) > 0) {
        $data = mysqli_fetch_array($cek);

        // Verifikasi password dengan password yang terenkripsi
        if (password_verify($password, $data['password'])) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['username'] = $data['username'];
                $_SESSION['level'] = $data['level'];
                session_regenerate_id(true);

                $nama = htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8');
                $redirect = $data['level'] === 'user' ? 'user_home.php' : 'dashboard.php';

            echo "<script>
                    alert('Selamat datang $nama ($data[level])');
                    window.location = '$redirect';
                </script>";
        } else {
            echo "<script>
                alert('Password salah');
                window.location = 'login.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Username tidak ditemukan');
            window.location = 'login.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        .card-header {
            background: transparent;
            border-bottom: none;
            padding: 30px 30px 0;
        }

        .logo-container {
            background: white;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .logo-container i {
            font-size: 40px;
            color: var(--primary-color);
        }

        .card-body {
            padding: 30px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-floating input {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 15px;
            height: 55px;
            transition: all 0.3s ease;
        }

        .form-floating input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .form-floating label {
            padding-left: 15px;
            color: #6c757d;
        }

        .btn-login {
            background: var(--accent-color);
            border: none;
            padding: 12px;
            height: 50px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .card-footer {
            background: transparent;
            border-top: none;
            padding: 0 30px 30px;
        }

        .register-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .register-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-left: none;
            cursor: pointer;
        }

        .password-toggle {
            color: #6c757d;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 10px;
            }
            
            .card-body {
                padding: 20px;
            }
        }
    </style>
    <link rel="stylesheet" href="css/theme.css">
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header text-center">
                <div class="logo-container">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h3 class="mb-0">Login Aplikasi Kasir</h3>
                <p class="text-muted">Silakan masuk ke akun Anda</p>
            </div>
            
            <div class="card-body">
                <form method="post">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" 
                               placeholder="Username" required>
                        <label for="username">
                            <i class="fas fa-user me-2"></i>Username
                        </label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Password" required>
                        <label for="password">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-login w-100 text-white">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
            </div>
            
            <div class="card-footer text-center">
                <p class="mb-0">Belum punya akun? 
                    <a href="register.php" class="register-link">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.password-toggle');
            const password = document.querySelector('#password');
            
            if (togglePassword && password) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
