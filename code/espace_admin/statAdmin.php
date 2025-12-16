<?php

include_once './../parties_fixes/sidebar.php';

$myGarageId = null;

if (isset($_SESSION['id_user'])) {
    // Utilisation d'une requête préparée pour la sécurité
    $stmt = $pdo->prepare("SELECT id_garage FROM utilisateur WHERE id_user = ?");
    $stmt->execute([$_SESSION['id_user']]);
    $userRow = $stmt->fetch();
    
    // Si l'utilisateur a un garage, on stocke l'ID, sinon null
    if ($userRow && isset($userRow['id_garage'])) {
        $myGarageId = $userRow['id_garage'];
    } else {
        // Fallback pour l'exemple si la colonne n'existe pas encore dans ta BDD : 
        // On dit arbitrairement que l'user 1 est au garage 1
        $myGarageId = 1; 
    }
}

// 2. STATS PAR GARAGE (Ajout de g.id_garage dans le SELECT)
$sqlGarage = "
    SELECT 
        g.id_garage, 
        g.nom,
        COUNT(i.id_intervention) as nb_interventions,
        COALESCE(AVG(i.cout), 0) as cout_moyen,
        COALESCE(SUM(i.cout), 0) as chiffre_affaires
    FROM garage g
    LEFT JOIN intervention i ON g.id_garage = i.id_garage
    GROUP BY g.id_garage
    ORDER BY chiffre_affaires DESC
";
$statsGarage = $pdo->query($sqlGarage)->fetchAll();

// 3. STATS MODELES
$sqlModele = "
    SELECT 
        CONCAT(ma.nom, ' ', m.designation) as nom_modele, 
        COUNT(DISTINCT i.id_intervention) as nb_visites, 
        COALESCE(SUM(iop.quantite_utilisee), 0) as total_pieces_remplacees
    FROM modele m
    JOIN marque ma ON m.id_marque = ma.id_marque
    JOIN voiture v ON m.id_modele = v.id_modele
    JOIN intervention i ON v.numero_vin = i.numero_vin
    LEFT JOIN intervention_operation_piece iop ON i.id_intervention = iop.id_intervention
    GROUP BY m.id_modele
    ORDER BY total_pieces_remplacees ASC
";
$statsModele = $pdo->query($sqlModele)->fetchAll();

// 4. TOP COMPOSANTS
$sqlPieces = "
    SELECT p.nom_piece, SUM(iop.quantite_utilisee) as total_use
    FROM pieces p
    JOIN intervention_operation_piece iop ON p.reference_piece = iop.reference_piece
    GROUP BY p.reference_piece
    ORDER BY total_use DESC
    LIMIT 5
";
$statsPieces = $pdo->query($sqlPieces)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyCarX Statistiques</title>
    <style>
            :root {
                --bg-color: #f8f9fa;
                --card-bg: #ffffff;
                --text-primary: #2c3e50;
                --text-secondary: #6c757d;
                --accent: #3498db;
                --success: #2ecc71;
                --warning: #f1c40f;
                --danger: #e74c3c;
                --highlight-bg: #e3f2fd; /* Couleur de fond pour le garage utilisateur */
                --highlight-border: #2196f3; /* Bordure pour le garage utilisateur */
                --border-radius: 12px;
                --shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            }

            body { font-family: sans-serif; background: var(--bg-color); margin: 0; }

            .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
            header { margin-bottom: 40px; text-align: center; }
            h1 { font-weight: 300; margin: 0; font-size: 2.5rem; color: var(--text-primary); }
            h2 { font-size: 1.25rem; margin-bottom: 20px; color: var(--text-primary); border-bottom: 2px solid var(--bg-color); padding-bottom: 10px; }
            .subtitle { color: var(--text-secondary); margin-top: 10px; }

            .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; }
            .card { background: var(--card-bg); border-radius: var(--border-radius); box-shadow: var(--shadow); padding: 25px; }
            .full-width { grid-column: 1 / -1; }

            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { text-align: left; padding: 12px 15px; border-bottom: 1px solid #eee; }
            th { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-secondary); }
            
            /* STYLE DISTINCTIF POUR LE GARAGE UTILISATEUR */
            tr.row-highlight {
                background-color: var(--highlight-bg);
                border-left: 4px solid var(--highlight-border);
            }
            tr.row-highlight td {
                font-weight: 600;
                color: var(--text-primary);
            }
            .badge-me {
                background-color: var(--accent);
                color: white;
                font-size: 0.7rem;
                padding: 2px 6px;
                border-radius: 4px;
                margin-left: 8px;
                vertical-align: middle;
                text-transform: uppercase;
            }

            .price { font-weight: 600; color: var(--text-primary); }
            .progress-container { width: 100%; background-color: #eee; border-radius: 10px; height: 8px; margin-top: 6px; overflow: hidden; }
            .progress-bar { height: 100%; border-radius: 10px; background-color: var(--accent); }
            
            .tag { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; }
            .tag-green { background: #e8f8f5; color: var(--success); }
            .tag-red { background: #fdedec; color: var(--danger); }
            .tag-blue { background: #eaf2f8; color: var(--accent); }
    </style>
</head>

<body class="d-flex">
<div class="flex-grow-1">

<div class="container">
    <header>
        <h1>Analytics Composants & Interventions</h1>
        <p class="subtitle">Vue d'ensemble des performances garages et fiabilité véhicules</p>
    </header>

    <div class="dashboard-grid">
        
        <div class="card full-width">
            <h2>Performance des Garages</h2>
            <table>
                <thead>
                    <tr>
                        <th>Garage</th>
                        <th style="text-align:center;">Interventions</th>
                        <th style="text-align:right;">Coût Moyen</th>
                        <th style="text-align:right;">Chiffre d'Affaires</th>
                        <th style="width: 25%;">Part du CA global</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $maxCA = 0;
                    foreach($statsGarage as $g){
                         $maxCA += $g['chiffre_affaires'];
                    }

                    foreach ($statsGarage as $garage){
                        $percent = ($maxCA > 0) ? ($garage['chiffre_affaires'] / $maxCA) * 100 : 0;
                        
                        // LOGIQUE DE DISTINCTION
                        $isMyGarage = ($garage['id_garage'] == $myGarageId);
                        $rowClass = $isMyGarage ? 'row-highlight' : '';
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td>
                            <?= htmlspecialchars($garage['nom']) ?>
                            <?php if($isMyGarage){ ?>
                                <span class="badge-me">Votre Garage</span>
                            <?php }; ?>
                        </td>
                        <td style="text-align:center;">
                            <span class="tag tag-blue"><?= $garage['nb_interventions'] ?></span>
                        </td>
                        <td style="text-align:right;"><?= number_format($garage['cout_moyen'], 2, ',', ' ') ?> €</td>
                        <td style="text-align:right;" class="price"><?= number_format($garage['chiffre_affaires'], 2, ',', ' ') ?> €</td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: <?= $percent ?>%; background-color: <?= $isMyGarage ? 'var(--accent)' : 'var(--success)' ?>;"></div>
                            </div>
                        </td>
                    </tr>
                    <?php }; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Fiabilité des Modèles (Global)</h2>
            <p class="subtitle">Moins de pièces remplacées = Meilleure fiabilité</p>
            <table>
                <thead>
                    <tr>
                        <th>Modèle</th>
                        <th style="text-align:center;">Ratio Pièces/Visite</th>
                        <th style="text-align:right;">Indice Santé</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statsModele as $mod){
                        $ratio = ($mod['nb_visites'] > 0) ? $mod['total_pieces_remplacees'] / $mod['nb_visites'] : 0;
                        $sante = max(0, 100 - ($ratio * 10));
                        $colorClass = ($sante > 80) ? 'tag-green' : (($sante > 50) ? 'tag-blue' : 'tag-red');
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($mod['nom_modele']) ?></td>
                        <td style="text-align:center;"><?= number_format($ratio, 1) ?> <small class="subtitle">pcs/int.</small></td>
                        <td style="text-align:right;">
                            <span class="tag <?= $colorClass ?>"><?= number_format($sante, 0) ?>%</span>
                        </td>
                    </tr>
                    <?php }; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Top Composants Remplacés (Global)</h2>
            <p class="subtitle">Pièces les plus demandées en stock</p>
            
            <?php if(count($statsPieces) > 0){
                $maxPiece = $statsPieces[0]['total_use'];
            ?>
                <table style="margin-top:20px;">
                    <?php foreach ($statsPieces as $piece){
                        $width = ($piece['total_use'] / $maxPiece) * 100;
                    ?>
                    <tr>
                        <td style="width: 40%; border:none; padding: 8px 0;"><?= htmlspecialchars($piece['nom_piece']) ?></td>
                        <td style="width: 60%; border:none; padding: 8px 0;">
                            <div style="display:flex; align-items:center;">
                                <div class="progress-container" style="margin-top:0; margin-right:10px;">
                                    <div class="progress-bar" style="width: <?= $width ?>%; background-color: var(--warning);"></div>
                                </div>
                                <span style="font-weight:bold;"><?= $piece['total_use'] ?></span>
                            </div>
                        </td>
                    </tr>
                    <?php }; ?>
                </table>
            <?php } else{ ?>
                <p>Aucune donnée disponible.</p>

            <?php }; ?>
        </div>

    </div>
</div>
</div>
</body>
</html>