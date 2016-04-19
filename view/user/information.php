<div id="user-information">
	<img class="picture" src="https://graph.facebook.com/<?php echo $read_user['fb_id'];?>/picture?width=200&height=200"/>
	<?php if ($read_user['name'] != ""){?>
	<div class="name">
		<?php echo $read_user['name'];?>

		<?php if (@$read_user['publishFB']){ ?>
			<a href='https://facebook.com/<?php echo $read_user['fb_id'];?>' class="facebook"><span>Facebook</span></a>
		<?php }?>
	</div>
	<div class="major"><?php echo $read_user['department_name'];?></div>
	<?php if ($read_user['club_id']){ ?>
			<div class="club"><?php echo $read_user['club_name'];?></div>
	<?php } ?>
	<?php } else { ?>
	<div class="tag">這個是你嗎? </div>
	<div class="tag">趕快<a href="/user/login">綁定FB</a>獲得更良好的服務品質吧!</div>
	<?php } ?>
</div>