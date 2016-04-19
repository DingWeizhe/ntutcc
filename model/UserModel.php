<?php
	class UserModel extends BaseModel {
		public $userDetailQuery = "
				select
					`user`.*,
					`club`.`id` as `club_id`,
					`club`.`name` as `club_name`,
					`department`.`id` as `department_id`,
					`department`.`name` as `department_name`
				from
					user
				left join department on `user`.`department_id` = `department`.`id`
				left join `club` on `user`.`club_id` = `club`.`id`
		";
		public function getRandom($count){
			$sth = $this->db->prepare("SELECT * FROM `user` ORDER BY RAND() LIMIT $count");
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		public function findByStudentId($student_id){
			$sth = $this->db->prepare($this->userDetailQuery . ' WHERE `user`.`student_id` = "'.$student_id.'" and `authenticate` = 1');
			$sth->execute();
			return $sth->fetch(PDO::FETCH_ASSOC);
		}
		public function findById($user_id){
			$sth = $this->db->prepare($this->userDetailQuery . ' WHERE `user`.`id` = :id');
			$sth->execute(array(':id' => $user_id));
			return $sth->fetch(PDO::FETCH_ASSOC);
		}

		public function findByFbId($fb_id){
			$sth = $this->db->prepare($this->userDetailQuery . ' WHERE `user`.`fb_id` = :fb_id');
			$sth->execute(array(':fb_id' => $fb_id));
			return $sth->fetch(PDO::FETCH_ASSOC);

		}

		public function create($data){
			$sth = $this->db->prepare("
				insert into `user`
				(`fb_id`,`name`,`email`,`birthday`,`gender`) value (:fb_id, :name, :email, :birthday, :gender)
			");

			$sth->execute(addColon($data));
			return $this->db->lastInsertId();
		}

		public function updateStudentIdAndAuthenticateCode($user_id, $student_id, $authenticationCode){
			$sth = $this->db->prepare("
				update `user`
				set `student_id` = :student_id, `authenticationCode` = :authenticationCode
				where `id` = :user_id
			");
			$sth->bindParam(':user_id', $user_id);
			$sth->bindParam(':student_id', $student_id, PDO::PARAM_INT);
			$sth->bindParam(':authenticationCode', $authenticationCode, PDO::PARAM_STR, 32);
			$sth->execute();
			return;
		}

		public function authenticate($id){
			$sth = $this->db->prepare("
				update `user`
				set `authenticate` = true
				where `id` = :id
			");
			$sth->bindParam(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			return;
		}

		public function registerFromFacebook($facebookData){
			$birthday = new DateTime($facebookData['birthday']);
			$user_id = $this->create(array(
				'fb_id' => $facebookData['id'],
				'name' => $facebookData['name'],
				'email' => @$facebookData['email'],
				'gender' => $facebookData['gender'],
				'birthday' => @date_format($birthday, 'Y-m-d')
			));
			return $user_id;
		}

		public function findAllUser($page,$gender=0){
			if ($gender){
				$sth = $this->db->prepare($this->userDetailQuery . ' where `gender` = "'.$gender.'" order by `user`.`id` desc limit '.($page*30).', 30');

			} else {
				$sth = $this->db->prepare($this->userDetailQuery . ' order by `user`.`id` desc limit '.($page*30).', 30');
			}

			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		public static function isLogin(){

			return isset($_SESSION['user']);
		}
	}
?>