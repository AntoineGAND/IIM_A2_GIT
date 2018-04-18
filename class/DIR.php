<?php
	class Dir{
		static private $time;

		const TEMP = [
            
        ];

		
		static public function empty($dir){
			if (file_exists(self::getUrl($dir))){
				self::truncate($dir);
			}else{
				self::create($dir);
			}
		}

		static public function getUrl($dir){
			return self::getParent().$dir;
		}

		static public function get($dir){
		    $dir = self::getUrl($dir);

		    if (!file_exists($dir)){
		        return [];
            }

			return array_values(array_filter(scandir($dir),function($v){
				return ($v !== 'index.php' and $v != '..' and $v != '.');
			},ARRAY_FILTER_USE_BOTH));
		}

		static public function files($dir){
			$scan = self::get($dir);

			$i = 0;
			$keys = array_keys($scan);
			$max = count($keys);

			$return = [];

			while($i < $max){
				if (is_file(Dir::getUrl($dir.$scan[$keys[$i]]))){
					$return[] = $scan[$keys[$i]];
				}

				$i++;
			}

			return $return;
		}

		static public function create($dir,$treeStart = 0){
			$split = explode('/',$dir);

			$i = $treeStart;
			$max = count($split);

			$file = self::getParent();

			while($i < $max){
				if (strlen($split[$i]) > 0){

					$file = $file.$split[$i].'/';

					if (!file_exists($file)){
						
						echo $file;
						
						mkdir($file);
					}
				}

				$i++;
			}

			return null;
		}

		static public function truncate($dir){
			self::rmdir($dir,false);
		}

		static public function getDir($dir,$param,$url = false){
			$keys = array_keys($param);
			$i = 0;
			$max = count($keys);

			while($i < $max){

			    if (is_array($param[$keys[$i]])){
			        return '';
                }

				$dir = str_replace('{'.$keys[$i].'}',$param[$keys[$i]],$dir);

				$i++;
			}

			return $dir;
		}

		public static function getJson($filename = ''){
            $open = self::files(self::getUrl($filename));

            if (count($open) > 0){
                return $open[0];
            }

            return null;
        }

		static public function rmdir($dir,$removeFolder = true){
			if (is_string($dir)){

			    $urlDir = self::getUrl($dir);

			    if (mb_substr_count($dir,'files/') === 0 and mb_substr_count($dir,'admin/app/') === 0){
                    return null;
                }

				if (is_dir($urlDir)){
					$objects = scandir($urlDir);
					foreach ($objects as $object) {
						if ($object != "." && $object != "..") {
							if (is_dir($urlDir."/".$object)){
								Dir::rmdir($dir."/".$object);
							}else{
                                Log::info($dir."/".$object.' - removed');
								unlink($urlDir."/".$object);
							}
						}
					}
					reset($objects);

					if ($removeFolder === true){
						rmdir($urlDir);
					}
				}
			}elseif(is_array($dir)){
				foreach($dir as $d){
					Dir::rmdir($d);
				}
			}

			return null;
		}

		static public function copy($src,$dst) {
            $srcDir = self::getUrl($src);
            $srcDst = self::getUrl($dst);

            if ((mb_substr_count($src,'admin/app/') === 0 and mb_substr_count($src,'files/') === 0) or (mb_substr_count($dst,'admin/app/') === 0 and mb_substr_count($dst,'files/') === 0)){
                return null;
            }

            if (is_dir($srcDir)){
                if (!file_exists($srcDst)) {
                    mkdir($srcDst);
                }

                $objects = Dir::files($src);
                foreach ($objects as $object){
                    if ($object != "." && $object != "..") {
                        if (is_dir($srcDir."/".$object)){
                            self::copy($src.$object.'/',$dst.$object.'/');
                        }else{
                            copy($srcDir."/".$object,$srcDst."/".$object);
                        }
                    }
                }
            }

			return true;
		}  
		
		static public function getParent(){
			return str_repeat('../',(mb_substr_count($_SERVER['PHP_SELF'],'/') - 1)).'IIM_A2_GIT/';
		}
	}