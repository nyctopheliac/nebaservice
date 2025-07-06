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
    <link rel="stylesheet" href="style.css">
    <title>Gerir Perfil</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">NebaService</a></li>
            <li><a href="catalogo.php">Catálogo</a>
        </li>
            <li class="nav-item">
                <a class="nav-link" href="servicos.php">Serviços</a>
            </li>
            <li><a href="login.php">Login</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
    
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

    <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h4>NebaService</h4>
          <p>A fornecer serviços profissionais desde 2025.</p>
        </div>
        <div class="col-md-3">
          <h5>Links</h5>
          <ul class="list-unstyled">
            <li><a href="index.php">Home</a></li>
            <li><a href="servicos.php">Serviços</a></li>
            <li><a href="catalogo.php">Catalogo</a></li>
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
</body>
</html>
