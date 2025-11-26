<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movix – Streaming Film</title>

    <!-- CSS Landing -->
    <link rel="stylesheet" href="/assets/css/landingpage.css">
</head>

<body>

    <!-- =============================== -->
    <!-- HERO SECTION -->
    <!-- =============================== -->
    <section class="hero">
        <div class="hero-content">
            <span class="tagline">Platform Streaming Film Terbaik</span>

            <h1>Nikmati Film Favorit Anda<br>
            <span>Kapan Saja, Dimana Saja</span></h1>

            <p>Akses ribuan film dari berbagai genre. Kualitas HD, subtitle Indonesia, dan pengalaman menonton yang tak terlupakan.</p>

            <div class="hero-buttons">
                <a href="/auth/login" class="btn-primary">▶ Mulai Menonton</a>
                <a href="#info" class="btn-secondary">Info Lengkap</a>
            </div>
        </div>
    </section>

    <!-- =============================== -->
    <!-- FEATURED MOVIES -->
    <!-- =============================== -->
    <section class="featured">
        <h2>Film Unggulan</h2>
        <p>Pilihan terbaik minggu ini yang wajib kamu tonton</p>

        <div class="featured-grid">

            <div class="movie-card">
                <img src="/assets/images/featured/1.jpg" alt="Film 1">
                <span class="genre-tag">Action</span>
                <span class="rating">⭐ 8.5</span>
                <h3>Thunder Strike</h3>
            </div>

            <div class="movie-card">
                <img src="/assets/images/featured/2.jpg" alt="Film 2">
                <span class="genre-tag">Sci-Fi</span>
                <span class="rating">⭐ 9</span>
                <h3>Neon Future</h3>
            </div>

            <div class="movie-card">
                <img src="/assets/images/featured/3.jpg" alt="Film 3">
                <span class="genre-tag">Horror</span>
                <span class="rating">⭐ 7.8</span>
                <h3>Dark Whispers</h3>
            </div>

            <div class="movie-card">
                <img src="/assets/images/featured/4.jpg" alt="Film 4">
                <span class="genre-tag">Drama</span>
                <span class="rating">⭐ 8.2</span>
                <h3>Eternal Love</h3>
            </div>

        </div>
    </section>

     <!-- =============================== -->
    <!-- GENRE SECTION -->
    <!-- =============================== -->
    <section class="genres">
        <h2>Jelajahi Genre Favorit</h2>
        <p>Temukan film sesuai mood kamu</p>

        <div class="genre-grid">
            <div class="genre-card action">⚡ Action</div>
            <div class="genre-card scifi">✨ Sci-Fi</div>
            <div class="genre-card horror">👻 Horror</div>
            <div class="genre-card romance">💛 Romance</div>
            <div class="genre-card comedy">😄 Comedy</div>
            <div class="genre-card drama">🎬 Drama</div>
        </div>
    </section>

    <!-- =============================== -->
    <!-- CTA SECTION -->
    <!-- =============================== -->
    <section class="cta">
        <h2>Mulai Petualangan Film Anda Sekarang</h2>
        <p>Bergabung dengan jutaan penonton lainnya dan nikmati pengalaman streaming terbaik</p>

        <div class="cta-features">
            <div>✔ Akses unlimited ke ribuan film</div>
            <div>✔ Streaming HD tanpa iklan</div>
            <div>✔ Download untuk offline</div>
            <div>✔ Subtitle Indonesia tersedia</div>
        </div>

        <a href="/auth/register" class="cta-button">Daftar Gratis Sekarang</a>
        <small>Uji coba gratis 30 hari • Batalkan kapan saja</small>
    </section>


    <!-- =============================== -->
    <!-- FOOTER -->
    <!-- =============================== -->
    <footer class="footer">
        <div class="footer-container">

            <div class="footer-about">
                <h3>🎬 Movix</h3>
                <p>Platform streaming film terbaik dengan koleksi film terlengkap dan berkualitas.</p>
            </div>

            <div>
                <h4>Perusahaan</h4>
                <p>Tentang Kami</p>
                <p>Karir</p>
                <p>Press</p>
            </div>

            <div>
                <h4>Bantuan</h4>
                <p>FAQ</p>
                <p>Pusat Bantuan</p>
                <p>Hubungi Kami</p>
            </div>

            <div>
                <h4>Legal</h4>
                <p>Syarat & Ketentuan</p>
                <p>Kebijakan Privasi</p>
                <p>Cookie Policy</p>
            </div>

        </div>

        <div class="footer-bottom">
            © <?= date('Y') ?> Movix. Semua hak dilindungi.
        </div>
    </footer>

</body>
</html>
