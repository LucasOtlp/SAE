<?php
session_start();
require_once './../connection.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: loginUser.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$message = "";

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {

    $_SESSION = array();
    session_destroy();
    header("Location: loginUser.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $telephone = trim($_POST["telephone"]);

    if (!empty($nom) && !empty($prenom)) {
        try {
            // Mise à jour de la bdd
            $sql = "UPDATE utilisateur SET nom = ?, prenom = ?, telephone = ? WHERE id_user = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $telephone, $id_user]);
            
            $message = "<div class='alert success'>Informations modifiées avec succès !</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert error'>Erreur SQL : " . $e->getMessage() . "</div>";
        }
    } else {
        $message = "<div class='alert error'>Le nom et le prénom sont obligatoires.</div>";
    }
}

// Récupération des infos utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_user = ?");
    $stmt->execute([$id_user]);
    $currentUser = $stmt->fetch();
} catch (PDOException $e) {
    die("Erreur de récupération : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profil-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 450px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin: 0;
        }

        .btn-logout {
            background-color: #ff4d4d;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background-color: #cc0000;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-weight: 600;
        }

        input[type="text"],
        input[type="tel"], 
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="profil-container">
        
        <div class="header-box">
            <h1>Mon Profil</h1>
            <a href="compte.php?action=logout" class="btn-logout">Déconnexion</a>
        </div>

        <?= $message ?>

        <form action="" method="POST">

            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" 
                       value="<?= htmlspecialchars($currentUser['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($currentUser['prenom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($currentUser['telephone']) ?>">
            </div>

            <button type="submit" class="btn-submit">Mettre à jour</button>
        </form>
    </div>

</body>
</html>