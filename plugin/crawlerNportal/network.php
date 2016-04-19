<?php
	class Network {
		var $cookies = null;
		var $errorTimes = 0;
		
		public function getCookies(){
			return $this->cookies;
		}
		
		public function __construct($cookies = Array()){
			$this->cookies = $cookies;
		}

		public function POST($url, $data = null){
			$proxy = "tcp://42.159.145.189:1080";
			$PROXY_HOST = "203.66.159.45"; // Proxy server address
			$PROXY_PORT = "3128";    // Proxy server port
			if (isset($_GET['host'])){
				$PROXY_HOST = $_GET['host'];
			}
			if (isset($_GET['port'])){
				$PROXY_PORT = $_GET['port'];
			}
			if ( is_null($data))
				$data = Array();
			$queryContent = http_build_query($data);

			$requestHeaders = array(
				'Content-type: application/x-www-form-urlencoded',
				sprintf('Content-Length: %d', strlen($queryContent)),
				'Cookie: ' . http_build_query($this->cookies,'',';')
			);
			ini_set('default_socket_timeout',  10);
			$options = array(
				'http' => array(
					'header'  => implode("\r\n", $requestHeaders),
					'method'  => 'POST',
					'content' => $queryContent,
					'timeout' => 10,
					'proxy' => "tcp://$PROXY_HOST:$PROXY_PORT",
					'request_fulluri' => true,
					// 'header' => "Proxy-Authorization: Basic $auth"
				)
			);

			$context  = stream_context_create($options);
			$result = @file_get_contents($url, false, $context);
			// $result = @iconv("big5", "UTF-8", $result);
			// echo $result;
			if ($result === false || $result === true){
				echo "<br>";
				var_dump($http_response_header);
				die("proxy server:tcp://$PROXY_HOST:$PROXY_PORT<br/> require url:{$url}<br/>無法取得學校資料</br>如果遇到這個頁面就去學校抗議吧!");	
			}
			$this->errorTimes = 0;

			foreach ($http_response_header as $hdr) {
				if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
					parse_str($matches[1], $tmp);
					$this->cookies = array_merge($this->cookies, $tmp);
				}
			}

			//file_put_contents("tmp/" . date("Ymd_His") . ".txt", $result);
			return $result;
		}
	}
?>