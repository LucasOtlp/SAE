<?php
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

$message = "";
$messageType = "";

// --- NOUVEAU : Récupération des modèles pour la liste déroulante ---
// On joint 'modele' et 'marque' pour afficher un nom complet (ex: Peugeot 208 III)
$sqlModeles = "SELECT mo.id_modele, ma.nom AS nom_marque, mo.designation, mo.generation 
               FROM modele mo
               JOIN marque ma ON mo.id_marque = ma.id_marque
               ORDER BY ma.nom ASC, mo.designation ASC";
$stmtModeles = $pdo->query($sqlModeles);
$listeModeles = $stmtModeles->fetchAll(PDO::FETCH_ASSOC);


// 2. Traitement du formulaire (INSERT)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupération et nettoyage des données
    $vin = htmlspecialchars($_POST['numero_vin']);
    $immatriculation = htmlspecialchars($_POST['immatriculation']);
    $id_modele = (int)$_POST['id_modele']; 
    $annee = (int)$_POST['annee'];
    $mise_en_circulation = $_POST['mise_en_circulation']; 
    $kilometrage = (int)$_POST['kilometrage'];
    $energie = htmlspecialchars($_POST['energie']);
    $couleur = htmlspecialchars($_POST['couleur']);
    $finition = htmlspecialchars($_POST['finition']);
    $puissance_din = htmlspecialchars($_POST['puissance_din']);
    $puissance_vin = htmlspecialchars($_POST['puissance_vin']);
    
    $id_user = $_SESSION['id_user'];

    // Requête SQL d'insertion
    $sqlInsert = "INSERT INTO voiture 
    (numero_vin, annee, couleur, kilometrage, finition, energie, puissance_vin, mise_en_circulation, immatriculation, puissance_din, id_modele, id_user) 
    VALUES 
    (:vin, :annee, :couleur, :km, :finition, :energie, :p_vin, :date_mec, :immat, :p_din, :id_modele, :id_user)";

    try {
        $stmt = $pdo->prepare($sqlInsert);
        $stmt->execute([
            ':vin' => $vin,
            ':annee' => $annee,
            ':couleur' => $couleur,
            ':km' => $kilometrage,
            ':finition' => $finition,
            ':energie' => $energie,
            ':p_vin' => $puissance_vin,
            ':date_mec' => $mise_en_circulation,
            ':immat' => $immatriculation,
            ':p_din' => $puissance_din,
            ':id_modele' => $id_modele,
            ':id_user' => $id_user
        ]);

        $message = "Véhicule ajouté avec succès !";
        $messageType = "success";

    } catch (PDOException $e) {
        $message = "Erreur lors de l'ajout : " . $e->getMessage();
        $messageType = "danger";
    }
}

// Variable pour la sidebar active
$active = "vehicule"; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un véhicule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex">

    <?php include_once './../parties_fixes/sidebar.php'; ?>

    <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="container">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Ajouter un nouveau véhicule</h2>
                <a href="mesVehicules.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary"><i class="bi bi-car-front me-2"></i>Informations du véhicule</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="" method="POST">
                        
                        <div class="row g-3">
                            
                            <div class="col-12"><h6 class="text-muted border-bottom pb-2 mt-2">Identification</h6></div>

                            <div class="col-md-6">
                                <label for="numero_vin" class="form-label fw-bold">Numéro VIN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="numero_vin" name="numero_vin" placeholder="Ex: VF3..." required maxlength="50">
                            </div>

                            <div class="col-md-6">
                                <label for="immatriculation" class="form-label fw-bold">Immatriculation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="immatriculation" name="immatriculation" placeholder="Ex: AA-123-BB" required maxlength="50">
                            </div>

                            <div class="col-md-6">
                                <label for="id_modele" class="form-label fw-bold">Modèle <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_modele" name="id_modele" required>
                                    <option value="" selected disabled>Choisir un modèle...</option>
                                    <?php foreach ($listeModeles as $mod): ?>
                                        <option value="<?php echo $mod['id_modele']; ?>">
                                            <?php 
                                                // Affiche: Peugeot 208 III
                                                echo htmlspecialchars($mod['nom_marque'] . ' ' . $mod['designation'] . ' ' . $mod['generation']); 
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12"><h6 class="text-muted border-bottom pb-2 mt-4">Caractéristiques Techniques</h6></div>

                            <div class="col-md-4">
                                <label for="mise_en_circulation" class="form-label">Date de mise en circulation</label>
                                <input type="date" class="form-control" id="mise_en_circulation" name="mise_en_circulation" required>
                            </div>

                            <div class="col-md-4">
                                <label for="annee" class="form-label">Année Modèle</label>
                                <input type="number" class="form-control" id="annee" name="annee" min="1900" max="2099" placeholder="2020">
                            </div>

                            <div class="col-md-4">
                                <label for="kilometrage" class="form-label">Kilométrage Actuel</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="kilometrage" name="kilometrage" placeholder="0">
                                    <span class="input-group-text">km</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="energie" class="form-label">Énergie</label>
                                <select class="form-select" id="energie" name="energie">
                                    <option value="" selected disabled>Choisir...</option>
                                    <option value="Essence">Essence</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Hybride">Hybride</option>
                                    <option value="Electrique">Électrique</option>
                                    <option value="GPL">GPL</option>
                                    <option value="Ethanol">Ethanol</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="finition" class="form-label">Finition</label>
                                <input type="text" class="form-control" id="finition" name="finition" placeholder="Ex: GT Line, Allure...">
                            </div>

                            <div class="col-md-4">
                                <label for="couleur" class="form-label">Couleur</label>
                                <input type="text" class="form-control" id="couleur" name="couleur" placeholder="Ex: Blanc Banquise">
                            </div>

                            <div class="col-md-4">
                                <label for="puissance_din" class="form-label">Puissance DIN (ch)</label>
                                <input type="text" class="form-control" id="puissance_din" name="puissance_din" placeholder="Ex: 130 ch">
                            </div>

                            <div class="col-md-4">
                                <label for="puissance_vin" class="form-label">Puissance Fiscale (CV)</label>
                                <input type="text" class="form-control" id="puissance_vin" name="puissance_vin" placeholder="Ex: 7 CV">
                            </div>

                        </div> <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow">
                                <i class="bi bi-save me-2"></i>Enregistrer le véhicule
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>