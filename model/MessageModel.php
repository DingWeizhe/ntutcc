<?php
	class MessageModel extends baseModel {
		public function findMessageAndUserByMarketId($market_id) {
			$sql = "SELECT * , market_message.id AS message_id
					FROM `market_message` 
					INNER JOIN `user`
						ON market_message.user_id = user.id
					WHERE market_message.market_id = :market_id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue("market_id", $market_id);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $data;
		}

		public function findReplyByMsgId($msg_id) {
			$sql = "SELECT * , market_reply.id AS reply_id
					FROM `market_reply`
					INNER JOIN `user`
						ON market_reply.user_id = user.id
					WHERE market_reply.msg_id = :msg_id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue("msg_id", $msg_id);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// debug($data);
			return $data;
		}

		public function addMsg() {
			$this->table_name = 'market_message';
			$this->fields = $this->getFields();
			if (is_array($data)){
				$data = array_select_keys($data, $this->fields);
				if (count($data) == 0) return;
				$data = $this->insertSQLdata($data);
				$sql = "INSERT INTO `market_message` (" . $data['key'] . ", `msg_time`) VALUES (" . $data['value'] . ", NOW())";
				$sth = $this->db->prepare($sql);
				$sth->execute();
			}
			return;
		}

		public function addReply($data) {
			$this->table_name = 'market_reply';
			$this->fields = $this->getFields();
			if (is_array($data)){
				$data = array_select_keys($data, $this->fields);
				if (count($data) == 0) return;
				$data = $this->insertSQLdata($data);
				$sql = "INSERT INTO `market_reply` (" . $data['key'] . ", `reply_time`) VALUES (" . $data['value'] . ", NOW())";
				$sth = $this->db->prepare($sql);
				$sth->execute();
			}
			return;
		}

		public function insertSQLdata($data) {
			$keys = array();
			$vals = array();
			foreach ($data as $key => $value){
				$keys[] = $key;
				$vals[] = "'$value'";
			}
			return array(
				"key" => implode(',', $keys),
				"value" => implode(',', $vals)
			);
		}
	}
?>