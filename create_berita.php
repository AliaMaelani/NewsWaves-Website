<?php
include_once 'koneksi.php';
session_start();

class UploadBerita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function upload() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $judul = $_POST['judul'];
            $isi = $_POST['isi'];
            $author = $_POST['author'];
            $kategori = $_POST['kategori'];
            $sumber = $_POST['sumber'];
            
            // Handle file upload
            $gambar = '';
            if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
                $upload_dir = 'uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $gambar = $upload_dir . basename($_FILES['gambar']['name']);
                move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar);
            }
        
            $query = "INSERT INTO berita (judul, isi, author, kategori, gambar, sumber) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            if($stmt->execute([$judul, $isi, $author, $kategori, $gambar, $sumber])) {
                header("Location: create_berita.php?status=success");
                exit();
            } else {
                header("Location: create_berita.php?status=failed");
                exit();
            }
        }
    }
}

$database = new Database();
$db = $database->getConnection();

$uploader = new UploadBerita($db);
$uploader->upload();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Berita</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            background-color: #007BFF;
            padding: 10px;
            justify-content: flex-start;
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
        .container {
            width: 50%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-top: 20px;
        }
        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #007BFF;
            color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            text-align: center;
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

    <div class="container">
        <h1 style="text-align: center;">Upload Berita</h1>
        
        <!-- Alert untuk menampilkan notifikasi -->
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success" role="alert">
                Berita berhasil diupload.
            </div>
        <?php elseif(isset($_GET['status']) && $_GET['status'] == 'failed'): ?>
            <div class="alert alert-danger" role="alert">
                Upload berita gagal.
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <label for="judul">Judul:</label>
            <input type="text" name="judul" required><br>
            <label for="isi">Isi:</label>
            <textarea name="isi" required></textarea><br>
            <label for="author">Author:</label>
            <input type="text" name="author" required><br>
            <label for="kategori">Kategori:</label>
            <select name="kategori" required>
                <option value="sosial">Sosial</option>
                <option value="politik">Politik</option>
                <option value="ekonomi">Ekonomi</option>
                <option value="edukasi">Edukasi</option>
                <option value="hiburan">Hiburan</option>
                <option value="olahraga">Olahraga</option>
                <option value="kesehatan">Kesehatan</option>
                <option value="teknologi">Teknologi</option>
            </select><br>
            <label for="gambar">Gambar:</label>
            <input type="file" name="gambar"><br>
            <label for="sumber">Sumber:</label>
            <input type="text" name="sumber" required><br>
            <button type="submit">Upload</button>
        </form>
    </div>

    <p><a href="index.php" class="btn-back">Back to Home</a></p>
</body>
</html>
