<?php 
    $client_id = '928b2fe475ee182fc05a';
    $redirect_url = 'http://situla.net/login/github/callback.php';

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $url = "https://github.com/login/oauth/authorize?client_id=$client_id&redirect_uri=$redirect_url&scope=user";
        header("Location: $url");
    }
?>