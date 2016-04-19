<?php
	class imageController extends baseController {
		public function upload(){
			include "/plugin/imageUpload.php";
			$imageUpload = new ImageUpload(array('resize_to'=>array('960','auto')));
			$filename = $imageUpload->upload($_FILES['file']);
			echo("<script>window.parent.{$_POST['callback']}({file:'{$filename}'})</script>");
			exit();
		}
	}
?>