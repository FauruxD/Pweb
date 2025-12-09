<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Berdasarkan Genre</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/genre_movies.css') ?>">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="<?= base_url('/dashboard') ?>" class="logo">MOVIX</a>
        <ul class="nav-links">
            <li><a href="<?= base_url('/dashboard') ?>">Film</a></li>
            <li><a href="<?= base_url('genre') ?>" class="active">Genre</a></li>
            <li><a href="<?= base_url('favorite') ?>">Favorit</a></li>
        </ul>
    </div>

    <div class="navbar-right">
        <div class="search-box">
            <span class="search-icon"></span>
            <input type="text" id="searchInput" placeholder="Cari film...">
        </div>

        <div class="user-profile" onclick="toggleUserMenu()">
            <div class="user-avatar"><?= strtoupper(substr(session('email'), 0, 1)) ?></div>
        </div>

        <div id="userMenu" class="user-menu">
            <a href="<?= base_url('/') ?>">Landing</a>
            <a href="<?= base_url('auth/logout') ?>">Logout</a>
        </div>
    </div>
</nav>

<div class="genre-header-flex">
    <a href="<?= base_url('genre') ?>" class="back-btn">Kembali ke Genre</a>
    <h2 class="genre-title">Daftar Film dalam Genre Ini</h2>
</div>




<!-- GRID FILM -->
<div class="film-grid" id="filmGrid">

    <?php foreach ($films as $film): ?>

        <div class="film-card" data-title="<?= strtolower($film['title']) ?>">
            <img src="<?= $imageUrl . $film['poster_path'] ?>"
                 class="film-poster"
                 onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>'">

            <div class="film-info">
                <h3 class="film-title"><?= $film['title'] ?></h3>
                <span class="film-year"><?= substr($film['release_date'], 0, 4) ?></span>
                <span class="film-rating"><?= number_format($film['vote_average'], 1) ?> ⭐</span>
            </div>
        </div>

    <?php endforeach; ?>

</div>

<div id="notFound" class="not-found">Tidak ada film ditemukan...</div>

<footer class="footer">
    <div class="footer-bottom">© 2025 Movix. All rights reserved.</div>
</footer>

<script>
const filmGrid  = document.getElementById("filmGrid");
const searchBox = document.getElementById("searchInput");
const notFound  = document.getElementById("notFound");

searchBox.addEventListener("keyup", function() {
    const q = this.value.toLowerCase().trim();
    let visible = 0;

    document.querySelectorAll(".film-card").forEach(card => {
        const title = card.dataset.title;
        const show = title.includes(q);
        card.style.display = show ? "block" : "none";
        if (show) visible++;
    });

    notFound.style.display = visible === 0 ? "block" : "none";
});
</script>
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
