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

// Inicializar controlo de tentativas de login
if (!isset($_SESSION['tentativas_login'])) {
    $_SESSION['tentativas_login'] = 0;
}

// Processar o pedido de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar bloqueio por tentativas excessivas
    if ($_SESSION['tentativas_login'] >= 5) {
        if (!isset($_SESSION['tempo_bloqueio'])) {
            $_SESSION['tempo_bloqueio'] = time() + 300; // Bloqueio de 5 minutos
        }
        if (time() < $_SESSION['tempo_bloqueio']) {
            $tempo_restante = $_SESSION['tempo_bloqueio'] - time();
            send_json_response(false, "Demasiadas tentativas de login. Tente novamente em " . $tempo_restante . " segundos.");
        } else {
            // Resetar tentativas após o fim do bloqueio
            $_SESSION['tentativas_login'] = 0;
            unset($_SESSION['tempo_bloqueio']);
        }
    }


    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        send_json_response(false, "Todos os campos são obrigatórios.");
    }

    $stmt = $conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $utilizador = $resultado->fetch_assoc();
        if (password_verify($password, $utilizador['passwordHash'])) {
            // Login bem-sucedido
            session_regenerate_id(true);
            $_SESSION['email'] = $utilizador['email'];
            $_SESSION['userID'] = $utilizador['ID'];
            $_SESSION['pfpURL'] = $utilizador['pfpURL'] ?? 'imagens/pfp.png';
            $_SESSION['tentativas_login'] = 0; // Resetar tentativas
            unset($_SESSION['tempo_bloqueio']);
            session_write_close();
            send_json_response(true, "Login bem-sucedido!", "index.php");
        } else {
            // Palavra-passe incorreta
            $_SESSION['tentativas_login']++;
            send_json_response(false, "Palavra-passe ou email inválido.");
        }
    } else {
        // Utilizador não encontrado
        $_SESSION['tentativas_login']++;
        send_json_response(false, "Palavra-passe ou email inválido.");
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="confirmation.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-5">
    <div class="col-lg-6 mx-auto card p-4">
        <h2 class="text-center mb-4">Login</h2>

        <form id="login-form" action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
        <p class="mt-3 text-center">Não tem uma conta? <a href="register.php">Crie uma aqui</a>.</p>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script src="confirmation.js"></script>
<script src="global.js"></script>
</body>
</html>