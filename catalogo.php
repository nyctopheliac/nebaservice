<?php
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Catalogo</title>
    
</head>
<button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
</button>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand nav-logo" href="index.php">Neba<span>Service</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="catalogo.php">Catálogo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="servicos.php">Serviços</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a href="login.php" class="btn btn-outline">Login</a>
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
                $query = "SELECT * FROM produtos";
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

        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="relative w-full md:w-auto">
                <input type="text" placeholder="Buscar produtos..." class="search-box pl-10 pr-4 py-2 border rounded-full w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        
        <!-- Pagination -->
        <div class="flex justify-center mt-10">
            <nav class="inline-flex rounded-md shadow">
                <a href="#" class="px-4 py-2 text-gray-500 bg-white rounded-l-md border border-gray-300 hover:bg-gray-50">Anterior</a>
                <a href="#" class="px-4 py-2 text-blue-600 bg-blue-50 border border-gray-300">1</a>
                <a href="#" class="px-4 py-2 text-gray-500 bg-white border border-gray-300 hover:bg-gray-50">2</a>
                <a href="#" class="px-4 py-2 text-gray-500 bg-white border border-gray-300 hover:bg-gray-50">3</a>
                <a href="#" class="px-4 py-2 text-gray-500 bg-white rounded-r-md border border-gray-300 hover:bg-gray-50">Próximo</a>
            </nav>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
      // Filtros de categoria
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // Aqui você implementaria a filtragem real dos produtos
            });
        });
        
        // Botão de adicionar ao carrinho
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function() {
                const productCard = this.closest('.product-card');
                const productName = productCard.querySelector('h3').textContent;
                const productPrice = productCard.querySelector('.font-bold').textContent;
                
                // Efeito visual
                this.textContent = 'Adicionado!';
                this.disabled = true;
                this.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                this.classList.add('bg-green-500');
                
                // Aqui você implementaria a lógica real para adicionar ao carrinho
                console.log(`Produto adicionado: ${productName} - ${productPrice}`);
                
                // Reset após 1.5 segundos
                setTimeout(() => {
                    this.textContent = 'Adicionar';
                    this.disabled = false;
                    this.classList.remove('bg-green-500');
                    this.classList.add('bg-blue-500', 'hover:bg-blue-600');
                }, 1500);
            });
        });
        
        // Busca de produtos (simples)
        document.querySelector('.search-box').addEventListener('input', function(e) {
            // Aqui você implementaria a busca real
            console.log('Buscando por:', e.target.value);
        });
    </script>
</body>
</html>
