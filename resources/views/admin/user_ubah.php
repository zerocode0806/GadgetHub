<div class="container-fluid px-4">
    <?php if ($admin_flash): ?><div class="alert alert-danger"><?= htmlspecialchars($admin_flash['message'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4" style="color:#1d3557"><i class="fas fa-user-edit me-2"></i>Edit Akun Staf</h1>
        <a href="/admin/users" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
    </div>
    <div class="card shadow-sm border-0"><div class="card-body">
        <form method="post" action="/admin/users/edit?id=<?= (int) $data['id_user']; ?>">
            <input type="hidden" name="admin_csrf" value="<?= htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label" for="nama">Nama lengkap</label><input class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($data['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
                <div class="col-md-6"><label class="form-label" for="username">Username</label><input class="form-control" id="username" name="username" value="<?= htmlspecialchars($data['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
                <div class="col-md-6"><label class="form-label" for="level">Peran</label><select class="form-select" id="level" name="level" required><option value="admin" <?= $data['level'] === 'admin' ? 'selected' : ''; ?>>Admin</option><option value="petugas" <?= $data['level'] === 'petugas' ? 'selected' : ''; ?>>Petugas</option></select></div>
                <div class="col-md-6"></div>
                <div class="col-md-6"><label class="form-label" for="password">Password baru</label><input class="form-control" type="password" id="password" name="password" minlength="8" autocomplete="new-password"><div class="form-text">Kosongkan jika password tidak diubah.</div></div>
                <div class="col-md-6"><label class="form-label" for="confirm_password">Konfirmasi password baru</label><input class="form-control" type="password" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password"></div>
                <div class="col-12"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-2"></i>Simpan Perubahan</button></div>
            </div>
        </form>
    </div></div>
</div>
