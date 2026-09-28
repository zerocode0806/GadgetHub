<div class="container-fluid px-4">
    <?php if ($admin_flash): ?><div class="alert alert-<?= $admin_flash['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert"><?= htmlspecialchars($admin_flash['message'], ENT_QUOTES, 'UTF-8'); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div><?php endif; ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div><h1 class="mt-4" style="color:#1d3557"><i class="fas fa-user-shield me-2"></i>Akun Staf</h1><p class="text-muted mb-0">Kelola akun admin dan petugas. Akun pembeli storefront dikelola terpisah pada menu Pelanggan.</p></div>
        <a href="/admin/users/create" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Tambah Akun Staf</a>
    </div>
    <div class="card border-0 shadow-sm"><div class="card-body"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Nama</th><th>Username</th><th>Peran</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            <?php if (!$users): ?><tr><td colspan="4" class="text-center text-muted py-4">Belum ada akun staf.</td></tr>
            <?php else: foreach ($users as $data): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($data['nama'] ?: 'Tanpa nama', ENT_QUOTES, 'UTF-8'); ?></strong><?php if ((int) $data['id_user'] === (int) $_SESSION['id_user']): ?><span class="badge bg-light text-dark ms-2">Akun Anda</span><?php endif; ?></td>
                    <td><?= htmlspecialchars($data['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><span class="badge <?= $data['level'] === 'admin' ? 'bg-primary' : 'bg-info text-dark'; ?>"><?= $data['level'] === 'admin' ? 'Admin' : 'Petugas'; ?></span></td>
                    <td><div class="d-flex justify-content-end gap-2">
                        <a href="/admin/users/edit?id=<?= (int) $data['id_user']; ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                        <form method="post" action="/admin/users/delete" onsubmit="return confirm('Hapus akun staf ini? Akun dengan riwayat pesanan tidak dapat dihapus.');">
                            <input type="hidden" name="admin_csrf" value="<?= htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="id" value="<?= (int) $data['id_user']; ?>">
                            <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus" <?= (int) $data['id_user'] === (int) $_SESSION['id_user'] ? 'disabled' : ''; ?>><i class="fas fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div></div></div>
</div>
