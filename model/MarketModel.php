<?php
	class MarketModel extends BaseModel {
		private $show_count = 12;

		public function getCommoditys($page = 1) {
			$data_start = ($page-1) * 12;
			$sql = "SELECT * FROM `market` WHERE `status` != 'cancel' ORDER BY `soldDate` DESC LIMIT {$data_start}, {$this->show_count}";
			$stmt = $this->db->query($sql);
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$this->setNoImage($data);
			for ($i = 0; $i < count($data); $i++) {
				$data[$i]['soldDate'] = $this->setDate($data[$i]['soldDate']);
			}
			return $data;
		}

		public function getPageCount() {
			$sql = "SELECT COUNT(*) FROM `market` WHERE `status` != 'cancel'";
			$stmt = $this->db->query($sql);
			$count = $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
			$count = $count / $this->show_count;
			$count = ceil($count);
			return $count;
		}

		public function findById($market_id)	{
			$sql = "SELECT * , `user`.`name` as `user_name`, `market`.`name` as `market_name`, `market`.`id` as `market_id`, `market`.`status` as `market_status`
					FROM `market`
					INNER JOIN `user`
						ON `market`.`user_id` = `user`.`id`
					WHERE `market`.`id`=:id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindvalue("id", $market_id);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->setNoImage($data, false);
			if ($data) {
				$data['soldDate'] = $this->setDate($data['soldDate']);
			}
			return $data;
		}

		public function findByUserId($user_id){
			$sql = "SELECT * FROM `market` WHERE `user_id`=:user_id ORDER BY `soldDate` DESC";
			$stmt = $this->db->prepare($sql);
			$stmt->bindvalue("user_id", $user_id);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$this->setNoImage($data);
			return $data;
		}
		public function addCommoditys($data) {
			if (is_array($data)){
				$data = array_select_keys($data, $this->fields);
				if (count($data) == 0) return;
				foreach ($data as $key => $value){
					$keys[] = $key;
					$vals[] = "'$value'";
				}
				$sth = $this->db->prepare("insert into `market` (".implode(",", $keys).", `soldDate`) values (".implode(",", $vals).", NOW())");
				$sth->execute();
			}
			return;
		}
		public function findByKeyWord($keyword) {
			$sql = "SELECT * FROM `market` WHERE `name` LIKE '%$keyword%' AND `status` != 'cancel' ORDER BY `soldDate` DESC";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$this->setNoImage($data);
			return $data;
		}
		private function setNoImage(&$data_array, $array = true) {
			if ($data_array) {
				if ($array) {
					foreach ($data_array as &$data) {
						if (!$data['thumbnail']) {
							$data['thumbnail'] = '../no-img.png';
						}
					}
				} else {
					if (!$data_array['thumbnail']) {
						$data_array['thumbnail'] = '../no-img.png';
					}
				}
			}
		}
		public function setDate($date) {
			if ($date) {
				$today = time();
				$date = explode(' ', $date);
				$time = explode(':', $date[1]);
				$date = explode('-', $date[0]);
				$date = date(mktime($time[0], $time[1], $time[0], $date[1], $date[2], $date[0]));
				$diff = ($today - $date);
				if ($diff > 86400) {
					$diff = $diff / 86400;
					return round($diff) . "天前";
				} elseif ($diff > 3600) {
					$diff = $diff / 3600;
					return round($diff) . "小時前";
				} elseif ($diff > 60) {
					$diff = $diff / 60;
					return round($diff) . "分鐘前";
				} else {
					return round($diff) . "秒前";
				}
			}
			return $date;
		}

		public function setCommentCount($count, $id) {
			$sql = "UPDATE `market` SET `comment_count` = {$count} WHERE `id` = {$id}";
			$stmt = $this->db->prepare($sql);
			$request = $stmt->execute();
			return $request;
		}
	}
?>