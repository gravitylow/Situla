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
            <div class="dropdown">
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
                echo '</li><li data-toggle="dropdown" class="dropdown-toggle"><b class="caret"></b></a></li>';
            }
            else
            {
                echo '<li><a href ="http://situla.net/login">Log in</a></li>';
            }
            echo
            '
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                  <li><a href="http://situla.net/account"><i class="icon-th-list"></i> Projects</a></li>
                  <li><a href="http://situla.net/account/?p=alerts"><i class="icon-bell"></i> Alerts</a></li>
                  <li><a href="http://situla.net/account/?p=logout"><i class="icon-off"></i> Logout</a></li>
                </ul>
              </ul>
            </div>
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
        <div style="text-align:center;">
          Today\'s key: <img src="http://situla.net/image/image.php">
        </div>
      </footer>

    </div> <!-- /container -->

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/charcount.js"></script>
    <script src="../assets/js/alert.js"></script>
    <script src="../assets/js/checklist.js"></script>
  </body>
</html>
    ';
}
//Thanks to Eric Coleman
function auto_link_text($text)
{
   $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))(?=[^>]*(<|$))#';
   $callback = create_function('$matches', '
       $url       = array_shift($matches);
       $url_parts = parse_url($url);

       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
       $text = preg_replace("/^www./", "", $text);

       $last = -(strlen(strrchr($text, "/"))) + 1;
       if ($last < 0) {
           $text = substr($text, 0, $last) . "&hellip;";
       }

       return sprintf(\'<a rel="nofollow" href="%s">%s</a>\', $url, $text);
   ');

   return preg_replace_callback($pattern, $callback, $text);
}
?>