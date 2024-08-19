<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Semua Produk</title>
    <link rel="stylesheet" href="style/check_table.css">
</head>
<body>
    <h1>Daftar Semua Produk</h1>

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

    // Cek apakah ada permintaan hapus
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        // Query untuk menghapus data
        $delete_sql = "DELETE FROM produk WHERE id_produk = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("s", $delete_id);

        if ($stmt->execute()) {
            echo "Produk berhasil dihapus.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Query SQL untuk mengambil semua data dari tabel produk
    $sql = "SELECT * FROM produk";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Menampilkan data dalam tabel HTML
        echo "<table>";
        echo "<thead><tr>";
        // Menampilkan header kolom berdasarkan nama kolom tabel
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            echo "<th>" . htmlspecialchars($field->name) . "</th>";
        }
        echo "<th>Aksi</th>"; // Kolom untuk tombol hapus
        echo "</tr></thead>";
        echo "<tbody>";
        // Menampilkan data baris per baris
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            // Menambahkan tombol hapus
            echo "<td><a href='?delete_id=" . htmlspecialchars($row['id_produk']) . "' class='delete-button' onclick='return confirm(\"Anda yakin ingin menghapus produk ini?\")'>Hapus</a></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "Tidak ada data produk.";
    }

    // Menutup koneksi
    $conn->close();
    ?>
</body>
</html>
