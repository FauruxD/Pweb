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

        /* CONTENT */
        .watch-container {
            padding: 40px;
            max-width: 1300px;
            margin: auto;
        }

        .video-box {
            width: 100%;
            aspect-ratio: 16/9;
            background: #111;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 0 20px #000;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .film-infoo {
            margin-top: 25px;
        }
        .film-infoo h1 {
            margin: 0;
            font-size: 32px;
        }
        .meta {
            color: #aaa;
            margin: 10px 0;
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
</nav>

<!-- WATCH PAGE CONTENT -->
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

</body>
</html>
