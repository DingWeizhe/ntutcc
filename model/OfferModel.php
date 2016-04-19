<?php

	class OfferModel extends baseModel {
		public function deleteByStudentId($student_id){
			$sth = $this->db->prepare("delete from `offer` where `student_id` = '{$student_id}'");
			$sth->execute();
			return;
		}
		public function findAllByStudentId($student_id){
			$student_id = $student_id;
			$sth = $this->db->prepare("
				select *,`offer`.`type` as `offer_type`, course.name as name, teacher.name as teacher_name, place.name as place_name, class.name as class_name
				from `offer`
				left join `course` on `offer`.`course_id` = `course`.`id`
				left join `course_teacher` on `course`.`id` = `course_teacher`.`course_id`
				left join `teacher` on `course_teacher`.`teacher_id` = `teacher`.`id`
				left join `course_place` on `course`.`id` = `course_place`.`course_id`
				left join `place` on `course_place`.`place_id` = `place`.`id`
				left join `course_class` on `course`.`id` = `course_class`.`course_id`
				left join `class` on `course_class`.`class_id` = `class`.`id`
				where `student_id` = :student_id
			");
			$sth->bindvalue(":student_id", $student_id);
			$sth->execute();
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			$table = array();
			foreach ($data as $row){
				if (!$row['year']) continue;
				if (!isset($table[$row['year']][$row['semester']][$row['course_id']])){
					$table[$row['year']][$row['semester']][$row['course_id']] = $row;
					$table[$row['year']][$row['semester']][$row['course_id']]['teacher'] = array();
					$table[$row['year']][$row['semester']][$row['course_id']]['place'] = array();
					$table[$row['year']][$row['semester']][$row['course_id']]['class'] = array();
				}
				$table[$row['year']][$row['semester']][$row['course_id']]['teacher'][$row['teacher_id']] = $row['teacher_name'];
				$table[$row['year']][$row['semester']][$row['course_id']]['place'][$row['place_id']] = $row['place_name'];
				$table[$row['year']][$row['semester']][$row['course_id']]['class'][$row['class_id']] = $row['class_name'];

			}

			return $table;
		}

		public function crawlerByStudentId($student_id){
			ini_set('max_execution_time', 300);
			include_once("plugin/crawlerNportal/nportal.php");
			include_once("plugin/crawlerNportal/network.php");
			include_once("config/nportal.inc.php");
			$courseModel = new courseModel($this->db);
			$network = new Network();
			$content = $network->POST("http://aps.ntut.edu.tw/course/tw/Select.jsp?format=-3&code={$student_id}");
			$content = @iconv("big5", "UTF-8", $content);
			if (strpos($content, "未登錄") !== false || strpos($content, "重新登入") !== false){
				$nportal = new Nportal($config['nportal']['username'], $config['nportal']['password']);
				$network = $nportal->sso("http://aps.ntut.edu.tw/course/tw/courseSID.jsp");
				$content = $network->POST("http://aps.ntut.edu.tw/course/tw/Select.jsp?format=-3&code={$student_id}");
			}
			$semesters = $this->recognizeSemester($content);
			$insertOffer = array();
			foreach ($semesters as $semester){
				$content = $network->POST("http://aps.ntut.edu.tw/course/tw/Select.jsp?format=-2&code={$student_id}&year={$semester[0]}&sem={$semester[1]}");
				$courses = $this->recognizeCourse($content);
				foreach ($courses as $course){
					$updateFlag = true;
					if ($course['id'] != ""){
						$sth = $this->db->prepare("select * from `course` where `id` = :course_id");
						$sth->bindvalue(":course_id", $course['id']);
						$sth->execute();
						$c = $sth->fetch(PDO::FETCH_ASSOC);
						if ($c == NULL){
							$sth = $this->db->prepare("
							insert into `course`
								(`id`, `name`, `level`, `credit`, `hour`, `sunday`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `peopleCount`, `deselected`, `note`, `year`, `semester`)
							values
								(:id, :name, :level, :credit, :hour, :sunday, :monday, :tuesday, :wednesday, :thursday, :friday, :saturday, :peopleCount, :deselected, :note, :year, :semester)
							");
							$sth->bindvalue(":id", $course['id']);
							$sth->bindvalue(":name", $course['name']);
							$sth->bindvalue(":level", $course['level']);
							$sth->bindvalue(":credit", $course['credit']);
							$sth->bindvalue(":hour", $course['hour']);
							$sth->bindvalue(":sunday", implode(",", $course['sunday']));
							$sth->bindvalue(":monday", implode(",", $course['monday']));
							$sth->bindvalue(":tuesday", implode(",", $course['tuesday']));
							$sth->bindvalue(":wednesday", implode(",", $course['wednesday']));
							$sth->bindvalue(":thursday", implode(",", $course['thursday']));
							$sth->bindvalue(":friday", implode(",", $course['friday']));
							$sth->bindvalue(":saturday", implode(",", $course['saturday']));
							$sth->bindvalue(":peopleCount", $course['peopleCount']);
							$sth->bindvalue(":deselected", $course['deselected']);
							$sth->bindvalue(":note", $course['note']);
							$sth->bindvalue(":year", $semester[0]);
							$sth->bindvalue(":semester", $semester[1]);
							$sth->execute();
							$sth = $this->db->prepare("select * from `course` where `id` = :course_id");
							$sth->bindvalue(":course_id", $course['id']);
							$sth->execute();
							$c = $sth->fetch(PDO::FETCH_ASSOC);
						}

						if (time() - strtotime($c['updateDatetime']) > 60 * 60 * 24 * 7){
							$content = $network->POST("http://aps.ntut.edu.tw/course/tw/Select.jsp?format=-1&code={$course['id']}");
							$courseDetail = $this->recognizeCourseDetail($content);
							$course['type'] = $courseDetail['type'];
							$insertCourseStudent = array();
							foreach($courseDetail['students'] as $student){
								$insertCourseStudent[] = "({$course['id']}, '{$student['id']}', '{$student['name']}')";
							}
							$sth = $this->db->prepare("delete from `course_student` where `course_id` = '{$course['id']}'");
							$sth->execute();
							if ( count($insertCourseStudent)){
								$insertCourseStudentStr = implode(",", $insertCourseStudent);
								$sth = $this->db->prepare("insert into `course_student` (`course_id`,`student_id`,`name`) values {$insertCourseStudentStr}");
								$sth->execute();
							}
							$course['updateDatetime'] = date('Y-m-d H:i:s');
						}
					}
					$insertOffer[] = "(".intval($course['id']).", '{$student_id}', 'real')";
					$courseModel->update($course['id'], $course);
				}
				break;
			}
			$insertOfferStr = implode(",", $insertOffer);
			$sth = $this->db->prepare("insert into `offer` (course_id,student_id,type) values {$insertOfferStr}");
			$sth->execute();
			return;
		}

		public function recognizeSemester($content){
			$table = getInnerStr($content, "<table border=1>", "</table>");
			$pattern = '/code=([^\&]+)&year=(\d+)&sem=(\d)/';
			$pos = 0;
			$row = array();
			while(1){
				preg_match($pattern, substr($table, $pos), $matches, PREG_OFFSET_CAPTURE);
				if (count($matches)!=4) break;
				$row[] = array($matches[2][0], $matches[3][0]);
				$pos += $matches[3][1];
			}

			return $row;
		}
		
		public function recognizeCourse($content){
			$content = mb_convert_encoding($content, "UTF-8", "BIG5");
			$content = str_replace("big5", "utf-8", $content);
			$course = new courseModel($this->db);
			$table = $course->recognizeCourse($content);
			return $table;
		}
		
		public function recognizeCourseDetail($content){
			$content = mb_convert_encoding($content, "UTF-8", "BIG5");
			$content = str_replace("big5", "utf-8", $content);
			
			$doc = new DOMDocument();
			$doc->recover = true;
			$doc->strictErrorChecking = false;
			$type = array("○","△","☆","●","▲","★");
			$content = str_replace("\n", "", $content);
			@$doc->loadHTML($content);
			$first = true;
			$table = array();
			$typeidx = array_search($doc->getElementsByTagName("table")->item(0)->getElementsByTagName("tr")->item(7)->getElementsByTagName("td")->item(0)->textContent, $type);
			$students_trs = $doc->getElementsByTagName("table")->item(2)->getElementsByTagName("tr");
			for ($i=1; $i<$students_trs->length; $i++){
				$tr = $students_trs->item($i);
				$tds = $tr->getElementsByTagName("td");
				if ($tds->length != 5) continue;
				$students[] = array(
					'id' => $tds->item(1)->getElementsByTagName("a")->item(0)->textContent,
					'name' => $tds->item(2)->textContent,
					'type' => $tds->item(3)->textContent,
					'type' => $tds->item(4)->textContent
				);
				
			}
			return array('type' => $typeidx, 'students' => $students);
		}
	}
?>