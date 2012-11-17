<?php
session_start();
if (!isset($_SESSION['can_create_user']) || !$_SESSION['can_create_user'])
{
    header('Location: http://situla.net/login');
}
else
{
    $_SESSION['can_create_user'] = false;
    include('../heart.php');
    echoHeader(0, "Login");
}
?>
    <div class="container">
      <div class="well">
<?php
if(isset($_GET['id']))
{
    $id = $_GET['id'];
    if(isset($_POST['username']))
    {
        $username = $_POST['username'];
        if(!hasUsername($username))
        {
            setUsername($id, $username);
        }
        else
        {
            alreadyInUse();
            makeUsername();
        }
    }
    else if(isset($_POST['useEmail']))
    {
        if(!hasUsername($id))
        {
            setUsername($id, $id);
        }
        else
        {
            alreadyInUse();
            makeUsername();
        }
    }
    else
    {
        if(hasAccount($id))
        {
            $_SESSION['loggedin'] = true;
            $info = getInfo($id);
            $_SESSION['username'] = $info[0];
            $_SESSION['gravatar'] = $info[1];
            $_SESSION['id'] = $id;
            $_SESSION['alerts'] = getAlerts($info[0]);
            header('Location: http://situla.net');
        }
        else
        {
            makeUsername();
        }
    }
}
else
{
    header('Location: http://situla.net/login');
}

function getAlerts($user)
{
    include('../config.php');
    if ($stmt = $conn->prepare("SELECT alerts FROM situla.usernames WHERE username=?"))
    {
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($alerts);
        $stmt->fetch();
        return $alerts;
    }
}

function makeUsername()
{
    echo
    '
    <div syle="text-align:center;">
      In the interest of privacy, Situla recommends that you choose a username that will be public on your posts, instead of exposing your email.
      <br>
      If you so choose, you may enter your desired username below, or you may click the \'Use email\' button, and your email will be used as your username.
      <br>
      <strong>This choice is permanent, choose wisely.</strong>
      <br><br>
      <form method="post">
        <input type="text" class="input-large" placeholder="Username" name="username">
        <br>
        <input type="submit" name="userSubmit" value="Submit" class="btn btn-success btn-large"/>
      </form>
      <br>
      - OR -
      <form method="post">
        <input type="submit" name="useEmail" value="Use email" class="btn btn-large"/>
      </form>
      <small>
        Your email will be public to others on Situla.net
      </small>
    </div>
    ';
}

function alreadyInUse()
{
    echo
    '
    <div class="row">
      <div style="text-align:center;">
        <div class="alert alert-error">
          <a class="close" data-dismiss="alert">&times;</a>
          <strong>Error: </strong>That username is already in use. Try another.
        </div>
      </div>
    </div>
    ';
}


function hasAccount($id)
{
    include('../config.php');
    if ($stmt = $conn->prepare("SELECT id FROM situla.usernames WHERE identification=?"))
    {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;
        $stmt->close();
        return $num == 1;
    }
}

function hasUsername($username)
{
    include('../config.php');
    if ($stmt = $conn->prepare("SELECT id FROM situla.usernames WHERE username=?"))
    {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;
        $stmt->close();
        return $num >= 1;
    }
}

function getInfo($id)
{
    include('../config.php');
    if ($stmt = $conn->prepare("SELECT username, gravatar FROM situla.usernames WHERE identification=?"))
    {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($name, $gravatar);
        $stmt->fetch();
        return array($name, $gravatar);
    }
}

function setUsername($id, $username)
{
    include('../config.php');
    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $gravatar = 'http://www.gravatar.com/avatar/?f=y';
    if(filter_var($id, FILTER_VALIDATE_EMAIL))
    {
        $hash = md5(strtolower(trim($id)));
        $gravatar = 'http://www.gravatar.com/avatar/'.$hash;
    }
    if ($stmt = $conn->prepare("INSERT INTO situla.usernames (username, identification, ipv4, gravatar) VALUES (?, ?, ?, ?)"))
    {
        $stmt->bind_param("ssss", $username, $id, $ip, $gravatar);
        $stmt->execute();
        $stmt->close();
    }
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['gravatar'] = $gravatar;
    $_SESSION['id'] = $id;
    $_SESSION['alerts'] = getAlerts($username);
    header('Location: http://situla.net/');
}
?>
        </div>
<?php
    echoFooter();
?>