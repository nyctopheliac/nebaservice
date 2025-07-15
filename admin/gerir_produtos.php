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

// Fetch all products
$sql = "SELECT p.*, c.nome AS categoria_nome, m.nome AS marca_nome FROM produtos p LEFT JOIN categorias c ON p.categoriaID = c.ID LEFT JOIN marcas m ON p.marcaID = m.ID ORDER BY p.ID DESC";
$result = mysqli_query($conexao, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<main class="container my-5">
    <h2 class="text-center mb-4">Gerir Produtos</h2>

    <div class="text-end mb-3">
        <a href="adicionar_produto.php" class="btn btn-success">Adicionar Produto</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Stock</th>
                <th>Categoria</th>
                <th>Marca</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['ID']) ?></td>
                    <td><?= htmlspecialchars($product['nome']) ?></td>
                    <td><?= htmlspecialchars($product['preco']) ?></td>
                    <td><?= htmlspecialchars($product['stock']) ?></td>
                    <td><?= htmlspecialchars($product['categoria_nome']) ?></td>
                    <td><?= htmlspecialchars($product['marca_nome']) ?></td>
                    <td>
                        <a href="editar_produto.php?id=<?= $product['ID'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="remover_produto.php?id=<?= $product['ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza que quer remover este produto?')">Remover</a>
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
