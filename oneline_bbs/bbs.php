<?php

//データベースに接続
$link = mysql_connect('localhost', 'user', 'pw');
if (!$link) {
  die('データベースに接続できません：' . mysql_error());
}
//データベースを選択する
mysql_select_db('oneline_bbs', $link);
$errors = array();

//スレッドID取得
$id = $_GET['id'];

//タイトル取得
$sql_title = sprintf("SELECT `title` FROM `threads` WHERE `id` ='%d'",$id);
$result_title = mysql_query($sql_title, $link);
$row_title = mysql_fetch_row($result_title);


// POSTなら保存処理実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //名前が正しく入力されているかチェック
  $name = null;
  if(!isset($_POST['name']) || !strlen($_POST['name'])) {
    $errors['name'] = '名前を入力してください';
  } else if (strlen($_POST['name']) > 40) {
    $errors['name'] = '名前は４０文字以内で入力してください';
  } else {
    $name = $_POST['name'];
  }

  // ひとことが正しく入力されているかチェック
  $comment = null;
  if (!isset($_POST['comment']) || !strlen($_POST['comment'])) {
    $errors['comment'] = 'ひとことを入力してください';
  } else if(strlen($_POST['comment']) > 200) {
    $errors['comment'] = 'ひとことは２００文字以内で入力してください';
  } else {
    $comment = $_POST['comment'];
  }


  // 削除パスが正しく入力されているかチェック
  $deletekey = null;
  if (!isset($_POST['deletekey']) || !strlen($_POST['deletekey'])) {
    $errors['deletekey'] = '4桁の削除用パスワードを入力してください（半角数字）';
  } else if(strlen($_POST['deletekey']) != 4) {
    $errors['deletekey'] = '4桁の削除用パスワードを入力してください（半角数字）';
  } else {
    $deletekey = sha1($_POST['deletekey']);
  }
  //エラーがなければ保存
  if (count($errors) === 0) {

    //画像保存
$upfile = $_FILES['upfile']['tmp_name'];

//画像が投稿されていた場合
if($upfile){

      //画像をバイナリに変換
  $img_file = file_get_contents($upfile);
  $img_binary = mysql_real_escape_string($img_file);

    //画像がアップされたときに保存するSQL文
  $img_sql = sprintf("INSERT INTO `post` (`thread_id`, `name`, `comment`, `image`, `deletekey`, `created_at`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
    mysql_real_escape_string($id),
    mysql_real_escape_string($name),
    mysql_real_escape_string($comment),
    $img_binary,
    mysql_real_escape_string($deletekey),
    date('Y-m-d H:i:s'));
    //保存する
mysql_query($img_sql, $link);

} else {

    //画像がアップされていないとき
    $sql = sprintf("INSERT INTO `post` (`thread_id`, `name`, `comment`, `deletekey`, `created_at`) VALUES ('%s', '%s', '%s', '%s', '%s')",
    mysql_real_escape_string($id),
    mysql_real_escape_string($name),
    mysql_real_escape_string($comment),
    mysql_real_escape_string($deletekey),
    date('Y-m-d H:i:s'));
    //保存する
mysql_query($sql, $link);
  }
  }


$url = "bbs.php?id=". $id;
}



// 投稿された内容を取得するSQLを作成して結果を取得
 $sql = sprintf("SELECT * FROM `post` where thread_id ='%d' ORDER BY `created_at`", $id);
 $result = mysql_query($sql, $link);


//取得した結果を$postsに格納
$posts = array();
if ($result !== false && mysql_num_rows($result)) {
  while ($post = mysql_fetch_assoc($result)) {
    $posts[] = $post;
  }
}
include 'view.php';
 ?>