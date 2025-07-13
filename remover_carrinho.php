<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    unset($_SESSION['carrinho'][$product_id]);
}

header('Location: carrinho.php');
exit();
?>
