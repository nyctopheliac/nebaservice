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
                // Add each product from the configuration to the cart
                if (!isset($_SESSION['carrinho'][$product_id])) {
                    $_SESSION['carrinho'][$product_id] = 1;
                } else {
                    $_SESSION['carrinho'][$product_id]++;
                }
            }
        }
    } elseif (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        // Add a single product to the cart
        if (!isset($_SESSION['carrinho'][$product_id])) {
            $_SESSION['carrinho'][$product_id] = 1;
        } else {
            $_SESSION['carrinho'][$product_id]++;
        }
    }
}

header('Location: carrinho.php');
exit();
?>
