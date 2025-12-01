<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">

    <style>
        .detail-container {
            display: flex;
            gap: 40px;
            padding: 40px;
            color: #fff;
        }
        .detail-poster {
            width: 320px;
            border-radius: 15px;
            box-shadow: 0 0 20px #000;
        }
        .detail-info h1 {
            margin: 0;
            font-size: 36px;
        }
        .detail-meta {
            opacity: .8;
            margin: 10px 0;
        }
        .btn-watch {
            background: #f7b500;
            padding: 12px 25px;
            border-radius: 10px;
            color: #000;
            display: inline-block;
            margin-top: 20px;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-watch:hover {
            background: #ffd060;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="<?= base_url('dashboard') ?>" class="logo">MOVIX</a>
        <ul class="nav-links">
            <li><a href="<?= base_url('dashboard') ?>">Film</a></li>
            <li><a href="<?= base_url('genre') ?>">Genre</a></li>
            <li><a href="<?= base_url('favorite') ?>">Favorit</a></li>
        </ul>
    </div>

    <div class="navbar-right">
        <div class="user-profile">
            <div class="user-avatar"><?= strtoupper(substr($user['email'], 0, 1)) ?></div>
        </div>
    </div>
</nav>

<!-- DETAIL CONTENT -->
<div class="detail-container">

    <?php
    // ✅ FIX: Build poster URL yang benar
    // Jika $poster sudah disiapkan dari controller, gunakan langsung
    // Jika belum, build dari film data
    if (!isset($poster) || empty($poster)) {
        if (isset($film['is_tmdb']) && $film['is_tmdb']) {
            // Film dari TMDB
            $imageUrl = 'https://image.tmdb.org/t/p/w500';
            $poster = $imageUrl . ($film['poster_path'] ?? '');
        } else {
            // Film lokal - poster di public/uploads/posters/
            $poster = base_url('uploads/posters/' . ($film['poster_path'] ?? 'placeholder.jpg'));
        }
    }
    ?>

    <img src="<?= esc($poster) ?>" 
         class="detail-poster"
         onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>'">

    <div class="detail-info">
        <h1><?= esc($film['title']) ?></h1>

        <div class="detail-meta">
            <?= esc($film['year']) ?> • <?= esc($film['genre']) ?> • ⭐ <?= number_format($film['rating'] ?? 0, 1) ?>
        </div>

        <p style="max-width:600px;">
            <?= esc($film['description'] ?? 'Tidak ada sinopsis tersedia.') ?>
        </p>

        <a href="<?= base_url('watch/' . $film['id']) ?>" class="btn-watch">▶ Watch Now</a>
    </div>
</div>

</body>
</html>