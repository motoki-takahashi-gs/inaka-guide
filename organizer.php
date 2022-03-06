<?php

include("./includes/functions.php");

$pageName = "Organizer's profile";

$dbh = db_con();

$organizer_id = filter_input(INPUT_GET, "id");

$sql = "SELECT first_name, last_name, about, image
FROM organizers
WHERE id = :organizer_id";

$sth = $dbh->prepare($sql);
$sth->bindValue(':organizer_id', $organizer_id, PDO::PARAM_INT);
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

<body class="organizer-profile">

    <?php include('./includes/search-options.php') ?>

    <?php include('./includes/log-in-window.php'); ?>

    <?php include('./includes/sign-up-window.php'); ?>

    <?php include('./includes/header.php') ?>

    <main>
        <div class="name-image">
            <div><?php echo $row['first_name']; ?></div>
            <div><img src="<?php echo $row['image']; ?>"></div>
        </div>
        <div class="about">
            <h2>About</h2>
            <div><?php echo $row['about']; ?></div>
        </div>
    </main>

    <?php include('./includes/footer.php') ?>

    <script src="./js/search-options.js"></script>
    <script src="./js/log-in-sign-up-modals.js"></script>

</body>

</html>