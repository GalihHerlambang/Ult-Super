<?php $isEdit = isset($user); ?>
<div class="page-header">
    <div><h1><?= $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna' ?></h1></div>
    <a href="<?= url('admin/user') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header"><h5><i class="bi bi-person me-2"></i>Data Pengguna</h5></div>
    <div class="card-body">
        <form method="POST" action="<?= $isEdit ? url('admin/user/update/' . $user['id']) : url('admin/user/simpan') ?>">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" name="nama" class="form-control" required
                           value="<?= htmlspecialchars($user['nama'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Username <?= !$isEdit ? '<span class="req">*</span>' : '' ?></label>
                    <input type="text" name="username" class="form-control" <?= !$isEdit ? 'required' : 'readonly' ?>
                           value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Password <?= $isEdit ? '(kosongkan jika tidak diubah)' : '<span class="req">*</span>' ?></label>
                <input type="password" name="password" class="form-control" <?= !$isEdit ? 'required' : '' ?> autocomplete="new-password">
            </div>
            <div class="form-group">
                <label class="form-label">Role <span class="req">*</span></label>
                <select name="role" class="form-control" required>
                    <?php foreach (['admin' => 'Administrator', 'helpdesk' => 'Helpdesk', 'atasan' => 'Atasan', 'pengadu' => 'Pengadu'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= (($user['role'] ?? '') === $val) ? 'selected' : '' ?>>
                        <?= $lbl ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <div class="d-flex gap-2">
                    <label class="form-check">
                        <input type="radio" name="is_active" value="1" <?= (!isset($user) || $user['is_active']) ? 'checked' : '' ?>>
                        <span>Aktif</span>
                    </label>
                    <label class="form-check">
                        <input type="radio" name="is_active" value="0" <?= (isset($user) && !$user['is_active']) ? 'checked' : '' ?>>
                        <span>Nonaktif</span>
                    </label>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' ?>
                </button>
                <a href="<?= url('admin/user') ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
