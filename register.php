<?php
include_once 'koneksi.php';

$status = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $database = new Database();
    $db = $database->getConnection();

    // Mengecek apakah username atau email sudah ada
    $query = "SELECT COUNT(*) FROM users WHERE username = ? OR email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username, $email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        header("Location: register.php?status=exists");
        exit();
    } else {
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        if ($stmt->execute([$username, $email, $password])) {
            header("Location: register.php?status=success");
            exit();
        } else {
            header("Location: register.php?status=failed");
            exit();
        }
    }
}

if (isset($_GET['status'])) {
    $status = $_GET['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        input[type="email"],
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
        .login-link {
            color: #007BFF; 
            text-decoration: none;
        }
        .login-link:hover {
            text-decoration: underline; 
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
    <div class="container">
        <h2>Register</h2>
        
        <!-- Alert untuk menampilkan notifikasi -->
        <?php if ($status == 'success'): ?>
            <div class="alert alert-success" role="alert">
                Registrasi berhasil. Silahkan <a href="login.php" class="login-link">login</a>.
            </div>
        <?php elseif ($status == 'failed'): ?>
            <div class="alert alert-danger" role="alert">
                Registrasi gagal.
            </div>
        <?php elseif ($status == 'exists'): ?>
            <div class="alert alert-danger" role="alert">
                Username atau email sudah terdaftar.
            </div>
        <?php endif; ?>

        <form method="post">
            <label for="username">Username:</label><br>
            <input type="text" name="username" required><br>
            <label for="email">Email:</label><br>
            <input type="email" name="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit">Register</button>
        </form>
        <p>Sudah punya akun? <a href="login.php" class="login-link">Klik disini untuk login</a></p>
    </div>
</body>
</html>
