$(document).ready(function() {
	$(".sliderbar li").on('click', scrollImg);
	$("#pageTop").on('click', pageTop);
	$(window).on('scroll', pageTopShow);
	$('img.lazy').lazyload ({
		effect: "fadeIn"
	});
})

function scrollImg () {
	var slider = $('.sliderbar li'),
		idx = slider.index(this),
		item = $('.item-list li');

	item.removeAttr('id');
	$('html, body').animate({
		scrollTop: item.eq(idx).offset().top - 10
	}, 1000, 'easeInOutQuint');
}

function pageTop () {
	$('html, body').animate({
		scrollTop: 0
	}, 1000, 'easeInOutQuint');
}

function pageTopShow () {
	var item = $('.item-list li');
	if ($(this).scrollTop() > item.eq(0).offset().top) {
		$('#pageTop').fadeIn();
	} else {
		$('#pageTop').fadeOut();
	}
}