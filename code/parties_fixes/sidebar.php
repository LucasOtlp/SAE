<?php 
/*require_once 'connection.php';

if (!isset($_SESSION['idAdmin'])) {
    header('Location: loginUser.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = $pdo->prepare("SELECT typeUser FROM User WHERE idAdmin = ?");
$sql->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$typeUser = $user['typeUser']*/
?>

<style>
    /* 1. Configuration de base de la sidebar */
    .sidebar-custom {
        width: 280px;
        transition: all 0.3s ease; /* Animation fluide lors du changement de taille */
    }

    /* 2. Comportement sur Mobile / Tablette (écran < 768px) */
    @media (max-width: 768px) {
        .sidebar-custom {
            width: 80px !important; /* On réduit la largeur */
            text-align: center; /* On centre tout */
            padding: 10px !important; /* Moins de padding */
        }

        /* On cache le texte des liens et le titre */
        .sidebar-text, 
        .sidebar-title {
            display: none !important;
        }

        /* On ajuste la taille des icones pour qu'elles soient bien visibles */
        .nav-link i {
            font-size: 1.5rem; /* Icones plus grosses sur mobile */
            margin-right: 0 !important; /* On enlève la marge à droite de l'icone */
        }
        
        /* On centre les liens */
        .nav-link {
            justify-content: center;
            display: flex;
        }
        
        /* Ajustement du logo/header */
        .logo-area {
            justify-content: center !important;
            margin-right: 0 !important;
        }
    }
</style>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> 


<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark min-vh-100 sidebar-custom">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none logo-area">
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#MyCarX"></use></svg>
      <span class="fs-4">MyCarX</span>
    </a>
    <hr>
    <!-- User = simple user -->
        <?php //if ($typeUser == "user") {?>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">
                    <i class="bi bi-fuel-pump-fill me-2"></i>
                    <span class="sidebar-text">Essence</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" class="nav-link text-white">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    <span class="sidebar-text">Mes documents</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                    <i class="bi bi-building-fill me-2"></i>
                    <span class="sidebar-text">Mes Garages</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                    <i class="bi bi-car-front-fill me-2"></i>
                    <span class="sidebar-text">Mes Véhicules</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                    <i class="bi bi-folder-fill me-2"></i>
                    <span class="sidebar-text">Mes Carnets</span>
                    </a>
                </li>
            </ul>
        <?php //} ?>
    <!-- User = admin -->
        <!-- <?php //else { ?> 
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">
                    <svg class="bi me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                    Statistiques
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                    <svg class="bi me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                    Gestion
                    </a>
                </li>
            </ul>
        <?php //} ?>-->
    <hr>
    <div>
      <a href="compte.php"> <strong> <?php //echo "$user['nom'] $user['prenom']; "?> </strong> </a>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>