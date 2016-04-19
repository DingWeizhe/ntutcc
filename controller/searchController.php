<?php
	class SearchController extends baseController {
		public function form(){
			$this->display("search/form");
		}
		public function index(){
			$this->redirect("/user/{$_POST['keyword']}");
		}
		public function market() {
			$keyword = $this->getGET('keyword');
			if ($keyword == "") {
				$market = array();
			} else {
				$this->loadModel('Market');
				$market = $this->Market->findByKeyWord($keyword);
			}
			$this->set("market", $market);
			$this->set("keyword", $keyword);
			$this->display("market/market");
		}
	}
?>