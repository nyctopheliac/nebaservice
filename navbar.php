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
                      <div class="profile-dropdown" id="profileDropdown">
                          <a href="profile.php">Profile Settings</a>
                          <a href="delivery.php">Check Deliveries</a>
                          <a href="logout.php">Logout</a>
                      </div>
                  </li>
              </ul>
          </div>
      </div>
  </nav>