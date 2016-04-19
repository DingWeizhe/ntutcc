if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(elt /*, from*/) {
		var len = this.length >>> 0;

		var from = Number(arguments[1]) || 0;
		from = (from < 0) ? Math.ceil(from) : Math.floor(from);
		if (from < 0) from += len;

		for (; from < len; from++) {
			if (from in this &&	this[from] === elt) return from;
		}
		return -1;
	};
}

$(document).ready(function () {
	var tag = ['market', 'post', 'edit', 'remove'];
	var test = tag.indexOf('market');
	var idx = tag.indexOf(window.location.hash.substr(1));
	if (idx == -1) idx = 0;
	$('.content-inner').hide();
	$('.tag li').on("click", changeTag).eq(idx).click();
});

function changeTag () {
	var idx = $('.tag li').index($(this));
	$('.content-inner')
		.hide()
		.eq(idx).show();
	$('.tag li').removeAttr('id');
	$(this).attr('id', 'selected');
}