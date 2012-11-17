<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false)
    {
        header('Location: http://situla.net/login?alert');
    }
    else if(isset($_GET['p']))
    {
        $page = $_GET['p'];
        if($page == 'logout')
        {
            session_destroy();
            header('Location: http://situla.net/');
        }
    }
    include('../heart.php');
    echoHeader(0, "Account");

    if(isset($_GET['clear']))
    {
        $_SESSION['alerts'] = 0;
    }
?>
    <div class="container">
      <div class="well well-small" style="text-align:center;">
        <a href="gravatar.php"><img src="<?php echo $_SESSION['gravatar']; ?>?s=100"></a>
        <br><br>
	<a class="btn btn-danger" href="?p=logout"><i class="icon-off icon-white"></i> Log out</a>
      </div>
      <div class="well">
      <div class="tabbable">
        <ul class="nav nav-tabs">
<?php
if($_GET['p'] == 'alerts')
{
   echo 
    '
      <li><a href="#t1" data-toggle="tab">Projects</a></li>
      <li class="active"><a href="#t2" data-toggle="tab">Alerts</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane" id="t1">
    ';
}
else
{
    echo 
    '
      <li class="active"><a href="#t1" data-toggle="tab">Projects</a></li>
      <li><a href="#t2" data-toggle="tab">Alerts</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="t1">
    ';
}

$user = $_SESSION['username'];
include('../config.php');
if($result = $conn->query('SELECT project, id FROM situla.projects WHERE user=\''.$user.'\''))
{
        echo
        '
        <table class="table table-bordered table-hover">
          <tbody>
        ';

    while($row = $result->fetch_assoc())
    {
        echo '<a href="http://situla.net/projects/?project='.$row['id'].'"><strong>'.$row['project'].'</strong></a>';
        echo '<br><br>';
    }
echo
        '
          </tbody>
        </table>
        '; 
}
if($_GET['p'] == 'alerts')
{
    echo 
    '
      </div>
      <div class="tab-pane active" id="t2">
    ';
}
else
{
    echo 
    '
      </div>
      <div class="tab-pane" id="t2">
    ';
}
if($result = $conn->query('SELECT text, id FROM situla.alerts WHERE user=\''.$user.'\' ORDER BY id DESC LIMIT 0, 10'))
{
    if ($result->num_rows > 0)
    {
        echo
        '
        <table class="table table-bordered table-hover">
          <tbody>
        ';
        while($row = $result->fetch_assoc())
        {
            echo '<tr><td>'.$row['text'].'</td>';
        } 
        echo
        '
          </tbody>
        </table>
        '; 
        echo '<a class="btn btn-success" href="?clear&p=alerts"><i class="icon-ok-circle icon-white"></i> Mark alerts read</a>';      
    }
    else
    {
        echo '<p>You currently have no new alerts.</p>';
    }
}

?>        
            </div>
          </div>
        </div>
      </div>
<?php
    echoFooter();
?>

