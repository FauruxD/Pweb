<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genre – Movix</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/genre.css') ?>">

    <style>
        /* FIX NAVBAR DI HALAMAN GENRE AGAR BISA DIKLIK */
        .navbar {
            position: relative;
            z-index: 9999 !important;
        }

        .nav-links a {
            position: relative;
            z-index: 99999 !important;
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
                <li><a href="<?= base_url('genre') ?>" class="active">Genre</a></li>
                <li><a href="<?= base_url('favorite') ?>">Favorit</a></li>
            </ul>
        </div>

        <div class="navbar-right">
            <div class="search-box">
                <span class="search-icon"></span>
                <input type="text" placeholder="Cari film..." id="searchInput">
            </div>

            <div class="user-profile" onclick="toggleUserMenu()">
                <div class="user-avatar"><?= strtoupper(substr(session('email'), 0, 1)) ?></div>
                <div class="notification-badge"></div>
            </div>

            <div id="userMenu" class="user-menu">
                <a href="<?= base_url('/') ?>">Landing</a>
                <a href="<?= base_url('auth/logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <!-- TITLE -->
    <div class="genre-title">
        Temukan film favorit Anda berdasarkan genre yang Anda sukai
    </div>

    <!-- GENRE GRID -->
    <div class="genre-grid">

        <!-- ACTION -->
        <div class="genre-card" onclick="window.location.href='<?= base_url('genre/'.$genres['aksi']['id']) ?>'">
            <img src="<?= base_url('assets/images/featured/1.jpg') ?>" class="genre-thumb">
            <div class="genre-body">
                <div class="genre-icon">✨</div>
                <div class="genre-name"><?= $genres['aksi']['name'] ?></div>
                <div class="genre-desc">Film penuh adegan aksi mendebarkan.</div>
                <div class="genre-bottom"><span><?= $genres['aksi']['total'] ?> Film</span></div>
                <div class="genre-arrow">→</div>
            </div>
        </div>

        <!-- DRAMA -->
        <div class="genre-card" onclick="window.location.href='<?= base_url('genre/'.$genres['drama']['id']) ?>'">
            <img src="<?= base_url('assets/images/featured/2.jpg') ?>" class="genre-thumb">
            <div class="genre-body">
                <div class="genre-icon">🎭</div>
                <div class="genre-name"><?= $genres['drama']['name'] ?></div>
                <div class="genre-desc">Cerita mendalam yang menyentuh hati.</div>
                <div class="genre-bottom"><span><?= $genres['drama']['total'] ?> Film</span></div>
                <div class="genre-arrow">→</div>
            </div>
        </div>

        <!-- KOMEDI -->
        <div class="genre-card" onclick="window.location.href='<?= base_url('genre/'.$genres['komedi']['id']) ?>'">
            <img src="<?= base_url('assets/images/featured/3.jpg') ?>" class="genre-thumb">
            <div class="genre-body">
                <div class="genre-icon">😄</div>
                <div class="genre-name"><?= $genres['komedi']['name'] ?></div>
                <div class="genre-desc">Film yang menghibur dan mengocok perut.</div>
                <div class="genre-bottom"><span><?= $genres['komedi']['total'] ?> Film</span></div>
                <div class="genre-arrow">→</div>
            </div>
        </div>

        <!-- HORROR -->
        <div class="genre-card" onclick="window.location.href='<?= base_url('genre/'.$genres['horror']['id']) ?>'">
            <img src="<?= base_url('assets/images/featured/4.jpg') ?>" class="genre-thumb">
            <div class="genre-body">
                <div class="genre-icon">👻</div>
                <div class="genre-name"><?= $genres['horror']['name'] ?></div>
                <div class="genre-desc">Film menegangkan yang bikin merinding.</div>
                <div class="genre-bottom"><span><?= $genres['horror']['total'] ?> Film</span></div>
                <div class="genre-arrow">→</div>
            </div>
        </div>

        <!-- ROMANCE -->
        <div class="genre-card" onclick="window.location.href='<?= base_url('genre/'.$genres['romance']['id']) ?>'">
            <img src="<?= base_url('assets/images/featured/3.jpg') ?>" class="genre-thumb">
            <div class="genre-body">
                <div class="genre-icon">💗</div>
                <div class="genre-name"><?= $genres['romance']['name'] ?></div>
                <div class="genre-desc">Kisah cinta yang menyentuh perasaan.</div>
                <div class="genre-bottom"><span><?= $genres['romance']['total'] ?> Film</span></div>
                <div class="genre-arrow">→</div>
            </div>
        </div>

        <!-- ANIMASI -->
        <div class="genre-card" onclick="window.location.href='<?= base_url('genre/'.$genres['animasi']['id']) ?>'">
            <img src="<?= base_url('assets/images/featured/1.jpg') ?>" class="genre-thumb">
            <div class="genre-body">
                <div class="genre-icon">🧩</div>
                <div class="genre-name"><?= $genres['animasi']['name'] ?></div>
                <div class="genre-desc">Film animasi untuk semua kalangan.</div>
                <div class="genre-bottom"><span><?= $genres['animasi']['total'] ?> Film</span></div>
                <div class="genre-arrow">➜</div>
            </div>
        </div>

        <!-- DOKUMENTER -->
        <div class="genre-card" onclick="window.location.href='<?= base_url('genre/'.$genres['dokumenter']['id']) ?>'">
            <img src="<?= base_url('assets/images/featured/3.jpg') ?>" class="genre-thumb">
            <div class="genre-body">
                <div class="genre-icon">📘</div>
                <div class="genre-name"><?= $genres['dokumenter']['name'] ?></div>
                <div class="genre-desc">Film edukatif berdasarkan fakta nyata.</div>
                <div class="genre-bottom"><span><?= $genres['dokumenter']['total'] ?> Film</span></div>
                <div class="genre-arrow">➜</div>
            </div>
        </div>

        <!-- SCI-FI -->
        <div class="genre-card" onclick="window.location.href='<?= base_url('genre/'.$genres['scifi']['id']) ?>'">
            <img src="<?= base_url('assets/images/posters/interstellar.jpg') ?>" class="genre-thumb">
            <div class="genre-body">
                <div class="genre-icon">🚀</div>
                <div class="genre-name"><?= $genres['scifi']['name'] ?></div>
                <div class="genre-desc">Film fiksi ilmiah bertema teknologi masa depan.</div>
                <div class="genre-bottom"><span><?= $genres['scifi']['total'] ?> Film</span></div>
                <div class="genre-arrow">→</div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Movix</h3>
                <p>Platform streaming film terbaik dengan koleksi lengkap dari berbagai genre.</p>
                <div class="social-links">
                    <a href="#"></a>
                    <a href="#"></a>
                    <a href="#"></a>
                    <a href="#"></a>
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
            © 2025 Movix. All rights reserved.
        </div>
    </footer>

    <!-- FIX JS NAVBAR -->
    <script>
        // Pastikan klik navbar tidak ditangkap genre-card
        document.querySelectorAll('.nav-links a, .logo').forEach(link => {
            link.addEventListener('click', function(e) {
                e.stopPropagation(); 
            }, true);
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
