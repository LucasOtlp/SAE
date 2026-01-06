<?php
// connection.php doit être inclus en premier
require_once './../connection.php'; 

// --- PARTIE 1 : LOGIQUE DE CALCUL (AJAX) ---
if (isset($_POST['action']) && $_POST['action'] === 'calcul_score' && isset($_POST['modele'])) {
    
    $modeleCible = $_POST['modele'];
    
    // 1. Récupération des voitures du modèle
    $stmt = $pdo->prepare("SELECT v.numero_vin, v.mise_en_circulation, v.kilometrage AS km_actuel
        FROM voiture v
        JOIN modele m ON v.id_modele = m.id_modele
        WHERE m.designation = :modele");
    $stmt->execute(['modele' => $modeleCible]);
    $usurePhysique = $stmt->fetchAll();

    if (empty($usurePhysique)) {
        echo "N/A"; 
        exit;
    }

    // 2. Récupération historique
    $stmt = $pdo->prepare("SELECT i.numero_vin, i.cout, i.km_voiture AS km_au_moment_intervention
    FROM intervention i
    JOIN voiture v ON i.numero_vin = v.numero_vin
    JOIN modele m ON v.id_modele = m.id_modele
    WHERE m.designation = :modele
    ORDER BY i.numero_vin, i.date ASC");
    $stmt->execute(['modele' => $modeleCible]);
    $historique = $stmt->fetchAll();

    $interventionsParVin = [];
    foreach ($historique as $inter) {
        $interventionsParVin[$inter['numero_vin']][] = $inter;
    }

    $scoresFinaux = [];
    $dateActuelle = new DateTime();

    foreach ($usurePhysique as $voiture) {
        $vin = $voiture['numero_vin'];
        $kmActuel = (int)$voiture['km_actuel'];
        
        $dateMec = new DateTime($voiture['mise_en_circulation']);
        $ageAnnees = $dateActuelle->diff($dateMec)->days / 365.25;
        $scoreU = 100 * ((0.4 * max(0, 1 - ($ageAnnees / 15))) + (0.6 * max(0, 1 - ($kmActuel / 250000))));

        $listeInter = $interventionsParVin[$vin] ?? [];
        $coutTotal = 0;
        $sommeEcarts = 0;
        $nbEcarts = 0;
        $dernierKm = 0;

        foreach ($listeInter as $idx => $act) {
            $coutTotal += $act['cout'];
            $kmInter = $act['km_au_moment_intervention'];
            if ($idx > 0 && ($kmInter - $dernierKm) > 0) {
                $sommeEcarts += ($kmInter - $dernierKm);
                $nbEcarts++;
            }
            $dernierKm = $kmInter;
        }

        $scoreF = ($nbEcarts > 0) ? min(100, ($sommeEcarts / $nbEcarts / 15000) * 100) : 100;
        $scoreC = ($kmActuel > 0) ? max(0, 100 * (1 - (($coutTotal / $kmActuel) / 0.10))) : 100;

        $scoresFinaux[] = ($scoreU * 0.4) + ($scoreF * 0.2) + ($scoreC * 0.4);
    }

    if (count($scoresFinaux) > 0) {
        $moyenne = array_sum($scoresFinaux) / count($scoresFinaux);
        echo number_format($moyenne, 1); 
    } else {
        echo "N/A";
    }
    
    exit; 
}

// --- PARTIE 2 : AFFICHAGE DE LA PAGE (HTML) ---
$active = "vehicule";
include_once './../parties_fixes/sidebar.php';

$id = $_SESSION['id_user'];
// Mise à jour de la requête pour inclure les détails demandés
$requeteVoituresUser = "SELECT 
                            v.numero_vin,
                            v.immatriculation,
                            v.annee,
                            v.couleur,
                            v.puissance_vin,
                            v.puissance_din,
                            v.kilometrage,
                            v.energie,
                            CONCAT(ma.nom, ' ', mo.designation, ' ', mo.generation) AS nom_voiture,
                            mo.designation AS nom_modele
                        FROM voiture v
                        JOIN modele mo ON v.id_modele = mo.id_modele
                        JOIN marque ma ON mo.id_marque = ma.id_marque
                        WHERE v.id_user = :id_user";
$requetePreparee = $pdo->prepare($requeteVoituresUser);
$requetePreparee->bindValue(":id_user", $id, PDO::PARAM_INT );
$requetePreparee->execute();
$voituresUser = $requetePreparee->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Véhicules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .spinner-border-sm { --bs-spinner-width: 1rem; --bs-spinner-height: 1rem; }
        .score-display { font-size: 2rem; font-weight: bold; color: #0d6efd; transition: all 0.3s; }
        .animate-score { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-backdrop.show { opacity: 0.7; backdrop-filter: blur(4px); }
    </style>
</head>

<body class="d-flex">

<main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
    <div class="container">
        
        <h2 class="mb-4">Mes Véhicules</h2>
        
        <div class="text-center mb-5">
            <a href="ajouterVehicule.php" class="btn btn-success shadow px-4 py-2 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Ajouter un nouveau véhicule
            </a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (empty($voituresUser)): ?>
                <div class="col-12">
                    <p class="alert alert-info">Vous n'avez aucun véhicule enregistré.</p>
                </div>
            <?php else: ?>
                <?php foreach ($voituresUser as $voiture): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 text-center">
                            
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 180px; border-radius: 8px 8px 0 0;">
                                <i class="bi bi-car-front-fill" style="font-size: 5rem; color: #ced4da;"></i>
                            </div>
                            
                            <div class="card-body d-flex flex-column justify-content-center align-items-center gap-2">
                                
                                <div id="result-<?php echo $voiture['numero_vin']; ?>" class="w-100"></div>

                                <button class="btn btn-outline-primary w-100 fw-bold py-2 shadow-sm btn-calcul" 
                                        data-vin="<?php echo $voiture['numero_vin']; ?>"
                                        data-modele="<?php echo htmlspecialchars($voiture['nom_modele']); ?>">
                                    <i class="bi bi-speedometer2 me-2"></i>Calculer le Score
                                </button>

                                <button class="btn btn-light w-100 fw-bold text-secondary border" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modal-<?php echo $voiture['numero_vin']; ?>">
                                    <i class="bi bi-info-circle me-2"></i>Détails
                                </button>
                            </div>
                            
                            <div class="card-footer bg-white border-top-0 pb-4 pt-2">
                                <h5 class="card-title text-dark fw-bold mb-0">
                                    <?php echo htmlspecialchars($voiture['nom_voiture']); ?>
                                </h5>
                                <small class="text-muted">Immat: <?php echo htmlspecialchars($voiture['immatriculation']); ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-<?php echo $voiture['numero_vin']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title fw-bold"><?php echo htmlspecialchars($voiture['nom_voiture']); ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="text-muted small d-block">Immatriculation</label>
                                            <span class="fw-bold text-uppercase"><?php echo htmlspecialchars($voiture['immatriculation']); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-muted small d-block">Année</label>
                                            <span class="fw-bold"><?php echo htmlspecialchars($voiture['annee']); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-muted small d-block">Énergie</label>
                                            <span class="fw-bold text-capitalize"><?php echo htmlspecialchars($voiture['energie']); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-muted small d-block">Puissance (VIN/DIN)</label>
                                            <span class="fw-bold"><?php echo htmlspecialchars($voiture['puissance_vin'] . ' / ' . $voiture['puissance_din']); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-muted small d-block">Kilométrage</label>
                                            <span class="fw-bold text-primary"><?php echo number_format($voiture['kilometrage'], 0, ',', ' '); ?> km</span>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-muted small d-block">Couleur</label>
                                            <span class="fw-bold text-capitalize"><?php echo htmlspecialchars($voiture['couleur']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-calcul');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const vin = this.getAttribute('data-vin');
            const modele = this.getAttribute('data-modele');
            const resultDiv = document.getElementById('result-' + vin);
            const btn = this;

            btn.disabled = true;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

            const formData = new FormData();
            formData.append('action', 'calcul_score');
            formData.append('modele', modele);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                btn.style.display = 'none';
                resultDiv.innerHTML = `
                    <div class="score-display animate-score mb-3">
                        ${data}<span style="font-size:1rem; color:#6c757d;">/100</span>
                    </div>`;
            })
            .catch(error => {
                console.error('Erreur:', error);
                btn.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Erreur';
                btn.classList.replace('btn-outline-primary', 'btn-outline-danger');
                btn.disabled = false;
            });
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>