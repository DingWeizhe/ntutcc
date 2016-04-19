<?php $controller->css("market/myMarket.css"); ?>
<?php $controller->css("market/post.css"); ?>
<?php $controller->script("market/upload_delay.js"); ?>
<?php $controller->script("market/readFile.js"); ?>
<div id="post">
	<form method="POST" enctype="multipart/form-data" action="/market/edit/<?php echo $commodity['market_id'] ?>">
		<div class="row">
			<label>商品名稱</label>
		</div>
		<div class="row">
			<input type="text" name="name" placeholder="商品名稱" value="<?php echo $commodity['market_name'] ?>">
		</div>
		<div class="row">
			<label>價格</label>
		</div>
		<div class="row">
			<input type="text" name="value" placeholder="價格" value="<?php echo $commodity['value'] ?>">
		</div>
		<div class="row">
			<label>類型</label>
		</div>
		<div class="row">
			<select name="type">
				<option value="book" <?php if ($commodity['type'] == 'book') echo "selected=selected"; ?> >書籍</option>
				<option value="3C" <?php if ($commodity['type'] == '3C') echo "selected=selected"; ?>>3C產品</option>
				<option value="clothes" <?php if ($commodity['type'] == 'clothes') echo "selected=selected"; ?>>衣服</option>
				<option value="other" <?php if ($commodity['type'] == 'other') echo "selected=selected"; ?>>其它</option>
			</select>
		</div>
		<div class="row">
			<label>狀態</label>
		</div>
		<div class="row">
			<input type="radio" id="sell" value="sell" name="status" <?php if ($commodity['market_status'] == 'sell') echo "checked"; ?>>
			<label for="sell">販賣中</label>
			<input type="radio" id="sold" value="sold" name="status" <?php if ($commodity['market_status'] == 'sold') echo "checked"; ?>>
			<label for="sold">已賣出</label>
			<input type="radio" id="cancel" value="cancel" name="status" <?php if ($commodity['market_status'] == 'cancel') echo "checked"; ?>>
			<label for="cancel">取消</label>
		</div>
		<div class="row">
			<label>產品圖片</label>
		</div>
		<div class="row">
			<img src="/imgs/market/<?php echo $commodity['thumbnail'] ?>" alt="<?php echo $commodity['thumbnail'] ?>" class="thumbnail imgShow">
		</div>
		<div class="row">
			 <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
			<input type="file" name="thumbnail" class="readImg">
		</div>
		<div class="row">
			<label>商品描述</label>
		</div>
		<div class="row">
			<textarea name="description"><?php echo $commodity['description'] ?></textarea>
		</div>
		<div class="row">
			<input type="submit" class="submit" value="修改商品">
		</div>
	</form>
</div>