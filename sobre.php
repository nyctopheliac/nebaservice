<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Sobre Nós - NebaService</title>
</head>
<body>

  <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="5"></circle>
      <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
    </svg>
  </button>

  <?php include 'navbar.php'; ?>

  <main class="container my-5">
    <div class="text-center">
        <h1 class="display-4">Sobre a NebaService</h1>
        <p class="lead">A sua loja de confiança para tecnologia e informática desde 2025.</p>
    </div>

    <div class="row align-items-center g-5 py-5">
        <div class="col-lg-6">
            <h2>A Nossa Missão</h2>
            <p>Na NebaService, a nossa missão é clara: fornecer os melhores produtos e serviços de tecnologia, combinando um catálogo de hardware de ponta com um apoio ao cliente especializado e dedicado. Acreditamos que a tecnologia deve ser acessível e funcional, e trabalhamos todos os dias para garantir que cada cliente encontra a solução perfeita para as suas necessidades, seja para trabalho, estudo ou lazer.</p>
        </div>
        <div class="col-10 col-sm-8 col-lg-6">
            <img src="imagens/computadorlight.png" class="mx-lg-auto img-fluid theme-image-light" alt="NebaService Team" width="400" height="500" loading="lazy">
            <img src="imagens/computador.png" class="mx-lg-auto img-fluid theme-image-dark" alt="NebaService Team" width="400" height="500" loading="lazy">
        </div>
    </div>

    <div class="row text-center py-5">
        <div class="col-lg-4 mb-4">
            <div class="card h-100 p-4">
                <h3>A Nossa Equipa</h3>
                <p>Somos uma equipa de 3 colaboradores apaixonados por tecnologia, prontos para oferecer um atendimento personalizado e resolver qualquer dúvida.</p>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100 p-4">
                <h3>Onde Estamos</h3>
                <p>A nossa empresa localiza-se na Amadora, um ponto central para servir os nossos clientes com rapidez e eficiência.</p>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100 p-4">
                <h3>Contacte-nos</h3>
                <p>Precisa de ajuda? Ligue para +351 XXX-XXX-XXX ou envie um email para o nosso suporte.</p>
            </div>
        </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="script.js"></script>
</body>
</html>