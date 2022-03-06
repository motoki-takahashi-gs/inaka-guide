<?php

include("./includes/functions.php");

$pageName = 'Event details';

$dbh = db_con();

$event_id = filter_input(INPUT_GET, "id");

$sql = "SELECT title, summary, details, main_image, start_date, end_date, start_time,
end_time, address, place_id, organizer_id, price,
organizers.first_name, organizers.last_name, organizers.image AS organizer_image
FROM events
INNER JOIN organizers ON events.organizer_id = organizers.id
WHERE events.id = :event_id AND is_deleted = 0";

$sth = $dbh->prepare($sql);
$sth->bindValue(':event_id', $event_id, PDO::PARAM_INT);
$status = $sth->execute();

if ($status == false) {
    sqlError($sth);
} else {

    $row = $sth->fetch();

    if (!isset($row['title'])) {
        redirect('./index.php');
    }

    $sql_2 = "SELECT category_id, name FROM event_category
    INNER JOIN categories ON event_category.category_id = categories.id
    WHERE event_id = :event_id
    ORDER BY name ASC";

    $sth_2 = $dbh->prepare($sql_2);
    $sth_2->bindValue(':event_id', $event_id, PDO::PARAM_INT);
    $status_2 = $sth_2->execute();

    if ($status_2 == false) {
        sqlError($sth_2);
    } else {

        $category = "";
        while ($row_2 = $sth_2->fetch(PDO::FETCH_ASSOC)) {
            $category .= '<a href="./index.php?activities=' . $row_2['category_id'] . '">';
            $category .= '<button>' . $row_2['name'] . '</button>';
            $category .= '</a>';
        }

        // to show activities list in search options modal
        $activities = show_activities_list($dbh);

        // change id of Next button depending on the login status
        $reviewOrAccount = isset($_SESSION['sid']) && isset($_SESSION["user_id"]) ? 'go-to-review' : 'link-to-account';

        $sql_3 = "SELECT id, name FROM genders";
        $sth_3 = $dbh->prepare($sql_3);
        $status_3 = $sth_3->execute();

        if ($status_3 == false) {
            sqlError($sth_3);
        } else {

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            } else {
                $user_id = "";
            }

            $sql_4 = "SELECT gender, birth_day, home_country FROM users WHERE id = :user_id";
            $sth_4 = $dbh->prepare($sql_4);
            $sth_4->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $status_4 = $sth_4->execute();

            if ($status_4 == false) {
                sqlError($sth_4);
            } else {

                $row_4 = $sth_4->fetch();

                // create options for gender
                $gender = "<option disabled selected value>Gender</option>";
                while ($row_3 = $sth_3->fetch(PDO::FETCH_ASSOC)) {
                    if ($row_3['id'] == $row_4['gender']) {
                        $registeredGender = ' selected';
                    } else {
                        $registeredGender = '';
                    }
                    $gender .= '<option value="' . $row_3['id'] . '"' . $registeredGender . '>' . $row_3['name'] . '</option>';
                }

                // options for month of birth
                $months = [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ];
                $birth_month = '<option disabled selected value>Month</option>';
                $month_num = 1;
                foreach ($months as $month) {
                    if (isset($row_4['birth_day']) && $month_num == (int) explode('-', $row_4['birth_day'])[1]) {
                        $registeredMonth = ' selected';
                    } else {
                        $registeredMonth = '';
                    }
                    $birth_month .= '<option value="' . $month_num . '"' . $registeredMonth . '>' . $month . '</option>';
                    $month_num++;
                }

                // options for date of birth
                $birth_date = '<option disabled selected value>Date</option>';
                for ($i = 1; $i <= 31; $i++) {
                    if (isset($row_4['birth_day']) && $i == (int) explode('-', $row_4['birth_day'])[2]) {
                        $registeredDate = ' selected';
                    } else {
                        $registeredDate = '';
                    }
                    $birth_date .= '<option value="' . $i . '"' . $registeredDate . '>' . $i . '</option>';
                }

                // options for year of birth
                $currentYear = date("Y");
                $oldestYear = $currentYear - 120;
                $birth_year = '<option disabled selected value>Year</option>';
                for ($i = $currentYear; $i >= $oldestYear; $i--) {
                    if (isset($row_4['birth_day']) && $i == (int) explode('-', $row_4['birth_day'])[0]) {
                        $registeredYear = ' selected';
                    } else {
                        $registeredYear = '';
                    }
                    $birth_year .= '<option value="' . $i . '"' . $registeredYear . '>' . $i . '</option>';
                }

                // options for home country
                $home_country = '<option disabled selected value>Home Country</option>';
                $countries = array(
                    'AF' => 'Afghanistan',
                    'AX' => 'Aland Islands',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AS' => 'American Samoa',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AQ' => 'Antarctica',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BQ' => 'Bonaire, Saint Eustatius and Saba',
                    'BA' => 'Bosnia and Herzegovina',
                    'BW' => 'Botswana',
                    'BV' => 'Bouvet Island',
                    'BR' => 'Brazil',
                    'IO' => 'British Indian Ocean Territory',
                    'VG' => 'British Virgin Islands',
                    'BN' => 'Brunei',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island',
                    'CC' => 'Cocos Islands',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros',
                    'CK' => 'Cook Islands',
                    'CR' => 'Costa Rica',
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CW' => 'Curacao',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'CD' => 'Democratic Republic of the Congo',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'TL' => 'East Timor',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FK' => 'Falkland Islands',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'TF' => 'French Southern Territories',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GU' => 'Guam',
                    'GT' => 'Guatemala',
                    'GG' => 'Guernsey',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HM' => 'Heard Island and McDonald Islands',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IM' => 'Isle of Man',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'CI' => 'Ivory Coast',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JE' => 'Jersey',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'XK' => 'Kosovo',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => 'Laos',
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'Macedonia',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MH' => 'Marshall Islands',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte',
                    'MX' => 'Mexico',
                    'FM' => 'Micronesia',
                    'MD' => 'Moldova',
                    'MC' => 'Monaco',
                    'MN' => 'Mongolia',
                    'ME' => 'Montenegro',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'MM' => 'Myanmar',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'NU' => 'Niue',
                    'NF' => 'Norfolk Island',
                    'KP' => 'North Korea',
                    'MP' => 'Northern Mariana Islands',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PW' => 'Palau',
                    'PS' => 'Palestinian Territory',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'PR' => 'Puerto Rico',
                    'QA' => 'Qatar',
                    'CG' => 'Republic of the Congo',
                    'RE' => 'Reunion',
                    'RO' => 'Romania',
                    'RU' => 'Russia',
                    'RW' => 'Rwanda',
                    'BL' => 'Saint Barthelemy',
                    'SH' => 'Saint Helena',
                    'KN' => 'Saint Kitts and Nevis',
                    'LC' => 'Saint Lucia',
                    'MF' => 'Saint Martin',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'WS' => 'Samoa',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'RS' => 'Serbia',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SX' => 'Sint Maarten',
                    'SK' => 'Slovakia',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia and the South Sandwich Islands',
                    'KR' => 'South Korea',
                    'SS' => 'South Sudan',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SJ' => 'Svalbard and Jan Mayen',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syria',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania',
                    'TH' => 'Thailand',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'VI' => 'U.S. Virgin Islands',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'GB' => 'United Kingdom',
                    'US' => 'United States',
                    'UM' => 'United States Minor Outlying Islands',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VA' => 'Vatican',
                    'VE' => 'Venezuela',
                    'VN' => 'Vietnam',
                    'WF' => 'Wallis and Futuna',
                    'EH' => 'Western Sahara',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe',
                );
                foreach ($countries as $key => $value) {
                    if ($key == $row_4['home_country']) {
                        $registeredCountry = ' selected';
                    } else {
                        $registeredCountry = '';
                    }
                    $home_country .= '<option value="' . $key . '"' . $registeredCountry . '>' . $value . '</option>';
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include('./includes/head.php'); ?>

<body class="event-details">
    <form action="reservation-process.php" method="POST">
        <!-- Calendar -->
        <div class="modal" id="calendar-modal">
            <div class="calendar">
                <?php include('./includes/calendar-header-body.php'); ?>
                <input type="hidden" name="event-id" value="<?php echo specialChar($event_id); ?>">
                <div class="date-and-guests">
                    <div class="selected-date" id="selected-date-normal"></div>
                    <input type="hidden" name="date" id="selected-date-hyphened-hidden">
                    <input type="hidden" name="display-date" id="selected-date-normal-hidden">
                    <div class="number-of-guests">
                        <div>Number of guests</div>
                        <div class="position-relative">
                            <span class="down-arrow"></span>
                            <?php
                            $maxGuestNum = 10;
                            $optionElem = '';
                            for ($i = 1; $i <= 10; $i++) {
                                $optionElem .= '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                            <select name="number-of-guests">
                                <?php echo $optionElem; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="total-amount">

                <div class="go-to-next">
                    <button type="button" id="next-button" class="<?php echo $reviewOrAccount; ?>">Next</button>
                </div>
                <span class="close">&times;</span>
            </div>
        </div>

        <!-- Review -->
        <div class="modal" id="review-modal">
            <div class="review-and-payment">
                <h2>Review and Payment</h2>
                <div class="review-event-title">
                    <div>Event</div>
                    <div class="bold"><?php echo $row['title']; ?></div>
                </div>
                <div class="review-selected-date">
                    <div>Date</div>
                    <div class="bold"></div>
                </div>
                <div class="review-number-of-guests">
                    <div>Number of guest(s)</div>
                    <div class="bold"></div>
                </div>
                <div class="review-total-amount">
                    <div>Total amount (JPY)</div>
                    <div class="bold"></div>
                </div>
                <div>
                    <h3>Your data</h3>
                </div>
                <div class="name">
                    <div>Name</div>
                    <div><?php if (isset($_SESSION['sid'])) {
                                echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
                            } ?></div>
                </div>
                <div class="gender">
                    <div>Gender</div>
                    <div class="position-relative">
                        <select name="gender" required>
                            <?php echo $gender; ?>
                        </select>
                        <span class="down-arrow"></span>
                    </div>
                </div>
                <div class="date-of-birth">
                    <div>Date of birth</div>
                    <div>
                        <div class="select-div">
                            <select name="birth_month" required>
                                <?php echo $birth_month; ?>
                            </select>
                            <span class="down-arrow"></span>
                        </div>
                        <div class="select-div">
                            <select name="birth_date" required>
                                <?php echo $birth_date; ?>
                            </select>
                            <span class="down-arrow"></span>
                        </div>
                        <div class="select-div">
                            <select name="birth_year" required>
                                <?php echo $birth_year; ?>
                            </select>
                            <span class="down-arrow"></span>
                        </div>
                    </div>
                </div>
                <div class="home-country">
                    <div>Home country</div>
                    <div class="position-relative">
                        <select name="home_country" required>
                            <?php echo $home_country; ?>
                        </select>
                        <span class="down-arrow"></span>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['sid'])) {
                    echo '<div><button type="submit">Confirm and apply</button></div>';
                }
                ?>
                <span class="close">&times;</span>
            </div>
        </div>
    </form>

    <?php include('./includes/search-options.php') ?>

    <?php include('./includes/log-in-window.php'); ?>

    <?php include('./includes/sign-up-window.php'); ?>

    <?php include('./includes/header.php') ?>

    <main>
        <div class="image">
            <div class="main">
                <img src="<?php echo $row['main_image']; ?>">
            </div>
        </div>
        <!-- カレンダーに追加できるようにする -->
        <div class="wrap">
            <h1 class="title"><?php echo $row['title']; ?></h1>
            <div class="category"><?php echo $category; ?></div>
            <div class="date">
                <span><i class="far fa-calendar-alt"></i></span>
                <span id="dates"><?php echo str_replace('-', '/', $row['start_date']) . ' - ' . str_replace('-', '/', $row['end_date']); ?></span>
            </div>
            <div class="time">
                <span><i class="far fa-clock"></i></span>
                <span><?php echo preg_replace('/:00$/', '', $row['start_time']) . ' - ' . preg_replace('/:00$/', '', $row['end_time']); ?></span>
            </div>
            <div class="address">
                <span><i class="fas fa-map-marker-alt"></i></span>
                <span><?php echo $row['address']; ?></span>
            </div>
            <div class="summary">
                <h2>Summary</h2>
                <div><?php echo $row['summary']; ?></div>
            </div>
            <div class="details">
                <h2>Details</h2>
                <div><?php echo $row['details'] ?></div>
            </div>
            <div class="organizer">
                <h2>Organizer</h2>
                <div class="flex-box">
                    <div><?php echo '<a href="organizer.php?id=' . $row['organizer_id'] . '"><img src="' . $row['organizer_image'] . '"></a>'; ?></div>
                    <div class="organizer-name"><?php echo '<a href="organizer.php?id=' . $row['organizer_id'] . '">' . $row['first_name'] . '</a>'; ?></div>
                </div>
            </div>
            <div class="where">
                <h2>Where we'll explore</h2>
                <div class="embed-map">
                    <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDqiHGqyF1Jcmbn_QIQFHkTIrD5EmIxPE4&q=place_id:<?php echo $row['place_id']; ?>" allowfullscreen>
                    </iframe>
                </div>
                <div class="flex-box">
                    <div class="open-map">
                        <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($row['address']); ?>&query_place_id=<?php echo $row['place_id']; ?>">
                            <button>Open Google Map</button>
                        </a>
                    </div>
                    <div class="directions">
                        <a target="_blank" href="https://www.google.com/maps/dir/?api=1&origin=&destination=<?php echo urlencode($row['address']); ?>&destination_place_id=<?php echo $row['place_id']; ?>&travelmode=">
                            <button>See directions</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="price-dates">
        <div class="price">From ¥ <?php echo number_format($row['price']); ?>/day</div>
        <div class="dates"><button id="check-dates">Check dates</button></div>
    </div>

    <?php include('./includes/footer.php') ?>

    <script src="./js/event.js"></script>
    <script src="./js/search-options.js"></script>
    <script src="./js/log-in-sign-up-modals.js"></script>
    <script src="./js/functions.js"></script>

</body>

</html>