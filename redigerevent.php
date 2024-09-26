<?php
/** @var PDO $db */
require "settings/init.php";

// Hvis formularen er indsendt, opdater eventets data
if (!empty($_POST['evenId']) && !empty($_POST['data'])) {
    $data = $_POST['data'];

    $db->sql("UPDATE events SET 
        evenName = :evenName, 
        evenDateTime = :evenDateTime, 
        evenLocation = :evenLocation, 
        evenDescription = :evenDescription, 
        evenImage = :evenImage 
        WHERE evenId = :evenId", [
        ":evenName" => $data["evenName"],
        ":evenDateTime" => $data["evenDateTime"],
        ":evenLocation" => $data["evenLocation"],
        ":evenDescription" => $data["evenDescription"],
        ":evenImage" => $data["evenImage"],
        ":evenId" => $_POST["evenId"]
    ]);

    // Omdiriger til en anden side efter opdateringen
    header("Location: redigerevent.php?succes=1&evenId=" . $_POST['evenId']);
    exit;
}

// Hvis Id mangler i URL'en, omdiriger til en anden side
if (empty($_GET["evenId"])) {
    header("Location: eventsoprettetafmig.php");
    exit;
}

$evenId = $_GET["evenId"];

// Hent eventets oplysninger fra databasen
$event = $db->sql("SELECT * FROM events WHERE evenId = :evenId", [":evenId" => $evenId]);
$event = $event[0]; // Vælg den første (og eneste) række fra resultatet

if (!empty($_GET['delete']) && $_GET['delete'] == 1 && !empty($_GET['evenId'])) {
    $evenId = $_GET['evenId'];
    $db->sql("DELETE FROM events WHERE evenId = :evenId", [":evenId" => $evenId]);

    // Omdiriger efter sletning
    header("Location: eventsoprettetafmig.php?deleted=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <title>Rediger eventet</title>
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
            <p class="overskrift-stor text-white">Rediger <?php echo $event->evenName; ?></p>
            <a href="minside.php" class="text-decoration-underline tilbageknap brødtekst-knap">Tilbage</a>
        </div>
    </div>
</div>

<?php
if (!empty($_GET["succes"]) && $_GET['succes'] == 1) {
    echo "<p class='brødtekst-lille'>Eventet er opdateret</p>";
}
?>

<div class="container">
    <form method="post" action="redigerevent.php">
        <div class="row">
            <!-- Første række med to felter -->
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenName" class="form-label">Navn på event</label>
                <input type="text" name="data[evenName]" id="evenName"
                       value="<?php echo $event->evenName; ?>"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenDateTime" class="form-label">Dato og tid</label>
                <input type="datetime-local" name="data[evenDateTime]" id="evenDateTime"
                       value="<?php echo date('Y-m-d\TH:i', strtotime($event->evenDateTime)); ?>"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>

            <!-- Anden række med to felter -->
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenLocation" class="form-label">Lokation</label>
                <input type="text" name="data[evenLocation]" id="evenLocation"
                       value="<?php echo $event->evenLocation; ?>"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenImage" class="form-label">Indsæt billede</label>
                <input type="file" name="data[evenImage]" id="evenImage"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>

            <!-- Tredje række med to felter -->
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenGuest" class="form-label">Inviter gæster</label>
                <input type="text" name="data[evenGuest]" id="evenGuest"
                       value="<?php echo $event->evenGuest; ?>"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenDescription" class="form-label">Beskrivelse af event</label>
                <input type="text" name="data[evenDescription]" id="evenDescription"
                       value="<?php echo $event->evenDescription; ?>"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>

            <!-- Submit-knap i bunden -->
            <div class="col-12 text-center">
                <input type="hidden" name="evenId" value="<?php echo $evenId; ?>">
                <button type="submit" class="btn btn-primærknap knap w-50 rounded-pill p-2 brødtekst-knap">Opdater eventet</button>
            </div>

            <!-- Link til sletning af event -->
            <div class="col-12 text-center mt-4">
                <p class="text-black">Ønsker du at slette dit event?
                    <a href="redigerevent.php?delete=1&evenId=<?php echo $event->evenId; ?>" class="deleteLink text-white fw-medium text-decoration-underline brødtekst-lille">Klik her</a></p>
            </div>
        </div>
    </form>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const deleteLink = document.querySelectorAll(".deleteLink");

    deleteLink.forEach(function (link) {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            if(confirm("Er du sikker på, at du vil slette dette event?")) {
                window.location.href = this.href;
            }
        });
    });

</script>
</body>
</html>
