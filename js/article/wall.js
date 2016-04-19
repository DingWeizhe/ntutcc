$(function(){
	$("#article-slide .tag-list .item").on('click', function(){
		$("#article-slide .tag-list .item.selected").removeClass("selected");
		$(this).addClass("selected");
		$("#article-slide .mask.slide .list").animate({
			top: $("#article-slide .tag-list .item").index($(this)) * -300
		}, 1000, 'easeOutQuart');
	})[0].click();
//	$("#news-slide .mask.slide .list").width($("#news-slide .mask.slide .list .item").length * 700);
	//$('.news-list .description').ellipsis();

})