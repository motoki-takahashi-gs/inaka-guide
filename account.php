<?php

include("./includes/functions.php");

checkSid();

$pageName = "My account";

$dbh = db_con();

$user_id = filter_input(INPUT_GET, "id");

$sql = "SELECT first_name, last_name, image
FROM users
WHERE id = :user_id";

$sth = $dbh->prepare($sql);
$sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$status = $sth->execute();

if ($status == false) {
    sqlError($sth);
} else {
    $row = $sth->fetch();

    // to show activities list in search options modal
    $activities = show_activities_list($dbh);
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include('./includes/head.php'); ?>

<body class="account">

    <?php include('./includes/search-options.php') ?>

    <?php include('./includes/header.php') ?>

    <main>
        <div class="name-image">
            <div><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></div>
            <div><?php
                    if (isset($row['image'])) {
                        echo '<img src="' . $row['image'] . '">';
                    } else {
                        echo '<div class="no-photo">' . strtoupper(substr($row['first_name'], 0, 1)) . '</div>';
                    }
                    ?></div>
        </div>
        <nav>
            <div>
                <a href="">Personal information</a>
            </div>
            <div>
                <a href="">Reservation history</a>
            </div>
            <div>
                <a href="">Favorites</a>
            </div>
            <div>
                <a href="">Messages</a>
            </div>
            <div>
                <a href="./log-out.php">Log out</a>
            </div>
        </nav>
    </main>

    <?php include('./includes/footer.php') ?>

    <script src="./js/search-options.js"></script>

</body>

</html>