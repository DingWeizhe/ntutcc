<?php
	class baseModel {
		protected $db;
		protected $table_name;
		protected $fields;

		public function __construct($db, $table_name = false){
			$this->db = $db;
			if (!$table_name) $table_name = get_called_class();
			$this->table_name = substr($table_name,0, strlen($table_name) - 5);
			if ($this->table_name != "base"){
				$this->fields = $this->getFields();
			}
		}

		public function create($data){
			if (is_array($data)){
				$data = array_select_keys($data, $this->fields);
				if (count($data) == 0) return;
				foreach ($data as $key => $value){
					$keys[] = $key;
					$vals[] = ":$key";
					$bindVal[":$key"] = $value;
				}
				$sth = $this->db->prepare("insert into `{$this->table_name}` (".implode(",", $keys).") values (".implode(",", $vals).")");
				$sth->execute($bindVal);
				return $this->db->lastInsertId();
			}
			return;
		}
		public function delete($data){
			if (is_array($data)){
				$data = array_select_keys($data, $this->fields);
				if (count($data) == 0) return;
				foreach ($data as $key => $value){
					$condition[] = "`$key` = '$value'";
				}
				$sth = $this->db->prepare("delete from `{$this->table_name}` where " . implode(" and ", $condition));
				$sth->execute();
			}
			return;
		}
		public function update($id, $data){

			if (is_array($data)){
				$data = array_select_keys($data, $this->fields);

				if (count($data) == 0) return;

				foreach ($data as $key => $value){
					$updateFields[] = "`{$key}` = :{$key}";
				}
				$updateFieldsStr = implode(",", $updateFields);
				$sth = $this->db->prepare("update `{$this->table_name}` set {$updateFieldsStr} where `id` = :id");
				foreach ($data as $key => $value){
					$sth->bindvalue(":{$key}", $value);
				}
				$sth->bindvalue(":id", $id);
				$sth->execute();
			}
			return;
		}

		public function getFields(){
			$sth = $this->db->prepare("SHOW COLUMNS FROM `{$this->table_name}`");
			$sth->execute();
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			$fields = array();
			foreach ($data as $key => $value){
				$fields[] = strtolower($value['Field']);
			}
			return $fields;
		}


		public function findAll(){
			$sth = $this->db->prepare("select * from `{$this->table_name}`");
			$sth->execute();
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $data;
		}

		public function findById($id){
			$id = intval($id);
			$sth = $this->db->prepare("select * from `{$this->table_name}` where `id` = {$id}");
			$sth->execute();
			$row = $sth->fetch(PDO::FETCH_ASSOC);
			return  $row;
		}

		public function getCommentHit($user_id) {
			$sql = "SELECT * FROM `market` WHERE `user_id` = {$user_id}";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$comment = 0;
			for ($i = 0; $i < count($data); $i++) {
				$comment += ($this->getFbCommentCount($data[$i]['id']) - $this->getCommentCount($data[$i]['id']));
			}
			return $comment;
		}

		public function getFbCommentCount($id) {
			$url = "http://ntut.cc/market/detail/" . $id;
			$url = "http://api.facebook.com/restserver.php?method=links.getStats&urls=" . $url;
			$xml = simplexml_load_file($url);
			$new_comments = $xml->link_stat->comment_count;
			return ($new_comments);
		}

		public function getCommentCount($id) {
			$stmt = $this->db->prepare("SELECT `comment_count` FROM `market` WHERE `id` = {$id}");
			$stmt->execute();
			$comments = $stmt->fetch(PDO::FETCH_ASSOC)['comment_count'];
			return $comments;
		}
	}
?>