<?php
	class SearchModel extends baseModel {
		public function __construct($db, $table_name = false){
			$this->db = $db;
		}
	}
?>