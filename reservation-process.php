<?php

include("./includes/functions.php");

checkSid();

// for reservations table
$user_id = $_SESSION['user_id'];
$event_id = filter_input(INPUT_POST, "event-id");
$date = filter_input(INPUT_POST, "date");
$display_date = filter_input(INPUT_POST, "display-date");
$number_of_guests = filter_input(INPUT_POST, "number-of-guests");
$total_amount = filter_input(INPUT_POST, "total-amount");

$dbh = db_con();

$sql = "INSERT INTO reservations (user_id, event_id, start_date, end_date, number_of_guests, total_amount, reserved_at)
VALUES(:user_id, :event_id, :start_date, :end_date, :number_of_guests, :total_amount, sysdate())";

$sth = $dbh->prepare($sql);

$sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$sth->bindValue(':event_id', $event_id, PDO::PARAM_INT);
$sth->bindValue(':start_date', $date, PDO::PARAM_STR);
$sth->bindValue(':end_date', $date, PDO::PARAM_STR);
$sth->bindValue(':number_of_guests', $number_of_guests, PDO::PARAM_INT);
$sth->bindValue(':total_amount', $total_amount, PDO::PARAM_INT);

$status = $sth->execute();

if ($status == false) {
    sqlError($sth);
} else {

    // for users table
    $gender = filter_input(INPUT_POST, "gender");
    $birth_month = filter_input(INPUT_POST, "birth_month");
    $birth_date = filter_input(INPUT_POST, "birth_date");
    $birth_year = filter_input(INPUT_POST, "birth_year");
    $home_country = filter_input(INPUT_POST, "home_country");

    // leading zero for month and date
    $birth_month = sprintf('%02d', $birth_month);
    $birth_date = sprintf('%02d', $birth_date);

    // birthday
    $birth_day = $birth_year . '-' . $birth_month . '-' . $birth_date;

    $sql_2 = "UPDATE users SET gender = :gender, birth_day = :birth_day, home_country = :home_country WHERE id = :user_id";
    $sth_2 = $dbh->prepare($sql_2);
    $sth_2->bindValue(':gender', $gender, PDO::PARAM_INT);
    $sth_2->bindValue(':birth_day', $birth_day, PDO::PARAM_STR);
    $sth_2->bindValue(':home_country', $home_country, PDO::PARAM_STR);
    $sth_2->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $status_2 = $sth_2->execute();

    if ($status_2 == false) {
        sqlError($sth_2);
    } else {

        $sql_3 = "SELECT first_name, last_name, email FROM users WHERE id = :user_id";
        $sth_3 = $dbh->prepare($sql_3);
        $sth_3->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $status_3 = $sth_3->execute();

        if ($status_3 == false) {
            sqlError($sth_3);
        } else {

            $row_3 = $sth_3->fetch();

            $sql_4 = "SELECT title, email FROM events
            INNER JOIN organizers ON events.organizer_id = organizers.id
            WHERE events.id = :event_id";

            $sth_4 = $dbh->prepare($sql_4);
            $sth_4->bindValue(':event_id', $event_id, PDO::PARAM_INT);
            $status_4 = $sth_4->execute();

            if ($status_4 == false) {
                sqlError($sth_4);
            } else {

                $row_4 = $sth_4->fetch();

                $to = $row_3['email'];
                $adminEmail = 'support@example.com';
                $subject = 'Reservation completed - INAKA GUIDE -';
                // 65 characters out of maximum 70 characters
                $message = 'Thank you for booking at INAKA GUIDE.\r\n';
                $message .= 'Our guide will get in touch with you soon.\r\n\r\n';
                $message .= 'Please check the details about this event.\r\n\r\n';
                $message .= 'Event name: ' . $row_4['title'] . '\r\n';
                $message .= 'Date: ' . $display_date . '\r\n';
                $message .= 'Number of guest(s): ' . $number_of_guests . '\r\n';
                $message .= 'Total amount (JPY): ¥ ' . number_format($total_amount) . '\r\n';
                $message = wordwrap($message, 70, '\r\n');

                // $headers = 'From: ' . $adminEmail . '\r\n' .
                //     'Reply-To: ' . $adminEmail . '\r\n' .
                //     'X-Mailer: PHP/' . phpversion();

                $headers = array(
                    'From' => $adminEmail,
                    'Cc' => $adminEmail,
                    // 'Cc' => $row_4['email'],
                    'Reply-To' => $adminEmail,
                    'X-Mailer' => 'PHP/' . phpversion()
                );

                mail($to, $subject, $message);

                redirect('reservation-success.php');
            }
        }
    }
}
