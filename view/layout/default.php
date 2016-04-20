<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#">
	<head>
		<meta charset="UTF-8"/>
    	<meta property="fb:admins" content="1594166637" />
	    <meta property="fb:app_id" content="796877767004504" />
		<title>NTUT.cc : 國立臺北科技大學</title>
		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script src="/js/layout/default.js"></script>
		<link type="text/css" rel="stylesheet" href="/style/index.css"/>
		<link href="/style/layout/default.css?id=1" media="screen, projection" rel="stylesheet" type="text/css" />
		<!-- Latest compiled and minified CSS -->
		<meta content="NTUT模擬選課系統" name="description">
		<meta content="NTUT模擬選課系統" property="og:description">
		<meta name="viewport" content="width=device-width"/>
		<meta name="viewport" content="initial-scale=1.0"/>

		<?php foreach ($controller->script as $script){ ?>
			<script type="text/javascript"  charset="UTF-8" src="/js/<?php echo $script;?>"></script>
		<?php } ?>
		<?php foreach ($controller->css as $css){ ?>
			<link type="text/css" rel="stylesheet" href="/style/<?php echo $css;?>"/>
		<?php } ?>
	</head>
	<body>
		<div id="header">
			<div class="logo">
				<a href="/">
					<h1>NTUT.cc</h1>
					<h2>北科零時校園</h2>
				</a>
			</div>
			<div id="user">
				<?php if ($controller->user){ ?>
				<div class="user">
					<a href="/user/"><?php echo $controller->user['name']; ?></a>

				</div>
				<div class="user phone-remover<?php if(@$hit) echo "hit"; ?>"><a href="/market/myMarket">我的市集</a></div>
				<div class="user phone-remover"><a href="/user/setting">設定</a></div>
				<?php } else { ?>
				<div class="fb-login"><a href="/user/login">以FB帳號登入</a></div>
				<?php }?>
			</div>
			<form action="/search" class="search" accept-charset="utf-8" method="POST">
				<input type="search" name="keyword" placeholder="輸入學號查詢課表">
			</form>

		</div>
		<div id="navigation">
			<ul>
				<a href="/user/"><li>美化課表</li></a>
				<a href="/user/simulator" class="phone-remover"><li>模擬選課</li></a>
				<a href="/market"><li>北科市集</li></a>
				<a href="/article"><li>我愛我報</li></a>
				<a href="/user/wall"><li>正妹型男</li></a>
				<li>
					相關連結
					<ul>
						<li><a href="http://ntut.edu.tw/">北科大</a></li>
						<li><a href="https://nportal.ntut.edu.tw/index.do">入口網站</a></li>
						<li><a href="http://www.cc.ntut.edu.tw/~ntutsu/">學生會</a></li>
						<li><a href="http://ntutsc.weebly.com/">學生議會</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div id="content">
			<?php echo $content;?>
		</div>
		<div id="footer">

			<div>
			© 2014 by D.Weizhe & kw-今天的風兒有點喧囂, 創意發想 by NTUST.cc
			</div>
		</div>
		<?php if (isset($message)){ ?>
		<div id="light-box">
			<div class="wrap">
				<div id="message-box">
						<?php echo $message;?>
				</div>
			</div>
		</div>
		<?php } ?>

		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-48396074-1', 'ntut.cc');
			ga('send', 'pageview');
		</script>
	</body>

</html>
