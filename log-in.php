<?php

include("./includes/functions.php");

$pageName = 'Log in';

$dbh = db_con();

// to show activities list in search options modal
$activities = show_activities_list($dbh);

?>

<!DOCTYPE html>
<html lang="en">

<?php include("./includes/head.php"); ?>

<body class="actual">

    <?php include('./includes/search-options.php') ?>

    <?php include('./includes/header.php'); ?>

    <main>
        <?php include('./includes/log-in-window.php'); ?>
    </main>

    <?php include('./includes/footer.php'); ?>

    <script src="./js/search-options.js"></script>
    <script src="./js/log-in.js"></script>

</body>

</html>