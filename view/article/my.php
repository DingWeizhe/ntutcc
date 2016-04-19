<?php $controller->css("article/default.css"); ?>
<?php foreach($list as $article){ ?>
<div class="article item">
	<a href="/article/read/<?php echo $article['id'];?>">
		<h2 class="title"><?php echo $article['title'];?></h2>
	</a>
	<a class="button" href="/article/edit/<?php echo $article['id'];?>">編輯</a> |
	<a class="button" href="/article/delete/<?php echo $article['id']?>">刪除</a>
	<div class="author"><?php echo $article['name'];?> | <?php echo date('Y-m-d H:i', strtotime($article['time'])); ?></div>
	<?php preg_match('@<img.+src="(.*)".*>@Uims', $article['content'], $matches); ?>
	<div class="picture" style="background-image: url(<?php echo $matches[1]; ?>)"></div>
	<div class="description">
	<?php echo mb_substr(remover_html_tag($article['content']),0, 300,'utf-8');?>
	</div>
</div>
<?php } ?>
