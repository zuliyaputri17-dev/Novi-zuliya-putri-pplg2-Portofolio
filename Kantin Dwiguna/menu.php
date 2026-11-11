<?php
require_once 'koneksi.php';
$conn = conn();

$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if ($cari != '' && $filter_kategori != '') {
    $keyword = "%$cari%";
    $sql = "SELECT * FROM menu WHERE nama_menu LIKE ? AND kategori = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $keyword, $filter_kategori);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($cari != '') {
    $keyword = "%$cari%";
    $sql = "SELECT * FROM menu WHERE nama_menu LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($filter_kategori != '') {
    $sql = "SELECT * FROM menu WHERE kategori = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $filter_kategori);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM menu");
}

$rows = [];
while ($r = $result->fetch_assoc()) $rows[] = $r;
$total = count($rows);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Menu – Kantin Sekolah</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --orange:#FF6B1A;
  --orange-light:#FFF3EC;
  --orange-hover:#E05A0C;
  --sidebar-w:200px;
  --topbar-h:64px;
  --radius:12px;
  --shadow:0 2px 12px rgba(0,0,0,.07);
}

body{font-family:'Plus Jakarta Sans',sans-serif;background:#F5F6FA;color:#1a1a1a;display:flex;flex-direction:column;min-height:100vh;}

/* ── TOPBAR ── */
.topbar{
  position:fixed;top:0;left:0;right:0;height:var(--topbar-h);
  background:#fff;border-bottom:1px solid #eee;
  display:flex;align-items:center;padding:0 24px;z-index:100;gap:16px;
}
.topbar .hamburger{background:none;border:none;cursor:pointer;display:flex;flex-direction:column;gap:5px;}
.topbar .hamburger span{display:block;width:22px;height:2px;background:#555;border-radius:2px;}
.topbar .brand{display:flex;align-items:center;gap:10px;margin-right:auto;}
.topbar .brand .icon{width:38px;height:38px;background:var(--orange);border-radius:50%;display:flex;align-items:center;justify-content:center;}
.topbar .brand .icon svg{width:20px;height:20px;fill:#fff;}
.topbar .brand .name .top{font-weight:800;font-size:14px;color:#1a1a1a;letter-spacing:.5px;}
.topbar .brand .name .bot{font-weight:600;font-size:10px;color:var(--orange);letter-spacing:1.5px;text-transform:uppercase;}
.topbar .user{display:flex;align-items:center;gap:10px;}
.topbar .user .avatar{width:36px;height:36px;background:#e0c9b0;border-radius:50%;overflow:hidden;display:flex;align-items:center;justify-content:center;}
.topbar .user .avatar svg{width:22px;height:22px;fill:#8b6040;}
.topbar .user .info .name{font-size:13px;font-weight:700;color:#1a1a1a;}
.topbar .user .info .role{font-size:11px;color:#888;}
.topbar .user .chevron{color:#aaa;}
.topbar .user .chevron svg{width:16px;height:16px;}

/* ── SIDEBAR ── */
.sidebar{
  position:fixed;top:var(--topbar-h);left:0;bottom:0;width:var(--sidebar-w);
  background:#fff;border-right:1px solid #eee;
  padding:24px 0;display:flex;flex-direction:column;z-index:90;
}
.sidebar .nav-label{font-size:10px;font-weight:700;color:#bbb;letter-spacing:1.5px;text-transform:uppercase;padding:0 20px;margin-bottom:10px;}
.sidebar a{
  display:flex;align-items:center;gap:12px;
  padding:11px 20px;font-size:13px;font-weight:600;color:#666;
  text-decoration:none;border-radius:0;transition:background .15s,color .15s;
  border-left:3px solid transparent;
}
.sidebar a svg{width:18px;height:18px;flex-shrink:0;}
.sidebar a:hover{background:#fafafa;color:#333;}
.sidebar a.active{background:var(--orange-light);color:var(--orange);border-left-color:var(--orange);}
.sidebar .logout{margin-top:auto;}
.sidebar .logout a{color:#e05;background:none;}
.sidebar .logout a:hover{background:#fff0f0;}

/* ── MAIN ── */
.main{margin-left:var(--sidebar-w);margin-top:var(--topbar-h);padding:32px 28px;flex:1;}

/* page header */
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;}
.page-header h1{font-size:26px;font-weight:800;color:#1a1a1a;}
.page-header p{font-size:13px;color:#888;margin-top:3px;}
.btn-tambah{
  display:flex;align-items:center;gap:8px;
  background:var(--orange);color:#fff;border:none;
  padding:11px 22px;border-radius:10px;font-family:inherit;font-size:14px;font-weight:700;
  cursor:pointer;transition:background .2s;white-space:nowrap;
}
.btn-tambah:hover{background:var(--orange-hover);}

/* search bar */
.search-row{display:flex;gap:12px;margin-bottom:20px;}
.search-wrap{
  position:relative;flex:1;max-width:400px;
}
.search-wrap svg{position:absolute;left:13px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#aaa;}
.search-wrap input{
  width:100%;padding:11px 14px 11px 40px;
  border:1.5px solid #e8e8e8;border-radius:10px;
  font-family:inherit;font-size:13px;color:#333;background:#fff;outline:none;
  transition:border-color .2s;
}
.search-wrap input:focus{border-color:var(--orange);}
.select-wrap{position:relative;}
.select-wrap svg{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#aaa;pointer-events:none;}
.select-wrap select{
  appearance:none;padding:11px 40px 11px 16px;
  border:1.5px solid #e8e8e8;border-radius:10px;
  font-family:inherit;font-size:13px;color:#333;background:#fff;outline:none;
  cursor:pointer;transition:border-color .2s;
}
.select-wrap select:focus{border-color:var(--orange);}

/* table card */
.card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
thead tr{border-bottom:1.5px solid #f0f0f0;}
thead th{padding:14px 16px;font-size:12px;font-weight:700;color:#888;text-align:left;white-space:nowrap;}
tbody tr{border-bottom:1px solid #f7f7f7;transition:background .15s;}
tbody tr:last-child{border-bottom:none;}
tbody tr:hover{background:#fafafa;}
tbody td{padding:14px 16px;font-size:13px;color:#333;vertical-align:middle;}
.td-no{color:#aaa;font-weight:600;width:40px;}
.td-img img{width:56px;height:56px;border-radius:10px;object-fit:cover;display:block;}
.td-img .no-img{width:56px;height:56px;border-radius:10px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;}
.td-img .no-img svg{width:24px;height:24px;color:#ccc;}
.td-name{font-weight:700;font-size:14px;}
.badge{display:inline-block;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;}
.badge.makanan{background:#FFF3EC;color:#E8510A;}
.badge.minuman{background:#E8F5E9;color:#2E7D32;}
.badge.snack{background:#FFF8E1;color:#F57F17;}
.td-aksi{display:flex;gap:8px;}
.btn-edit,.btn-del{
  width:34px;height:34px;border-radius:8px;border:1.5px solid;
  display:flex;align-items:center;justify-content:center;cursor:pointer;background:#fff;
  transition:background .15s;
}
.btn-edit{border-color:#4285F4;color:#4285F4;}
.btn-edit:hover{background:#EAF1FF;}
.btn-del{border-color:#e05;color:#e05;}
.btn-del:hover{background:#fff0f0;}
.btn-edit svg,.btn-del svg{width:15px;height:15px;}

/* pagination */
.table-footer{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-top:1px solid #f0f0f0;}
.table-footer .info{font-size:12px;color:#888;}
.pagination{display:flex;gap:6px;}
.page-btn{
  width:32px;height:32px;border-radius:8px;border:1.5px solid #e8e8e8;
  background:#fff;font-family:inherit;font-size:13px;font-weight:600;color:#555;
  cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.15s;
}
.page-btn:hover{border-color:var(--orange);color:var(--orange);}
.page-btn.active{background:var(--orange);border-color:var(--orange);color:#fff;}

/* ── SIDE PANEL (Tambah / Delete) ── */
.panel-overlay{display:none;position:fixed;inset:0;z-index:200;}
.panel-overlay.show{display:block;}
.side-panel{
  position:fixed;top:0;right:0;bottom:0;width:320px;
  background:#fff;box-shadow:-4px 0 30px rgba(0,0,0,.1);
  padding:28px 24px;overflow-y:auto;z-index:201;
  transform:translateX(100%);transition:transform .25s ease;
}
.side-panel.show{transform:translateX(0);}
.panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
.panel-header h3{font-size:17px;font-weight:800;}
.panel-close{background:none;border:none;cursor:pointer;display:flex;padding:4px;}
.panel-close svg{width:20px;height:20px;color:#888;}
.panel-close:hover svg{color:#333;}

.form-field{margin-bottom:16px;}
.form-field label{display:block;font-size:13px;font-weight:600;color:#333;margin-bottom:6px;}
.form-field input,.form-field select,.form-field textarea{
  width:100%;padding:10px 14px;
  border:1.5px solid #e8e8e8;border-radius:10px;
  font-family:inherit;font-size:13px;color:#333;background:#fafafa;outline:none;
  transition:border-color .2s;
}
.form-field input:focus,.form-field select:focus,.form-field textarea:focus{border-color:var(--orange);background:#fff;}
.form-field textarea{resize:vertical;min-height:80px;}
.harga-wrap{display:flex;align-items:center;border:1.5px solid #e8e8e8;border-radius:10px;overflow:hidden;background:#fafafa;transition:border-color .2s;}
.harga-wrap:focus-within{border-color:var(--orange);background:#fff;}
.harga-wrap .prefix{padding:10px 12px;font-size:13px;font-weight:700;color:#888;border-right:1px solid #eee;}
.harga-wrap input{border:none;background:transparent;padding:10px 12px;width:100%;}
.harga-wrap input:focus{border:none;background:transparent;}

.upload-box{
  border:1.5px dashed #ddd;border-radius:10px;padding:20px;
  display:flex;flex-direction:column;align-items:center;gap:6px;cursor:pointer;
  transition:border-color .2s;
}
.upload-box:hover{border-color:var(--orange);}
.upload-box svg{width:28px;height:28px;color:#ccc;}
.upload-box .up-title{font-size:13px;font-weight:600;color:#555;}
.upload-box .up-sub{font-size:11px;color:#aaa;}
.upload-box input{display:none;}

.panel-actions{display:flex;gap:10px;margin-top:8px;}
.btn-batal{flex:1;padding:12px;border:1.5px solid #e8e8e8;border-radius:10px;background:#fff;font-family:inherit;font-size:14px;font-weight:700;color:#555;cursor:pointer;transition:.15s;}
.btn-batal:hover{border-color:#ccc;color:#333;}
.btn-simpan{flex:1;padding:12px;border:none;border-radius:10px;background:var(--orange);font-family:inherit;font-size:14px;font-weight:700;color:#fff;cursor:pointer;transition:background .15s;}
.btn-simpan:hover{background:var(--orange-hover);}

/* delete confirm panel */
.delete-panel{
  position:fixed;bottom:0;right:0;width:320px;
  background:#fff;border-radius:12px 12px 0 0;
  box-shadow:0 -4px 30px rgba(0,0,0,.1);
  padding:24px;z-index:202;
  transform:translateY(100%);transition:transform .25s ease;
}
.delete-panel.show{transform:translateY(0);}
.del-icon-wrap{width:48px;height:48px;background:#FFF0F0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:12px;}
.del-icon-wrap svg{width:24px;height:24px;color:#e05;}
.delete-panel h3{font-size:16px;font-weight:800;margin-bottom:6px;}
.delete-panel p{font-size:13px;color:#666;line-height:1.5;margin-bottom:20px;}
.delete-panel p strong{color:#1a1a1a;}
.del-actions{display:flex;gap:10px;}
.btn-del-cancel{flex:1;padding:12px;border:1.5px solid #e8e8e8;border-radius:10px;background:#fff;font-family:inherit;font-size:14px;font-weight:700;color:#555;cursor:pointer;}
.btn-del-confirm{flex:1;padding:12px;border:none;border-radius:10px;background:#e05;font-family:inherit;font-size:14px;font-weight:700;color:#fff;cursor:pointer;}
.btn-del-confirm:hover{background:#c00;}
</style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
  <button class="hamburger" onclick="toggleSidebar()">
    <span></span><span></span><span></span>
  </button>
  <div class="brand">
    <div class="icon">
      <svg viewBox="0 0 24 24"><path d="M11 3v7H9V3H7v7H5V3H3v8a4 4 0 0 0 3.5 3.97V21h2v-6.03A4 4 0 0 0 12 11V3h-1zm8-1c-1.66 0-3 1.34-3 3v5h2v11h2V3a2 2 0 0 0-1-1.73V2z"/></svg>
    </div>
    <div class="name">
      <div class="top">KANTIN</div>
      <div class="bot">SEKOLAH</div>
    </div>
  </div>
  <div class="user">
    <div class="avatar">
      <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
    </div>
    <div class="info">
      <div class="name">Unknown</div>
      <div class="role">Guest</div>
    </div>
    <span class="chevron"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg></span>
  </div>
</header>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="nav-label">Menu Navigasi</div>
  <a href="index.php">
    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    Dashboard
  </a>
  <a href="lihat.php" class="active">
    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h7v7H3zm0 11h7v7H3zm11-11h7v7h-7zm0 11h7v7h-7z"/></svg>
    Data Menu
  </a>

 
  
</aside>

<!-- MAIN CONTENT -->
<main class="main">
  <div class="page-header">
    <div>
      <h1>Data Menu</h1>
      <p>Lihat menu makanan dan minuman kantin sekolah.</p>
    </div>
  </div>

  <!-- Search -->
  <form method="GET" action="">
  <div class="search-row">
    <div class="search-wrap">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
      <input type="text" name="cari" placeholder="Cari menu..." value="<?= htmlspecialchars($cari) ?>">
    </div>
    <div class="select-wrap">
      <select name="kategori" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        <option value="Makanan"  <?= $filter_kategori=='Makanan'  ?'selected':'' ?>>Makanan</option>
        <option value="Minuman"  <?= $filter_kategori=='Minuman'  ?'selected':'' ?>>Minuman</option>
        <option value="Snack"    <?= $filter_kategori=='Snack'    ?'selected':'' ?>>Snack</option>
      </select>
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
    </div>
    <button type="submit" style="display:none">Cari</button>
  </div>
  </form>

  <!-- Table -->
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama Menu</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Deskripsi</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="7" style="text-align:center;color:#aaa;padding:32px;">Menu tidak ditemukan.</td></tr>
        <?php else: ?>
        <?php foreach ($rows as $i => $data): ?>
          <tr>
            <td class="td-no"><?= $i+1 ?></td>
            <td class="td-img">
              <?php if (!empty($data['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($data['image']) ?>" alt="">
              <?php else: ?>
                <div class="no-img"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg></div>
              <?php endif; ?>
            </td>
            <td class="td-name"><?= htmlspecialchars($data['nama_menu']) ?></td>
            <td>
              <?php
                $kat = strtolower($data['kategori']);
                $cls = $kat === 'makanan' ? 'makanan' : ($kat === 'minuman' ? 'minuman' : 'snack');
              ?>
              <span class="badge <?= $cls ?>"><?= htmlspecialchars($data['kategori']) ?></span>
            </td>
            <td>Rp <?= number_format($data['harga'],0,',','.') ?></td>
            <td style="max-width:180px;line-height:1.5;"><?= htmlspecialchars($data['deskripsi']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
   
  </div>
</main>


</body>
</html>