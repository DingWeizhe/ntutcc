
<form method="POST" class="authenticate">
	<h3>你目前尚未認證為北科大學生</h3>
	<h4>請輸入你的學校信箱，我們將會寄送認證信件進行認證。</h4>
	<hr/>
	<input type="hidden" name="access_token" value="<?php echo $access_token;?>">

	<div class="input-group input-group-lg">
	  <span class="input-group-addon">t</span>
	  <input type="text" name="student_id" class="form-control" placeholder="學號">
	  <span class="input-group-addon">@ntut.edu.tw</span>
	</div>
	<hr/>
	<div class="center">
		<input type="submit" class="submit" value="寄出驗信件">
	</div>
</form>