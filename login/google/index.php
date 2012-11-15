<?php
require_once 'openid.php';
$openid = new LightOpenID("situla.net");
 
$openid->identity = 'https://www.google.com/accounts/o8/id';
$openid->required = array(
  'namePerson/first',
  'namePerson/last',
  'contact/email',
);
$openid->returnUrl = 'http://situla.net/login/google/callback.php';
header('Location: '.$openid->authUrl());
?>