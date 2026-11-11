<?php
require_once 'konek.php';

$pesan = '';
$sukses = false;

if (isset($_POST['simpan'])) {
    $nama         = trim($_POST['nama']);
    $alamat       = trim($_POST['alamat']);
    $tanggal      = $_POST['tanggal'];
    $no_telepon   = trim($_POST['no_telepon']);
    $pic          = trim($_POST['pic']);
    $minat        = trim($_POST['minat']);
    $diinput_oleh = trim($_POST['diinput_oleh']);

   
    $koneksi = conn(); 
    $stmt = $koneksi->prepare(
        "INSERT INTO leads (nama, alamat, tanggal, no_telepon, pic, minat, diinput_oleh) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    if ($stmt) {
        $stmt->bind_param('sssssss', $nama, $alamat, $tanggal, $no_telepon, $pic, $minat, $diinput_oleh);
        if ($stmt->execute()) {
            $sukses = true;
            $pesan = 'Data leads berhasil disimpan.';
        } else {
            $pesan = 'Gagal menyimpan data: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $pesan = 'System Error: ' . $koneksi->error;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SanFai — Input Leads</title>
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
    padding:clamp(32px,6vh,64px) 24px;
  }

  .card{
    width:100%;
    max-width:560px;
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
    margin-bottom:14px;
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

  .card > p.desc{
    font-size:14px;
    color:rgba(32,40,31,0.6);
    margin-bottom:28px;
    max-width:44ch;
  }

  .field{ margin-bottom:18px; }
  .field label{
    display:block;
    font-size:12.5px;
    font-weight:700;
    letter-spacing:0.02em;
    color:var(--teal-deep);
    margin-bottom:6px;
    text-transform:uppercase;
  }
  .field input{
    width:100%;
    padding:12px 14px;
    border:1.5px solid var(--line);
    border-radius:10px;
    font-family:inherit;
    font-size:14.5px;
    color:var(--ink);
    background:var(--paper);
    outline:none;
    transition:border-color .2s, background .2s;
  }
  .field input:focus{
    border-color:var(--gold);
    background:var(--white);
  }

  .notice{
    padding:12px 16px;
    border-radius:10px;
    font-size:13.5px;
    margin-bottom:20px;
    font-family:'IBM Plex Mono', monospace;
  }
  .notice.ok{ background:#E7F3EC; color:#1F4E4A; border:1px solid #BFE0CC; }
  .notice.err{ background:#FBEAEA; color:#8C2C2C; border:1px solid #F0C6C6; }

  .btn-simpan{
    width:100%;
    padding:14px;
    background:var(--teal);
    color:#fff;
    border:none;
    border-radius:10px;
    font-family:inherit;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    transition:background .2s, transform .1s;
    margin-top:6px;
  }
  .btn-simpan:hover{ background:var(--teal-deep); }
  .btn-simpan:active{ transform:scale(0.99); }

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
    <a class="back-link" href="menu.php">&larr; Kembali ke Menu</a>
  </div>

  <div class="content">
    <div class="card">
      <div class="eyebrow">Input Data</div>
      <h1>Tambah Leads Baru</h1>
      <p class="desc">Lengkapi detail di bawah untuk mendaftarkan leads baru ke dalam sistem.</p>

      <?php if ($pesan): ?>
        <div class="notice <?php echo $sukses ? 'ok' : 'err'; ?>">
          <?php echo htmlspecialchars($pesan); ?>
        </div>
      <?php endif; ?>

      <form action="input.php" method="post">
        <div class="field">
          <label for="nama">Nama</label>
          <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
        </div>

         <form action="input.php" method="post">
        <div class="field">
          <label for="alamat">Alamat</label>
          <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamatnya" required>
        </div>

        <div class="field">
          <label for="tanggal">Tanggal</label>
          <input type="date" id="tanggal" name="tanggal" required>
        </div>

        <div class="field">
          <label for="no_telepon">No Telepon</label>
          <input type="text" id="no_telepon" name="no_telepon" placeholder="08xxxxxxxxxx" required>
        </div>

        <div class="field">
          <label for="pic">PIC</label>
          <input type="text" id="pic" name="pic" placeholder="Nama PIC yang menangani" required>
        </div>

        <div class="field">
          <label for="minat">minat</label>
          <input type="text" id="minat" name="minat" placeholder="Minat Cluster" required>
        </div>

        <div class="field">
          <label for="diinput_oleh">Diinput Oleh</label>
          <input type="text" id="diinput_oleh" name="diinput_oleh" placeholder="Nama yang menginput data" required>
        </div>

        <button type="submit" name="simpan" class="btn-simpan">Simpan Leads</button>
      </form>
    </div>
  </div>

  <footer>SanFai Property System · Panel Admin</footer>

</body>
</html>