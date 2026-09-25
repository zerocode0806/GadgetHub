<?php
require_once APP_ROOT . '/app/Support/koneksi.php';


// Cek jika form login sudah disubmit
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain text password input

    // Cek user berdasarkan username
    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
    
    if (mysqli_num_rows($cek) > 0) {
        $data = mysqli_fetch_array($cek);

        // Verifikasi password dengan password yang terenkripsi
        if (password_verify($password, $data['password'])) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['level'] = $data['level']; // level: admin/kasir

            $nama = $data['nama']; // Ambil nama pengguna dari database

            // Redirect ke /dashboard dengan level pengguna
            echo "<script>
                    alert('Selamat datang $nama ($data[level])');
                    window.location = '/dashboard'; // Arahkan ke /dashboard
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
require APP_ROOT . '/resources/views/auth/index.php';
