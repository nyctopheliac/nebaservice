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
        $updateQuery = "UPDATE utilizadores SET email='$newEmail', morada='$newAddress' WHERE email='$email'";
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE utilizadores SET email='$newEmail', morada='$newAddress', passwordHash='$hashedPassword' WHERE email='$email'";
        }
        mysqli_query($conn, $updateQuery);
        $_SESSION['email'] = $newEmail; // Update ao email na sessão
        echo "<script>alert('Profile updated successfully!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Catálogo</title>
</head>
<button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
</button>
<body>
    <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
        </svg>
    </button>

    <nav>
        <ul>
            <li><a href="index.php">NebaService</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="servicos.php">Serviços</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div>
        <h1>Catálogo de Produtos</h1>
        <!-- fazer a porra do catálogo -->
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4>NebaService</h4>
                    <p>Providing professional services since 2025.</p>
                </div>
                <div class="col-md-3">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="servicos.php">Serviços</a></li>
                        <li><a href="catalogo.php">Catálogo</a></li>
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
