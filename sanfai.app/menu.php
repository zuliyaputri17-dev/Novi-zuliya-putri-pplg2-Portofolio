<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SanFai — Menu Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Manrope:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#20281F;
    --teal:#1F4E4A;
    --teal-deep:#153836;
    --sand:#F2ECDD;
    --paper:#FBF8F1;
    --white:#FFFFFF;
    --gold:#8C6B34;
    --gold-bright:#B4915C;
    --line: rgba(32,40,31,0.10);
  }

  *{ box-sizing:border-box; margin:0; padding:0; }
  html,body{ height:100%; }

  body{
    background:var(--paper);
    color:var(--ink);
    font-family:'Manrope', sans-serif;
    min-height:100vh;
    display:flex;
    flex-direction:column;
    overflow-x:hidden;
  }

  /* ---------- hero (image background) ---------- */
  .hero{
    position:relative;
    min-height:58vh;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    background:
      linear-gradient(180deg, rgba(20,26,20,0.62) 0%, rgba(20,26,20,0.28) 42%, rgba(20,26,20,0.78) 100%),
      url('hero-house.jpg') center 68% / cover no-repeat;
    color:var(--white);
    padding-bottom:clamp(40px,7vh,72px);
  }

  .topbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:28px clamp(24px,5vw,64px) 0;
  }

  .brand{
    display:flex;
    align-items:center;
    gap:14px;
  }

  .brand-mark{ width:38px;height:38px; flex-shrink:0; }

  .brand-text{ line-height:1.15; }

  .brand-text .name{
    font-family:'Fraunces', serif;
    font-weight:600;
    font-size:20px;
    letter-spacing:0.3px;
    color:var(--white);
  }

  .brand-text .tag{
    font-size:11px;
    letter-spacing:0.12em;
    text-transform:uppercase;
    color:var(--gold-bright);
    font-family:'IBM Plex Mono', monospace;
  }

  .session{
    text-align:right;
    font-size:12px;
    color:rgba(255,255,255,0.7);
    font-family:'IBM Plex Mono', monospace;
    letter-spacing:0.02em;
  }
  .session strong{
    display:block;
    color:var(--white);
    font-family:'Manrope',sans-serif;
    font-size:13px;
    font-weight:600;
    margin-bottom:2px;
  }

  .hero-copy{
    padding: clamp(20px,4vh,40px) clamp(24px,5vw,64px) 0;
    max-width:760px;
  }

  .eyebrow{
    display:flex;
    align-items:center;
    gap:10px;
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    letter-spacing:0.14em;
    text-transform:uppercase;
    color:var(--gold-bright);
    margin-bottom:18px;
  }
  .eyebrow::before{
    content:'';
    width:22px;height:1px;
    background:var(--gold-bright);
    display:inline-block;
  }

  .hero-copy h1{
    font-family:'Fraunces', serif;
    font-weight:500;
    font-size:clamp(30px,4.4vw,48px);
    line-height:1.12;
    letter-spacing:-0.01em;
    color:var(--white);
  }
  .hero-copy h1 em{
    font-style:italic;
    color:var(--gold-bright);
  }

  .hero-copy p{
    margin-top:16px;
    font-size:15.5px;
    line-height:1.6;
    color:rgba(255,255,255,0.82);
    max-width:56ch;
  }

  /* ---------- panels ---------- */
  .panels-wrap{
    flex:1;
    padding: 0 clamp(24px,5vw,64px) clamp(28px,5vw,56px);
    margin-top:clamp(28px,4vw,48px);
    position:relative;
    z-index:3;
  }

  .panels{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));
    gap:22px;
    max-width:1180px;
    margin:0 auto;
  }

  .panel{
    position:relative;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:18px;
    box-shadow:0 18px 40px rgba(32,40,31,0.10);
    overflow:hidden;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    cursor:pointer;
    text-align:left;
    font:inherit;
    color:inherit;
    transition:transform 0.35s cubic-bezier(.2,.8,.2,1), box-shadow 0.35s ease;
  }
  .panel:hover{ transform:translateY(-5px); box-shadow:0 26px 50px rgba(32,40,31,0.16); }
  .panel:focus-visible{ outline:2px solid var(--gold); outline-offset:-4px; }

  /* gable / roofline strip — signature motif echoing the pitched-roof facade */
  .roofline{
    position:relative;
    height:clamp(110px,17vh,160px);
    display:flex;
    align-items:flex-end;
    justify-content:center;
    overflow:hidden;
    clip-path: polygon(0 100%, 0 40%, 50% 0%, 100% 40%, 100% 100%);
    transition:transform 0.5s cubic-bezier(.2,.8,.2,1);
  }
  .panel:hover .roofline{ transform:translateY(-6px); }

  .panel.view .roofline{ background:linear-gradient(160deg, #2A5D58, #163C39); }
  .panel.input .roofline{ background:linear-gradient(160deg, #C7A16C, #8C6B34); }
  .panel.laporan .roofline{ background:linear-gradient(160deg, #4C7A96, #244C5E); }
  .panel.kontak .roofline{ background:linear-gradient(160deg, #8C6C8F, #4E2F52); }

  .roofline svg{ width:44px;height:44px; margin-bottom:20px; opacity:0.96; }

  .roofline::after{
    content:'';
    position:absolute;
    inset:0;
    background:repeating-linear-gradient(115deg, rgba(255,255,255,0.08) 0 2px, transparent 2px 26px);
    pointer-events:none;
  }

  .panel-body{
    padding:24px clamp(26px,4vw,40px) clamp(28px,4vw,40px);
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    gap:26px;
  }

  .panel-copy h2{
    font-family:'Fraunces', serif;
    font-weight:500;
    font-size:clamp(23px,2.4vw,29px);
    margin-bottom:10px;
    color:var(--ink);
  }

  .panel-copy p{
    font-size:14.5px;
    line-height:1.65;
    color:rgba(32,40,31,0.62);
    max-width:38ch;
  }

  .panel-cta{
    display:flex;
    align-items:center;
    gap:12px;
    font-weight:700;
    font-size:14.5px;
    letter-spacing:0.01em;
    color:var(--ink);
  }

  .panel-cta .arrow{
    width:34px;height:34px;
    border-radius:50%;
    border:1px solid var(--line);
    display:flex;
    align-items:center;
    justify-content:center;
    transition:transform 0.35s ease, border-color 0.35s ease;
    flex-shrink:0;
  }
  .panel.view .panel-cta .arrow{ color:#1F4E4A; }
  .panel.input .panel-cta .arrow{ color:#8C6B34; }
  .panel.laporan .panel-cta .arrow{ color:#244C5E; }
  .panel.kontak .panel-cta .arrow{ color:#4E2F52; }

  .panel:hover .panel-cta .arrow{
    transform:translateX(4px);
    border-color:currentColor;
  }

  footer{
    padding:0 clamp(24px,5vw,64px) 26px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-family:'IBM Plex Mono', monospace;
    font-size:11px;
    color:rgba(32,40,31,0.4);
    letter-spacing:0.04em;
    flex-wrap:wrap;
    gap:8px;
  }

  @media (max-width:760px){
    .panels{ grid-template-columns:1fr; }
    .topbar{ flex-direction:column; align-items:flex-start; gap:14px; }
    .session{ text-align:left; }
    .panels-wrap{ margin-top:24px; }
  }

  @media (prefers-reduced-motion: reduce){
    .roofline, .panel-cta .arrow, .panel{ transition:none; }
  }
</style>
</head>
<body>

  <section class="hero">
    <div class="topbar">
      <div class="brand">
        <svg class="brand-mark" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 34V16L20 5L34 16V34" stroke="#FFFFFF" stroke-width="2" fill="none"/>
          <path d="M14 34V22H26V34" stroke="#D8B77E" stroke-width="2" fill="none"/>
          <path d="M27 10.5C27 12.4 25.4 14 23.5 14C25.4 14 27 15.6 27 17.5C27 15.6 28.6 14 30.5 14C28.6 14 27 12.4 27 10.5Z" fill="#D8B77E"/>
        </svg>
        <div class="brand-text">
          <div class="name">SanFai</div>
          <div class="tag">Perusahaan Property Islami</div>
        </div>
      </div>
      <div class="session">
        <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin Sanfai Property'); ?></strong>
       
      </div>
    </div>

    <div class="hero-copy">
      <div class="eyebrow">Panel Admin</div>
      <h1>Kelola data pelanggan <em>dari Websate ini.</em></h1>
      <p>Pilih tujuan Anda — pantau seluruh data pelanggan yang tercatat, atau tambahkan data pelanggan baru.</p>
    </div>
  </section>

  <div class="panels-wrap">
    <div class="panels">

      <button class="panel view" type="button" onclick="location.href='data.php'">
        <div class="roofline">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="3" width="8" height="8" rx="1.2" stroke="#FFFFFF" stroke-width="1.6"/>
            <rect x="13" y="3" width="8" height="8" rx="1.2" stroke="#FFFFFF" stroke-width="1.6"/>
            <rect x="3" y="13" width="8" height="8" rx="1.2" stroke="#FFFFFF" stroke-width="1.6"/>
            <rect x="13" y="13" width="8" height="8" rx="1.2" stroke="#FFFFFF" stroke-width="1.6"/>
          </svg>
        </div>
        <div class="panel-body">
          <div class="panel-copy">
            <h2>Lihat Data Leads</h2>
            <p>Tinjau seluruh Leads Yang terinput.</p>
          </div>
          <div class="panel-cta">
            Buka Database
            <span class="arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </div>
        </div>
      </button>

      <button class="panel input" type="button" onclick="location.href='input.php'">
        <div class="roofline">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 21V10L12 4L20 10V21" stroke="#FFFFFF" stroke-width="1.6"/>
            <path d="M12 13V19M9 16H15" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </div>
        <div class="panel-body">
          <div class="panel-copy">
            <h2>Input leads</h2>
            <p>Input New leads dengan wajib mengisi Nama, Tanggal, No telepon, PIC.</p>
          </div>
          <div class="panel-cta">
            Tambah New Leads
            <span class="arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </div>
        </div>
      </button>

      <button class="panel laporan" type="button" onclick="location.href='laporan.php'">
        <div class="roofline">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 20V4" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M4 20H20" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/>
            <rect x="7" y="13" width="3" height="6" fill="#FFFFFF"/>
            <rect x="12.5" y="9" width="3" height="10" fill="#FFFFFF"/>
            <rect x="18" y="6" width="3" height="13" fill="#FFFFFF"/>
          </svg>
        </div>
        <div class="panel-body">
          <div class="panel-copy">
            <h2>Laporan Bulanan</h2>
            <p>Lihat rekap leads per bulan, dikelompokkan berdasarkan PIC yang menangani.</p>
          </div>
          <div class="panel-cta">
            Buka Laporan
            <span class="arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </div>
        </div>
      </button>

      <button class="panel kontak" type="button" onclick="location.href='kontak.php'">
        <div class="roofline">
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 3H15L19 7V21H6V3Z" stroke="#FFFFFF" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M9 10H16M9 14H16M9 18H13" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </div>
        <div class="panel-body">
          <div class="panel-copy">
            <h2>Kontak Leads</h2>
            <p>Lihat nama, no telepon, dan PIC, lalu salin datanya per PIC untuk dihubungi.</p>
          </div>
          <div class="panel-cta">
            Buka Kontak
            <span class="arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </div>
        </div>
      </button>

    </div>
  </div>

  <footer>
    <span>SanFai Property</span>
    <span>Panel Admin · v1.0</span>
  </footer>

</body>
</html>