<?php
/** @var PDO $db */
require "settings/init.php";

$loggedInUserId = 4;

// Hent alle events, hvor brugeren er gæst (evuseOwner = 0)
$invitedEvents  = $db->sql(" SELECT * FROM events JOIN event_user_con ON evenId = evuseEvenId 
    WHERE evuseUserId = :userId
    AND event_user_con.evuseOwner = 0", [":userId" => $loggedInUserId
]);

?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Events jeg er inviteret til</title>

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


<div class="container">
    <div class="row">
        <div class="py-5">
            <p class="overskrift-stor text-white">Events jeg er inviteret til</p>
            <a href="minside.php" class="text-decoration-underline tilbageknap brødtekst-knap">Tilbage</a>
        </div>
    </div>
</div>

<div class="container">

        <div class="swiper">

            <div class="swiper-wrapper">

                <?php foreach ($invitedEvents as $event): ?>

                    <div class="swiper-slide d-flex justify-content-center">
                        <div class="card mt-5 rounded-5">

                            <h5 class="card-header text-center overskrift-lille py-3">

                            <?php echo $event->evenName;

                            if ($event->evuseStatus === null) {
                            echo '<span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-danger brødtekst-lille">!</span>';
                            } elseif ($event->evuseStatus == 1) {
                            echo '<span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-success brødtekst-lille">Deltager</span>';
                            } else {
                            echo '<span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-secondary brødtekst-lille">Deltager ikke</span>';
                            }
                            ?>

                            </h5>

                            <div class="card-body p-0" style="height: 200px;">
                                <img src="userimages/<?php echo $event->evenImage; ?>" class="card-img-top img-fluid" alt="<?php echo $event->evenImage; ?>">
                            </div>

                            <div class="card-footer text-center pt-3" style="height: 80px;">
                                <a href="eventinfo.php?evenId=<?php echo $event->evenId; ?>" class="btn btn-primærknap ps-4 pe-4 py-2 brødtekst-knap rounded-pill">Se mere</a>

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
        loop: false,
        watchOverflow: true,

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

    });


</script>

</body>
</html>
