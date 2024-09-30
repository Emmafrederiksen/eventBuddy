<?php
function uploadImage($fileInputName, $targetDir = "userimages/") {
    $target_file = $targetDir . basename($_FILES[$fileInputName]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Tjek om filen er et billede
    $check = getimagesize($_FILES[$fileInputName]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }


    // Begræns filstørrelse
    if ($_FILES[$fileInputName]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Tillad visse filformater
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
        echo "Sorry, only JPG, JPEG, PNG & WEBP files are allowed.";
        $uploadOk = 0;
    }

    // Upload filen
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $target_file)) {
            return basename($_FILES[$fileInputName]["name"]); // Returner filnavnet ved succes
        } else {
            echo "Sorry, there was an error uploading your file. ";
            return false; // Fejl ved upload
        }
    } else {
        echo "File was not uploaded due to an error. ";
        return false;
    }
}
