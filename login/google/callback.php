<?php
require_once 'openid.php';
$openid = new LightOpenID("situla.net");
 
if ($openid->mode) 
{
	if ($openid->mode == 'cancel') 
    	{
        	header('Location: http://situla.net/login');
    	} 
   	else if($openid->validate()) 
    	{
       	$data = $openid->getAttributes();
		$id = $data['contact/email'];
		session_start();
		$_SESSION['can_create_user'] = true;
                $_SESSION['create_id'] = $id;
		header('Location: http://situla.net/login/callback.php?id='.$id);
    	} 
    	else 
    	{
       	header('Location: http://situla.net/login');
    	}
} 
else 
{
    header('Location: http://situla.net/login');
}
?>
