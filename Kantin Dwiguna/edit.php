<?php
require_once 'koneksi.php';
$conn  = conn();
$getid = $_GET['id'];

$nama_menu = $_POST['nama_menu'];
$kategori  = $_POST['kategori'];
$harga     = $_POST['harga'];
$deskripsi = $_POST['deskripsi'];

// Ambil nama gambar lama dari database
$old     = $conn->query("SELECT image FROM menu WHERE id = $getid");
$oldData = $old->fetch_assoc();
$image   = $oldData['image']; // default: pakai gambar lama

// Cek apakah ada gambar baru yang di-upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image = $_FILES['image']['name'];
    $tmp   = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, 'uploads/' . $image);
}

$sql  = "UPDATE menu SET nama_menu=?, kategori=?, harga=?, deskripsi=?, image=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssissi", $nama_menu, $kategori, $harga, $deskripsi, $image, $getid);

if ($stmt->execute()) {
    header("Location: lihat.php");
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
