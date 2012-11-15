<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false)
    {
        header('Location: http://situla.net/login?alert');
    }
    else
    {
        include('../heart.php');
        echoHeader(0, "Account");
        if($_POST['gravatar'])
        {
            $gravatar = $_POST['gravatar'];
            $hash = md5(strtolower(trim($gravatar)));
            $gravatar = 'http://www.gravatar.com/avatar/'.$hash;
            include('../config.php');
            if ($stmt = $conn->prepare("UPDATE situla.usernames SET gravatar=? WHERE username=?"))
            {
                $stmt->bind_param("ss", $gravatar, $_SESSION['username']);
                $stmt->execute();
                $stmt->close();
                $_SESSION['gravatar'] = $gravatar;
                echo
                '
                <div class="row">
                  <div style="text-align:center;">
                    <div class="alert alert-success">
                      <a class="close" data-dismiss="alert">&times;</a>
                      <strong>Success: </strong>Your gravatar has been updated.
                    </div>
                  </div>
                </div>
                ';
            }
        }
    }
?>
    <div class="container">
      <div class="pager previous" style="text-align:left;">
        <a href="http://situla.net/account/" class=>&larr; Back to account</a>
      </div>
      <div class="well">
        <center>
	   <h1>Gravatar</h1>
	   <img src="<?php echo $_SESSION['gravatar']; ?>">
          <br><br>
          <h4>To change this avatar, modify your account at <a href="http://gravatar.com" target="_blank">gravatar.com</a></h4>
          <br>
            <form method="post">
<?php
    $id = $_SESSION['id'];
    if(filter_var($id, FILTER_VALIDATE_EMAIL))
    {
        echo '<small>We already have an email on-file for you, and are using it as your gravatar (above). <br> However, if this is not your preferred gravatar, you may choose to use one associated with a different email below.</small>';
    }
?>
	     <br><br>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on"><i class="icon-envelope"></i></span>
                <input type="email" name="gravatar" placeholder="New email">
              </div>
            </div>
            <input type="submit" class="btn btn-small btn-success" name="update" value="Update gravatar email">
          </form>
        </center>
      </div>
<?php
    echoFooter(true);
?>
