<?php
include 'connect.php';

// --- Lógica de Filtragem e Ordenação ---

// Definir valores padrão
$sort_option = $_GET['sort'] ?? 'default';
$search_term = $_GET['search'] ?? '';

// Mapear opções de ordenação para cláusulas SQL
$order_by_map = [
    'price_asc' => 'p.preco ASC',
    'price_desc' => 'p.preco DESC',
    'name_asc' => 'p.nome ASC',
    'name_desc' => 'p.nome DESC',
    'default' => 'p.nome ASC' // Ordenação padrão dentro da categoria
];

// A ordenação principal será sempre por categoria, e a secundária pela opção do user
$order_by = 'p.categoriaID ASC, ' . ($order_by_map[$sort_option] ?? $order_by_map['default']);

// Se for uma pesquisa, a ordenação não agrupa por categoria
if (!empty($search_term)) {
    // Usa a ordenação selecionada, ou o nome como padrão para os resultados da pesquisa
    $order_by = $order_by_map[$sort_option] ?? 'p.nome ASC';
    if($sort_option === 'default') {
        $order_by = 'p.nome ASC';
    }
}


// Construir a cláusula WHERE para a pesquisa
$where_clause = '';
$search_params = [];
if (!empty($search_term)) {
    $where_clause = " WHERE p.nome LIKE ? OR b.nome LIKE ?";
    $search_param = "%{$search_term}%";
    $search_params = [$search_param, $search_param];
}

// --- Fim da Lógica ---

// Fetch all categories
$category_query = "SELECT * FROM categorias ORDER BY nome ASC";
$category_result = mysqli_query($conn, $category_query);
$categories = [];
while ($row = mysqli_fetch_assoc($category_result)) {
    $categories[] = $row;
}

// Fetch all products with their brands, applying filters and sorting
$product_query = "SELECT p.*, b.nome as marca_nome FROM produtos p JOIN marcas b ON p.marcaID = b.ID" . $where_clause . " ORDER BY " . $order_by;
$stmt = mysqli_prepare($conn, $product_query);

if (!empty($search_params)) {
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($search_params)), ...$search_params);
}

mysqli_stmt_execute($stmt);
$product_result = mysqli_stmt_get_result($stmt);

$products_by_category = [];
$search_results = [];

if ($product_result) {
    while ($row = mysqli_fetch_assoc($product_result)) {
        // Se uma pesquisa estiver ativa, usamos uma lista única para os resultados
        if (!empty($search_term)) {
            $search_results[] = $row;
        } else {
            // Caso contrário, agrupamos sempre por categoria
            $products_by_category[$row['categoriaID']][] = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="catalogo.css">
    <title>Catálogo</title>
</head>
<body>

  <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Alternar modo escuro">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="5"></circle>
      <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
    </svg>
  </button>

<?php include 'navbar.php'; ?>

<main class="container-fluid mt-5 px-lg-5">
    <h1 class="text-center mb-5">Catálogo de Produtos</h1>

    <div class="row catalog-main-row">
        <div class="col-md-3">
            <div class="category-sidebar">
                <div class="list-group">
                    <h4 class="list-group-item list-group-item-action active" aria-current="true">
                        Categorias
                    </h4>
                    <?php foreach ($categories as $category): ?>
                        <a href="#category-<?= $category['ID'] ?>" class="list-group-item list-group-item-action"><?= htmlspecialchars($category['nome']) ?></a>
                    <?php endforeach; ?>
                </div>

                <!-- Filtros na Sidebar -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Filtros</h5>
                        <form id="filter-form" action="catalogo.php" method="GET">
                            <div class="mb-3">
                                <label for="search-input" class="form-label small">Pesquisar</label>
                                <input type="text" id="search-input" name="search" class="form-control form-control-sm" placeholder="Produto ou marca..." value="<?= htmlspecialchars($search_term) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="sort-select" class="form-label small">Ordenar por</label>
                                <select id="sort-select" name="sort" class="form-select form-select-sm">
                                    <option value="default" <?= $sort_option == 'default' ? 'selected' : '' ?>>Padrão</option>
                                    <option value="price_asc" <?= $sort_option == 'price_asc' ? 'selected' : '' ?>>Preço (Baixo-Alto)</option>
                                    <option value="price_desc" <?= $sort_option == 'price_desc' ? 'selected' : '' ?>>Preço (Alto-Baixo)</option>
                                    <option value="name_asc" <?= $sort_option == 'name_asc' ? 'selected' : '' ?>>Nome (A-Z)</option>
                                    <option value="name_desc" <?= $sort_option == 'name_desc' ? 'selected' : '' ?>>Nome (Z-A)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Aplicar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <?php if (!empty($search_term)): ?>
                <div id="search-results">
                    <h2 class="mb-4 product-category-title">Resultados da Pesquisa por "<?= htmlspecialchars($search_term) ?>"</h2>
                    <div class="row">
                        <?php if (count($search_results) > 0): ?>
                            <?php foreach ($search_results as $product): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card product-card h-100">
                                        <div class="product-image-container">
                                            <a href="produto.php?id=<?= $product['ID'] ?>">
                                                <img src="imagens/<?= htmlspecialchars($product['imagemPrincipal']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['nome']) ?>">
                                            </a>
                                        </div>
                                                                                    <a href="adicionar_carrinho.php?id=<?= $product['ID'] ?>" class="btn btn-primary btn-add-to-cart"><i class="bi bi-cart"></i></a>
                                        <div class="card-body d-flex flex-column p-3">
                                            <div class="flex-grow-1">
                                                <p class="product-brand text-muted small mb-1"><?= htmlspecialchars($product['marca_nome']) ?></p>
                                                <h5 class="product-title mb-2">
                                                    <a href="produto.php?id=<?= $product['ID'] ?>" class="text-decoration-none" title="<?= htmlspecialchars($product['nome']) ?>">
                                                        <?= htmlspecialchars($product['nome']) ?>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="mt-auto">
                                                <p class="product-price fw-bold mb-0">€<?= number_format($product['preco'], 2, ',', '.') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Não foram encontrados produtos a corresponder à sua pesquisa.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($categories as $category): ?>
                    <?php if (isset($products_by_category[$category['ID']]) && count($products_by_category[$category['ID']]) > 0): ?>
                        <div id="category-<?= $category['ID'] ?>" class="category-anchor">
                            <h2 class="mt-5 mb-4 product-category-title"><?= htmlspecialchars($category['nome']) ?></h2>
                            <div class="row">
                                <?php foreach ($products_by_category[$category['ID']] as $product): ?>
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card product-card h-100">
                                            <div class="product-image-container">
                                                <a href="produto.php?id=<?= $product['ID'] ?>">
                                                    <img src="imagens/<?= htmlspecialchars($product['imagemPrincipal']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['nome']) ?>">
                                                </a>
                                            </div>
                                            <a href="adicionar_carrinho.php?id=<?= $product['ID'] ?>" class="btn btn-primary btn-add-to-cart"><i class="bi bi-cart"></i></a>
                                            <div class="card-body d-flex flex-column p-3">
                                                <div class="flex-grow-1">
                                                    <p class="product-brand text-muted small mb-1"><?= htmlspecialchars($product['marca_nome']) ?></p>
                                                    <h5 class="product-title mb-2">
                                                        <a href="produto.php?id=<?= $product['ID'] ?>" class="text-decoration-none" title="<?= htmlspecialchars($product['nome']) ?>">
                                                            <?= htmlspecialchars($product['nome']) ?>
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div class="mt-auto">
                                                    <p class="product-price fw-bold mb-0">€<?= number_format($product['preco'], 2, ',', '.') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll suave para as âncoras de categoria
    document.querySelectorAll('.category-sidebar a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            let targetId = this.getAttribute('href');
            let targetElement = document.querySelector(targetId);

            if(targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
</body>
</html>