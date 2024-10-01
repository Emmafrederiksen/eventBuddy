<?php
/** @var PDO $db */
require "settings/init.php";

// Tjek om evenId er sat i URL'en, ellers vis en fejlmeddelelse
if (empty($_GET["evenId"])) {
    echo "evenId mangler i URL'en!";
    exit;
}

// Hent evenId fra URL'en
$evenId = $_GET["evenId"];

// Hent eventets oplysninger fra databasen
$event = $db->sql("SELECT * FROM events WHERE evenId = :evenId", [":evenId" => $evenId]);
$event = $event[0];

// Antag at brugerens ID hentes fra sessionen eller en anden kilde
$loggedInUserId = 4; // Dette skal ændres til sessionens bruger-ID

// Opdater deltagelsesstatus
if (isset($_POST['status']) && isset($_POST['evenId'])) {
    $status = $_POST['status'];
    $evenId = $_POST['evenId'];

    $db->sql("UPDATE event_user_con SET evuseStatus = :status 
              WHERE evuseEvenId = :evenId AND evuseUserId = :userId", [
        ":evenId" => $evenId,
        ":userId" => $loggedInUserId,
        ":status" => $status
    ]);

    // Omdiriger efter opdatering
    header("Location: eventinfo.php?evenId=$evenId&status_updated=1");
    exit();
}

// Hent brugerens status fra event_user_con-tabellen
$userStatus = $db->sql("SELECT evuseStatus FROM event_user_con WHERE evuseEvenId = :evenId AND evuseUserId = :userId", [
    ":evenId" => $evenId,
    ":userId" => $loggedInUserId
]);

// Brug status til at bestemme knappefarven
if (!empty($userStatus)) {
    $status = $userStatus[0]->evuseStatus; // 1 for deltager, 0 for deltager ikke
} else {
    $status = null; // Hvis brugeren ikke har angivet status endnu
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <title><?php echo $event->evenName; ?></title>
    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
    <img src="images/background1.webp" alt="background" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;">
</div>

<!-- Første container til overskrift og tekst -->
<div class="container">
    <div class="row py-5">
        <div class="col-7">
            <p class="overskrift-stor text-white"><?php echo $event->evenName; ?></p>
            <a href="eventsduerinviterettil.php" class="text-decoration-underline tilbageknap brødtekst-knap">Tilbage</a>
        </div>

        <div class="col-5 text-end pt-5">
            <form method="post" action="eventinfo.php?evenId=<?php echo $evenId; ?>" id="statusForm">
                <input type="hidden" name="evenId" value="<?php echo $evenId; ?>">
                <input type="hidden" name="status" id="status">

                <button id="deltagerBtn" type="button" class="btn-deltager brødtekst-knap rounded-pill me-3 p-1" style="width: 150px">Deltager</button>
                <button id="ikkeDeltagerBtn" type="button" class="btn-ikke-deltager brødtekst-knap rounded-pill p-1" style="width: 150px">Deltager ikke</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="row pt-5 mt-4">
        <div class="col-5">
            <p class="overskrift-mellem text-white">Beskrivelse af event:</p>
            <p class="brødtekst-mellem text-white"><?php echo $event->evenDescription; ?></p>
        </div>

        <div class="col-2"></div>

        <div class="col-5">
            <p class="overskrift-mellem text-white">Lokation:</p>
            <p class="brødtekst-mellem text-white"><?php echo $event->evenLocation; ?></p>
        </div>

        <div class="col-5">
            <p class="overskrift-mellem text-white pt-5">Dato og tid:</p>
            <p class="brødtekst-mellem text-white">
                <?php echo (new DateTime($event->evenDateTime))->format('d. F Y, \k\l. H:i'); ?>
            </p>
        </div>

        <div class="col-2"></div>

        <div class="col-5">
            <p class="overskrift-mellem text-white pt-5">Se gæsteliste? Klik her</p>
        </div>
    </div>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const deltagerBtn = document.getElementById('deltagerBtn');
    const ikkeDeltagerBtn = document.getElementById('ikkeDeltagerBtn');
    const statusField = document.getElementById('status');
    const statusForm = document.getElementById('statusForm');

    // Brug status fra PHP til at ændre knappernes udseende
    const userStatus = <?php echo isset($status) ? $status : 'null'; ?>;

    if (userStatus === 1) {
        deltagerBtn.style.backgroundColor = '#C99D45'; // Guld farve
        deltagerBtn.style.color = 'white';
        deltagerBtn.style.border = '2px solid #C99D45';

        ikkeDeltagerBtn.style.backgroundColor = '#f0f0f0'; // Lys grå farve
        ikkeDeltagerBtn.style.color = '#333';
        ikkeDeltagerBtn.style.border = '1px solid #ccc';
        ikkeDeltagerBtn.style.opacity = '0.6';
    } else if (userStatus === 0) {
        ikkeDeltagerBtn.style.backgroundColor = '#C99D45'; // Guld farve
        ikkeDeltagerBtn.style.color = 'white';
        ikkeDeltagerBtn.style.border = '2px solid #C99D45';

        deltagerBtn.style.backgroundColor = '#f0f0f0'; // Lys grå farve
        deltagerBtn.style.color = '#333';
        deltagerBtn.style.border = '1px solid #ccc';
        deltagerBtn.style.opacity = '0.6';
    }

    // Håndter klik på knapper
    deltagerBtn.addEventListener('click', function() {
        statusField.value = 1; // 1 betyder deltager
        statusForm.submit();
    });

    ikkeDeltagerBtn.addEventListener('click', function() {
        statusField.value = 0; // 0 betyder deltager ikke
        statusForm.submit();
    });
</script>

</body>
</html>

