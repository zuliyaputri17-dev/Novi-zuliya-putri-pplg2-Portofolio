<?php
include 'koneksi.php';
$conn = conn();

if (isset($_POST['submit'])) {
    $nama_menu = $_POST['nama_menu'];
    $kategori  = $_POST['kategori'];
    $harga     = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Cek apakah file gambar di-upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_file = $_FILES['image']['name'];
        $picture    = $_FILES['image']['tmp_name'];
        move_uploaded_file($picture, 'uploads/' . $image_file);
    } else {
        $image_file = ''; // Tidak ada gambar yang di-upload
    }

    // Ganti 'menu' dengan nama tabel yang benar di database kamu
    $sql  = "INSERT INTO menu (nama_menu, kategori, deskripsi, harga, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $nama_menu, $kategori, $deskripsi, $harga, $image_file);
    $stmt->execute();

    echo "<script>
        alert('Menu berhasil ditambahkan!');
        location.href = 'lihat.php';
    </script>";
}
?>