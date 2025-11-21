<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <a href="<?= base_url('/dashboard') ?>" class="logo">MOVIX</a>
            <ul class="nav-links">
                <li><a href="<?= base_url('/dashboard') ?>" class="active">Film</a></li>
                <li><a href="#">Genre</a></li>
                <li><a href="#">Favorit</a></li>
            </ul>
        </div>
        <div class="navbar-right">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Cari film..." id="searchInput">
            </div>
            <div class="user-profile" onclick="toggleUserMenu()">
                <div class="user-avatar">
                    <?= strtoupper(substr($user['email'], 0, 1)) ?>
                </div>
                <div class="notification-badge"></div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Hero Section -->
        <div class="hero-section">
            <h1>Koleksi Film</h1>
            <p>Temukan film terbaik untuk Anda</p>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <label>Genre</label>
                <select id="genreFilter">
                    <option value="">Semua Genre</option>
                    <option value="Action">Action</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Drama">Drama</option>
                    <option value="Horror">Horror</option>
                    <option value="Romance">Romance</option>
                    <option value="Sci-fi">Sci-fi</option>
                    <option value="Thriller">Thriller</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Tahun</label>
                <select id="yearFilter">
                    <option value="">Semua Tahun</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Rating</label>
                <select id="ratingFilter">
                    <option value="">Semua Rating</option>
                    <option value="9">9+ ⭐</option>
                    <option value="8">8+ ⭐</option>
                    <option value="7">7+ ⭐</option>
                    <option value="6">6+ ⭐</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Urutkan</label>
                <select id="sortFilter">
                    <option value="">Populeritas</option>
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="rating">Rating Tertinggi</option>
                </select>
            </div>
        </div>

        <!-- Trending Section -->
        <?php if (!empty($trending_films)): ?>
        <div class="section-header">
            <span class="icon">🔥</span>
            <h2>Trending Sekarang</h2>
        </div>

        <div class="trending-container">
            <?php if (isset($trending_films[0])): ?>
            <div class="trending-main">
                <?php if (!empty($trending_films[0]['poster_path'])): ?>
                    <img src="<?= base_url('uploads/posters/' . $trending_films[0]['poster_path']) ?>" alt="<?= esc($trending_films[0]['title']) ?>" onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>'">
                <?php else: ?>
                    <img src="<?= base_url('assets/images/placeholder.jpg') ?>" alt="<?= esc($trending_films[0]['title']) ?>">
                <?php endif; ?>
                <div class="trending-overlay">
                    <span class="trending-badge">8.5</span>
                    <span class="trending-badge">Action, Thriller</span>
                    <h3><?= esc($trending_films[0]['title']) ?></h3>
                    <p class="trending-meta"><?= $trending_films[0]['year'] ?> • 2h 30m</p>
                    <a href="<?= base_url('film/' . $trending_films[0]['id']) ?>" class="watch-btn">
                        ▶ Watch Now
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <div class="trending-list">
                <?php 
                $trendingRatings = ['8.9/10', '8.8/10', '7.8/10'];
                for ($i = 1; $i < count($trending_films) && $i < 3; $i++): 
                ?>
                <div class="trending-item" onclick="window.location.href='<?= base_url('film/' . $trending_films[$i]['id']) ?>'">
                    <?php if (!empty($trending_films[$i]['poster_path'])): ?>
                        <img src="<?= base_url('uploads/posters/' . $trending_films[$i]['poster_path']) ?>" alt="<?= esc($trending_films[$i]['title']) ?>" onerror="this.src='<?= base_url('assets/images/placeholder.jpg') ?>'">
                    <?php else: ?>
                        <img src="<?= base_url('assets/images/placeholder.jpg') ?>" alt="<?= esc($trending_films[$i]['title']) ?>">
                    <?php endif; ?>
                    <span class="trending-item-badge">8.5</span>
                    <span class="trending-item-rating"><?= $trendingRatings[$i] ?></span>
                    <div class="trending-item-overlay">
                        <h4><?= esc($trending_films[$i]['title']) ?></h4>
                        <p><?= $trending_films[$i]['year'] ?> • <?= esc($trending_films[$i]['genre']) ?></p>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- All Films Section -->
        <div class="section-header">
            <h2>Semua Film</h2>
        </div>

        <div class="film-grid" id="filmGrid">
            <?php if (!empty($films)): ?>
                <?php foreach ($films as $film): ?>
                <div class="film-card" data-genre="<?= esc($film['genre']) ?>" data-year="<?= $film['year'] ?>" data-rating="<?= $film['rating'] ?? 0 ?>">
                    <img src="<?= base_url('assets/images/poster-' . $film['id'] . '.jpg') ?>" 
                         alt="<?= esc($film['title']) ?>" 
                         class="film-poster"
                         onerror="this.src='https://via.placeholder.com/300x450/1a2332/f39c12?text=<?= urlencode($film['title']) ?>'">
                    <span class="film-rating"><?= number_format($film['rating'] ?? 0, 1) ?></span>
                    <button class="film-favorite" onclick="toggleFavorite(event, <?= $film['id'] ?>)">♡</button>
                    <div class="film-info">
                        <h3 class="film-title"><?= esc($film['title']) ?></h3>
                        <div class="film-meta">
                            <span><?= esc($film['genre']) ?></span>
                            <span><?= $film['year'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; padding: 60px; color: #8899aa;">
                    Belum ada film tersedia. Admin sedang menambahkan film baru.
                </p>
            <?php endif; ?>
        </div>

        <!-- Load More Button -->
        <?php if (count($films) >= 6): ?>
        <div class="load-more-container">
            <button class="load-more-btn" onclick="loadMore()">Muat Film Lain</button>
        </div>
        <?php endif; ?>

        <!-- Category Section -->
        <div class="category-section">
            <div class="section-header">
                <h2>Jelajahi Kategori</h2>
            </div>
            <div class="category-grid">
                <div class="category-card" onclick="filterByGenre('Action')">
                    <div class="category-icon">💥</div>
                    <div class="category-name">Action</div>
                    <div class="category-count">245 Film</div>
                </div>
                <div class="category-card" onclick="filterByGenre('Romance')">
                    <div class="category-icon">💕</div>
                    <div class="category-name">Romance</div>
                    <div class="category-count">186 Film</div>
                </div>
                <div class="category-card" onclick="filterByGenre('Comedy')">
                    <div class="category-icon">😄</div>
                    <div class="category-name">Comedy</div>
                    <div class="category-count">156 Film</div>
                </div>
                <div class="category-card" onclick="filterByGenre('Horror')">
                    <div class="category-icon">👻</div>
                    <div class="category-name">Horror</div>
                    <div class="category-count">98 Film</div>
                </div>
                <div class="category-card" onclick="filterByGenre('Sci-Fi')">
                    <div class="category-icon">🚀</div>
                    <div class="category-name">Sci-Fi</div>
                    <div class="category-count">134 Film</div>
                </div>
                <div class="category-card" onclick="filterByGenre('Thriller')">
                    <div class="category-icon">👁️</div>
                    <div class="category-name">Thriller</div>
                    <div class="category-count">187 Film</div>
                </div>
                <div class="category-card" onclick="filterByGenre('Fantasy')">
                    <div class="category-icon">🧙</div>
                    <div class="category-name">Fantasy</div>
                    <div class="category-count">89 Film</div>
                </div>
                <div class="category-card" onclick="filterByGenre('History')">
                    <div class="category-icon">🕐</div>
                    <div class="category-name">History</div>
                    <div class="category-count">78 Film</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Movix</h3>
                <p>Platform streaming film terbaik dengan koleksi lengkap dari berbagai genre.</p>
                <div class="social-links">
                    <a href="#" class="social-link">📘</a>
                    <a href="#" class="social-link">🐦</a>
                    <a href="#" class="social-link">📷</a>
                    <a href="#" class="social-link">▶️</a>
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

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = `<?= base_url('search') ?>?q=${encodeURIComponent(query)}`;
                }
            }
        });

        // Filter functionality
        function applyFilters() {
            const genre = document.getElementById('genreFilter').value;
            const year = document.getElementById('yearFilter').value;
            const rating = document.getElementById('ratingFilter').value;
            const cards = document.querySelectorAll('.film-card');

            cards.forEach(card => {
                const cardGenre = card.dataset.genre;
                const cardYear = card.dataset.year;
                const cardRating = parseFloat(card.dataset.rating);

                let show = true;

                if (genre && !cardGenre.includes(genre)) show = false;
                if (year && cardYear !== year) show = false;
                if (rating && cardRating < parseFloat(rating)) show = false;

                card.style.display = show ? 'block' : 'none';
            });
        }

        document.getElementById('genreFilter').addEventListener('change', applyFilters);
        document.getElementById('yearFilter').addEventListener('change', applyFilters);
        document.getElementById('ratingFilter').addEventListener('change', applyFilters);

        // Toggle favorite
        function toggleFavorite(event, filmId) {
            event.stopPropagation();
            const btn = event.target;
            btn.textContent = btn.textContent === '♡' ? '❤️' : '♡';
        }

        // Filter by genre from category
        function filterByGenre(genre) {
            document.getElementById('genreFilter').value = genre;
            applyFilters();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Load more films
        function loadMore() {
            alert('Fitur load more akan segera ditambahkan!');
        }

        // Add click event to film cards
        document.querySelectorAll('.film-card').forEach(card => {
            card.addEventListener('click', function() {
                const filmId = this.dataset.id;
                if (filmId) {
                    window.location.href = `<?= base_url('film/') ?>${filmId}`;
                }
            });
        });

        // User menu toggle
        function toggleUserMenu() {
            if (confirm('Logout dari akun Anda?')) {
                window.location.href = '<?= base_url('auth/logout') ?>';
            }
        }
    </script>
</body>
</html>