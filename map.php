<?php

include("./includes/functions.php");

$pageName = 'Maps';

$dbh = db_con();

// to show activities list in search options modal
$activities = show_activities_list($dbh);

?>

<!DOCTYPE html>
<html lang="en">

<?php include('./includes/head.php'); ?>

<body class="map">

    <?php include('./includes/search-options.php') ?>

    <?php include('./includes/log-in-window.php'); ?>

    <?php include('./includes/sign-up-window.php'); ?>

    <?php include('./includes/header.php') ?>

    <main class="map">
        <div id="map"></div>
    </main>

    <?php include('./includes/footer.php') ?>

    <script src="js/map.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqiHGqyF1Jcmbn_QIQFHkTIrD5EmIxPE4&callback=initMap" async defer></script>

    <script src="./js/search-options.js"></script>
    <script src="./js/log-in-sign-up-modals.js"></script>
</body>

</html>