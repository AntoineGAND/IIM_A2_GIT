<?php
	class SESSION{
		static public function setUserID($id = null){
			$_SESSION['id'] = $id;
		}
		static public function getUserID(){
			return $_SESSION['id'];
		}
	}
?>