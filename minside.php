<?php
require "settings/init.php";
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


<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
    <img src="images/background1.webp" alt="background" class="position-absolute top-0 start-0 w-100 h-100"
         style="object-fit: cover;">
</div>

<!-- Første container til overskrift og tekst -->
<div class="container ">
    <div class="row">
        <div class="col-12 text-light">
            <p class="overskrift-stor">Min side</p>
        </div>
    </div>


    <div class="row w-100">
        <div class="col-8 col-sm-10 col-md-8 col-lg-6 col-xl-4 offset-2 offset-sm-1 offset-md-2 offset-lg-3 offset-xl-4 brødtekst-lille">
            <div class="mb-4 mt-5">
                <input type="text" class="form-control rounded-pill" id="Input" placeholder="Brugernavn">
            </div>

            <div class="mb-4">
                <input type="password" class="form-control rounded-pill" id="Password" placeholder="Adgangskode">
            </div>

            <div class="mb-5">
                <button class="btn btn-primærknap w-100 rounded-pill">Log på</button>
            </div>

            <p class="text-white text-center">Har du ikke en konto? <a href="#" class="link-underline text-white fw-medium">Opret dig her</a> </p>
        </div>
    </div>

</div>



<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
