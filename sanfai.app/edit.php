<?php
require_once 'konek.php';
$koneksi = conn();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $koneksi->prepare("SELECT * FROM leads WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$leads = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$leads) {
    die("Data tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SanFai — Edit Leads</title>
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

  .topbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:24px clamp(24px,5vw,64px);
    background:var(--teal-deep);
    flex-wrap:wrap;
    gap:12px;
  }

  .brand{ display:flex; align-items:center; gap:14px; }
  .brand-mark{ width:34px;height:34px; flex-shrink:0; }
  .brand-text .name{
    font-family:'Fraunces', serif;
    font-weight:600;
    font-size:18px;
    color:var(--white);
  }
  .brand-text .tag{
    font-size:10.5px;
    letter-spacing:0.12em;
    text-transform:uppercase;
    color:var(--gold-bright);
    font-family:'IBM Plex Mono', monospace;
  }

  .back-link{
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    color:rgba(255,255,255,0.75);
    text-decoration:none;
    border:1px solid rgba(255,255,255,0.25);
    padding:8px 14px;
    border-radius:20px;
    transition:border-color .2s, color .2s;
  }
  .back-link:hover{ color:#fff; border-color:#fff; }

  .content{
    flex:1;
    display:flex;
    justify-content:center;
    padding:clamp(28px,5vh,52px) clamp(24px,5vw,64px);
  }

  .card{
    width:100%;
    max-width:640px;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:18px;
    box-shadow:0 18px 40px rgba(32,40,31,0.10);
    padding:clamp(28px,4vw,44px);
  }

  .eyebrow{
    display:flex;
    align-items:center;
    gap:10px;
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    letter-spacing:0.14em;
    text-transform:uppercase;
    color:var(--gold);
    margin-bottom:10px;
  }
  .eyebrow::before{
    content:'';
    width:22px;height:1px;
    background:var(--gold);
    display:inline-block;
  }

  .card h1{
    font-family:'Fraunces', serif;
    font-weight:500;
    font-size:clamp(24px,3vw,30px);
    margin-bottom:8px;
  }

  .card p.desc{
    font-size:14.5px;
    color:rgba(32,40,31,0.6);
    margin-bottom:28px;
    line-height:1.5;
  }

  label{
    display:block;
    font-family:'IBM Plex Mono', monospace;
    font-size:11.5px;
    letter-spacing:0.06em;
    text-transform:uppercase;
    font-weight:600;
    color:var(--ink);
    margin-bottom:8px;
  }

  .field{ margin-bottom:20px; }

  input[type="text"],
  input[type="date"]{
    width:100%;
    padding:13px 16px;
    background:var(--sand);
    border:1px solid var(--line);
    border-radius:10px;
    font-family:'Manrope', sans-serif;
    font-size:14.5px;
    color:var(--ink);
    outline:none;
    transition:border-color .2s, background .2s;
  }
  input[type="text"]:focus,
  input[type="date"]:focus{
    border-color:var(--teal);
    background:var(--white);
  }

  .actions{
    display:flex;
    align-items:center;
    gap:16px;
    margin-top:8px;
  }

  .btn-simpan{
    background:var(--teal);
    color:#fff;
    border:none;
    padding:13px 26px;
    border-radius:10px;
    font-family:'Manrope', sans-serif;
    font-size:14px;
    font-weight:700;
    cursor:pointer;
    transition:background .2s;
  }
  .btn-simpan:hover{ background:var(--teal-deep); }

  .btn-batal{
    font-family:'IBM Plex Mono', monospace;
    font-size:12.5px;
    color:rgba(32,40,31,0.55);
    text-decoration:none;
    border:1px solid var(--line);
    padding:12px 20px;
    border-radius:10px;
    transition:border-color .2s, color .2s;
  }
  .btn-batal:hover{ color:var(--ink); border-color:var(--ink); }

  footer{
    padding:20px clamp(24px,5vw,64px);
    text-align:center;
    font-family:'IBM Plex Mono', monospace;
    font-size:11px;
    color:rgba(32,40,31,0.4);
    letter-spacing:0.04em;
  }
</style>
</head>
<body>

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
    <a class="back-link" href="data.php">&larr; Kembali ke Data Leads</a>
  </div>

  <div class="content">
    <div class="card">
      <div class="eyebrow">Edit Data</div>
      <h1>Edit Leads</h1>
      <p class="desc">Perbarui detail leads di bawah, lalu simpan perubahan.</p>

      <form method="POST" action="data.php">
        <input type="hidden" name="edit" value="1">
        <input type="hidden" name="id" value="<?= $leads['id'] ?>">

        <div class="field">
          <label>Nama</label>
          <input type="text" name="nama" value="<?= htmlspecialchars($leads['nama']) ?>" required>
        </div>

        <div class="field">
          <label>Alamat</label>
          <input type="text" name="alamat" value="<?= htmlspecialchars($leads['alamat']) ?>">
        </div>

        <div class="field">
          <label>Tanggal</label>
          <input type="date" name="tanggal" value="<?= $leads['tanggal'] ?>">
        </div>

        <div class="field">
          <label>No Telepon</label>
          <input type="text" name="no_telepon" value="<?= htmlspecialchars($leads['no_telepon']) ?>">
        </div>

        <div class="field">
          <label>PIC</label>
          <input type="text" name="pic" value="<?= htmlspecialchars($leads['pic']) ?>">
        </div>

        <div class="field">
          <label>Minat</label>
          <input type="text" name="minat" value="<?= htmlspecialchars($leads['minat']) ?>">
        </div>

        <div class="field">
          <label>Diinput Oleh</label>
          <input type="text" value="<?= htmlspecialchars($leads['diinput_oleh'] ?? '-') ?>" disabled>
        </div>

        <div class="actions">
          <button type="submit" class="btn-simpan">Simpan Perubahan</button>
          <a href="data.php" class="btn-batal">Batal</a>
        </div>
      </form>
    </div>
  </div>

  <footer>SanFai Property System · Panel Admin</footer>

</body>
</html>