<?php
$client_id = '928b2fe475ee182fc05a';		
//authorised at github
                if(isset($_GET['code']))
		{
			$code = $_GET['code'];
			$post = http_build_query(array(
				'client_id' => $client_id ,
				'redirect_uri' => $redirect_url ,
				'client_secret' => get_cfg_var("situla.github_secret"),
				'code' => $code ,
			));
			
			$context = stream_context_create(array("http" => array(
				"method" => "POST",
				"header" => "Content-Type: application/x-www-form-urlencoded\r\n" .
							"Content-Length: ". strlen($post) . "\r\n".
							"Accept: application/json" ,  
				"content" => $post,
			))); 
			$json_data = file_get_contents("https://github.com/login/oauth/access_token", false, $context);
			$r = json_decode($json_data , true);
			$access_token = $r['access_token'];
			$url = "https://api.github.com/user?access_token=$access_token";
			$data =  file_get_contents($url);
			$emails =  file_get_contents("https://api.github.com/user/emails?access_token=$access_token");
			$emails = json_decode($emails , true);
			$email = $emails[0];
                        session_start();
			$_SESSION['can_create_user'] = true;
                        $_SESSION['create_id'] = $email;
			header('Location: http://situla.net/login/callback.php?id='.$email);
		}
		else
		{
			$url = "https://github.com/login/oauth/authorize?client_id=$client_id&redirect_uri=$redirect_url&scope=user";
			header("Location: $url");
		}
?>
