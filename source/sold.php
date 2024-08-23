<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk Terjual</title>
    <link rel="stylesheet" href="style/sold.css">
</head>
<body>
    <div class="container">
        <h1 class="page-title">Daftar Produk Terjual</h1>

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

        // Cek apakah ada permintaan restore
        if (isset($_GET['restore_id'])) {
            $restore_id = $_GET['restore_id'];

            // Mulai transaksi
            $conn->begin_transaction();

            try {
                // Query untuk mendapatkan data produk dari tabel sold
                $select_sql = "SELECT id_produk, id_jenis, id_kategori, nama_produk, harga_produk, link_ig
                               FROM sold
                               WHERE id_produk = ?";
                $stmt = $conn->prepare($select_sql);
                $stmt->bind_param("s", $restore_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    // Masukkan data ke tabel produk
                    $insert_sql = "INSERT INTO produk (id_produk, id_jenis, id_kategori, nama_produk, harga_produk, link_ig) 
                                   VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("ssssis", $row['id_produk'], $row['id_jenis'], $row['id_kategori'], 
                                      $row['nama_produk'], $row['harga_produk'], $row['link_ig']);

                    if ($stmt->execute()) {
                        // Hapus produk dari tabel sold
                        $delete_sql = "DELETE FROM sold WHERE id_produk = ?";
                        $stmt = $conn->prepare($delete_sql);
                        $stmt->bind_param("s", $restore_id);

                        if ($stmt->execute()) {
                            // Hapus ID dari tabel produk_terhapus
                            $delete_terhapus_sql = "DELETE FROM produk_terhapus WHERE id_produk = ?";
                            $stmt = $conn->prepare($delete_terhapus_sql);
                            $stmt->bind_param("s", $restore_id);
                            $stmt->execute(); // Eksekusi penghapusan dari produk_terhapus

                            // Jika semua berhasil, commit transaksi
                            $conn->commit();
                            echo "<p>Produk berhasil dikembalikan ke tabel produk dan dihapus dari tabel produk_terhapus.</p>";
                        } else {
                            // Jika penghapusan dari tabel sold gagal, batalkan transaksi
                            $conn->rollback();
                            echo "<p>Error DELETE: " . $stmt->error . "</p>";
                        }
                    } else {
                        // Jika insert gagal, batalkan transaksi
                        $conn->rollback();
                        echo "<p>Error INSERT: " . $stmt->error . "</p>";
                    }
                } else {
                    echo "<p>Produk tidak ditemukan.</p>";
                }

                $stmt->close();
            } catch (Exception $e) {
                // Jika ada kesalahan lain, batalkan transaksi
                $conn->rollback();
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        }

        // Cek apakah ada permintaan delete
        if (isset($_GET['delete_id'])) {
            $delete_id = $_GET['delete_id'];

            // Hapus produk dari tabel sold
            $delete_sql = "DELETE FROM sold WHERE id_produk = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("s", $delete_id);

            if ($stmt->execute()) {
                echo "<p>Produk berhasil dihapus dari tabel sold.</p>";
            } else {
                echo "<p>Error DELETE: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }

        // Query SQL untuk menampilkan produk yang terjual
        $sql = "SELECT 
                    sold.id_produk AS 'Kode',
                    sold.nama_produk AS 'Nama Produk',
                    sold.harga_produk AS 'Harga (Rp.)',
                    sold.link_ig AS 'Link'
                FROM 
                    sold";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Menampilkan data dalam tabel HTML
            echo "<table>";
            echo "<thead><tr>";
            echo "<th>Kode</th>";
            echo "<th>Nama Produk</th>";
            echo "<th>Harga (Rp.)</th>";
            echo "<th>Link</th>";
            echo "<th>Aksi</th>";
            echo "</tr></thead>";
            echo "<tbody>";
            // Menampilkan data baris per baris
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Kode']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Nama Produk']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Harga (Rp.)']) . "</td>";
                echo "<td><a href='" . htmlspecialchars($row['Link']) . "' target='_blank'>Lihat produk</a></td>";
                echo "<td>
                        <a href='?restore_id=" . htmlspecialchars($row['Kode']) . "' class='restore-button' onclick='return confirm(\"Anda yakin ingin mengembalikan produk ini ke tabel produk?\")'>Restore</a>
                        <a href='?delete_id=" . htmlspecialchars($row['Kode']) . "' class='delete-button' onclick='return confirm(\"Anda yakin ingin menghapus produk ini dari tabel sold?\")'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>Tidak ada produk yang terjual.</p>";
        }

        // Menutup koneksi
        $conn->close();
        ?>

        <!-- Tombol Kembali -->
        <a href="homepage.php" class="back-button">Kembali</a>
    </div>
</body>
</html>
