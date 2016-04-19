<?php $controller->css("article/default.css"); ?>
<div id="article-read">
	<div class="left">
		<h2><?php echo $article['title'];?></h2>
		<div class="subtitle">
			<?php echo $article['club_id'] ? $article['club_name'] : $article['name'];?>
			|
			<?php echo date("Y-m-d H:i",strtotime($article['time']));?>
		</div>
		<div class="content"><?php echo $article['content'];?></div>
		<div class="fb-comments" data-href="http://ntut.cc/article/read/<?php echo $article['id'] ?>" data-width="600" data-numposts="10" data-colorscheme="light"></div>
	</div>
	<div class="right">

		<div id="button-list">
			<?php if ($article['user_id'] != @$user['id']){?>
			<a href="/article/post/">
				<div id="wrtie_article" class="button">
					<span>撰寫文章</span>
				</div>
			</a>
			<a href="/article/my/">
				<div id="wrtie_article" class="button">
					<span>我的文章</span>
				</div>
			</a>
			<?php } else {?>
			<a href="/article/edit/<?php echo $article['id'];?>">
				<div id="wrtie_article" class="button">
					<span>編輯文章</span>
				</div>
			</a>
			<a href="/article/delete/<?php echo $article['id'];?>">
				<div id="wrtie_article" class="button">
					<span>刪除文章</span>
				</div>
			</a>
			<?php } ?>
		</div>

		<div id="type-list">
			<div class="type">
				<h3>學生會</h3>
				<?php foreach($su_list as $article){ ?>
				<div class="item">
					<a href="/article/read/<?php echo $article['id'];?>"><div class="title"><?php echo $article['title'];?></div></a>
				</div>
				<?php } ?>
			</div>
			<div class="type">
				<h3>學生社團</h3>
				<?php foreach($other_club_list as $article){ ?>
				<div class="item">
					<a href="/article/read/<?php echo $article['id'];?>"><div class="title"><?php echo $article['title'];?></div></a>
				</div>
				<?php } ?>
			</div>
			<div class="type">
				<h3>個人發表</h3>
				<?php foreach($personal_list as $article){ ?>
				<div class="item">
					<a href="/article/read/<?php echo $article['id'];?>"><div class="title"><?php echo $article['title'];?></div></a>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<style>
		img{
			max-width: 600px;
		}
	</style>
	<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
</div>