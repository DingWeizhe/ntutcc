<?php $controller->css("market/market.css"); ?>
<?php $controller->css("market/post.css"); ?>
<?php $controller->script("market/upload_delay.js"); ?>
<?php $controller->script("market/readFile.js"); ?>
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
			<input type="file" name="thumbnail" class="readImg" />
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