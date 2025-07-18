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

// Fetch all orders
$sql = "SELECT e.*, u.nomeUtilizador FROM encomendas e JOIN utilizadores u ON e.utilizadorID = u.ID ORDER BY e.data DESC";
$result = mysqli_query($conexao, $sql);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Encomendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<main class="container my-5">
    <h2 class="text-center mb-4">Gerir Encomendas</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilizador</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['ID']) ?></td>
                    <td><?= htmlspecialchars($order['nomeUtilizador']) ?></td>
                    <td><?= htmlspecialchars($order['total']) ?></td>
                    <td><?= htmlspecialchars($order['estado']) ?></td>
                    <td><?= htmlspecialchars($order['data']) ?></td>
                    <td>
                        <a href="editar_encomenda.php?id=<?= $order['ID'] ?>" class="btn btn-primary btn-sm">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>
