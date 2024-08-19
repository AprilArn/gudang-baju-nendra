<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Stok Produk</title>
    <link rel="stylesheet" href="style/homepage.css">
</head>
<body>
    <div class="container">
        <h1>Jumlah Stok Produk</h1>
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

            // Variabel untuk menyimpan jumlah total produk
            $total_produk_new = 0;
            $total_produk_thrift = 0;
            $total_produk_sold = 0;

            // Query untuk mendapatkan jumlah stok untuk jenis 'New'
            $sql_new = "SELECT COUNT(*) AS total_produk FROM produk JOIN jenis ON produk.id_jenis = jenis.id_jenis WHERE jenis.nama_jenis = 'New'";
            $result_new = $conn->query($sql_new);

            if ($result_new->num_rows > 0) {
                $row = $result_new->fetch_assoc();
                $total_produk_new = $row['total_produk'];
            }

            // Query untuk mendapatkan jumlah stok untuk jenis 'Thrift'
            $sql_thrift = "SELECT COUNT(*) AS total_produk FROM produk JOIN jenis ON produk.id_jenis = jenis.id_jenis WHERE jenis.nama_jenis = 'Thrift'";
            $result_thrift = $conn->query($sql_thrift);

            if ($result_thrift->num_rows > 0) {
                $row = $result_thrift->fetch_assoc();
                $total_produk_thrift = $row['total_produk'];
            }

            // Query untuk mendapatkan jumlah produk yang telah terjual
            $sql_sold = "SELECT COUNT(*) AS total_produk FROM sold";
            $result_sold = $conn->query($sql_sold);

            if ($result_sold->num_rows > 0) {
                $row = $result_sold->fetch_assoc();
                $total_produk_sold = $row['total_produk'];
            }

            // Menutup koneksi
            $conn->close();
            ?>
            <a href="product_new.php">
                <div class="stock-category">
                    <h2>New</h2>
                    <p><strong>Total Produk:</strong></p>
                    <p class="total-number"><?php echo htmlspecialchars($total_produk_new); ?></p>
                </div>
            </a>
            <a href="product_thrift.php">
                <div class="stock-category">
                    <h2>Thrift</h2>
                    <p><strong>Total Produk:</strong></p>
                    <p class="total-number"><?php echo htmlspecialchars($total_produk_thrift); ?></p>
                </div>
            </a>
            <a href="sold.php">
                <div class="stock-category">
                    <h2>Sold</h2>
                    <p><strong>Total Produk:</strong></p>
                    <p class="total-number"><?php echo htmlspecialchars($total_produk_sold); ?></p>
                </div>
            </a>
        </div>
        <!-- Tombol tambah produk di dalam kotak -->
        <a href="insert_data.php" class="add-button">+</a>
    </div>
</body>
</html>
