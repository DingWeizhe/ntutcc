<?php
	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\GraphObject;
	use Facebook\GraphUser;
	define('FACEBOOK_SDK_V4_SRC_DIR', 'plugin/Facebook/');
	class UserController extends BaseController {
		public function login(){
			include "/config/facebook.inc.php";
			require '/plugin/Facebook/autoload.php';
			$this->loadModel('user');
			FacebookSession::setDefaultApplication($config['facebook']['app']['appId'], $config['facebook']['app']['secret']);
			$helper = new FacebookRedirectLoginHelper('http://' . HOST . '/user/login/');
			$session = $helper->getSessionFromRedirect();
			
			//facebook尚未登入
			if (!$session){
				$helper = new FacebookRedirectLoginHelper('http://' . HOST . '/user/login/');
				$loginUrl = $helper->getLoginUrl();
				$this->redirect($loginUrl);
				return;
			}
			$request = new FacebookRequest($session, 'GET', '/me');
			$response = $request->execute();
			$graphObject = $response->getGraphObject(GraphUser::className());
			$fb_id = $graphObject->getId();
			$this->user = $this->User->findByFbId($fb_id);

			// 尚未註冊帳號
			if (!$this->user){
				$facebookUserData = array(
					"id" => $graphObject->getId(),
					"name" => $graphObject->getName(),
					"gender" => $graphObject->getGender(),
					"birthday" => $graphObject->getBirthday()->format('Y-m-d H:i:s'),
					"email" => $graphObject->getEmail(),
				);
				$this->User->registerFromFacebook($facebookUserData);
				$this->user = $this->User->findByFbId($fb_id);
			}


			$_SESSION['user'] = intval($this->user['id']);

			// 尚未設定學號
			if ($this->user['student_id'] == 0){
				return $this->redirect("/user/setting");
			}

			// 成功登入
			return $this->redirect("/user");
		}

		public function logout(){
			unset($_SESSION['user']);
			//$facebook = new Facebook($config['facebook']['app']);
			//$logoutUrl = $facebook->getLogoutUrl($config['facebook']['logout']);
			return $this->redirect("/");
		}


		public function setting(){
			if (!$this->User->isLogin()){
				return $this->redirect("/user/login");
			}
			if ($this->getMethod() == "POST"){
				$data = array(
						'name' => $this->getPOST('name'),
						'birthday' => $this->getPOST('birthday'),
						'department_id' => $this->getPOST('department'),
						'status' => $this->getPOST('status')
				);
				if ($this->user['authenticate']){
					$data['publishCourse'] = $this->getPOST('publishCourse');
					$data['publishFB'] = $this->getPOST('publishFB');
					$data['displayWeekend'] = $this->getPOST('displayWeekend');
				} else {
					$data['student_id'] = $this->getPOST('student_id');
				}

				if (!$this->user['authenticate'] && $data['student_id'] != $this->user['student_id']){
					$data['authenticate'] = 0;
					$data['authenticationCode'] = md5(time() . SALT);
				}
				$this->User->update($this->user['id'], $data);
				$this->set("message", "資料修改完成");
				$this->user = $this->User->findById($this->user['id']);
			}

			$this->loadModel("department");
			$this->set('user', $this->user);
			$this->set('departments', $this->Department->findAll());
			return $this->display("/user/setting");
		}
		public function authenticate(){
			if (!isset($_POST['user'])){
				return $this->display("/user/form");
			}
			
			include_once("plugin/crawlerNportal/nportal.php");
			include_once("plugin/crawlerNportal/network.php");
			$nportal = new Nportal($_POST['user'], $_POST['password']);
			if ($nportal->status == false){
				return $this->error("帳號或密碼錯誤");
			} else {
				$data['student_id'] = $this->getPOST('user');
				$this->User->update($this->user['id'], $data);
				$this->User->authenticate($this->user['id']);
				return $this->display("/user/successAuthenticate");
			}
			/*
			include "/config/gmail.inc.php";
			include "/plugin/Gmail.php";
			if (isset($_GET['authenticateCode'])){
				$this->User->authenticate($_GET['authenticateCode']);
				return $this->display("/user/successAuthenticate");

			} else {
				$student_id = $this->user['student_id'];
				if ($student_id == 0 ){
					$this->set("message", "請先設定學號");
					$this->redirect("/user/setting");
				}
				if ( time() - strtotime($this->user['lastAuthenticateTime']) < 60 * 5){
					return $this->error("你很沒有耐心喔!", "五分鐘內已經發送過驗證信, 請耐心等候信件寄出");
				}
				$gmail = GMailFactory();
				$gmail->Subject = "認證信件";
				$this->set('user', $this->user);
				$gmail->Body = evaluate("/view/user/mail.php", $this->collection);
				if (substr($student_id, 0, 1) == "9"){
					$gmail->AddAddress("t".substr($student_id, 1)."@ntut.edu.tw", $this->user['name']);
					$gmail->AddAddress("t".substr($student_id, 1)."@ntut.org.tw", $this->user['name']);
				} else {
					$gmail->AddAddress("t{$student_id}@ntut.edu.tw", $this->user['name']);
					$gmail->AddAddress("t{$student_id}@ntut.org.tw", $this->user['name']);
				}

				if(!$gmail->Send()){
					return $this->error("寄信發生錯誤：" . $gmail->ErrorInfo);
				} else {			
					$this->User->update($this->user['id'], array("lastAuthenticateTime" => date('Y-m-d H:i:s')));
				}

				$this->display("/user/successSent");
			}
			*/
		}
		public function wallAJAX($page, $gender=0){
			$users = $this->User->findAllUser($page,$gender);
			$json = json_encode($users);
			print_r($json);
			exit();
		}
		public function wall($gender=0){
			$this->set("gender", $gender);
			$this->display("user/wall");
		}
		public function like($user_id){}
		public function simulatorAJAX(){

		}
		public function simulator($year=0, $semester=0){
			if (!$this->user){
				return $this->Error("安捏不對喔", "如果想要模擬選課, 請先登入FB喔 揪咪^ _ <");
			}
			if (!$this->user['student_id']){
				return $this->Error("安捏不對喔", "如果想要模擬選課, 請先到<a href='/user/setting'>這裡</a>設定學號喔 揪咪^ _ <");
			}
			if (!$this->user['authenticate']){
				return $this->Error("阿是不會認證喔", "如果想要使用模擬選課, 請先到<a href='/user/setting'>這裡</a>認證齁！");
			}

			$this->loadModel("Offer");
			$offers = array();
			if ($this->user['student_id'] != 0){
				$offers = $this->Offer->findAllByStudentId($this->user['student_id']);

				if (!is_array($offers) || count($offers) == 0){
					$this->Offer->crawlerByStudentId($this->user['student_id']);
					$offers = $this->Offer->findAllByStudentId($this->user['student_id']);

				}
			}
			if ($year == 0){
				$year = key($offers);
				$semester = key($offers[$year]);
			}
			$this->set("offers", $offers);
			$this->set("read_year", $year);
			$this->set("read_semester", $semester);
			$this->display("user/simulator");
		}

		public function recrawler($user_id){
			$this->loadModel("Offer");
			set_time_limit(100);
			$this->Offer->deleteByStudentId($user_id);
			$this->Offer->crawlerByStudentId($user_id);
			$this->redirect("/user/{$user_id}");
		}

		public function index($student_id=0, $year=0, $semester=0){
			$this->loadModel("Offer");
			$this->loadModel("Market");
			if ($student_id == 0){
				if (!$this->user){
					return $this->redirect("/search/form");
				}
				if ( !$this->user['student_id'] ){
					return $this->Error("阿捏不對喔", "你還沒有設定你的學號, 請先到<a href='/user/setting'>這裡</a>設定你的學號");
				}
				return $this->redirect("/user/{$this->user['student_id']}");
			}

			$read_user = $this->User->findByStudentId($student_id);

			if (!$read_user){
				$read_user['name'] = "";
				$read_user['department_name'] = "????";
				$read_user['fb_id'] = 100000000;
				$read_user['displayWeekend'] = 1;
				$read_user['student_id'] = $student_id;
				$read_user['publishCourse'] = 1;
			}

			if (
				!@$read_user['publishCourse'] &&
				@$this->user['id'] != @$read_user['id']
			){
				return $this->Error("ㄌㄩㄝ~","這個課表的主人不想要給你看喔~");
			}

			$offers = array();
			if ($read_user['student_id'] != 0){
				$offers = $this->Offer->findAllByStudentId($read_user['student_id']);
				if (!is_array($offers) || count($offers) == 0){
					$this->Offer->crawlerByStudentId($read_user['student_id']);
					$offers = $this->Offer->findAllByStudentId($read_user['student_id']);
				}
			}

			if (count($offers) == 0){
				$this->Error("唉呦~ 好像有點怪怪的", "是否輸入錯誤的學號了呢?");
			}

			$this->set("offers", $offers);
			if ( $year == 0){
				$year = key($offers);
				$semester = key($offers[$year]);
			}

			$this->set("user", $this->user);
			$this->set("read_user", $read_user);
			$this->set("read_year", $year);
			$this->set("read_semester", $semester);
			$this->set("read_market", $this->Market->findByUserId(@$read_user['id']));
			return $this->display("user/read");
		}
	}
?>