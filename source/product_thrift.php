<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Kategori - Gudang Baju</title>
    <link rel="stylesheet" href="style/product_new_thrift.css">
</head>
<body>
    <div class="container">
        <h1>Thrift</h1>
        <div class="stock-info">
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

            // Query untuk mendapatkan jumlah stok untuk kategori yang diinginkan dengan jenis 'New'
            $sql = "SELECT 
                        kategori.nama_kategori AS Kategori,
                        COUNT(produk.id_produk) AS Jumlah_Produk
                    FROM 
                        kategori
                    LEFT JOIN 
                        produk ON kategori.id_kategori = produk.id_kategori
                    LEFT JOIN
                        jenis ON produk.id_jenis = jenis.id_jenis
                    WHERE 
                        jenis.nama_jenis = 'Thrift'
                        AND kategori.nama_kategori IN ('Atasan', 'Celana', 'Dress', 'Jaket', 'Setelan')
                    GROUP BY 
                        kategori.nama_kategori
                    ORDER BY 
                        kategori.nama_kategori";

            $result = $conn->query($sql);

            // Membuat array dengan semua kategori dan default jumlah produk = 0
            $categories = [
                'Atasan' => 'product_page/thrift/ta.php',
                'Celana' => 'product_page/thrift/tc.php',
                'Dress' => 'product_page/thrift/td.php',
                'Jaket' => 'product_page/thrift/tj.php',
                'Setelan' => 'product_page/thrift/ts.php'
            ];
            $category_counts = array_fill_keys(array_keys($categories), 0);

            // Memasukkan hasil query ke dalam array
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $category = $row['Kategori'];
                    if (array_key_exists($category, $category_counts)) {
                        $category_counts[$category] = $row['Jumlah_Produk'];
                    }
                }
            }

            // Menampilkan kategori sebagai tautan dan jumlah produk
            foreach ($categories as $category => $link) {
                echo '<a href="' . htmlspecialchars($link) . '" class="stock-category-link">';
                echo '<div class="stock-category">';
                echo '<h2>' . htmlspecialchars($category) . '</h2>';
                echo '<p><strong>Total Produk:</strong></p>';
                echo '<p class="total-number">' . htmlspecialchars($category_counts[$category]) . '</p>';
                echo '</div>';
                echo '</a>';
            }

            // Menutup koneksi
            $conn->close();
            ?>
        </div>
        <!-- Tombol Back -->
        <a href="homepage.php" class="back-button">Kembali</a>
    </div>
</body>
</html>
