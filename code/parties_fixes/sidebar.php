<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL); 
require_once './../connection.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: ./../espace_utilisateur/loginUser.php');
    exit;
}

if(!isset($active)){
    $active = "carnet";
}

$id = $_SESSION['id_user'];

$sql = $pdo->prepare("SELECT * FROM utilisateur WHERE id_user = ?");
$sql->execute([$id]);
$user = $sql->fetch(PDO::FETCH_ASSOC);

$typeUser = $user['type_user'];
?>

<style>
    .sidebar-custom {
        width: 280px;
        transition: all 0.3s ease; 
        position: sticky; 
        top: 0;
        height: 100dvh;
        overflow-y: auto;
    }

    .sidebar-custom::-webkit-scrollbar {
        width: 6px;
    }


    @media (max-width: 768px) {
        .sidebar-custom {
            width: 100px; 
            text-align: center; 
            padding: 10px; 
        }

        .sidebar-text, 
        .sidebar-title {
            display: none;
        }


        .nav-link i {
            font-size: 1.5rem; 
            margin-right: 0;
        }
        

        .nav-link {
            justify-content: center;
            display: flex;
        }
        
        .logo-area {
            justify-content: center;
            margin-right: 0;
        }
    }
</style>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--icones de bootstrap-->

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar-custom">
    <a href="./../accueil.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none logo-area">
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#MyCarX"></use></svg>
      <span class="fs-4">MyCarX</span>
    </a>
    <hr>
    <!-- User = simple user -->
            <ul class="nav nav-pills flex-column mb-auto">
                <li>
                    <a href="./../espace_utilisateur/mesVehicules.php" class="nav-link <?php if($active == "vehicule"){ echo "active"; }else{ echo "text-white";} ?>">
                    <i class="bi bi-car-front-fill me-2"></i>
                    <span class="sidebar-text">Mes Véhicules</span>
                    </a>
                </li>
                <li>
                    <a href="./../espace_utilisateur/carnetEntretien.php" class="nav-link <?php if($active == "carnet"){ echo "active"; }else{ echo "text-white";} ?>">
                    <i class="bi bi-folder-fill me-2"></i>
                    <span class="sidebar-text">Mes Carnets</span>
                    </a>
                </li>
                <li>
                    <a href="./../espace_utilisateur/documents.php" class="nav-link <?php if($active == "documents"){ echo "active"; }else{ echo "text-white";} ?>">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    <span class="sidebar-text">Mes documents</span>
                    </a>
                </li>
                <li>
                    <a href="./../espace_utilisateur/mesGarages.php" class="nav-link <?php if($active == "garage"){ echo "active"; }else{ echo "text-white";} ?>">
                    <i class="bi bi-building-fill me-2"></i>
                    <span class="sidebar-text">Mes Garages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./../espace_utilisateur/prixEssence.php" class="nav-link <?php if($active == "essence"){ echo "active"; }else{ echo "text-white";} ?>" aria-current="page">
                    <i class="bi bi-fuel-pump-fill me-2"></i>
                    <span class="sidebar-text">Prix Essence</span>
                    </a>
                </li>                
    <!-- User = garagiste -->
        <?php if ($typeUser == "garagiste") { ?>
                <li>
                    <a href="./../espace_admin/statAdmin.php" class="nav-link <?php if($active == "stats"){ echo "active"; }else{ echo "text-white";} ?>">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span class="sidebar-text">Statistiques</span>
                    </a>
                </li>
        <?php } ?>
    <hr>
    <div>
      <strong> <?php echo $user['nom'] . ' ' . $user['prenom']; ?> </strong>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>