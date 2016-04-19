<?php
	class ArticleModel extends BaseModel {
		public $baseQuery = "
				select
					`article`.`id`,
					`title`,
					`content`,
					`time`,
					`user`.`name`,
					`user`.`id` as `user_id`,
					`club`.`name` as `club_name`,
					`club`.`id` as `club_id`,
					`publish`
				from `article`
				left join `user` on `article`.`user_id`=`user`.`id`
				left join `club` on `article`.`club_id`=`club`.`id`
		";
		public function findById($article_id){
			$sth = $this->db->prepare("{$this->baseQuery} where `article`.`id` = :article_id");
			$sth->bindValue(":article_id", $article_id);
			$sth->execute();
			return $sth->fetch();
		}

		public function getArticleList($where, $page=1){
			$sth = $this->db->prepare("{$this->baseQuery} where (".$where.") order by `id` desc limit :start_count,30");
			$sth->bindValue(":start_count", ($page - 1) * 30, PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetchAll();
		}
		public function getArticleCount($where){
			$sth = $this->db->prepare("select count(*) from `article` and (".$where.")");
			$sth->execute();
			return $sth->fetch()[0];
		}
		public function getByUserId($user_id){
			$sth = $this->db->prepare("{$this->baseQuery} where `article`.`user_id` = :user_id  order by `id` desc");
			$sth->bindValue(":user_id", $user_id);
			$sth->execute();
			return $sth->fetchAll();
		}

		public function getAllArticleList($page=1){
			return $this->getArticleList("1", $page);
		}
		public function getAllArticleCount($page=1){
			return $this->getArticleCount("1");
		}
		public function getSUArticleList($page=1){
			return $this->getArticleList("`article`.`club_id` = 1", $page);
		}
		public function getSUArticleCount(){
			return $this->getArticleCount("`article`.`club_id` = 1");
		}
		public function getClubArticleList($page=1){
			return $this->getArticleList("`article`.`club_id` > 1", $page);
		}
		public function getClubArticleCount(){
			return $this->getArticleCount("`article`.`club_id` > 1");
		}
		public function getPersonalArticleList($page=1){
			return $this->getArticleList("`article`.`club_id` = 0");
		}
		public function getPersonalArticleCount(){
			return $this->getArticleCount("`article`.`club_id` =0");
		}

		public function getSlideList(){
			$sth = $this->db->prepare("select * from `article` where  `time` < NOW() order by `id` desc");
			$sth->execute();
			return $sth->fetchAll();
		}
	}
?>