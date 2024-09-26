<?php
/** @var PDO $db */
require "settings/init.php";

if (!empty($_POST)) {
    // Hent input fra formularen og indsæt det i databasen
    $db->sql("INSERT INTO events (evenName, evenDateTime, evenLocation, evenDescription, evenImage) 
              VALUES (:evenName, :evenDateTime, :evenLocation, :evenDescription, :evenImage)", [
        ":evenName" => $_POST["evenName"],
        ":evenDateTime" => $_POST["evenDateTime"],
        ":evenLocation" => $_POST["evenLocation"],
        ":evenDescription" => $_POST["evenDescription"],
        ":evenImage" => $_POST["evenImage"]
    ]);

    // Redirect tilbage til adminEvents.php
    header("Location: minside.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>EventBuddy</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<body class="bg-info">

<div class="container">
    <div class="row">
        <div class="py-5">
            <p class="overskrift-stor text-white">Opret nyt event</p>
            <a href="minside.php" class="text-decoration-underline tilbageknap brødtekst-knap">Tilbage</a>
        </div>
    </div>
</div>


<div class="container">
    <form method="post" action="opretnytevent.php">
        <div class="row">
            <!-- Første række med to felter -->
            <div class="mb-4 col-12 col-md-6">
                <label for="evenName" class="form-label">Navn på event</label>
                <input type="text" name="evenName" id="evenName"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>
            <div class="mb-4 col-12 col-md-6">
                <label for="evenDateTime" class="form-label">Dato og tid</label>
                <input type="datetime-local" name="evenDateTime" id="evenDateTime"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>

            <!-- Anden række med to felter -->
            <div class="mb-4 col-12 col-md-6">
                <label for="evenLocation" class="form-label">Lokation</label>
                <input type="text" name="evenLocation" id="evenLocation"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>
            <div class="mb-4 col-12 col-md-6">
                <label for="evenImage" class="form-label">Indsæt billede</label>
                <input type="file" name="evenImage" id="evenImage"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>

            <!-- Tredje række med to felter -->
            <div class="mb-4 col-12 col-md-6">
                <label for="evenGuest" class="form-label">Inviter gæster</label>
                <input type="text" name="evenGuest" id="evenGuest"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>
            <div class="mb-4 col-12 col-md-6">
                <label for="evenDescription" class="form-label">Beskrivelse af event</label>
                <input type="text" name="evenDescription" id="evenDescription"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>

            <!-- Submit-knap i bunden -->
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primærknap w-50 rounded-pill p-2 brødtekst-knap">Opret event</button>
            </div>
        </div>
    </form>
</div>


<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

