<?php $controller->Script("tinymce/tinymce.min.js"); ?>
<?php $controller->script("bootstrap-datepicker.js"); ?>
<?php $controller->script("bootstrap-timepicker.js"); ?>
<?php $controller->script("/article/post.js");?>
<?php $controller->css("bootstrap-datepicker.css"); ?>
<?php $controller->css("bootstrap-timepicker.css"); ?>
<?php $controller->css("article/post.css"); ?>
<form method="POST">
	<input type="hidden" name="access_token" value="<?php echo $access_token;?>"/>
	<div class="row">
		<label>文章標題</label>
		<input class="large" type="text" name="title" value="<?php echo $article['title'];?>"/>
	</div>

	<div class="row">
		<label>發文時間</label>
		<input type="text" name="date" value="<?php echo date("Y-m-d",strtotime($article['time']));?>" />
		<input type="text" name="time" value="<?php echo date("H:i:s",strtotime($article['time']));?>" />
		<span class="tag">* 文章將會在指定的時間後才公開</span>
	</div>
	<textarea name="content"><?php echo $article['content'];?></textarea>
	<div class="row">
		<input type="submit" name="submit" value="確定編輯"/>
	</div>
</form>
<script>
</script>
