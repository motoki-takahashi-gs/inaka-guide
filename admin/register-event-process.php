<?php

include("../includes/functions.php");

checkSid();

$title = filter_input(INPUT_POST, "title");
$summary = filter_input(INPUT_POST, "summary");
$details = filter_input(INPUT_POST, "details");
$start_date = filter_input(INPUT_POST, "start_date");
$end_date = filter_input(INPUT_POST, "end_date");
$start_time = filter_input(INPUT_POST, "start_time_hours") . ':' . filter_input(INPUT_POST, "start_time_minutes");
$end_time = filter_input(INPUT_POST, "end_time_hours") . ':' . filter_input(INPUT_POST, "end_time_minutes");
$address = filter_input(INPUT_POST, "address");
$latitude = filter_input(INPUT_POST, "latitude");
$longitude = filter_input(INPUT_POST, "longitude");
$place_id = filter_input(INPUT_POST, "place_id");
$prefecture = filter_input(INPUT_POST, "prefecture");
$organizer_id = $_SESSION["organizer_id"];

// データベース接続
$dbh = db_con();

// register an event except for the main image and categories
$sql = "INSERT INTO events (title, summary, details, start_date, end_date, start_time, end_time, address, latitude, longitude, place_id, prefecture, organizer_id, created_at)
VALUES(:title, :summary, :details, :start_date, :end_date, :start_time, :end_time, :address, :latitude, :longitude, :place_id, :prefecture, :organizer_id, sysdate())";
$sth = $dbh->prepare($sql);
$sth->bindValue(':title', $title, PDO::PARAM_STR);
$sth->bindValue(':summary', $summary, PDO::PARAM_STR);
$sth->bindValue(':details', $details, PDO::PARAM_STR);
$sth->bindValue(':start_date', $start_date, PDO::PARAM_STR);
$sth->bindValue(':end_date', $end_date, PDO::PARAM_STR);
$sth->bindValue(':start_time', $start_time, PDO::PARAM_STR);
$sth->bindValue(':end_time', $end_time, PDO::PARAM_STR);
$sth->bindValue(':address', $address, PDO::PARAM_STR);
$sth->bindValue(':latitude', $latitude, PDO::PARAM_STR);
$sth->bindValue(':longitude', $longitude, PDO::PARAM_STR);
$sth->bindValue(':place_id', $place_id, PDO::PARAM_STR);
$sth->bindValue(':prefecture', $prefecture, PDO::PARAM_STR);
$sth->bindValue(':organizer_id', $organizer_id, PDO::PARAM_INT);
$status = $sth->execute();

if ($status == false) {
    sqlError($sth);
} else {
    // 上記で登録されたイベントのレコードを選択
    $sql = "SELECT id FROM events
    WHERE id = LAST_INSERT_ID() AND organizer_id = $organizer_id";
    $sth = $dbh->prepare($sql);
    $status = $sth->execute();

    if ($status == false) {
        sqlError($sth);
    } else {
        $row = $sth->fetch();
        $event_id = $row["id"];

        /* ファイルのアップロード ここから */
        // if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {

        // メイン画像のファイル名
        $main_image = $_FILES['main_image']['name'];

        // 一時パス
        $tmp_path = $_FILES['main_image']['tmp_name'];

        /* 画像のリサイズ ここから */

        // 横幅と高さの最大値を設定
        $width = 500;
        $height = 500;

        // オリジナル画像の横幅と高さ
        list($width_orig, $height_orig) = getimagesize($tmp_path);

        // オリジナル画像の横縦比
        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }

        // オリジナル画像を元に指定サイズで新画像を作成
        $new_image = imagecreatetruecolor($width, $height);
        $orig_image = imagecreatefromjpeg($tmp_path);
        imagecopyresampled($new_image, $orig_image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        // 作成した新画像を一時パスに保存
        imagejpeg($new_image, $tmp_path, 100);

        // 新画像を削除してメモリを解放
        imagedestroy($new_image);

        /* 画像のリサイズ ここまで */

        // ファイルの拡張子
        $extension = pathinfo($main_image, PATHINFO_EXTENSION);

        // ファイル名を上書き
        $main_image = date('YmdHis') . md5(session_id()) . '.' . $extension;

        // 作られていなければユーザー専用のフォルダを作成
        $new_directory = '../images/events/' . $event_id . '/';
        if (!is_dir($new_directory)) {
            mkdir($new_directory);
        }

        // フォルダの権限を確認
        // echo substr(sprintf('%o', fileperms($new_directory)), -4);

        // 画像へのパス
        $img_path = $new_directory . $main_image;

        // 一時パスにファイルがアップされているかチェック
        if (is_uploaded_file($tmp_path)) {

            // 一時パスから画像へのパスにファイルを移動
            if (move_uploaded_file($tmp_path, $img_path)) {

                // 権限を変更（オーナーは読み書き、グループとその他は読み込みだけ）
                chmod($img_path, 0644);
            } else {
                echo 'エラーが発生したため、画像をアップロードできませんでした。';
            }
        }

        // トップページから見たメイン画像のパスを再定義
        $main_image = 'images/events/' . $event_id . '/' . $main_image;

        // }

        $sql_2 = "UPDATE events SET main_image = :main_image WHERE id = $event_id";
        $sth_2 = $dbh->prepare($sql_2);
        $sth_2->bindValue(':main_image', $main_image, PDO::PARAM_STR);
        $status_2 = $sth_2->execute();
        /* ファイルのアップロード ここまで */

        if ($status_2 == false) {
            sqlError($sth_2);
        } else {

            // カテゴリ（配列で渡ってくる）
            $categories = [];
            if (isset($_POST["categories"])) {
                $categories = $_POST["categories"];
            }

            // 登録されたカテゴリの数だけループ
            for ($i = 0; $i < count($categories); $i++) {
                $sql_3 = "INSERT INTO event_category (event_id, category_id, created_at)
                VALUES($event_id, :category_id, sysdate())";
                $sth_3 = $dbh->prepare($sql_3);
                $sth_3->bindValue(':category_id', $categories[$i], PDO::PARAM_INT);
                $status_3 = $sth_3->execute();
            }

            if ($status_3 == false) {
                sqlError($sth_3);
            } else {
                // redirect("register-event-success.php");
            }
        }
    }
}
