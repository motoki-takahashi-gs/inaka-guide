<?php

include("./includes/functions.php");

checkSid();

$pageName = 'Reservation completed';

$dbh = db_con();

// to show activities list in search options modal
$activities = show_activities_list($dbh);

?>

<!DOCTYPE html>
<html lang="en">

<?php include('./includes/head.php'); ?>

<body class="reservation-success">

    <?php include('./includes/search-options.php') ?>

    <?php include('./includes/header.php') ?>

    <main>
        <h1>Thank you for making a reservation!</h1>

        <p>Our guide will get in touch with you soon.</p>

        <p>The details will be sent to your E-mail address,
            but you can also check your reservation history here.</p>

        <p>Have a nice trip!</p>
    </main>

    <?php include('./includes/footer.php') ?>

    <script src="./js/search-options.js"></script>

</body>

</html>