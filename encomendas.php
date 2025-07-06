<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email'");
$user = mysqli_fetch_assoc($query);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deliveryAddress = $_POST['deliveryAddress'];
    $updateQuery = "UPDATE utilizadores SET morada='$deliveryAddress' WHERE email='$email'";
    mysqli_query($conn, $updateQuery);
    echo "<script>alert('Morada de entrega atualizada com sucesso!');</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Informação de Entrega</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">NebaService</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link" href="servicos.php">Serviços</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                    <li class="nav-item profile-icon">
                        <img src="images/profile-icon.png" alt="Profile" width="30" height="30" id="profileIcon">
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="profile.php">Definições de Perfil</a>
                            <a href="delivery.php">Verificar Entregas</a>
                            <a href="logout.php">Sair</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Informação de Entrega</h1>
        <form method="post">
            <label for="deliveryAddress">Morada de Entrega:</label>
            <input type="text" name="deliveryAddress" id="deliveryAddress" value="<?php echo $user['morada']; ?>" required>
            <input type="submit" value="Atualizar Morada de Entrega">
        </form>

        <h2>O seu Histórico de Entregas</h2>
        <div id="deliveryHistory">
            <?php
            $historyQuery = mysqli_query($conn, "SELECT * FROM delivery_history WHERE user_email='$email'");
            if (mysqli_num_rows($historyQuery) > 0) {
                echo "<ul>";
                while ($row = mysqli_fetch_assoc($historyQuery)) {
                    echo "<li>Entrega para: " . htmlspecialchars($row['address']) . " em " . htmlspecialchars($row['date']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Nenhum histórico de entregas encontrado.</p>";
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 NebaService. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
