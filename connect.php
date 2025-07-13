<?php
// Estabelece uma conexão com a base de dados MySQL.
$servidor = "localhost";
$utilizador = "root";
$senha = "";
$base_dados = "nebaservice";

$conexao = mysqli_connect($servidor, $utilizador, $senha, $base_dados);
if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>
