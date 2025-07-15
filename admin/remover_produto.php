<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page or an error page
    header('Location: ../login.php');
    exit();
}

// Include the database connection
require_once '../connect.php';

// Get the product ID from the URL
$id = $_GET['id'];

// Delete the product
$sql = "DELETE FROM produtos WHERE ID = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: gerir_produtos.php');
    exit();
} else {
    echo "Erro ao remover o produto.";
}
