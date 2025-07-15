<?php
include 'connect.php';
include 'config.php'; // Incluir para aceder a SHIPPING_COST

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit();
}

$utilizadorID = $_SESSION['userID'];

// Ir buscar os dados do utilizador
$sql = "SELECT * FROM utilizadores WHERE ID = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $utilizadorID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Ir buscar os produtos do carrinho ao iniciar sessão
$cart_products = [];
$subtotal_price = 0;
$product_ids = array_keys($_SESSION['carrinho']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$query = "SELECT * FROM produtos WHERE ID IN ($placeholders)";
$stmt = mysqli_prepare($conexao, $query);

// Colar os parâmetros
$types = str_repeat('i', count($product_ids));
mysqli_stmt_bind_param($stmt, $types, ...$product_ids);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $row['quantidade'] = $_SESSION['carrinho'][$row['ID']];
    $cart_products[] = $row;
    $subtotal_price += $row['preco'] * $row['quantidade'];
}

// Calcular o preço total com portes
$total_price = $subtotal_price + SHIPPING_COST;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    mysqli_begin_transaction($conexao);
    try {
        // Verificar stock antes de criar a encomenda
        foreach ($cart_products as $item) {
            $sql_check_stock = "SELECT stock FROM produtos WHERE ID = ? FOR UPDATE"; // FOR UPDATE para bloquear a linha
            $stmt_check_stock = mysqli_prepare($conexao, $sql_check_stock);
            mysqli_stmt_bind_param($stmt_check_stock, "i", $item['ID']);
            mysqli_stmt_execute($stmt_check_stock);
            $result_stock = mysqli_stmt_get_result($stmt_check_stock);
            $product_stock = mysqli_fetch_assoc($result_stock);

            if (!$product_stock || $product_stock['stock'] < $item['quantidade']) {
                throw new mysqli_sql_exception("Stock insuficiente para o produto: " . htmlspecialchars($item['nome']));
            }
        }

        // Criar a encomenda
        $sql_insert_order = "INSERT INTO encomendas (utilizadorID, total, estado) VALUES (?, ?, 'Pendente')";
        $stmt_insert_order = mysqli_prepare($conexao, $sql_insert_order);
        mysqli_stmt_bind_param($stmt_insert_order, "id", $utilizadorID, $total_price);
        mysqli_stmt_execute($stmt_insert_order);
        $encomendaID = mysqli_insert_id($conexao);

        // Mover produtos do carrinho para a tabela de encomenda e decrementar stock
        foreach ($cart_products as $item) {
            $sql_insert_order_product = "INSERT INTO encomendaprodutos (encomendaID, produtoID, quantidade, precoUnitario) VALUES (?, ?, ?, ?)";
            $stmt_insert_order_product = mysqli_prepare($conexao, $sql_insert_order_product);
            mysqli_stmt_bind_param($stmt_insert_order_product, "iiid", $encomendaID, $item['ID'], $item['quantidade'], $item['preco']);
            mysqli_stmt_execute($stmt_insert_order_product);

            $sql_update_stock = "UPDATE produtos SET stock = stock - ? WHERE ID = ?";
            $stmt_update_stock = mysqli_prepare($conexao, $sql_update_stock);
            mysqli_stmt_bind_param($stmt_update_stock, "ii", $item['quantidade'], $item['ID']);
            mysqli_stmt_execute($stmt_update_stock);
        }

        mysqli_commit($conexao);
        // Limpar o carrinho e guardar o ID da encomenda na sessão
        unset($_SESSION['carrinho']);
        $_SESSION['last_order_id'] = $encomendaID;
        header('Location: obrigado.php?order_id=' . $encomendaID);
        exit();
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($conexao);
        // Log the error for debugging
        error_log("Erro na transação de checkout: " . $exception->getMessage());
        // Store the specific error message in the session to display it
        $_SESSION['checkout_error'] = $exception->getMessage();
        // Redirect to an error page or show a user-friendly message
        header('Location: erro.php'); // You might want to create an erro.php page
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Checkout</title>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container mt-5">
    <h1 class="text-center mb-5">Checkout</h1>
    <div class="row">
        <div class="col-md-6">
            <h2>Endereço de Envio</h2>
            <p><?= htmlspecialchars($user['nomeCompleto']) ?></p>
            <p><?= htmlspecialchars($user['morada']) ?></p>
            <p><?= htmlspecialchars($user['codigoPostal']) ?> <?= htmlspecialchars($user['localidade']) ?></p>
            <p><?= htmlspecialchars($user['pais']) ?></p>
        </div>
        <div class="col-md-6">
            <h2>Resumo do Pedido</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_products as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?> x <?= $item['quantidade'] ?></td>
                            <td>€<?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Subtotal:</strong> €<?= number_format($subtotal_price, 2, ',', '.') ?></p>
            <p><strong>Portes de Envio:</strong> €<?= number_format(SHIPPING_COST, 2, ',', '.') ?></p>
            <p><strong>Total: €<?= number_format($total_price, 2, ',', '.') ?></strong></p>
            <form action="checkout.php" method="post">
                <h2>Pagamento</h2>
                <p>Simulação de pagamento. Clique em "Finalizar Encomenda" para completar.</p>
                <button type="submit" class="btn btn-primary">Finalizar Encomenda</button>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
