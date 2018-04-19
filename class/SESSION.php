<?php
	class SESSION{
		private static $started = false;
		
		static private function start(){
			if (self::$started === false){
				session_start();
				self::$started = true;
			}
		}
		static public function removeUserID(){
			self::start();
			
			unset($_SESSION['id']);
		}
		static public function setUserID($id = null){
			self::start();
			
			$_SESSION['id'] = $id;
		}
		static public function getUserID(){
			self::start();
			
			if (!array_key_exists('message',$_SESSION)){
				return null;
			}else{
				return $_SESSION['id'];
			}
		}
		
		static public function setSucces($succes){
			self::start();
			
			if (!array_key_exists('message',$_SESSION)){
				$_SESSION['message'] = [];
			}
			
			$_SESSION['message']['succes'] = $succes;
		}
		static public function getSucces(){
			self::start();
			
			if (!array_key_exists('message',$_SESSION) or !array_key_exists('succes',$_SESSION['message'])){
				return null;
			}else{
				$return = $_SESSION['message']['succes'];
				unset($_SESSION['message']['succes']);
				
				return $return;
			}
		}
		
		static public function setError($error){
			self::start();
			
			if (!array_key_exists('message',$_SESSION)){
				$_SESSION['message'] = [];
			}
			
			$_SESSION['message']['error'] = $error;
		}
		static public function getError(){
			self::start();
			
			if (!array_key_exists('message',$_SESSION) or !array_key_exists('error',$_SESSION['message'])){
				return null;
			}else{
				$return = $_SESSION['message']['error'];
				unset($_SESSION['message']['error']);
				
				return $return;
			}
		}
	}
?>