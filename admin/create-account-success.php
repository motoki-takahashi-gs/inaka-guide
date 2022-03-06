<?php

include("../includes/functions.php");

checkSid();

$pageName = 'Account created';

?>

<!DOCTYPE html>
<html lang="en">

<?php include("../includes/head.php"); ?>

<body>

    <?php include('../includes/header.php'); ?>

    <main>
        <section>
            <p>Your account has been created.<br>Please login at<a href="log-in.php">Login page</a>.</p>
        </section>
    </main>

    <?php include('../includes/footer.php'); ?>

</body>

</html>