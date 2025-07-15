<?php
if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false, // Altere para true se o site estiver em HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}
// Configuração de tratamento de erros
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log'); // Caminho para o arquivo de log de erros

// Configuração de cabeçalhos de segurança HTTP
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
// HSTS (Strict-Transport-Security) - Apenas para HTTPS
// header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
// Content-Security-Policy - Ajuste conforme necessário para evitar quebras
header("Content-Security-Policy: default-src 'self' https://cdn.jsdelivr.net; script-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; img-src 'self' data:; font-src 'self' https://cdn.jsdelivr.net;");

// Estabelece uma conexão com a base de dados MySQL.
require_once 'config.php';

$conexao = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if (!$conexao) {
    error_log("Falha na conexão com a base de dados: " . mysqli_connect_error());
    die("Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.");
}
?>
