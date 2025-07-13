<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit();
}

$utilizadorID = $_SESSION['userID'];

// Fetch user data
$sql = "SELECT * FROM utilizadores WHERE ID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $utilizadorID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Fetch cart items from session
$cart_products = [];
$total_price = 0;
$product_ids = array_keys($_SESSION['carrinho']);
$product_ids_str = implode(',', $product_ids);

$query = "SELECT * FROM produtos WHERE ID IN ($product_ids_str)";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $row['quantidade'] = $_SESSION['carrinho'][$row['ID']];
    $cart_products[] = $row;
    $total_price += $row['preco'] * $row['quantidade'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create order
    $sql = "INSERT INTO encomendas (utilizadorID, total, estado) VALUES (?, ?, 'Pendente')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "id", $utilizadorID, $total_price);
    mysqli_stmt_execute($stmt);
    $encomendaID = mysqli_insert_id($conn);

    // Move cart items to order items
    foreach ($cart_products as $item) {
        $sql = "INSERT INTO encomendaprodutos (encomendaID, produtoID, quantidade, precoUnitario) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiid", $encomendaID, $item['ID'], $item['quantidade'], $item['preco']);
        mysqli_stmt_execute($stmt);
    }

    // Clear shopping cart
    unset($_SESSION['carrinho']);

    header('Location: obrigado.php');
    exit();
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
