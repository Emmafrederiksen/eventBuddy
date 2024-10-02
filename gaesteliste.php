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

    // HTML til at vise i modalet
    ?>
    <div class="modal-header">
        <h5 class="modal-title">Gæsteliste</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <ul class="list-group">
            <?php foreach ($guests as $guest): ?>
                <li class="list-group-item">
                    <?php echo $guest->userName; ?>
                    <span class="badge bg-secondary">
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
    <?php
}
?>
