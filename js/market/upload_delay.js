var upload_message = $("<div id='message-box'></div>"),
	upload_light_box = $("<div id='light-box'><div class=wrap></div></div>");

$(document).ready(function () {
	var img = $('<img>');
	img.attr("src", "/imgs/waiting.gif");
	upload_message.html(img).css("backgroundColor", "transparent");
	upload_light_box.find('.wrap').append(upload_message);
	upload_light_box.hide().appendTo($('body'));
	$('form').submit(upload_delay);
});

function upload_delay() {
	upload_light_box.show();
}