<?php
/** @var PDO $db */
require "settings/init.php";

// Fiktivt bruger-ID (skal erstattes med det rigtige ID fra login-systemet senere)
$loggedInUserId = 4;

// Hent alle events, hvor brugeren er gæst (evuseOwner = 0)
$invitedEvents  = $db->sql(" SELECT * FROM events JOIN event_user_con ON events.evenId = event_user_con.evuseEvenId WHERE event_user_con.evuseUserId = :userId
    AND event_user_con.evuseOwner = 0", [
    ":userId" => $loggedInUserId
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

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
    <img src="images/background1.webp" alt="background" class="position-absolute top-0 start-0 w-100 h-100"
         style="object-fit: cover;">
</div>

<!-- Første container til overskrift og tekst -->
<div class="container">
    <div class="row">
        <div class="py-5">
            <p class="overskrift-stor text-white">Events jeg er inviteret til</p>
            <a href="minside.php" class="text-decoration-underline tilbageknap brødtekst-knap">Tilbage</a>
        </div>
    </div>
</div>


<div class="container">
    <div id="eventCarousel" class="carousel slide" data-bs-interval="false">
        <div class="carousel-inner pt-lg-5">
            <div class="carousel-item active">
                <div class="d-flex justify-content-start">
                    <!-- Brug den opdaterede $eventsCreated variabel til kun at vise brugerens oprettede events -->
                    <?php
                    $count = 0;
                    foreach ($invitedEvents as $event) {

                        // Starter en ny række for hvert tredje kort
                        if ($count % 3 == 0 && $count != 0) {
                            echo '</div></div><div class="carousel-item"><div class="d-flex justify-content-start">';
                        }
                        ?>

                        <div class="card mx-5 rounded-5 mb-4" style="width: 25rem; height: 400px;">
                            <h5 class="card-title text-center overskrift-lille py-3"><?php echo $event->evenName; ?></h5>
                            <div class="card-body p-0" style="height: 250px;">
                                <img src="userimages/<?php echo $event->evenImage; ?>" class="card-img-top img-fluid" alt="..." style="max-height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-footer text-center" style="height: 150px;">
                                <a href="eventinfo.php?evenId=<?php echo $event->evenId; ?>" class="btn btn-primærknap ps-4 pe-4 py-2 brødtekst-knap rounded-pill">Se mere</a>
                            </div>
                        </div>


                        <?php
                        $count++;
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- Carousel controls (previous and next) -->
        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.getElementById('eventCarousel');
        const prevButton = document.querySelector('.carousel-control-prev');
        const nextButton = document.querySelector('.carousel-control-next');
        const carouselItems = document.querySelectorAll('.carousel-item');

        // Tjek for at opdatere pilene baseret på slide position
        function updateCarouselControls() {
            const activeIndex = Array.from(carouselItems).findIndex(item => item.classList.contains('active'));

            // Skjul venstre pil, hvis vi er på første slide
            if (activeIndex === 0) {
                prevButton.style.display = 'none';
            } else {
                prevButton.style.display = 'block';
            }

            // Skjul højre pil, hvis vi er på sidste slide
            if (activeIndex === carouselItems.length - 1) {
                nextButton.style.display = 'none';
            } else {
                nextButton.style.display = 'block';
            }
        }

        // Lyt til carousel-events for at opdatere pilene, når der skiftes slide
        carousel.addEventListener('slid.bs.carousel', updateCarouselControls);

        // Kald funktionen når siden først indlæses
        updateCarouselControls();
    });
</script>

</body>
</html>
