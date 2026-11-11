<?php
require_once 'koneksi.php'; 
$conn = conn();

$getid = $_GET['id'];

$sql = "DELETE FROM menu WHERE id = $getid"; 
$result = $conn->query($sql);

if ($result == 1) {
    echo "<script>
        alert('Berhasil dihapus!');
        location.href = 'lihat.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus!');
        location.href = 'lihat.php';
    </script>";
}

$conn->close();
?>