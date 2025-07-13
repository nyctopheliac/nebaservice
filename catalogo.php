<?php
include 'connect.php';

// Fetch all categories
$category_query = "SELECT * FROM categorias ORDER BY nome ASC";
$category_result = mysqli_query($conexao, $category_query);
$categories = [];
while ($row = mysqli_fetch_assoc($category_result)) {
    $categories[] = $row;
}

// Fetch all products with their brands
$product_query = "SELECT p.*, b.nome as marca_nome FROM produtos p JOIN marcas b ON p.marcaID = b.ID ORDER BY p.categoriaID, p.nome ASC";
$product_result = mysqli_query($conexao, $product_query);
$products_by_category = [];
while ($row = mysqli_fetch_assoc($product_result)) {
    $products_by_category[$row['categoriaID']][] = $row;
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Catálogo</title>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container mt-5">
    <h1 class="text-center mb-5">Catálogo de Produtos</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <h4 class="list-group-item list-group-item-action active" aria-current="true">
                    Categorias
                </h4>
                <?php foreach ($categories as $category): ?>
                    <a href="#category-<?= $category['ID'] ?>" class="list-group-item list-group-item-action"><?= htmlspecialchars($category['nome']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-9">
            <?php foreach ($categories as $category): ?>
                <div id="category-<?= $category['ID'] ?>">
                    <h2 class="mt-5 mb-3"><?= htmlspecialchars($category['nome']) ?></h2>
                    <div class="row">
                        <?php if (isset($products_by_category[$category['ID']])): ?>
                            <?php foreach ($products_by_category[$category['ID']] as $product): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <img src="imagens/<?= htmlspecialchars($product['imagemPrincipal']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['nome']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($product['nome']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($product['descricao']) ?></p>
                                            <p class="card-text"><strong>Marca:</strong> <?= htmlspecialchars($product['marca_nome']) ?></p>
                                            <p class="card-text"><strong>Preço:</strong> €<?= number_format($product['preco'], 2, ',', '.') ?></p>
                                            <a href="adicionar_carrinho.php?id=<?= $product['ID'] ?>" class="btn btn-primary">Adicionar ao Carrinho</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Não há produtos nesta categoria.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
