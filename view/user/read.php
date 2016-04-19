<?php $controller->css("baseform.css"); ?>
<div id="read">
	<div class="left">
<?php $controller->loadView("user/information"); ?>
		<div class="courses box clearfix">
			<h2>歷年課表</h2>
			<div class="list">
<?php
				foreach ($offers as $year => $semesters){
					foreach ($semesters as $semester => $courses){
?>
				<div class="item <?php if($year == $read_year && $semester == $read_semester) echo "selected";?>">
					<h3>
						<?php $semesterText = array("先修","上","下"); ?>
						<a href="/user/<?php echo $read_user['student_id'];?>/<?php echo $year;?>/<?php echo $semester;?>">
							<?php echo $year?>學年度<?php echo $semesterText[$semester];?>學期
						</a>
					</h3>
					<div class="detail">
						<?php $credit[$year][$semester] = 0; ?>
						<?php foreach ($courses as $key => $course){?>
						<?php $credit[$year][$semester] += $course['credit']; ?>
							<a class="course" data-id="<?php echo $course['course_id'];?>"><?php echo $course['alias'] == "" ? $course['name'] : $course['alias'];?></a>、
						<?php } ?>
						共<?php echo $credit[$year][$semester]; ?>學分
					</div>
				</div>
				<?php }} ?>
			</div>
		</div>
		<?php if (count(@$read_market)){ ?>
		<div class="market box">
			<h2>市集商品</h2>
			<div class="list">
				<?php foreach ($read_market as $item){ ?>
				<a href="/market/detail/<?php echo $item['id']; ?>">
					<div class="item">
						<div class="mark">
							<img src="/imgs/market/<?php echo $item['thumbnail'];?>"/>
						</div>
						<div class="name"><?php echo $item['name'];?></div>
					</div>
				</a>
				<?php } ?>
			</div>
		</div>
		<?php } ?>

	</div>
	<div class="right">
	<?php $controller->loadView("user/timetable"); ?>
	</div>
</div>
<div class="clr"></div>