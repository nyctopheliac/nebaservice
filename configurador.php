<?php
session_start();
include 'connect.php';

// Ir buscar os tipos de componente à base de dados
$component_types_query = "SELECT * FROM tipocomponente ORDER BY ID ASC";
$component_types_result = mysqli_query($conn, $component_types_query);
$component_types = mysqli_fetch_all($component_types_result, MYSQLI_ASSOC);

// Ir buscar os produtos disponiveis para cada tipo de componente assumindo que cada tipo de componente tem uma categoria associada
$products_query = "SELECT p.*, b.nome as marca_nome, c.nome as categoria_nome FROM produtos p JOIN marcas b ON p.marcaID = b.ID JOIN categorias c ON p.categoriaID = c.ID";
$products_result = mysqli_query($conn, $products_query);
$products = mysqli_fetch_all($products_result, MYSQLI_ASSOC);

// Ir buscar todas as especificações dos produtos
$specs_query = "SELECT * FROM especificacoes";
$specs_result = mysqli_query($conn, $specs_query);
$specifications = [];
while ($row = mysqli_fetch_assoc($specs_result)) {
    $specifications[$row['produtoID']][] = $row;
}

// Ir buscar todas as regras de compatibilidade
$compatibility_query = "SELECT * FROM compatibilidade";
$compatibility_result = mysqli_query($conexao, $compatibility_query);
$compatibilities = [];
while ($row = mysqli_fetch_assoc($compatibility_result)) {
    $compatibilities[$row['produtoID']][] = $row;
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Configurador de PC</title>
</head>
<body>

<button id="darkModeToggle" class="dark-mode-toggle" aria-label="Alternar modo escuro">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="5"></circle>
      <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
    </svg>
</button>

<?php include 'navbar.php'; ?>

<main class="container mt-5">
    <h1 class="text-center mb-5">Configurador de PC</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Filtros</h5>
                    <div class="mb-3">
                        <label for="minPrice" class="form-label">Preço Mínimo (€)</label>
                        <input type="number" class="form-control form-control-sm" id="minPrice" placeholder="0">
                    </div>
                    <div class="mb-3">
                        <label for="maxPrice" class="form-label">Preço Máximo (€)</label>
                        <input type="number" class="form-control form-control-sm" id="maxPrice" placeholder="10000">
                    </div>
                    <button type="button" id="applyFiltersBtn" class="btn btn-primary w-100">Aplicar Filtros</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <form id="configuratorForm">
                <?php foreach ($component_types as $type): ?>
                    <div class="mb-3">
                        <label for="<?= $type['slug'] ?>" class="form-label"><h4><?= htmlspecialchars($type['nome']) ?></h4></label>
                        <select class="form-select product-select" id="<?= $type['slug'] ?>" name="<?= $type['slug'] ?>" data-type-id="<?= $type['ID'] ?>">
                            <option value="" data-price="0">Selecione...</option>
                            <?php foreach ($products as $product): ?>
                                <?php if ($product['categoria_nome'] == $type['nome']): ?>
                                    <option value="<?= $product['ID'] ?>" data-price="<?= $product['preco'] ?>">
                                        <?= htmlspecialchars($product['nome']) ?> - €<?= number_format($product['preco'], 2, ',', '.') ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>

        <div class="col-md-3">
            <div class="card sticky-top">
                <div class="card-body">
                    <h4 class="card-title themed-title">Resumo da Configuração</h4>
                    <ul id="summaryList" class="list-group list-group-flush"></ul>
                    <hr>
                    <h5 class="text-end">Total: <span id="totalPrice">€0,00</span></h5>
                    <button type="button" id="addToCartBtn" class="btn btn-primary w-100 mt-3">Adicionar ao Carrinho</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script src="confirmation.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allProducts = <?= json_encode($products) ?>;
        const allSpecifications = <?= json_encode($specifications) ?>;
        const allCompatibilities = <?= json_encode($compatibilities) ?>;
        const allCategories = <?= json_encode($component_types) ?>;

        // Now load global.js after these variables are defined
        const globalScript = document.createElement('script');
        globalScript.src = 'global.js';
        document.body.appendChild(globalScript);
    });
</script>
<script>
    const allProducts = <?= json_encode($products) ?>;
    const allSpecifications = <?= json_encode($specifications) ?>;
    const allCompatibilities = <?= json_encode($compatibilities) ?>;
    const allCategories = <?= json_encode($component_types) ?>;
</script>
</body>
</html>
</body>
</html>