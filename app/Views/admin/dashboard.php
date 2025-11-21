<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Movix</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <a href="<?= base_url('auth/logout') ?>" class="logout-btn">Kembali Ke Beranda</a>
    </div>

    <div class="main-content">
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Film</h3>
                <div class="number"><?= $total_films ?></div>
                <div class="icon">🎬</div>
            </div>

            <div class="stat-card">
                <h3>Total User</h3>
                <div class="number"><?= $total_users ?></div>
                <div class="icon">👥</div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="window.location.href='<?= base_url('admin/kelolafilm') ?>'">Kelola Film</button>
            <button class="tab-btn" onclick="window.location.href='<?= base_url('admin/kelolauser') ?>'">Kelola User</button>
        </div>

        <div class="welcome-message">
            <h2>Selamat Datang di Admin Dashboard!</h2>
            <p>Kelola film dan pengguna Anda dengan mudah</p>
            <div class="quick-actions">
                <a href="<?= base_url('admin/kelolafilm') ?>" class="action-btn">Kelola Film</a>
                <a href="<?= base_url('admin/kelolauser') ?>" class="action-btn secondary">Kelola User</a>
            </div>
        </div>
    </div>
</body>
</html>