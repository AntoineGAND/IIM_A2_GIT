<?php

/**
 * Class BDD
 */
class BDD extends CONFIG{
    /**
     * @var null|PDO The connection to the database
     */
    static private $pdo;

    /**
     * @var array The params to connect to the database
     */

    /**
     * @param string $name The name to operate
     * @param string $operator The operator's type.The allowed values are "<>" and "=". The default value is "=".
     * @param string|integer|bool|array $value The value to operate
     * @return string Return a generate SQL operator
     */
    static public function getOperation($name, $operator = '=', $value){		
		switch($operator){
			case '<>': 
			case '=': 
				if (is_array($value)){
					
					if (count($value) == 1){
						return self::getOperation($name,$operator,$value[0]);
					}else{
						
						$i = 0;
						$keys = array_keys($value);
						$max = count($keys);

						while($i < $max){
							if (is_string($value[$keys[$i]])){
								$value[$keys[$i]] = '"'.Transform::mysqlString($value[$keys[$i]]).'"';
							}

							$i++;
						}
						
						$value = '('.join(',',$value).')';
					}
					
					if ($operator == '='){
						return '`'.$name.'` in '.$value;
					}else{
						return '`'.$name.'` not in '.$value;
					}
					
					
				}else if(is_string($value)){
					return '`'.$name.'`'.$operator.'"'.Transform::mysqlString($value).'"';
				}else if(is_numeric($value)){
					return '`'.$name.'`'.$operator.$value;
				}


            return 'false';
			break;
		}

		return 'false';
	}

    /**
     * @return PDO Return the connection to the database.
     */
    static public function getPDO(){
		
        if (is_null(BDD::$pdo)){
            try{
                BDD::$pdo = new PDO('mysql:host='.self::BDD_CONNECT['host'].';dbname='.self::BDD_CONNECT['dbname'].';charset='.self::BDD_CONNECT['charset'], self::BDD_CONNECT['login'], self::BDD_CONNECT['password'],array(PDO::ATTR_PERSISTENT  => true,PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
            }catch(Exception $e){
                var_dump($e);
            }
        }

        return BDD::$pdo;
    }

    /**
     * @return integer Return the ID of the last request.
     */
    static public function getID(){
		return self::getPDO()->lastInsertId();
	}

    /**
     * @param null $sql
     * @param string|bool $return Set what you want be returned by the function. The allowed value are true, false and "insert_id". The default value is false.
     * @return bool|null|int|array Return the number of lines changed if $return is equal to false, return the data if $type is equal to true or return the id insered by your request if $type is equal to "insert_id".
     */
    static public function query($sql = null, $return = false){
        if (!is_string($sql)){
			return null;
        }

        if ($return){
            $req = self::getPDO()->query($sql);

            if (!$req){
				return null;
			}
            
            if ($return === 'insert_id'){
				$retour = BDD::getPDO()->lastInsertId();
                $req->closeCursor();
				
				return $retour;
            }else{
                $retour = $req->fetchAll(PDO::FETCH_ASSOC);
                $req->closeCursor();
				
                return $retour;
            }
        }else{
            $req = self::getPDO()->exec($sql);
            return $req;
        }
    }
}