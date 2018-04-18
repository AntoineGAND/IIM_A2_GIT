<?php
class Verify{
	const FORBIDDEN = '\'"1234567890&#{([-|\\_^@)]°=+}$£¤µ*%§/.?:!<>²';


	static public function request(&$array,$verify = []){
        if (
            !isset($array)
            or empty($array)
            or !is_array($array)
        ){
            return false;
        }

        $keys = array_keys($verify);
        $max = count($keys);
        $i = 0;

        while($i < $max){

            if (
                !array_key_exists($keys[$i],$array)
                or !isset($array[$keys[$i]])
            ){
                return false;
            }

            switch ($verify[$keys[$i]]){
                case 'string':
                    $array[$keys[$i]] = Transform::string($array[$keys[$i]]);
                    if (!is_string($array[$keys[$i]])){
                        return false;
                    }

                    break;
                case 'int':
                    $array[$keys[$i]] = Transform::int($array[$keys[$i]]);
                    if (!is_numeric($array[$keys[$i]])){
                        return false;
                    }

                    break;
                case 'bool':
                    $array[$keys[$i]] = Transform::bool($array[$keys[$i]]);
                    if (!is_bool($array[$keys[$i]])){
                        return false;
                    }

                    break;
                case 'file':
                    if (!is_array($array[$keys[$i]])){
                        return false;
                    }
                    break;
                case '':
                    break;
                default:
                    return false;

                    break;
            }

            $i++;
        }

        return true;
    }

    static public function files($verify = []){
        $return = self::request($_FILES,$verify);

        return $return;
    }

	static public function post($verify = []){
        $return = self::request($_POST,$verify);

        return $return;
	}

    static public function get($verify = []){
        $return = self::request($_GET,$verify);

        return $return;
    }
	
	static public function username($pseudo = null){
		if (!empty($pseudo)){
			if (isset($pseudo{4})){
				if (!isset($pseudo{15})){
					if (mb_substr_count($pseudo,' ') == 0){
						return true;
					}else{
						return 'Votre username doit ne peut pas contenir d\'espaces.';
					}
				}else{
					return 'Votre username doit ne peut pas contenir plus de 15 caractères.';
				}
			}else{
				return 'Votre username doit contenir au moins 5 caractères.';
			}
		}else{
			return 'Votre username est vide.';
		}
	}

	static public function password($password = null,$password1 = null){
		if (!is_string($password)){
			return 'Une erreur inconnue s\'est produite lors de la vérification de votre mots de passe.';
		}
		
		if (!empty($password)){
			if (isset($password{5})){
					if (is_null($password1) or $password1 === $password){
						return true;
					}else{
						return 'Vos mots de passe ne sont pas identiques.';
					}
			}else{
				return 'Votre mots de passe doit posséder au moins 5 caractères.';
			}
		}else{
			return 'Votre mots de passe est vide.';
		}
	}

	static public function email($email = null,$email1 = null){
		if (!empty($email)){
			if (filter_var($email,FILTER_VALIDATE_EMAIL)){
				if (is_null($email1) or $email1 === $email){
					return true;
				}else{
					return 'Vos adresses emails ne sont pas identiques.';
				}
			}else{
				return 'Votre adresse email a une syntaxe incorrect.';
			}
		}else{
			return 'Votre adresse email est vide.';
		}
	}
}