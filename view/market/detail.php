<?php
	$controller->script('jquery.easing.js');
	$controller->script('jquery.lazyload.min.js');
	$controller->script('market/detail.scroll.js');
	$controller->css('market/market.css');
	$controller->css('market/detail.css');
?>
<div id="mark">
	<h3 class="clearfix">
		<div class="rightSide toolbar">
			<a href="#msg" class="msg">留言</a>
			<?php if (@$user['authenticate'] && $user['id'] == $commodity['user_id']): ?>
				<a href="/market/edit/<?php echo $commodity['market_id'] ?>" class="edit">修改</a>
				<a href="/market/removeById/<?php echo $commodity['market_id'] ?>" class="remove">刪除</a>
			<?php endif ?>
		</div>
		<div class="leftSide title"><?php echo $commodity['market_name'] ?></div>
	</h3>
	<div class="clearfix">
		<div class="rightSide">
			<div class="leftSide item-list">
				<ul>
					<li><img class="lazy" data-original="/imgs/market/<?php echo $commodity['thumbnail'] ?>"></li>
				</ul>
			</div>
		</div>
		<div class="leftSide content-inner">
			<dl>
				<dt>賣家</dt>
				<dd><a href="/user/<?php echo $commodity['student_id'] ?>"><?php echo $commodity['user_name'] ?></a></dd>
				<dt>價格</dt>
				<dd><?php echo $commodity['value'] ?>NT</dd>
				<dt>分類</dt>
				<dd><?php echo $commodity['type'] ?></dd>
				<dt>開始販售時間</dt>
				<dd><?php echo $commodity['soldDate'] ?></dd>
				<dt>介紹</dt>
				<dd><?php echo nl2br($commodity['description']) ?></dd>
			</dl>
		</div>
	</div>
	<div class="clearfix" id="msg">
		<div class="fb-comments" data-href="http://ntut.cc/market/detail/<?php echo $commodity['market_id'] ?>" data-width="960" data-numposts="10" data-colorscheme="light"></div>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	</div>
</div>