<?php
    include 'koneksi.php';

    // Check if 'id' is passed in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Sanitize the input to prevent SQL injection
        $id = mysqli_real_escape_string($koneksi, $id);

        // Execute the DELETE query
        $query = mysqli_query($koneksi, "DELETE FROM user WHERE id_user='$id'");

        if ($query) {
            echo "<script>
                alert('Data berhasil dihapus');
                history.back(); // Kembali ke halaman sebelumnya dan refresh
            </script>";
        } else {
            echo "<script>
                alert('Data gagal dihapus');
                history.back(); // Kembali ke halaman sebelumnya dan refresh
            </script>";
        }
    }
?>
