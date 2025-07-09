<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
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

// Fetch cart items
$sql = "SELECT c.ID, p.nome, p.preco, c.quantidade FROM carrinho c JOIN produtos p ON c.produtoID = p.ID WHERE c.utilizadorID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $utilizadorID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cartItems = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($cartItems)) {
    header('Location: carrinho.php');
    exit();
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create order
    $sql = "INSERT INTO encomendas (utilizadorID, total, estado) VALUES (?, ?, 'Pendente')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "id", $utilizadorID, $total);
    mysqli_stmt_execute($stmt);
    $encomendaID = mysqli_insert_id($conn);

    // Move cart items to order items
    foreach ($cartItems as $item) {
        $sql = "INSERT INTO encomendaprodutos (encomendaID, produtoID, quantidade, precoUnitario) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiid", $encomendaID, $item['ID'], $item['quantidade'], $item['preco']);
        mysqli_stmt_execute($stmt);
    }

    // Clear shopping cart
    $sql = "DELETE FROM carrinho WHERE utilizadorID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $utilizadorID);
    mysqli_stmt_execute($stmt);

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

    <div class="container">
        <h1>Checkout</h1>
        <div class="row">
            <div class="col-md-6">
                <h2>Endereço de Envio</h2>
                <p><?php echo htmlspecialchars($user['nomeCompleto']); ?></p>
                <p><?php echo htmlspecialchars($user['morada']); ?></p>
                <p><?php echo htmlspecialchars($user['codigoPostal']); ?> <?php echo htmlspecialchars($user['localidade']); ?></p>
                <p><?php echo htmlspecialchars($user['pais']); ?></p>
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
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nome']); ?> x <?php echo $item['quantidade']; ?></td>
                                <td><?php echo $item['preco'] * $item['quantidade']; ?>€</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p><strong>Total: <?php echo $total; ?>€</strong></p>
                <form action="checkout.php" method="post">
                    <h2>Pagamento</h2>
                    <p>Simulação de pagamento. Clique em "Finalizar Encomenda" para completar.</p>
                    <button type="submit" class="btn btn-primary">Finalizar Encomenda</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>