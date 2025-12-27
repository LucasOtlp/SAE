<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$active = "documents";


$message = "Ajouter un document";




$dossierCible = 'uploads/'; // Dossier où on stocke les fichiers
$tailleMax = 1000 * 1024 * 1024; // Taille autorisée (ici 1000 Mo)

$typesAutorises = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
    'mp4'  => 'video/mp4',
    'mov'  => 'video/quicktime',
    'avi'  => 'video/x-msvideo',
    'mpeg' => 'video/mpeg',
    'pdf'  => 'application/pdf',
    'txt'  => 'text/plain',
    'rtf'  => 'application/rtf',
    'doc'  => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls'  => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'ppt'  => 'application/vnd.ms-powerpoint',
    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'odt'  => 'application/vnd.oasis.opendocument.text',
];

// AJouter un fichier
if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_FILES['files']) && isset($_POST['btnAdd'])) {
    if ($_POST['name'] != "") {
    
        $fichier = $_FILES['files'];

        // Vérification s'il y a des erreurs 
        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            $message = ("Erreur lors de l'upload. Code erreur : " . $fichier['error']);
        }
        else {
            // Vérification de la taille 
            if ($fichier['size'] > $tailleMax) {
                $message = ("Erreur : Le fichier est trop volumineux.");
            }
            else {
                //Vérification de sécurité du type
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $typeReel = $finfo->file($fichier['tmp_name']);
                if (!in_array($typeReel, $typesAutorises)) {
                    $message = ("Erreur : Type de fichier non autorisé.");
                }
                else {
                    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
                    $nouveauNom = $_POST['name'] . '.' . $extension;
                    $cheminFinal = $dossierCible . $nouveauNom;
                    //Vérification qu'un fichier ne possède déjà pas ce nom
                    if (!(file_exists($cheminFinal))) {
                        if (move_uploaded_file($fichier['tmp_name'], $cheminFinal)) {
                            $message = "Succès ! Fichier bien enregistré.";
                        } else {
                            $message = "Erreur lors de l'enregistrement du fichier.";
                        }
                    }
                    else {
                        $message = "Nom de fichier déjà utilisé";
                    }
                }
            }
        }
    }
    else {
        $message = "Nom de fichier vide ";
    }
}

// Supprimer un fichier
if (isset($_GET['supprimer'])) {
    // basename renvoie le dernier lien du chemin pour acceder au fichier à supprimer
    $fichierASupprimer = basename($_GET['supprimer']);
    
    // Chemin (__DIR__ = le chemin où se trouve la page web actuelle sur le serveur)
    $cheminFichier = __DIR__ . '/uploads/' . $fichierASupprimer;

    if (file_exists($cheminFichier)) {
        if (unlink($cheminFichier)) {
            $message = "Fichier supprimé avec succès.";
            header("Location: ./documents.php"); 
            exit; 
        } else {
            $message = "Erreur : Impossible de supprimer le fichier.";
        }
    } else {
        $message = "Erreur : Le fichier est introuvable.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex">

<?php include_once './../parties_fixes/sidebar.php'; ?>

<div class="flex-grow-1 p-3">

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <form method="post" enctype="multipart/form-data" class="row g-3 bg-white p-4 shadow rounded">
                    <div class="col-12">
                        <div class="alert text-center mb-0">
                            <?php echo "$message"; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="files" class="form-label">Sélectionner un fichier</label>
                        <input type="file" class="form-control" name="files" id="files" accept="image/*, video/*, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .txt, .rtf, .odt">
                    </div>
                    <div class="col-12">
                        <label for="name" class="form-label">Nom du fichier</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Entrez le nom du fichier">
                    </div>

                    <div class="col-12 d-grid">
                        <button name="btnAdd" type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-5">
            <h3 class="mb-4">Vos documents</h3>
            
            <div class="row g-3">
                <?php
                // On vérifie si le dossier existe pour éviter les erreurs
                if (is_dir($dossierCible)) {
                    $fichiers = scandir($dossierCible);
                    
                    // Enlever les  "." et ".." 
                    $fichiers = array_diff($fichiers, array('.', '..'));

                    //  SI on trouve rien
                    if (count($fichiers) == 0) {
                        echo '<div class="col-12"><div class="alert alert-info">Aucun document disponible pour le moment.</div></div>';
                    }


                    foreach ($fichiers as $nomFichier) {
                        
                        $ext = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
                        
                        $icone = "bi-file-earmark"; // Icône par défaut
                        $couleur = "text-secondary"; // Couleur par défaut

                        switch ($ext) {
                            case 'pdf':
                                $icone = "bi-file-earmark-pdf-fill"; 
                                $couleur = "text-danger"; // rouge PDF
                                break;
                            case 'jpg': case 'jpeg': case 'png': case 'gif': case 'webp':
                                $icone = "bi-file-earmark-image-fill";
                                $couleur = "text-primary"; // Bleu, images
                                break;
                            case 'doc': case 'docx': case 'odt':
                                $icone = "bi-file-earmark-word-fill";
                                $couleur = "text-primary";
                                break;
                            case 'xls': case 'xlsx':
                                $icone = "bi-file-earmark-excel-fill";
                                $couleur = "text-success"; // vert Excel
                                break;
                            case 'mp4': case 'avi': case 'mov':
                                $icone = "bi-file-earmark-play-fill";
                                $couleur = "text-danger"; // rouge autre
                                break;
                        }

                        $lienFichier = 'uploads/' . $nomFichier;
                        ?>

                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 display-6 <?php echo $couleur; ?>">
                                        <i class="bi <?php echo $icone; ?>"></i>
                                    </div>
                                    
                                    <div class="flex-grow-1 text-truncate">
                                        <h6 class="card-title text-truncate" title="<?php echo $nomFichier; ?>">
                                            <?php echo $nomFichier; ?>
                                        </h6>
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo $lienFichier; ?>" class="btn btn-sm btn-outline-dark flex-fill" download>
                                                <i class="bi bi-download"></i> Télécharger
                                            </a>
                                            <a href="?supprimer=<?php echo $nomFichier; ?>" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?');">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-warning">Le dossier uploads n\'existe pas encore.</div></div>';
                }
                ?>
            </div>
        </div>
    </div>

</div>

</body>
</html>