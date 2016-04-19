<?php
	class BaseController {
		protected $collection = array();
		protected $models = array();
		protected $user;
		public $script = array();
		public $css = array();
		private $method;
		public function setMethod($method){
			$this->method = $method;
		}
		public function getMethod(){
			return $this->method;
		}
		public function before(){
			if (isset($_SESSION['user'])){
				$user_id = $_SESSION['user'];
				$this->loadModel('user');
				$this->user = $this->User->findById($user_id);
				$this->set("user", $this->user);
			} else {
				include "/config/facebook.inc.php";
				$this->loadModel('user');
				$facebook = new Facebook($config['facebook']['app']);
				$fb_id = $facebook->getUser();
				if ($fb_id){
					$this->user = $this->User->findByFbId($fb_id);
					if ($this->user){
						$this->set("user", $this->user);
						$_SESSION['user'] = $user_id;
					}
				}
			}
		}

		public function after(){}

		public function beforeDisplay(){}
		public function afterDisplay(){}
		public function redirect($url){
			$this->before();
			header("Location: {$url}", true, 302);
			die();
		}

		public function display($view, $layout = "default"){
			if (!$this->model['User']) {
				$this->loadModel('User');
			}
			$comment = $this->User->getCommentHit($this->user['id']);
			$this->set("hit", $comment);
			$this->css("{$view}.css");
			$this->script("{$view}.js");
			$this->beforeDisplay();
			$this->collection['controller'] = $this;
 			$content = evaluate("/view/{$view}.php", $this->collection);
 			$this->collection['content'] = $content;
 			$content = evaluate("/view/layout/{$layout}.php", $this->collection);
 			echo $content;
 			$this->afterDisplay();
		}
		public function loadView($view){
			$this->css("{$view}.css");
 			$content = evaluate("/view/{$view}.php", $this->collection);
 			echo $content;
 			return;
		}

		public function message($message){
			$this->set("message", $message);
		}

		public function set($key, $value){
			$this->collection[strtolower($key)] = $value;
		}


		public function loadModel($model){
			if ( $this->$model == null ){
				$modelClass = $model . "Model";
				if (class_exists($modelClass)){
					$this->models[ucwords($model)] = new $modelClass($this->database);
				}
			}
		}

		public function index(){
			$this->loadModel("article");
			$this->loadModel("user");
			$this->loadModel("market");
			$this->set("walluser", $this->User->getRandom(15));
			$this->set("articles", $this->Article->getSlideList());
			$this->set("markets", $this->Market->getCommoditys(1));
			$this->display("index");
		}

		public function __construct($db){
			$this->database = $db;
		}

		public function __get($key){
			foreach ($this->models as $_key => $model){
				if ($_key == $key) return $model;
			}

			foreach ($this->collection as $_key => $ele){
				if ($_key == $key) return $ele;
			}
		}

		public function script($filename){
			$this->script[] = $filename;
		}

		public function css($filename){
			$this->css[] = $filename;
		}
		public function Error($error_code, $content=""){
			switch( $error_code ){
				case 404:
					$this->set("msg", "找不到你要的頁面");
					$this->set("content", "可能本來就不存在 或者已經被我吃掉了");
					break;
				default:
					$this->set("msg", $error_code);
					$this->set("content", $content);
			}
			$this->display("error");
			exit();
		}

		public function getPOST($key){
			return htmlspecialchars(@$_POST[$key]);
		}
		public function getGET($key){
			return @$_GET[$key];
		}
		public function getFILES($key){
			return @$_FILES[$key];
		}
	}
?>