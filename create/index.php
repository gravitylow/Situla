<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false)
{
    header('Location: http://situla.net/login?alert');
}
else
{
    include('../heart.php');
    echoHeader(3, "Create Project");
    if(isset($_POST['post']))
    {
        if($_POST['project-name'] && $_POST['project-url'] && $_POST['description'])
        {
            $name = strip_tags($_POST['project-name']);
            $url = strip_tags($_POST['project-url']);
            $desc = strip_tags($_POST['description']);
            include('../config.php');
            if ($stmt = $conn->prepare("INSERT INTO situla.projects (project, url, description, user, created, updated) VALUES (?, ?, ?, ?, NOW(), NOW())"))
            {
                $stmt->bind_param("ssss", $name, $url, $desc, $_SESSION['username']);
                $stmt->execute();
                $stmt->close();
            }
            header('Location: http://situla.net/projects?new');
        }
        else
        {
            echo
            '
            <div class="row">
              <div style="text-align:center;">
                <div class="alert alert-error">
                  <a class="close" data-dismiss="alert">&times;</a>
                  <strong>Error: </strong>Please fill in all of the fields.
                </div>
              </div>
            </div>
            ';
        }
    }
}
?>
    <br>
    <div class="container">
      <div class="well">
        <form method="post">
          <h5>Name of the project</h3>
          <div class="input-prepend">
            <span class="add-on"><i class="icon-star"></i></span>
            <input type="text" class="input-large" placeholder="ProjectName" name="project-name" <?php if(isset($_POST['project-name'])) $value = $_POST['project-name']; echo 'value="'.$value.'"';?>><br>
          </div>
          <h5>Site that the project/code can be found at</h3>
          <div class="input-prepend">
            <span class="add-on"><i class="icon-home"></i></span>
            <input type="url" class="input-large" placeholder="http://github.com/user/project" name="project-url" <?php if(isset($_POST['project-url'])) $value = $_POST['project-url']; echo 'value="'.$value.'"';?>><br>
          </div>
          <h5>A short description of the project and why it complies with Situla standards</h3>
          <textarea class="input-large" id="description" style="width: 600px; height: 150px;" name="description" onkeyup="countChar(this)"><?php if(isset($_POST['description'])) echo $_POST['description'];?></textarea>    
          <div id="charNum"></div>
          <input type="hidden" name="post">
	</div>
	<div class="well">
          <input type="submit" style="left: 50%;position: relative;top: 50%;" name="submit" value="Submit" class="btn btn-success btn-large"/>
        </form>
     </div>
<?php
    echoFooter();
?>
