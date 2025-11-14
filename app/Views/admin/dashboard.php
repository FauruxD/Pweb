<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Movix</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <h1 class="admin-title">Admin Dashboard</h1>
            <button class="btn-back" onclick="window.location.href='<?= base_url('home') ?>'">
                Kembali Ke Beranda
            </button>
        </header>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                        <line x1="7" y1="2" x2="7" y2="22"></line>
                        <line x1="17" y1="2" x2="17" y2="22"></line>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <line x1="2" y1="7" x2="7" y2="7"></line>
                        <line x1="2" y1="17" x2="7" y2="17"></line>
                        <line x1="17" y1="17" x2="22" y2="17"></line>
                        <line x1="17" y1="7" x2="22" y2="7"></line>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Total Film</h3>
                    <p class="stat-number"><?= $total_films ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 class="stat-title">Total User</h3>
                    <p class="stat-number"><?= $total_users ?></p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="film">Kelola Film</button>
            <button class="tab-btn" data-tab="user">Kelola User</button>
        </div>

        <!-- Film Management Section -->
        <div class="tab-content active" id="film-content">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Daftar Film</h2>
                    <p class="section-subtitle">Kelola Daftar Film</p>
                </div>
                <button class="btn-add" onclick="showAddFilmModal()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Film
                </button>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Genre</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="film-table-body">
                        <tr>
                            <td colspan="5" class="loading">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Management Section -->
        <div class="tab-content" id="user-content">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Daftar User</h2>
                    <p class="section-subtitle">Kelola Pengguna</p>
                </div>
                <button class="btn-add" onclick="window.location.href='<?= base_url('auth/register') ?>'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Film
                </button>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        <tr>
                            <td colspan="5" class="loading">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add Film -->
    <div id="addFilmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Film Baru</h2>
                <button class="modal-close" onclick="closeAddFilmModal()">&times;</button>
            </div>
            <form id="addFilmForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="film_title">Judul Film</label>
                    <input type="text" id="film_title" name="title" placeholder="Masukkan Judul Film" required>
                </div>

                <div class="form-group">
                    <label for="film_genre">Genre</label>
                    <input type="text" id="film_genre" name="genre" placeholder="Masukkan Genre Film" required>
                </div>

                <div class="form-group">
                    <label for="film_year">Tahun Rilis</label>
                    <input type="number" id="film_year" name="year" placeholder="2025" required>
                </div>

                <div class="form-group">
                    <label for="film_poster">Poster Film</label>
                    <input type="file" id="film_poster" name="poster" accept="image/*" required>
                    <small>Max 2MB. Format: JPG, PNG, WEBP</small>
                </div>

                <div class="form-group">
                    <label for="film_rating">Rating (Opsional)</label>
                    <input type="number" id="film_rating" name="rating" placeholder="8.5" step="0.1" min="0" max="10">
                </div>

                <div class="form-group">
                    <label for="film_description">Deskripsi (Opsional)</label>
                    <textarea id="film_description" name="description" placeholder="Masukkan deskripsi film..." rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="film_video">Video</label>
                    <input type="file" id="film_video" name="video" accept="video/*" required>
                    <small>Max 100MB. Format: MP4, MKV, AVI</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddFilmModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>