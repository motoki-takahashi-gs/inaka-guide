<?php

include("./includes/functions.php");

$first_name = filter_input(INPUT_POST, "first_name");
$last_name = filter_input(INPUT_POST, "last_name");
$email = filter_input(INPUT_POST, "email");
$password = filter_input(INPUT_POST, "password");
$password = password_hash($password, PASSWORD_DEFAULT);
$redirectTo = filter_input(INPUT_POST, "redirect-to");

$dbh = db_con();

$sql = "INSERT INTO users (first_name, last_name, email, password, created_at)
VALUES(:first_name, :last_name, :email, :password, sysdate())";
$sth = $dbh->prepare($sql);
$sth->bindValue(':first_name', $first_name, PDO::PARAM_STR);
$sth->bindValue(':last_name', $last_name, PDO::PARAM_STR);
$sth->bindValue(':email', $email, PDO::PARAM_STR);
$sth->bindValue(':password', $password, PDO::PARAM_STR);
$status = $sth->execute();

if ($status == false) {
    sqlError($sth);
} else {

    // get user id which has just been inserted
    $sql_2 = "SELECT id, first_name, last_name FROM users WHERE id = LAST_INSERT_ID()";
    $sth_2 = $dbh->prepare($sql_2);
    $status_2 = $sth_2->execute();

    if ($status_2 == false) {
        sqlError($sth_2);
    } else {

        $row = $sth_2->fetch();

        // page a user should be redirected to
        $redirectTo = ($redirectTo == 'account.php') || (!$redirectTo) ? 'account.php?id=' . $row["id"] : $redirectTo;

        // give the user the session variables
        $_SESSION["sid"] = session_id();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["first_name"] = $row["first_name"];
        $_SESSION["last_name"] = $row["last_name"];

        // redirect to a page
        redirect($redirectTo);
    }
}
