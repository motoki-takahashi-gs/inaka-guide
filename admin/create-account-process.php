<?php

include("../includes/functions.php");

checkSid();

$last_name = filter_input(INPUT_POST, "last_name");
$first_name = filter_input(INPUT_POST, "first_name");
$company_name = filter_input(INPUT_POST, "company_name");
$email = filter_input(INPUT_POST, "email");
$password = filter_input(INPUT_POST, "password");
$password = password_hash($password, PASSWORD_DEFAULT);
$about = filter_input(INPUT_POST, "about");

$dbh = db_con();

$sql = "INSERT INTO organizers (last_name, first_name, company_name, email, password, about, created_at)
VALUES(:last_name, :first_name, :company_name, :email, :password, :about, sysdate())";
$sth = $dbh->prepare($sql);
$sth->bindValue(':last_name', $last_name, PDO::PARAM_STR);
$sth->bindValue(':first_name', $first_name, PDO::PARAM_STR);
$sth->bindValue(':company_name', $company_name, PDO::PARAM_STR);
$sth->bindValue(':email', $email, PDO::PARAM_STR);
$sth->bindValue(':password', $password, PDO::PARAM_STR);
$sth->bindValue(':about', $about, PDO::PARAM_STR);
$status = $sth->execute();

if ($status == false) {
    sqlError($sth);
} else {

    $sql_2 = "SELECT id FROM organizers WHERE id = LAST_INSERT_ID()";
    $sth_2 = $dbh->prepare($sql_2);
    $status_2 = $sth_2->execute();

    if ($status_2 == false) {
        sqlError($sth_2);
    } else {
        $row_2 = $sth_2->fetch();
        $organizer_id = $row_2["id"];
    }

    /* ファイルのアップロード ここから */

    // メイン画像のファイル名
    $image = $_FILES['image']['name'];

    // 一時パス
    $tmp_path = $_FILES['image']['tmp_name'];

    /* 画像のリサイズ ここから */

    // 横幅と高さの最大値を設定
    $width = 150;
    $height = 150;

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
    $extension = pathinfo($image, PATHINFO_EXTENSION);

    // ファイル名を上書き
    $image = date('YmdHis') . md5(session_id()) . '.' . $extension;

    // 作られていなければユーザー専用のフォルダを作成
    $new_directory = '../images/guides/' . $organizer_id . '/';
    if (!is_dir($new_directory)) {
        mkdir($new_directory);
    }

    // フォルダの権限を確認
    // echo substr(sprintf('%o', fileperms($new_directory)), -4);

    // 画像へのパス
    $img_path = $new_directory . $image;

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
    $image = 'images/guides/' . $organizer_id . '/' . $image;

    /* ファイルのアップロード ここまで */

    // if :image is $image, it needs single quotes in "SET image" part
    $sql_3 = "UPDATE organizers SET image = :image WHERE id = $organizer_id";
    $sth_3 = $dbh->prepare($sql_3);
    $sth_3->bindValue(':image', $image, PDO::PARAM_STR);
    $status_3 = $sth_3->execute();

    if ($status_3 == false) {
        sqlError($sth_3);
    } else {
        // redirect("create-account-success.php");
    }
}
