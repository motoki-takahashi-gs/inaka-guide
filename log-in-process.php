<?php

include("./includes/functions.php");

$email = filter_input(INPUT_POST, "email");
$password = filter_input(INPUT_POST, "password");
$redirectTo = filter_input(INPUT_POST, "redirect-to");

$dbh = db_con();

$sql = "SELECT * FROM users WHERE email=:email";
$sth = $dbh->prepare($sql);
$sth->bindValue(':email', $email);
$row = $sth->execute();

if ($row == false) {
    sqlError($sth);
} else {

    $row = $sth->fetch();

    // page a user should be redirected to
    $redirectTo = ($redirectTo == 'account.php') || (!$redirectTo) ? 'account.php?id=' . $row["id"] : $redirectTo;

    // when password is correct
    if (password_verify($password, $row["password"])) {

        // give the user the session variables
        $_SESSION["sid"] = session_id();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["first_name"] = $row["first_name"];
        $_SESSION["last_name"] = $row["last_name"];

        // redirect to a page
        redirect($redirectTo);
    } else {

        // when password is incorrect
        redirect('log-in.php');
    }
    exit();
}
