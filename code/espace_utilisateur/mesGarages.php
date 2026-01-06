<?php
$active = "garage";
include_once './../parties_fixes/sidebar.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex">

<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once "./../connection.php";

//connexion à l'API pour accéder à l'annuaire des entreprises en France
require './../vendor/autoload.php';
use GuzzleHttp\Client;
$client = new Client(['base_uri' => 'https://recherche-entreprises.api.gouv.fr/search']);

?>

    <div class="container my-5 me-5">
        <div class="p-5 text-center bg-body-tertiary rounded-5"> 
            <h1 class="text-body-emphasis">Accédez aux garages</h1>
            <p class="col-lg-8 mx-auto fs-5 text-muted">
                Cherchez un garage parmis ceux enregistrés sur le site
            </p>
            <div> 
                <form action="./mesGarages.php" method="POST">
                    Garage
                    <input type="text" name="garage">
                    <br>
                    <br>
                    <input class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" type="submit" value ="chercher mon garage">
                </form>
            </div>
            <style>
                table {
                    border-collapse: collapse;
                    border: 2px solid rgb(140 140 140);
                    font-family: sans-serif;
                    font-size: 0.8rem;
                    letter-spacing: 1px;
                }
                th,
                td {
                    border: 1px solid rgb(160 160 160);
                    padding: 8px 10px;
                }
                td:last-of-type {
                    text-align: center;
                }
            </style>
            <div class="mx-auto p-2">
                <?php
                //On recherche un garage parmi ceux enregistrés dans le site si le champ associé est rempli
                    if(isset($_REQUEST["garage"]) && $_REQUEST["garage"] != "" ){
                        //Recherche dans la base de données du site
                        $ordreSQL = "SELECT * FROM garage WHERE nom='".$_REQUEST["garage"]."'";
                        $resultat = $pdo->query($ordreSQL);
                        $leGarage = $resultat->fetch();
                        if ($leGarage != null){
                            echo "<table>";
                            echo "<tr><th>Nom</th><th>Mail</th></tr>";
                            echo "<tr><td> ".$leGarage["nom"]." </td><td> ".$leGarage["mail"]." </td></tr>";
                            echo "</table>";
                        }else{
                            echo "aucun garage correspondant";
                        }

                    }else{
                        //on affiche tous les garages si le champ est vide
                        $ordreSQL = "SELECT * FROM garage";
                        $lesGarages = $pdo->query($ordreSQL);
                        echo "<table>";
                        echo "<tr><th>Nom</th><th>Mail</th></tr>";                            
                        foreach($lesGarages as $leGarage){
                            echo "<tr><td> ".$leGarage["nom"]." </td><td> ".$leGarage["mail"]." </td></tr>";
                        }
                        echo "</table>";
                    }
                ?>

                <p class="col-lg-8 mx-auto fs-5 text-muted">
                    Cherchez un garage autour de chez vous
                </p>
                <div> 
                    <form action="./mesGarages.php" method="POST">
                        Code postal
                        <input type="text" name="CP">
                        <br>
                        <br>
                        <input class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" type="submit" value ="chercher mon garage">
                    </form>
                </div>



                <?php
                    //on recherche les garages à l'aide de l'API des entreprises. Les codes 45.20A, 45.20B et 45.11Z sont ceux correspondants aux types d'entreprises cherchées (garages, concessions)
                    if(isset($_REQUEST["CP"]) && is_numeric($_REQUEST["CP"]) && strlen($_REQUEST["CP"]) == 5){
                        $response = $client->request('GET', '?activite_principale=45.20A%2C45.20B%2C45.11Z&code_postal='.$_REQUEST["CP"]);
                        $body = $response->getBody()->getContents();
                        $data = json_decode($body, true);
                        if ($data["results"]){
                            echo "<table>";
                            echo "<tr><th>Nom</th><th>Adresse siège</th><th>adresse garage</th></tr>";
                            foreach($data["results"] as $garage){
                                for($i=0;$i<count($garage["matching_etablissements"]);$i++){
                                    echo "<tr><td> ".$garage["nom_complet"]." </td><td> ".$garage["siege"]["adresse"]." </td><td> ".$garage["matching_etablissements"][$i]["adresse"]." </td></tr>";
                                }
                            }
                            echo "</table>";
                        }else {
                            echo "pas de station dans la ville choisie";
                        }
                    }
                ?>


            </div>
        </div>
    </div>

</body>
</html>