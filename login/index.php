<?php
include('../heart.php');
echoHeader(0, "Login");
if(isset($_GET['alert']))
{
    echo
    '
    <div class="row">
      <div style="text-align:center;">
        <div class="alert alert-error">
          <a class="close" data-dismiss="alert">&times;</a>
          <strong>Error: </strong>You must be logged in to do that.
        </div>
      </div>
    </div>
    ';
}
?>
    <div class="container">
      <div class="well">
        <div style="text-align:center;">
          <a href="google" title="Log in with Google"><img src="../assets/img/google-logo.png" width="75" height="75"></a>
        </div>
      </div>
<?php
    echoFooter();
?>
