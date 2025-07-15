<?php
session_start();
include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro na Encomenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Ocorreu um erro!</h4>
            <p class="error-text">Lamentamos, mas não foi possível processar a sua encomenda. Um erro inesperado aconteceu.</p>
            <?php if (isset($_SESSION['checkout_error'])):
                // Remove a mensagem de erro da sessão para não a mostrar novamente
                $errorMessage = $_SESSION['checkout_error'];
                unset($_SESSION['checkout_error']);
            ?>
                <div class="alert alert-warning">
                    <strong>Detalhes do erro:</strong>
                    <p class="error-text-warning"><?= htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>
            <hr>
            <p class="mb-0 error-text">Por favor, tente novamente mais tarde ou contacte o suporte se o problema persistir.</p>
            <a href="carrinho.php" class="btn btn-primary mt-3">Voltar ao Carrinho</a>
        </div>
    </div>

    <div id="darkModeToggle" class="dark-mode-toggle">
        <!-- O ícone será inserido aqui via JavaScript -->
    </div>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>
