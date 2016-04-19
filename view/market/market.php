<?php $controller->css("market/market.css"); ?>
<?php $controller->script("market/market.js"); ?>
<?php $controller->script("jquery.lazyload.min.js"); ?>
<div id="mark">
	<div class="clearfix toolbar">
		<div class="leftSide market-search clearfix">
			<form method="GET" accept-charset="utf-8" action="/search/market">
				<input type="text" name="keyword" placeholder="商品關鍵字" value="<?php echo @$keyword ?>">
				<input type="submit" value="找商品">
			</form>
		</div>
		<div class="rightSide tools">
			<a href="/market/post">刊登商品</a>
			<a href="/market/myMarket">我的市集</a>
		</div>
	</div>
	<div class="clearfix">
		<?php foreach ($market as $commodity): ?>
		<div class="item <?php echo $commodity['status'] ?>">
			<a href="/market/detail/<?php echo $commodity['id'] ?>">
				<div class="item-inner">
					<div class="img" style="background-image:url(/imgs/market/<?php echo $commodity['thumbnail'] ?>);"></div>
					<div class="date"><?php echo $commodity['soldDate'] ?></div>
					<div class="title"><?php echo $commodity['name'] ?></div>
				</div>
			</a>
		</div>
		<?php endforeach ?>
	</div>
	<?php if (@$page_count): ?>
	<div class="clearfix page">
		<ul>
			<?php if ($page != 1): ?>
			<li class="first"><a href="/market/">«</a></li>
			<li><a href="/market/<?php echo $page-1 ?>">‹</a></li>
			<?php endif ?>
			<?php for ($i = 1; $i < $page_count + 1; $i++): ?>
			<li id='<?php if($page == $i) echo "selected"; ?>' ><a href="/market/<?php echo $i ?>"><?php echo $i ?></a></li>
			<?php endfor; ?>
			<?php if ($page != $page_count): ?>
			<li><a href="/market/<?php echo $page+1 ?>">›</a></li>
			<li class="last"><a href="/market/<?php echo $page_count ?>">»</a></li>
			<?php endif ?>
		</ul>
	</div>
	<?php endif ?>
</div>