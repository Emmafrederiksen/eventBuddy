<?php
/** @var PDO $db */
require "settings/init.php";
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
            <p class="overskrift-stor text-white">Events oprettet af mig</p>
            <a href="minside.php" class="text-decoration-underline text-white brødtekst-knap">Tilbage</a>
        </div>
    </div>
</div>

<br>
<br>
<br>
<br>
<br>


<div class="container">
    <div id="eventCarousel" class="carousel slide" data-bs-interval="false">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="d-flex justify-content-start">
                    <?php
                    $events = $db->sql("SELECT evenId, evenName, evenImage FROM events ORDER BY evenDateTime ASC");
                    $count = 0;
                    foreach ($events as $event) {

                        // Starter en ny række for hvert tredje kort
                        if ($count % 3 == 0 && $count != 0) {
                            echo '</div></div><div class="carousel-item"><div class="d-flex justify-content-start flex-wrap">';
                        }
                        ?>

                        <div class="card mx-5 mb-4" style="width: 20rem;">
                            <h5 class="card-title text-center overskrift-lille"><?php echo $event->evenName; ?></h5>
                            <div class="card-body">
                                <img src="<?php echo $event->evenImage; ?>" class="card-img-top" alt="...">
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" class="btn btn-primærknap ps-4 pe-4 py-2 brødtekst-knap rounded-pill">Rediger event</a>
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
