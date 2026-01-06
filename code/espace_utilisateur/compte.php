<?php
require_once './../connection.php';

$active = "compte";
include_once './../parties_fixes/sidebar.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ./../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$message = "";

/* ======================
   DÉCONNEXION
   ====================== */
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ./../accueil.php");
    exit;
}

/* ======================
   MISE À JOUR PROFIL
   ====================== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? '');
    $prenom = trim($_POST["prenom"] ?? '');
    $telephone = trim($_POST["telephone"] ?? '');

    if ($nom !== '' && $prenom !== '') {
        try {
            $stmt = $pdo->prepare("
                UPDATE utilisateur 
                SET nom = ?, prenom = ?, telephone = ?
                WHERE id_user = ?
            ");
            $stmt->execute([$nom, $prenom, $telephone, $id_user]);

            $message = "<div class='alert alert-success'>Informations mises à jour avec succès.</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Erreur lors de la mise à jour.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Nom et prénom obligatoires.</div>";
    }
}

/* ======================
   RÉCUPÉRATION UTILISATEUR
   ====================== */
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_user = ?");
$stmt->execute([$id_user]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$currentUser) {
    die("Utilisateur introuvable");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex">

<div class="flex-grow-1 p-4">

    <div class="container" style="max-width: 600px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Mon profil</h1>
            <a href="compte.php?action=logout" class="btn btn-danger btn-sm">
                Déconnexion
            </a>
        </div>

        <?= $message ?>

        <form method="post" class="card p-4 shadow-sm">

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text"
                       name="nom"
                       class="form-control"
                       value="<?= htmlspecialchars($currentUser['nom']) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text"
                       name="prenom"
                       class="form-control"
                       value="<?= htmlspecialchars($currentUser['prenom']) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="tel"
                       name="telephone"
                       class="form-control"
                       value="<?= htmlspecialchars($currentUser['telephone']) ?>">
            </div>

            <button class="btn btn-primary w-100">
                Mettre à jour mes informations
            </button>

        </form>

    </div>

</div>

</body>
</html>