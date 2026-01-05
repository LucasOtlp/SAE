<?php
$active = "vehicule";
include_once './../parties_fixes/sidebar.php';
require_once './../connection.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex">

<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

//include_once './../parties_fixes/sidebar.php';

$requeteVoituresUser = "SELECT CONCAT(ma.nom, ' ', mo.designation, ' ', mo.generation) AS nom_voiture
                        FROM voiture v
                        JOIN modele mo ON v.id_modele = mo.id_modele
                        JOIN marque ma ON mo.id_marque = ma.id_marque
                        WHERE v.id_user = 2; -- ID User Connecté";
$voituresUser = $pdo->query($requeteVoituresUser)->fetchAll();

?>

<!-- mesVehicules.php -->

<main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="mb-4">Mes Véhicules</h2>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (empty($voituresUser)): ?>
                <div class="col-12">
                    <p class="alert alert-info">Vous n'avez aucun véhicule enregistré.</p>
                </div>
            <?php else: ?>
                <?php foreach ($voituresUser as $voiture): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 text-center">
                            
                            <div class="card-body d-flex align-items-center justify-content-center bg-light" style="height: 220px; border-radius: 8px 8px 0 0;">
                                <i class="bi bi-car-front-fill" style="font-size: 5rem; color: #ced4da;"></i>
                            </div>
                            
                            <div class="card-footer bg-white border-top-0 py-4">
                                <h5 class="card-title text-primary mb-0">
                                    <?php echo htmlspecialchars($voiture['nom_voiture']); ?>
                                </h5>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

</body>
</html>