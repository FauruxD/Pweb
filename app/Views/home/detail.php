<?php
$other_films = [
    [
        'id' => 2,
        'title' => 'John Wick 4',
        'genre' => 'Action',
        'rating' => 8.5,
        'poster_path' => 'johnwick4.jpg',
    ],
    [
        'id' => 3,
        'title' => 'The Conjuring',
        'genre' => 'Horror',
        'rating' => 7.8,
        'poster_path' => 'conjuring.jpg',
    ],
    [
        'id' => 4,
        'title' => 'The Hangover',
        'genre' => 'Comedy',
        'rating' => 8.2,
        'poster_path' => 'hangover.jpg',
    ],
    [
        'id' => 5,
        'title' => 'Toy Story 4',
        'genre' => 'Animation',
        'rating' => 9.1,
        'poster_path' => 'toy_story_4.jpg',
    ],
    [
        'id' => 6,
        'title' => 'Forrest Gump',
        'genre' => 'Drama',
        'rating' => 8.6,
        'poster_path' => 'forrestgump.jpg',
    ],
    [
        'id' => 7,
        'title' => 'Inception',
        'genre' => 'Thriller',
        'rating' => 9.1,
        'poster_path' => 'inception.jpg',
    ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/detail.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
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
        <div class="search-box">
            <span class="search-icon"></span>
            <input type="text" placeholder="Cari film..." id="searchInput">
        </div>
        <div class="user-profile">
            <div class="user-avatar"><?= strtoupper(substr($user['email'], 0, 1)) ?></div>
        </div>
    </div>
</nav>

<!-- FILM DETAIL SECTION -->
<div class="detail-hero">
    <?php
        if (isset($film['is_tmdb']) && $film['is_tmdb']) {
            $poster_url = 'https://image.tmdb.org/t/p/w500' . ($film['poster_path'] ?? '');
        } else {
            $poster_url = base_url('uploads/posters/' . ($film['poster_path'] ?? 'placeholder.jpg'));
        }
    ?>
    <div class="detail-hero-poster">
        <img src="<?= esc($poster_url) ?>" alt="<?= esc($film['title']) ?>" onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>'">
    </div>
    <div class="detail-hero-info">
        <div class="detail-hero-rating">
            <span class="badge-rating"><?= number_format($film['rating'] ?? 0, 1) ?></span>
            <span class="badge-genre"><?= esc($film['genre']) ?></span>
        </div>
        <h1 class="detail-hero-title"><?= esc($film['title']) ?></h1>
        <div class="detail-hero-desc"><?= esc($film['description'] ?? 'Tidak ada sinopsis tersedia.') ?></div>
        <div class="detail-hero-actions">
            <a class="btn-watch" href="<?= base_url('watch/' . $film['id']) ?>">▶ Watch Now</a>
        </div>
    </div>
</div>

<!-- FILM LAINNYA SECTION -->
<div class="film-lainnya-section">
    <div class="film-lainnya-title">Film Lainnya</div>
    <div class="film-lainnya-grid">
        <?php foreach($other_films as $other): ?>
            <?php $other_poster = base_url('uploads/posters/' . ($other['poster_path'] ?? 'placeholder.jpg')); ?>
            <div class="film-card-list">
                <img src="<?= $other_poster ?>" alt="<?= esc($other['title']) ?>">
                <div class="card-row">
                    <span class="list-rating"><?= number_format($other['rating'] ?? 0, 1) ?></span>
                    <span class="list-fav">&#9825;</span>
                </div>
                <div class="list-title"><?= esc($other['title']) ?></div>
                <div class="list-genre"><?= esc($other['genre']) ?></div>
                <a href="<?= base_url('detail/' . $other['id']) ?>" class="list-detail-btn">Detail</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Movix</h3>
            <p>Platform streaming film terbaik dengan koleksi lengkap dari berbagai genre.</p>
            <div class="social-links">
                <a href="#" class="social-link"></a>
                <a href="#" class="social-link"></a>
                <a href="#" class="social-link"></a>
                <a href="#" class="social-link"></a>
            </div>
        </div>
        <div class="footer-section">
            <h3>Genre</h3>
            <ul class="footer-links">
                <li><a href="#">Action</a></li>
                <li><a href="#">Drama</a></li>
                <li><a href="#">Comedy</a></li>
                <li><a href="#">Romance</a></li>
                <li><a href="#">Horror</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Bantuan</h3>
            <ul class="footer-links">
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Kontak</a></li>
                <li><a href="#">Kebijakan</a></li>
                <li><a href="#">Syarat & Ketentuan</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Newsletter</h3>
            <p>Dapatkan update film terbaru!</p>
            <form class="newsletter-form" onsubmit="return false;">
                <input type="email" placeholder="Email Anda">
                <button type="submit">→</button>
            </form>
        </div>
    </div>
    <div class="footer-bottom">
        © <?= date('Y') ?> Movix. All rights reserved.
    </div>
</footer>

</body>
</html>