$(document).ready(function() {
	$('.reply-button').click(createReplyText);
})

function createReplyText (e) {
	if (!e) e = event;
	var reply_text = $("<input type='type' name='reply' class='reply-text' autofocus /><input type='submit' class='reply-submit' value='回覆'>");
	$(this).parent().html(reply_text);
	console.log($(this), $(this).parent());
	e.preventDefault();
	return false;
}