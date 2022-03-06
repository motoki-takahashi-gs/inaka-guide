<?php

include("./includes/functions.php");

// page name
$pageName = 'Home';

$dbh = db_con();

// 1ページあたりのデータ数
$perPage = 3;

// 現在のページ番号
$currentPage = (isset($_GET['page']) ? (int) $_GET['page'] : 1);

// スタートするデータ番号
$startAt = $perPage * ($currentPage - 1);

// 検索キーワード
$query = (isset($_GET['q']) ? $_GET['q'] : null);

// Destinations parameter
$destinationsParam = (isset($_GET['destinations']) ? $_GET['destinations'] : null);

// Dates parameter
$datesParam = (isset($_GET['dates']) ? $_GET['dates'] : null);

// Activities parameter
$activitiesParam = (isset($_GET['activities']) ? $_GET['activities'] : null);

// count the number of events matched to search criteria
$innerJoin = "";
$destinationsCondition = "";
$datesCondition = "";
$activitiesCondition = "";

if ($destinationsParam) {
    $destinationsParamArray = explode(',', $destinationsParam);
    foreach ($destinationsParamArray as $dest) {
        $destinationsCondition .= "prefecture = '" . $dest . "' OR ";
    }
    // omit the last 4 characters which are ' OR '
    $destinationsCondition = substr($destinationsCondition, 0, -4);
    $destinationsCondition = "AND (" . $destinationsCondition . ")";
}

if ($datesParam) {
    $datesParamArray = explode(',', $datesParam);
    $start_date_searched = $datesParamArray[0];
    $end_date_searched = $datesParamArray[1];
    $datesCondition = "AND ('$start_date_searched' <= end_date AND '$end_date_searched' >= start_date)";
}

if ($activitiesParam) {
    // inner join
    $innerJoin = "INNER JOIN event_category ON events.id = event_category.event_id";

    $activitiesParamArray = explode(',', $activitiesParam);
    foreach ($activitiesParamArray as $act) {
        $activitiesCondition .= "category_id = '" . $act . "' OR ";
    }
    // omit the last 4 characters which are ' OR '
    $activitiesCondition = substr($activitiesCondition, 0, -4);
    $activitiesCondition = "AND (" . $activitiesCondition . ")";
}

$sql_count = "SELECT COUNT(DISTINCT events.id) AS cnt FROM events
    $innerJoin
    WHERE title LIKE :query AND is_deleted = 0
    $destinationsCondition $datesCondition $activitiesCondition";

$sth_count = $dbh->prepare($sql_count);
$sth_count->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);

$status_count = $sth_count->execute();

if ($status_count == false) {
    sqlError($sth_count);
} else {
    $total_row = $sth_count->fetch(PDO::FETCH_ASSOC);
    // イベントの全件数
    $total_events = $total_row['cnt'];
}

// 合計ページ数
$totalPages = ceil($total_events / $perPage);

// 前ページへのリンク
$backward = ($currentPage > 1
    ? sprintf('<a href="?%s"><i class="fas fa-chevron-circle-left"></i></a>', http_build_query([
        'page' => $currentPage - 1,
        'q' => $query,
        'destinations' => $destinationsParam,
        'dates' => $datesParam,
        'activities' => $activitiesParam
    ]))
    : '<i class="fas fa-chevron-circle-left no-link"></i>');

// 次ページへのリンク
$forward = ($currentPage < $totalPages
    ? sprintf('<a href="?%s"><i class="fas fa-chevron-circle-right"></i></a>', http_build_query([
        'page' => $currentPage + 1,
        'q' => $query,
        'destinations' => $destinationsParam,
        'dates' => $datesParam,
        'activities' => $activitiesParam
    ]))
    : '<i class="fas fa-chevron-circle-right no-link"></i>');

// events テーブルから選択
$sql = "SELECT DISTINCT events.id AS event_id, main_image, title, summary, start_date, end_date, start_time, end_time, address
    FROM events
    $innerJoin
    WHERE title LIKE :query AND is_deleted = 0
    $destinationsCondition $datesCondition $activitiesCondition
    ORDER BY start_date ASC LIMIT :perPage OFFSET :startAt";
$sth = $dbh->prepare($sql);
$sth->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
$sth->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$sth->bindValue(':startAt', $startAt, PDO::PARAM_INT);
$status = $sth->execute();

if ($status == false) {
    sqlError($sth);
} else {

    // events テーブルのデータをいったん配列化
    $db_data = [];
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $db_data[] = $row;
    }

    // event_category テーブルから選択
    $sql_2 = "SELECT event_id, name FROM event_category
    INNER JOIN categories ON event_category.category_id = categories.id
    ORDER BY name ASC";
    $sth_2 = $dbh->prepare($sql_2);
    $status_2 = $sth_2->execute();

    if ($status_2 == false) {
        sqlError($sth_2);
    } else {

        // event_category テーブルのデータをいったん配列化
        $db_data_2 = [];
        while ($row_2 = $sth_2->fetch(PDO::FETCH_ASSOC)) {
            $db_data_2[] = $row_2;
        }

        $events = '';
        foreach ($db_data as $row) {
            $events .= '<div class="event">';
            $events .= '<a href="event.php?id=' . $row['event_id'] . '">';
            $events .= '<div class="image"><img src="' . $row['main_image'] . '"></div>';
            $events .= '<div class="basic-info">'; // for flex starts
            $events .= '<div class="title">' . specialChar($row['title']) . '</div>';
            $events .= '<div class="summary">' . $row['summary'] . '</div>';
            $events .= '<div class="date">';
            $events .= '<span><i class="far fa-calendar-alt"></i></span>';
            $startDate = str_replace('-', '/', $row['start_date']);
            $endDate = str_replace('-', '/', $row['end_date']);
            $events .= '<span>';
            if ($startDate == $endDate) {
                $events .= $startDate;
            } else {
                $events .=  $startDate . ' - ' . $endDate;
            }
            $events .= '</span>';
            $events .= '</div>';
            $events .= '<div class="time">';
            $events .= '<span><i class="far fa-clock"></i></span>';
            $startTime = preg_replace('/:00$/', '', $row['start_time']);
            $endTime = preg_replace('/:00$/', '', $row['end_time']);
            $events .= '<span>' . $startTime . ' - ' . $endTime . '</span>';
            $events .= '</div>';
            $events .= '<div class="address">';
            $events .= '<span><i class="fas fa-map-marker-alt"></i></span>';
            $events .= '<span>' . specialChar($row['address']) . '</span>';
            $events .= '</div>';
            $events .= '<div class="category">'; // Category starts
            foreach ($db_data_2 as $row_2) {
                if ($row['event_id'] == $row_2['event_id']) {
                    $events .= '<button>' . specialChar($row_2['name']) . '</button>';
                }
            }
            $events .= '</div>'; // Category ends
            $events .= '</div>'; // for flex ends
            $events .= '</a>';
            $events .= '</div>';
        }

        // to show activities list in search options modal
        $activities = show_activities_list($dbh);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include('./includes/head.php'); ?>

<body>

    <?php include('./includes/search-options.php') ?>

    <?php include('./includes/log-in-window.php'); ?>

    <?php include('./includes/sign-up-window.php'); ?>

    <?php include('./includes/header.php') ?>

    <main>
        <div class="explanation">
            <?php
            if (isset($_GET['page']) && ($_GET['page'] >= 2)) {
                $explanation = '<p>Things you can experience only in rural areas.</p>';
            } else {
                $explanation = '
                <p>INAKA means "rural area" or "countryside" in Japanese.</p>
                <p>INAKA GUIDE is a web application where you can discover wonderful and beautiful rural areas in Japan.</p>
                <p>We provide entirely new and unforgettable experiences to those who want to know how it is to live in
                rural areas and what the cultures and histories are, and who are interested in relaxing getting away
                    from the hustle and bustle of a city life.</p>
                <p>Our tour guides will show you around nice places as well as things you can experience only in Japanese rural areas.</p>
                ';
            }

            echo $explanation;

            ?>
        </div>

        <p class="query-result"><?php
                                echo $total_events . ' results';
                                if (isset($query)) {
                                    echo ' for <span>' . specialChar($query) . '</span>';
                                }
                                ?></p>

        <div>
            <?php echo $events; ?>
        </div>

        <div class="pagination">
            <div><?php echo $backward; ?></div>
            <div><?php echo $forward; ?></div>
        </div>
    </main>

    <?php include('./includes/footer.php') ?>

    <script src="./js/search-options.js"></script>
    <script src="./js/log-in-sign-up-modals.js"></script>
    <script src="./js/functions.js"></script>

</body>

</html>