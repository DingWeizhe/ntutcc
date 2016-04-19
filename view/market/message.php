<div id="message">
	<fb:comments href="{$URI}/angel/read/{$angel['id']}" width="680"></fb:comments>
	<?php foreach ($msgs as $msg): ?>
	<div class="msg-box clearfix">
		<div class="person">
			<img src="/imgs/no-img.png" class="picture" alt="">
			<div class="name"><a href="/user/<?php echo $msg['student_id'] ?>"><?php echo $msg['name'] ?></a></div>
			<div class="date"><?php echo $msg['msg_time'] ?></div>
		</div>
		<div class="msg">
			<p><?php echo $msg['msg'] ?></p>
			<?php foreach ($msg['reply'] as $reply): ?>
			<div class="reply clearfix">
				<div class="reply-person">
					<!-- <img src="/imgs/no-img.png" alt="" class="reply-pic"> -->
					<div class="reply-name"><a href="/user/<?php echo $reply['student_id'] ?>"><?php echo $reply['name'] ?></a>：</div>
				</div>
				<div class="reply-msg">
					<p><?php echo $reply['reply'] ?></p>
				</div>
			</div>
			<?php endforeach ?>
			<div class="reply clearfix">
				<form method="POST" action="/market/addReply/<?php echo $market_id ?>">
					<input type="hidden" name="msg_id" value="<?php echo $msg['message_id'] ?>">
					<div>
						<a href="#" class="reply-button">reply</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php endforeach ?>
</div>