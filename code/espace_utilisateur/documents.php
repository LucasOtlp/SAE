<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$active = "documents";
include_once './../parties_fixes/sidebar.php';





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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files']) && isset($_POST['name'])) {
    
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


?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex">

<div class="flex-grow-1">

    <form method="post" enctype="multipart/form-data" class="row g-3 bg-white p-4 shadow rounded">

        <div class="col-md-12 text-center">
            <label for="files" class="form-label text-center">Ajouter un document : </label>
            <input type="file" name="files" accept="image/*, video/*, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .txt, .rtf, .odt">
        </div>
        <div class="col-md-12 text-center">
            <label for="name" class="form-label text-center">Nom du fichier : </label>
            <input type="text" name="name" size="20">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>