<?php $controller->css("baseform.css"); ?>
<div id="read">
	<div class="left">
<?php
	$controller->set("read_user", $user);
	$controller->loadView("user/information");
?>

		<div id="semesters">
			<h2>歷年課表</h2>
<?php 	foreach ($offers as $year => $semesters ){
			foreach ($semesters as $semester => $courses){
?>
			<div class="semester  <?php if($year == $read_year && $semester == $read_semester) echo "selected";?>">
				<a href="/user/simulator/<?php echo $year;?>/<?php echo $semester;?>">
					<?php echo $year;?>學年度<?php echo idx($semester, "上","下");?>學期
				</a>
			</div>
<?php
			}
		}
?>
		</div>
		<div id="simulator">
			<h2>尋找課程</h2>
				<div class="row">
					<select name="matric">
						<option value="0,1,4,5,6,7,8,9,A,C,D,E,F">全校</option>
						<option value="1,5,6,7,8,9" selected="">日間部</option>
						<option value="7">日間部四技</option>
						<option value="8,9">日間部研究所(碩、博)</option>
						<option value="4,A,D,E">進修部</option>
						<option value="4">進修部二技</option>
						<option value="E">學士後學位學程</option>
						<option value="F">進修部四技產學專班</option>
						<option value="A">進修部碩士在職專班</option>
						<option value="D">ＥＭＢＡ</option>
						<option value="C">週末碩士班</option>
						<option value="8,9,A,C,D">研究所(日間部、進修部、週末碩士班)</option>
						<option value="0">進修學院(二技)</option>
						<option value="0,4,6">二技(日間部、進修部暨進修學院)</option>
						<option value="1">學程</option>
					</select>
				</div>
				<input type="hidden" name="year" value="<?php echo $read_year;?>"/>
				<input type="hidden" name="semester" value="<?php echo $read_semester;?>"/>
				<div class="row">
					<input type="text" name="keyword" placeholder="關鍵字"/>
					<div class="question">
						？
						<div class="answer">
							請直接輸入課程名稱、代碼、節次(如:Sun1/ThuA)、老師
						</div>
					</div>
				</div>
			<div class="courses">
			</div>
		</div>
	</div>
	<div class="right">
		<?php $controller->loadView("user/timetable")?>
	</div>
	<div class="clr"></div>
</div>