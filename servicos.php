<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>ServicosPage</title>
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

  <main class="container">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5"><div class="col-10 col-sm-8 col-lg-6">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Header</h1>    
    </div>
  </main>

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
  
<script src="script.js"></script>
</body>
</html>
