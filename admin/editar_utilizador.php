<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page or an error page
    header('Location: ../login.php');
    exit();
}

// Include the database connection
require_once '../connect.php';

// Get the user ID from the URL
$id = $_GET['id'];

// Fetch the user details
$sql = "SELECT * FROM utilizadores WHERE ID = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeUtilizador = $_POST['nomeUtilizador'];
    $email = $_POST['email'];
    $nomeCompleto = $_POST['nomeCompleto'];
    $telefone = $_POST['telefone'];
    $morada = $_POST['morada'];
    $codigoPostal = $_POST['codigoPostal'];
    $localidade = $_POST['localidade'];
    $pais = $_POST['pais'];
    $nif = $_POST['nif'];

    $sql = "UPDATE utilizadores SET nomeUtilizador = ?, email = ?, nomeCompleto = ?, telefone = ?, morada = ?, codigoPostal = ?, localidade = ?, pais = ?, nif = ? WHERE ID = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssi", $nomeUtilizador, $email, $nomeCompleto, $telefone, $morada, $codigoPostal, $localidade, $pais, $nif, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: gerir_utilizadores.php');
        exit();
    } else {
        $erro = "Erro ao atualizar o utilizador.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Utilizador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<main class="container my-5">
    <h2 class="text-center mb-4">Editar Utilizador</h2>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form action="editar_utilizador.php?id=<?= $id ?>" method="POST">
        <div class="mb-3">
            <label for="nomeUtilizador" class="form-label">Nome de Utilizador</label>
            <input type="text" class="form-control" id="nomeUtilizador" name="nomeUtilizador" value="<?= htmlspecialchars($user['nomeUtilizador'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="mb-3">
            <label for="nomeCompleto" class="form-label">Nome Completo</label>
            <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" value="<?= htmlspecialchars($user['nomeCompleto'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($user['telefone'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="mb-3">
            <label for="morada" class="form-label">Morada</label>
            <input type="text" class="form-control" id="morada" name="morada" value="<?= htmlspecialchars($user['morada'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="mb-3">
            <label for="codigoPostal" class="form-label">Código Postal</label>
            <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" value="<?= htmlspecialchars($user['codigoPostal'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="mb-3">
            <label for="localidade" class="form-label">Localidade</label>
            <input type="text" class="form-control" id="localidade" name="localidade" value="<?= htmlspecialchars($user['localidade'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="mb-3">
            <label for="pais" class="form-label">País</label>
            <input type="text" class="form-control" id="pais" name="pais" value="<?= htmlspecialchars($user['pais'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="mb-3">
            <label for="nif" class="form-label">NIF</label>
            <input type="text" class="form-control" id="nif" name="nif" value="<?= htmlspecialchars($user['nif'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="gerir_utilizadores.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>
