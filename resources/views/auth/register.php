<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Daftar akun pelanggan GadgetHub" />
        <title>Daftar Akun Pelanggan - GadgetHub</title>
        <link rel="stylesheet" href="/assets/css/user.css">
        <link rel="stylesheet" href="/css/theme.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <style>
            body.user-site { min-height: 100vh; }
            .register-shell { margin: 0 auto; max-width: 620px; padding: 42px 24px 70px; }
            .register-brand { align-items: center; display: flex; font-family: Manrope, sans-serif; font-size: 1.12rem; gap: 10px; margin-bottom: 28px; }
            .register-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: clamp(24px, 5vw, 42px); }
            .register-card h1 { font-family: Manrope, sans-serif; font-size: 2rem; margin: 0 0 8px; }
            .register-card > p { color: var(--muted); margin: 0 0 28px; }
            .register-form { display: grid; gap: 17px; }
            .register-form .name-fields { display: grid; gap: 16px; grid-template-columns: 1fr 1fr; }
            .register-form .btn { border: 0; margin-top: 5px; }
            .register-links { border-top: 1px solid var(--line); display: flex; gap: 16px; justify-content: space-between; margin-top: 26px; padding-top: 20px; }
            @media (max-width: 560px) { .register-form .name-fields { grid-template-columns: 1fr; } .register-links { flex-direction: column; } }
        </style>
</head>
    <body class="user-site">
        <main class="register-shell">
            <a class="register-brand" href="/shop"><span class="brand-mark"><i class="fa-solid fa-basket-shopping"></i></span>GadgetHub</a>
            <section class="register-card">
                <h1>Daftar Akun Pelanggan GadgetHub</h1>
                <p>Buat akun untuk berbelanja, melihat pesanan, dan mengelola profil Anda.</p>
                <form class="register-form" method="POST" action="/register">
                    <div class="name-fields">
                        <div class="form-group">
                            <label for="inputFirstName">Nama depan</label>
                            <input class="form-control" id="inputFirstName" name="first_name" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="inputLastName">Nama belakang</label>
                            <input class="form-control" id="inputLastName" name="last_name" type="text" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputUsername">Username</label>
                        <input class="form-control" id="inputUsername" name="username" type="text" required>
                    </div>
                    <div class="name-fields">
                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input class="form-control" id="inputPassword" name="password" type="password" minlength="8" required>
                        </div>
                        <div class="form-group">
                            <label for="inputPasswordConfirm">Konfirmasi password</label>
                            <input class="form-control" id="inputPasswordConfirm" name="confirm_password" type="password" minlength="8" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Buat akun pelanggan</button>
                </form>
                <div class="register-links">
                    <a href="/login">Sudah punya akun? Masuk</a>
                    <a href="/shop">Kembali ke toko</a>
                </div>
            </section>
        </main>
    </body>
</html>
