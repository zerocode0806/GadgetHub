<?php
require_once APP_ROOT . '/app/Support/koneksi.php';

// Get the current settings from the database
$query = "SELECT * FROM settings WHERE id = 1";
$result = mysqli_query($koneksi, $query);
$settings = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $business_name = mysqli_real_escape_string($koneksi, $_POST['business_name']);
    $address = mysqli_real_escape_string($koneksi, $_POST['address']);
    $phone = mysqli_real_escape_string($koneksi, $_POST['phone']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
        $logo = $_FILES['logo']['name'];
        $logo_tmp = $_FILES['logo']['tmp_name'];
        $logo_path = APP_ROOT . '/public/uploads/' . $logo;
        
        // Delete old logo if exists
        if (!empty($settings['logo']) && file_exists(APP_ROOT . '/public/uploads/' . $settings['logo'])) {
            unlink(APP_ROOT . '/public/uploads/' . $settings['logo']);
        }
        
        move_uploaded_file($logo_tmp, $logo_path);
    } else {
        $logo = $settings['logo'];
    }

    // Update settings
    $updateQuery = "UPDATE settings SET 
        business_name = '$business_name',
        address = '$address',
        phone = '$phone',
        email = '$email',
        logo = '$logo'
        WHERE id = 1";

    if (mysqli_query($koneksi, $updateQuery)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Settings have been updated successfully',
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update settings'
                });
              </script>";
    }
}
require APP_ROOT . '/resources/views/admin/settings.php';
