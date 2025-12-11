<?php

// Using sha1 for password

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once './../connection.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {
        $passwordSha = sha1($password);

        $stmt = $pdo->prepare("SELECT idAdmin FROM Admin WHERE usernameAdmin = ? AND passwordAdmin = ?");
        $stmt->execute([$username, $passwordSha]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            $_SESSION['idAdmin'] = $admin['idAdmin'];
            header('Location: ./');
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Informations incorrectes.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Merci de remplir tous les champs.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>MyCarX - Connexion</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
        body {
            margin: 0;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        input.form-control {
            border-radius: 50px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #000;
        }

        .header-login {
            width: 100%;
            padding: 1rem 2rem;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%; 
            left: 0;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: all 0.4s ease;
            z-index: 999;
        }

        .dropdown-menu-custom.show {
            max-height: 300px; 
            opacity: 1;
            padding: 1rem 0;
        }

        .dropdown-menu-custom a {
            color: #333;
            font-weight: 500;
            transition: background 0.3s;
        }
        .dropdown-menu-custom a:hover {
            background-color: #f8f9fa;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 1%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }



        @media (max-width: 833px) {
            .nav-links {
                display: none !important;
            }
            .navbar-toggler {
                display: block !important;
            }
        }

        @media (min-width: 834px) {
            .navbar-toggler {
                display: none !important;
            }
            .nav-links {
                display: flex !important;
            }
        }
  </style>
</head>
<body>

<div class="header-login">
  <nav class="container d-flex justify-content-between align-items-center flex-wrap position-relative">
    <div class="d-flex align-items-center gap-2">
      <h2 class="m-0">Compte MyCarX</h2>
    </div>

    <!-- PC -->
    <div class="nav-links d-flex gap-3">
      <a href="#" class="text-decoration-none text-secondary">Se connecter</a>
      <a href="./createUser.php" class="text-decoration-none text-primary">Créer mon compte MyCarX</a>
    </div>

    <!-- Phone -->
    <div class="dropdown position-static">
      <button class="btn btn-outline-secondary navbar-toggler" type="button" id="mobileMenuBtn">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="dropdown-menu-custom w-100 text-center">
        <ul class="list-unstyled m-0">
          <li><a class="dropdown-item py-3 d-block text-decoration-none text-secondary" href="#">Se connecter</a></li>
          <li><a class="dropdown-item py-3 d-block text-decoration-none text-primary" href="./createUser.php">Créer un compte MyCarX</a></li>
        </ul>
      </div>
    </div>
  </nav>
</div>


<div class="main-content">
    <div class="login-box">
        <h2 class="text-center mb-4">Connectez-vous</h2>

        <?= $message ?>

        <form method="POST">
            <div class="row mb-2">
                <div class="col-md-12 mb-2 mb-md-0">
                    <input type="email" class="form-control" id="username" name="username" placeholder="E-mail" required>
                </div>
                <div class="col-md-12 password-wrapper">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                        <i data-feather="eye" class="password-toggle" id="togglePassword"></i>
                </div>
            </div>
            <p> </p>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("mobileMenuBtn");
    const menu = document.querySelector(".dropdown-menu-custom");

    btn.addEventListener("click", () => {
        menu.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }
    });
});
</script>

<!-- Icon mdp -->
<script src="https://unpkg.com/feather-icons"></script>
<script>
feather.replace();

const toggle = document.getElementById("togglePassword");
const passwordField = document.getElementById("password");

let isVisible = false;

toggle.addEventListener("click", () => {
  isVisible = !isVisible;
  passwordField.type = isVisible ? "text" : "password";

  // Change uniquement l’icône SVG sans recréer tout le DOM
  toggle.innerHTML = feather.icons[isVisible ? "eye-off" : "eye"].toSvg();
});
</script>

</body>
</html>
