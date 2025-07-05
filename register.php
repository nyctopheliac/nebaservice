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
    $confirmPassword = $_POST['confirmPassword'];
    if (empty($email) || empty($password) || empty($confirmPassword)) {
        die("All fields are required.");
    }
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }
    // Encriptar a password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    // Prevenir injeções
    $stmt = $conn->prepare("INSERT INTO utilizadores (email, passwordHash) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);
    $stmt->execute();

    // Verificar se o utilizador foi criado com sucesso
    if ($stmt->affected_rows > 0) {
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        die("Failed to create user.");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
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
        <h1>Register</h1>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" name="confirmPassword" id="confirmPassword" required>
            <input type="submit" value="Register">
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 NebaService. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
