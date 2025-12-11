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
        .reco-card { width:150px; flex:0 0 auto; cursor:pointer; transition: transform 0.2s; }
        .reco-card:hover { transform: scale(1.05); }
        .reco-card img { width:100%; border-radius:8px; object-fit: cover; height: 225px; }
        .reco-title { color:#fff; font-size:13px; margin-top:6px; }
        .reco-meta { color:#8899aa; font-size:11px; margin-top:2px; }
    </style>
</head>
<body>

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

    <div class="film-grid" id="fav-grid">
        <?php if(empty($movies)): ?>
            <div class="empty-favs">
                <p>Belum ada film favorit.</p>
                <p>Tambahkan film dari halaman detail.</p>
                <a href="<?= base_url('dashboard') ?>" style="display:inline-block; margin-top:20px; padding:12px 24px; background:#f39c12; color:#000; text-decoration:none; border-radius:8px; font-weight:600;">
                    Jelajahi Film
                </a>
            </div>
        <?php else: ?>
            <?php foreach($movies as $m):
                $detailUrl = base_url('detail/' . $m['movie_id']);
            ?>
                <div class="film-card">
                    <img
                        class="film-poster"
                        src="<?= esc($m['poster_path']) ?>"
                        style="cursor:pointer;"
                        onclick="window.location.href='<?= $detailUrl ?>'"
                        alt="<?= esc($m['title']) ?>"
                    >
                    <div class="film-info">
                        <div class="film-title"><?= esc($m['title']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Rekomendasi Random dari Database Lokal -->
    <?php if(!empty($recommendations)): ?>
    <div class="reco-section">
        <h2 style="color:#fff; margin-bottom:12px;">Film Lainnya</h2>
        <p style="color:#8899aa; margin-bottom:20px;">Jelajahi koleksi film lainnya</p>
        <div class="reco-list">
            <?php foreach($recommendations as $r): 
                $rposter = base_url('uploads/posters/' . ($r['poster_path'] ?? 'no-poster.jpg'));
                $detailUrl = base_url('detail/' . $r['id']);
            ?>
                <div class="reco-card" onclick="window.location.href='<?= $detailUrl ?>'">
                    <img 
                        src="<?= $rposter ?>" 
                        alt="<?= esc($r['title']) ?>"
                        onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>'"
                    >
                    <div class="reco-title">
                        <?= esc($r['title']) ?>
                    </div>
                    <div class="reco-meta">
                        <?= esc($r['genre'] ?? '') ?> • <?= esc($r['year'] ?? '') ?>
                        <?php if(isset($r['rating']) && $r['rating'] > 0): ?>
                            • ⭐ <?= number_format($r['rating'], 1) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <?php endif; ?>

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