<?php
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id_karyawan = $_POST['id'];
$pw_input = $_POST['pw'];

// Cek kecocokan menggunakan id_karyawan
$sql_karyawan = "SELECT * FROM karyawan WHERE id_karyawan = ? AND pw = ?";
$stmt_karyawan = $conn->prepare($sql_karyawan);
$stmt_karyawan->bind_param("ss", $id_karyawan, $pw_input);
$stmt_karyawan->execute();
$result_karyawan = $stmt_karyawan->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Absen</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; padding: 50px; text-align: center; }
        .box { max-width: 400px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .btn { display: inline-block; margin-top: 20px; background-color: #3498db; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; }
        .btn:hover { background-color: #2980b9; }
    </style>
</head>
<body>
<div class="box">
<?php
if ($result_karyawan->num_rows > 0) {
    
    $jam_sekarang = (int)date('H');
    
    if ($jam_sekarang < 7) {
        $batas_awal = date('Y-m-d 07:00:00', strtotime('-1 day'));
        $batas_akhir = date('Y-m-d 06:59:59');
    } else {
        $batas_awal = date('Y-m-d 07:00:00');
        $batas_akhir = date('Y-m-d 06:59:59', strtotime('+1 day'));
    }

    $sql_cek_absen = "SELECT * FROM absensi WHERE id_karyawan = ? AND waktu_absen >= ? AND waktu_absen <= ?";
    $stmt_cek = $conn->prepare($sql_cek_absen);
    $stmt_cek->bind_param("iss", $id_karyawan, $batas_awal, $batas_akhir);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();

    if ($result_cek->num_rows > 0) {
        echo "<h3 style='color: #e67e22;'>⚠️ Kamu sudah melakukan absensi untuk shift ini!</h3>";
    } else {
        $sql_insert = "INSERT INTO absensi (id_karyawan) VALUES (?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("i", $id_karyawan);
        
        if ($stmt_insert->execute()) {
            echo "<h3 style='color: #27ae60;'>✅ Absen Selesai! Terima kasih.</h3>";
            echo "<p>Waktu Absen: <b>" . date('Y-m-d H:i:s') . "</b></p>";
        } else {
            echo "<h3 style='color: #c0392b;'>❌ Terjadi kesalahan saat menyimpan absen.</h3>";
        }
    }

} else {
    echo "<h3 style='color: #c0392b;'>❌ Gagal! Password salah.</h3>";
    echo "<a href='absen.php?id=$id_karyawan' class='btn' style='background-color:#e74c3c;'>Coba Lagi</a><br>";
}
?>
<a href="index.php" class="btn">Kembali ke Halaman Utama</a>
</div>
</body>
</html>