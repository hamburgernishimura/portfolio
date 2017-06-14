<?php
//データベースに接続
$link = mysql_connect('localhost', 'user', 'pw');
if (!$link) {
 die('データベースに接続できません：' . mysql_error());
}

//データベースを選択する
mysql_select_db('oneline_bbs', $link);

//ユーザが入力したパスワードをハッシュ化
$tmp = sha1($_POST['postdeletekey']);

//該当投稿の削除用パスを取り出す
$sql_del = sprintf("SELECT deletekey FROM post WHERE id ='%s'",$_POST['id']);
$verify = mysql_query($sql_del, $link);
$row = mysql_fetch_row($verify);

// パスワードが一致していればDELETE文を実行
if ($tmp === $row[0]) {
$sql = sprintf("DELETE FROM post WHERE id = '%s'",$_POST['id']);
$result = mysql_query($sql, $link);

if (!$result) {
	die("IDの削除に失敗しました。");
	} else {
	echo ("投稿が削除されました。");
	}
} else{
	echo"パスワードが違います。";
}
// 接続を閉じる
 mysql_close($link);

?>

<html>
<head>
	<meta charset="utf-8">
	<title>掲示版</title>
	<link rel="stylesheet" href="stylepls.css">
</head>
<body class="all">
<div>
	<br />

<?php
	$url = "bbs.php?id=". $_POST['thread_id'];
	echo '<input type="button" value="前のページへ戻る" onClick="location.href=\'' .$url. '\'">';
?>

</div>

</body>
</html>

