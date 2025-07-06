<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>NebaService</title>
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

  <main class="container">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      <div class="col-10 col-sm-8 col-lg-6">
        <img src="bootstrap-themes.png" class="d-block mx-lg-auto img-fluid" alt="NebaService Illustration" width="700" height="500" loading="lazy">
      </div>
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold mb-3">Professional Services for Your Business</h1>
        <p class="lead">NebaService provides top-quality solutions tailored to your needs. Our team of experts is ready to help you grow your business efficiently.</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
          <button type="button" class="btn btn-primary btn-lg px-4 me-md-2">Learn More</button>
          <a href="login.php">
            <button type="button" class="btn btn-outline btn-lg px-4">Get Started</button>
          </a>
        </div>
      </div>
    </div>

    <div class="row g-4 py-5">
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h3 class="text-accent">Fast Service</h3>
            <p>Our team delivers quick and efficient solutions to keep your business running smoothly.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h3 class="text-accent">Reliable Support</h3>
            <p>24/7 customer support to address any issues or questions you might have.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-body">
            <h3 class="text-accent">Custom Solutions</h3>
            <p>Tailored services designed specifically for your business requirements.</p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-5">
          <h4>NebaService</h4>
          <p>Promovemos serviços profissionais desde 2023.</p>
        </div>
        <div class="col-md-3">
          <h5>Precisa de ajuda?</h5>
          <ul class="list-unstyled">
            <li><a href="#">Ajuda</a></li>
            <li><a href="#">Envio de encomendas e portes</a></li>
            <li><a href="#">Trocas e devoluções</a></li>
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
            <li><a href="#">Facebook</a></li>
            <li><a href="#">Twitter</a></li>
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
  </script>

</body>
</html>