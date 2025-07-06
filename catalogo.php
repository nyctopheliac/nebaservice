<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user details
$query = mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email'");
$user = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Updates ao perfil do utilizador
    if (isset($_POST['update'])) {
        $newEmail = $_POST['email'];
        $newAddress = $_POST['address'];
        $newPassword = $_POST['password'];

        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE utilizadores SET email = ?, morada = ?, passwordHash = ? WHERE email = ?");
            $stmt->bind_param("ssss", $newEmail, $newAddress, $hashedPassword, $email);
        } else {
            $stmt = $conn->prepare("UPDATE utilizadores SET email = ?, morada = ? WHERE email = ?");
            $stmt->bind_param("sss", $newEmail, $newAddress, $email);
        }
        $stmt->execute();

        $_SESSION['email'] = $newEmail; // Update ao email na sessão
        echo "<script>alert('Perfil atualizado com sucesso!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Catlogo</title>
</head>
<body>

  <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="5"></circle>
      <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
    </svg>
  </button>

  <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
          <a class="navbar-brand nav-logo" href="index.php">Neba<span>Service</span></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                  <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                  <li class="nav-item"><a class="nav-link" href="catalogo.php">Catalogo</a></li>
                  <li class="nav-item"><a class="nav-link" href="servicos.php">Serviços</a></li>
                  <li class="nav-item ms-lg-3">
                      <a href="login.php" class="btn btn-outline">Login</a>
                  </li>
                  <li class="nav-item profile-icon">
                      <img src="images/profile-icon.png" alt="Profile" width="30" height="30" id="profileIcon">
                      <div class="profile-dropdown" id="profileDropdown">~
                          <a href="profile.php">Profile Settings</a>
                          <a href="delivery.php">Check Deliveries</a>
                          <a href="logout.php">Logout</a>
                      </div>
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
                    echo '<p class="card-text">Modelo: '.$row['productModel'].'</p>';
                    echo '<p class="card-text">Preço: €'.$row['price'].'</p>';
                    echo '<a href="#" class="btn btn-primary">Adicionar ao Carrinho</a>';
                    echo '</div></div></div>';
                }
                ?>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="relative w-full md:w-auto">
                <input type="text" placeholder="Buscar produtos..." class="search-box pl-10 pr-4 py-2 border rounded-full w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <!-- Grelha de Produtos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Paginação -->
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
                <div class="col-md-4">
                    <h4>NebaService</h4>
                    <p>Promovemos serviços profissionais desde 2023.</p>
                </div>
                <div class="col-md-4">
                    <h5>Precisa de ajuda?</h5>
                    <ul class="list-unstyled">
                        <li><a href="ajuda.php">Ajuda</a></li>
                        <li><a href="encomendas.php">Envio de encomendas e portes</a></li>
                        <li><a href="trocaedevo.php">Trocas e devoluções</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="servicos.php">Serviços</a></li>
                        <li><a href="catalogo.php">Catalogo</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>Contactos</h5>
                    <ul class="list-unstyled">
                        <li>info@nebaservice.com</li>
                        <li>+351 xxx-xxx-xxx</li>
                        <li><a href="#">Instagram</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        const sunIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5"></circle>
                <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
            </svg>
        `;
        const moonIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        `;

        const currentTheme = localStorage.getItem('theme') || 
            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        
        if (currentTheme === 'dark') {
            document.body.classList.add('dark');
            darkModeToggle.innerHTML = moonIcon;
        } else {
            darkModeToggle.innerHTML = sunIcon;
        }

        darkModeToggle.addEventListener('click', () => {
            const isDark = document.body.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            darkModeToggle.innerHTML = isDark ? moonIcon : sunIcon;
        });
        });
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