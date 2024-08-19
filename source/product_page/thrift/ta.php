<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk Atasan</title>
    <link rel="stylesheet" href="../style/global_product_style.css">
</head>
<body>
    <div class="container">
        <h1 class="page-title">Etalase Atasan New</h1>   <!-- JUDUL: perlu diubah setiap page -->

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
            $delete_sql = "DELETE FROM produk WHERE id_produk = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("s", $delete_id);

            if ($stmt->execute()) {
                echo "<p>Produk berhasil dihapus.</p>";
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }

        // Cek apakah ada permintaan sold
        if (isset($_GET['sold_id'])) {
            $sold_id = $_GET['sold_id'];

            // Query untuk mendapatkan data produk
            $select_sql = "SELECT id_produk, id_jenis, id_kategori, nama_produk, harga_produk, link_ig
                           FROM produk
                           WHERE id_produk = ?";
            $stmt = $conn->prepare($select_sql);
            $stmt->bind_param("s", $sold_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Masukkan data ke tabel sold
                $insert_sql = "INSERT INTO sold (id_produk, id_jenis, id_kategori, nama_produk, harga_produk, link_ig) 
                               VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("ssssis", $row['id_produk'], $row['id_jenis'], $row['id_kategori'], 
                                  $row['nama_produk'], $row['harga_produk'], $row['link_ig']);

                if ($stmt->execute()) {
                    echo "<p>Produk berhasil dijual dan dipindahkan ke tabel sold.</p>";

                    // Hapus produk dari tabel produk
                    $delete_sql = "DELETE FROM produk WHERE id_produk = ?";
                    $stmt = $conn->prepare($delete_sql);
                    $stmt->bind_param("s", $sold_id);
                    $stmt->execute();
                } else {
                    echo "<p>Error INSERT: " . $stmt->error . "</p>";
                }
            } else {
                echo "<p>Produk tidak ditemukan.</p>";
            }

            $stmt->close();
        }

        // Query SQL untuk menampilkan produk
        $sql = "SELECT 
                    produk.id_produk AS 'Kode',
                    produk.nama_produk AS 'Nama Produk',
                    produk.harga_produk AS 'Harga (Rp.)',
                    produk.link_ig AS 'Link'
                FROM 
                    produk
                JOIN 
                    jenis ON produk.id_jenis = jenis.id_jenis
                JOIN 
                    kategori ON produk.id_kategori = kategori.id_kategori
                WHERE
                    kategori.nama_kategori = 'Atasan' -- < Perlu di Ubah >
                AND
                    jenis.nama_jenis = 'Thrift'";

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
                        <a href='?sold_id=" . htmlspecialchars($row['Kode']) . "' class='sold-button' onclick='return confirm(\"Anda yakin ingin menandai produk ini sebagai terjual?\")'>Sold</a>
                        <a href='?delete_id=" . htmlspecialchars($row['Kode']) . "' class='delete-button' onclick='return confirm(\"Anda yakin ingin menghapus produk ini?\")'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>Tidak ada data produk untuk kategori Atasan dan jenis New.</p>";
        }

        // Menutup koneksi
        $conn->close();
        ?>

        <!-- Tombol Kembali -->
        <a href="../../product_new.php" class="back-button">Kembali</a>   <!-- Perlu diubah di setiap Jenis New/Thrift -->
    </div>
</body>
</html>
