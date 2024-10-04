<?php
/** @var PDO $db */
require "settings/init.php";
require "uploads.php"; // Inkluder billedeuploadhåndtering

// Hent alle brugere, så de kan vælges som gæster
$users = $db->sql("SELECT * FROM users");

// Hvis formularen er indsendt, tilføj eventet og inviterede gæster
if (!empty($_POST)) {

    // Håndter billedeupload via uploads.php
    $uploadedImage = uploadImage("evenImage");

    if ($uploadedImage !== false) {

        $db->sql("INSERT INTO events (evenName, evenDateTime, evenLocation, evenDescription, evenImage) 
                  VALUES (:evenName, :evenDateTime, :evenLocation, :evenDescription, :evenImage)", [
            ":evenName" => $_POST["evenName"],
            ":evenDateTime" => $_POST["evenDateTime"],
            ":evenLocation" => $_POST["evenLocation"],
            ":evenDescription" => $_POST["evenDescription"],
            ":evenImage" => $uploadedImage // Gemmer det uploadede billede
        ]);

        // Hent ID'et for det netop indsatte event
        $eventId = $db->lastInsertId();

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

        // Omdiriger efter succes
        header("Location: eventsoprettetafmig.php?success=1");
        exit();
    }
}
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
<body>

<!-- Baggrundsbillede med overlay -->
<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
    <img src="images/opretredigerimg.jpg" alt="background" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="filter: blur(0px);">

    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2); z-index: 1;"></div>
</div>


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
                <label for="evenName" class="form-label text-white">Navn på event</label>
                <input type="text" name="evenName" id="evenName"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>
            <div class="mb-4 col-12 col-md-6">
                <label for="evenDateTime" class="form-label text-white">Dato og tid</label>
                <input type="datetime-local" name="evenDateTime" id="evenDateTime"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>
            <div class="mb-4 col-12 col-md-6">
                <label for="evenLocation" class="form-label text-white">Lokation</label>
                <input type="text" name="evenLocation" id="evenLocation"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>


            <div class="mb-4 col-12 col-md-6">
                <label for="evenImage" class="form-label text-white">Indsæt billede her</label>
                <input type="file" name="evenImage" id="evenImage"
                       class="form-control rounded-pill p-2 brødtekst-knap ps-3" required>
            </div>


            <div class="mb-4 col-12 col-md-6">
                <label for="evenGuest" class="form-label text-white">Inviter gæster</label>

                <div class="form-control" style="height: auto; max-height: 80px; overflow-y: scroll; padding: 10px;">

                    <?php foreach ($users as $user): ?>
                        <div class="form-check">
                            <input class="form-check-input brødtekst-knap" type="checkbox" name="users[]" value="<?php echo $user->userId; ?>" id="<?php echo $user->userId; ?>">
                            <label class="form-check-label brødtekst-knap" for="<?php echo $user->userId; ?>">
                                <?php echo $user->userName; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>


            <div class="mb-4 col-12 col-md-6">
                <label for="evenDescription" class="form-label text-white">Beskrivelse af event</label>
                <textarea type="text" name="evenDescription" id="evenDescription" class="form-control p-2 brødtekst-knap ps-3" required></textarea>
            </div>


            <div class="col-12 mt-4 text-center">
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

        // Tjek om formularen er gyldig ved hjælp af validering
        if (opretEventForm.checkValidity()) {
            if (confirm("Dit event er nu oprettet. Se dine events")) {
                opretEventForm.submit();
            }
        } else {
            // Hvis formularen ikke er gyldig, vis validering
            opretEventForm.reportValidity();
        }
    });



</script>

</body>
</html>
