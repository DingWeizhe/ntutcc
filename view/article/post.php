<?php $controller->Script("tinymce/tinymce.min.js"); ?>
<?php $controller->script("bootstrap-datepicker.js"); ?>
<?php $controller->script("bootstrap-timepicker.js"); ?>

<?php $controller->css("bootstrap-datepicker.css"); ?>
<?php $controller->css("bootstrap-timepicker.css"); ?>
<form method="POST">
	<input type="hidden" name="access_token" value="<?php echo $access_token;?>"/>
	<div class="row">
		<label for="title">文章標題</label>
		<input id="title" class="large" type="text" name="title" placeholder="文章標題" />
	</div>
	<?php if ($user['club_id']) {?>
	<div class="row">
		<label>發表身份</label>
		<input type="radio" name="club_id" id="personal" value="0" checked>
		<label for="personal"><?php echo $user['name'];?></label>
		<input type="radio" name="club_id" id="club" value="<?php echo $user['club_id'];?>">
		<label for="club"><?php echo $user['club_name'];?></label>
	</div>
	<?php } ?>
	<div class="row">
		<label>發文時間</label>
		<input type="text" name="date" />
		<input type="text" name="time" />
		<span class="tag">* 文章將會在指定的時間後才公開</span>
	</div>
	<textarea name="content"></textarea>
	<div class="row">
		<input type="submit" name="submit" value="確定發文"/>
	</div>
</form>