<?php
include 'connect.php';

?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Página de Serviços</title>
</head>

<body>  

  <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Alternar modo escuro">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="5"></circle>
      <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
    </svg>
  </button>

  <?php include 'navbar.php'; ?>

  <main class="container">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5"><div class="col-10 col-sm-8 col-lg-6">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Cabeçalho</h1>    
    </div>
  </main>

  <footer class="footer">
    <div class="container">
        <div class="row">
          <div class="col-md-5">
            <h4>NebaService</h4>
            <p>&copy; 2025 NebaService. Todos os direitos reservados.</p>
          </div>
          <div class="col-md-3">
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
