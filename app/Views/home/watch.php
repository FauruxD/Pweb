<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">

    <style>
        body {
            margin: 0;
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        /* NAVBAR */
        .navbar {
            width: 100%;
            padding: 15px 40px;
            background: rgba(10,14,20,0.9);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .navbar .logo {
            font-size: 22px;
            font-weight: bold;
            color: #f39c12;
            text-decoration: none;
        }
        .watch-container {
            max-width: 100vw;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .video-box {
            width: 100%;
            height: 100%;
            max-height: 107vh;
            background: #111;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
            position: relative;
        }
        iframe {
            width: 100vw;
            height: 45vw;
            max-height: 56.25vw; 
            border: none;
            display: block;
            background: #000;
        }
        .film-infoo {
            max-width: 100vw;
            padding: 32px 42px;
            margin: 0;
        }
        .film-infoo h1 {
            margin: 0;
            font-size: 32px;
        }
        .meta {
            color: #aaa;
            margin: 12px 0 18px 0;
        }
        @media (max-width: 980px) {
            .film-infoo { padding: 16px 14px; }
            .video-box { height: 56vw; }
            iframe    { min-height: 56vw; }
        }
        @media (max-width: 600px) {
            .film-infoo { font-size: 14px; padding: 10px 2px; }
            .film-infoo h1 { font-size: 20px; }
            .video-box { height: 60vw; }
            iframe    { min-height: 60vw; }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="<?= base_url('/dashboard') ?>" class="logo">MOVIX</a>
        <ul class="nav-links">
            <li><a href="<?= base_url('/dashboard') ?>">Film</a></li>
            <li><a href="<?= base_url('genre') ?>">Genre</a></li>
            <li><a href="<?= base_url('favorite') ?>" class="active">Favorit</a></li>
        </ul>
    </div>
    <div class="navbar-right">
        <div class="user-profile" onclick="toggleUserMenu()">
            <div class="user-avatar">
                <?= strtoupper(substr(session('email'), 0, 1)) ?>
            </div>
            <div class="notification-badge"></div>
        </div>
        <div id="userMenu" class="user-menu">
            <a href="<?= base_url('/') ?>">Ke Landing Page</a>
            <a href="<?= base_url('auth/logout') ?>" class="logout">Logout</a>
        </div>
    </div>
</nav>  

<!-- WATCH PAGE -->
<div class="watch-container">

    <!-- Video Player -->
    <div class="video-box">
        <iframe src="<?= $embed ?>" allowfullscreen></iframe>
    </div>

    <!-- Film Info -->
    <div class="film-infoo">
        <h1><?= $film['title'] ?></h1>
        <div class="meta">
            <?= $film['year'] ?? '' ?> • 
            <?= $film['genre'] ?? '' ?> • 
            ⭐ <?= number_format($film['rating'] ?? 0, 1) ?>
        </div>
        <p><?= $film['description'] ?? '' ?></p>
    </div>

</div>

<script>
function toggleUserMenu() {
    document.getElementById("userMenu").classList.toggle("show");
}
document.addEventListener("click", function(e) {
    const menu = document.getElementById("userMenu");
    const profile = document.querySelector(".user-profile");

    if (!profile.contains(e.target)) {
        menu.classList.remove("show");
    }
});
</script>

</body>
</html>