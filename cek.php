<?php
// Hash yang diberikan
$hashed_password = "$2y$10$4giKOHHxV0jW1N8N.M5SPet/T8CrTxMgnK.LH2LF76ZIv/Y3KTCOy";

// String asli yang ingin diuji
$plain_text_password = "ubeddahlan";

// Verifikasi apakah string cocok dengan hash
if (password_verify($plain_text_password, $hashed_password)) {
    echo "Password cocok!";
} else {
    echo "Password tidak cocok!";
}
?>
