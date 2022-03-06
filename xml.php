<?php

include('includes/functions.php');

$doc = new DOMDocument('1.0', 'UTF-8');
$node = $doc->createElement('events');
$parNode = $doc->appendChild($node);

$dbh = db_con();

// events テーブルから選択
$sql = 'SELECT * FROM events WHERE is_deleted = 0';
$sth = $dbh->prepare($sql);
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
    $sql_2 = 'SELECT * FROM event_category
    INNER JOIN categories ON event_category.category_id = categories.id';
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

        // XML で表示
        header('Content-type: text/xml; charset=UTF-8');

        // events テーブルのレコードをループ
        foreach ($db_data as $row) {
            $node = $doc->createElement('event');
            $newNode = $parNode->appendChild($node);

            $newNode->setAttribute("id", $row['id']);
            $newNode->setAttribute("title", $row['title']);
            $newNode->setAttribute("summary", $row['summary']);
            $newNode->setAttribute("main_image", $row['main_image']);
            $newNode->setAttribute("start_date", $row['start_date']);
            $newNode->setAttribute("end_date", $row['end_date']);
            $newNode->setAttribute("start_time", $row['start_time']);
            $newNode->setAttribute("end_time", $row['end_time']);
            $newNode->setAttribute("address", $row['address']);
            $newNode->setAttribute("latitude", $row['latitude']);
            $newNode->setAttribute("longitude", $row['longitude']);

            $category = [];
            foreach ($db_data_2 as $row_2) {
                if ($row['id'] == $row_2['event_id']) {
                    $category[] = $row_2['name'];
                }
            }
            $newNode->setAttribute("category", implode(',', $category));
        }

        echo $doc->saveXML();
    }
}
