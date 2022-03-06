<?php

include("../includes/functions.php");

checkSid();

$pageName = "Admin page";

?>

<!DOCTYPE html>
<html lang="en">

<?php include("../includes/head.php"); ?>

<body class="admin-top">

    <?php include('../includes/header.php'); ?>

    <main>
        <section class="name">
            <div><?php echo "Hello " . $_SESSION["org_first_name"] . " " . $_SESSION["org_last_name"]; ?></div>
        </section>
        <section>
            <nav>
                <ul>
                    <li>
                        <a href="register-event.php">Register an event</a>
                    </li>
                    <li>
                        <a href="">Your events</a>
                    </li>
                    <li>
                        <a href="">Account information</a>
                    </li>
                    <li>
                        <a href="create-account.php">Create an account</a>
                    </li>
                    <li>
                        <a href="log-out.php">Log out</a>
                    </li>
                </ul>
            </nav>
        </section>
    </main>

</body>

</html>