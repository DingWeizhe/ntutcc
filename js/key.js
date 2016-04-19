var keyQueue = [];
var count = 0;

$(document).ready(function (){
	$(window).on('keydown', checkAnsKey);
})

function checkAnsKey (e) {
	if (count > 200) return;
	var ans_key = [38, 38, 40, 40, 37, 37, 39, 39, 65, 66, 65, 66];
	if (!e) {e = event};
	keyQueue.push(e.which);
	if (arraysEqual(keyQueue, ans_key)) {
		$('body').css({
			position: "fixed",
			height: "100%",
			width: "100%"
		});
		var timer = setInterval(function () {
			count++;
			showImg();
			if (count > 200) {
				clearTimeout(timer)
				var light_box = $("<div id='light-box'><div class=wrap></div></div>"),
					message = $("<div id='message-box'></div>");

				message.html("哈哈哈哈～你看看你(σ′▽‵)′▽‵)σ");
				light_box.appendTo($('body'));
				light_box.find('.wrap').append(message);
			};
		}, 5);
	};
}

function arraysEqual(a, b) {
	if (a === b) return true;
	if (a == null || b == null) return false;

	for (var i = 0; i < a.length; ++i) {
		if (a[i] !== b[i]) {
			keyQueue = [];
			return false;
		}
	}

	if (a.length != b.length) return false;
	return true;
}

function showImg () {
	var error = $('<div>');
	error
		.append($('<img>').attr('src', '/imgs/error.png'))
		.css({
			position: "absolute",
			top: random(-100, $('body').height()),
			left: random(-100, $('body').width())
		});
	$('body').append(error);
}

function random(min, max) {
	return (Math.random() * (max - min)) + min;
}