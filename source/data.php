<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Baju</title>
    <link rel="stylesheet" href="style/data.css">
</head>
<body>
    <h1>Data Gudang Baju</h1>
    <table id="dataTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Baju</th>
                <th>Kategori</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Konfigurasi koneksi database
            $servername = "localhost";
            $username = "root";  // Ganti dengan username database Anda
            $password = "";  // Ganti dengan password database Anda
            $dbname = "gudang_baju";  // Ganti dengan nama database Anda

            // Membuat koneksi
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Memeriksa koneksi
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query untuk mendapatkan data
            $sql = "SELECT id, nama_baju, kategori, harga FROM produk"; // not Baju
            $result = $conn->query($sql);

            // Menampilkan data di tabel
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nama_baju']}</td>
                            <td>{$row['kategori']}</td>
                            <td>{$row['stok']}</td>
                            <td>{$row['harga']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
            }

            // Menutup koneksi
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
