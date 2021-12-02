<header>
  <nav class="navbar navbar-light bg-light fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex" href="home.php">
        <img src="img/crosshair.png" alt="" width="30" height="30" />
        <img src="img/font-logo.png" alt="" height="30" />
      </a>
      <a href="home.php" class="text-dark m-auto d-none d-lg-flex">
        <h2 class="m-0"><i class="bi bi-house-fill mx-auto house-color"></i></h2>
      </a>

      <form class="d-none d-md-flex search-form m-auto">
        <input class="form-control me-2 search-bar" type="search" placeholder="Search..." aria-label="Search" />
      </form>
      <?php
      if (!isset($_SESSION['user'])) {
        echo '<a href="login.php" class="d-none d-lg-flex text-decoration-none text-white btn btn-danger me-2">
                  Login
                </a>
                <a href="sign_up.php" class="d-none d-lg-flex text-decoration-none text-white btn btn-dark me-2">
                  Sign Up
                </a>';
      }
      ?>
      <?php
      if (isset($_SESSION['user'])) {
        // If user is logged in, display their profile pic as the icon
        echo '<div data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                  <span>
                    <img class="rounded-circle border border-3 border-danger" src="img/default_profile_pic.png" width="40" height="40" alt="Profile Picture" />
                    <i class="fas fa-caret-down"></i>
                  </span>
                </div>';
      } else {
        // If user isn't logged in, display normal menu icon
        echo '<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                  <span class="navbar-toggler-icon"></span>
                </button>';
      } ?>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <hr class="m-0" />
        <div class="offcanvas-body mt-0">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown
              </a>
              <ul class="dropdown-menu" aria-labelledby="offcanvasNavbarDropdown">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
              <?php if (isset($_SESSION['user'])) {
                // If user isn't logged in, display logout button
                echo '<li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                      </li>';
              } ?>
            </li>
          </ul>
          <form class="d-flex d-md-none">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          </form>
        </div>
      </div>
    </div>
  </nav>
</header>