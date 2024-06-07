<?php
include_once 'koneksi.php';

class Berita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllBerita($kategori = '') {
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
}

$database = new Database();
$db = $database->getConnection();

$beritaObj = new Berita($db);
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$beritaList = $beritaObj->getAllBerita($kategori);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Baca Berita</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #007BFF; 
            padding: 10px;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: white;
            font-size: 18px; 
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            background-color: #ddd;
            color: black;
        }
        
        .navbar-custom .navbar-nav {
            margin-left: 0; 
        }
        .navbar-custom .navbar-nav .nav-item {
            margin-left: 10px; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="arsip_berita.php">Arsip Berita</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create_berita.php">Upload Berita</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="berita.php">Baca Berita</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Konten berita -->
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Berita</h1>
        
        <!-- Form untuk memilih kategori berita -->
        <form method="get" action="berita.php" class="mb-4">
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
        
        <div class="row">
            <!-- Looping untuk menampilkan berita -->
            <?php foreach($beritaList as $berita): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <!-- Tambahkan gambar berita jika tersedia -->
                    <?php if ($berita['gambar']): ?>
                    <img src="<?php echo $berita['gambar']; ?>" class="card-img-top" alt="Gambar Berita">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold"><?php echo $berita['judul']; ?></h5>
                        <p class="card-text"><?php echo substr($berita['isi'], 0, 100); ?>...</p>
                        <p class="card-text">
                            <small class="text-muted font-italic">Penulis: <?php echo $berita['author']; ?></small>
                        </p>
                        <p class="card-text">
                            <small class="text-muted font-italic">Kategori: <?php echo $berita['kategori']; ?></small>
                        </p>
                        <p class="card-text">
                            <small class="text-muted font-italic">Waktu Publikasi: <?php echo $berita['waktu_publikasi']; ?></small>
                        </p>
                        <a href="detail_berita.php?id=<?php echo $berita['id']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

