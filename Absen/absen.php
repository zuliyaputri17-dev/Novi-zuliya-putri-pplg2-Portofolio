<?php 
include 'koneksi.php';
$id = $_GET['id'];

$sql = "SELECT nama FROM karyawan WHERE id_karyawan = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Data karyawan tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Absen Karyawan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 50px;
        }
        .card {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 22px;
        }
        label {
            font-weight: 600;
            color: #555;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #7f8c8d;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Konfirmasi Absen</h2>
    <p>Karyawan: <b><?php echo htmlspecialchars($row['nama']); ?></b></p>
    
    <form action="proses_absen.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label>Masukkan Password:</label>
        <input type="password" name="pw" placeholder="Masukkan password akun..." required>
        <button type="submit">Kirim Absen</button>
    </form>
    
    <a href="index.php" class="back-link">&larr; Kembali ke Daftar</a>
</div>

</body>
</html>