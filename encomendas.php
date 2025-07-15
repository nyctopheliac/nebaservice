<?php
include 'connect.php';
include 'config.php'; // Incluir para aceder a SHIPPING_COST

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$utilizadorID = $_SESSION['userID'];

// Ir buscar as encomendas do utilizador
$sql = "SELECT * FROM encomendas WHERE utilizadorID = ? ORDER BY data DESC";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $utilizadorID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$encomendas = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
                    <h6>Produtos:</h6>
                    <ul>
                        <?php 
                        $subtotal = 0;
                        if (isset($order_products[$encomenda['ID']])):
                            foreach ($order_products[$encomenda['ID']] as $produto): 
                                $subtotal += $produto['precoUnitario'] * $produto['quantidade'];
                        ?>
                                <li><?= htmlspecialchars($produto['nome']) ?> (x<?= $produto['quantidade'] ?>) - €<?= number_format($produto['precoUnitario'] * $produto['quantidade'], 2, ',', '.') ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                    <hr>
                    <p class="card-text"><strong>Subtotal:</strong> €<?= number_format($subtotal, 2, ',', '.') ?></p>
                    <p class="card-text"><strong>Portes de Envio:</strong> €<?= number_format(SHIPPING_COST, 2, ',', '.') ?></p>
                    <p class="card-text"><strong>Total:</strong> €<?= number_format($encomenda['total'], 2, ',', '.') ?></p>
                    <a href="gerar_fatura.php?id=<?= $encomenda['ID'] ?>" class="btn btn-primary" target="_blank">Ver Fatura</a>
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
