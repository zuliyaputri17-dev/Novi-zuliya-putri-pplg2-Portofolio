<?php
require_once 'konek.php';

$koneksi = conn();

$namaBulanIndo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// Daftar PIC untuk dropdown
$picResult = $koneksi->query("SELECT DISTINCT pic FROM leads WHERE pic IS NOT NULL AND pic <> '' ORDER BY pic ASC");
$daftarPic = [];
if ($picResult) {
    while ($r = $picResult->fetch_assoc()) {
        $daftarPic[] = $r['pic'];
    }
}

// Daftar Bulan (berdasarkan kolom tanggal) untuk dropdown, format YYYY-MM
$bulanResult = $koneksi->query("SELECT DISTINCT DATE_FORMAT(tanggal, '%Y-%m') AS ym FROM leads WHERE tanggal IS NOT NULL ORDER BY ym DESC");
$daftarBulan = [];
if ($bulanResult) {
    while ($r = $bulanResult->fetch_assoc()) {
        if ($r['ym']) {
            $tahun = (int) substr($r['ym'], 0, 4);
            $bln = (int) substr($r['ym'], 5, 2);
            $daftarBulan[] = [
                'value' => $r['ym'],
                'label' => ($namaBulanIndo[$bln] ?? $bln) . ' ' . $tahun
            ];
        }
    }
}

$picDipilih = isset($_GET['pic']) ? trim($_GET['pic']) : '';
$bulanDipilih = isset($_GET['bulan']) ? trim($_GET['bulan']) : '';

// Validasi format bulan (YYYY-MM) supaya aman dipakai di query
if ($bulanDipilih !== '' && !preg_match('/^\d{4}-\d{2}$/', $bulanDipilih)) {
    $bulanDipilih = '';
}

$where = [];
$params = [];
$types = '';

if ($picDipilih !== '') {
    $where[] = 'pic = ?';
    $params[] = $picDipilih;
    $types .= 's';
}

if ($bulanDipilih !== '') {
    $where[] = "DATE_FORMAT(tanggal, '%Y-%m') = ?";
    $params[] = $bulanDipilih;
    $types .= 's';
}

$sql = "SELECT nama, no_telepon, pic, tanggal FROM leads";
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY tanggal DESC, nama ASC";

$stmt = $koneksi->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SanFai — Kontak Leads</title>
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

  .filter-wrap{
    max-width:1180px;
    margin:0 auto 18px;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:14px;
    box-shadow:0 6px 16px rgba(32,40,31,0.06);
    padding:18px 22px;
    display:flex;
    align-items:flex-end;
    gap:14px;
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
    min-width:200px;
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

  .btn-salin{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:var(--gold);
    color:#fff;
    border:none;
    padding:12px 20px;
    border-radius:10px;
    font-size:14px;
    font-weight:700;
    font-family:'Manrope', sans-serif;
    cursor:pointer;
    transition:background .2s;
    margin-left:auto;
  }
  .btn-salin:hover{ background:#6E5327; }
  .btn-salin.copied{ background:var(--teal); }

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

  .pic-tag{
    display:inline-block;
    background:var(--sand);
    color:var(--teal-deep);
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    font-weight:600;
    padding:4px 12px;
    border-radius:14px;
  }

  .empty{
    padding:60px 20px;
    text-align:center;
    color:rgba(32,40,31,0.5);
    font-size:14.5px;
  }

  .info-row{
    max-width:1180px;
    margin:0 auto 14px;
    font-family:'IBM Plex Mono', monospace;
    font-size:12px;
    color:rgba(32,40,31,0.55);
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
    table{ min-width:520px; }
    .btn-salin{ margin-left:0; width:100%; justify-content:center; }
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
        <div class="eyebrow">Kontak Leads</div>
        <h1>Nama, No Telepon &amp; PIC</h1>
      </div>
    </div>

    <form class="filter-wrap" method="GET" action="kontak.php">
      <div class="filter-field">
        <label for="pic">Pilih PIC</label>
        <select name="pic" id="pic">
          <option value="">Semua PIC</option>
          <?php foreach ($daftarPic as $p): ?>
            <option value="<?php echo htmlspecialchars($p); ?>" <?php echo $picDipilih === $p ? 'selected' : ''; ?>><?php echo htmlspecialchars($p); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-field">
        <label for="bulan">Pilih Bulan</label>
        <select name="bulan" id="bulan">
          <option value="">Semua Bulan</option>
          <?php foreach ($daftarBulan as $b): ?>
            <option value="<?php echo htmlspecialchars($b['value']); ?>" <?php echo $bulanDipilih === $b['value'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($b['label']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn-tampilkan">Tampilkan</button>
      <button type="button" class="btn-salin" id="btnSalin" onclick="salinData()">
        📋 Salin Semua Data
      </button>
    </form>

    <div class="info-row">
      <?php
        $labelBulan = '';
        foreach ($daftarBulan as $b) {
            if ($b['value'] === $bulanDipilih) {
                $labelBulan = $b['label'];
                break;
            }
        }
        $keterangan = [];
        if ($picDipilih !== '') {
            $keterangan[] = 'PIC "' . htmlspecialchars($picDipilih) . '"';
        }
        if ($bulanDipilih !== '' && $labelBulan !== '') {
            $keterangan[] = 'bulan "' . htmlspecialchars($labelBulan) . '"';
        }
      ?>
      <?php if (!empty($keterangan)): ?>
        Menampilkan kontak untuk <?php echo implode(' & ', $keterangan); ?> — <?php echo count($rows); ?> data.
      <?php else: ?>
        Menampilkan seluruh kontak — <?php echo count($rows); ?> data.
      <?php endif; ?>
    </div>

    <div class="table-wrap">
      <?php if (!empty($rows)): ?>
      <table id="tabelKontak">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>No Telepon</th>
            <th>PIC</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
          <?php
            $tglTampil = '-';
            if (!empty($row['tanggal'])) {
                $ts = strtotime($row['tanggal']);
                if ($ts !== false) {
                    $tglTampil = date('d', $ts) . ' ' . ($namaBulanIndo[(int) date('n', $ts)] ?? '') . ' ' . date('Y', $ts);
                }
            }
          ?>
          <tr>
            <td><?php echo htmlspecialchars($tglTampil); ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
            <td><span class="pic-tag"><?php echo htmlspecialchars($row['pic']); ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <div class="empty">Tidak ada data kontak untuk ditampilkan.</div>
      <?php endif; ?>
    </div>
  </div>

  <footer>SanFai Property System · Panel Admin</footer>

  <script>
    function salinData() {
      const rows = document.querySelectorAll('#tabelKontak tbody tr');
      if (!rows.length) {
        alert('Tidak ada data untuk disalin.');
        return;
      }

      let teks = '';
      rows.forEach(function (tr) {
        const cells = tr.querySelectorAll('td');
        const nama = cells[1].innerText.trim();
        const telepon = cells[2].innerText.trim();
        teks += nama + ' - ' + telepon + '\n';
      });

      navigator.clipboard.writeText(teks).then(function () {
        const btn = document.getElementById('btnSalin');
        const teksAsli = btn.innerHTML;
        btn.innerHTML = '✅ Tersalin!';
        btn.classList.add('copied');
        setTimeout(function () {
          btn.innerHTML = teksAsli;
          btn.classList.remove('copied');
        }, 1800);
      }).catch(function () {
        alert('Gagal menyalin otomatis. Berikut datanya, silakan salin manual:\n\n' + teks);
      });
    }
  </script>

</body>
</html>