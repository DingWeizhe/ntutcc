<?php
	class ArticleController extends baseController{
		public function my(){
			if (!$this->user) $this->Error("你還沒有登入喔", "登入後還要驗證才可以使用喔 b(￣︿￣)d");
			$this->set("list", $this->Article->getByUserId($this->user['id']));
			$this->display("article/my");
		}

		public function index(){
			$slideList = $this->Article->getSlideList();
			$list = $this->Article->getAllArticleList(1);
			$this->set("list", $list);
			$this->set("slide_list", $slideList);
			$this->set("su_list", $this->Article->getSUArticleList());
			$this->set("other_club_list", $this->Article->getClubArticleList());
			$this->set("personal_list", $this->Article->getPersonalArticleList());
			return $this->display("article/wall");
		}

		public function all($page=1){
			$count = $this->Article->getAllArticleCount();
			if ($page <= 0){ $this->redirect("/article/all/1"); }
			else if ($page > ceil($count / 7)+1){  $this->redirect("/article/all/" . ceil($count / 7)); }
			$this->set("list", $this->Article->getAllArticleList(1));
			$this->set("count", $count);
			$this->display("article/page");
		}

		public function su($page=1){
			$count = $this->Article->getSUArticleCount(1);
			if ($page <= 0){ $this->redirect("/article/su/1"); }
			if ($page > ceil($count / 7)){  $this->redirect("/article/su/" . ceil($count / 7)); }
			$this->set("list", $this->Article->getSUArticleList($page));
			$this->set("count", $count);
			$this->display("article/page");
		}

		public function club($page=1){
			$count = $this->Article->getClubArticleCount();
			if ($page <= 0){ $this->redirect("/article/club/1"); }
			if ($page > ceil($count / 7)){  $this->redirect("/article/club/" . ceil($count / 7)); }
			$this->set("list", $this->Article->getClubArticleList($page));
			$this->set("count", $count);
			$this->display("article/page");
		}

		public function personal($page=1){
			$count = $this->Article->getPersonalArticleCount();
			if ($page <= 0){ $this->redirect("/article/personal/1"); }
			if ($page > ceil($count / 7)){  $this->redirect("/article/personal/" . ceil($count / 7)); }
			$this->set("list", $this->Article->getPersonalArticleList($page));
			$this->set("count", $count);
			$this->display("article/page");
		}

		public function read($id){
			$article = $this->Article->findById($id);
			if (!$article){
				$this->Error("嗝~~~","我絕對沒有把網頁吃掉, 它本來就不存在了喔");
			}
			if ($article['publish']){
				$this->Error("啊哈!", "這邊文章是秘密喔~ 才不要給你看哩!");
			}
			$this->set("su_list", $this->Article->getSUArticleList());
			$this->set("other_club_list", $this->Article->getClubArticleList());
			$this->set("personal_list", $this->Article->getPersonalArticleList());
			$this->set("article", $article);
			$this->display("article/read");
		}

		public function post(){
			if (!$this->user) $this->Error("阿哈", "沒有登入是無法發表文章的喔");
			if ($this->getPOST('title') != ""){
				$title = $this->getPOST("title");
				$datetime = date("Y-m-d H:i:s", strtotime($this->getPOST('date') . " " . $this->getPOST('time')));
				$content = $this->getPOST("content");
				$content = htmlspecialchars_decode($content);
				$content = strip_tags($content, "<p><div><a><img><embed><span><br>");
				if ( $this->getPOST('club_id') != 0 && $this->getPOST('club_id') == $this->user['club_id']){
					$club_id = $this->getPOST('club_id');
				} else {
					$clib_id = 0;
				}
				$article_id = $this->Article->create(array(
					'title' => $title,
					'time' => $datetime,
					'content' => $content,
					'user_id' => $this->user['id'],
					'club_id' => $this->user['club_id']
				));

				$this->redirect("/article/read/{$article_id}");
			}
			$_SESSION['access_token'] = md5(time() . SALT);
			$this->set("access_token", $_SESSION['access_token']);
			$this->display("article/post");
		}

		public function edit($id){
			$article = $this->Article->findById($id);
			if ($article['user_id'] != $this->user['id']) $this->Error("沒有權限", "你不是這篇文章的主人, 無法修改這篇文章喔!");
			if ($this->getPOST('access_token') == @$_SESSION['access_token']){
				$title = $this->getPOST("title");
				$datetime = date("Y-m-d H:i:s", strtotime($this->getPOST('date') . " " . $this->getPOST('time')));
				$content = $this->getPOST("content");
				$content = htmlspecialchars_decode($content);
				$content = strip_tags($content, "<p><div><a><img><embed><span><br>");
				$this->Article->update($id, array(
					'title' => $title,
					'time' => $datetime,
					'content' => $content
				));
				$this->redirect("/article/read/{$id}");
			}
			if (isset($this->user)) $this->set("user", $this->user);
			$this->set("article", $article);
			$_SESSION['access_token'] = md5(time() . SALT);
			$this->set("access_token", $_SESSION['access_token']);
			return $this->display("article/edit");
		}

		public function delete($id, $checker=0){
			$article = $this->Article->findById($id);
			if ($article['user_id'] != @$this->user['id']) $this->Error("沒有權限", "這篇文章不是你發表的, 不要亂砍阿!!@#!");
			if ($checker){
				$this->Article->delete(array('id'=>$id));
				$this->redirect("/article/my");
			} else {
				$this->set("article", $article);
				$this->display("article/check_delete");
			}

		}

	}
?>