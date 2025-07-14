<?php
<<<<<<< Updated upstream
session_start();
=======
>>>>>>> Stashed changes
include 'connect.php';

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$utilizadorID = $_SESSION['userID'];

<<<<<<< Updated upstream
// Fetch orders
$sql = "SELECT * FROM encomendas WHERE utilizadorID = ? ORDER BY data DESC";
$stmt = mysqli_prepare($conn, $sql);
=======
// Ir buscar as encomendas do utilizador
$sql = "SELECT * FROM encomendas WHERE utilizadorID = ? ORDER BY data DESC";
$stmt = mysqli_prepare($conexao, $sql);
>>>>>>> Stashed changes
mysqli_stmt_bind_param($stmt, "i", $utilizadorID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$encomendas = mysqli_fetch_all($result, MYSQLI_ASSOC);

<<<<<<< Updated upstream
=======
// Sacar os produtos de cada encomenda
$order_products = [];
$order_ids = array_column($encomendas, 'ID');
if (!empty($order_ids)) {
    // Criar placeholders para a consulta
    $placeholders = implode(',', array_fill(0, count($order_ids), '?'));
    $sql = "SELECT ep.encomendaID, p.nome, ep.quantidade, ep.precoUnitario FROM encomendaprodutos ep JOIN produtos p ON ep.produtoID = p.ID WHERE ep.encomendaID IN ($placeholders)";
    $stmt = mysqli_prepare($conexao, $sql);

    // Colar os parâmetros uns aos outros
    $types = str_repeat('i', count($order_ids));
    mysqli_stmt_bind_param($stmt, $types, ...$order_ids);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $order_products[$row['encomendaID']][] = $row;
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
    <title>As Minhas Encomendas</title>
</head>
<body>

<<<<<<< Updated upstream
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>As Minhas Encomendas</h1>
        <?php if (empty($encomendas)): ?>
            <p>Não tem encomendas.</p>
        <?php else: ?>
            <?php foreach ($encomendas as $encomenda): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        Encomenda #<?php echo $encomenda['ID']; ?> - <?php echo $encomenda['data']; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Estado: <?php echo htmlspecialchars($encomenda['estado']); ?></h5>
                        <p class="card-text">Total: <?php echo htmlspecialchars($encomenda['total']); ?>€</p>
                        <h6>Produtos:</h6>
                        <ul>
                            <?php
                            $sql = "SELECT p.nome, ep.quantidade, ep.precoUnitario FROM encomendaprodutos ep JOIN produtos p ON ep.produtoID = p.ID WHERE ep.encomendaID = ?";
                            $stmt = mysqli_prepare($conn, $sql);
                            mysqli_stmt_bind_param($stmt, "i", $encomenda['ID']);
                            mysqli_stmt_execute($stmt);
                            $produtosResult = mysqli_stmt_get_result($stmt);
                            $produtos = mysqli_fetch_all($produtosResult, MYSQLI_ASSOC);
                            foreach ($produtos as $produto) {
                                echo "<li>" . htmlspecialchars($produto['nome']) . " (x" . $produto['quantidade'] . ") - " . htmlspecialchars($produto['precoUnitario']) . "€</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
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
    <h1 class="text-center mb-5">As Minhas Encomendas</h1>
    <?php if (empty($encomendas)): ?>
        <div class="alert alert-info" role="alert">
            Não tem encomendas.
        </div>
    <?php else: ?>
        <?php foreach ($encomendas as $encomenda): ?>
            <div class="card mb-3">
                <div class="card-header">
                    Encomenda #<?= $encomenda['ID'] ?> - <?= date('d/m/Y H:i', strtotime($encomenda['data'])) ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Estado: <?= htmlspecialchars($encomenda['estado']) ?></h5>
                    <p class="card-text">Total: €<?= number_format($encomenda['total'], 2, ',', '.') ?></p>
                    <h6>Produtos:</h6>
                    <ul>
                        <?php if (isset($order_products[$encomenda['ID']])): ?>
                            <?php foreach ($order_products[$encomenda['ID']] as $produto): ?>
                                <li><?= htmlspecialchars($produto['nome']) ?> (x<?= $produto['quantidade'] ?>) - €<?= number_format($produto['precoUnitario'], 2, ',', '.') ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>
>>>>>>> Stashed changes
