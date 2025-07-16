<?php
session_start();
include 'connect.php';

// Function to add a single product to the cart
function add_product_to_cart($conexao, $utilizadorID, $produtoID) {
    $quantidade = 1; // Default quantity

    // Check if the product is already in the cart
    $sql = "SELECT * FROM carrinho WHERE utilizadorID = ? AND produtoID = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $utilizadorID, $produtoID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Product already in cart, update quantity
        $row = mysqli_fetch_assoc($result);
        $newQuantity = $row['quantidade'] + 1;
        $sql = "UPDATE carrinho SET quantidade = ? WHERE ID = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $newQuantity, $row['ID']);
        mysqli_stmt_execute($stmt);
    } else {
        // Product not in cart, insert new row
        $sql = "INSERT INTO carrinho (utilizadorID, produtoID, quantidade) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $utilizadorID, $produtoID, $quantidade);
        mysqli_stmt_execute($stmt);
    }
    return true;
}

if (isset($_SESSION['userID'])) {
    $utilizadorID = $_SESSION['userID'];

    if (isset($_GET['id'])) {
        // Handle single product addition from catalogo.php
        $produtoID = $_GET['id'];
        if (add_product_to_cart($conexao, $utilizadorID, $produtoID)) {
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Produto adicionado ao carrinho!']);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['message' => 'Erro ao adicionar produto ao carrinho.']);
        }
    } elseif (isset($_POST['configuracao'])) {
        // Handle multiple product addition from configurador.php
        $configuracao = json_decode($_POST['configuracao'], true);
        $all_added = true;
        foreach ($configuracao as $produtoID) {
            if (!add_product_to_cart($conexao, $utilizadorID, $produtoID)) {
                $all_added = false;
                break;
            }
        }

        if ($all_added) {
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Configuração adicionada ao carrinho!']);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['message' => 'Erro ao adicionar configuração ao carrinho.']);
        }
    } else {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['message' => 'Dados inválidos para adicionar ao carrinho.']);
    }
} else {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['message' => 'Utilizador não autenticado.']);
}
?>