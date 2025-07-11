<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand nav-logo" href="index.php">Neba<span>Service</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="sobre.php">Sobre Nós</a></li>
                <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link" href="servicos.php">Serviços</a></li>
                <li class="nav-item"><a class="nav-link" href="configurador.php">Configurador</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['userID'])): ?>
                    <li class="nav-item">
                        <a href="carrinho.php" class="nav-link">Carrinho</a>
                    </li>
                    <li class="nav-item dropdown">
                        <?php $pfp = isset($_SESSION['pfpURL']) && file_exists($_SESSION['pfpURL']) ? $_SESSION['pfpURL'] : 'imagens/pfp.png'; ?>
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdownToggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo htmlspecialchars($pfp); ?>" alt="Profile" width="30" height="30" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdownToggle">
                            <li><a class="dropdown-item" href="definicoes.php">Definições</a></li>
                            <li><a class="dropdown-item" href="encomendas.php">Minhas Encomendas</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" class="btn btn-outline">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="register.php" class="btn btn-primary">Registar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>