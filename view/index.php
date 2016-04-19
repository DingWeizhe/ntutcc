<!DOCTYPE html>
<meta charset="UTF-8">
<?php $controller->css("article/slide.css"); ?>
<?php $controller->script("jquery.easing.js"); ?>
<div id="article-slide">
	<div class="mask slide">
		<div class="news-list list">
		<?php foreach ($articles as $article){ ?>
			<a href="/article/read/<?php echo $article['id'];?>">
				<?php preg_match('@<img.+src="(.*)".*>@Uims', $article['content'], $matches); ?>
				<div class="item" style="background-image:url(<?php echo $matches[1];?>)">
					
				</div>
			</a>
		<?php } ?>
		</div>
	</div>
	<div class="previous arrow"></div>
	<div class="next arrow"></div>
</div>
<div id="wall">
	<h1>正妹型男</h1>
	<div class="group1 group">
		<a href="/user/<?php echo $walluser[0]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[0]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[0]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[1]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[1]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[1]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[2]['student_id'];?>"><div class="picture lg " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[2]['fb_id'];?>/picture?width=230&height=230)"><div class="mask"></div><div class="name"><?php echo $walluser[2]['name'];?></div></div></a>
	</div>
	<div class="group2 group">
		<a href="/user/<?php echo $walluser[3]['student_id'];?>"><div class="picture lg " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[3]['fb_id'];?>/picture?width=230&height=230)"><div class="mask"></div><div class="name"><?php echo $walluser[3]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[4]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[4]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[4]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[5]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[5]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[5]['name'];?></div></div></a>
	</div>
	<div class="group3 group">
		<a href="/user/<?php echo $walluser[6]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[6]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[6]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[7]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[7]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[7]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[8]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[8]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[8]['name'];?></div></div></a>
	</div>
	<div class="group1 group">
		<a href="/user/<?php echo $walluser[9]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[9]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[9]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[10]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[10]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[10]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[11]['student_id'];?>"><div class="picture lg " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[11]['fb_id'];?>/picture?width=230&height=230)"><div class="mask"></div><div class="name"><?php echo $walluser[11]['name'];?></div></div></a>
	</div>
	<div class="group3 group">
		<a href="/user/<?php echo $walluser[12]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[12]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[12]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[13]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[13]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[13]['name'];?></div></div></a>
		<a href="/user/<?php echo $walluser[14]['student_id'];?>"><div class="picture sm " style="background-image:url(https://graph.facebook.com/<?php echo $walluser[14]['fb_id'];?>/picture?width=110&height=110)"><div class="mask"></div><div class="name"><?php echo $walluser[14]['name'];?></div></div></a>
	</div>
</div>