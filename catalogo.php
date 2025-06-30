<?php
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Catalogo</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">NebaService</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="pricing.php">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container mt-5">
            <h1 class="text-center">Catálogo de Produtos</h1>
            <div class="row">
                <?php
                $query = "SELECT * FROM products";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card">';
                    echo '<img src="path/to/image.jpg" class="card-img-top" alt="'.$row['productName'].'">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">'.$row['productName'].'</h5>';
                    echo '<p class="card-text">Model: '.$row['productModel'].'</p>';
                    echo '<p class="card-text">Price: €'.$row['price'].'</p>';
                    echo '<a href="#" class="btn btn-primary">Add to Cart</a>';
                    echo '</div></div></div>';
                }
                ?>
            </div>
        </div>
    </main>

    <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h4>NebaService</h4>
          <p>Promovemos serviços profissionais desde 2023.</p>
        </div>
        <div class="col-md-3">
          <h5>Links</h5>
          <ul class="list-unstyled">
            <li><a href="index.php">Home</a></li>
            <li><a href="catalogo.php">Catalogo</a></li>
            <li><a href="servicos.php">Serviços</a></li>
          </ul>
        </div>
        <div class="col-md-3">
          <h5>Contatos</h5>
          <ul class="list-unstyled">
            <li>info@nebaservice.com</li>
            <li>+351 xxx-xxx-xxx</li>
          </ul>
        </div>
      </div>
    </div>
    </footer>    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
