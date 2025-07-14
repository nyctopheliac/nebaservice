<?php
<<<<<<< Updated upstream
session_start();
=======
>>>>>>> Stashed changes
include 'connect.php';

$mensagemErro = '';

if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['tentativas_login'])) {
    $_SESSION['tentativas_login'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_SESSION['tentativas_login'] >= 5) {
            if (!isset($_SESSION['tempo_bloqueio'])) {
                $_SESSION['tempo_bloqueio'] = time() + 300; // Lockout de 5 minutos para evitar brute forcing / broken authentication
            }
            if (time() < $_SESSION['tempo_bloqueio']) {
                $mensagemErro = "Demasiadas tentativas de login. Tente novamente em " . ($_SESSION['tempo_bloqueio'] - time()) . " segundos.";
                // Prevenir que o utilizador veja a página de login se estiver bloqueado
                echo '<!DOCTYPE html>
    <html lang="pt-PT">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <main class="container my-5">
        <div class="col-lg-6 mx-auto card p-4">
            <h2 class="text-center mb-4">Login</h2>
            <div class="alert alert-danger" role="alert">' . htmlspecialchars($mensagemErro) . '</div>
            <p class="mt-3 text-center">Não tem uma conta? <a href="register.php">Crie uma aqui</a>.</p>
        </div>
    </main>
    </body>
    </html>';
                exit();
            } else {
                // Devolver tentativas de login e remover o bloqueio quando o tempo (5 minutos/300 segundos) acabar
                $_SESSION['tentativas_login'] = 0;
                unset($_SESSION['tempo_bloqueio']);
            }
        }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $mensagemErro = "Todos os campos são obrigatórios.";
    } else {
<<<<<<< Updated upstream
        $stmt = $conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
=======
        $stmt = $conexao->prepare("SELECT * FROM utilizadores WHERE email = ?");
>>>>>>> Stashed changes
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $utilizador = $resultado->fetch_assoc();
            if (password_verify($password, $utilizador['passwordHash'])) {
                session_regenerate_id(true);
                $_SESSION['email'] = $utilizador['email'];
                $_SESSION['userID'] = $utilizador['ID'];
                $_SESSION['pfpURL'] = $utilizador['pfpURL'] ?? 'imagens/pfp.png'; // Armazenar pfpURL na sessão, com um valor padrão se não estiver definido
                session_write_close(); // Guardar e fechar explicitamente a sessão
<<<<<<< Updated upstream
                header("Location: index.php?_t=" . time());
=======
                header("Location: index.php");
>>>>>>> Stashed changes
                exit();
            } else {
                $_SESSION['tentativas_login']++;
                $mensagemErro = "Palavra-passe ou email inválido.";
            }
        } else {
            $_SESSION['tentativas_login']++;
            $mensagemErro = "Palavra-passe ou email inválido.";
        }
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
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-5">
    <div class="col-lg-6 mx-auto card p-4">
        <h2 class="text-center mb-4">Login</h2>

        <?php if (!empty($mensagemErro)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($mensagemErro); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
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
</body>
</html>