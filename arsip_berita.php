<?php
include_once 'koneksi.php';

class ArsipBerita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getBeritaList($kategori = '') {
        if ($kategori) {
            $query = "SELECT * FROM berita WHERE kategori = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$kategori]);
        } else {
            $query = "SELECT * FROM berita";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function displayBeritaList() {
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
        $beritaList = $this->getBeritaList($kategori);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Arsip Berita</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: white; 
                    background-size: cover;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .navbar {
                    display: flex;
                    background-color: #007BFF; /* Warna biru */
                    padding: 10px;
                }
                .navbar a {
                    color: white;
                    padding: 14px 20px;
                    text-decoration: none;
                    text-align: center;
                }
                .navbar a:hover {
                    background-color: #ddd;
                    color: black;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                table, th, td {
                    border: 1px solid black;
                    padding: 8px;
                }
                th {
                    background-color: #f2f2f2;
                    text-align: left;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                tr:nth-child(odd) {
                    background-color: #ffffff;
                }
                td {
                    text-align: left;
                }
                .btn-update, .btn-delete, .btn-primary {
                    padding: 5px 10px;
                    border: none;
                    cursor: pointer;
                    border-radius: 3px;
                }
                .btn-update, .btn-delete {
                    color: white;
                }
                .btn-update {
                    background-color: #4CAF50;
                }
                .btn-delete {
                    background-color: #f44336;
                }
                .btn-primary {
                    background-color: #007BFF;
                    color: white;
                }
                .form-group {
                    margin-bottom: 20px;
                }
                #kategori {
                    font-size: 16px;
                    padding: 10px;
                }
            </style>
        </head>
        <body>
            <div class="navbar">
                <a href="index.php">Home</a>
                <a href="arsip_berita.php">Arsip Berita</a>
                <a href="create_berita.php">Upload Berita</a>
                <a href="berita.php">Baca Berita</a>
                <a href="logout.php">Logout</a>
            </div>

            <h1>Arsip Berita</h1>
                <form method="get" action="arsip_berita.php" class="mb-4">
                <div class="form-group">
                    <label for="kategori">Pilih Kategori:</label>
                    <select class="form-control" id="kategori" name="kategori">
                        <option value="">Semua Kategori</option>
                        <option value="sosial" <?php if($kategori == 'sosial') echo 'selected'; ?>>Sosial</option>
                        <option value="politik" <?php if($kategori == 'politik') echo 'selected'; ?>>Politik</option>
                        <option value="ekonomi" <?php if($kategori == 'ekonomi') echo 'selected'; ?>>Ekonomi</option>
                        <option value="edukasi" <?php if($kategori == 'edukasi') echo 'selected'; ?>>Edukasi</option>
                        <!-- Tambahkan kategori baru di sini -->
                        <option value="hiburan" <?php if($kategori == 'hiburan') echo 'selected'; ?>>Hiburan</option>
                        <option value="olahraga" <?php if($kategori == 'olahraga') echo 'selected'; ?>>Olahraga</option>
                        <option value="kesehatan" <?php if($kategori == 'kesehatan') echo 'selected'; ?>>Kesehatan</option>
                        <option value="teknologi" <?php if($kategori == 'teknologi') echo 'selected'; ?>>Teknologi</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>

            <table>
                <br>
                <tr>
                    <th>Judul</th>
                    <th>Isi</th>
                    <th>Author</th>
                    <th>Kategori</th>
                    <th>Waktu Publikasi</th>
                    <th>Gambar</th>
                    <th>Sumber</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach($beritaList as $berita): ?>
                <tr>
                    <td><?php echo $berita['judul']; ?></td>
                    <td><?php echo $berita['isi']; ?></td>
                    <td><?php echo $berita['author']; ?></td>
                    <td><?php echo $berita['kategori']; ?></td>
                    <td><?php echo $berita['waktu_publikasi']; ?></td>
                    <td>
                        <?php if ($berita['gambar']): ?>
                            <img src="<?php echo $berita['gambar']; ?>" width="100">
                        <?php endif; ?>
                    </td>
                    <td><?php echo $berita['sumber']; ?></td>
                    <td>
                        <button class="btn-update" onclick="window.location.href='update.php?id=<?php echo $berita['id']; ?>'">Update</button>
                        <button class="btn-delete" onclick="if(confirm('Apakah Anda yakin ingin menghapus berita ini?')) 
                        window.location.href='delete.php?id=<?php echo $berita['id']; ?>'">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </body>
        </html>
        <?php
    }
}

$database = new Database();
$db = $database->getConnection();

$arsipBerita = new ArsipBerita($db);
$arsipBerita->displayBeritaList();
?>
