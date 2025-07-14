<?php
<<<<<<< Updated upstream
session_start();
include 'connect.php';

if (isset($_POST['produtoID']) && isset($_SESSION['userID'])) {
    $produtoID = $_POST['produtoID'];
    $utilizadorID = $_SESSION['userID'];
    $quantidade = 1; // Default quantity

    // Check if the product is already in the cart
    $sql = "SELECT * FROM carrinho WHERE utilizadorID = ? AND produtoID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $utilizadorID, $produtoID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Product already in cart, update quantity
        $row = mysqli_fetch_assoc($result);
        $newQuantity = $row['quantidade'] + 1;
        $sql = "UPDATE carrinho SET quantidade = ? WHERE ID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $newQuantity, $row['ID']);
        mysqli_stmt_execute($stmt);
    } else {
        // Product not in cart, insert new row
        $sql = "INSERT INTO carrinho (utilizadorID, produtoID, quantidade) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $utilizadorID, $produtoID, $quantidade);
        mysqli_stmt_execute($stmt);
    }

    echo "Produto adicionado ao carrinho!";
} else {
    echo "Erro ao adicionar produto ao carrinho.";
}
?>
=======
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['configuracao'])) {
        $product_ids = json_decode($_POST['configuracao'], true);
        if (is_array($product_ids)) {
            foreach ($product_ids as $product_id) {
                // Adicionar cada produto da configuração ao carrinho
                if (!isset($_SESSION['carrinho'][$product_id])) {
                    $_SESSION['carrinho'][$product_id] = 1;
                } else {
                    $_SESSION['carrinho'][$product_id]++;
                }
            }
        }
    }
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    // Adicionar um único produto ao carrinho
    if (!isset($_SESSION['carrinho'][$product_id])) {
        $_SESSION['carrinho'][$product_id] = 1;
    } else {
        $_SESSION['carrinho'][$product_id]++;
    }
}

header('Location: carrinho.php');
exit();
?>
>>>>>>> Stashed changes
