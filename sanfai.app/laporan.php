<?php
require_once 'konek.php';

$koneksi = conn();

$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$bulan = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) date('n');
$tahun = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) date('Y');

if ($bulan < 1 || $bulan > 12) {
    $bulan = (int) date('n');
}

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
if (!in_array($tahun, $daftarTahun)) {
    $daftarTahun[] = $tahun;
    rsort($daftarTahun);
}

$stmt = $koneksi->prepare(
    "SELECT pic, COUNT(*) AS jumlah
     FROM leads
     WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ?
     GROUP BY pic
     ORDER BY jumlah DESC, pic ASC"
);
$stmt->bind_param('ii', $bulan, $tahun);
$stmt->execute();
$rekap = $stmt->get_result();

$dataPic = [];
$totalLeads = 0;
if ($rekap) {
    while ($row = $rekap->fetch_assoc()) {
        $dataPic[] = $row;
        $totalLeads += (int) $row['jumlah'];
    }
}
$stmt->close();

$stmtDetail = $koneksi->prepare(
    "SELECT * FROM leads
     WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ?
     ORDER BY pic ASC, tanggal ASC"
);
$stmtDetail->bind_param('ii', $bulan, $tahun);
$stmtDetail->execute();
$detail = $stmtDetail->get_result();

$detailByPic = [];
if ($detail) {
    while ($row = $detail->fetch_assoc()) {
        $detailByPic[$row['pic']][] = $row;
    }
}
$stmtDetail->close();
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SanFai — Laporan Bulanan</title>
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

  .filter-wrap{
    max-width:1180px;
    margin:0 auto 22px;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:14px;
    box-shadow:0 6px 16px rgba(32,40,31,0.06);
    padding:18px 22px;
    display:flex;
    align-items:flex-end;
    gap:16px;
    flex-wrap:wrap;
  }

  .filter-field label{
    display:block;
    font-family:'IBM Plex Mono', monospace;
    font-size:11px;
    letter-spacing:0.06em;
    text-transform:uppercase;
    color:rgba(32,40,31,0.55);
    margin-bottom:6px;
  }

  .filter-field select{
    padding:11px 14px;
    background:var(--sand);
    border:1px solid var(--line);
    border-radius:10px;
    font-family:'Manrope', sans-serif;
    font-size:14px;
    color:var(--ink);
    outline:none;
    min-width:160px;
  }
  .filter-field select:focus{ border-color:var(--teal); background:var(--white); }

  .btn-tampilkan{
    background:var(--teal);
    color:#fff;
    border:none;
    padding:12px 22px;
    border-radius:10px;
    font-size:14px;
    font-weight:700;
    font-family:'Manrope', sans-serif;
    cursor:pointer;
    transition:background .2s;
  }
  .btn-tampilkan:hover{ background:var(--teal-deep); }

  .summary-row{
    max-width:1180px;
    margin:0 auto 26px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:16px;
  }

  .summary-card{
    background:var(--white);
    border:1px solid var(--line);
    border-radius:14px;
    padding:20px 22px;
    box-shadow:0 6px 16px rgba(32,40,31,0.06);
  }

  .summary-card .label{
    font-family:'IBM Plex Mono', monospace;
    font-size:11px;
    letter-spacing:0.08em;
    text-transform:uppercase;
    color:rgba(32,40,31,0.5);
    margin-bottom:8px;
  }

  .summary-card .value{
    font-family:'Fraunces', serif;
    font-weight:600;
    font-size:28px;
    color:var(--teal-deep);
  }

  .section-title{
    max-width:1180px;
    margin:0 auto 14px;
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    letter-spacing:0.1em;
    text-transform:uppercase;
    color:rgba(32,40,31,0.55);
  }

  .table-wrap{
    max-width:1180px;
    margin:0 auto 32px;
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
    padding:14px 20px;
    font-size:14px;
    border-bottom:1px solid var(--line);
    color:var(--ink);
  }

  tbody tr:last-child td{ border-bottom:none; }
  tbody tr:hover{ background:var(--sand); }

  .pic-name{ font-weight:700; }

  .badge-jumlah{
    display:inline-block;
    background:var(--sand);
    color:var(--teal-deep);
    font-family:'IBM Plex Mono', monospace;
    font-size:12.5px;
    font-weight:600;
    padding:4px 12px;
    border-radius:14px;
  }

  .detail-block{
    max-width:1180px;
    margin:0 auto 22px;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:18px;
    box-shadow:0 12px 30px rgba(32,40,31,0.08);
    overflow:hidden;
  }

  .detail-block summary{
    cursor:pointer;
    list-style:none;
    padding:18px 22px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-weight:700;
    font-size:15px;
    color:var(--teal-deep);
    background:var(--sand);
  }
  .detail-block summary::-webkit-details-marker{ display:none; }
  .detail-block summary .count{
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    font-weight:600;
    color:var(--teal);
  }

  .detail-block table{ margin-top:0; }
  .detail-block thead th{ background:var(--white); color:var(--teal-deep); border-bottom:1px solid var(--line); }

  .empty{
    padding:60px 20px;
    text-align:center;
    color:rgba(32,40,31,0.5);
    font-size:14.5px;
    max-width:1180px;
    margin:0 auto;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:18px;
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
    .table-wrap, .detail-block{ overflow-x:auto; }
    table{ min-width:520px; }
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
      <div class="eyebrow">Laporan</div>
      <h1>Laporan Bulanan per PIC</h1>
    </div>

    <form class="filter-wrap" method="GET" action="laporan.php">
      <div class="filter-field">
        <label for="bulan">Bulan</label>
        <select name="bulan" id="bulan">
          <?php foreach ($namaBulan as $angka => $nama): ?>
            <option value="<?php echo $angka; ?>" <?php echo $angka === $bulan ? 'selected' : ''; ?>><?php echo $nama; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-field">
        <label for="tahun">Tahun</label>
        <select name="tahun" id="tahun">
          <?php foreach ($daftarTahun as $thn): ?>
            <option value="<?php echo $thn; ?>" <?php echo $thn === $tahun ? 'selected' : ''; ?>><?php echo $thn; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn-tampilkan">Tampilkan</button>
    </form>

    <div class="summary-row">
      <div class="summary-card">
        <div class="label">Periode</div>
        <div class="value" style="font-size:20px;"><?php echo $namaBulan[$bulan] . ' ' . $tahun; ?></div>
      </div>
      <div class="summary-card">
        <div class="label">Total Leads</div>
        <div class="value"><?php echo $totalLeads; ?></div>
      </div>
      <div class="summary-card">
        <div class="label">Jumlah PIC Aktif</div>
        <div class="value"><?php echo count($dataPic); ?></div>
      </div>
    </div>

    <?php if (!empty($dataPic)): ?>

      <div class="section-title">Rekap per PIC</div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>PIC</th>
              <th>Jumlah Leads</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($dataPic as $row): ?>
            <tr>
              <td class="pic-name"><?php echo htmlspecialchars($row['pic']); ?></td>
              <td><span class="badge-jumlah"><?php echo (int) $row['jumlah']; ?> leads</span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="section-title">Detail Leads per PIC</div>
      <?php foreach ($detailByPic as $picNama => $listLeads): ?>
        <details class="detail-block">
          <summary>
            <span><?php echo htmlspecialchars($picNama); ?></span>
            <span class="count"><?php echo count($listLeads); ?> leads</span>
          </summary>
          <table>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No Telepon</th>
                <th>Minat</th>
                <th>Diinput Oleh</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($listLeads as $lead): ?>
              <tr>
                <td><?php echo htmlspecialchars(date('d M Y', strtotime($lead['tanggal']))); ?></td>
                <td><?php echo htmlspecialchars($lead['nama']); ?></td>
                <td><?php echo htmlspecialchars($lead['alamat']); ?></td>
                <td><?php echo htmlspecialchars($lead['no_telepon']); ?></td>
                <td><?php echo htmlspecialchars($lead['minat']); ?></td>
                <td><?php echo htmlspecialchars($lead['diinput_oleh'] ?? '-'); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </details>
      <?php endforeach; ?>

    <?php else: ?>
      <div class="empty">
        Belum ada data leads untuk periode <b><?php echo $namaBulan[$bulan] . ' ' . $tahun; ?></b>.
      </div>
    <?php endif; ?>

  </div>

  <footer>SanFai Property System · Panel Admin</footer>

</body>
</html>