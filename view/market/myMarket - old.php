<?php $controller->css("market/myMarket.css"); ?>
<?php $controller->script("jquery.lazyload.min.js"); ?>
<?php $controller->script("market/market.js"); ?>
<?php $controller->script("market/tag.js"); ?>
<?php $controller->css("market/post.css"); ?>
<?php $controller->script("market/upload_delay.js"); ?>
<?php $controller->script("market/readFile.js"); ?>
<div id="mark">
	<div class="tag">
		<ul class="clearfix">
			<li id="selected"><a href="#market">我的商品</a></li>
			<li><a href="#post">刊登</a></li>
			<li><a href="#edit">修改</a></li>
			<li><a href="#remove">移除</a></li>
		</ul>
	</div>
	<div class="content-inner">
		<?php if ($market): ?>
			<?php foreach ($market as $commodity): ?>
			<div class="item <?php echo $commodity['status'] ?>">
				<a href="/market/detail/<?php echo $commodity['id'] ?>">
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
	<div class="content-inner">
		<div id="post">
			<form method="POST" enctype="multipart/form-data" action="/market/create/">
				<div class="row">
					<label>商品名稱</label>
				</div>
				<div class="row">
					<input type="text" name="name" placeholder="商品名稱">
				</div>
				<div class="row">
					<label>價格</label>
				</div>
				<div class="row">
					<input type="text" name="value" placeholder="價格">
				</div>
				<div class="row">
					<label>類型</label>
				</div>
				<div class="row">
					<select name="type">
						<option value="book">書籍</option>
						<option value="3C">3C產品</option>
						<option value="clothes">衣服</option>
						<option value="other">其它</option>
					</select>
				</div>
				<div class="row">
					<label>產品圖片</label>
				</div>
				<div class="row">
					<input type="file" name="thumbnail" class="readImg">
				</div>
				<div class="row">
					<label>商品描述</label>
				</div>
				<div class="row">
					<textarea name="description"></textarea>
				</div>
				<div class="row">
					<input type="submit" class="submit" value="發佈商品">
				</div>
			</form>
		</div>
	</div>
	<div class="content-inner">
		<?php if ($market): ?>
			<?php foreach ($market as $commodity): ?>
			<div class="item <?php echo $commodity['status'] ?>">
				<a href="/market/edit/<?php echo $commodity['id'] ?>">
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
	<div class="content-inner">
		<?php if ($market): ?>
			<form method="GET" action="/market/remove">
				<div class="clearfix">
					<?php foreach ($market as $commodity): ?>
					<div class="item <?php echo $commodity['status'] ?>">
						<div class="item-inner removeItem">
							<div class="img"><img class="lazy" src="/imgs/market/<?php echo $commodity['thumbnail'] ?>" alt="<?php echo $commodity['thumbnail'] ?>"></div>
							<div class="title"><?php echo $commodity['name'] ?></div>
						</div>
						<input type="checkbox" name="remove[]" class="remove" value="<?php echo $commodity['id'] ?>">
					</div>
					<?php endforeach ?>
				</div>
				<div class="row">
					<input type="submit" class="submit" id="remove-selected" value="刪除">
				</div>
			</form>
		<?php else: ?>
			<h3>沒有可以讓你刪的東西唷（&lt;ゝω・）</h3>
		<?php endif ?>
	</div>
</div>