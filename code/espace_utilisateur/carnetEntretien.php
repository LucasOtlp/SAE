<?php
$active = "carnet";
include_once './../parties_fixes/sidebar.php';

// 1. Connexion et Session
require_once './../connection.php'; 

// Vérification de session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Historique des interventions
$stmt = $pdo->prepare("
    SELECT 
        i.id_intervention,
        i.date,
        i.cout,
        i.km_voiture,
        g.nom AS garage,
        o.nature AS operation,
        p.nom_piece,
        iop.quantite_utilisee
    FROM intervention i
    JOIN voiture v ON v.numero_vin = i.numero_vin
    LEFT JOIN garage g ON g.id_garage = i.id_garage
    LEFT JOIN intervention_operation_piece iop ON iop.id_intervention = i.id_intervention
    LEFT JOIN operations o ON o.id_operation = iop.id_operation
    LEFT JOIN pieces p ON p.reference_piece = iop.reference_piece
    WHERE v.id_user = ?
    ORDER BY i.date DESC
");
$stmt->execute([$id_user]);
$interventions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$voitures = $pdo->prepare("SELECT numero_vin FROM voiture WHERE id_user = ?");
$voitures->execute([$id_user]);

$garages = $pdo->query("SELECT id_garage, nom FROM garage");
$operations = $pdo->query("SELECT id_operation, nature FROM operations");
$pieces = $pdo->query("SELECT reference_piece, nom_piece FROM pieces");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carnet d'entretien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">
<div class="flex-grow-1 p-4">
<div class="container my-5">

    <h1 class="mb-4">Carnet d’entretien</h1>

    <h2 class="mb-3">Historique</h2>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Garage</th>
                <th>Opération</th>
                <th>Pièce</th>
                <th>Qté</th>
                <th>Kilométrage</th>
                <th>Coût (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($interventions) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center">Aucune intervention enregistrée</td>
                </tr>
            <?php else: ?>
                <?php foreach ($interventions as $i): ?>
                    <tr>
                        <td><?= htmlspecialchars($i['date']) ?></td>
                        <td><?= htmlspecialchars($i['garage'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($i['operation']) ?></td>
                        <td><?= htmlspecialchars($i['nom_piece'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($i['quantite_utilisee'] ?? 0) ?></td>
                        <td><?= htmlspecialchars($i['km_voiture']) ?> km</td>
                        <td><?= number_format($i['cout'], 2, ',', ' ') ?> €</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h2 class="mt-5 mb-3">Ajouter une intervention</h2>

    <form method="post" action="ajoutIntervention.php" class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Véhicule (VIN)</label>
            <select name="vin" class="form-select" required>
                <?php foreach ($voitures as $v): ?>
                    <option value="<?= $v['numero_vin'] ?>">
                        <?= $v['numero_vin'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Kilométrage</label>
            <input type="number" name="km" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Garage</label>
            <select name="garage" class="form-select">
                <option value="">—</option>
                <?php foreach ($garages as $g): ?>
                    <option value="<?= $g['id_garage'] ?>"><?= $g['nom'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Opération</label>
            <select name="operation" class="form-select" required>
                <?php foreach ($operations as $o): ?>
                    <option value="<?= $o['id_operation'] ?>">
                        <?= $o['nature'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Pièce</label>
            <select name="piece" class="form-select">
                <option value="">—</option>
                <?php foreach ($pieces as $p): ?>
                    <option value="<?= $p['reference_piece'] ?>">
                        <?= $p['nom_piece'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Quantité</label>
            <input type="number" name="quantite" class="form-control" value="1" min="1">
        </div>

        <div class="col-md-2">
            <label class="form-label">Coût (€)</label>
            <input type="number" step="0.01" name="cout" class="form-control" required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Ajouter l’intervention</button>
        </div>

    </form>

</div>
</div>
</body>
</html>