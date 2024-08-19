<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru</title>
    <link rel="stylesheet" href="style/insert_data.css">
</head>
<body>
    <div class="container">
        <h1>Tambah Produk Baru</h1>
        <?php
        // Cek apakah form telah disubmit
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

            // Ambil data dari form
            $id_jenis = $_POST['id_jenis'];
            $id_kategori = $_POST['id_kategori'];
            $nama_produk = $_POST['nama_produk'];
            $harga_produk = $_POST['harga_produk'];
            $link_ig = $_POST['link_ig'];

            // Membuat query untuk insert data
            $sql = "INSERT INTO produk (id_jenis, id_kategori, nama_produk, harga_produk, link_ig)
                    VALUES ('$id_jenis', '$id_kategori', '$nama_produk', $harga_produk, '$link_ig')";

            // Eksekusi query dan cek keberhasilan
            if ($conn->query($sql) === TRUE) {
                // Redirect ke cektable.php
                header("Location: homepage.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            // Menutup koneksi
            $conn->close();
        }
        ?>

        <!-- Form untuk menambahkan produk baru -->
        <form method="post" action="">
            <label for="id_jenis">Jenis:</label>
            <!-- Tombol untuk memilih jenis -->
            <input type="hidden" id="id_jenis" name="id_jenis" required>
            <button type="button" class="option-button jenis-button" data-id="N">New</button>
            <button type="button" class="option-button jenis-button" data-id="T">Thrift</button>

            <label for="id_kategori">Kategori:</label>
            <!-- Tombol untuk memilih kategori -->
            <input type="hidden" id="id_kategori" name="id_kategori" required>
            <button type="button" class="option-button kategori-button" data-id="A">Atasan</button>
            <button type="button" class="option-button kategori-button" data-id="C">Celana</button>
            <button type="button" class="option-button kategori-button" data-id="D">Dress</button>
            <button type="button" class="option-button kategori-button" data-id="J">Jaket</button>
            <button type="button" class="option-button kategori-button" data-id="S">Setelan</button>

            <label for="nama_produk">Nama Produk:</label>
            <input type="text" id="nama_produk" name="nama_produk" required>

            <label for="harga_produk">Harga Produk:</label>
            <input type="number" id="harga_produk" name="harga_produk" required>

            <label for="link_ig">Link Instagram:</label>
            <input type="url" id="link_ig" name="link_ig">

            <input type="submit" value="Tambah Produk">
        </form>

        <!-- Tombol Back -->
        <div class="back-button-container">
            <a href="homepage.php" class="back-button">Batal</a>
        </div>
    </div>

    <script>
        // JavaScript untuk menangani klik pada tombol jenis dan kategori
        document.querySelectorAll('.jenis-button').forEach(button => {
            button.addEventListener('click', function() {
                // Hapus kelas aktif dari semua tombol jenis
                document.querySelectorAll('.jenis-button').forEach(btn => btn.classList.remove('active'));
                
                // Tambahkan kelas aktif ke tombol yang diklik
                this.classList.add('active');
                
                // Set nilai input hidden id_jenis sesuai dengan tombol yang diklik
                document.getElementById('id_jenis').value = this.getAttribute('data-id');
            });
        });

        document.querySelectorAll('.kategori-button').forEach(button => {
            button.addEventListener('click', function() {
                // Hapus kelas aktif dari semua tombol kategori
                document.querySelectorAll('.kategori-button').forEach(btn => btn.classList.remove('active'));
                
                // Tambahkan kelas aktif ke tombol yang diklik
                this.classList.add('active');
                
                // Set nilai input hidden id_kategori sesuai dengan tombol yang diklik
                document.getElementById('id_kategori').value = this.getAttribute('data-id');
            });
        });
    </script>
</body>
</html>
