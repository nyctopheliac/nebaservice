<?php
session_start();
include 'connect.php';

// Se o utilizador já estiver logado, redireciona para a página inicial
if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Função para enviar resposta JSON
function send_json_response($success, $message, $redirectUrl = null) {
    header('Content-Type: application/json');
    $response = ['success' => $success, 'message' => $message];
    if ($redirectUrl) {
        $response['redirectUrl'] = $redirectUrl;
    }
    echo json_encode($response);
    exit();
}

// Processar o pedido de registo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeUtilizador = trim($_POST['username']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['password']);
    $confirmarSenha = trim($_POST['confirmPassword']);

    $mensagensErro = [];

    if (empty($nomeUtilizador) || empty($email) || empty($senha)) {
        $mensagensErro[] = "Precisa de preencher todos os campos obrigatórios.";
    }
    if (strlen($senha) < 8) {
        $mensagensErro[] = "A palavra-passe deve ter pelo menos 8 caracteres.";
    }
    if ($senha !== $confirmarSenha) {
        $mensagensErro[] = "As palavras-passe não coincidem.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagensErro[] = "O formato do email é inválido.";
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $nomeUtilizador)) {
        $mensagensErro[] = "O nome de utilizador só pode conter letras, números e underscores.";
    }

    if (!empty($mensagensErro)) {
        send_json_response(false, implode("\n", $mensagensErro));
    }

    $stmt_check = $conn->prepare("SELECT ID FROM utilizadores WHERE nomeUtilizador = ? OR email = ?");
    $stmt_check->bind_param("ss", $nomeUtilizador, $email);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        send_json_response(false, "O nome de utilizador ou o email já se encontram registados.");
    } else {
        $hashedPassword = password_hash($senha, PASSWORD_BCRYPT);
        $stmt_insert = $conn->prepare("INSERT INTO utilizadores (nomeUtilizador, email, passwordHash) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $nomeUtilizador, $email, $hashedPassword);
        
        if ($stmt_insert->execute()) {
            session_regenerate_id(true);
            $idUtilizador = $stmt_insert->insert_id;
            $_SESSION['userID'] = $idUtilizador;
            $_SESSION['email'] = $email;
            $_SESSION['pfpURL'] = 'imagens/pfp.png';
            send_json_response(true, "Conta criada com sucesso! Redirecionando...", "index.php");
        } else {
            send_json_response(false, "Falha ao criar o utilizador. Tente novamente.");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="confirmation.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-5">
    <div class="col-lg-6 mx-auto card p-4">
        <h2 class="text-center mb-4">Criar Conta</h2>
        <form id="register-form" action="register.php" method="POST">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script src="confirmation.js"></script>
<script src="global.js"></script>
</body>
</html>