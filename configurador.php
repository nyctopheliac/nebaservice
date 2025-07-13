<?php
include 'connect.php';

// Ir buscar os tipos de componente à base de dados
$component_types_query = "SELECT * FROM tipocomponente ORDER BY ID ASC";
$component_types_result = mysqli_query($conexao, $component_types_query);
$component_types = mysqli_fetch_all($component_types_result, MYSQLI_ASSOC);

// Ir buscar os produtos disponiveis para cada tipo de componente assumindo que cada tipo de componente tem uma categoria associada
$products_query = "SELECT p.*, b.nome as marca_nome, c.nome as categoria_nome FROM produtos p JOIN marcas b ON p.marcaID = b.ID JOIN categorias c ON p.categoriaID = c.ID";
$products_result = mysqli_query($conexao, $products_query);
$products = mysqli_fetch_all($products_result, MYSQLI_ASSOC);

// Ir buscar todas as especificações dos produtos
$specs_query = "SELECT * FROM especificacoes";
$specs_result = mysqli_query($conexao, $specs_query);
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

<?php include 'navbar.php'; ?>

<main class="container mt-5">
    <h1 class="text-center mb-5">Configurador de PC</h1>

    <div class="row">
        <div class="col-md-8">
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

        <div class="col-md-4">
            <div class="card sticky-top">
                <div class="card-body">
                    <h4 class="card-title">Resumo da Configuração</h4>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelects = document.querySelectorAll('.product-select');
        const summaryList = document.getElementById('summaryList');
        const totalPriceEl = document.getElementById('totalPrice');
        const addToCartBtn = document.getElementById('addToCartBtn');

        let selectedProducts = {};
        let totalPrice = 0;

        productSelects.forEach(select => {
            select.addEventListener('change', function() {
                const productId = this.value;
                const typeId = this.dataset.typeId;
                const selectedOption = this.options[this.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const name = selectedOption.text;

                if (selectedProducts[typeId]) {
                    totalPrice -= selectedProducts[typeId].price;
                }

                if (productId) {
                    selectedProducts[typeId] = { id: productId, name: name, price: price };
                    totalPrice += price;
                } else {
                    delete selectedProducts[typeId];
                }

                updateSummary();
                updateTotalPrice();
            });
        });

        function updateSummary() {
            summaryList.innerHTML = '';
            for (const typeId in selectedProducts) {
                const product = selectedProducts[typeId];
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.textContent = product.name;
                summaryList.appendChild(listItem);
            }
        }

        function updateTotalPrice() {
            totalPriceEl.textContent = `€${totalPrice.toFixed(2).replace('.', ',')}`;
        }

        function getProductSpecs(productId) {
            return allSpecifications[productId] || [];
        }

        function getProductCompatibility(productId) {
            return allCompatibilities[productId] || [];
        }

        function filterProducts() {
            productSelects.forEach(select => {
                const currentTypeId = select.dataset.typeId;
                const selectedProductInThisCategory = selectedProducts[currentTypeId];

                // Store the currently selected value to re-select it if it's still valid
                const previouslySelectedValue = select.value;

                // Clear and re-populate options
                select.innerHTML = '<option value="" data-price="0">Selecione...</option>';

                allProducts.forEach(product => {
                    // Check if the product belongs to the current select's category
                    const productCategory = allCategories.find(cat => cat.nome === product.categoria_nome);
                    if (!productCategory || productCategory.ID != currentTypeId) {
                        return; // Skip if not in this category
                    }

                    let isCompatible = true;

                    // Check compatibility with all other selected products
                    for (const typeId in selectedProducts) {
                        if (typeId === currentTypeId) continue; // Don't check against itself

                        const selectedProduct = selectedProducts[typeId];
                        const selectedProductSpecs = getProductSpecs(selectedProduct.id);
                        const selectedProductCompatibility = getProductCompatibility(selectedProduct.id);

                        // Check if the current product is compatible with the selected product
                        // This is a simplified example. Real compatibility would be more complex.
                        // For instance, if a CPU is selected, check if the motherboard supports its socket.
                        // If a motherboard is selected, check if the CPU fits its socket.

                        // Example: Motherboard compatibility with CPU
                        if (productCategory.nome === 'Motherboards' && selectedProduct.categoria_nome === 'Processadores') {
                            const motherboardCompatibility = getProductCompatibility(product.ID);
                            const cpuSocket = selectedProductSpecs.find(spec => spec.chaves === 'Socket');
                            if (cpuSocket && motherboardCompatibility.length > 0) {
                                const compatibleSocket = motherboardCompatibility.find(comp => {
                                    try {
                                        const compData = JSON.parse(comp.compativelcom);
                                        return compData.socket === cpuSocket.valor;
                                    } catch (e) {
                                        return false;
                                    }
                                });
                                if (!compatibleSocket) {
                                    isCompatible = false;
                                }
                            }
                        }

                        // Example: CPU compatibility with Motherboard
                        if (productCategory.nome === 'Processadores' && selectedProduct.categoria_nome === 'Motherboards') {
                            const cpuCompatibility = getProductCompatibility(product.ID);
                            const motherboardSocket = selectedProductSpecs.find(spec => spec.chaves === 'Socket');
                            if (motherboardSocket && cpuCompatibility.length > 0) {
                                const compatibleSocket = cpuCompatibility.find(comp => {
                                    try {
                                        const compData = JSON.parse(comp.compativelcom);
                                        return compData.socket === motherboardSocket.valor;
                                    } catch (e) {
                                        return false;
                                    }
                                });
                                if (!compatibleSocket) {
                                    isCompatible = false;
                                }
                            }
                        }
                    }

                    if (isCompatible) {
                        const option = document.createElement('option');
                        option.value = product.ID;
                        option.dataset.price = product.preco;
                        option.textContent = `${product.nome} - €${product.preco.toFixed(2).replace('.', ',')}`;
                        select.appendChild(option);
                    }
                });

                // Re-select the previously selected value if it's still in the options
                if (previouslySelectedValue && select.querySelector(`option[value="${previouslySelectedValue}"]`)) {
                    select.value = previouslySelectedValue;
                } else {
                    select.value = ""; // Reset if not compatible anymore
                    // Also remove from selectedProducts if it became incompatible
                    if (selectedProductInThisCategory && selectedProductInThisCategory.id == previouslySelectedValue) {
                        delete selectedProducts[currentTypeId];
                        updateSummary();
                        updateTotalPrice();
                    }
                }
            });
        }

        productSelects.forEach(select => {
            select.addEventListener('change', function() {
                const productId = this.value;
                const typeId = this.dataset.typeId;
                const selectedOption = this.options[this.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const name = selectedOption.textContent;

                if (selectedProducts[typeId]) {
                    totalPrice -= selectedProducts[typeId].price;
                }

                if (productId) {
                    selectedProducts[typeId] = { id: productId, name: name, price: price, categoria_nome: allCategories.find(cat => cat.ID == typeId).nome };
                    totalPrice += price;
                } else {
                    delete selectedProducts[typeId];
                }

                updateSummary();
                updateTotalPrice();
                filterProducts(); // Re-filter products after a selection changes
            });
        });

        addToCartBtn.addEventListener('click', function() {
            const productIds = Object.values(selectedProducts).map(p => p.id);
            if (productIds.length > 0) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'adicionar_carrinho.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'configuracao';
                input.value = JSON.stringify(productIds);

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('Selecione pelo menos um componente.');
            }
        });

        // Initial filtering when the page loads
        filterProducts();

    });

    const allProducts = <?= json_encode($products) ?>;
    const allSpecifications = <?= json_encode($specifications) ?>;
    const allCompatibilities = <?= json_encode($compatibilities) ?>;
    const allCategories = <?= json_encode($component_types) ?>;

</script>
</body>
</html>