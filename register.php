<?php
session_start();
include 'connect.php';

if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (empty($username) || empty($email) || empty($password)) {
        $errorMessages[] = "Precisa de preencher todos os campos obrigatórios.";
    }
    if ($password !== $confirmPassword) {
        $errorMessages[] = "As palavras-passe não coincidem.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessages[] = "O formato do email é inválido.";
    }

    // Se não houver erros de validação, verifica se o utilizador já existe
    if (empty($errorMessages)) {
        $stmt_check = $conn->prepare("SELECT ID FROM utilizadores WHERE nomeUtilizador = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) {
            $errorMessages[] = "O nome de utilizador ou o email já se encontram registados.";
        } else {
            // Encriptar a password e inserir o novo utilizador
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt_insert = $conn->prepare("INSERT INTO utilizadores (nomeUtilizador, email, passwordHash) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $email, $hashedPassword);
            
            if ($stmt_insert->execute()) {
                // Iniciar sessão automaticamente
                $userID = $stmt_insert->insert_id;
                $_SESSION['userID'] = $userID;
                $_SESSION['email'] = $email;
                $_SESSION['pfpURL'] = 'imagens/pfp.png'; // Default PFP
                header("Location: index.php");
                exit();
            } else {
                $errorMessages[] = "Falha ao criar o utilizador. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
        </svg>
    </button>

    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <div class="col-lg-6 mx-auto card p-4">
            <h2 class="text-center mb-4">Criar Conta</h2>
            <?php if (!empty($errorMessages)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errorMessages as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Nome de Utilizador</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirmar Senha</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Criar Conta</button>
                </div>
            </form>
            <p class="mt-3 text-center">Já tem uma conta? <a href="login.php">Faça login aqui</a>.</p>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>
