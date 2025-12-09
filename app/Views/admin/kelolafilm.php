<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Film - Admin Dashboard</title>
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

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: rgba(30, 40, 50, 0.98);
            padding: 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin: 20px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .modal-header h3 {
            font-size: 24px;
        }

        .close-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 28px;
            cursor: pointer;
            line-height: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(70, 85, 100, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #f39c12;
        }

        .file-input {
            background: rgba(70, 85, 100, 0.3) !important;
            cursor: pointer;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #f39c12;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #e67e22;
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

        .help-text {
            color: #8899aa;
            font-size: 12px;
            display: block;
            margin-top: 4px;
        }

        .char-counter {
            color: #8899aa;
            font-size: 12px;
            text-align: right;
            margin-top: 4px;
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
            <button class="tab-btn active">Kelola Film</button>
            <button class="tab-btn" onclick="window.location.href='<?= base_url('admin/kelolauser') ?>'">Kelola User</button>
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
                <h2>Daftar Film</h2>
                <p>Kelola Daftar Film</p>
            </div>
            <button class="add-btn" onclick="openModal()">✚ Tambah Film</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Genre</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($films)): ?>
                        <?php foreach ($films as $film): ?>
                            <tr>
                                <td><?= $film['id'] ?></td>
                                <td><?= esc($film['title']) ?></td>
                                <td><?= esc($film['genre']) ?></td>
                                <td><?= $film['year'] ?></td>
                                <td>
                                    <button class="delete-btn" onclick="confirmDelete(<?= $film['id'] ?>, '<?= esc($film['title']) ?>')">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #8899aa;">
                                Belum ada film. Tambahkan film pertama Anda!
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Film -->
    <div class="modal" id="addFilmModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Film Baru</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>

            <form action="<?= base_url('admin/tambah-film') ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Judul Film</label>
                    <input type="text" name="title" placeholder="Masukkan Judul Film" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Film</label>
                    <textarea name="description" id="description" placeholder="Masukkan deskripsi singkat tentang film ini..." required maxlength="1000"></textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span>/1000 karakter
                    </div>
                </div>

                <div class="form-group">
                    <label>Genre</label>
                    <input type="text" name="genre" placeholder="Action, Drama, Horror, dll" required>
                </div>

                <div class="form-group">
                    <label>Tahun Rilis</label>
                    <input type="number" name="year" placeholder="2025" min="1900" max="2100" required>
                </div>

                <div class="form-group">
                    <label>Rating (0.0 - 10.0)</label>
                    <input type="number" name="rating" step="0.1" min="0" max="10" placeholder="8.5" value="0.0">
                </div>

                <div class="form-group">
                    <label>Poster Film</label>
                    <input type="file" name="poster" class="file-input" accept="image/*" required>
                    <small class="help-text">Format: JPG, PNG, WEBP (Max 5MB)</small>
                </div>

                <div class="form-group">
                    <label>Video Path</label>
                    <input type="text" name="video_path" placeholder="dragon-ball" required>
                    <small class="help-text">
                        Masukkan identifier video dari Archive.org (contoh: dragon-ball)
                    </small>
                </div>

                <button type="submit" class="submit-btn">SIMPAN FILM</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('addFilmModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('addFilmModal').classList.remove('active');
        }

        function confirmDelete(id, title) {
            if (confirm(`Apakah Anda yakin ingin menghapus film "${title}"?`)) {
                window.location.href = `<?= base_url('admin/delete-film/') ?>${id}`;
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addFilmModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>