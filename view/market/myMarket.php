<?php $controller->css("market/myMarket.css"); ?>
<?php $controller->script("jquery.lazyload.min.js"); ?>
<?php $controller->script("market/market.js"); ?>
<?php $controller->css("market/post.css"); ?>
<?php $controller->script("market/upload_delay.js"); ?>
<?php $controller->script("market/readFile.js"); ?>
<div id="mark">
	<div class="content-inner">
		<?php if ($market): ?>
			<?php foreach ($market as $commodity): ?>
			<div class="item <?php echo $commodity['status'] ?> <?php if ($commodity['comment_count']) echo "hit"; ?>">
				<a href="/market/detail/<?php echo $commodity['id'] ?><?php if ($commodity['comment_count']) echo "#msg"; ?>">
					<div class="item-inner">
						<div class="img"><img class="lazy" data-original="/imgs/market/<?php echo $commodity['thumbnail'] ?>" src="/imgs/market/<?php echo $commodity['thumbnail'] ?>" alt="<?php echo $commodity['thumbnail'] ?>"></div>
						<div class="title"><?php echo $commodity['name'] ?></div>
					</div>
				</a>
			</div>
			<?php endforeach ?>
		<?php else: ?>
			<h3>目前沒有商品唷</h3>
		<?php endif ?>
	</div>
</div>