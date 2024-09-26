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


<body>

<div class="container">
    <h1>Tilføj nyt event</h1>
    <form method="post" action="opretnytevent..php">
        <div class="mb-3 col-12 col-md-6">
            <label for="bookTitle" class="form-label">Navn på event</label>
            <input type="text" name="evenName" id="evenName" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="bookYear" class="form-label">Dato og tid</label>
            <input type="number" name="evenDateTime" id="evenDateTime" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="bookGenre" class="form-label">Lokation</label>
            <input type="text" name="evenLocation" id="evenLocation" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="bookGenre" class="form-label">Indsæt billede</label>
            <input type="text" name="evenImage" id="evenImage" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="bookGenre" class="form-label">Inviter gæster</label>
            <input type="text" name="#" id="#" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="bookGenre" class="form-label">Beskrivelse af event</label>
            <input type="text" name="evenDescription" id="evenDescription" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Opret event</button>
    </form>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

