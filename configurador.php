<?php
session_start();
include 'connect.php';

// Fetch component types
$component_types_query = "SELECT * FROM tipocomponente ORDER BY ID ASC";
$component_types_result = mysqli_query($conn, $component_types_query);
$component_types = [];
while ($row = mysqli_fetch_assoc($component_types_result)) {
    $component_types[] = $row;
}

// Fetch all products for the configurator
$products_query = "SELECT p.*, b.nome as marca_nome, c.nome as categoria_nome FROM produtos p JOIN marcas b ON p.marcaID = b.ID JOIN categorias c ON p.categoriaID = c.ID";
$products_result = mysqli_query($conn, $products_query);
$products = [];
while ($row = mysqli_fetch_assoc($products_result)) {
    $products[] = $row;
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
    });
</script>
</body>
</html>