<?php
//データベースに接続
$link = mysql_connect('localhost', 'user', 'pw');
if (!$link) {
 die('データベースに接続できません：' . mysql_error());
}

//データベースを選択する
mysql_select_db('oneline_bbs', $link);

//スレッド取得
$sql ="SELECT * FROM threads order by created_at desc";
$result = mysql_query($sql,$link);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta charset="utf-8">
<title>スレッド一覧</title>
<link rel="stylesheet" href="stylepls.css">
</head>
<body class="all">
 <div style="cursor: pointer; " onclick="location.href='index.php'" title="ホームへ">
 <h1 class="midashi">Oneline BBS</h1>
 </div>

 <a class="supMenu" href="create_thread.php">スレッド作成</a>

 <h1 class="thread_midashi">スレッド一覧</h1>

<table class="thread_list">
	<?php
		while ($thread = mysql_fetch_array($result)):?>
		<tr><td><a href="bbs.php?id=<?php echo $thread['id'];?>" class="threadlink">
		<?php echo $thread['title'];?></a></td>
		<td> 作成日時：<?php echo $thread['created_at'];?></td></tr>
	<?php endwhile;?>
</table>

<footer class="pagefoot">
    <p class="copyright"><small>Copyright&copy; 2016 @SuguruNishimura All Rights Reserved.
    </small>
    </p>
</footer>
</body>
</html>
