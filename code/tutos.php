<?php
$tutoriels_EcoCarb = [
    [
        'titre' => "Comprendre l'aérodynamisme",
        'image' => "./multimedia/images/aerodynamisme.jpg", 
        'video' => "./multimedia/videos/Aerodynamisme.mp4"
    ],
    [
        'titre' => "Comprendre les différents types de carburants",
        'image' => "./multimedia/images/types-carburants.jpg", 
        'video' => "./multimedia/videos/TypesCarburants.mp4"
    ]
];

$tutoriels_Entretien = [
    [
        'titre' => "Vérifier le niveau d'huile moteur",
        'image' => "./multimedia/images/niveau-huile.jpg", 
        'video' => "./multimedia/videos/NiveauHuile.mp4"
    ],
    [
        'titre' => "Vérifier la pression de ses pneus",
        'image' => "./multimedia/images/pression-pneus.jpeg", 
        'video' => "./multimedia/videos/PressionPneus.mp4"
    ]
];

$tutoriels_Depannage = [
    [
        'titre' => "Changer une roue",
        'image' => "./multimedia/images/changement-roue.jpg", 
        'video' => "./multimedia/videos/ChangementRoue.mp4"
    ],
    [
        'titre' => "Démarrer en cas de batterie KO",
        'image' => "./multimedia/images/demarrage-batterie.jpg", 
        'video' => "./multimedia/videos/DemarrageBatterie.mp4"
    ]
];

$tutoriels_Secu = [
    [
        'titre' => "Gérer ses phares la nuit",
        'image' => "./multimedia/images/gerer-phares.jpg", 
        'video' => "./multimedia/videos/GererPhares.mp4"
    ]
];

$tutoriels_Culture = [
    [
        'titre' => "Comprendre le fonctionnement d'un moteur",
        'image' => "./multimedia/images/fonctionnement-moteur.jpg", 
        'video' => "./multimedia/videos/FonctionnementMoteur.mp4"
    ]
];
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
    <!--icones de bootstrap-->
</head>

<body>

    <div class="container-fluid pt-4 px-4">
        <div class="row align-items-center">
            <div class="col-md-3 col-6">
                <a href="./accueil.php">
                    <button class="btn btn-primary rounded-pill px-4 py-2 shadow-sm" type="button">
                        <i class="bi bi-arrow-left me-2"></i> Retour
                    </button>
                </a>
            </div>

            <div class="col-md-6 d-none d-md-block text-center">
                <h1 class="fw-bold display-5 mb-0">Tutoriels Mécaniques</h1>
            </div>

            <div class="col-md-3 d-none d-md-block"></div>
            
            <div class="col-12 d-md-none text-center mt-3">
                <h1 class="fw-bold h2">Tutoriels Mécaniques</h1>
            </div>
        </div>
    </div>

    <hr class="my-4 mx-5 opacity-25">

    <div class="container px-4 py-5" id="custom-cards">
        
        <h2 class="pb-2 border-bottom">Économies et Carburants</h2>
        
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
            
            <?php foreach($tutoriels_EcoCarb as $tuto): ?>
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg video-card-trigger"
                        style="background-image: url('<?php echo $tuto['image']; ?>'); background-size: cover; background-position: center; cursor: pointer; min-height: 300px;"
                        data-video="<?php echo $tuto['video']; ?>">
                        
                    <div class="d-flex flex-column h-100 p-4 pb-3 text-white text-shadow-1" 
                        style="background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);">
                        
                        <h3 class="mt-auto mb-4 display-6 lh-1 fw-bold"><?php echo $tuto['titre']; ?></h3>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

    </div>

    <div class="container px-4 py-5" id="custom-cards">
        
        <h2 class="pb-2 border-bottom">Entretien Courant</h2>
        
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
            
            <?php foreach($tutoriels_Entretien as $tuto): ?>
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg video-card-trigger"
                        style="background-image: url('<?php echo $tuto['image']; ?>'); background-size: cover; background-position: center; cursor: pointer; min-height: 300px;"
                        data-video="<?php echo $tuto['video']; ?>">
                        
                    <div class="d-flex flex-column h-100 p-4 pb-3 text-white text-shadow-1" 
                        style="background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);">
                        
                        <h3 class="mt-auto mb-4 display-6 lh-1 fw-bold"><?php echo $tuto['titre']; ?></h3>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        
    </div>

    <div class="container px-4 py-5" id="custom-cards">
        
        <h2 class="pb-2 border-bottom">Dépannage et Urgences</h2>
        
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
            
            <?php foreach($tutoriels_Depannage as $tuto): ?>
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg video-card-trigger"
                        style="background-image: url('<?php echo $tuto['image']; ?>'); background-size: cover; background-position: center; cursor: pointer; min-height: 300px;"
                        data-video="<?php echo $tuto['video']; ?>">
                        
                    <div class="d-flex flex-column h-100 p-4 pb-3 text-white text-shadow-1" 
                        style="background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);">
                        
                        <h3 class="mt-auto mb-4 display-6 lh-1 fw-bold"><?php echo $tuto['titre']; ?></h3>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        
    </div>

    <div class="container px-4 py-5" id="custom-cards">
        
        <h2 class="pb-2 border-bottom">Sécurité et Comportement</h2>
        
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
            
            <?php foreach($tutoriels_Secu as $tuto): ?>
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg video-card-trigger"
                        style="background-image: url('<?php echo $tuto['image']; ?>'); background-size: cover; background-position: center; cursor: pointer; min-height: 300px;"
                        data-video="<?php echo $tuto['video']; ?>">
                        
                    <div class="d-flex flex-column h-100 p-4 pb-3 text-white text-shadow-1" 
                        style="background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);">
                        
                        <h3 class="mt-auto mb-4 display-6 lh-1 fw-bold"><?php echo $tuto['titre']; ?></h3>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        
    </div>

    <div class="container px-4 py-5" id="custom-cards">
        
        <h2 class="pb-2 border-bottom">Culture Mécanique</h2>
        
        <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
            
            <?php foreach($tutoriels_Culture as $tuto): ?>
            <div class="col">
                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg video-card-trigger"
                        style="background-image: url('<?php echo $tuto['image']; ?>'); background-size: cover; background-position: center; cursor: pointer; min-height: 300px;"
                        data-video="<?php echo $tuto['video']; ?>">
                        
                    <div class="d-flex flex-column h-100 p-4 pb-3 text-white text-shadow-1" 
                        style="background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);">
                        
                        <h3 class="mt-auto mb-4 display-6 lh-1 fw-bold"><?php echo $tuto['titre']; ?></h3>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        
    </div>

    <div id="videoModal" class="custom-modal">
        <div class="custom-modal-content">
            <span class="close-btn">&times;</span>
            <div class="video-wrapper">
                <video id="videoPlayer" controls style="width: 100%; height: 100%; object-fit: contain; background:black;">
                <source src="" type="video/mp4">
                    Votre navigateur ne gère pas la vidéo.
                </video>
            </div>
        </div>
    </div>

    <style>
    .custom-modal {
    display: none; position: fixed; z-index: 9999; left: 0; top: 0;
    width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.9);
    align-items: center; justify-content: center;
    }
    .custom-modal-content {
    position: relative; width: 90%; max-width: 1100px; max-height: 90vh;
    background: #000; border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5); overflow: hidden;
    display: flex; justify-content: center;
    }
    .video-wrapper { position: relative; width: 100%; }
    .close-btn {
    position: absolute; top: 10px; right: 20px; color: white;
    font-size: 40px; cursor: pointer; z-index: 100; font-weight: bold;
    text-shadow: 0 0 5px black; line-height: 1;
    }
    .video-card-trigger { transition: transform 0.3s ease; }
    .video-card-trigger:hover { transform: scale(1.02); z-index: 10; }
    .text-gradient-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
        border-radius: 16px; /* Doit correspondre à l'arrondi de votre carte */
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('videoModal');
        const player = document.getElementById('videoPlayer');
        const closeBtn = document.querySelector('.close-btn');
        const triggers = document.querySelectorAll('.video-card-trigger');

        triggers.forEach(card => {
            card.addEventListener('click', () => {
                const videoFile = card.getAttribute('data-video');
                if(videoFile) {
                    player.src = videoFile;
                    modal.style.display = 'flex';
                    player.play(); 
                }
            });
        });

        const closeModal = () => {
            modal.style.display = 'none';
            player.pause(); 
            player.src = ""; 
        };

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape" && modal.style.display === 'flex') closeModal();
        });
    });
    </script>

</body>