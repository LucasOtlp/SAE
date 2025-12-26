<?php 
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

require './../vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records']);


$response = $client->request('GET', '?select=ville%2C%20gazole_prix%2C%20sp95_prix%2C%20sp98_prix&where=ville%3D"Susmiou"&limit=20');

echo "<br>".$response->getStatusCode();


$body = $response->getBody()->getContents();
$data = json_decode($body, true);
echo $data["results"][0]["ville"];
echo "vvvvvvvvvqernfqbrfkbqlrefblhqbrfhjlqbeflbqlbfvlqdsvfqvsfiuqfbiuqfiumqvbjdbvlqbvqberfliubqeurfvbulerbvudbfvbdfuvbljkbvjlkbiubiuerbfimqbeiumbjkvbkbvkmqbjbqjrfvbimqbf";

?>
    <br>
    hello


</body>

</html>