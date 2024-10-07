<?php
/** @var PDO $db */
require "settings/init.php";
require "uploads.php"; // Inkluder billedehåndteringen fra uploads.php

// Hent eventets oplysninger fra databasen
$evenId = $_GET["evenId"];
$event = $db->sql("SELECT * FROM events WHERE evenId = :evenId", [":evenId" => $evenId]);
$event = $event[0];

// Hent alle brugere, så de kan vælges som gæster
$users = $db->sql("SELECT * FROM users");

// Hent de allerede inviterede gæster
$invitedGuests = $db->sql("SELECT evuseUserId FROM event_user_con WHERE evuseEvenId = :evenId", [":evenId" => $evenId]);

// Hvis formularen er indsendt, opdater eventets data
if (!empty($_POST['evenId']) && !empty($_POST['data'])) {
    $data = $_POST['data'];

    // Billede håndtering/upload
    if (!empty($_FILES['evenImage']['name'])) {
        $uploadedImage = uploadImage('evenImage', 'userimages/');
        if ($uploadedImage) {
            $data['evenImage'] = $uploadedImage; // Gem det uploadede billednavn
        } else {
            echo "Fejl ved upload af billede.";
            exit;
        }
    } else {
        // Behold det gamle billede
        $eventImage = $db->sql("SELECT evenImage FROM events WHERE evenId = :evenId", [":evenId" => $_POST["evenId"]]);
        if ($eventImage && isset($eventImage[0]->evenImage)) {
            $data['evenImage'] = $eventImage[0]->evenImage; // Gem det eksisterende billede
        } else {
            echo "Fejl med at hente eksisterende billede fra databasen.";
            exit;
        }
    }

    // Opdater eventdata i databasen
        $result = $db->sql("UPDATE events SET 
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


    // Håndter tilføjelse eller fjernelse af gæster
    if (!empty($_POST["guests"])) {
        // Hent eksisterende gæster for eventet
        $existingGuests = $db->sql("SELECT evuseUserId FROM event_user_con WHERE evuseEvenId = :evenId", [
            ":evenId" => $_POST["evenId"]
        ]);

        $existingGuestId = array_column($existingGuests, 'evuseUserId'); // Konverter til array med userId'er

        // Tilføj nye gæster
        foreach ($_POST["guests"] as $guest) {
            if (!in_array($guest, $existingGuestId)) {
                $db->sql("INSERT INTO event_user_con (evuseEvenId, evuseUserId, evuseOwner) 
                      VALUES (:evuseEvenId, :evuseUserId, 0)", [
                    ":evuseEvenId" => $_POST["evenId"],
                    ":evuseUserId" => $guest
                ]);
            }
        }

        // Fjern gæster, der ikke længere er valgt
        foreach ($existingGuestId as $guestId) {
            if (!in_array($guestId, $_POST["guests"])) {
                $db->sql("DELETE FROM event_user_con WHERE evuseEvenId = :evenId AND evuseUserId = :userId", [
                    ":evenId" => $_POST["evenId"],
                    ":userId" => $guestId
                ]);
            }
        }
    }

    // Omdiriger efter succes
    header("Location: eventsoprettetafmig.php?success=1");
    exit();
}

// Slet event
if (!empty($_GET['delete']) && $_GET['delete'] == 1 && !empty($_GET['evenId'])) {
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
<body>

<!-- Baggrundsbillede med overlay -->
<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
    <img src="images/opretredigerimg.jpg" alt="background" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="filter: blur(0px);">

    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.2); z-index: 1;"></div>
</div>


<div class="container">
    <div class="row">
        <div class="py-5">
            <p class="overskrift-stor text-white">Rediger <?php echo $event->evenName; ?></p>
            <a href="eventsoprettetafmig.php" class="text-decoration-underline tilbageknap brødtekst-knap">Tilbage</a>
        </div>
    </div>
</div>


<div class="container">
    <form method="post" action="redigerevent.php" enctype="multipart/form-data" id="redigerEventForm">
        <div class="row">
            <!-- Første række med to felter -->
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenName" class="form-label text-white">Navn på event</label>
                <input type="text" name="data[evenName]" id="evenName"
                       value="<?php echo $event->evenName; ?>"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenDateTime" class="form-label text-white">Dato og tid</label>
                <input type="datetime-local" name="data[evenDateTime]" id="evenDateTime"
                       value="<?php echo date('Y-m-d\TH:i', strtotime($event->evenDateTime)); ?>"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>

            <!-- Anden række med to felter -->
            <div class="mb-4 col-12 col-lg-6">
                <label for="evenLocation" class="form-label text-white">Lokation</label>
                <input type="text" name="data[evenLocation]" id="evenLocation"
                       value="<?php echo $event->evenLocation; ?>"
                       class="form-control rounded-pill p-2 brødtekst-knap" required>
            </div>

            <div class="mb-4 col-12 col-lg-6">
                <label for="evenImage" class="form-label text-white">Indsæt billede</label>

                <!-- Filinput til nyt billede -->
                <input type="file" name="evenImage" id="evenImage" class="form-control rounded-pill p-2 brødtekst-knap" aria-label="Indsæt billede">

                <!-- Skjult element til at vise det eksisterende billede -->
                <small id="existingFile" class="text-white">
                    <?php
                    if (!empty($event->evenImage)) {
                        echo 'Nuværende billede: ' . $event->evenImage;
                    } else {
                        echo 'Der er ikke valgt nogen fil';
                    }
                    ?>
                </small>
            </div>


            <div class="mb-4 col-12 col-lg-6">
                <label for="evenGuest" class="form-label text-white">Inviter gæster</label>

                <div class="form-control" style="height: auto; max-height: 80px; overflow-y: scroll; padding: 10px;">
                    <?php foreach ($users as $user): ?>
                        <div class="form-check">
                            <input class="form-check-input brødtekst-knap" type="checkbox" name="guests[]" value="<?php echo $user->userId; ?>"
                                   id="guest_<?php echo $user->userId; ?>"
                                <?php echo in_array($user->userId, array_column($invitedGuests, 'evuseUserId')) ? 'checked' : ''; ?>>
                            <label class="form-check-label brødtekst-knap" for="guest_<?php echo $user->userId; ?>">
                                <?php echo $user->userName; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>



            <div class="mb-4 col-12 col-lg-6" >
                <label for="evenDescription" class="form-label text-white" >Beskrivelse af event</label>
                <textarea name="data[evenDescription]" id="evenDescription" class="form-control p-2 brødtekst-knap" required><?php echo $event->evenDescription; ?></textarea>
            </div>


            <!-- Submit-knap i bunden -->
            <div class="col-12 text-center mt-4">
                <input type="hidden" name="evenId" value="<?php echo $event->evenId; ?>">
                <button type="submit" class="btn btn-primærknap knap w-50 rounded-pill p-2 brødtekst-knap" id="redigerEvent">Opdater eventet</button>
            </div>

            <!-- Link til sletning af event -->
            <div class="col-12 text-center mt-4">
                <p class="text-white">Ønsker du at slette dit event?
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


    const redigerEvent = document.getElementById("redigerEvent");
    const redigerEventForm = document.getElementById("redigerEventForm");

    redigerEvent.addEventListener("click", function(e) {
        e.preventDefault();

        if (confirm("Dit event er nu opdateret. Se dine events?")) {
            redigerEventForm.submit();
        }
    });


</script>
</body>
</html>