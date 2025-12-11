<!DOCTYPE html>

<html>

<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--icones de bootstrap-->
</head>


<!--sidebar-->
<?php
include_once './parties_fixes/sidebar.php';
?>



<body>

    <div class="px-4 py-5 my-5 text-center">
        <img class="d-block mx-auto mb-4"
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Test-Logo.svg/1566px-Test-Logo.svg.png?20150906031702"
            alt="" width="72" height="57">
        <h1 class="display-5 fw-bold text-body-emphasis">MyCarX</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">Une nouvelle façon de faire le suivi de l'entretien de son véhicule. Notre site vous
                propose des interfaces intuitives et des explications claires sur tous les aspects de l'entretien de
                votre
                véhicule. Accédez dès à présent à votre espace pour profiter de toutes les fonctionnalités offertes par
                notre application.
            </p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <button type="button" class="btn btn-primary btn-lg px-4 gap-3">Je m'inscris</button>
                <button type="button" class="btn btn-outline-secondary btn-lg px-4">Je me connecte</button>
            </div>
        </div>
    </div>




    <div class="container my-5">
        <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg">
            <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
                <h1 class="display-4 fw-bold lh-1 text-body-emphasis">Notre carnet d'entretien virtuel et connecté</h1>
                <p class="lead">
                    Un carnet mis à jour par votre garage à distance vous permettant de suivre au plus près l'avancement
                    de vos entretiens et révisions.
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3">
                    <button type="button" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold">J'accède à mon carnet
                        d'entretien</button>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden shadow-lg">
                <img class="rounded-lg-3"
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Test-Logo.svg/1566px-Test-Logo.svg.png?20150906031702"
                    alt="" width="720">
            </div>
        </div>
    </div>







    <div class="container px-4 py-5" id="custom-cards">
        <h2 class="pb-2 border-bottom">Nos tutoriels</h2>
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg"
                    style="background-image: url('unsplash-photo-1.jpg');">
                    <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                        <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Vérifier le niveau d'huile moteur</h3>
                        <ul class="d-flex list-unstyled mt-auto">
                            <li class="me-auto">
                                <img src="https://github.com/twbs.png" alt="Bootstrap" width="32" height="32"
                                    class="rounded-circle border border-white">
                            </li>
                            <li class="d-flex align-items-center me-3">
                                <svg class="bi me-2" width="1em" height="1em" role="img" aria-label="Location">
                                    <use xlink:href="#geo-fill"></use>
                                </svg>
                                <small>Fait par</small>
                            </li>
                            <li class="d-flex align-items-center">
                                <svg class="bi me-2" width="1em" height="1em" role="img" aria-label="Duration">
                                    <use xlink:href="#calendar3"></use>
                                </svg> <small>Gérard</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg"
                    style="background-image: url('unsplash-photo-2.jpg');">
                    <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                        <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Configurer son pare soleil</h3>
                        <ul class="d-flex list-unstyled mt-auto">
                            <li class="me-auto">
                                <img src="https://github.com/twbs.png" alt="Bootstrap" width="32" height="32"
                                    class="rounded-circle border border-white">
                            </li>
                            <li class="d-flex align-items-center me-3">
                                <svg class="bi me-2" width="1em" height="1em" role="img" aria-label="Location">
                                    <use xlink:href="#geo-fill"></use>
                                </svg>
                                <small>Fait par</small>
                            </li>
                            <li class="d-flex align-items-center">
                                <svg class="bi me-2" width="1em" height="1em" role="img" aria-label="Duration">
                                    <use xlink:href="#calendar3"></use>
                                </svg>
                                <small>Gérard 2</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg"
                    style="background-image: url('unsplash-photo-3.jpg');">
                    <div class="d-flex flex-column h-100 p-5 pb-3 text-shadow-1">
                        <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Comment fermer son coffre par la pensée</h3>
                        <ul class="d-flex list-unstyled mt-auto">
                            <li class="me-auto">
                                <img src="https://github.com/twbs.png" alt="Bootstrap" width="32" height="32"
                                    class="rounded-circle border border-white">
                            </li>
                            <li class="d-flex align-items-center me-3">
                                <svg class="bi me-2" width="1em" height="1em" role="img" aria-label="Location">
                                    <use xlink:href="#geo-fill"></use>
                                </svg>
                                <small>Fait par</small>
                            </li>
                            <li class="d-flex align-items-center">
                                <svg class="bi me-2" width="1em" height="1em" role="img" aria-label="Duration">
                                    <use xlink:href="#calendar3"></use>
                                </svg>
                                <small>Garard</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="container my-5">
        <div class="p-5 text-center bg-body-tertiary rounded-3">
            <h1 class="text-body-emphasis">Accédez à votre véhicule en 3D</h1>
            <iframe width="1024" height="640" allowfullscreen src="https://v3d.net/1dh1"></iframe>
            <p class="col-lg-8 mx-auto fs-5 text-muted">
                Visualisez les problèmes de votre véhicule, accédez rapidement aux informations dont vous avez besoin.
            </p>
            <div class="d-inline-flex gap-2 mb-5">
                <button class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" type="button">
                    J'accède à mon véhicule
                </button>
            </div>
        </div>
    </div>


    <script>
        const header = document.querySelector('header');
        document.body.style.paddingTop = header.offsetHeight + 'px';
    </script>

</body>

<!--FAQ-->

<div class="accordion mx-5 my-5 px-5" id="accordionExample">
    
    <h1>FAQ</h1><br>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                À quoi sert le carnet d’entretien ?
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Le carnet d'entretien est un registre qui vous aide à garder une trace de toutes les opérations d'entretien effectuées sur votre voiture depuis que vous l'avez achetée.
                <ul>
                    <li>Chaque opération est classée selon son importance.</li>
                    <li>Cela vous permet d'anticiper et de planifier le budget pour les futurs entretiens, en tenant compte de l'âge de votre voiture, de son kilométrage, et de son historique d'entretien.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                À quoi sert l’assistant de fiabilité ?
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                L'Assistant Fiabilité est un outil qui vous donne des informations spécifiques sur votre voiture, telles que la fiabilité de certaines pièces et sa consommation de carburant.<br>
                Son rôle principal est de vous informer à l'avance des particularités notables de votre véhicule.<br>
                En clair, il vous sert de guide pour connaître les points forts et faibles de votre modèle afin d'éviter les surprises.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                C’est quoi les tutoriels mécaniques ?
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Les tutoriels mécaniques sont de courtes vidéos qui couvrent divers sujets liés à la conduite et aux véhicules.
                <ul>
                    <li>Leur but principal est de vous aider à réduire vos dépenses liées à votre voiture.</li>
                    <li>Ils vous donnent des astuces pratiques pour économiser sur la consommation de carburant, l'entretien ou encore pour améliorer la sécurité (et donc éviter les accidents coûteux).</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Quels sont les avantages de l’espace membre ?
            </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Le statut de membre vous donne accès à un suivi complet pour chacun de vos véhicules et à toutes les fonctionnalités de l'application.
                <ul>
                    <li>Vous bénéficiez notamment du carnet d'entretien et de l'Assistant Fiabilité.</li>
                    <li>Il vous permet aussi de suivre le prix du carburant, et donc le coût de ravitaillement de votre/vos véhicules.</li>
                    <li>Vous pouvez stocker tous les documents importants liés à votre voiture (assurance, factures, preuves d'entretien, etc.).</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                Qui sommes nous ?
            </button>
        </h2>
        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Ce projet a été développé par un groupe de quatre étudiants en apprentissage qui étudient à l'ISA NUM, une école d'ingénieurs spécialisée en informatique située à Anglet. Cette école propose régulièrement des projets de groupe semestriels en lien avec les Objectifs de Développement Durable (ODD) de l'ONU, et cette application web en est le résultat direct.
                Pour en apprendre plus sur l’école et ses étudiants, rendez vous sur <a href="https://isanum.univ-pau.fr/fr/actualites.html" style="text-decoration: none; color: #3498db; font-weight: 500;">le site officiel de l'école</a> !
            </div>
        </div>
    </div>
</div>

<hr>



</html>