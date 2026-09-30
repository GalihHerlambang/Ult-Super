<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($setting['deskripsi'] ?? 'Login') ?>">
    <title>Login — <?= htmlspecialchars($setting['nama_aplikasi'] ?? 'Super Aplikasi Pengaduan') ?></title>
    <?php if (!empty($setting['favicon'])): ?>
    <link rel="icon" href="<?= htmlspecialchars(url($setting['favicon'])) ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= url('assets/css/app.css') ?>">
</head>
<body>
    <?= $content ?>
<script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>
