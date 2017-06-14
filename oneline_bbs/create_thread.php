<?php
//データベースに接続
$link = mysql_connect('localhost', 'user', 'pw');
if (!$link) {
  die('データベースに接続できません：' . mysql_error());
}
//データベースを選択する
mysql_select_db('oneline_bbs', $link);
$errors = array();

// POSTなら保存処理実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

 //タイトルが正しく入力されているかチェック
  $title = null;
  if(!isset($_POST['title']) || !strlen($_POST['title'])) {
    $errors['title'] = 'タイトルを入力してください';
  } else if (strlen($_POST['title']) > 40) {
    $errors['title'] = 'タイトルは40文字以内で入力してください。';
  } else {
    $name = $_POST['name'];
  }

  //エラーがなければ保存
  if (count($errors) === 0) {
//書き込み
$sql_thread = sprintf("INSERT INTO `threads` (`title`, `comment`, `created_at`) VALUES ('%s', '%s', '%s')",
	mysql_real_escape_string($_POST['title']),
	mysql_real_escape_string($_POST['comment']),
	date('Y-m-d H:i:s'));

$result_thread = mysql_query($sql_thread, $link);

//スレッド画面に遷移
header("Location: index.php");
}
}
?>

<html>
<head>
<meta charset="utf-8">
	<title>スレッド作成</title>
	<link rel="stylesheet" href="stylepls.css">
</head>
<body class="all">
<div style="cursor: pointer; " onclick="location.href='index.php'" title="ホームへ">
 <h1 class="midashi">Oneline BBS</h1>
 </div>

<form action="create_thread.php" method="post">
<?php if (count($errors) > 0): ?>
  <ul class="error_list">
   <?php foreach ($errors as $error): ?>
   <li>
    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
   </li>
   <?php endforeach; ?>
  </ul>
  <?php endif; ?>
<table class="postmenu">
	<tr>
		<th>タイトル</th>
		<td><input type="text" name="title" size="25"></td>
	</tr>
	<tr>
		<td><input type="hidden" name="type" value="create"></td>
		<td><button class="create" type="submit" name="submit" value="send">create</button></td>
	</tr>
</table>
</form>

</body>
</html>