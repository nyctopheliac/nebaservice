

<<<<<<< Updated upstream
=======
<?php include 'connect.php'; ?>

>>>>>>> Stashed changes
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Obrigado!</title>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container mt-5 text-center">
    <h1 class="mb-4">Obrigado pela sua encomenda!</h1>
    <p>A sua encomenda foi recebida e está a ser processada.</p>
    <p>Receberá um email de confirmação em breve.</p>
    <a href="index.php" class="btn btn-primary mt-3">Voltar à Página Inicial</a>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');

    if (orderId) {
        // Create a hidden iframe to trigger the download
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = 'gerar_fatura.php?order_id=' + orderId;
        document.body.appendChild(iframe);
    }
});
</script>
</body>
</html>
