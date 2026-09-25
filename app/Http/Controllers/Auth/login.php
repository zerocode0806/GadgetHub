<?php
require_once APP_ROOT . '/app/Support/koneksi.php';


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
                $redirect = $data['level'] === 'user' ? '/shop' : '/dashboard';

            echo "<script>
                    alert('Selamat datang $nama ($data[level])');
                    window.location = '$redirect';
                </script>";
        } else {
            echo "<script>
                alert('Password salah');
                window.location = '/login';
            </script>";
        }
    } else {
        echo "<script>
            alert('Username tidak ditemukan');
            window.location = '/login';
        </script>";
    }
}
require APP_ROOT . '/resources/views/auth/login.php';
