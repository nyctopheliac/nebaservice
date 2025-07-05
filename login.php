<?php
session_start();
include 'connect.php';

if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        die("All fields are required.");
    }

    // Proteção contra injeções
    $stmt = $conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['passwordHash'])) {
            $_SESSION['email'] = $user['email'];
            header("Location: index.php");
            exit();
        } else {
            die("Invalid password.");
        }
    } else {
        die("No user found with that email.");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">NebaService</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="servicos.php">Serviços</a></li>
        </ul>
    </nav>

    <div>
        <h1>Login</h1>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Login">
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 NebaService. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
