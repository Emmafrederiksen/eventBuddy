<?php
/** @var PDO $db */
require "settings/init.php";

$loggedInUserId = 4;

// Hent alle events, som brugeren har oprettet (evuseOwner = 1)
$eventsCreated = $db->sql(" SELECT * FROM events JOIN event_user_con ON evenId = evuseEvenId 
    WHERE evuseUserId = :userId
    AND evuseOwner = 1", [":userId" => $loggedInUserId
]);

?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Events oprettet af mig</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
    <img src="images/baggrundsbillede.jpg" alt="background" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="filter: blur(0px);">

    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); z-index: 1;"></div>
</div>

<!-- Første container til overskrift og tekst -->
<div class="container">
    <div class="row">
        <div class="py-5">
            <p class="overskrift-stor text-white">Events oprettet af mig</p>
            <a href="minside.php" class="text-decoration-underline tilbageknap brødtekst-knap">Tilbage</a>
        </div>
    </div>
</div>


<div class="container">

    <div class="swiper">

        <div class="swiper-wrapper">

            <?php foreach ($eventsCreated as $event): ?>

                <div class="swiper-slide d-flex justify-content-center">
                    <div class="card mt-5 rounded-5">

                        <h5 class="card-header text-center overskrift-lille py-3"><?php echo $event -> evenName; ?></h5>

                        <div class="card-body p-0" style="height: 200px;">
                            <img src="userimages/<?php echo $event->evenImage; ?>" class="card-img-top img-fluid" alt="<?php echo $event->evenImage; ?>" style="height: 100%; width: 100%; object-fit: cover;">
                        </div>

                        <div class="card-footer text-center pt-3" style="height: 80px;">
                            <a href="redigerevent.php?evenId=<?php echo $event->evenId; ?>" class="btn btn-primærknap ps-4 pe-4 py-2 brødtekst-knap rounded-pill">Rediger</a>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <!-- Navigation buttons -->
        <div class="swiper-button-prev tilbageknap mt-2"></div>
        <div class="swiper-button-next tilbageknap mt-2"></div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>

    const swiper = new Swiper('.swiper', {
        slidesPerView: 1,
        breakpoints: {
            576: {
                slidesPerView: 1,
            },
            992: {
                slidesPerView: 2,
            },
            1200: {
                slidesPerView: 3,
            },
        },
        loop: false, // Du kan også sætte loop til true, hvis du vil have det som uendelig loop
        watchOverflow: true, // Slukker for navigation og pagination, hvis der ikke er nok slides

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

    });

</script>

</body>
</html>
