<?php
	class MarketController extends baseController {
		private $img_dir = 'imgs/market/';
		private $max_size = 2097152; // 2M
		public function index($page = 1){
			if ($page == 0)	return $this->redirect('/market/1');

			$market = $this->Market->getCommoditys($page);
			$page_count = $this->Market->getPageCount();
			$this->set("market", $market);
			$this->set('page_count', $page_count);
			$this->set('page', $page);
			$this->display("market/market");
		}
		// list
		public function other($page){}
		public function detail($market_id = 0){
			if ($market_id == 0) return $this->redirect('/market/1');

			$commodity = $this->Market->findById($market_id);
			if ((!$commodity || $commodity['market_status'] == 'cancel') && $this->user['id'] != $commodity['user_id']) {
				$this->script('key.js');
				return $this->Error("可能是隱藏了或是沒這東西", "輸入↑ ↑ ↓ ↓ ← ← → → A B A B即可看見");
			}

			$count = $this->Market->getFbCommentCount($market_id);
			$this->Market->setCommentCount($count, $market_id);
			$type = array(
				"book" => "書籍",
				"3C" => "3C產品",
				"clothes" => "衣服",
				"other" => "其它"
			);
			$commodity['type'] = $type[$commodity['type']];
			$this->set('commodity', $commodity);
			$this->set('user', $this->user);
			$this->display("market/detail");
		}
		public function myMarket() {
			if (!$this->user) {
				return $this->Error("你還沒登入喔", "登入後還要驗證才可以使用喔 b(￣︿￣)d");
			}
			$market = $this->Market->findByUserId($this->user['id']);
			for ($i = 0; $i < count($market); $i++) { 
				if ($market[$i]['comment_count'] != $this->Market->getFbCommentCount($market[$i]['id'])) {
					$market[$i]['comment_count'] = true;
				} else {
					$market[$i]['comment_count'] = false;
				}
			}
			$this->set("market", $market);
			$this->display("market/myMarket");
		}
		public function post(){
			if (!$this->user) {
				return $this->Error("安捏不對喔", "登入後才可以刊登商品喔 揪咪^ _ <");
			}
			if (!$this->user['authenticate']) {
				return $this->Error("你還沒驗證齁", "驗證過後才可以刊登商品喔 揪咪^ _ <");
			}
			$this->display("market/post");
		}
		public function create(){
			if (!$this->user) {
				return $this->Error("安捏不對喔", "登入後才可以刊登商品喔 揪咪^ _ <");
			}
			if (!$this->user['authenticate']) {
				return $this->Error("你還沒驗證齁", "驗證過後才可以刊登商品喔 揪咪^ _ <");
			}
			if ($this->getMethod() == "POST") {
				$data = array(
					'name' => $this->getPOST('name'),
					'type' => $this->getPOST('type'),
					'description' => $this->getPOST('description'),
					'value' => $this->getPOST('value'),
					'status' => "sell",
					'user_id' => $this->user['id']
				);
				if ($data['name'] && $data['description']) {
					try {
						$img = new imageUpload();
						$thumbnail = $this->getFILES('thumbnail');
						list($usec, $sec) = explode(" ", microtime());
						$filename = date('Ymd_his_') . substr($usec, 2, 4) . ".png";
						$img_path = '..\\market\\' . $filename;
						$error = $img->upload($this->getFILES('thumbnail'), $img_path);
						if ($error != $img_path && $thumbnail['name'] != "") {
							$this->message($error);
						} else {
							if ($thumbnail['name'] != "") {
								$data['thumbnail'] = $filename;
							}
							$this->Market->addCommoditys($data);
							return $this->redirect("/market/myMarket");
						}
					} catch (Exception $e) {
						$this->message($e->getMessage());
					}
				} else {
					$this->message("資料不齊全呦T^T");
				}
			}
			return $this->display("market/post");
		}
		public function edit($market_id = 0){
			if (!$this->user) {
				return $this->Error("安捏不對喔", "登入後才可以刊登商品喔 揪咪^ _ <");
			}
			if (!$this->user['authenticate']) {
				return $this->Error("你還沒驗證齁", "驗證過後才可以刊登商品喔 揪咪^ _ <");
			}
			$commodity = $this->Market->findById($market_id);
			if ($commodity['user_id'] != $this->user['id']) {
				return $this->Error("這不是你的東西捏");
			}
			$this->set("commodity", $commodity);
			if ($this->getMethod() == "POST") {
				$data = array(
					'name' => $this->getPOST('name'),
					'type' => $this->getPOST('type'),
					'description' => $this->getPOST('description'),
					'status' => $this->getPOST('status'),
					'value' => $this->getPOST('value'),
					'user_id' => $this->user['id'],
				);
				if ($data['name'] && $data['description']) {
					try {
						$img = new imageUpload();
						$thumbnail = $this->getFILES('thumbnail');

						list($usec, $sec) = explode(" ", microtime());
						$filename = date('Ymd_his_') . substr($usec, 2, 4) . ".png";
						$img_path = '..\\market\\' . $filename;
						$error = $img->upload($this->getFILES('thumbnail'), $img_path);
						if ($error != $img_path && $thumbnail['name'] != "") {
							$this->message($error);
						} else {
							if ($thumbnail['name'] != "") {
								$result = $this->removeFile($commodity['thumbnail']);
								$data['thumbnail'] = $filename;
							}
							$this->Market->update($market_id, $data);
							return $this->redirect("/market/myMarket");
						}
					} catch (Exception $e) {
						$this->message($e->getMessage());
					}
				} else {
					$this->message("沒有輸入產品名稱或介紹呦T^T");
				}
			}
			return $this->display("/market/edit");
		}
		public function remove($market_id = 0){
			$remove = $this->getGET('remove');
			if (!$this->user) {
				return $this->Error("安安", "你是來亂的嗎？⊙皿⊙");
			}
			if (!$this->user['authenticate']) {
				return $this->Error("安安", "你是來亂的嗎？⊙皿⊙");
			}
			for ($i = 0; $i < count($remove); $i++) { 
				$commodity = $this->Market->findById($remove[$i]);
				if ($commodity['user_id'] != $this->user['id']) {
					return $this->Error("這不是你的東西捏");
				}
				$result = $this->removeFile($commodity['thumbnail']);
				$data = array("id" => $remove[$i]);
				$this->Market->delete($data);
				$this->redirect("market/myMarket");
			}
		}

		public function removeById($market_id = 0) {
			if ($market_id == 0) {
				return $this->redirect('/market/myMarket');
			}
			if (!$this->user) {
				return $this->Error("安安", "你是來亂的嗎？◎皿◎");
			}
			if (!$this->user['authenticate']) {
				return $this->Error("安安", "你是來亂的嗎？◎皿◎");
			}
			$commodity = $this->Market->findById($market_id);
			if ($commodity['user_id'] != $this->user['id']) {
				return $this->Error("這不是你的東西捏");
			}
			$data = array("id" => $market_id);
			$this->Market->delete($data);
			$this->redirect("market/myMarket");
		}

		public function checkFileTypeIsImage($file) {
			$file_type = explode("/", $file['type']);
			if ($file_type[0] != 'image') {
				return false;
			}
			return true;
		}
		public function checkFileSize($file) {
			if ($file['size'] > $this->max_size) {
				return false;
			}
			return true;
		}

		public function saveImage($img) {
			if ($img['error'] == 1) {
				$this->message("圖片超出容量限制了T^T");
			} else {
				if ($img['error'] == 0) {
					if (!$this->checkFileTypeIsImage($img)) {
						$this->message("只能上傳圖片喔");
					} else {
						if (!$this->checkFileSize($img)) {
							$this->message("阿喔，圖片超出容量限制了T^T");
						} else {
							$img['name'] = time() . $img['name'];
							$save_name = iconv('UTF-8', 'BIG5', $img['name']);
							$result = move_uploaded_file($img['tmp_name'], $this->img_dir . $save_name);
							return $img['name'];
						}
					}
				} elseif ($img['error'] != 4) {
					$this->message("發生不明的錯誤，請重新再試一次<(￣一￣ )");
				}
			}
			return false;
		}
		public function removeFile($file) {
			if ($file == '../no-img.png') return;
			$filename = iconv("UTF-8", "BIG5", $file);
			$r = false;
			if (is_file($this->img_dir . $filename)) {
				$r = unlink($this->img_dir . $filename);
				if (!$r) {
					error_log("market Image {$file} delete fail", 3, "error_log");
				}
			}
			return $r;
		}
		public function marketMessage($market_id = 0) {
			if ($market_id == 0) {
				return $this->Error("此留言已被隱藏", "輸入LF2中透明人的大招即可顯現");
			}
			$this->loadModel('Message');
			$msg = $this->Message->findMessageAndUserByMarketId($market_id);
			foreach ($msg as &$val) {
				$val['reply'] = $this->Message->findReplyByMsgId($val['message_id']);
			}
			$this->set("msgs", $msg);
			$this->display("market/message");
		}
		public function addReply($market_id = 0) {
			if (!$this->user) {
				$this->Error("你還沒登入齁", "要先登入才能回覆喔");
			}
			if ($this->getMethod() == "POST") {
				$data = array(
					"user_id" => $this->user['id'],
					"msg_id" => $this->getPOST('msg_id'),
					"reply" => $this->getPOST('reply')
				);
				$this->loadModel('Message');
				$this->Message->addReply($data);
				debug($data);
			}
			return $this->redirect("/market/marketMessage/{$market_id}");
		}
		public function comment_count() {
			$url = "http://ntut.cc/market/detail/39";
			echo "http://api.facebook.com/restserver.php?method=comment_count.getStats&urls=" . urlencode($url);
		}
	}
?>