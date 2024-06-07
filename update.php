<?php
include_once 'koneksi.php';

class UpdateBerita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function updateBerita($id, $judul, $isi, $author, $kategori, $gambar, $sumber) {
        $query = "UPDATE berita SET judul = ?, isi = ?, author = ?, kategori = ?, gambar = ?, sumber = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$judul, $isi, $author, $kategori, $gambar, $sumber, $id]);
    }

    public function getBerita($id) {
        $query = "SELECT * FROM berita WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function displayUpdateForm($berita, $status = '') {
        ?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Berita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .container {
            margin: auto;
            width: 50%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #008CBA;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .navbar {
            background-color: #007BFF; 
            padding: 10px 0;
        }
        .navbar a {
            color: white; 
            padding: 10px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #00688B; 
        }
        .alert {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: left;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="arsip_berita.php" class="btn-back">Back</a>
    </nav>
    <div class="container">
        <h2>Update Berita</h2>

        <!-- Alert untuk menampilkan notifikasi -->
        <?php if($status == 'success'): ?>
            <div class="alert alert-success" role="alert">
                Berita berhasil diupdate.
            </div>
        <?php elseif($status == 'failed'): ?>
            <div class="alert alert-danger" role="alert">
                Update berita gagal.
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $berita['id']; ?>">
            <label for="judul">Judul:</label>
            <input type="text" name="judul" value="<?php echo $berita['judul']; ?>" required>
            <label for="isi">Isi:</label>
            <textarea name="isi" required><?php echo $berita['isi']; ?></textarea>
            <label for="author">Author:</label>
            <input type="text" name="author" value="<?php echo $berita['author']; ?>" required>
            <label for="kategori">Kategori:</label>
            <select name="kategori" required>
                <option value="sosial" <?php echo ($berita['kategori'] == 'sosial') ? 'selected' : ''; ?>>Sosial</option>
                <option value="politik" <?php echo ($berita['kategori'] == 'politik') ? 'selected' : ''; ?>>Politik</option>
                <option value="ekonomi" <?php echo ($berita['kategori'] == 'ekonomi') ? 'selected' : ''; ?>>Ekonomi</option>
                <option value="edukasi" <?php echo ($berita['kategori'] == 'edukasi') ? 'selected' : ''; ?>>Edukasi</option>
                <option value="olahraga" <?php echo ($berita['kategori'] == 'olahraga') ? 'selected' : ''; ?>>Olahraga</option>
                <option value="hiburan" <?php echo ($berita['kategori'] == 'hiburan') ? 'selected' : ''; ?>>Hiburan</option>
                <option value="kesehatan" <?php echo ($berita['kategori'] == 'kesehatan') ? 'selected' : ''; ?>>Kesehatan</option>
                <option value="teknologi" <?php echo ($berita['kategori'] == 'teknologi') ? 'selected' : ''; ?>>Teknologi</option>
            </select>
            <label for="gambar">Gambar:</label>
            <input type="file" name="gambar">
            <input type="hidden" name="existing_gambar" value="<?php echo $berita['gambar']; ?>">
            <label for="sumber">Sumber:</label>
            <input type="text" name="sumber" value="<?php echo $berita['sumber']; ?>" required>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
<?php
    }
}

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $author = $_POST['author'];
    $kategori = $_POST['kategori'];
    $sumber = $_POST['sumber'];

    $gambar = $_POST['existing_gambar'];
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = 'uploads/' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
    }

    $database = new Database();
    $db = $database->getConnection();

    $updateBerita = new UpdateBerita($db);
    if ($updateBerita->updateBerita($id, $judul, $isi, $author, $kategori, $gambar, $sumber)) {
        header("Location: update.php?id=$id&status=success");
        exit();
    } else {
        header("Location: update.php?id=$id&status=failed");
        exit();
    }
} else if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    $database = new Database();
    $db = $database->getConnection();

    $updateBerita = new UpdateBerita($db);
    $berita = $updateBerita->getBerita($id);

    if (!$berita) {
        echo "Berita tidak ditemukan.";
        exit;
    }

    $updateBerita->displayUpdateForm($berita, $status);
}
?>
