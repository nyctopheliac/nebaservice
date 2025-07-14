<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['quantidade'])) {
        $product_id = $_POST['id'];
        $quantity = (int)$_POST['quantidade'];

        if ($quantity > 0) {
            $_SESSION['carrinho'][$product_id] = $quantity;
        } else {
            unset($_SESSION['carrinho'][$product_id]);
        }
    }
}

header('Location: carrinho.php');
exit();
?>
