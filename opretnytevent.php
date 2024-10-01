<?php
/** @var PDO $db */
require "settings/init.php";
require "uploads.php"; // Inkluder billedeuploadhåndtering

// Hent evenId fra URL'en
$evenId = $_GET["evenId"];  // Sørg for at 'evenId' findes i URL'en


// Hvis formularen er indsendt, tilføj eventet og inviterede gæster
if (!empty($_POST)) {
    // Håndter billedeupload via uploads.php
    $uploadedImage = uploadImage("evenImage");

    if ($uploadedImage !== false) {
        // Indsæt eventets data
        $db->sql("INSERT INTO events (evenName, evenDateTime, evenLocation, evenDescription, evenImage) 
                  VALUES (:evenName, :evenDateTime, :evenLocation, :evenDescription, :evenImage)", [
            ":evenName" => $_POST["evenName"],
            ":evenDateTime" => $_POST["evenDateTime"],
            ":evenLocation" => $_POST["evenLocation"],
            ":evenDescription" => $_POST["evenDescription"],
            ":evenImage" => $uploadedImage // Gemmer det uploadede billede
        ]);

        // Hent det nyoprettede event baseret på navn og tidspunkt
        $event = $db->sql("SELECT evenId FROM events WHERE evenName = :evenName AND evenDateTime = :evenDateTime", [
            ":evenName" => $_POST["evenName"],
            ":evenDateTime" => $_POST["evenDateTime"]
        ]);

        // Hvis eventet blev fundet, tilføj gæster
        if (!empty($event)) {
            $eventId = $event[0]->evenId;

            // Tilføj de valgte gæster til eventet
            if (!empty($_POST["users"])) {
                foreach ($_POST["users"] as $user) {
                    $db->sql("INSERT INTO event_user_con (evuseEvenId, evuseUserId, evuseOwner) 
                              VALUES (:evuseEvenId, :evuseUserId, 0)", [
                        ":evuseEvenId" => $eventId,
                        ":evuseUserId" => $user
                    ]);
                }
            }
        }

        // Omdiriger efter succes
        header("Location: eventsoprettetafmig.php?success=1");
        exit();
    } else {
        echo "Error uploading image.";
    }
}

// Hent alle brugere, så de kan vælges som gæster
$users = $db->sql("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Opret nyt event</title>

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
    <form method="post" action="opretnytevent.php" enctype="multipart/form-data" id="opretEventForm">
        <div class="row">
            <!-- Eventdetaljer -->
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
            <div class="mb-4 col-12 col-md-6">
                <label for="evenLocation" class="form-label">Lokation</label>
                <input type="text" name="evenLocation" id="evenLocation"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>

            <!-- Indsæt billede -->
            <div class="mb-4 col-12 col-md-6">
                <label for="evenImage" class="form-label">Indsæt billede her</label>
                <input type="file" name="evenImage" id="evenImage"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>

            <div class="mb-4 col-12 col-md-6">
                <label class="form-label">Inviter gæster</label>

                <!-- En container med scroll og fast højde -->
                <div class="form-control" style="height: auto; max-height: 80px; overflow-y: scroll; padding: 10px;">
                    <!-- Checkboxes til at vælge gæster -->
                    <?php foreach ($users as $user): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="users[]" value="<?php echo $user->userId; ?>" id="users_<?php echo $user->userId; ?>">
                            <label class="form-check-label" for="users_<?php echo $user->userId; ?>">
                                <?php echo $user->userName; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Eventbeskrivelse -->
            <div class="mb-4 col-12 col-md-6">
                <label for="evenDescription" class="form-label">Beskrivelse af event</label>
                <input type="text" name="evenDescription" id="evenDescription"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>

            <!-- Submit-knap -->
            <div class="col-12 text-center">
                <input type="hidden" name="evenId" value="<?php echo $evenId; ?>">
                <button type="submit" class="btn btn-primærknap w-50 rounded-pill p-2 brødtekst-knap" id="opretEvent">Opret event</button>
            </div>
        </div>
    </form>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<<script>


    const opretEvent = document.getElementById("opretEvent");
    const opretEventForm = document.getElementById("opretEventForm");

    opretEvent.addEventListener("click", function(e) {
        e.preventDefault();

        if (confirm("Dit event er nu oprettet. Se dine events")) {
            opretEventForm.submit();
        }
    });


</script>

</body>
</html>
