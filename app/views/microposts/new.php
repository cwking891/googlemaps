<?php
/**
  * Posts a new comment.
 */
require_once("../include/must_be_logged_in.php");
require_once("Comment.php");

$title = "Google Maps | Comments";
require_once("header.php");

// Initialize Comment from session
$comment = new Comment($r);
?>

<body onload="setFocus('subject')">

<?php require_once("navigation.php"); ?>

<div class="mainpanel center round">

  <h2>Google Maps Comments</h2>

  <form action="new_verify.php" method="post">
    <input type="hidden" name="user_id" value="<?=$user->get_user_id()?>">

    <?php msg_display($r); ?>

    <?= $comment->generate_inputs()?>
    
    <br/><input type="submit" name="submit" value="Post Comment">

  </form>

</div>

<?php require_once("footer.php"); ?>
