<?php
	class CourseController extends BaseController {
		public function index(){}
		public function add($course_id){
			$this->loadModel("offer");
			$this->Offer->create(array(
				'student_id' => $this->user['student_id'],
				'course_id' => $course_id,
				'type' => 'virtual'
			));
			print_r(json_encode(array('status'=>'success')));
			exit();
		}

		public function detail($course_id){
			$data = $this->Course->findDetailById($course_id);
			$weeks = array(
				"sun" => "sunday",
				"mon" => "monday",
				"tue" => "tuesday",
				"thu" => "thursday",
				"wed" => "wednesday",
				"fri" => "friday",
				"sat" => "saturday"
			);
			$row = $data[0];
			$time = array();
			foreach ($weeks as $k => $week){
				$classes = explode(",",$row[$week]);
				foreach ($classes as $class){
					if ($class == "") continue;
					$time[] = $k . $class;
				}
			}
			foreach ($data as $r){
				$teacher[$r['teacher_id']] = $r['teacher_name'];
				$place[$r['place_id']] = $r['place_name'];
				$class[$r['class_id']] = $r['class_name'];
				$student[intval($r['student_id'])] = array(
					$r['user_fb_id'],
					$r['user_name'],
					$r['student_name']
				);
			}
			$time = implode("、", $time);
			if (isset($teacher) && is_array($teacher)){
				$teacher_str = implode("、", $teacher);
			} else {
				$teacher_str = "";
			}
			if (isset($class) && is_array($class)){
				$class_str = implode("、", $class);
			} else {
				$class_str = "";
			}

			if (isset($place) && is_array($place)){
				$place_str = implode("、", $place);
			} else {
				$place_str = "";
			}

			$row = array($row['course_id'], $row['name'], $row['alias'], $row['englishName'], $row['credit'], $row['hour'], $row['type'], $time, $row['peopleCount'], $row['deselected'], $row['note'], $row['description'], $row['englishDescription'], $teacher_str, $place_str, $class_str,$student);
			print_r(json_encode($row));
			exit();
		}

		public function remove($course_id){
			$this->loadModel("offer");
			$this->Offer->delete(array(
				'student_id' => $this->user['student_id'],
				'course_id' => $course_id,
				'type' => 'virtual'
			));
			print_r(json_encode(array('status'=>'success')));
			exit();

		}
		//public function other($year, $semester){}

		public function search(){
			$matric = implode(",", array_map("intval",explode(",",$this->getGET("matric"))));
			$keyword = $this->getGET("keyword");
			$year = $this->getGET('year');
			$semester = $this->getGET('semester');
			$data = array();
			$weeks = array("sun","mon","thu","wed","thu","sat");
			if (intval($keyword)."" == $keyword){
				$row = $this->Course->findById($keyword);
				$data[] = $row;
			} else if(in_array(strtolower(substr($keyword,0,3)), $weeks)){
				$keyword = strtolower($keyword);
				$data = $this->Course->findAllByTime(substr($keyword,0,3), substr($keyword,3,1), $matric, $year, $semester);
			} else {
				$data = $this->Course->findAllByName($keyword, $matric, $year, $semester);
			}
			$courses = array();
			foreach($data as $row){
				if (!isset($courses[$row['course_id']])){
					$weeks = array(
						"sun" => "sunday",
						"mon" => "monday",
						"tue" => "tuesday",
						"thu" => "thursday",
						"wed" => "wednesday",
						"fri" => "friday",
						"sat" => "saturday"
					);
					$times = array();
					foreach ($weeks as $key => $val){
						$time = explode(",", $row[$val]);
						if (count($time)){
							foreach ($time as $i){
								if ($i == "") continue;
								$times[] = $key . $i;
							}
						}
					}
					$courses[$row['course_id']] = array($row['name'], implode(",", $times), array(), array(), $row['credit'], $row['type']);
				}
				$courses[$row['course_id']][2][$row['teacher_id']] = $row['teacher_name'];
				$courses[$row['course_id']][3][$row['place_id']] = $row['place_name'];

			}
			print_r(json_encode($courses));
			exit();
		}
		/*
		public function test(){
		}
		public function recognizeSemesterCourses(){
			$this->Course->recognizeSemesterCourses();
		}
		public function crawlerCourses(){
			$this->Course->recognizeCourseDeatil();

		}
		public function crawler(){
			set_time_limit(0);
			include_once("plugin/crawlerNportal/nportal.php");
			include_once("plugin/crawlerNportal/network.php");
			$cookies = Array("JSESSIONID" => file_get_contents("tmp/cookies.txt"));
			$network = new Network($cookies);
			$content = $network->POST("http://aps.ntut.edu.tw/course/tw/QueryCurrPage.jsp");
			$content = mb_convert_encoding($content,"UTF-8","Big5");
			if (strpos($content, "未登錄") !== false){
				$nportal = new Nportal('101590311','aS82851049');
				$network = $nportal->sso("http://aps.ntut.edu.tw/course/tw/courseSID.jsp");
				$content = $network->POST("http://aps.ntut.edu.tw/course/tw/QueryCurrPage.jsp");
				file_put_contents("tmp/cookies.txt", $network->getCookies());
			}

			for ($year=93; $year<=102; $year++){
				for ($sem=1; $sem<=2; $sem++){
					for ($matric=0; $matric<16; $matric++){
						$content = $network->POST("http://aps.ntut.edu.tw/course/tw/QueryCourse.jsp", array(
							'stime' => 0,
							'year' => $year,
							'matric' => "'" . strtoupper(dechex($matric)) . "'",
							'sem' => $sem,
							'unit' => '**',
							'cname' => '*',
							'ccode' => "",
							'tname' => "",
							'D0' => 'ON',
							'D1' => 'ON',
							'D2' => 'ON',
							'D3' => 'ON',
							'D4' => 'ON',
							'D5' => 'ON',
							'D6' => 'ON',
							'P1' => 'ON',
							'P2' => 'ON',
							'P3' => 'ON',
							'P4' => 'ON',
							'P5' => 'ON',
							'P6' => 'ON',
							'P7' => 'ON',
							'P8' => 'ON',
							'P9' => 'ON',
							'P10' => 'ON',
							'P11' => 'ON',
							'P12' => 'ON',
							'P13' => 'ON'
						));
						file_put_contents("tmp/{$year}_{$sem}_" . dechex($matric) . ".txt", $content);
						print_r("{$year}_{$sem}_" . dechex($matric) . "<br/>");
						ob_flush();
						flush();
					}
				}
			}


		}
		*/
	}
?>