<?php $controller->script("jquery.autoellipsis-1.0.10.min.js"); ?>
<?php $controller->script("jquery.easing.js"); ?>
<?php $controller->css("article/default.css"); ?>
<?php $controller->css("article/slide.css"); ?>
<div id="article-slide">
	<div class="mask slide">
		<div class="news-list list">
		<?php foreach ($slide_list as $article){ ?>
			<?php preg_match('@<img.+src="(.*)".*>@Uims', $article['content'], $matches); ?>
			<a href="/article/read/<?php echo $article['id'];?>">
				<div class="item" style="background-image:url(<?php echo $matches[1];?>)">
					<div class="content">
						<div class="title"><?php echo $article['title'];?></div>
						<div class="description"><?php echo mb_substr(remover_html_tag($article['content']),0, 200,'utf-8');?></div>
					</div>
				</div>
			</a>
		<?php } ?>
		</div>
	</div>
	<div class="tag-list">
		<?php foreach ($slide_list as $article){ ?>
			<div class="item">
				<?php echo $article['title'];?>
			</div>
		<?php } ?>
	</div>
</div>
<div id="news-list" class="left">
	<?php foreach($list as $article){ ?>
	<div class="article item">
		<a href="/article/read/<?php echo $article['id'];?>">
			<h2 class="title"><?php echo $article['title'];?></h2>
			<div class="author">
				<?php echo $article['club_id'] ? $article['club_name'] : $article['name'];?> |
				<?php echo date('Y-m-d H:i', strtotime($article['time'])); ?>
			</div>
			<?php preg_match('@<img.+src="(.*)".*>@Uims', $article['content'], $matches); ?>
			<div class="picture" style="background-image: url(<?php echo $matches[1]; ?>)"></div>
			<div class="description">
			<?php echo mb_substr(remover_html_tag($article['content']),0, 300,'utf-8');?>
			</div>
		</a>
	</div>
	<?php } ?>
		<a class="more" href="/article/all/1">more</a>
	<div class="clr"></div>
</div>
<div class="right">
	<div id="button-list">
		<a href="/article/post/">
			<div id="wrtie_article" class="button">
				<span>發表文章</span>
			</div>
		</a>
		<a href="/article/my/">
			<div id="my" class="button">
				我的文章
			</div>
		</a>
	</div>
	<div id="type-list">
		<div class="type">
			<h3>學生會</h3>
			<?php foreach($su_list as $article){ ?>
			<div class="item">
				<a href="/article/read/<?php echo $article['id'];?>"><div class="title"><?php echo $article['title'];?></div></a>
			</div>
			<?php } ?>
			<a class="more" href="/article/su/1">more</a>
		</div>
		<div class="type">
			<h3>學生社團</h3>
			<?php foreach($other_club_list as $article){ ?>
			<div class="item">
				<a href="/article/read/<?php echo $article['id'];?>"><div class="title"><?php echo $article['title'];?></div></a>
			</div>
			<?php } ?>
			<a class="more" href="/article/club/1">more</a>
		</div>
		<div class="type">
			<h3>個人發表</h3>
			<?php foreach($personal_list as $article){ ?>
			<div class="item">
				<a href="/article/read/<?php echo $article['id'];?>"><div class="title"><?php echo $article['title'];?></div></a>
			</div>
			<?php } ?>
			<a class="more" href="/article/personal/1">more</a>
		</div>
	</div>
</div>
<div class="clr"></div>