<?php
session_start();
include 'connect.php';

if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("Precisa de preencher todos os campos.");
    }
    if ($password !== $confirmPassword) {
        die("As palavras-passe não coincidem uma com a outra.");
    }
    // Encriptar a password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prevenir injeções
    $stmt = $conn->prepare("INSERT INTO utilizadores (nomeUtilizador, email, passwordHash) VALUES ($username, $email, $hashedPassword)");
    $stmt->bind_param($username, $email, $hashedPassword);
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
    <title>Registar</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registar</h2>
        <form action="process_register.php" method="POST">
            <div class="form-group">
                <label for="username">Nome de Utilizador</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="username">Email</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Criar Conta</button>
        </form>
        <p class="mt-3">Já tem uma conta? <a href="login.php">Faça login aqui</a>.</p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
