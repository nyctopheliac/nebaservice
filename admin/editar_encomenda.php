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

// Get the order ID from the URL
$id = $_GET['id'];

// Fetch the order details
$sql = "SELECT * FROM encomendas WHERE ID = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estado = $_POST['estado'];

    $sql = "UPDATE encomendas SET estado = ? WHERE ID = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "si", $estado, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: gerir_encomendas.php');
        exit();
    } else {
        $erro = "Erro ao atualizar o estado da encomenda.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Encomenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<main class="container my-5">
    <h2 class="text-center mb-4">Editar Encomenda</h2>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form action="editar_encomenda.php?id=<?= $id ?>" method="POST">
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="Pendente" <?= $order['estado'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="A processar" <?= $order['estado'] == 'A processar' ? 'selected' : '' ?>>A processar</option>
                <option value="Enviada" <?= $order['estado'] == 'Enviada' ? 'selected' : '' ?>>Enviada</option>
                <option value="Entregue" <?= $order['estado'] == 'Entregue' ? 'selected' : '' ?>>Entregue</option>
                <option value="Cancelada" <?= $order['estado'] == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="gerir_encomendas.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>
