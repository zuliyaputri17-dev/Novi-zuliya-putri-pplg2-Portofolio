<?php

require_once 'konek.php';

$koneksi = conn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {

    $id         = (int) $_POST['id'];
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $tanggal    = $_POST['tanggal'];
    $no_telepon = $_POST['no_telepon'];
    $pic        = $_POST['pic'];
    $minat      = $_POST['minat'];

    $stmt = $koneksi->prepare("UPDATE leads SET nama = ?, alamat = ?, tanggal = ?, no_telepon = ?, pic = ?, minat = ? WHERE id = ?");

    if ($stmt) {
        $stmt->bind_param('ssssssi', $nama, $alamat, $tanggal, $no_telepon, $pic, $minat, $id);
        $stmt->execute();
        $stmt->close();

        header('Location: data.php');
        exit;
    } else {
        die("Error Update: " . $koneksi->error);
    }
}

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];

    $stmt = $koneksi->prepare("DELETE FROM leads WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $id_hapus);
        $stmt->execute();
        $stmt->close();

        header('Location: data.php');
        exit;
    } else {
        die("Error Hapus: " . $koneksi->error);
    }
}

$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$bulan  = (isset($_GET['bulan']) && $_GET['bulan'] !== '') ? (int) $_GET['bulan'] : null;
$tahun  = (isset($_GET['tahun']) && $_GET['tahun'] !== '') ? (int) $_GET['tahun'] : null;

$tahunResult = $koneksi->query("SELECT DISTINCT YEAR(tanggal) AS thn FROM leads ORDER BY thn DESC");
$daftarTahun = [];
if ($tahunResult) {
    while ($r = $tahunResult->fetch_assoc()) {
        $daftarTahun[] = (int) $r['thn'];
    }
}
if (empty($daftarTahun)) {
    $daftarTahun[] = (int) date('Y');
}

$where  = [];
$types  = '';
$params = [];

if ($search !== '') {
    $like = '%' . $search . '%';
    $where[] = "(nama LIKE ? OR alamat LIKE ? OR no_telepon LIKE ? OR pic LIKE ? OR minat LIKE ?)";
    $types  .= 'sssss';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($bulan !== null) {
    $where[] = "MONTH(tanggal) = ?";
    $types  .= 'i';
    $params[] = $bulan;
}

if ($tahun !== null) {
    $where[] = "YEAR(tanggal) = ?";
    $types  .= 'i';
    $params[] = $tahun;
}

$sql = "SELECT * FROM leads";
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY id DESC";

if (!empty($params)) {
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $koneksi->query($sql);
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SanFai — Data Leads</title>
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
    padding:clamp(28px,5vh,52px) clamp(24px,5vw,64px);
  }

  .head-row{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    flex-wrap:wrap;
    gap:16px;
    max-width:1180px;
    margin:0 auto 24px;
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

  .head-row h1{
    font-family:'Fraunces', serif;
    font-weight:500;
    font-size:clamp(24px,3vw,30px);
  }

  .btn-tambah{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:var(--teal);
    color:#fff;
    text-decoration:none;
    padding:12px 20px;
    border-radius:10px;
    font-size:14px;
    font-weight:700;
    transition:background .2s;
  }
  .btn-tambah:hover{ background:var(--teal-deep); }

  .search-wrap{
    max-width:1180px;
    margin:0 auto 18px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
  }

  .search-box{
    flex:1;
    min-width:220px;
    display:flex;
    align-items:center;
    gap:10px;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:10px;
    padding:10px 16px;
    box-shadow:0 6px 16px rgba(32,40,31,0.06);
  }

  .search-box svg{ flex-shrink:0; opacity:0.5; }

  .search-box input{
    flex:1;
    border:none;
    outline:none;
    background:transparent;
    font-family:'Manrope', sans-serif;
    font-size:14px;
    color:var(--ink);
  }
  .search-box input::placeholder{ color:rgba(32,40,31,0.4); }

  .search-actions{
    display:flex;
    gap:10px;
  }

  .filter-select{
    background:var(--white);
    border:1px solid var(--line);
    border-radius:10px;
    padding:0 14px;
    font-family:'Manrope', sans-serif;
    font-size:14px;
    color:var(--ink);
    box-shadow:0 6px 16px rgba(32,40,31,0.06);
    outline:none;
    min-width:130px;
  }
  .filter-select:focus{ border-color:var(--teal); }

  .btn-cari{
    background:var(--teal);
    color:#fff;
    border:none;
    padding:0 22px;
    border-radius:10px;
    font-size:14px;
    font-weight:700;
    font-family:'Manrope', sans-serif;
    cursor:pointer;
    transition:background .2s;
  }
  .btn-cari:hover{ background:var(--teal-deep); }

  .btn-reset{
    display:inline-flex;
    align-items:center;
    background:var(--white);
    color:var(--teal);
    border:1px solid rgba(31,78,74,0.3);
    padding:0 20px;
    border-radius:10px;
    font-size:14px;
    font-weight:700;
    text-decoration:none;
    transition:background .2s;
  }
  .btn-reset:hover{ background:var(--sand); }

  .search-info{
    max-width:1180px;
    margin:0 auto 14px;
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    color:rgba(32,40,31,0.55);
  }
  .search-info b{ color:var(--ink); }

  .table-wrap{
    max-width:1180px;
    margin:0 auto;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:18px;
    box-shadow:0 18px 40px rgba(32,40,31,0.10);
    overflow:hidden;
  }

  table{
    width:100%;
    border-collapse:collapse;
  }

  thead th{
    text-align:left;
    padding:16px 20px;
    font-family:'IBM Plex Mono', monospace;
    font-size:11.5px;
    letter-spacing:0.06em;
    text-transform:uppercase;
    color:var(--white);
    background:var(--teal);
  }

  tbody td{
    padding:16px 20px;
    font-size:14px;
    border-bottom:1px solid var(--line);
    color:var(--ink);
  }

  tbody tr:last-child td{ border-bottom:none; }
  tbody tr:hover{ background:var(--sand); }

  .aksi{ white-space:nowrap; }
  .aksi a{
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    text-decoration:none;
    color:var(--teal);
    border:1px solid rgba(31,78,74,0.3);
    padding:6px 12px;
    border-radius:16px;
    transition:background .2s;
    display:inline-block;
    margin-right:8px;
  }
  .aksi a:last-child{ margin-right:0; }
  .aksi a:hover{ background:var(--sand); }
  .aksi a.hapus{
    color:#B23A3A;
    border-color:rgba(178,58,58,0.35);
  }
  .aksi a.hapus:hover{ background:#FBEAEA; }

  .empty{
    padding:60px 20px;
    text-align:center;
    color:rgba(32,40,31,0.5);
    font-size:14.5px;
  }

  footer{
    padding:20px clamp(24px,5vw,64px);
    text-align:center;
    font-family:'IBM Plex Mono', monospace;
    font-size:11px;
    color:rgba(32,40,31,0.4);
    letter-spacing:0.04em;
  }

  @media (max-width:760px){
    .table-wrap{ overflow-x:auto; }
    table{ min-width:640px; }
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
    <div class="head-row">
      <div>
        <div class="eyebrow">Data Leads</div>
        <h1>Seluruh Leads Terinput</h1>
      </div>
      <a class="btn-tambah" href="input.php">+ Tambah Leads</a>
      <a class="back-link" href="laporan.php" style="color:var(--teal); border-color:rgba(31,78,74,0.3);">Laporan Bulanan</a>
    </div>

    <form class="search-wrap" method="GET" action="data.php">
      <div class="search-box">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="11" cy="11" r="7" stroke="#20281F" stroke-width="2"/>
          <path d="M21 21L16.65 16.65" stroke="#20281F" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <input type="text" name="q" placeholder="Cari nama, alamat, no telepon, PIC, atau minat..." value="<?php echo htmlspecialchars($search); ?>">
      </div>
      <select name="bulan" class="filter-select">
        <option value="">Semua Bulan</option>
        <?php foreach ($namaBulan as $angka => $nama): ?>
          <option value="<?php echo $angka; ?>" <?php echo $bulan === $angka ? 'selected' : ''; ?>><?php echo $nama; ?></option>
        <?php endforeach; ?>
      </select>
      <select name="tahun" class="filter-select">
        <option value="">Semua Tahun</option>
        <?php foreach ($daftarTahun as $thn): ?>
          <option value="<?php echo $thn; ?>" <?php echo $tahun === $thn ? 'selected' : ''; ?>><?php echo $thn; ?></option>
        <?php endforeach; ?>
      </select>
      <div class="search-actions">
        <button class="btn-cari" type="submit">Cari</button>
        <?php if ($search !== '' || $bulan !== null || $tahun !== null): ?>
        <a class="btn-reset" href="data.php">Reset</a>
        <?php endif; ?>
      </div>
    </form>

    <?php if ($search !== '' || $bulan !== null || $tahun !== null): ?>
    <div class="search-info">
      <?php
        $ket = [];
        if ($search !== '') { $ket[] = 'kata kunci "' . htmlspecialchars($search) . '"'; }
        if ($bulan !== null) { $ket[] = 'bulan ' . $namaBulan[$bulan]; }
        if ($tahun !== null) { $ket[] = 'tahun ' . $tahun; }
        echo 'Hasil filter untuk ' . implode(', ', $ket);
      ?>
      — <?php echo $result ? $result->num_rows : 0; ?> data ditemukan.
    </div>
    <?php endif; ?>

    <div class="table-wrap">
      <?php if ($result && $result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No Telepon</th>
            <th>PIC</th>
            <th>Minat</th>
            <th>Diinput Oleh</th>
            <th>Aksi</th>
           
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars(date('d M Y', strtotime($row['tanggal']))); ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
            <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
            <td><?php echo htmlspecialchars($row['pic']); ?></td>
            <td><?php echo htmlspecialchars($row['minat']); ?></td>
            <td><?php echo htmlspecialchars($row['diinput_oleh'] ?? '-'); ?></td>
            <td class="aksi">
              <a href="edit.php?id=<?php echo (int)$row['id']; ?>">Edit</a>
              <a href="data.php?hapus=<?php echo (int)$row['id']; ?>" class="hapus" onclick="return confirm('Yakin ingin menghapus data \'<?php echo htmlspecialchars(addslashes($row['nama'])); ?>\'? Data yang dihapus tidak dapat dikembalikan.');">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <div class="empty">
          <?php if ($search !== ''): ?>
            Tidak ada data leads yang cocok dengan "<?php echo htmlspecialchars($search); ?>".
          <?php else: ?>
            Belum ada data leads. Klik "Tambah Leads" untuk menambahkan data pertama.
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <footer>SanFai Property System · Panel Admin</footer>

</body>
</html>