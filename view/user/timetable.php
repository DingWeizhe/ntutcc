<?php
	$controller->Css("user/timetable.css");
	$controller->script("user/timetable.js");
	$semesterText = array("","上","下");
	$credit = 0;
	foreach ($offers[$read_year][$read_semester] as $course){
		$credit += $course['credit'];
	}
?>
<div id="timetable">
	<h2><?php echo $read_year;?>學年度<?php echo $semesterText[$read_semester];?>學期 <?php echo $credit; ?>學分</h2>
	<a class="recrawler" href="/user/recrawler/<?php echo $read_user['student_id']; ?>">更新課表</a>
	<div class="clr"></div>
	<table id="course" class="table table-striped" cellpadding="0" cellspacing="0">
			<tr>
<?php if ($read_user['displayWeekend']){ ?>
				<th>日</th>
<?php } ?>
				<th>一</th>
				<th>二</th>
				<th>三</th>
				<th>四</th>
				<th>五</th>
<?php if ($read_user['displayWeekend']){ ?>
				<th>六</th>
<?php }?>
			</tr>
<?php
if ($read_user['displayWeekend']){
 	$week = array("sunday","monday","tuesday","wednesday","thursday","friday","saturday");
 } else {
 	$week = array("monday","tuesday","wednesday","thursday","friday");
 }
 	$idx = 0;
 	foreach ($offers[$read_year][$read_semester] as $key => $course){
 		$offers[$read_year][$read_semester][$key]['idx'] = $idx++;
 	}
 	for($class=1; $class<14; $class++){
?>
	<tr>
<?php 		foreach ($week as $key => $day){ ?>
			<td id="c<?php echo $key;?>-<?php echo $class;?>">
				<div class="course">
				<?php
					foreach ($offers[$read_year][$read_semester] as $course){
						if (in_array(strtoupper(dechex($class)), explode(",", $course[$day]))){
?>
					<div class="course course-style-<?php echo $course['idx'];?> <?php echo $course['offer_type']=='virtual'?'template':'';?>" data-id="<?php echo $course['course_id'];?>">
						<div class="name"><?php echo $course['alias'] == "" ? $course['name'] : $course['alias'];?></div>
						<div class="place">
							<?php echo implode("、",$course['place']); ?>
						</div>
						<div class="teacher">
							<?php echo implode("、",$course['teacher']); ?>
						</div>
						<?php if (@$read_user['id'] == @$user['id'] && $course['offer_type'] == 'virtual'){ ?>
						<button class="delete" data-id="<?php echo $course['course_id'];?>">
							x
						</button>
						<?php }?>
					</div>
<?php
						}
					}
				?>
				</div>
			</td>
<?php 		}?>
		</tr>
<?php }?>
		<tr>
			<td colspan="<?php echo ($read_user['displayWeekend']?7:5);?>" id="c-else">
			<?php
			foreach ($offers[$read_year][$read_semester] as $course){
				if ($course['monday'] . $course['tuesday'] . $course['wednesday'] . $course['thursday'] . $course['friday'] . $course['saturday'] . $course['sunday'] == ""){
?>
<div class="course course-style-<?php echo $course['idx'];?> <?php echo $course['offer_type']=='virtual'?'template':'';?>" data-id="<?php echo $course['course_id'];?>">
	<div class="name"><?php echo $course['alias'] == "" ? $course['name'] : $course['alias'];?></div>
	<div class="place">
		<?php echo implode("、",$course['place']); ?>
	</div>
	<div class="teacher">
		<?php echo implode("、",$course['teacher']); ?>
	</div>
	<?php if (@$read_user['id'] == @$user['id'] && $course['offer_type'] == 'virtual'){ ?>
	<button class="delete" data-id="<?php echo $course['course_id'];?>">
		x
	</button>
	<?php }?>
</div>
<?php
				}
			}
			?>
			</td>
		</tr>
	</table>
</div>
<div id="course-detail" style="display:none">
	<div class="wrap">
		<div class="close">Close</div>
		<h2 class="name">中國史 The History of China (1325)</h2>
		<h3 class="engilshName"></h3>
		<div class="row info">學分 : 2.0 / 時數 : 2 / 選課人數 : 19 / 退選 : 0 / 校內必修</div>
		<div class="row time">上課時段 : mon1、mon2</div>
		<div class="row class">授課班級 : 博雅核心－歷史(一) 歷史向度</div>
		<div class="row teacher">教師 : 李南海</div>
		<div class="row place">授課地點 : 三教306</div>
		<div class="row students"><span>修這門課的學生 : </span><div class="list"></div></div>
		<div class="row note">授課地點 : 三教306</div>
		<div class="row description">本課程的設計以歷史趨勢的闡述及專題研究分析二方面同時進行。課程方面包括中、西歷史觀念的比較與中國歷代政治、經濟、社會、文化的歷史演進，藉以幫助學生認識時代之趨勢與意義。授課方式以口頭講述為主，討論及書面報告為輔，期使學生在短時間內，能對中國歷史有一簡要而清晰的了解，深化學生的歷史感。</div>
		<div class="row englishDescription">Methodologically, the course is performed in two ways at the same time: interpretation of historical trends as well as the study and analysis of specific topics. The course will cover: comparison of historical concepts and the historical development of a long term of Chinese history in the fields of politics, economics, society and culture so that the students who take this course will sure understand better the trends and significance as the historical time spans go by. Class activities are made up of: lectures, student discussion and paper presentation. The students who take this course will have a brief but clear understanding of the Chinese history in a short time-thus embedding themselves with a deeper sense of history.</div>
	</div>
</div>

<script>

</script>

<!--["1325", "中國史", "", "The History of China    ", "2", "1", "2", "mon1、mon2", "19", "0", "歷史向度", "本課程的設計以歷史趨勢的闡述及專題研究分析二方面同時進行。課程方面包括中、西歷史觀念的比較與中國歷代…為主，討論及書面報告為輔，期使學生在短時間內，能對中國歷史有一簡要而清晰的了解，深化學生的歷史感。", "Methodologically, the course is performed in two w…edding themselves with a deeper sense of history.", "李南海", "三教306", "博雅核心－歷史(一)"]-->

