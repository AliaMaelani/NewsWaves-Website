<?php
include_once 'koneksi.php';

class DetailBerita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getBerita($id) {
        $query = "SELECT * FROM berita WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function displayDetail($berita) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Detail Berita</title>
            <!-- Bootstrap CSS -->
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <style>
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
                .btn-berita {
            background-color: blue;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 20px;
            }
            .btn-berita {
                background-color: #007BFF; 
                color: white;
            }
            .center-btn {
                display: flex;
                justify-content: center;
            }
            </style>
        </head>
        <body>
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
    

            <div class="container mt-5">
                <h1><?php echo $berita['judul']; ?></h1>
                <p><strong>Author:</strong> <?php echo $berita['author']; ?></p>
                <p><strong>Kategori:</strong> <?php echo $berita['kategori']; ?></p>
                <p><strong>Waktu Publikasi:</strong> <?php echo $berita['waktu_publikasi']; ?></p>
                <?php if ($berita['gambar']): ?>
                    <p><img src="<?php echo $berita['gambar']; ?>" class="img-fluid"></p>
                <?php endif; ?>
                <p><?php echo nl2br($berita['isi']); ?></p>
                <p><strong>Sumber:</strong> <a href="<?php echo $berita['sumber']; ?>" target="_blank"><?php echo $berita['sumber']; ?></a></p>
            </div>
            <div class="center-btn">
            <a href="berita.php" class="btn-berita">Baca berita lainnya</a>

            <!-- Bootstrap JS and dependencies -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
            <script src="https://maxcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>
        </html>
        <?php
    }
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->getConnection();

    $detailBerita = new DetailBerita($db);
    $berita = $detailBerita->getBerita($id);

    if(!$berita) {
        echo "Berita tidak ditemukan.";
        exit;
    }

    $detailBerita->displayDetail($berita);
} else {
    echo "ID berita tidak ditemukan.";
    exit;
}
?>
