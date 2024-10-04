<?php
/** @var PDO $db */
require "settings/init.php";

// Brugerens ID
$loggedInUserId = 4;

// Tjek for nye events (hvor evuseStatus er NULL, dvs. brugeren har ikke set eventet endnu)
$newEvents = $db->sql("SELECT COUNT(*) AS newEventsCount FROM event_user_con WHERE evuseUserId = :userId AND evuseStatus IS NULL", [
    ":userId" => $loggedInUserId
]);

// Gemmer antallet af nye events
$newEventsCount = $newEvents[0]->newEventsCount;

?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Min side</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>

<!-- Baggrundsbillede med overlay -->
<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
    <img src="images/baggrundsbillede.jpg" alt="background"
         class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="filter: blur(0px);">

    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); z-index: 1;"></div>
</div>


<div class="container">
    <div class="row">
        <div class="py-5">
            <p class="overskrift-stor text-white">Min side</p>
            <a href="index.php" class="text-decoration-underline tilbageknap brødtekst-knap">Tilbage</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row w-100 mt-5">
        <div class="col-8 col-sm-10 col-md-8 col-lg-6 col-xl-4 offset-2 offset-sm-1 offset-md-2 offset-lg-3 offset-xl-4 ">
            <div class="mb-4">
                <a href="eventsoprettetafmig.php">
                    <button class="btn btn-minside w-100 rounded-pill p-3 brødtekst-knap">Events oprettet af mig
                    </button>
                </a>
            </div>


            <div class="mb-4 position-relative">
                <a href="eventsjegerinviterettil.php">
                    <button class="btn btn-minside w-100 rounded-pill p-3 brødtekst-knap">
                        Events jeg er inviteret til

                        <!-- Vis badge hvis der er nye events -->
                        <?php
                        if ($newEventsCount > 0) {
                            echo '<span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-danger">' . $newEventsCount . '</span>';
                        }
                        ?>

                    </button>
                </a>
            </div>


            <div class="mb-4">
                <a href="opretnytevent.php">
                    <button class="btn btn-minside w-100 rounded-pill p-3 brødtekst-knap">Opret nyt event</button>
                </a>
            </div>


            <div class="mb-4">
                <a href="minside.php">
                    <button class="btn btn-minside w-100 rounded-pill p-3 brødtekst-knap">Min profil</button>
                </a>
            </div>
        </div>
    </div>
</div>


<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
