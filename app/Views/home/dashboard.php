<?php
// dashboard.php
// Pastikan $title, $user, $films, $trending_films, $imageUrl tersedia dari controller
if (!isset($imageUrl)) $imageUrl = 'https://image.tmdb.org/t/p/w500';

// (Opsional) $userFavorites = array of movie_id yang diambil dari DB untuk menandai favorite pada load
// Contoh controller: $data['userFavorites'] = $favModel->where('user_id', $userId)->findColumn('movie_id');
// Jika tidak ada, file masih bekerja tanpa error.
$userFavorites = $userFavorites ?? [];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

    <!-- CSRF (CodeIgniter 4) -->
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <meta name="csrf-hash" content="<?= csrf_hash() ?>">

    <!-- CSS UTAMA -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <style>
        /* Tambahan kecil styling untuk tombol favorite */
        .film-favorite { background: transparent; border: 0; font-size:18px; cursor:pointer; }
        .film-favorite.favorited { transform: scale(1.05); transition: .12s ease; }
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
            <li><a href="<?= base_url('/dashboard') ?>" class="active">Film</a></li>
            <li><a href="<?= base_url('genre') ?>">Genre</a></li>
            <li><a href="<?= base_url('favorite') ?>">Favorit</a></li>
        </ul>
    </div>

    <div class="navbar-right">
        <div class="search-box">
            <span class="search-icon"></span>
            <input type="text" placeholder="Cari film..." id="searchInput">
        </div>

        <div class="user-profile" onclick="toggleUserMenu()">
            <div class="user-avatar">
                <?= isset($user['email']) ? strtoupper(substr($user['email'], 0, 1)) : 'U' ?>
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

    <!-- Hero -->
    <div class="hero-section">
        <h1>Koleksi Film</h1>
        <p>Temukan film terbaik untuk Anda</p>
    </div>

    <!-- Filter -->
    <div class="filter-container">
        <div class="filter-box">
            <div class="filter-item">
                <label>Genre</label>
                <select id="genreFilter">
                    <option value="">Semua Genre</option>
                    <option value="Action">Action</option>
                    <option value="Drama">Drama</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Horror">Horror</option>
                    <option value="Romance">Romance</option>
                    <option value="Sci-Fi">Sci-Fi</option>
                    <option value="Thriller">Thriller</option>
                </select>
            </div>

            <div class="filter-item">
                <label>Tahun</label>
                <select id="yearFilter">
                    <option value="">Semua Tahun</option>
                    <?php for ($y = (int)date('Y'); $y >= 1990; $y--): ?>
                        <option value="<?= $y ?>"><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="filter-item">
                <label>Rating</label>
                <select id="ratingFilter">
                    <option value="">Semua Rating</option>
                    <option value="9">9+ ⭐</option>
                    <option value="8">8+ ⭐</option>
                    <option value="7">7+ ⭐</option>
                    <option value="6">6+ ⭐</option>
                </select>
            </div>

            <div class="filter-item">
                <label>Urutkan</label>
                <select id="sortFilter">
                    <option value="popularitas">Popularitas</option>
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="rating">Rating Tertinggi</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Trending -->
    <?php if (!empty($trending_films)): ?>
    <div class="section-header"><h2>Trending Sekarang</h2></div>

    <div class="trending-container">
        <?php
            // ambil item utama trending
            $t = $trending_films[0];
            // fungsi kecil untuk membuat URL poster yang benar
            function buildPosterUrl($base, $path) {
                if (empty($path)) return null;
                // jika sudah URL penuh
                if (strpos($path, 'http') === 0) return $path;
                // jika path dimulai dengan slash (mis: /abc.jpg) -> gabungkan
                if ($path[0] === '/') return rtrim($base, '/') . $path;
                // jika path tidak diawali slash -> tambahkan slash
                return rtrim($base, '/') . '/' . $path;
            }
            $t_poster = buildPosterUrl($imageUrl, $t['poster_path'] ?? '');
        ?>
        <div class="trending-main">
            <img src="<?= esc($t_poster ?: base_url('assets/images/placeholder.jpg')) ?>"
                 alt="<?= esc($t['title']) ?>"
                 onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>';">

            <div class="trending-overlay">
                <span class="trending-badge"><?= esc($t['vote_average']) ?></span>
                <span class="trending-badge">Trending</span>

                <h3><?= esc($t['title']) ?></h3>
                <p><?= isset($t['release_date']) ? substr($t['release_date'], 0, 4) : '' ?></p>

                <a href="#" class="watch-btn">▶ Watch Now</a>
            </div>
        </div>

        <div class="trending-list">
            <?php for ($i = 1; $i < 4 && $i < count($trending_films); $i++): 
                $tr = $trending_films[$i];
                $tr_poster = buildPosterUrl($imageUrl, $tr['poster_path'] ?? '');
            ?>
                <div class="trending-item">
                    <img src="<?= esc($tr_poster ?: base_url('assets/images/placeholder.jpg')) ?>"
                         alt="<?= esc($tr['title']) ?>"
                         onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>';">

                    <span class="trending-item-badge"><?= esc($tr['vote_average']) ?></span>

                    <div class="trending-item-overlay">
                        <h4><?= esc($tr['title']) ?></h4>
                        <p><?= isset($tr['release_date']) ? substr($tr['release_date'], 0, 4) : '' ?></p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Semua Film -->
    <div class="section-header"><h2>Semua Film</h2></div>

    <div class="film-grid" id="filmGrid">
        <?php foreach ($films as $film): ?>
            <?php
                // buat poster (sama logic seperti di atas)
                $poster = null;
                if (isset($film['is_tmdb']) && $film['is_tmdb']) {
                    if (!empty($film['poster_path']) && strpos($film['poster_path'], 'http') === 0) {
                        $poster = $film['poster_path'];
                    } else {
                        $poster = buildPosterUrl($imageUrl, $film['poster_path'] ?? '');
                    }
                } else {
                    // poster lokal (nama file)
                    $poster = base_url('assets/images/posters/' . ($film['poster_path'] ?? 'no-poster.jpg'));
                }

                // apakah film sudah favorit user (jika $userFavorites disediakan)
                $isFav = in_array($film['id'], $userFavorites ?? []) ? true : false;

                // escape untuk passing ke JS
                $jsTitle = addslashes($film['title'] ?? '');
                $jsPoster = addslashes($poster ?? '');
            ?>
            <div class="film-card"
             onclick="goDetail(<?= $film['id'] ?>, <?= isset($film['is_tmdb']) ? 1 : 0 ?>)>"
            data-id="<?= esc($film['id']) ?>"
            data-url="<?= base_url('detail/' . $film['id']) ?>"
            data-genre="<?= esc($film['genre'] ?? '') ?>"
            data-year="<?= esc($film['year'] ?? '') ?>"
            data-rating="<?= esc($film['rating'] ?? 0) ?>">

            <img src="<?= esc($poster) ?>"
                alt="<?= esc($film['title']) ?>"
                class="film-poster"
                onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>'">

            <span class="film-rating"><?= number_format($film['rating'] ?? 0, 1) ?></span>

            <button class="film-favorite <?= $isFav ? 'favorited' : '' ?>"
                    onclick="event.stopPropagation(); toggleFavorite(<?= (int)$film['id'] ?>, '<?= $jsTitle ?>', '<?= $jsPoster ?>', this)">
                <?= $isFav ? '❤️' : '♡' ?>
            </button>

            <div class="film-info">
                <h3 class="film-title"><?= esc($film['title']) ?></h3>
                <div class="film-meta">
                    <span><?= esc($film['genre'] ?? '-') ?></span>
                    <span><?= esc($film['year'] ?? '-') ?></span>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
    </div>

    <!-- Category -->
    <div class="category-section">
        <div class="section-header"><h2>Jelajahi Kategori</h2></div>

        <div class="category-grid category-box-style">
            <div class="category-card" onclick="filterByGenre('Action')">💥 Action</div>
            <div class="category-card" onclick="filterByGenre('Romance')">💕 Romance</div>
            <div class="category-card" onclick="filterByGenre('Comedy')">😄 Comedy</div>
            <div class="category-card" onclick="filterByGenre('Horror')">👻 Horror</div>
            <div class="category-card" onclick="filterByGenre('Sci-Fi')">🚀 Sci-Fi</div>
            <div class="category-card" onclick="filterByGenre('Thriller')">👁️ Thriller</div>
        </div>
    </div>

</div>

<!-- ========================================================= -->
<!--                           FOOTER                          -->
<!-- ========================================================= -->

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

<!-- ===================================================== -->
<!--                     JAVASCRIPT                         -->
<!-- ===================================================== -->

<script>
/* util: ambil CSRF dari meta */
const csrfName = document.querySelector('meta[name="csrf-name"]').content;
let csrfHash = document.querySelector('meta[name="csrf-hash"]').content;

function toggleUserMenu() {
    document.getElementById("userMenu").classList.toggle("show");
}

window.addEventListener('click', function(e) {
    if (!e.target.closest('.user-profile')) {
        document.getElementById("userMenu").classList.remove("show");
    }
});

/* ======================================== */
/*           SEARCH + FILTER LOGIC          */
/* ======================================== */

const filmGrid = document.getElementById("filmGrid");
const searchInput = document.getElementById("searchInput");
const genreFilter = document.getElementById("genreFilter");
const yearFilter = document.getElementById("yearFilter");
const ratingFilter = document.getElementById("ratingFilter");
const sortFilter = document.getElementById("sortFilter");

let notFoundMsg = document.createElement("div");
notFoundMsg.innerHTML = "Tidak ada film ditemukan...";
notFoundMsg.style.textAlign = "center";
notFoundMsg.style.padding = "40px";
notFoundMsg.style.color = "#8899aa";
notFoundMsg.style.fontSize = "18px";
notFoundMsg.style.display = "none";
filmGrid.parentNode.insertBefore(notFoundMsg, filmGrid.nextSibling);

function applyAll() {
    const keyword = searchInput.value.toLowerCase();
    const genre = genreFilter.value.toLowerCase();
    const year = yearFilter.value;
    const rating = ratingFilter.value;
    const sort = sortFilter.value;

    const cards = Array.from(document.querySelectorAll(".film-card"));
    let visibleCards = [];

    cards.forEach(card => {
        const title = (card.querySelector(".film-title")?.innerText || '').toLowerCase();
        const filmGenre = (card.dataset.genre || '').toLowerCase();
        const filmYear = card.dataset.year || '';
        const filmRating = parseFloat(card.dataset.rating || 0);

        let visible = true;

        if (keyword && !title.includes(keyword) && !filmGenre.includes(keyword))
            visible = false;
        if (genre && !filmGenre.includes(genre))
            visible = false;
        if (year && filmYear !== year)
            visible = false;
        if (rating && filmRating < parseFloat(rating))
            visible = false;

        card.style.display = visible ? "block" : "none";
        if (visible) visibleCards.push(card);
    });

    visibleCards.sort((a, b) => {
        let rA = parseFloat(a.dataset.rating || 0);
        let rB = parseFloat(b.dataset.rating || 0);
        let yA = parseInt(a.dataset.year || 0);
        let yB = parseInt(b.dataset.year || 0);

        switch (sort) {
            case "rating":
                return rB - rA;
            case "newest":
                return yB - yA;
            case "oldest":
                return yA - yB;
            default:
                return 0;
        }
    });

    visibleCards.forEach(card => filmGrid.appendChild(card));
    notFoundMsg.style.display = visibleCards.length === 0 ? "block" : "none";
}

// debounce kecil
let debounceTimer;
searchInput.addEventListener("keyup", (e) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(applyAll, 250);
});

genreFilter.addEventListener("change", applyAll);
yearFilter.addEventListener("change", applyAll);
ratingFilter.addEventListener("change", applyAll);
sortFilter.addEventListener("change", applyAll);

searchInput.addEventListener("keydown", e => {
    if (e.key === "Enter") {
        e.preventDefault();
        applyAll();
    }
});

/* ======================================== */
/*           FAVORITE TOGGLE (AJAX)         */
/* ======================================== */

function toggleFavorite(movieId, title, poster, btn) {
    // kirim CSRF dengan nama token dinamis (CI4 expects name => hash)
    const payload = {
        movie_id: movieId,
        title: title,
        poster: poster
    };
    // sisipkan CSRF ke object payload
    payload[csrfName] = csrfHash;

    fetch("<?= base_url('/favorite/toggle') ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify(payload)
    })
    .then(res => {
        // jika server mengembalikan header CSRF baru, update hash (CI4 rotate token)
        const newCsrf = res.headers.get('X-CSRF-Hash');
        if (newCsrf) {
            csrfHash = newCsrf;
            document.querySelector('meta[name="csrf-hash"]').content = newCsrf;
        }
        return res.json();
    })
    .then(res => {
        if (res.status === "added") {
            btn.innerHTML = "❤️";
            btn.classList.add("favorited");
        } else if (res.status === "removed") {
            btn.innerHTML = "♡";
            btn.classList.remove("favorited");
        } else {
            console.warn('Unknown response', res);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Gagal menyimpan favorit. Coba lagi.');
    });
}
</script>
<script>
    document.querySelectorAll(".film-card").forEach(card => {
    card.addEventListener("click", () => {
        const url = card.dataset.url;
        if (url) window.location.href = url;
    });
});

// ======================================================
// FIX: Klik poster → pindah ke halaman detail (TMDB + lokal)
// ======================================================
document.querySelectorAll('.film-card').forEach(card => {
    // jangan override tombol favorite
    card.addEventListener('click', function (e) {

        // jika yang diklik tombol favorite → jangan pindah halaman
        if (e.target.closest(".film-favorite")) return;

        const id = this.dataset.id;

        if (!id) return;

        // arahkan ke halaman detail
        window.location.href = "<?= base_url('detail') ?>/" + id;
    });
});
function goDetail(id, isTmdb) {
    if (isTmdb == 1) {
        window.location.href = "<?= base_url('detail_tmdb/') ?>" + id;
    } else {
        window.location.href = "<?= base_url('detail/') ?>" + id;
    }
}

</script>
</body>
</html>
