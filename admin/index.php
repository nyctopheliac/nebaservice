<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page or an error page
    header('Location: ../login.php');
    exit();
}

// The rest of the admin dashboard page
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<main class="container my-5">
    <h2 class="text-center mb-4">Painel de Administração</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gerir Produtos</h5>
                    <p class="card-text">Adicionar, editar e remover produtos e categorias.</p>
                    <a href="gerir_produtos.php" class="btn btn-primary">Gerir Produtos</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gerir Encomendas</h5>
                    <p class="card-text">Ver e atualizar o estado das encomendas.</p>
                    <a href="gerir_encomendas.php" class="btn btn-primary">Gerir Encomendas</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gerir Utilizadores</h5>
                    <p class="card-text">Ver e editar as definições dos utilizadores.</p>
                    <a href="gerir_utilizadores.php" class="btn btn-primary">Gerir Utilizadores</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>
