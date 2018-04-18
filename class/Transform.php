<?php

class Transform{
	
	const CRYPT = PASSWORD_BCRYPT;

	public static function mysqlBool($bool = null){

	    if (is_bool($bool)){
            if ($bool === true){
                return 'true';
            }else{
                return 'false';
            }
        }

	    return '';
    }
	
	public static function password($data){
        $options = [
            'cost' => 11
        ];

		return password_hash($data,self::CRYPT,$options);
	}
		
	static public function string($string){
        if (is_array($string)){
            return null;
        }
		return strval($string);
	}
	static public function bool($int){
		return boolval($int);
	}
	static public function int($int){
	    if (is_array($int)){
            return null;
        }

		return intval($int);
	}
	static function MYSQL($string){
		return str_replace('"','\"',trim(htmlspecialchars($string)));
	}
	static function mysqlString($string){
		return str_replace('"','\"',$string);
	}
}