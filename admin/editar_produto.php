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

// Get the product ID from the URL
$id = $_GET['id'];

// Fetch the product details
$sql = "SELECT * FROM produtos WHERE ID = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// Fetch categories and brands for the form
$sql_categorias = "SELECT * FROM categorias ORDER BY nome";
$result_categorias = mysqli_query($conexao, $sql_categorias);
$categorias = mysqli_fetch_all($result_categorias, MYSQLI_ASSOC);

$sql_marcas = "SELECT * FROM marcas ORDER BY nome";
$result_marcas = mysqli_query($conexao, $sql_marcas);
$marcas = mysqli_fetch_all($result_marcas, MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $stock = $_POST['stock'];
    $categoriaID = $_POST['categoriaID'];
    $marcaID = $_POST['marcaID'];

    $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, stock = ?, categoriaID = ?, marcaID = ? WHERE ID = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ssdiisi", $nome, $descricao, $preco, $stock, $categoriaID, $marcaID, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: gerir_produtos.php');
        exit();
    } else {
        $erro = "Erro ao atualizar o produto.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<main class="container my-5">
    <h2 class="text-center mb-4">Editar Produto</h2>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form action="editar_produto.php?id=<?= $id ?>" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($product['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($product['descricao'], ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" class="form-control" id="preco" name="preco" step="0.01" value="<?= htmlspecialchars($product['preco'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($product['stock'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="mb-3">
            <label for="categoriaID" class="form-label">Categoria</label>
            <select class="form-select" id="categoriaID" name="categoriaID" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['ID'] ?>" <?= $product['categoriaID'] == $categoria['ID'] ? 'selected' : '' ?>><?= htmlspecialchars($categoria['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="marcaID" class="form-label">Marca</label>
            <select class="form-select" id="marcaID" name="marcaID" required>
                <option value="">Selecione uma marca</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= $marca['ID'] ?>" <?= $product['marcaID'] == $marca['ID'] ? 'selected' : '' ?>><?= htmlspecialchars($marca['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="gerir_produtos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>
