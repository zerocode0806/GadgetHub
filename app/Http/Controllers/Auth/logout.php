<?php
require_once APP_ROOT . '/app/Support/koneksi.php';
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destroy all session data
session_destroy();

// Redirect to login page with success message
echo "<script>
    alert('Anda berhasil logout!');
    window.location = '/login';
</script>";
exit();
