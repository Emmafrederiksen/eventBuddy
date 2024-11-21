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

<!-- Baggrundsbillede -->
<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1;">
    <img src="images/background1.webp" alt="background" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
</div>


<!-- Overskrift og tekst -->
<div class="container d-flex flex-column justify-content-center align-items-center vh-100">
    <div class="row">
        <div class="col-12 text-center text-light fs-1">
            <p class="overskrift-stor">Velkommen til din event buddy</p>
            <span class="brødtekst-mellem">Skal du planlægge</span>
            <span id="featureText" class="overskrift-mellem"></span>
            <span class="inputCursor"></span>
        </div>
    </div>

<!-- Log på -->
    <div class="row w-100">
        <div class="col-8 col-sm-10 col-md-8 col-lg-6 col-xl-4 offset-2 offset-sm-1 offset-md-2 offset-lg-3 offset-xl-4">
            <form action="minside.php" method="post">
                <div class="mb-4 mt-5">
                    <input type="text" class="form-control rounded-pill p-2 brødtekst-knap ps-3" id="Input" name="username" placeholder="Brugernavn" required>
                </div>

                <div class="mb-4">
                    <input type="password" class="form-control rounded-pill p-2 brødtekst-knap ps-3" id="Password" name="password" placeholder="Adgangskode" required>
                </div>

                <div class="mb-5">
                    <button type="submit" class="btn btn-primærknap w-100 rounded-pill p-2 brødtekst-knap">Log på</button>
                </div>
            </form>

            <p class="text-white text-center">Har du ikke en konto? <a href="#" class="text-white fw-medium text-decoration-underline brødtekst-lille">Opret dig her</a></p>
        </div>
    </div>

</div>


<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script>

    // Typewriter tekst

    const featureText = document.querySelector("#featureText");
    const carouselList = [
        {text: "sommerfest?", color: "#C99D45"},
        {text: "fødselsdag?", color: "#C99D45"},
        {text: "julefrokost?", color: "#C99D45"},
        {text: "bryllup?", color: "#C99D45"},
    ];

    typewriter();

    async function typewriter() {
        let i = 0
        while (true) {
            updateColor(carouselList[i].color);
            await typeSentence(carouselList[i].text);
            await waitFor(1500);
            await deleteSentence();
            i++;
            if (i >= carouselList.length) {
                i = 0;
            }
        }
    }

    async function typeSentence(sentence) {
        const letters = sentence.split("");
        for (let i = 0; i < letters.length; i++) {
            await waitFor(100);
            featureText.textContent += letters[i];
        }
    }

    async function deleteSentence() {
        const letters = featureText.textContent.split("");
        while (letters.length > 0) {
            await waitFor(100);
            letters.pop();
            featureText.textContent = letters.join("");
        }
    }

    function updateColor(color) {
        featureText.style.color = color;
    }

    function waitFor(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }


</script>
</body>
</html>
