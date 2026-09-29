<?php
$register_error = $register_error ?? '';
$register_values = $register_values ?? ['nama' => '', 'username' => '', 'no_telepon' => '', 'alamat' => ''];
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Buat akun pelanggan GadgetHub untuk berbelanja dan melacak pesanan gadget Anda.">
    <title>Buat Akun Pelanggan - GadgetHub</title>
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --reg-ink:#111; --reg-muted:#686868; --reg-line:#e7e7e7; --reg-soft:#f6f6f6; }
        * { box-sizing:border-box; }
        body.register-page { min-height:100vh; margin:0; padding:32px 18px; display:grid; place-items:center; background:#f6f6f6 !important; color:var(--reg-ink); }
        .register-shell { width:min(100%, 1040px); display:grid; grid-template-columns:minmax(280px,.85fr) minmax(0,1.15fr); overflow:hidden; border:1px solid var(--reg-line); border-radius:22px; background:#fff; box-shadow:0 22px 70px rgba(0,0,0,.08); }
        .register-intro { position:relative; min-height:100%; padding:48px 42px; display:flex; flex-direction:column; justify-content:space-between; overflow:hidden; background:#111; color:#fff; }
        .register-intro::after { content:""; position:absolute; width:330px; height:330px; right:-170px; bottom:6%; border:1px solid rgba(255,255,255,.14); border-radius:50%; box-shadow:0 0 0 34px rgba(255,255,255,.035),0 0 0 70px rgba(255,255,255,.025); pointer-events:none; }
        .register-brand { display:inline-flex; align-items:center; gap:11px; color:#fff !important; text-decoration:none; font-size:1.12rem; font-weight:750; letter-spacing:-.03em; }
        .register-brand-mark { width:40px; height:40px; display:grid; place-items:center; border:1px solid rgba(255,255,255,.36); border-radius:13px; font-size:1rem; }
        .intro-copy { position:relative; z-index:1; max-width:390px; margin:58px 0; }
        .intro-eyebrow { margin:0 0 14px; color:#c8c8c8; font-size:.78rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; }
        .intro-copy h1 { margin:0 0 16px; font-size:clamp(2rem,4vw,3rem); line-height:1.08; letter-spacing:-.055em; }
        .intro-copy p { margin:0; color:#c6c6c6; line-height:1.75; }
        .intro-benefits { position:relative; z-index:1; display:grid; gap:13px; margin:0; padding:0; list-style:none; color:#e8e8e8; font-size:.91rem; }
        .intro-benefits li { display:flex; align-items:center; gap:11px; }
        .intro-benefits i { width:20px; color:#fff; }
        .register-form-panel { padding:44px clamp(24px,5vw,58px) 36px; }
        .form-heading { margin-bottom:25px; }
        .form-heading .eyebrow { margin:0 0 8px; color:#777; font-size:.78rem; font-weight:700; letter-spacing:.13em; text-transform:uppercase; }
        .form-heading h2 { margin:0 0 8px; font-size:1.8rem; letter-spacing:-.045em; }
        .form-heading p { margin:0; color:var(--reg-muted); font-size:.94rem; line-height:1.6; }
        .register-alert { display:flex; gap:10px; align-items:flex-start; margin:0 0 18px; padding:13px 14px; border:1px solid #e1caca; border-radius:11px; background:#fbf7f7; color:#4a2525; font-size:.9rem; line-height:1.5; }
        .register-alert i { margin-top:2px; }
        .register-form { display:grid; gap:16px; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .field label { display:block; margin:0 0 7px; color:#222; font-size:.86rem; font-weight:650; }
        .field label .optional { color:#777; font-size:.78rem; font-weight:400; }
        .field-control { position:relative; }
        .field-control input, .field-control textarea { width:100%; min-height:48px; padding:12px 13px; border:1px solid #d9d9d9; border-radius:10px; outline:none; background:#fff; color:#111; font:inherit; font-size:.94rem; transition:border-color .16s,box-shadow .16s; }
        .field-control textarea { min-height:88px; resize:vertical; }
        .field-control input:focus, .field-control textarea:focus { border-color:#777; box-shadow:0 0 0 3px rgba(0,0,0,.07); }
        .field-control input::placeholder, .field-control textarea::placeholder { color:#9a9a9a; }
        .field-control input.password-input { padding-right:48px; }
        .password-toggle { position:absolute; top:50%; right:10px; width:32px; height:32px; transform:translateY(-50%); display:grid; place-items:center; border:0; border-radius:8px; background:transparent; color:#707070; cursor:pointer; }
        .password-toggle:hover { background:#f1f1f1; color:#111; }
        .field-help { margin:6px 0 0; color:#777; font-size:.76rem; line-height:1.45; }
        .password-match { min-height:17px; margin:5px 0 0; color:#666; font-size:.76rem; }
        .privacy-note { display:flex; align-items:flex-start; gap:9px; margin:0; padding:12px 13px; border-radius:10px; background:#f7f7f7; color:#666; font-size:.78rem; line-height:1.5; }
        .privacy-note i { margin-top:2px; color:#333; }
        .register-submit { min-height:49px; display:flex; align-items:center; justify-content:center; gap:10px; border:1px solid #111; border-radius:10px; background:#111; color:#fff; font:inherit; font-size:.94rem; font-weight:700; cursor:pointer; transition:background .16s,transform .16s; }
        .register-submit:hover { transform:translateY(-1px); background:#333; }
        .register-submit:focus-visible, .password-toggle:focus-visible { outline:3px solid #8c8c8c; outline-offset:3px; }
        .register-login { margin:20px 0 0; color:#666; text-align:center; font-size:.88rem; }
        .register-login a { color:#111 !important; font-weight:700; text-decoration:underline; text-underline-offset:3px; }
        .register-login a:hover { color:#555 !important; }
        @media (max-width:760px) { body.register-page { padding:18px 12px; } .register-shell { max-width:580px; grid-template-columns:1fr; } .register-intro { min-height:0; padding:25px 27px 23px; } .intro-copy { margin:31px 0 20px; } .intro-copy h1 { max-width:430px; font-size:2rem; } .intro-benefits { grid-template-columns:1fr 1fr; gap:9px 12px; font-size:.82rem; } .register-intro::after { right:-210px; bottom:-210px; } .register-form-panel { padding:30px 27px; } }
        @media (max-width:480px) { .form-row { grid-template-columns:1fr; gap:16px; } .register-intro { padding:22px 20px; } .register-form-panel { padding:26px 20px; } .intro-benefits { grid-template-columns:1fr; } .form-heading h2 { font-size:1.55rem; } }
        @media (prefers-reduced-motion:reduce) { *,*::before,*::after { scroll-behavior:auto !important; transition:none !important; } }
    </style>
</head>
<body class="register-page">
    <main class="register-shell">
        <aside class="register-intro" aria-label="Keuntungan akun pelanggan">
            <a class="register-brand" href="/shop"><span class="register-brand-mark"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i></span>GadgetHub</a>
            <div class="intro-copy">
                <p class="intro-eyebrow">Akun pelanggan</p>
                <h1>Belanja gadget jadi lebih mudah.</h1>
                <p>Buat akun untuk menyimpan data pengiriman, melanjutkan checkout lebih cepat, dan memantau pesananmu.</p>
            </div>
            <ul class="intro-benefits">
                <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>Checkout lebih praktis</li>
                <li><i class="fa-solid fa-box" aria-hidden="true"></i>Pantau status pesanan</li>
                <li><i class="fa-solid fa-user-shield" aria-hidden="true"></i>Profil tersimpan di akunmu</li>
            </ul>
        </aside>

        <section class="register-form-panel" aria-labelledby="register-title">
            <header class="form-heading">
                <p class="eyebrow">Mulai dari sini</p>
                <h2 id="register-title">Buat akun baru</h2>
                <p>Isi data berikut untuk mendaftar sebagai pelanggan GadgetHub.</p>
            </header>

            <?php if ($register_error !== ''): ?>
                <div class="register-alert" role="alert"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i><span><?= e($register_error); ?></span></div>
            <?php endif; ?>

            <form class="register-form" method="post" action="/register" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?= e(user_csrf_token()); ?>">

                <div class="field">
                    <label for="nama">Nama lengkap <span aria-hidden="true">*</span></label>
                    <div class="field-control"><input id="nama" name="nama" type="text" value="<?= e($register_values['nama']); ?>" placeholder="Contoh: Nadia Putri" autocomplete="name" minlength="2" maxlength="120" required></div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label for="username">Username <span aria-hidden="true">*</span></label>
                        <div class="field-control"><input id="username" name="username" type="text" value="<?= e($register_values['username']); ?>" placeholder="Contoh: nadia.putri" autocomplete="username" minlength="3" maxlength="30" pattern="[A-Za-z0-9._-]{3,30}" autocapitalize="none" spellcheck="false" required></div>
                        <p class="field-help">3–30 karakter: huruf, angka, titik, _ atau -.</p>
                    </div>
                    <div class="field">
                        <label for="no_telepon">Nomor telepon <span aria-hidden="true">*</span></label>
                        <div class="field-control"><input id="no_telepon" name="no_telepon" type="tel" value="<?= e($register_values['no_telepon']); ?>" placeholder="Contoh: 0812 3456 7890" autocomplete="tel" inputmode="tel" maxlength="30" required></div>
                        <p class="field-help">Dipakai untuk informasi pengiriman pesanan.</p>
                    </div>
                </div>

                <div class="field">
                    <label for="alamat">Alamat pengiriman <span class="optional">(opsional, bisa dilengkapi saat checkout)</span></label>
                    <div class="field-control"><textarea id="alamat" name="alamat" placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan, kota, dan kode pos" autocomplete="street-address" maxlength="1000"><?= e($register_values['alamat']); ?></textarea></div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label for="password">Kata sandi <span aria-hidden="true">*</span></label>
                        <div class="field-control">
                            <input class="password-input" id="password" name="password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" minlength="8" required>
                            <button class="password-toggle" type="button" data-toggle-password="password" aria-label="Tampilkan kata sandi" aria-pressed="false"><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
                        </div>
                        <p class="field-help">Gunakan minimal 8 karakter dan kata sandi yang tidak mudah ditebak.</p>
                    </div>
                    <div class="field">
                        <label for="confirm_password">Ulangi kata sandi <span aria-hidden="true">*</span></label>
                        <div class="field-control">
                            <input class="password-input" id="confirm_password" name="confirm_password" type="password" placeholder="Ketik ulang kata sandi" autocomplete="new-password" minlength="8" required>
                            <button class="password-toggle" type="button" data-toggle-password="confirm_password" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false"><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
                        </div>
                        <p class="password-match" id="password-match" aria-live="polite"></p>
                    </div>
                </div>

                <p class="privacy-note"><i class="fa-solid fa-lock" aria-hidden="true"></i><span>Data kontak digunakan untuk akun dan pengiriman pesanan. Kata sandi disimpan dalam bentuk hash, bukan teks biasa.</span></p>

                <button class="register-submit" type="submit">Buat akun pelanggan <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
            </form>

            <p class="register-login">Sudah punya akun? <a href="/login">Masuk di sini</a></p>
        </section>
    </main>
    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            button.addEventListener('click', function () {
                var input = document.getElementById(button.dataset.togglePassword);
                var icon = button.querySelector('i');
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                button.setAttribute('aria-pressed', show ? 'true' : 'false');
                button.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
                icon.classList.toggle('fa-eye', !show);
                icon.classList.toggle('fa-eye-slash', show);
            });
        });
        var password = document.getElementById('password');
        var confirmation = document.getElementById('confirm_password');
        var matchMessage = document.getElementById('password-match');
        function checkPasswordMatch() {
            if (!confirmation.value) {
                confirmation.setCustomValidity('');
                matchMessage.textContent = '';
                return;
            }
            var matches = password.value === confirmation.value;
            confirmation.setCustomValidity(matches ? '' : 'Konfirmasi kata sandi belum cocok.');
            matchMessage.textContent = matches ? 'Kata sandi sudah cocok.' : 'Kata sandi belum cocok.';
            matchMessage.style.color = matches ? '#267246' : '#7a3030';
        }
        password.addEventListener('input', checkPasswordMatch);
        confirmation.addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
