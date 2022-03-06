<?php

include("../includes/functions.php");

$email = filter_input(INPUT_POST, "email");
$password = filter_input(INPUT_POST, "password");

$dbh = db_con();

$sql = "SELECT * FROM organizers WHERE email=:email";
$sth = $dbh->prepare($sql);
$sth->bindValue(':email', $email);
$row = $sth->execute();

if ($row == false) {
    sqlError($sth);
} else {

    $organizer = $sth->fetch();

    if (password_verify($password, $organizer["password"])) {
        $_SESSION["sid"] = session_id();
        $_SESSION["organizer_id"] = $organizer["id"];
        $_SESSION["org_first_name"] = $organizer["first_name"];
        $_SESSION["org_last_name"] = $organizer["last_name"];
        redirect('index.php');
    } else {
        redirect('log-out.php');
    }
    exit();
}
