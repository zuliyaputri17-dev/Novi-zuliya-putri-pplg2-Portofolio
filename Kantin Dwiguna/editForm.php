<?php
require_once 'koneksi.php';
$getid = $_GET['id'];
$conn  = conn();

$sql    = "SELECT * FROM menu WHERE id = $getid";
$result = $conn->query($sql);
$data   = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Menu – Kantin Sekolah</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

body{
  font-family:'Plus Jakarta Sans',sans-serif;
  background:#F5F6FA;
  color:#1a1a1a;
  min-height:100vh;
}

/* ── TOPBAR ── */
.topbar{
  background:#fff;
  border-bottom:1px solid #eee;
  height:64px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 40px;
}

.brand{display:flex;align-items:center;gap:10px;}
.brand .icon{
  width:40px;height:40px;
  background:#FF6B1A;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
}
.brand .icon svg{width:20px;height:20px;fill:#fff;}
.brand .name .top{font-weight:800;font-size:15px;color:#1a1a1a;letter-spacing:.5px;}
.brand .name .bot{font-weight:700;font-size:11px;color:#FF6B1A;letter-spacing:1.5px;}

.topbar .admin{font-size:14px;font-weight:700;color:#1a1a1a;}

/* ── PAGE CONTENT ── */
.page{max-width:900px;margin:40px auto;padding:0 24px;}

.page-title{
  font-size:26px;font-weight:800;color:#1a1a1a;
  margin-bottom:20px;
}

/* ── CARD ── */
.card{
  background:#fff;
  border-radius:14px;
  box-shadow:0 2px 16px rgba(0,0,0,.07);
  padding:32px 36px;
}

.divider{height:1px;background:#f0f0f0;margin-bottom:28px;}

/* ── FORM FIELDS ── */
.field{margin-bottom:22px;}
.field label{
  display:block;
  font-size:14px;font-weight:700;color:#1a1a1a;
  margin-bottom:8px;
}

.field input[type="text"],
.field input[type="number"],
.field select,
.field textarea{
  width:100%;
  padding:13px 16px;
  border:1.5px solid #E0E0E0;
  border-radius:10px;
  font-family:inherit;
  font-size:14px;
  color:#1a1a1a;
  background:#fff;
  outline:none;
  transition:border-color .2s,box-shadow .2s;
  appearance:none;
}

.field input:focus,
.field select:focus,
.field textarea:focus{
  border-color:#FF6B1A;
  box-shadow:0 0 0 3px rgba(255,107,26,.08);
}

.field input::placeholder,
.field textarea::placeholder{color:#bbb;}

/* select wrapper */
.select-wrap{position:relative;}
.select-wrap svg{
  position:absolute;right:14px;top:50%;transform:translateY(-50%);
  width:18px;height:18px;color:#888;pointer-events:none;
}

/* textarea */
.field textarea{resize:vertical;min-height:90px;line-height:1.6;}

/* ── IMAGE UPLOAD ── */
.upload-area{
  border:2px dashed #E0E0E0;
  border-radius:12px;
  padding:20px;
  text-align:center;
  cursor:pointer;
  transition:border-color .2s, background .2s;
  position:relative;
}
.upload-area:hover{border-color:#FF6B1A;background:#FFF9F6;}
.upload-area input[type="file"]{
  position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;
}
.upload-area svg{width:32px;height:32px;color:#ccc;margin-bottom:8px;}
.upload-area .up-title{font-size:14px;font-weight:600;color:#555;}
.upload-area .up-sub{font-size:12px;color:#aaa;margin-top:4px;}

/* current image preview */
.current-img-wrap{
  display:flex;align-items:center;gap:16px;
  background:#F9F9F9;border-radius:12px;
  padding:14px 16px;margin-bottom:12px;
  border:1.5px solid #EFEFEF;
}
.current-img-wrap img{
  width:64px;height:64px;border-radius:8px;object-fit:cover;flex-shrink:0;
}
.current-img-info .label{font-size:12px;color:#aaa;font-weight:600;margin-bottom:2px;}
.current-img-info .filename{font-size:13px;font-weight:700;color:#333;word-break:break-all;}
.current-img-info .hint{font-size:11px;color:#bbb;margin-top:4px;}

/* preview baru */
#preview-wrap{display:none;margin-bottom:12px;}
#preview-wrap img{
  width:100%;max-height:200px;object-fit:cover;
  border-radius:10px;border:1.5px solid #eee;
}
#preview-name{font-size:12px;color:#888;margin-top:6px;}

/* bottom divider */
.bottom-divider{height:1px;background:#f0f0f0;margin:8px 0 24px;}

/* actions */
.actions{display:flex;gap:12px;}

.btn-simpan{
  padding:13px 32px;
  background:#FF6B1A;color:#fff;
  border:none;border-radius:10px;
  font-family:inherit;font-size:14px;font-weight:700;
  cursor:pointer;transition:background .15s;
}
.btn-simpan:hover{background:#E05A0C;}

.btn-batal{
  padding:13px 28px;
  background:#fff;color:#555;
  border:1.5px solid #ddd;border-radius:10px;
  font-family:inherit;font-size:14px;font-weight:700;
  text-decoration:none;display:inline-flex;align-items:center;
  cursor:pointer;transition:border-color .15s,color .15s;
}
.btn-batal:hover{border-color:#bbb;color:#1a1a1a;}
</style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
  <div class="brand">
    <div class="icon">
      <svg viewBox="0 0 24 24"><path d="M11 3v7H9V3H7v7H5V3H3v8a4 4 0 0 0 3.5 3.97V21h2v-6.03A4 4 0 0 0 12 11V3h-1zm8-1c-1.66 0-3 1.34-3 3v5h2v11h2V3a2 2 0 0 0-1-1.73V2z"/></svg>
    </div>
    <div class="name">
      <div class="top">KANTIN <span style="color:#FF6B1A;">SEKOLAH</span></div>
    </div>
  </div>
  <div class="admin">Admin Kantin</div>
</header>

<!-- PAGE -->
<div class="page">
  <h1 class="page-title">Edit Menu</h1>

  <div class="card">
    <div class="divider"></div>

    <!-- enctype wajib untuk upload file -->
    <form action="edit.php?id=<?= $data['id'] ?>" method="POST" enctype="multipart/form-data">

      <div class="field">
        <label>Nama Menu</label>
        <input type="text" name="nama_menu"
               value="<?= htmlspecialchars($data['nama_menu']) ?>"
               placeholder="Masukkan nama menu" required>
      </div>

      <div class="field">
        <label>Kategori</label>
        <div class="select-wrap">
          <select name="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Makanan" <?= $data['kategori']=='Makanan'?'selected':'' ?>>Makanan</option>
            <option value="Minuman" <?= $data['kategori']=='Minuman'?'selected':'' ?>>Minuman</option>
            <option value="Snack"   <?= $data['kategori']=='Snack'  ?'selected':'' ?>>Snack</option>
          </select>
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
        </div>
      </div>

      <div class="field">
        <label>Harga</label>
        <input type="number" name="harga"
               value="<?= htmlspecialchars($data['harga']) ?>"
               placeholder="Masukkan harga" required>
      </div>

      <div class="field">
        <label>Deskripsi</label>
        <textarea name="deskripsi" placeholder="Masukkan deskripsi menu"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
      </div>

      <!-- ── GAMBAR ── -->
      <div class="field">
        <label>Gambar</label>

        <!-- Tampilkan gambar saat ini jika ada -->
        <?php if (!empty($data['image'])): ?>
        <div class="current-img-wrap">
          <img src="uploads/<?= htmlspecialchars($data['image']) ?>" alt="Gambar saat ini">
          <div class="current-img-info">
            <div class="label">Gambar saat ini</div>
            <div class="filename"><?= htmlspecialchars($data['image']) ?></div>
            <div class="hint">Pilih gambar baru di bawah untuk menggantinya</div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Preview gambar baru sebelum submit -->
        <div id="preview-wrap">
          <img id="preview-img" src="" alt="Preview">
          <div id="preview-name"></div>
        </div>

        <!-- Area upload -->
        <label class="upload-area">
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
          </svg>
          <div class="up-title"><?= !empty($data['image']) ? 'Klik untuk ganti gambar' : 'Klik untuk pilih gambar' ?></div>
          <div class="up-sub">JPG, PNG · Maks. 2MB · Kosongkan jika tidak ingin mengganti</div>
          <input type="file" name="image" id="image" accept="image/*">
        </label>
      </div>

      <div class="bottom-divider"></div>

      <div class="actions">
        <button type="submit" class="btn-simpan">Simpan</button>
        <a href="lihat.php" class="btn-batal">Batal</a>
      </div>

    </form>
  </div>
</div>

<script>
// Preview gambar baru sebelum submit
document.getElementById('image').addEventListener('change', function() {
  const file = this.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById('preview-img').src = e.target.result;
    document.getElementById('preview-name').textContent = '📎 ' + file.name;
    document.getElementById('preview-wrap').style.display = 'block';
  };
  reader.readAsDataURL(file);
});
</script>

</body>
</html>