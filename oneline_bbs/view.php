 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<script type="text/javascript" src="jquery/jquery-3.1.1.min.js"></script>
<head>
<meta charset="utf-8">
 <title><?php echo $row_title[0];?></title>
 <link rel="stylesheet" href="stylepls.css">
</head>
<body class="all">
 <div style="cursor: pointer; " onclick="location.href='index.php'" title="ホームへ">
 <h1 class="midashi">Oneline BBS</h1>
 </div><br /><br />
 <div class="title_position">
  <h1 class="thread_title"><?php echo $row_title[0]; ?></h1>
 </div>

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

<div class="postmenu">

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
  <input type="submit" class="send" value="send" onclick="location.reload();">

  </form>

</div>

<?php if (count($posts) > 0): ?>
  <ol>
    <?php foreach ($posts as $post): ?>
    <li class="comment">
    名前：<br>
      <?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?> <p></p>
    内容：<br />

      <?php // 画像を出力
      if(isset($post['image'])){
  print("<img data-original=\"imageview.php?imgid=" . $post['id'] . "\">");
} else {}

   ?><br>
      <?php $text  = htmlspecialchars($post['comment'], ENT_QUOTES, 'UTF-8');

      //改行処理
      $text = nl2br($text);

      print $text; ?> <p></p>
      投稿日時 <?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?>
    <form action="delete.php" method="post">
      <?php echo '<input type="hidden" name="thread_id" value="'. $id .'">'; ?>
      <?php echo '<input type="hidden" name="id" value="' . $post["id"] . '">'; ?>

          <br />
          <input type="password" name="postdeletekey" size="4", maxlength="4">
          <button class="delete" type="submit" value="delete">パスワードを入力して削除</button>
      </form>

    <div class="reply">
      <form action="reply.php" method="GET">
       <?php echo '<input type="hidden" name="id" value="' . $post["id"] . '">'; ?>
        <?php echo '<input type="hidden" name="thread_id" value="'. $id .'">'; ?>
      <button type="submit"><img src="bg/reply.png">reply</button>
      </form>
    </div>
</li>
    <ol style="list-style-type: none">
        
    <?php
//返信を格納
$sql_getreply = sprintf("select * from `reply` where `srcid`='%d'", $post['id']);
    $replyresult = mysql_query($sql_getreply, $link);
    $row = mysql_fetch_assoc($replyresult);
    if (isset($row)) {
    while ($row = mysql_fetch_assoc($replyresult)) {
    ?>
    <li class="replylist" >
    名前：
    <?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?> <br />
    返信内容：
    <?php // 画像を出力
      if(isset($row['image'])){ ?>
      <br />
      <?php
  print("<img data-original=\"imageviewfromreply.php?imgid=" . $row['id'] . "\">");
} else {}

   ?> <br>
    <?php echo htmlspecialchars($row['comment'], ENT_QUOTES, 'UTF-8'); ?> <br/>
    投稿日時：
    <?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?> <br />
    </li><?php
    }
    } else {}
    ?>

  </ol>

  </li>
<?php endforeach; ?>
</ol>
<?php endif; ?>

<footer class="pagefoot">
    <p class="copyright"><small>Copyright&copy; 2016 @SuguruNishimura All Rights Reserved.
    </small>
    </p>
</footer>


<!-- LazyLoadの設定 -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="jquery/jquery.lazyload.js"></script>
<script>
$( function()
{
	$( 'img' ).lazyload(
        {
            threshold: 1,			// 1pxの距離まで近づいたら表示する
	        effect: "fadeIn",		// じわじわっと表示させる
    	    effect_speed: 2000 ,		// 2秒かけて表示させる
        }
    ) ;
} ) ;
</script>

</body>
</html>
