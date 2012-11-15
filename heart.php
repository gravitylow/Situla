<?php

function echoHeader($page, $name)
{
    echo
    '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Situla | '.$name.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand">Situla</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
            ';
            if($page == 1)
            {
                echo '<li class="active">';
            }
            else
            {
                echo '<li>';
            }
            echo '<a href="http://situla.net/">Home</a></li>';
            if($page == 2)
            {
                echo '<li class="active">';
            }
            else
            {
                echo '<li>';
            }
            echo '<a href="http://situla.net/about">About</a></li>';
            if($page == 3)
            {
                echo '<li class="active">';
            }
            else
            {
                echo '<li>';
            }
            echo '<a href="http://situla.net/projects">Projects</a></li>';
            echo
            '
            </ul>
            <ul class="nav pull-right">
            ';
            session_start();
            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
            {
                $name = $_SESSION['username'];
	         $alerts = $_SESSION['alerts'];
                echo '<li><a href ="http://situla.net/account">'.$name;
		  if($alerts != 0)
		  {
		      echo ' ('.$alerts.')';
		  }
                echo '</a></li>';
            }
            else
            {
                echo '<li><a href ="http://situla.net/login">Log in</a></li>';
            }
            echo
            '
            </ul>
          </div>
        </div>
      </div>
    </div>

    <br>
    ';
}

function echoFooter($alerts)
{
    echo
    '
      <hr>

      <footer>
        &copy; Situla 2012
        <center>
          Today\'s key: <img src="http://situla.net/image/image.php">
        </center>
      </footer>

    </div> <!-- /container -->

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/charcount.js"></script>
    ';
    if($alerts) echo '<script src="../assets/js/alert.js"></script>';
    echo
    '
  </body>
</html>
    ';
}
?>
