<?php
$tmdb_image_base = 'https://image.tmdb.org/t/p/w500';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Film Favorit - Movix</title>

    <!-- CSS Halaman Favorite -->
    <link rel="stylesheet" href="<?= base_url('assets/css/favorite.css') ?>">

    <style>
        .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        .page-title { font-size:30px; font-weight:700; margin-bottom:4px; }
        .page-sub { color:#8899aa; font-size:14px; }
        .badge-small { background:#f39c12; padding:6px 12px; border-radius:6px; font-weight:700; color:#000; }
        .empty-favs { color:#9aa3b0; padding:40px 10px; text-align:center; }
        .reco-section { margin-top:50px; }
        .reco-list { display:flex; gap:16px; overflow-x:auto; padding-bottom:12px; }
        .reco-card { width:150px; flex:0 0 auto; }
        .reco-card img { width:100%; border-radius:8px; }
    </style>
</head>
<body>

<!-- ========================================================= -->
<!--                           NAVBAR                          -->
<!-- ========================================================= -->

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
        <div class="search-box">
            <span class="search-icon"></span>
            <input type="text" placeholder="Cari film..." id="searchInput">
        </div>

        <!-- ✔ FIX: gunakan session email -->
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

<!-- ========================================================= -->
<!--                        MAIN CONTENT                        -->
<!-- ========================================================= -->

<div class="main-content">

    <div class="page-header">
        <div>
            <div class="page-title">Film Favorit</div>
            <div class="page-sub">Koleksi film yang telah Anda simpan</div>
        </div>

        <div>
            <span class="badge-small"><?= count($movies ?? []) ?> Film</span>
        </div>
    </div>

    <!-- FILM GRID -->
    <div class="film-grid" id="fav-grid">

        <?php if(empty($movies)): ?>
            <div class="empty-favs">
                <p>Belum ada film favorit.</p>
                <p>Tambahkan film dari halaman detail.</p>
            </div>
        <?php else: ?>

            <?php foreach($movies as $m): 
                $poster = isset($m['poster']) 
                    ? (strpos($m['poster'], '/') === 0 ? $tmdb_image_base.$m['poster'] : $m['poster']) 
                    : base_url('assets/images/no-poster.png');
            ?>
                <div class="film-card">

                    <img class="film-poster" src="<?= $poster ?>">

                    <button class="film-favorite">♥</button>

                    <div class="film-info">
                        <div class="film-title"><?= esc($m['title']) ?></div>
                        <div class="film-meta">
                            <span style="color:#9aa3b0;">Genre • Tahun</span>

                            <a class="btn-primary" href="<?= base_url('movie/'.$m['id']) ?>" style="text-decoration:none;">Detail</a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <!-- REKOMENDASI -->
    <div class="reco-section">
        <h3 style="color:#fff; margin-bottom:12px;">Rekomendasi Untukmu</h3>

        <div class="reco-list">
            <?php if(!empty($recommendations)): ?>
                <?php foreach($recommendations as $r): ?>
                    <?php $rposter = $tmdb_image_base . $r['poster_path']; ?>

                    <div class="reco-card">
                        <img src="<?= $rposter ?>">
                        <div style="color:#fff; font-size:13px; margin-top:6px;">
                            <?= esc($r['title'] ?? $r['name']) ?>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <div class="reco-card">
                    <img src="<?= base_url('assets/images/sample-1.jpg') ?>">
                    <div style="color:#fff; font-size:13px; margin-top:6px;">Blade Runner</div>
                </div>
            <?php endif; ?>
        </div>
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
