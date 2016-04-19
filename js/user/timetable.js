$(function(){
	$("#timetable .course .delete").on('click', deleteVirtualCourse);

	$(".course").on('click', readCourseDetail);
})
function deleteVirtualCourse(){
	$.getJSON("/course/remove/" + $(this).data("id"),{},function(){
		location.href = location.href;
	});
}

function readCourseDetail(){
	var type = ["共同必修","共同必修","共同選修","專業必修","專業必修","專業選修"];
	$.getJSON("/course/detail/" + $(this).data("id"),{},function(data){
		$("#course-detail .name").html( data[1] + " #" + data[0]);
		$("#course-detail .engilshName").html(data[3]);
		$("#course-detail .course_id").html(data[0]);
		$("#course-detail .info").html("學分 : " + data[4] + " / 時數 : " + data[5] + " / 選課 : " + data[8] + "人 / 退選 : " + data[9] + "人 / " + type[parseInt(data[6])]);
		$("#course-detail .time").html("上課時段 : " + data[7]);
		$("#course-detail .teacher").html("授課教師 : " + data[13]);
		$("#course-detail .class").html("班級 : " + data[15]);
		$("#course-detail .note").html("備註 : " + data[10]);
		$("#course-detail .place").html("授課地點 : " + data[14]);
		$("#course-detail .description").html(data[11]);
		$("#course-detail .englishDescription").html(data[12]);
		$("#course-detail .students .list").empty();
		if (data[16].length == 1 ){
			$("#course-detail .students").hide();
		} else {
			$("#course-detail .students").show();
		}
		for (student_id in data[16]){
			student = data[16][student_id];
			if (student[1]==null) continue;
			$("<div class='student'><a href='/user/"+student_id+"'><img src='https://graph.facebook.com/"+student[0]+"/picture?width=80&amp;height=80'/><div class='name'>"+student[1]+"</div></a></div>").appendTo($("#course-detail .students .list"));
		}
		for (student_id in data[16]){
			student = data[16][student_id];
			if (student[1]!==null) continue;
			$("<div class='student nonfb'><a href='/user/"+student_id+"'><img src='/imgs/error.png'/><div class='name'>"+student[2]+"</div></a></div>").appendTo($("#course-detail .students .list"));
		}
		$("#course-detail").fadeIn();
	});
}
$(function(){
	$("#course-detail").on('click', function(e){ if(e.target == this){ $("#course-detail").fadeOut();} });
	$("#course-detail .close").on('click', function(e){$("#course-detail").fadeOut();});
})