<?php
	include_once "network.php";
	include_once "ORC.php";

	class Nportal {
		private $cookies = null;
		private $network = null;
		private $student_id;
		public $status = false;
		public function __construct($student_id, $password){
			file_put_contents("./tmp/cookies.txt", "1");
			$this->student_id = $student_id;
			$this->cookies = Array();
			$this->cid = rand();
			$this->network = new Network($this->cookies);
			$s = $this->getSslPage("https://nportal.ntut.edu.tw/logout.do"); 
			$s = $this->getSslPage("https://nportal.ntut.edu.tw/index.do?thetime=" . time() . "000"); 
			$p = strpos($s, "token");
			$token = substr($s, $p + 28, 13);
			$authImage = $this->getSslPage("https://nportal.ntut.edu.tw/authImage.do");
			file_put_contents("./tmp/auth.png", $authImage);

			$text = ORC("./tmp/auth.png");

			$log = $this->getSslPage("https://nportal.ntut.edu.tw/login.do", Array(
				"muid" => $this->student_id,
				"mpassword" => $password,
				"authcode" => $text,
				"Submit2" => "登入（Login）",
				"token" => $token
			));
			$s = $this->getSslPage("http://nportal.ntut.edu.tw/myPortal.do?thetime=1461000074840_true");
			$this->status = true;
			if (strpos($s, "帳號或密碼錯誤") !== false || strpos($s, "請重新登入") !== false){
				$this->status = false;
			}
		}


		public function getSslPage($url, $data = null) {
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		    curl_setopt($ch, CURLOPT_HEADER, 1);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_REFERER, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_PROXY, '203.66.159.45:3128');
			curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookiefile.txt'); 
			curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookiefile.txt'); 
			curl_setopt($ch,CURLOPT_HTTPHEADER, array(
				'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36',
				'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
				'Accept-Encoding:gzip, deflate, sdch',
				'Accept-Language:zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4',
				'Cache-Control:max-age=0',
				'Connection:keep-alive',
				'Host:nportal.ntut.edu.tw',
				'Referer:http://nportal.ntut.edu.tw/index.do?thetime=' . time() . '000',
				'Upgrade-Insecure-Requests:1',
			));
			if (isset($data)){
				curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $data )); 
			}
		    $response = curl_exec($ch);
		    if ($response === false){
				exit('Curl error: ' . curl_error($ch));
		    }
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($response, 0, $header_size);
			$body = substr($response, $header_size);
		    curl_close($ch);
		    return $body;
		}

		public function sso($url){
			$s = $this->getSslPage("https://nportal.ntut.edu.tw/ssoIndex.do?apUrl={$url}&apOu=aa_0010&sso=true");
			$session_id = "";
			if (@preg_match("/'sessionId' value='\s*([^']+)'/", $s, $matches)) {
				$session_id = $matches[1];
			}
			$cookies = Array('sessionId' => $session_id);
			$network = new Network();
			$network->POST($url, Array(
				'sessionId' => $session_id,
				'userid' => $this->student_id,
				'userType' => 50
			));
			return $network;
		}

		public function getCookies(){
			return $this->cookies;
		}
	}

?>