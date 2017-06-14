<?php
//データベースに接続
$link = mysql_connect('localhost', 'user', 'pw');
if (!$link) {
 die('データベースに接続できません：' . mysql_error());
}

//データベースを選択する
mysql_select_db('oneline_bbs', $link);
$errors = array();

//スレッドID,コメントID取得
$threadid = $_GET['thread_id'];
$commentid = $_GET['id'];


//親発言があるか確認
$sql_parent = sprintf("select * from `post` where `id`='%s'", $commentid);
$result = mysql_query($sql_parent, $link);
if (mysql_num_rows($result) != 1) {
	echo ("親発言がありません。");
}
$row = mysql_fetch_assoc($result);

//投稿後、リダイレクト用のURL
$url2 = "location: bbs.php?id=". $threadid;

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
  $img_sql = sprintf("INSERT INTO `reply` (`name`, `comment`, `srcid`, `image`, `deletekey`, `created_at`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
    mysql_real_escape_string($name),
    mysql_real_escape_string($comment),
    mysql_real_escape_string($commentid),
    $img_binary,
    mysql_real_escape_string($deletekey),
    date('Y-m-d H:i:s'));
    //保存する
mysql_query($img_sql, $link);
header($url2);
exit();

} else {

    //画像がアップされていないとき
    $sql = sprintf("INSERT INTO `reply` (`name`, `comment`, `srcid`,  `deletekey`, `created_at`) VALUES ('%s', '%s', '%s', '%s', '%s')",
    mysql_real_escape_string($name),
    mysql_real_escape_string($comment),
    mysql_real_escape_string($commentid),
    mysql_real_escape_string($deletekey),
    date('Y-m-d H:i:s'));
    //保存する
mysql_query($sql, $link);

header($url2);
exit();
  }
  }
}
$url = "reply.php?id=". $commentid . "&thread_id=" . $threadid;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<script type="text/javascript" src="jquery/jquery-3.1.1.min.js"></script>
<head>
<meta charset="utf-8">
<title>返信フォーム</title>
<link rel="stylesheet" href="stylepls.css">
</head>
<body class="all">
 <div style="cursor: pointer; " onclick="location.href='index.php'" title="ホームへ">
 <h1 class="midashi">Oneline BBS</h1>
 </div>
<body>
<form action="<?php $url ?>" method="post" enctype="multipart/form-data">
  <?php if (count($errors) > 0): ?>
  <ul class="error_list">
   <?php foreach ($errors as $error): ?>
   <li>
    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
   </li>
   <?php endforeach; ?>
  </ul>
  <?php endif; ?>

<h2 align="center">親発言</h2>
<div class="comment">
  <p>名前：<?php echo $row['name']; ?></p>
  <p>内容：
  <br/>
  <?php // 画像を出力
      if(isset($row['image'])){
  print("<img src=\"imageview.php?imgid=" . $row['id'] . "\">");
} else {} ?>
<br/>
  <?php echo $row['comment']; ?></p>
  <p>投稿日時：<?php echo $row['created_at'] ?></p>

</div>

<div class="postmenu">
	<h2 align="center">返信フォーム</h2>

  <p> ニックネーム</p>
  <input type="text" class="namebox" name="name" />

  <p> ひとこと</p>
  <textarea class="textbox" name="comment" size="60" /></textarea><br />

  <!--画像アップロード欄-->
  <p>画像ファイルを添付(GIF, JPEG, PNGのみ対応)</p>
<input type="file" id="file" name="upfile" style="display:none;" onchange="$('#fake_input_file').val($(this).val())">
<input type="button"  class="imagebtn" value="" onClick="$('#file').click();">
<input id="fake_input_file" readonly type="text" value="" onClick="$('#file').click();">



  <p>削除用パスワード（半角数字４桁）</p>
  <input type="password" class="deletebox" name="deletekey" size="4">
  <br /><br /><br />
  <input type="submit" class="send" value="reply" onclick="location.reload();">

  </form>

</body>
</html>