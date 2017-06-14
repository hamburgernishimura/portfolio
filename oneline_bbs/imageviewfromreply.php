<?php
//データベースに接続
$link = mysql_connect('localhost', 'user', 'pw');
if (!$link) {
  die('データベースに接続できません：' . mysql_error());
}
//データベースを選択する
mysql_select_db('oneline_bbs', $link);
$imgid = $_GET['imgid'];

//指定したidの画像を取得
$get_img_sql = sprintf("SELECT `image` FROM `reply` WHERE id ='%s'",$_GET['imgid']);
$result = mysql_query($get_img_sql, $link);
$row_image = mysql_fetch_row($result);

// バイナリデータを直接表示
header("Content-Type: image/jpeg");
echo $row_image[0];
?>