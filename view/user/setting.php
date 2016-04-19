<?php $controller->script("bootstrap-datepicker.js"); ?>
<?php $controller->css("bootstrap-datepicker.css"); ?>
<?php $controller->css("baseform.css"); ?>
<form method="POST" class="setting">
	<hr/>
	<input type="hidden" name="access_token" value="<?php echo $access_token;?>">
	<div class="row">
		<img class="picture" src="https://graph.facebook.com/<?php echo $user['fb_id'];?>/picture?width=200&height=200">
	</div>
	<div class="row">
			<label>認證</label>
			<?php if ($user['authenticate']){?>
				<div class="authenticate">已認證</div>
			<?php } else { ?>
				<div class="unauthenticate authenticate">未認證</div>
				<a href="authenticate">立即認證</a>
			<?php } ?>
	</div>
	<div class="row">
		<label>稱呼</label>
		<input type="text" name="name" placeholder="暱稱" value="<?php echo $user['name'];?>">
	</div>
	<div class="row">
		<label>狀態</label>
		<input type="text" name="status" placeholder="葉子的離開，是因為風的追求，還是樹的不挽留。" value="<?php echo $user['status'];?>">
	</div>
	<div class="row">
		<label>學號</label>
		<input type="text" name="student_id" placeholder="學號" value="<?php echo $user['student_id'];?>" <?php if ($user['authenticate']) echo "disabled"; ?>>
	</div>
	<div class="row">
		<label>科系</label>
		<select name="department">
			<?php foreach ($departments as $department){ ?>
				<option value="<?php echo $department['id']; ?>" <?php if ($department['id'] == $user['department_id']) echo 'selected="selectd"'; ?>>
					<?php echo $department['name'];?>
				</option>
			<?php } ?>
		</select>
	</div>
	<div class="row">
		<label>生日</label>
		<input type="text" name="birthday" placeholder="生日" autocomplete="off" value="<?php echo $user['birthday'];?>">
	</div>
<?php if ($user['authenticate']){ ?>
	<div class="row">
		<label>公開課表</label>
		<input type="radio" id="publish_course_on" name="publishCourse" value="1" <?php if ($user['publishCourse']) echo 'checked'; ?>> <label for="publish_course_on">公開</label>
		<input type="radio" id="publish_course_off" name="publishCourse" value="0" <?php if (!$user['publishCourse']) echo 'checked'; ?> > <label for="publish_course_off">隱藏</label>

	</div>
	<div class="row">
		<label>公開FB</label>
		<input type="radio" id="publish_fb_on" name="publishFB" value="1" <?php if ($user['publishFB']) echo 'checked'; ?> > <label for="publish_fb_on">公開</label>
		<input type="radio" id="publish_fb_off" name="publishFB" value="0" <?php if (!$user['publishFB']) echo 'checked'; ?> > <label for="publish_fb_off">隱藏</label>
	</div>
	<div class="row">
		<label>顯示六日</label>
		<input type="radio" id="display_weekend_on" name="displayWeekend" value="1" <?php if ($user['displayWeekend']) echo 'checked'; ?> > <label for="display_weekend_on">顯示</label>
		<input type="radio" id="display_weekend_off" name="displayWeekend" value="0" <?php if (!$user['displayWeekend']) echo 'checked'; ?> > <label for="display_weekend_off">隱藏</label>
	</div>

	<div class="row">
		<label>重抓課表資料</label>
		<a class="recrawler" href="/user/recrawler"><img src="/imgs/sat_dish_icon&16.png"/></a>
	</div>
<?php } ?>
	<hr/>
	<div class="center">
		<input type="submit" class="submit" value="修改資料">
	</div>
</form>