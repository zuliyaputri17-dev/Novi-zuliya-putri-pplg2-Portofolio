<?php 
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Menentukan rentang waktu shift hari ini untuk pengecekan status
$jam_sekarang = (int)date('H');
if ($jam_sekarang < 7) {
    $batas_awal = date('Y-m-d 07:00:00', strtotime('-1 day'));
    $batas_akhir = date('Y-m-d 06:59:59');
} else {
    $batas_awal = date('Y-m-d 07:00:00');
    $batas_akhir = date('Y-m-d 06:59:59', strtotime('+1 day'));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Absensi Karyawan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        .container {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn-absen {
            background-color: #2ecc71;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn-absen:hover {
            background-color: #27ae60;
        }
        .sudah-absen {
            color: #27ae60;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Data Absensi Karyawan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Status / Jam Absen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM karyawan";
            $result = $conn->query($sql);
            $no = 1;

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $id_karyawan = $row["id_karyawan"];
                    $nama = $row["nama"];

                    // Cek apakah karyawan ini sudah absen di rentang waktu shift aktif
                    $sql_cek = "SELECT waktu_absen FROM absensi WHERE id_karyawan = ? AND waktu_absen >= ? AND waktu_absen <= ?";
                    $stmt = $conn->prepare($sql_cek);
                    $stmt->bind_param("iss", $id_karyawan, $batas_awal, $batas_akhir);
                    $stmt->execute();
                    $result_cek = $stmt->get_result();

                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($nama) . "</td>";

                    if ($result_cek->num_rows > 0) {
                        // Jika sudah absen
                        $data_absen = $result_cek->fetch_assoc();
                        $jam_absen = date('H:i:s', strtotime($data_absen['waktu_absen']));
                        echo "<td><span class='sudah-absen'>✓ Hadir</span> <br><small style='color:gray;'>Pukul: $jam_absen</small></td>";
                        echo "<td>-</td>";
                    } else {
                        // Jika belum absen
                        echo "<td><span style='color: #e74c3c;'>Belum Absen</span></td>";
                        echo "<td><a href='absen.php?id=" . $id_karyawan . "' class='btn-absen'>Absen Sekarang</a></td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada data karyawan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>