<?php

include("../includes/functions.php");

checkSid();

$pageName = "Register an event";

$dbh = db_con();

$sql = "SELECT * FROM categories ORDER BY name ASC";
$sth = $dbh->prepare($sql);
$status = $sth->execute();

if ($status == false) {
    sqlError($sth);
} else {
    $categories = "";
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $categories .= '<label class="container">';
        $categories .= $row["name"];
        $categories .= '<input type="checkbox" name="categories[]" value="' . $row["id"] . '">';
        $categories .= '<span class="checkmark"></span>';
        $categories .= '</label>';
    }
}

// create options for hours
$hours = '';
for ($i = 0; $i <= 23; $i++) {
    // leading zero
    $i = sprintf('%02d', $i);
    $hours .= '<option value="' . $i . '">' . $i . '</option>';
}

// create options for minutes
$minutes = '';
for ($i = 0; $i <= 59; $i++) {
    // leading zero
    $i = sprintf('%02d', $i);
    $minutes .= '<option value="' . $i . '">' . $i . '</option>';
}

// create options for prefectures
$prefectures = '<option disabled selected value>Prefecture</option>';
$prefecture_array = array(
    "Hokkaido",
    "Aomori",
    "Iwate",
    "Miyagi",
    "Akita",
    "Yamagata",
    "Fukushima",
    "Ibaraki",
    "Tochigi",
    "Gunma",
    "Saitama",
    "Chiba",
    "Tokyo",
    "Kanagawa",
    "Niigata",
    "Toyama",
    "Ishikawa",
    "Fukui",
    "Yamanashi",
    "Nagano",
    "Gifu",
    "Shizuoka",
    "Aichi",
    "Mie",
    "Shiga",
    "Kyoto",
    "Osaka",
    "Hyogo",
    "Nara",
    "Wakayama",
    "Tottori",
    "Shimane",
    "Okayama",
    "Hiroshima",
    "Yamaguchi",
    "Tokushima",
    "Kagawa",
    "Ehime",
    "Kochi",
    "Fukuoka",
    "Saga",
    "Nagasaki",
    "Kumamoto",
    "Oita",
    "Miyazaki",
    "Kagoshima",
    "Okinawa"
);
foreach ($prefecture_array as $prefecture) {
    $prefectures .= '<option value="' . $prefecture . '">' . $prefecture . '</option>';
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include("../includes/head.php"); ?>

<body class="register-event">

    <?php include('../includes/header.php'); ?>

    <main>
        <section>
            <div class="name"><?php echo "Hi " . $_SESSION["org_first_name"] . " " . $_SESSION["org_last_name"]; ?></div>
        </section>
        <section>
            <h1><?php echo $pageName; ?></h1>
            <form action="register-event-process.php" method="POST" enctype="multipart/form-data">
                <div class="title">
                    <input type="text" name="title" placeholder="Title" required>
                </div>
                <div class="summary">
                    <textarea name="summary" placeholder="Summary"></textarea>
                </div>
                <div class="details">
                    <textarea name="details" placeholder="Details" required></textarea>
                </div>
                <div class="images">
                    <div>
                        <input type="file" name="main_image" id="real_button" accept="image/*" hidden="hidden" required>
                        <button type="button" id="custom_button">Upload a main image</button>
                    </div>
                    <div class="main_image_thumbnail">
                        <img src="" id="main_image_thumbnail">
                    </div>
                </div>
                <div class="categories"><?php echo $categories; ?></div>

                <div class="calendar">
                    <?php include('../includes/calendar-header-body.php'); ?>
                    <div class="date-range">
                        <div class="from-date">
                            <span>From</span>
                            <span id="from-date"></span>
                        </div>
                        <div class="to-date">
                            <span>To</span>
                            <span id="to-date"></span>
                        </div>
                    </div>
                    <input type="hidden" name="start_date" id="start-date" required>
                    <input type="hidden" name="end_date" id="end-date" required>
                </div>

                <div class="time">
                    <div>
                        <div>Start at</div>
                        <div class="position-relative">
                            <select name="start_time_hours" required>
                                <?php echo $hours; ?>
                            </select>
                            <span class="down-arrow"></span>
                        </div>
                        <div>:</div>
                        <div class="position-relative">
                            <select name="start_time_minutes" required>
                                <?php echo $minutes; ?>
                            </select>
                            <span class="down-arrow"></span>
                        </div>
                    </div>
                    <div>
                        <div>End at</div>
                        <div class="position-relative">
                            <select name="end_time_hours" required>
                                <?php echo $hours; ?>
                            </select>
                            <span class="down-arrow"></span>
                        </div>
                        <div>:</div>
                        <div class="position-relative">
                            <select name="end_time_minutes" required>
                                <?php echo $minutes; ?>
                            </select>
                            <span class="down-arrow"></span>
                        </div>
                    </div>
                </div>
                <div class="address">
                    <textarea name="address" id="address" placeholder="Address" required></textarea>
                    <button type="button" id="confirm_address">Confirm address</button>
                    <input type="hidden" name="latitude" id="latitude" required>
                    <input type="hidden" name="longitude" id="longitude" required>
                    <input type="hidden" name="place_id" id="place_id" required>
                    <div id="map"></div>
                </div>
                <div class="prefecture position-relative">
                    <select name="prefecture" required>
                        <?php echo $prefectures; ?>
                    </select>
                    <span class="down-arrow"></span>
                </div>
                <div><button type="submit">Register</button></div>
            </form>
        </section>
    </main>

    <?php include('../includes/footer.php'); ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqiHGqyF1Jcmbn_QIQFHkTIrD5EmIxPE4&libraries=places"></script>
    <script src="../js/register-event.js"></script>

</body>

</html>