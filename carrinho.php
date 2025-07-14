<?php
<<<<<<< Updated upstream
session_start();
include 'connect.php';

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$utilizadorID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $carrinhoID = $_POST['carrinhoID'];
        $quantidade = $_POST['quantidade'];
        $sql = "UPDATE carrinho SET quantidade = ? WHERE ID = ? AND utilizadorID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $quantidade, $carrinhoID, $utilizadorID);
        mysqli_stmt_execute($stmt);
    } elseif (isset($_POST['remove_item'])) {
        $carrinhoID = $_POST['carrinhoID'];
        $sql = "DELETE FROM carrinho WHERE ID = ? AND utilizadorID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $carrinhoID, $utilizadorID);
        mysqli_stmt_execute($stmt);
    }
}

$sql = "SELECT c.ID, p.nome, p.preco, c.quantidade FROM carrinho c JOIN produtos p ON c.produtoID = p.ID WHERE c.utilizadorID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $utilizadorID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cartItems = mysqli_fetch_all($result, MYSQLI_ASSOC);

=======
include 'connect.php';

$cart_products = [];
$total_price = 0;

if (!empty($_SESSION['carrinho'])) {
    $product_ids = array_keys($_SESSION['carrinho']);
    // Higienizar os IDs dos produtos 
    $product_ids = array_map('intval', $product_ids);
    $product_ids_str = implode(',', $product_ids);
    $query = "SELECT * FROM produtos WHERE ID IN ($product_ids_str)";
    $result = mysqli_query($conexao, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantidade'] = $_SESSION['carrinho'][$row['ID']];
        $cart_products[] = $row;
        $total_price += $row['preco'] * $row['quantidade'];
    }
}
>>>>>>> Stashed changes
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Carrinho de Compras</title>
</head>
<body>

<<<<<<< Updated upstream
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Carrinho de Compras</h1>
        <?php if (empty($cartItems)): ?>
            <p>O seu carrinho está vazio.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nome']); ?></td>
                            <td><?php echo htmlspecialchars($item['preco']); ?>€</td>
                            <td>
                                <form action="carrinho.php" method="post">
                                    <input type="hidden" name="carrinhoID" value="<?php echo $item['ID']; ?>">
                                    <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1">
                                    <button type="submit" name="update_quantity">Atualizar</button>
                                </form>
                            </td>
                            <td><?php echo $item['preco'] * $item['quantidade']; ?>€</td>
                            <td>
                                <form action="carrinho.php" method="post">
                                    <input type="hidden" name="carrinhoID" value="<?php echo $item['ID']; ?>">
                                    <button type="submit" name="remove_item">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="checkout.php" class="btn btn-primary">Checkout</a>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
=======
<?php include 'navbar.php'; ?>

<main class="container mt-5">
    <h1 class="text-center mb-5">Carrinho de Compras</h1>

    <?php if (empty($cart_products)): ?>
        <div class="alert alert-info" role="alert">
            O seu carrinho está vazio.
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['nome']) ?></td>
                        <td>€<?= number_format($product['preco'], 2, ',', '.') ?></td>
                        <td>
                            <form action="atualizar_carrinho.php" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?= $product['ID'] ?>">
                                <input type="number" name="quantidade" value="<?= $product['quantidade'] ?>" min="1" class="form-control" style="width: 80px;">
                                <button type="submit" class="btn btn-sm btn-primary">Atualizar</button>
                            </form>
                        </td>
                        <td>€<?= number_format($product['preco'] * $product['quantidade'], 2, ',', '.') ?></td>
                        <td>
                            <a href="remover_carrinho.php?id=<?= $product['ID'] ?>" class="btn btn-sm btn-danger">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end">
            <h4>Total: €<?= number_format($total_price, 2, ',', '.') ?></h4>
            <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
        </div>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
>>>>>>> Stashed changes
