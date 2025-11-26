<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0e14;
            color: #fff;
            min-height: 100vh;
        }

        .header {
            background: rgba(20, 25, 30, 0.95);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header h1 {
            color: #f39c12;
            font-size: 24px;
        }

        .logout-btn {
            padding: 10px 24px;
            background: #f39c12;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #e67e22;
        }

        .main-content {
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(30, 40, 50, 0.8);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .stat-card h3 {
            color: #8899aa;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .stat-card .number {
            font-size: 42px;
            font-weight: 700;
        }

        .stat-card .icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 48px;
            opacity: 0.15;
        }

        .tabs {
            display: flex;
            gap: 16px;
            margin-bottom: 30px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .tab-btn {
            padding: 12px 28px;
            background: transparent;
            color: #8899aa;
            border: none;
            border-bottom: 3px solid transparent;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .tab-btn.active {
            color: #fff;
            border-bottom-color: #f39c12;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .section-header h2 {
            font-size: 28px;
        }

        .section-header p {
            color: #8899aa;
            font-size: 14px;
        }

        .add-btn {
            padding: 12px 28px;
            background: #f39c12;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .add-btn:hover {
            background: #e67e22;
            transform: translateY(-2px);
        }

        .table-container {
            background: rgba(30, 40, 50, 0.8);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: rgba(40, 50, 60, 0.9);
        }

        th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #fff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: #e0e0e0;
        }

        tbody tr {
            transition: background 0.3s;
        }

        tbody tr:hover {
            background: rgba(40, 50, 60, 0.5);
        }

        .delete-btn {
            background: transparent;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
        }

        .delete-btn:hover {
            color: #c0392b;
            transform: scale(1.2);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid rgba(46, 204, 113, 0.3);
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        .role-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .role-admin {
            background: rgba(243, 156, 18, 0.2);
            color: #f39c12;
        }

        .role-user {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <a href="<?= base_url('dashboard') ?>" class="logout-btn">Kembali Ke Beranda</a>
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
            <button class="tab-btn" onclick="window.location.href='<?= base_url('admin/kelolafilm') ?>'">Kelola Film</button>
            <button class="tab-btn active">Kelola User</button>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="section-header">
            <div>
                <h2>Daftar User</h2>
                <p>Kelola Pengguna</p>
            </div>
            <button class="add-btn" onclick="window.location.href='<?= base_url('auth/register') ?>'">✚ Tambah Film</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <span class="role-badge <?= $user['role'] === 'Admin' ? 'role-admin' : 'role-user' ?>">
                                        <?= $user['role'] ?>
                                    </span>
                                </td>
                                <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <button class="delete-btn" onclick="confirmDelete(<?= $user['id'] ?>, '<?= esc($user['email']) ?>')">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #8899aa;">
                                Tidak ada user.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete(id, email) {
            if (confirm(`Apakah Anda yakin ingin menghapus user "${email}"?`)) {
                window.location.href = `<?= base_url('admin/delete-user/') ?>${id}`;
            }
        }
    </script>
</body>
</html>