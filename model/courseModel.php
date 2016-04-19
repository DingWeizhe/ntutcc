<?php
	class courseModel extends baseModel {
		public function findDetailById($id){
			$sth = $this->db->prepare("
				select
					*,
					course.type as `type`,
					course.name as name,
					teacher.name as teacher_name,
					place.name as place_name,
					class.name as class_name,
					course_student.name as student_name,
					course_student.student_id as student_id,
					user.fb_id as user_fb_id,
					user.name as user_name
				from `{$this->table_name}`
					left join `course_teacher` on `course`.`id` = `course_teacher`.`course_id`
					left join `teacher` on `course_teacher`.`teacher_id` = `teacher`.`id`
					left join `course_place` on `course`.`id` = `course_place`.`course_id`
					left join `place` on `course_place`.`place_id` = `place`.`id`
					left join `course_class` on `course`.`id` = `course_class`.`course_id`
					left join `class` on `course_class`.`class_id` = `class`.`id`
					left join `courseDetail` on `course`.`id` = `courseDetail`.`course_id`
					left join `course_student` on `course`.`id` = `course_student`.`course_id`
					left join `user` on `user`.`student_id` = `course_student`.`student_id`
				where `course`.`id` = :id
			");
			$sth->bindValue("id", $id);
			$sth->execute();
			$row = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $row;
		}
		public function findById($id){
			$sth = $this->db->prepare("
				select *,course.type as `type`, course.name as name, teacher.name as teacher_name, place.name as place_name, class.name as class_name
				from `{$this->table_name}`
				left join `course_teacher` on `course`.`id` = `course_teacher`.`course_id`
				left join `teacher` on `course_teacher`.`teacher_id` = `teacher`.`id`
				left join `course_place` on `course`.`id` = `course_place`.`course_id`
				left join `place` on `course_place`.`place_id` = `place`.`id`
				left join `course_class` on `course`.`id` = `course_class`.`course_id`
				left join `class` on `course_class`.`class_id` = `class`.`id`
				where
					`course`.`id` = $id
			");
			$sth->execute();
			$data = $sth->fetch(PDO::FETCH_ASSOC);
			return  $data;
		}
		public function findAllByName($name, $matric, $year, $semester){
			$sth = $this->db->prepare("
				select *,course.type as `type`, course.name as name, teacher.name as teacher_name, place.name as place_name, class.name as class_name
				from `{$this->table_name}`
				left join `course_teacher` on `course`.`id` = `course_teacher`.`course_id`
				left join `teacher` on `course_teacher`.`teacher_id` = `teacher`.`id`
				left join `course_place` on `course`.`id` = `course_place`.`course_id`
				left join `place` on `course_place`.`place_id` = `place`.`id`
				left join `course_class` on `course`.`id` = `course_class`.`course_id`
				left join `class` on `course_class`.`class_id` = `class`.`id`
				where
					(`course`.`name` like '%$name%' or `teacher`.`name` like '%$name%') and
					`matric` in ($matric) and
					`year` = $year and
					`semester` = $semester
			");
			$sth->execute();
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			return  $data;
		}
		public function findAllByTime($week, $time, $matric, $year, $semester){
			$weeks = array(
				"sun" => "sunday",
				"mon" => "monday",
				"tue" => "tuesday",
				"thu" => "thursday",
				"wed" => "wednesday",
				"fri" => "friday",
				"sat" => "saturday"
			);
			$weekfield = $weeks[$week];
			$sth = $this->db->prepare("
				select *,course.type as `type`,course.name as name, teacher.name as teacher_name, place.name as place_name, class.name as class_name
				from `{$this->table_name}`
				left join `course_teacher` on `course`.`id` = `course_teacher`.`course_id`
				left join `teacher` on `course_teacher`.`teacher_id` = `teacher`.`id`
				left join `course_place` on `course`.`id` = `course_place`.`course_id`
				left join `place` on `course_place`.`place_id` = `place`.`id`
				left join `course_class` on `course`.`id` = `course_class`.`course_id`
				left join `class` on `course_class`.`class_id` = `class`.`id`
				where
					`{$weekfield}` like '%$time%' and
					`matric` in ($matric) and
					`year` = $year and
					`semester` = $semester and
					`teacher`.`id` <> 0
			");
			$sth->execute();

			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			return  $data;

		}
		public function update($id, $course){
			$week = array("sunday","monday","tuesday","wednesday","thursday","friday","saturday");
			foreach($week as $day)
				$course[$day] = implode(',', $course[$day]);
			parent::update($id, $course);

			if (isset($course['teacher'])){
				$this->db->prepare("delete from `course_teacher` where `course_id` = {$course['id']}")->execute();
				$insert = array();
				foreach($course['teacher'] as $teacher){
					$sth = $this->db->prepare("select count(*) from `teacher` where `id` = :id");
					$sth->bindValue(":id", $teacher[0]);
					$sth->execute();
					$r = $sth->fetch(PDO::FETCH_NUM);
					if ($r[0] == 0){
						$sth = $this->db->prepare("insert into `teacher` (`id`, `name`) values (:id, :name)");
						$sth->bindValue(":id", $teacher[0]);
						$sth->bindValue(":name", $teacher[1]);
						$sth->execute();
					}
					$insert[] = "({$course['id']}, {$teacher[0]})";
				}
				$this->db->prepare("insert into `course_teacher` (course_id, teacher_id ) values ". implode(",", $insert))->execute();

			}
			if (isset($course['class'])){
				$this->db->prepare("delete from `course_class` where `course_id` = {$course['id']}")->execute();
				$insert = array();
				foreach($course['class'] as $cid) $insert[] = "({$course['id']}, {$cid[0]})";
				$this->db->prepare("insert into `course_class` (course_id, class_id) values ". implode(",", $insert))->execute();
			}

			if (isset($course['place'])){
				$this->db->prepare("delete from `course_place` where `course_id` = {$course['id']}")->execute();
				$insert = array();
				foreach($course['place'] as $place) $insert[] = "({$course['id']}, {$place[0]})";
				$this->db->prepare("insert into `course_place` (course_id, place_id) values ". implode(",", $insert))->execute();
			}
			return;
		}
		/*
		public function recognizeCourseDeatil(){


			set_time_limit(0);
			$start = false;
			include_once("plugin/crawlerNportal/network.php");
			for($year=93; $year>=90; $year--)
				for($semester=1; $semester<=2; $semester++)
					for($matric=0; $matric<=15; $matric++){
						$network = new Network(array());
						$doc = new DOMDocument();
						$doc->recover = true;
						$doc->strictErrorChecking = false;
						$content = file_get_contents("tmp/{$year}_{$semester}_".dechex($matric).".txt");
						$content = mb_convert_encoding($content, "UTF-8", "BIG5");
						$content = str_replace("big5", "utf-8", $content);
						print_r("tmp/{$year}_{$semester}_".dechex($matric).".txt");

							ob_flush();
							flush();
						$content = str_replace("\n", "", $content);
						@$doc->loadHTML($content);
						$trs = $doc->getElementsByTagName("tr");
						$first = true;
						foreach ($trs as $tr){
							if ($first){ $first=false; continue; }
							$id = $tr->getElementsByTagName("td")->item(0)->textContent;
							$code_id = str_replace("Curr.jsp?format=-2&code=", "", $tr->getElementsByTagName("td")->item(1)->getElementsByTagName("a")->item(0)->attributes->getNamedItem("href")->textContent);
							if ($id == "83017"){ $start = true; }
							if (!$start ) continue;
							print_r("$id $code_id ");
							$content = $network->POST("http://aps.ntut.edu.tw/course/tw/Curr.jsp?format=-2&code=" . $code_id);
							$content = mb_convert_encoding($content, "UTF-8", "BIG5");
							$content = str_replace("big5", "utf-8", $content);
							$ndoc = new DOMDocument();
							$ndoc->recover = true;
							$ndoc->strictErrorChecking = false;
							$content = str_replace("\n", "", $content);

							@$ndoc->loadHTML($content);
							$ntrs = $ndoc->getElementsByTagName("tr");
							if ($ndoc->getElementsByTagName("tr")->length == 1 ){
								continue;
							}

							print_r($ntrs->length);
							if ($ntrs->length < 4){
								print_r($ntrs->item(2)->getElementsByTagName("td")->item(0)->textContent);
							}
							$englishName = @$ntrs->item(1)->getElementsByTagName("td")->item(2)->textContent;
							$description = @$ntrs->item(2)->getElementsByTagName("td")->item(0)->textContent;
							$englishDescription = @$ntrs->item(3)->getElementsByTagName("td")->item(0)->textContent;
							$sth = $this->db->prepare("insert into `courseDetail` (`course_id`,`englishName`, `description`, `englishDescription`)values(:course_id,:english_name,:description,:english_description)");
							$sth->bindValue("course_id", $id);
							$sth->bindValue("english_name", $englishName);
							$sth->bindValue("description", $description);
							$sth->bindValue("english_description", $englishDescription);
							$sth->execute();
							print_r("<br/>");
							ob_flush();
							flush();
						}
			}

		}
		public function recognizeSemesterCourses(){
			$c = array();
			$classes = array();
			$teachers = array();
			$places = array();
			for ($year=90; $year<=102; $year++){
				$courseInserts = array();
				for ($semester=1; $semester<=2; $semester++){
					for ($matric=0; $matric<16; $matric++){
						$content = file_get_contents("tmp/{$year}_{$semester}_".dechex($matric).".txt");
						$content = mb_convert_encoding($content, "UTF-8", "BIG5");
						$content = str_replace("big5", "utf-8", $content);
						$data = $this->recognizeCourse($content);
						foreach ($data as $row){
							/*foreach ($row['class'] as $class){
								$classes[$class[0]] = $class[1];
								$course_classes[] = "({$row['id']}, {$class[0]})";
							}
							foreach ($row['teacher'] as $teacher){
								$teachers[$teacher[0]] = $teacher[1];
								$course_teachers[] = "({$row['id']}, {$teacher[0]})";
							}
							foreach ($row['place'] as $place){
								$places[$place[0]] = $place[1];
								$course_places[] = "({$row['id']}, {$place[0]})";
							}
							$sunday = implode(",", $row['sunday']);
							$monday = implode(",", $row['monday']);
							$tuesday = implode(",", $row['tuesday']);
							$wednesday = implode(",", $row['wednesday']);
							$thursday = implode(",", $row['thursday']);
							$friday = implode(",", $row['friday']);
							$saturday = implode(",", $row['saturday']);
							//$courseInserts[] = "({$row['id']}, '{$row['name']}', {$row['level']}, {$row['credit']}, {$row['hour']}, '{$sunday}', '{$monday}', '{$tuesday}', '{$wednesday}', '{$thursday}', '{$friday}', '{$saturday}', {$row['peopleCount']}, {$row['deselected']}, '{$row['note']}', {$year}, {$semester}, {$matric})";
							$type[ $row['type'] ][] = $row['id'];
						}
					}
				}
				//$InsertStr = "insert into `course` (`id`,`name`,`level`,`credit`,`hour`,`sunday`,`monday`,`tuesday`,`wednesday`,`thursday`,`friday`,`saturday`,`peopleCount`,`deselected`,`note`,`year`,`semester`,`matric`) values " . implode(",", $courseInserts);
				//foreach($type as $k => $v)
				//	$courseUpdates[] = "update `course` set `type` = '{$k}' where `id` in (".implode(",", $v).");";

				//file_put_contents("course_{$year}.sql", implode("\n", $courseUpdates));
				//$type = array();
			}
			/*$insert = array();
			foreach ($classes as $key => $value){
				$insert[] = "({$key},'{$value}')";
			}
			$InsertStr = "insert into `class` (`id`,`name`) values " . implode(",", $insert);
			file_put_contents("class.sql", $InsertStr);

			$insert = array();
			foreach ($teachers as $key => $value){
				$insert[] = "({$key},'{$value}')";
			}
			$InsertStr = "insert into `teacher` (`id`,`name`) values " . implode(",", $insert);
			file_put_contents("teacher.sql", $InsertStr);

			$insert = array();
			foreach ($places as $key => $value){
				$insert[] = "({$key},'{$value}')";
			}
			$InsertStr = "insert into `place` (`id`,`name`) values " . implode(",", $insert);
			file_put_contents("place.sql", $InsertStr);


			$InsertStr = "insert into `course_class` (`course_id`, `class_id`) values " . implode(",", $course_classes);
			file_put_contents("course_class.sql", $InsertStr);

			$InsertStr = "insert into `course_teacher` (`course_id`, `teacher_id`) values " . implode(",", $course_teachers);
			file_put_contents("course_teacher.sql", $InsertStr);

			$InsertStr = "insert into `course_place` (`course_id`, `place_id`) values " . implode(",", $course_places);
			file_put_contents("course_place.sql", $InsertStr);

		}
*/
		public function recognizeCourse($content){
			$doc = new DOMDocument();
			$doc->recover = true;
			$doc->strictErrorChecking = false;
			$type = array("○","△","☆","●","▲","★");
			$content = str_replace("\n", "", $content);
			@$doc->loadHTML($content);
			$first = true;
			$table = array();
			$trs = $doc->getElementsByTagName("tr");

			for ($i=3; $i<$trs->length; $i++){
				$tr = $trs->item($i);
				$tds = $tr->getElementsByTagName("td");
				if ($tds->length != 21) continue;
				$row = array(
					'id' => intval($tds->item(0)->textContent),
					'name' => $tds->item(1)->textContent,
					'level' => intval($tds->item(2)->textContent),
					'credit' => floatval($tds->item(3)->textContent),
					'hour' => intval($tds->item(4)->textContent),
					'class' => array(),
					'teacher' => array(),
					'sunday' => explode(" ", $tds->item(8)->textContent),
					'monday' => explode(" ", $tds->item(9)->textContent),
					'tuesday' => explode(" ", $tds->item(10)->textContent),
					'wednesday' => explode(" ", $tds->item(11)->textContent),
					'thursday' => explode(" ", $tds->item(12)->textContent),
					'friday' => explode(" ", $tds->item(13)->textContent),
					'saturday' => explode(" ", $tds->item(14)->textContent),
					'place' => array(),
					'peopleCount' => $tds->item(16)->textContent,
					'deselected' => $tds->item(17)->textContent,
					'note' => $tds->item(18)->textContent
				);

				foreach ($tds->item(15)->getElementsByTagName("a") as $place){
					$href = $place->attributes->getNamedItem("href")->textContent;
					$row['place'][] = array(
						substr($href, strpos($href, "code=") + 5),
						$place->textContent
					);
				}
				foreach ($tds->item(7)->getElementsByTagName("a") as $class){
					$href = $class->attributes->getNamedItem("href")->textContent;
					$row['class'][] = array(
						substr($href, strpos($href, "code=") + 5),
						$class->textContent
					);
				}
				foreach ($tds->item(6)->getElementsByTagName("a") as $teacher){
					$href = $teacher->attributes->getNamedItem("href")->textContent;
					$row['teacher'][] = array(
						substr($href, strpos($href, "code=") + 5),
						$teacher->textContent
					);
				}
				array_shift($row['sunday']);
				array_shift($row['monday']);
				array_shift($row['tuesday']);
				array_shift($row['wednesday']);
				array_shift($row['thursday']);
				array_shift($row['friday']);
				array_shift($row['saturday']);
				$table[] = $row;
			}
			return $table;
		}
	}
?>