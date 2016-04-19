$(document).ready(function () {
	$('img.lazy').lazyload ({
		effect: "fadeIn"
	});
	$('.removeItem').on('click', removeSelected);
})

function removeSelected () {
	var idx = $('.removeItem').index($(this));
	$('.remove').eq(idx).click();
}