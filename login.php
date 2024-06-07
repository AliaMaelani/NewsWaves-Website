<?php
include_once 'koneksi.php';
session_start();

$error = ""; // Variabel untuk menyimpan pesan error

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
    } else {
        $error = "Username atau password salah."; // Pesan error jika login gagal
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg'); 
            background-size: cover; 
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            width: 50%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; 
        }
        input[type="text"],
        input[type="password"] {
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
        .register-link {
            color: #007BFF; 
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline; 
        }
        .alert {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            border-radius: .25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if(!empty($error)): ?>
            <div class="alert"><?= $error ?></div> <!-- Tampilkan pesan error jika ada -->
        <?php endif; ?>
        <form method="post">
            <label for="username">Username:</label><br>
            <input type="text" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php" class="register-link">Klik di sini</a></p>
    </div>
</body>
</html>
