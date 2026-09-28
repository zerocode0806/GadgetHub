<div class="container-fluid px-4">
    <?php if ($admin_flash): ?><div class="alert alert-danger"><?= htmlspecialchars($admin_flash['message'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color:#1d3557"><i class="fas fa-user-edit me-2"></i>Edit <?= $customer_type === 'account' ? 'Akun Pelanggan' : 'Data Pelanggan'; ?></h1>
        <a href="/admin/customers" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
    </div>
    <div class="card shadow-sm border-0"><div class="card-body">
        <form method="post" action="/admin/customers/edit?id=<?= (int) $data['customer_id']; ?>&type=<?= htmlspecialchars($customer_type, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="admin_csrf" value="<?= htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="type" value="<?= htmlspecialchars($customer_type, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label" for="nama">Nama lengkap</label><input class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($data['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
                <?php if ($customer_type === 'account'): ?>
                    <div class="col-md-6"><label class="form-label" for="username">Username</label><input class="form-control" id="username" name="username" value="<?= htmlspecialchars($data['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
                <?php endif; ?>
                <div class="col-md-6"><label class="form-label" for="no_telepon">Nomor telepon</label><input class="form-control" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($data['no_telepon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" inputmode="tel"></div>
                <div class="col-md-6"><label class="form-label" for="alamat">Alamat profil</label><input class="form-control" id="alamat" name="alamat" value="<?= htmlspecialchars($data['alamat'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                <?php if ($customer_type === 'account'): ?>
                    <div class="col-md-6"><label class="form-label" for="password">Password baru</label><input class="form-control" type="password" id="password" name="password" minlength="8" autocomplete="new-password"><div class="form-text">Kosongkan jika password tidak diubah.</div></div>
                    <div class="col-md-6"><label class="form-label" for="confirm_password">Konfirmasi password baru</label><input class="form-control" type="password" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password"></div>
                <?php endif; ?>
                <div class="col-12"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-2"></i>Simpan Perubahan</button></div>
            </div>
        </form>
        <?php if ($customer_type === 'account'): ?><p class="small text-muted mt-3 mb-0">Perubahan profil tidak mengubah alamat dan nama penerima yang sudah tersimpan pada pesanan sebelumnya.</p><?php endif; ?>
    </div></div>
</div>
