<?php
include_once './../parties_fixes/sidebar.php';
?>
<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex">

    <?php
    //connexion à l'API pour obtenir le prix de l'essence dans les stations essence en France
    require './../vendor/autoload.php';
    use GuzzleHttp\Client;
    $client = new Client(['base_uri' => 'https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records']);
    ?>

    <div class="container my-5 me-5">
        <div class="p-5 text-center bg-body-tertiary rounded-5"> 
            <h1 class="text-body-emphasis">Accédez au prix de l'essence près de chez vous</h1>
            <p class="col-lg-8 mx-auto fs-5 text-muted">
                Choisissez la ville où vous souhaitez faire votre plein et découvrez les prix proposés
            </p>
            <div> 
                <form action="./prixEssence.php" method="POST">
                    Ville
                    <input type="text" name="ville">
                    <br>
                    <br>
                    <input class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" type="submit" value ="chercher ma station">
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
                    if(isset($_REQUEST["ville"])){
                        //Envoie et traitement de la requete API
                        $response = $client->request('GET', '?select=ville%2C%20adresse%2C%20gazole_prix%2C%20sp95_prix%2C%20sp98_prix%2C%20e85_prix%2C%20e10_prix&where=ville%3D"'.$_REQUEST["ville"].'"');
                        $body = $response->getBody()->getContents();
                        $data = json_decode($body, true);
                        if ($data["results"]){
                            echo "<table>";
                            echo "<tr><th>Ville</th><th>Adresse</th><th>prix gazole</th><th>prix sp95</th><th>prix sp98</th><th>prix e85</th><th>prix e10</th></tr>";
                            foreach($data["results"] as $station){
                                echo "<tr><td> ".$station["ville"]." </td><td> ".$station["adresse"]." </td><td> ".$station["gazole_prix"]." </td><td> ".$station["sp95_prix"]." </td><td> ".$station["sp98_prix"]." </td><td> ".$station["e85_prix"]." </td><td> ".$station["e10_prix"]." </td></tr>";
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