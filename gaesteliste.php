<?php
/** @var PDO $db */
require "settings/init.php";

if (!empty($_GET['evenId'])) {
    $evenId = $_GET['evenId'];
    // Hent alle gæster til eventet og deres status
    $guests = $db->sql("SELECT userName, evuseStatus, evuseOwner
                        FROM users 
                        JOIN event_user_con ON userId = evuseUserId 
                        WHERE evuseEvenId = :evenId
                        ORDER BY evuseOwner DESC, 
                        evuseStatus IS NULL DESC, 
                        evuseStatus DESC", [":evenId" => $evenId]);

    ?>

    <!-- Modalvindue -->


    <div class="modal-header">
        <p class="modal-title overskrift-mellem">Gæsteliste</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <ul class="list-group brødtekst-lille">
            <?php foreach ($guests as $guest): ?>
                <li class="list-group-item d-flex justify-content-between">
                    <?php echo $guest->userName; ?>
                    <span class="badge bg-dark-subtle text-black-50 w-25 align-content-center">
                        <?php
                        if ($guest -> evuseOwner === 1) {
                            echo "Ejer";
                        } else if ($guest->evuseStatus === null) {
                            echo "Inviteret";
                        } elseif ($guest->evuseStatus == 1) {
                            echo "Deltager";
                        } else {
                            echo "Deltager ikke";
                        }
                        ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="modal-footer">
        <p class=""></p>
        <button class="btn bg-primærknap brødtekst-lille rounded-pill w-25" data-bs-dismiss="modal">Luk</button>
    </div>
    <?php
}
?>
