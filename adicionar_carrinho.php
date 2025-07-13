<?php
session_start();

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
