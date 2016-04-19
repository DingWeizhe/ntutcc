var timeout_id = 0;
$(function(){
	$("#article-slide .list").css({width: $("#article-slide .list .item").length * 960});
	$("#article-slide .next.arrow").on('click', function(){
		if (parseInt($("#article-slide .list").css('left')) > -1*($("#article-slide .list .item").length - 1) * 960){
			$("#article-slide .list").animate({left : '-=960'}, 1000, "easeOutQuart");
		}
		clearTimeout(timeout_id);
		timeout_id = setTimeout(slide, 5000);
	});
	$("#article-slide .previous.arrow").on('click', function(){
		if (parseInt($("#article-slide .list").css('left')) < 0){
			$("#article-slide .list").animate({left : '+=960'}, 1000, "easeOutQuart");
		}
		clearTimeout(timeout_id);
		timeout_id = setTimeout(slide, 5000);
	});
	timeout_id = setTimeout(slide, 5000);
})
function slide(){
	if (parseInt($("#article-slide .list").css('left')) > -1*($("#article-slide .list .item").length - 1) * 960){
		$("#article-slide .list").animate({left : '-=960'},1000,"easeOutQuart");
	} else {
		$("#article-slide .list").animate({left : 0},1000,"easeOutQuart");
	}
	timeout_id = setTimeout(slide, 5000);
}