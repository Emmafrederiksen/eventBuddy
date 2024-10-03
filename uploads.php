<?php
function uploadImage($fileInputName, $targetDir = "userimages/") {
// Tjek om filen blev uploadet korrekt
    if (empty($_FILES[$fileInputName]['tmp_name'])) {
        echo "Ingen fil uploadet.";
        return false;
    }

    $target_file = $targetDir . basename($_FILES[$fileInputName]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Tjek om filen er et billede
    $check = getimagesize($_FILES[$fileInputName]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        return false;
    }


    // Begræns filstørrelse
    if ($_FILES[$fileInputName]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        return false;
    }

    // Tillad visse filformater
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
        echo "Sorry, only JPG, JPEG, PNG & WEBP files are allowed.";
        return false;
    }

    // Upload filen
    if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $target_file)) {
        return basename($_FILES[$fileInputName]["name"]);
    } else {
        echo "Sorry, there was an error uploading your file.";
        return false;
    }
}
?>
