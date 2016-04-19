$(function(){
	$("input[name='keyword']").on("keydown", function(e){
		if (e.keyCode == 13 && $(this).val() != ""){
			$(".courses").html("");
			$.getJSON("/course/search/", {
				matric: $("select[name='matric']").val(),
				year: $("input[name='year']").val(),
				semester: $("input[name='semester']").val(),
				keyword: $(this).val()
			}, function(courses){ loadCourse(courses);});
		}

	});
})

	var lastCourse;

	function loadCourse(courses){
		var type = ["共同必修","共同必修","共同選修","專業必修","專業必修","專業選修"];
		for (var i in courses){

			var teacher = $.map(courses[i][2],function(n, i){ return n;}).join("、");
			var place = $.map(courses[i][3],function(n, i){ return n;}).join("、");
			$(".courses")
				.append(
					$("<div>")
						.addClass("course")
						.data("id", i)
						.data("name", courses[i][0])
						.data("time", courses[i][1])
						.data("teacher", teacher)
						.data("place", place)
						.data("credit", courses[i][4])
						.append(
							$("<div>")
								.addClass("name")
								.html(courses[i][0])
								.append(
									$("<span>")
										.addClass("course_id")
										.html(i)
								)
						)
						.append(
							$("<span>")
								.addClass("teacher")
								.html(teacher)
						)
						.append(" | ")
						.append(
							$("<span>")
								.addClass("credit")
								.html(courses[i][4] + " 學分")
						)
						.append(" | ")
						.append(
							$("<span>")
								.addClass("type")
								.html(type[courses[i][5]])
						)
						.append(
							$("<button>")
								.addClass("add")
								.html("排入")
								.on('click', addCourse)
						)
						.on('mousemove', sumulator)
				)
		}
	}
	function addCourse(){
		$.getJSON("/course/add/" + $(this).parents(".course").data("id"),{
		},function(){
			location.href = location.href;
		})
	}
	function sumulator(e){
		if (this == lastCourse) return;
		$(".course.template").remove();
		lastCourse = this;
		var time = $(this).data("time").split(",");
		var weeks = ["mon","tue","wed","thu","fri","sat","sun"];
		var name = $(this).data("name");
		var place = $(this).data("place");
		var teacher = $(this).data("teacher");
		if (time[0] == ""){
			conflict = 0;
			$("#c-else")
				.append(
					$("<div>")
						.addClass("course")
						.addClass("template " + (conflict?"conflict":""))
						.append(
							$("<div>")
								.addClass("name")
								.html(name)
						).append(
							$("<div>")
								.addClass("place")
								.html(place)
						).append(
							$("<div>")
								.addClass("teacher")
								.html(teacher)
						)
				);
		}
		for ( var i in time){
			conflict = 0;
			var week = weeks.indexOf(time[i].substring(0, 3));
			var th = time[i].substring(3, 4);
			if ($("#c" + week + "-" + th + " .course").length){
				conflict = 1;
			}
			$("#c" + week + "-" + th)
				.append(
					$("<div>")
						.addClass("course")
						.addClass("template " + (conflict?"conflict":""))
						.append(
							$("<div>")
								.addClass("name")
								.html(name)
						).append(
							$("<div>")
								.addClass("place")
								.html(place)
						).append(
							$("<div>")
								.addClass("teacher")
								.html(teacher)
						)
				);

		}
	}