<?php
include_once 'koneksi.php';
session_start();

class DeleteBerita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function deleteBerita($id) {
        $query = "DELETE FROM berita WHERE id = ?";
        $stmt = $this->db->prepare($query);
        if($stmt->execute([$id])) {
            header("Location: delete.php?status=success");
            exit();
        } else {
            header("Location: delete.php?status=failed");
            exit();
        }
    }
}

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->getConnection();

    $deleter = new DeleteBerita($db);
    $deleter->deleteBerita($id);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Berita</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <div class="container">
        <h1 style="text-align: center;">Delete Berita</h1>
        
        <!-- Alert untuk menampilkan notifikasi -->
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success" role="alert">
                Berita berhasil dihapus.
            </div>
        <?php elseif(isset($_GET['status']) && $_GET['status'] == 'failed'): ?>
            <div class="alert alert-danger" role="alert">
                Hapus berita gagal.
            </div>
        <?php endif; ?>
        <p><a href="arsip_berita.php" class="btn-back">Back </a></p>
    </div>
</body>
</html>
