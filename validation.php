<?php
require('config/config.php');
require('model/functions.fn.php');
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if(	isset($_POST['username']) && isset($_POST['email1']) && isset($_POST['password1']) && isset($_POST['email']) && isset($_POST['password']) &&
    !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['email1']) && !empty($_POST['password1'])) {
		
	$username = $_POST ['username'];
	$email = $_POST ['email'];
	$password = $_POST ['password'];
	
	$error = verify_mdp($password);
	if ($error === true){
		
		$error = verify_mail($email);
		
		if ($error === true){
			if ($email == $_POST['email1']){
				if ($password == $_POST['password1']){
					if (isUsernameAvailable($db,$username) && isEmailAvailable($db,$email)){

						userRegistration($db,$username, $email, $password);

						userConnection($db, $email, $password);
						header('Location: dashboard.php');

					}elseif(!isEmailAvailable($db,$email)){

						$_SESSION['message'] = "Email indisponible";
						header('Location: register.php');

					}else{

						$_SESSION['message'] = "Username indisponible";
						header('Location: register.php');
					}
				}else{
					$_SESSION['message'] = "Vos mots de passe ne sont pas identiques";
					header('Location: register.php');
				}
			}else{
				$_SESSION['message'] = "Vos adresses emails ne sont pas identiques";
				header('Location: register.php');
			}
		}else{
			$_SESSION['message'] = $error;
			header('Location: register.php');
		}
	}else{
		$_SESSION['message'] = $error;
		header('Location: register.php');
	}
}else{
    $_SESSION['message'] = 'Erreur : Formulaire incomplet';
    header('Location: register.php');
}