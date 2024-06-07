<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

class Navbar {
    private $links = [
        'Home' => 'index.php',
        'Arsip Berita' => 'arsip_berita.php',
        'Upload Berita' => 'create_berita.php',
        'Baca Berita' => 'berita.php',
        'Logout' => 'logout.php'
    ];

    public function display() {
        echo '<div class="navbar">';
        foreach ($this->links as $title => $url) {
            echo '<a href="' . $url . '">' . $title . '</a>';
        }
        echo '</div>';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>NewsWaves</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('NewsWaves.jpg') no-repeat center center fixed; 
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            background-color: #007BFF; 
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
        .container {
            text-align: center;
            padding: 100px;
            background-color: rgba(255, 255, 255, 0.8); 
            border-radius: 10px;
            margin: 50px auto;
            width: 50%;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        button {
            font-size: 1.2em;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php
$navbar = new Navbar();
$navbar->display();
?>

<div class="container">
    <h1>Selamat Datang di NewsWaves</h1>
    <button onclick="window.location.href='berita.php'">Baca Berita</button>
</div>
</body>
</html>
