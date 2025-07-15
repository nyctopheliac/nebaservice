<?php
include 'connect.php';

?>
<!DOCTYPE html>
<html lang="pt-PT ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>NebaService</title>
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
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      <div class="col-10 col-sm-8 col-lg-6">
        <img src="imagens/computadorlight.png" class="mx-lg-auto img-fluid theme-image-light" alt="NebaService Illustration" width="400" height="500" loading="lazy">
        <img src="imagens/computador.png" class="mx-lg-auto img-fluid theme-image-dark" alt="NebaService Illustration" width="400" height="500" loading="lazy">
      </div>
      <div class="col-lg-6">
        
        <h1>Serviços Profissionais para o seu Negócio</h1>
        <p class="lead">A NebaService oferece soluções de alta qualidade, adaptadas às suas necessidades. A nossa equipa de especialistas está pronta para o ajudar a expandir o seu negócio de forma eficiente.</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
          <a href="catalogo.php">
            <button type="button" class="btn btn-primary btn-lg px-4 me-md-2">Saiba Mais</button>
          </a>
          <a href="configurador.php">
            <button type="button" class="btn btn-outline btn-lg px-4">Começar</button>
          </a>
        </div>
      </div>
    </div>

    <!-- Carousel de Destaques -->
    <div class="carousel-container rounded-3 my-5 py-5">
      <div class="row justify-content-center">
        <div class="col-lg-10 position-relative">
          <h2 class="text-center mb-4">Os Nossos Destaques</h2>
          <div id="featureCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <!-- Slide 1 -->
              <div class="carousel-item active">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <div class="card-body text-center">
                        <h3 class="text-accent">Confiança Garantida</h3>
                        <p>Somos uma loja especializada em informática, com atendimento profissional e dedicado ao cliente.</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <div class="card-body text-center">
                        <h3 class="text-accent">Equipa Técnica</h3>
                        <p>Tens dúvidas? A nossa equipa está pronta para te ajudar a escolher ou resolver qualquer problema.</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <div class="card-body text-center">
                        <h3 class="text-accent">Clientes Satisfeitos</h3>
                        <p>Valorizamos cada cliente. Trabalhamos para garantir uma experiência positiva em cada compra.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Slide 2 -->
              <div class="carousel-item">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <div class="card-body text-center">
                        <h3 class="text-accent">Envios Rápidos</h3>
                        <p>Entregas seguras de 24 a 48h para todo o país. Compra com confiança, recebe sem demoras.</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <div class="card-body text-center">
                        <h3 class="text-accent">Grande Variedade</h3>
                        <p>Trabalhamos com as melhores marcas e temos stock atualizado para todos os tipos de clientes.</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card h-100">
                      <div class="card-body text-center">
                        <h3 class="text-accent">Apoio Pós-Venda</h3>
                        <p>Após a compra, continuamos contigo. Garantias, assistência e suporte sempre que precisares.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#featureCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Anterior</span></button>
          <button class="carousel-control-next" type="button" data-bs-target="#featureCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Próximo</span></button>
        </div>
      </div>
    </div>

    <!-- Informação Adicional -->
    <div class="row py-5">
      <div class="col-lg-8 mx-auto text-center">
        <h2>Porquê escolher a nossa loja?</h2>
        <p class="lead">Descobre o que nos distingue. Do atendimento ao pós-venda, trabalhamos para oferecer a melhor experiência em informática — com confiança, rapidez e apoio especializado.</p>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>

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