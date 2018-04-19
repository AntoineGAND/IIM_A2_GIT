<?php
	require_once 'model/functions.fn.php';

	$data = [];
	$error = [];

	if (Verify::get(['get' => 'string'])){
		$get = explode(',',$_GET['get']);
		
		$i = 0;
		$max = count($get);
		while($i < $max){
			switch(trim($get[$i])){
				case 'users':
					$array = [];
					
					if (Verify::get(['email' => 'string'])){
						$array['email'] = $_GET['email'];
					}
					if (Verify::get(['id_user' => 'int'])){
						$array['id'] = $_GET['id_user'];
					}
					if (Verify::get(['username' => 'string'])){
						$array['username'] = $_GET['username'];
					}
					
					if (count($array) > 0){
						$data = User::json(User::get($array));
					}else{
						$data = [];
						
						$users = User::getList();
						
						$i=0;
						$max = count($users);
						while($i < $max){
							
							$data[] = User::json($users[$i]);
							
							$i++;
						}
					}
				break;
				case 'musics':
					$array = [];
					
					if (Verify::get(['id_music' => 'int'])){
						$data = MUSIC::json(MUSIC::get($_GET['id']));
					}else{
						$data = [];
						
						$users = MUSIC::getList();
						
						$i=0;
						$max = count($users);
						while($i < $max){
							
							$data[] = MUSIC::json($users[$i]);
							
							$i++;
						}
					}
				break;
			}
			
			$i++;
		}
	}
	
	echo json_encode([
		'data' => $data,
		'error' => $error,
	]);
?>