<?php
	class ImageUpload {
		private $options = array(
			'support_format' => array("image/gif","image/jpeg","image/jpg","image/pjpeg","image/x-png","image/png"),
			'file_size_limit' => 2097152, //2M
			'crop' => array('center', 'middle'),
			'resize_to' => array('100%', '100%')
		);

		public function __construct($options=array()){
			$this->options['directory'] = dirname(__FILE__) . "\\..\\imgs\\upload\\";
			foreach ($this->options as $key=>$val){
				if (isset($options[$key]))
					$this->options[$key] = $options[$key];
			}
		}

		public function upload($file, $filepath=""){
			if (!$this->checkError($file)){ return "圖片上傳失敗"; }
			if (!$this->checkFormat($file)){ return "不支援檔案格式"; }
			if (!$this->checkSize($file)){ return "圖片超出容量限制"; }
			if (($im=$this->readImage($file))===false){ return "讀取圖片發生錯誤請重新上傳"; }
			if (($im=$this->cropAndResize($im))===false){ return "圖片裁切發生錯誤"; }
			if ($filepath==""){ $filepath = $this->filenameGenerator(); }
			$this->save($im, $this->options['directory'] . $filepath);
			return $filepath;
		}

		public function checkError($file){
			return $file['error'] == 0;
		}

		public function checkFormat($file){
			return in_array(@$file['type'], $this->options['support_format']);
		}

		public function checkSize($file){
			return $file['size'] <= $this->options['file_size_limit'];
		}

		public function readImage($file){
			switch($file['type']){
				case "image/gif":
					$im = imagecreatefromgif($file['tmp_name']);
					break;

				case "image/jpeg": case "image/jpg": case "image/pjpeg":
					$im = imagecreatefromjpeg($file['tmp_name']);
					break;

				case "image/x-png": case "image/png":
					$im = imagecreatefrompng($file['tmp_name']);
					break;

				default:
					return false;
			}
			return $im;
		}

		public function cropAndResize($im){
			$original = array('width' => imagesx($im), 'height' => imagesy($im));
			$offset = array('x'=>0,'y'=>0);
			$target = array('width'=>0, 'height'=> 0);

			//寬度固定 高度自動調整
			if ($this->options['resize_to'][1] == 'auto'){
				$target['width'] = $this->options['resize_to'][0];
				$target['height'] = $original['height'] / $original['width'] * $target['width'];

			//高度固定 寬度自動調整
			} else if($this->options['resize_to'][0] == 'auto'){
				$target['height'] = $this->options['resize_to'][1];
				$target['width'] = $original['width'] / $original['height'] * $target['height'];

			//有固定大小 套用裁切
			} else {

				list($target['width'], $target['height']) = $this->options['resize_to'];

				//固定大小事比率
				if (strpos($target['height'], "%")!==false){
					$target['height'] = intval($target['height']) * $original['height'] / 100;
				}

				if (strpos($target['width'], "%")!==false){
					$target['width'] = intval($target['width']) * $original['width'] / 100;
				}

				//比例較長 高度要裁切
				if ($target['height']/$target['width'] < $original['height']/$original['width']){
					//對上裁切
					if ($this->options['crop'][1] == 'top'){
						$offset['y'] = 0;

					//置中裁切
					} else if ($this->options['crop'][1] == 'middle'){
						$offset['y'] = ($target['height'] - $original['height'] / $original['width'] * $target['width']) / 2;

					//置底裁切
					} else if ($this->options['crop'][1] == 'bottom'){
						$offset['y'] = ($target['height'] - $original['height'] / $original['width'] * $target['width']);

					//自訂裁切點
					} else if (is_numeric($this->options['crop'][1])){
						$offset['y'] = $this->options['crop'][1];
					}
					$original['height'] = $target['height'] / $target['width'] * $original['width'];
				//比例較寬 寬度要裁切
				} else {
					//置左裁切
					if ($this->options['crop'][0] == 'left'){
						$offset['x'] = 0;

					//置中裁切
					} else if ($this->options['crop'][0] == 'center'){
						$offset['x'] = ($target['width'] - $original['width'] / $original['height'] * $target['height']) / 2;

					//置右裁切
					} else if ($this->options['crop'][0] == 'right'){
						$offset['x'] = ($target['width'] - $original['width'] / $original['height'] * $target['height']);

					//自訂裁切點
					} else if (is_numeric($this->options['crop'][0])){
						$offset['x'] = $this->options['crop'][0];
					}
					$original['width'] = $target['width'] / $target['height'] * $original['height'];

				}
			}
			$nim = imagecreatetruecolor($target['width'], $target['height']);
			imagecopyresized($nim, $im, 0, 0, $offset['x'] * -1, $offset['y'] * -1, $target['width'], $target['height'], $original['width'], $original['height']);
			return $nim;
		}

		public function filenameGenerator(){
			list($usec, $sec) = explode(" ", microtime());
			return date('Ymd_his_') . substr($usec, 2, 4) . ".png";
		}

		public function save($im, $filepath){
			switch (strtolower(pathinfo($filepath, PATHINFO_EXTENSION))){
				case "png":
					imagepng($im, $filepath);
					break;

				case "jpg":
					imagejpeg($im, $filepath);
					break;

				case "gif":
					imagegif($im, $filepath);
					break;

				default;
					return false;
			}
			return;
		}
	}

?>