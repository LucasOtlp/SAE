<?php
require_once './../connection.php';
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ./../login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

/* ======================
   VALIDATION DES DONNÉES
   ====================== */

$vin = $_POST['vin'] ?? null;
$date = $_POST['date'] ?? null;
$km = $_POST['km'] ?? null;
$cout = $_POST['cout'] ?? null;
$garage = $_POST['garage'] ?: null;
$operation = $_POST['operation'] ?? null;
$piece = $_POST['piece'] ?: null;
$quantite = $_POST['quantite'] ?? 1;

if (!$vin || !$date || !$km || !$cout || !$operation) {
    die("Données invalides");
}

/* ======================
   SÉCURITÉ MÉTIER
   ====================== */

$check = $pdo->prepare("
    SELECT 1 
    FROM voiture 
    WHERE numero_vin = ? AND id_user = ?
");
$check->execute([$vin, $id_user]);

if (!$check->fetch()) {
    die("Accès interdit");
}

/* ======================
   TRANSACTION SQL
   ====================== */

try {
    $pdo->beginTransaction();

    // Intervention
    $stmt = $pdo->prepare("
        INSERT INTO intervention (date, cout, km_voiture, id_garage, numero_vin)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$date, $cout, $km, $garage, $vin]);

    $idIntervention = $pdo->lastInsertId();

    // Détail opération / pièce
    $stmt = $pdo->prepare("
        INSERT INTO intervention_operation_piece
        (id_intervention, id_operation, reference_piece, quantite_utilisee)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$idIntervention, $operation, $piece, $quantite]);

    $pdo->commit();

    header('Location: carnetEntretien.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erreur lors de l'ajout de l'intervention");
}