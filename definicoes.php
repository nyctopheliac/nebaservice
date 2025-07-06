<?php
session_start();
include('connect.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Saca os detalhes do utilizador
$query = mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email'");
$user = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualizações aos perfis
    if (isset($_POST['update'])) {
        $newEmail = $_POST['email'];
        $newAddress = $_POST['address'];
        $newPassword = $_POST['password'];
        $updateQuery = "UPDATE utilizadores SET email='$newEmail', morada='$newAddress' WHERE email='$email'";
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE utilizadores SET email='$newEmail', morada='$newAddress', passwordHash='$hashedPassword' WHERE email='$email'";
        }
        mysqli_query($conn, $updateQuery);
        $_SESSION['email'] = $newEmail; // Atualiza o email na sessão
        echo "<script>alert('Perfil atualizado com sucesso!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Gerir Perfil</title>
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
    <div>
        <h1>Gerir o Seu Perfil</h1>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
            <label for="address">Morada de Entrega:</label>
            <input type="text" name="address" id="address" value="<?php echo $user['morada']; ?>" required>
            <label for="password">Nova Palavra-passe:</label>
            <input type="password" name="password" id="password">
            <input type="submit" name="update" value="Atualizar Perfil">
        </form>
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
  </script>
</body>
</html>
