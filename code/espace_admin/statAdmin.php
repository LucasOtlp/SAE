<?php
$active = 'stats';
require_once './../connection.php'; 

$myGarageId = null;
if (isset($_SESSION['id_user'])) {
    $stmt = $pdo->prepare("SELECT id_garage FROM utilisateur WHERE id_user = ?");
    $stmt->execute([$_SESSION['id_user']]);
    $userRow = $stmt->fetch();
    
    if ($userRow && isset($userRow['id_garage'])) {
        $myGarageId = $userRow['id_garage'];
    } else {
        $myGarageId = 1; 
    }
}

// Stats Garages
$sqlGarage = "SELECT g.id_garage, g.nom, COUNT(i.id_intervention) as nb_interventions, COALESCE(AVG(i.cout), 0) as cout_moyen, COALESCE(SUM(i.cout), 0) as chiffre_affaires
            FROM garage g
            LEFT JOIN intervention i ON g.id_garage = i.id_garage
            GROUP BY g.id_garage
            ORDER BY chiffre_affaires DESC";
$statsGarage = $pdo->query($sqlGarage)->fetchAll();

// Stats Modèles
$sqlModele = "SELECT CONCAT(ma.nom, ' ', m.designation) as nom_modele, COUNT(DISTINCT i.id_intervention) as nb_visites, COALESCE(SUM(iop.quantite_utilisee), 0) as total_pieces_remplacees
            FROM modele m
            JOIN marque ma ON m.id_marque = ma.id_marque
            JOIN voiture v ON m.id_modele = v.id_modele
            JOIN intervention i ON v.numero_vin = i.numero_vin
            LEFT JOIN intervention_operation_piece iop ON i.id_intervention = iop.id_intervention
            GROUP BY m.id_modele
            ORDER BY total_pieces_remplacees ASC";
$statsModele = $pdo->query($sqlModele)->fetchAll();

// Stats Pièces
$sqlPieces = "SELECT p.nom_piece, SUM(iop.quantite_utilisee) as total_use
            FROM pieces p
            JOIN intervention_operation_piece iop ON p.reference_piece = iop.reference_piece
            GROUP BY p.reference_piece
            ORDER BY total_use DESC";
$statsPieces = $pdo->query($sqlPieces)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyCarX Statistiques</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            overflow: hidden;
        }
        .scroll {
            height: 100vh;
            overflow-y: auto;
            width: 100%;
        }

        .subtitle { 
            color: #6c757d; 
            font-size: 0.95rem; 
        }
        .priceText { 
            font-weight: 600; 
            color: #2c3e50; 
            letter-spacing: -0.5px; 
        }

        .customCard {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            background: white;
            transition: transform 0.2s;
        }
        .customCard:hover { 
            transform: translateY(-2px); 
        }
        .customCardHeader {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .customTable th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            font-weight: 600;
            border-bottom: 1px solid #eee;
        }
        .customTable td {
            vertical-align: middle;
            font-size: 0.9rem;
            padding: 12px 8px;
        }
        
        .row-highlight { 
            background-color: #e3f2fd;
        }
        .row-highlight td:first-child {
            box-shadow: inset 4px 0 0 #2196f3; 
            font-weight: 600;
        }

        .badge-me {
            background: #3498db;
            color: white;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 6px;
            text-transform: uppercase;
            vertical-align: middle;
        }
        .tag { 
            padding: 4px 10px; 
            border-radius: 6px; 
            font-size: 0.8rem; 
            font-weight: 600; 
        }
        .tagG { 
            background: #e8f8f5; 
            color: #2ecc71; 
        }
        .tagR { 
            background: #fdedec; 
            color: #e74c3c; 
        }
        .tagB { 
            background: #eaf2f8; 
            color: #3498db; 
        }

        .progressBar { 
            height: 6px; 
            min-width: 60px; 
            background-color: #eee; 
            border-radius: 10px; 
        }
        .progressBarV2 { 
            border-radius: 10px; 
        }
    </style>
</head>

<body class="d-flex">

    <?php include './../parties_fixes/sidebar.php'; ?>

    <div class="flex-grow-1 scroll">
        <div class="container-fluid p-4" style="max-width: 1600px;">
            
            <header class="mb-5 text-md-start">
                <h1 class="text-center">Statistiques</h1>
                <p class="subtitle text-center">Vue d'ensemble des performances garages et fiabilité véhicules</p>
            </header>

            <div class="row g-4">
                
                <div class="col-12">
                    <div class="customCard p-4">
                        <div class="customCardHeader d-flex justify-content-between align-items-center">
                            <h2>Performance des Garages</h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover customTable mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Garage</th>
                                        <th class="text-center">Interventions</th>
                                        <th class="text-end">Coût Moyen</th>
                                        <th class="text-end">Chiffre d'Affaires</th>
                                        <th style="width: 20%;">Part du marché</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $maxCA = 0;

                                    foreach ($statsGarage as $garage){
                                        $maxCA += $garage['chiffre_affaires'];
                                    }


                                    foreach ($statsGarage as $garage){

                                        if ($maxCA > 0) {
                                            $percent = ($garage['chiffre_affaires'] / $maxCA) * 100;
                                        }
                                        else {
                                            $percent = 0;
                                        }

                                        $isMyGarage = $garage['id_garage'] == $myGarageId;
                                        // Ajuste la class pour le style en fonction de si c'est son garage ou pas
                                            if ($isMyGarage) {
                                                $rowClass = "row-highlight";
                                            }
                                            else {
                                                $rowClass = "";
                                            }
                                    ?>
                                    <tr class="<?= $rowClass ?>">
                                        <td>
                                            <?= htmlspecialchars($garage['nom']) ?>
                                            <?php if($isMyGarage){ ?>
                                                <span class="badge-me">Votre Garage</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="tag tagB"><?= $garage['nb_interventions'] ?></span>
                                        </td>
                                        <td class="text-end text-muted"><?= number_format($garage['cout_moyen'], 2, ',', ' ') ?> €</td>
                                        <td class="text-end priceText"><?= number_format($garage['chiffre_affaires'], 2, ',', ' ') ?> €</td>
                                        <td>
                                            <div class="d-flex align-items-center" style="height: 100%;">
                                                <div class="progress progressBar flex-grow-1">
                                                    <!-- Barre de progression en fonction du pourcentage du CA du garage par rapport au CA total -->
                                                        <div class="progress-bar progressBarV2" role="progressbar" 
                                                            style="width: <?= $percent ?>%; background-color: <?= $isMyGarage ? '#3498db' : '#2ecc71' ?>;">
                                                        </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="customCard p-4 h-100">
                        <div class="customCardHeader">
                            <h2>Fiabilité des Modèles</h2>
                            <p class="subtitle mb-0">Basé sur le ratio pièces / visite</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless customTable align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Modèle</th>
                                        <th class="text-center">Ratio</th>
                                        <th class="text-end">Indice Santé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($statsModele as $mod){
                                        if ($mod['nb_visites'] > 0) {
                                            // ratio du nombre de piece changée par visite
                                            $ratio = $mod['total_pieces_remplacees'] / $mod['nb_visites'];
                                        }
                                        else {
                                            $ratio = 0;
                                        }
                                        // Fonction mac pour éviter les négatifs et *10 est une pénalité par piece changée
                                        $sante = max(0, 100 - ($ratio * 10));
                                        if ($sante > 80) {
                                            $colorClass = "tagG";
                                        }
                                        elseif ($sante > 50){
                                            $colorClass = "tagB";
                                        }
                                        else {
                                            $colorClass = "tagR";
                                        }
                                    ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($mod['nom_modele']) ?></td>
                                        <td class="text-center text-muted"><?= number_format($ratio, 1) ?></td>
                                        <td class="text-end">
                                            <span class="tag <?= $colorClass ?>"><?= number_format($sante, 0) ?>%</span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="customCard p-4 h-100">
                        <div class="customCardHeader">
                            <h2>Top Composants</h2>
                            <p class="subtitle mb-0">Pièces les plus remplacées</p>
                        </div>
                        
                        <?php if(count($statsPieces) > 0) { 
                            $maxPiece = $statsPieces[0]['total_use'];
                        ?>
                        <div class="table-responsive mt-2">
                            <table class="table table-borderless customTable mb-0">
                                <?php foreach ($statsPieces as $piece){
                                    $width = ($piece['total_use'] / $maxPiece) * 100;
                                ?>
                                <tr>
                                    <td style="width: 40%;" class="text-muted"><?= htmlspecialchars($piece['nom_piece']) ?></td>
                                    <td style="width: 60%;">
                                        <div class="d-flex align-items-center">
                                            <div class="progress progressBar flex-grow-1 me-3">
                                                <div class="progress-bar progressBarV2" role="progressbar" 
                                                     style="width: <?= $width ?>%; background-color: #f1c40f;">
                                                </div>
                                            </div>
                                            <span class="fw-bold text-dark"><?= $piece['total_use'] ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <?php } 
                        else { ?>
                            <p class="text-center text-muted py-4">Aucune donnée disponible.</p>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>