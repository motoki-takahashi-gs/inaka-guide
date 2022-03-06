<?php

include("../includes/functions.php");

$pageName = 'Log in';

?>

<!DOCTYPE html>
<html lang="en">

<?php include("../includes/head.php"); ?>

<body class="actual">

    <?php include('../includes/header.php'); ?>

    <main>
        <section class="log-in">
            <h1>Log in</h1>
            <form action="log-in-process.php" method="POST">
                <div><input type="email" name="email" placeholder="Email address" required></div>
                <div><input type="password" name="password" placeholder="Password" required></div>
                <div><button type="submit">Log in</button></div>
            </form>
        </section>
    </main>

</body>

</html>