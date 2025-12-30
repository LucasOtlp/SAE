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
?>

    <div class="container my-5 me-5">
        <div class="p-5 text-center bg-body-tertiary rounded-5"> 
            <h1 class="text-body-emphasis">Accédez aux garages</h1>
            <p class="col-lg-8 mx-auto fs-5 text-muted">
                
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
                    if(isset($_REQUEST["garage"]) && $_REQUEST["garage"] != "" ){
                        //Envoie et traitement de la requete API
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
            </div>
        </div>
    </div>

</body>
</html>