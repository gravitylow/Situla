<?php
// TODO: Split this code uppppppppp
// It's bad, people. Don't look.
ob_start();

$bbcode = array(
    "[big]" => "<big>",
    "[/big]" => "</big>",
    "[b]" => "<strong>",
    "[/b]" => "</strong>",
    "[i]" => "<em>",
    "[/i]" => "</em>",
    "[u]" => "<u>",
    "[/u]" => "</u>",
    "[img]" => "<img src=\"",
    "[/img]" => "\" border=\"0\" />",
    "[sup]" => "<sup>",
    "[/sup]" => "</sup>",
    "[sub]" => "<sub>",
    "[/sub]" => "</sub>",
);

include('../heart.php');
echoHeader(3, "Projects");
?>
    <div class="container">
    <a class="btn btn-inverse pull-left" href="http://situla.net/create"><i class="icon-globe icon-white"></i> Create a project</a>
    <form class="form-search">
      <div class="input-append pull-right">
        <input type="text" class="span2 search-query" name="query">
        <button type="submit" class="btn btn-inverse">Search</button>
      </div>
    </form>
	<h5><center>Feel free to create test projects/comments and leave feedback on them for now. Projects will be wiped before site goes live.</center></h5>
<?php
if(isset($_GET['query']))
{
        $query = $_GET['query'];
        echo '<div class="well" style="text-align:center;"><h3>Results for: \''.$query.'\'</h3></div>';
        echo '<div class="well">';
        include('../config.php');
        if($stmt = $conn->prepare("SELECT id, project, replies, rating, user FROM situla.projects WHERE project=? OR project LIKE CONCAT('%', ?, '%')"))
        {
            $stmt->bind_param("ss", $query, $query);
            $stmt->execute();
            $stmt->bind_result($id, $project, $replies, $rating, $user);
            $found = false;
            while($stmt->fetch())
            {
                $found = true;
                echo '<div class="pull-right"><small>Replies: '.$replies.'<br>Rating: ';
                if($rating >= 1)
                {
                    echo '<span class="text-success">+'.$rating;
                }
                else if($rating == 0)
                {
                    echo '<span class="muted">'.$rating;
                }
                else
                {
                    echo '<span class="text-error">'.$rating;
                }
                echo '</div></span></small>';
                echo '<strong><a href="http://situla.net/projects?projects='.$id.'">'.$project.'</a></strong><br>';
                echo 'Project by: '.$user;
                echo '<hr>';
            }
            if(!$found)
            {
                echo '<strong>No projects found. Sorry!</strong>';
            }
        }
        echo $conn->error;
        echo '</div>';
}
else if(isset($_GET['project']))
{
    include('../config.php');
    $project = $_GET['project'];
    if(isset($_GET['vote']))
    {
        echo
        '
        <div class="row">
          <div style="text-align:center;">
            <div class="alert alert-success">
              <a class="close" data-dismiss="alert">&times;</a>
              <strong>Success: </strong>Your vote has been counted.
            </div>
          </div>
        </div>
        ';
    }
    if(isset($_GET['changevote']))
    {
        echo
        '
        <div class="row">
          <div style="text-align:center;">
            <div class="alert">
              <a class="close" data-dismiss="alert">&times;</a>
              Your vote has been changed.
            </div>
          </div>
        </div>
        ';
    }
    if(isset($_GET['update']))
    {
        echo
        '
        <div class="row">
          <div style="text-align:center;">
            <div class="alert">
              <a class="close" data-dismiss="alert">&times;</a>
              Your post has been updated.
            </div>
          </div>
        </div>
        ';
    }
    if($stmt = $conn->prepare("SELECT id, project, url, description, user, DATE_FORMAT(`created`, '%W the %D of %M at %h:%i %p'), rating, replies, updates FROM situla.projects WHERE id=?"))
    {
        echo '<br><div class="well">';
        $stmt->bind_param("i", $project);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $name, $url, $desc, $user, $created, $rating, $comments, $updates);
        $stmt->fetch();
        if($stmt->num_rows == 0)
        {
            echo '<span style="text-align:center;"><h3>Sorry, we couldn\'t find a project by the id: '.$project.'</h3>';
            echo '<h4>It could have been deleted, or you may have followed a bad link.</h4></span>';
        }
        else
        {
            if(isset($_POST['comment']))
            {
                if(!$_SESSION['loggedin'])
                {
                    echo
                    '
                    <div class="row">
                      <div style="text-align:center;">
                        <div class="alert alert-error">
                          <a class="close" data-dismiss="alert">&times;</a>
                          <strong>Error: </strong>You must be logged in to comment.
                        </div>
                      </div>
                    </div>
                    ';
                }
                else
                {
                    $user = $_SESSION['username'];
                    $comment = $_POST['comment'];
                    $commentNum = $_POST['commentNum'];
                    if($comment != null && $comment != "")
                    {
                        $user = $_SESSION['username'];
                        $comment = strip_tags($comment);
                        $user = $_SESSION['username'];
                        $commentNum = $_POST['comment#'];
                        $creator = "";
                        if ($stmt = $conn->prepare("INSERT INTO situla.comments (project, user, comment, created) VALUES (?, ?, ?, NOW())"))
                        {
                            $stmt->bind_param("iss", $project, $user, $comment);
                            $stmt->execute();
                            $stmt->free_result();
                        }
                        if($stmt = $conn->prepare("UPDATE situla.projects SET replies = 1 + (SELECT p.replies FROM (SELECT * FROM situla.projects) AS p WHERE id=?) WHERE id=?"))
                        {
                            $stmt->bind_param("ii", $project, $project);
                            $stmt->execute();
                            $stmt->free_result();
                        }
                        if($stmt = $conn->prepare("UPDATE situla.projects SET updated=NOW() WHERE id=?"))
                        {
                            $stmt->bind_param("i", $project);
                            $stmt->execute();
                        }
                        if($stmt = $conn->prepare("SELECT user, project FROM situla.projects WHERE id=?"))
                        {
                            $stmt->bind_param("i", $project);
                            $stmt->execute();
                            $stmt->store_result();
                            $stmt->bind_result($creator, $prj);
                            $stmt->fetch();
                            $stmt->free_result();
                        }
                        if($creator != $user)
                        {
                            if ($stmt = $conn->prepare("INSERT INTO situla.alerts (user, text) VALUES (?, ?)"))
                            {
                                $text = $user.' replied to your project: <a href="http://situla.net/projects/?project='.$project.'#c'.$commentNum.'">'.$prj.'</a>';
                                $stmt->bind_param("ss", $creator, $text);
                                $stmt->execute();
                                $stmt->free_result();
                                if($stmt = $conn->prepare("UPDATE situla.usernames SET alerts = 1 + (SELECT p.alerts FROM (SELECT * FROM situla.usernames) AS p WHERE username=?) WHERE username=?"))
                                {
                                    $stmt->bind_param("ss", $creator, $creator);
                                    $stmt->execute();
                                    $stmt->free_result();
                                }
                            }
                        }
                        $stmt->close();
                        header("Location: http://situla.net/projects/?project=".$project."#c".$commentNum);
                    }
                }
            }
            if(isset($_POST['agree']) || isset($_POST['disagree']))
            {
                $user = $_SESSION['username'];
                if(!$_SESSION['loggedin'])
                {
                    echo
                    '
                    <div class="row">
                      <div style="text-align:center;">
                        <div class="alert alert-error">
                          <a class="close" data-dismiss="alert">&times;</a>
                          <strong>Error: </strong>You must be logged in to vote.
                        </div>
                      </div>
                    </div>
                    ';
                }
                else
                {
                    if($stmt = $conn->prepare("SELECT id FROM situla.comments WHERE project=? AND user=?"))
                    {
                        $stmt->bind_param("is", $project, $user);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->bind_result($vote);
                        $stmt->fetch();
                        $empty = $stmt->num_rows == 0;
                        if($empty)
                        {
                            echo
                            '
                            <div class="row">
                              <div style="text-align:center;">
                                <div class="alert alert-error">
                                  <a class="close" data-dismiss="alert">&times;</a>
                                  <strong>Error: </strong>Please comment with feedback at least once before rating.
                                </div>
                              </div>
                             </div>
                            ';
                        }
                        else
                        {
                            $choice = isset($_POST['agree']) ? 1 : -1;
                            $user = $_SESSION['username'];
                            if($stmt = $conn->prepare("SELECT vote FROM situla.votes WHERE project=? AND user=?"))
                            {
                                $stmt->bind_param("is", $project, $user);
                                $stmt->execute();
                                $stmt->store_result();
                                $stmt->bind_result($vote);
                                $stmt->fetch();
                                $insert = $stmt->num_rows == 0;
                                if(!$insert)
                                {
                                    if($choice != $vote)
                                    {
                                        $stmt->free_result();
                                        if ($stmt = $conn->prepare("UPDATE situla.votes SET vote=? WHERE user=? AND project=?"))
                                        {
                                            $stmt->bind_param("iss", $choice, $user, $project);
                                            $stmt->execute();
                                        }
                                        $stmt->free_result();
                                        if($stmt = $conn->prepare("SELECT vote FROM situla.votes WHERE project=?"))
                                        {
                                            $stmt->bind_param("s", $project);
                                            $stmt->execute();
                                            $stmt->bind_result($vote);
                                            while($stmt->fetch())
                                            {
                                                $rate+=$vote;
                                            }
                                            $stmt->free_result();
                                            if($stmt = $conn->prepare("UPDATE situla.projects SET rating = ? WHERE id=?"))
                                            {
                                                $stmt->bind_param("ii", $rate, $project);
                                                $stmt->execute();
                                                $stmt->close();
                                            }
                                            $vote = null;
                                        }
                                        header("Location: http://situla.net/projects/?project=".$project."&changevote");
                                    }
                                    else
                                    {
                                        $c = $choice == 1 ? 'agreed' : 'disagreed';
                                        echo
                                        '
                                        <div class="row">
                                          <div style="text-align:center;">
                                            <div class="alert alert-error">
                                              <a class="close" data-dismiss="alert">&times;</a>
                                              <strong>Error: </strong>You\'ve already '.$c.' with this project.
                                            </div>
                                          </div>
                                         </div>
                                        ';
                                    }
                                }
                                else if($stmt = $conn->prepare("INSERT INTO situla.votes (user, project, vote) VALUES (?, ?, ?)"))
                                {
                                    $stmt->bind_param("ssi", $user, $project, $choice);
                                    $stmt->execute();
                                    $stmt->free_result();
                                    if($stmt = $conn->prepare("SELECT vote FROM situla.votes WHERE project=?"))
                                    {
                                        $stmt->bind_param("s", $project);
                                        $stmt->execute();
                                        $stmt->bind_result($vote);
                                        while($stmt->fetch())
                                        {
                                            $rating+=$vote;
                                        }
                                        $stmt->free_result();
                                        if($stmt = $conn->prepare("UPDATE situla.projects SET rating = ? WHERE id=?"))
                                        {
                                            $stmt->bind_param("ii", $rating, $project);
                                            $stmt->execute();
                                            $stmt->close();
                                        }
                                        $vote = null;
                                    }
                                    header("Location: http://situla.net/projects/?project=".$project."&vote");
                                }
                            }
                        }
                    }
                }
            }
            if(isset($_POST['edit=main']))
            {
                if($_SESSION['username'] == $user)
                {
                    $newDesc = strip_tags($_POST['description']);
                    $newProject = strip_tags($_POST['project']);
                    $newUrl = strip_tags($_POST['url']);
                    if($newDesc != $desc || $newProject != $project || $newUrl != $url)
                    {
                        if ($stmt = $conn->prepare("UPDATE situla.projects SET project=?, description=?, url=? WHERE id=?"))
                        {
                            $stmt->bind_param("ssss", $newProject, $newDesc, $newUrl, $project);
                            $stmt->execute();
                            $stmt->close();
                        }
                        $stmt->free_result();
                        if($stmt = $conn->prepare("UPDATE situla.projects SET updates= 1 + (SELECT p.updates FROM (SELECT * FROM situla.projects) AS p WHERE id=?) WHERE id=?"))
                        {
                            $stmt->bind_param("ii", $project, $project);
                            $stmt->execute();
                            $stmt->close();
                        }                    
                        header('Location: http://situla.net/projects/?project='.$project.'&update');
                    }
                    $newDesc = null;
                    $newProject = null;
                    $newUrl = null;
                }
            }
            if($stmt = $conn->prepare("SELECT gravatar FROM situla.usernames WHERE username=?"))
            {
                $stmt->bind_param("s", $user);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($gravatar);
                $stmt->fetch();
                $stmt->free_result();
            }
            $stmt->free_result();
            echo
            '
            <div class="modal hide" id="banner">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Project banner</h3>
              </div>
              <div class="modal-body" style="text-align:center;">
                  <img src="http://situla.net/image/?project='.$project.'">
                     <br><br>
                  The Situla banner helps users easily identify which projects are safe to use, by means of community consensus. By placing this banner on your project\'s page, it lets people know you intend on participating in the best practices possible to keep people safe.
                  <p><br>
                  When adding a banner to your page, please copy the code exactly, and do not modify your banner. Doing so could be seen as violation of the standards, and your project\'s rating could suffer because of it. 
                  <br>
                  Your banner must always link back to this page (<a href="http://situla.net/projects/?project='.$project.'">http://situla.net/projects/?project='.$project.'</a>) so that users can further inspect the project if they so choose.
                  <p><br>
                  To add this banner to your page, simply add one of the following snippits to your project\'s markup. We offer multiple markup languages, so choose the one that fits you best.
                  <p><br>
                  <h4>WikiCreole:</h4>
                  <code>
                    [[http://situla.net/projects/?project='.$project.'|{{http://situla.net/image/?project='.$project.'|}}]]
                  </code><br>
                  <h4>BB code:</h4>
                  <code>
                    [url="http://situla.net/projects/?project='.$project.'"][img]http://situla.net/image/?project='.$project.'[/img][/url]
                  </code><br>
                  <h4>Markdown:</h4>
                  <code>
                    [&lt;img src="http://situla.net/image/?project='.$project.'"&gt](http://situla.net/projects/?project='.$project.')
                  </code><br>
                  <h4>HTML:</h4>
                  <code>
                    &lt;a href="http://situla.net/projects/?project='.$project.'"&gt&lt;img src="http://situla.net/image/?project='.$project.'"&gt&lt;/a&gt
                  </code>
              </div>
              <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
              </div>
            </div>';
            echo '<div class="pull-right thumbnail"><a href="#banner" data-toggle="modal"><img src="http://situla.net/image/?project='.$project.'"></a></div>';
            echo '<span style="text-align:center;"><h3><a href="'.$url.'">'.$name.'</a></h3></span>';
            $displayUrl = $url;
            if(strlen($url) > 50)
            {
                $displayUrl = substr($url, 0, 50)."...";
            }
            echo '<small><a href="'.$url.'">'.$displayUrl.'</a></small>';
            echo '</div><div class="well">';
            echo '<div class="pull-right">Created: '.$created.'</div><br>';
            echo '<div class="pull-right"><h2>Rating: ';
            if($rating >= 1)
            {
                echo '<span class="text-success">+'.$rating;
            }
            else if($rating == 0)
            {
                echo '<span class="muted">'.$rating;
            }
            else
            {
                echo '<span class="text-error">'.$rating;
            }
            echo '</span><h4>';
            echo
            '
            <form method="post">
              <input type="submit" name="agree" class="btn btn-mini btn-success" value="Compliant">
              <input type="submit" name="disagree" class="btn btn-mini btn-danger" value="NOT compliant!">
            </form>
            <a href="#votes" role="button" class="btn" data-toggle="modal">View votes</a></div>
            ';
            echo
            '
            <div
            <div class="modal hide" id="votes" tabindex="-1" role="dialog" aria-labelledby="votesLabel" aria-hidden="true">
              <div class="modal-header">
                <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                <h3 id="votesLabel">Votes</h3>
              </div>
            <div class="modal-body">
            ';
            if($result = $conn->query("SELECT user, vote FROM situla.votes WHERE project=".$project))
            {
                if($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc())
                    {
                        if($row['vote'] == 1)
                        {
                            $c = '<span class="text-success">Compliant (+1)</span>';
                        }
                        else
                        {
                            $c = '<span class="text-error">Not complaint (-1)</span>';
                        }
                        echo '<strong>'.$row['user'].':</strong> '.$c.'<br>';
                    }
                }
                else
                {
                    echo 'No votes yet!';
                }
                $voteUser = null;
                $voteNum = null;
            }
            echo
            '
            </div>
              <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
              </div>
            </div>
            ';
            echo '<img src="'.$gravatar.'?s=100"><br>';
            echo '<strong>'.$user.'</strong><br><br>';
            echo auto_link_text(nl2br(strtr($desc, $bbcode)));
            if($_SESSION['username'] == $user)
            {
                echo
                '
                <div class="modal hide" id="editmain">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Edit post</h3>
                  </div>
                  <div class="modal-body">
                    <form method="post">
                      <input type="text" name="project" value="'.$name.'">
                      <input type="url" name="url" value="'.$url.'">
                      <input type="hidden" name="edit=main">
                      <textarea class="input-large" id="description" style="width: 520px; height: 150px;" name="description">'.$desc.'</textarea>
                      <div id="charNum"></div>
                      </div>
                      <div class="modal-footer">
                      <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                      <input type="submit" class="btn btn-primary" value="Save Changes">
                    </form>
                  </div>
                </div>';
                echo '<br><a href="#editmain" role="button" class="btn btn-mini pull-right" data-toggle="modal">Edit</a>';
            }
            echo '</div><div class="well">';
            $stmt->free_result();
            $stmt = null;
            if($result = $conn->query("SELECT id, user, comment, rating, DATE_FORMAT(`created`, '%W the %D of %M at %h:%i %p') AS created FROM situla.comments WHERE project=".$project." AND status=0"))
            {
                if($result->num_rows > 0)
                {
                    $count = 0;
                    while ($row = $result->fetch_assoc())
                    {
                        $count++;
                        $comment = strtr(nl2br($row['comment']), $bbcode);
                        if(isset($_POST['edit='.$count]))
                        {
                             if($_SESSION['username'] == $row['user'])
                             {
                                 $newComment = strip_tags($_POST['newComment']);
                                 if($commentText != $newComment)
                                 {
                                     if ($stmt = $conn->prepare("UPDATE situla.comments SET comment=?, created=NOW() WHERE id=?"))
                                     {
                                         $stmt->bind_param("si", $newComment, $row['id']);
                                         $stmt->execute();
                                         $stmt->close();
                                     }
                                     header('Location: http://situla.net/projects/?project='.$project.'&update');
                                 }
                             }
                        }
                        echo '<div id="c'.$count.'">';
                        echo '<strong>'.$row['user'].'</strong> on '.$row['created'].'</strong><br>';
                        echo $row['comment'].'<br>';
                        if($_SESSION['username'] == $row['user'])
                        {
                            echo
                            '
                            <div class="modal hide" id="edit'.$count.'">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3>Edit post</h3>
                              </div>
                              <div class="modal-body">
                                <form method="post">
                                  <input type="hidden" name="edit='.$count.'">
                                  <textarea class="input-large" style="width: 520px; height: 150px;" id="newComment" name="newComment">'.$row['comment'].'</textarea>
                                  <div id="charNum"></div>
                                  </div>
                                  <div class="modal-footer">
                                  <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                  <input type="submit" class="btn btn-primary" value="Save Changes">
                                </form>
                              </div>
                            </div>
                            ';
                            echo ' <a href="#edit'.$count.'" role="button" class="btn btn-mini pull-right" data-toggle="modal">Edit</a>';
                        }                  
                        echo '<div class="pull-right"><small><a href="http://situla.net/projects/?project='.$project.'#c'.$count.'">#'.$count.'</a></small></div>';      
                        echo '</div></div><div class="well">';
                    }
                }
                if($_SESSION['loggedin'])
                {
                    echo
                    '
                    <form method="post">
                    <textarea class="input-large" id="comment" style="width: 300px; height: 100px;" name="comment" onkeyup="countChar(this)"></textarea>    
                    <div id="charNum"></div>
                      <input type="hidden" name="comment#" value="'.($comments+1).'">
                      <input type="submit" name="submit" value="Comment" class="btn btn-small">
                    </form>
                    ';
                }
                else
                {
                        echo '<a href="http://situla.net/login">You must be logged in to comment.</a>';
                }
            }
        }
        echo '</div>';
    }
}
else
{
    echo
    '
    <div class="pagination pagination-centered">
    <ul>
    ';
    if(isset($_GET['p']))
    {
        $page = $_GET['p'];
        if($page == 'replies')
        {
            echo
            '
            <li><a href="?">Latest</a></li>
            <li class="active"><a href="">Replies</a></li>
            <li><a href="?p=rating">Rating</a></li>
            ';
        }
        else if($page == 'rating')
        {
            echo
            '
            <li><a href="?">Latest</a></li>
            <li><a href="?p=replies">Replies</a></li>
            <li class="active"><a href="">Rating</a></li>
            ';
        }
    }
    else
    {
        echo
        '
        <li class="active"><a href="">Latest</a></li>
        <li><a href="?p=replies">Replies</a></li>
        <li><a href="?p=rating">Rating</a></li>
        ';
    }
    echo
    '
      </ul>
    </div>
    ';
}
if(!isset($_GET['project']) && !isset($_GET['query']))
{
    echo '<div class="well">';
    $query = "SELECT * FROM situla.projects ORDER BY UNIX_TIMESTAMP(`updated`) DESC";
    if(isset($_GET['p']))
    {
        $page = $_GET['p'];
        if($page == 'replies')
        {
            $query = "SELECT * FROM situla.projects ORDER BY replies DESC";
        }
        else if($page == 'rating')
        {
            $query = "SELECT * FROM situla.projects ORDER BY rating DESC";
        }
    }
    include('../config.php');
    if($result = $conn->query($query))
    {
        while($row = $result->fetch_assoc())
        {
            $replies = $row['replies'];
            $rating = $row['rating'];
            echo '<strong><a href="http://situla.net/projects/?project='.$row['id'].'">'.$row['project'].'</a></strong><br>';
            echo 'Project by: '.$row['user'];
            echo '<div class="pull-right"><small>Replies: '.$replies.'<br>Rating: ';
            if($rating >= 1)
            {
                echo '<span class="text-success">+'.$rating;
            }
            else if($rating == 0)
            {
                echo '<span class="muted">'.$rating;
            }
            else
            {
                echo '<span class="text-error">'.$rating;
            }
            echo '</div></span></small><div class="project-sep"></div>';
        }
    }
    echo '</div>';
}
echoFooter();
ob_flush();
?>
