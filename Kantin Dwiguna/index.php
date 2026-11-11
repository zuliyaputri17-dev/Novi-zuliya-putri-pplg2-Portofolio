<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kantin Sekolah</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --orange: #F07D28;
      --orange-light: #FFF3E8;
      --orange-hover: #d96c18;
      --text-dark: #1a1a1a;
      --text-muted: #666;
      --white: #ffffff;
      --bg: #FAFAF8;
    }

    /* MENARIK FOOTER KE REAL-BOTTOM */
    html, body {
      height: 100%;
    }

    body {
      font-family: 'Nunito', sans-serif;
      background: var(--bg);
      color: var(--text-dark);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .main-content-wrapper {
      flex: 1 0 auto;
    }

    /* ── NAVBAR ── */
    nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 80px;
      background: var(--white);
      box-shadow: 0 1px 8px rgba(0,0,0,.04);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }

    .logo-icon {
      width: 44px;
      height: 44px;
      background: var(--orange);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 18px;
    }

    .logo-text { line-height: 1.1; }
    .logo-text .top {
      font-size: 18px;
      font-weight: 900;
      color: var(--text-dark);
      letter-spacing: 0.5px;
    }
    .logo-text .bottom {
      font-size: 11px;
      font-weight: 800;
      color: var(--orange);
      letter-spacing: 2px;
    }

    .btn-login {
      background: var(--orange);
      color: var(--white);
      border: none;
      border-radius: 8px;
      padding: 10px 22px;
      font-family: 'Nunito', sans-serif;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      transition: background .2s;
    }
    .btn-login:hover { background: var(--orange-hover); }

    /* ── HERO SECTION EXTRA LARGE ── */
    .hero {
      background: linear-gradient(180deg, #FFF5EC 0%, #FFFFFF 100%);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 40px 80px;
      position: relative;
      overflow: hidden;
      min-height: 480px; /* Dipertinggi agar megah */
    }

    .hero-dots {
      position: absolute;
      left: 45px;
      top: 40%;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .hero-dots span {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: var(--orange);
      opacity: .35;
    }
    .hero-dots span:nth-child(2) { opacity: .65; }
    .hero-dots span:nth-child(3) { opacity: 1; }

    .hero-content { max-width: 550px; padding-left: 24px; z-index: 2; }

    .hero-content h1 {
      font-size: 46px;
      font-weight: 900;
      line-height: 1.2;
      color: var(--text-dark);
      margin-bottom: 16px;
    }
    .hero-content h1 span { color: var(--orange); }

    .hero-content p {
      font-size: 16px;
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 32px;
    }

    .hero-btns { display: flex; gap: 16px; }

    .btn-primary {
      background: var(--orange);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 12px 26px;
      font-family: 'Nunito', sans-serif;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      box-shadow: 0 4px 10px rgba(240, 125, 40, 0.2);
    }
    .btn-primary:hover { background: var(--orange-hover); }

    .btn-secondary {
      background: transparent;
      color: #777777;
      border: 1px solid #DCDCDC;
      border-radius: 8px;
      padding: 12px 26px;
      font-family: 'Nunito', sans-serif;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }
    .btn-secondary:hover { background: #FAFAFA; color: #333; }

    /* KONFIGURASI GAMBAR UTAMA BESAR DAN PADAT */
    .hero-image-container {
      z-index: 2;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }
    .hero-image {
      width: 540px; /* Ukuran diperbesar signifikan agar tidak sepi */
      height: auto;
      object-fit: contain;
      filter: drop-shadow(0 20px 30px rgba(0,0,0,0.08));
     
    }

    /* ── INFO SECTION BERJAJAR LAYOUT 100% MOCKUP ── */
    .info-section {
      padding: 40px 80px 80px 80px;
      display: flex;
      align-items: stretch;
      gap: 40px;
    }

    .info-card {
      flex: 1;
      display: flex;
      align-items: flex-start;
      gap: 20px;
    }

    .info-icon {
      width: 56px;
      height: 56px;
      min-width: 56px;
      background: var(--orange-light);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: var(--orange);
    }

    .info-card h3 {
      font-size: 18px;
      font-weight: 800;
      color: var(--text-dark);
      margin-bottom: 10px;
    }

    .info-card p {
      font-size: 14px;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .info-card .schedule { margin-top: 2px; }
    .info-card .schedule .day {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 2px;
    }
    .info-card .schedule .time {
      font-size: 14px;
      font-weight: 800;
      color: var(--orange);
      margin-bottom: 10px;
    }

    .divider-line {
      width: 1px;
      background: #EAEAEA;
      align-self: stretch;
    }

    .arrow-right {
      display: flex;
      align-items: center;
      color: var(--orange);
      font-size: 16px;
      padding-left: 10px;
      cursor: pointer;
      opacity: 0.7;
    }

    /* ── FOOTER LOCK PALING BAWAH ── */
    footer {
      flex-shrink: 0;
      background: #F6EEE5;
      border-top: 1px solid #EAE1D7;
    }

    .footer-top {
      padding: 36px 80px;
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 40px;
      text-align: center;
    }

    .footer-col h4 {
      font-size: 13px;
      font-weight: 700;
      color: #828282;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .footer-col p {
      font-size: 14px;
      font-weight: 800;
      color: var(--text-dark);
    }

    .footer-bottom {
      padding: 18px 80px;
      border-top: 1px solid #EAE1D7;
      text-align: center;
      font-size: 12px;
      font-weight: 700;
      color: #999999;
    }

    /* RESPONSIVE LAYOUT BREAKPOINTS */
    @media (max-width: 992px) {
      nav { padding: 16px 40px; }
      .hero { flex-direction: column; padding: 40px; text-align: center; gap: 40px; }
      .hero-dots { display: none; }
      .hero-content { padding-left: 0; max-width: 100%; }
      .hero-btns { justify-content: center; }
      .hero-image-container { justify-content: center; }
      .hero-image { width: 100%; max-width: 440px; }
      .info-section { padding: 40px; flex-direction: column; gap: 32px; }
      .divider-line { display: none; }
      .arrow-right { display: none; }
      .footer-top { grid-template-columns: 1fr; gap: 20px; padding: 30px; }
    }
  </style>
</head>
<body>

  <div class="main-content-wrapper">

    <nav>
      <a href="#" class="logo">
        <div class="logo-icon"><i class="fas fa-utensils"></i></div>
        <div class="logo-text">
          <div class="top">KANTIN</div>
          <div class="bottom">SEKOLAH</div>
        </div>
      </a>
      <a href="login.php" class="btn-login">
        <i class="fas fa-user"></i> Login Admin
      </a>
    </nav>

    <section class="hero">
      <div class="hero-dots">
        <span></span><span></span><span></span>
      </div>

      <div class="hero-content">
        <h1>Selamat Datang di<br>Kantin <span>Sekolah</span></h1>
        <p>Menyediakan berbagai makanan dan minuman sehat, lezat, dan bergizi untuk seluruh siswa.</p>
        <div class="hero-btns">
          <a href="menu.php" class="btn-primary">
            <i class="fas fa-utensils"></i> Lihat Menu
          </a>
          <a href="#tentang" class="btn-secondary">
            <i class="fas fa-store"></i> Tentang Kami
          </a>
        </div>
      </div>

      <div class="hero-image-container">
        <img
          class="hero-image"
          src="menukeren.png"
          alt="Menu Makanan Lengkap Kantin"
        />
      </div>
    </section>

    <section class="info-section" id="tentang">

      <div class="info-card">
        <div class="info-icon"><i class="fas fa-store"></i></div>
        <div>
          <h3>Tentang Kantin</h3>
          <p>Kantin sekolah kami berkomitmen untuk menyediakan makanan dan minuman berkualitas dengan harga terjangkau. Kami mengutamakan kebersihan, kualitas bahan, dan kepuasan siswa.</p>
        </div>
      </div>

      <div class="divider-line"></div>

      <div class="info-card">
        <div class="info-icon"><i class="fas fa-clock"></i></div>
        <div>
          <h3>Jam Operasional</h3>
          <div class="schedule">
            <div class="day">Senin - Jumat</div>
            <div class="time">07.00 - 15.00 WIB</div>
            <div class="day">Sabtu - Minggu</div>
            <div class="time" style="color: #EB5757;">Tutup</div>
          </div>
        </div>
      </div>

      <div class="arrow-right">
        <i class="fas fa-chevron-right"></i>
      </div>

    </section>

  </div>

  <footer>
    <div class="footer-top">
      <div class="footer-col">
        <h4>Kontak</h4>
        <p>0812-3456-7890</p>
      </div>
      <div class="footer-col">
        <h4>Email</h4>
        <p>kantin sekolah.sch.id</p>
      </div>
      <div class="footer-col">
        <h4>Lokasi</h4>
        <p>Area Kantin Sekolah</p>
      </div>
    </div>
    <div class="footer-bottom">
      &copy; 2026 Kantin Sekolah, All Rights Reserved.
    </div>
  </footer>

</body>
</html>