<?php
/**
  * Posts a reply to existing comment.
  * TODO: On page refresh coming back from error,
  *       loses the parent-id from $_GET.
 */
require_once("../include/must_be_logged_in.php");
require_once("Comment.php");
 
// Initialize ids from session
$parent_id = (isset($_GET['parent_id'])) ?$_GET['parent_id'] :$r['parent_id'];

$title = "Google Maps | Reply to comment";
require_once("header.php");

$sql = "SELECT * FROM comments"
     . " WHERE comment_id=%s"
     . " ORDER BY date_entered DESC";
$result = sql(sprintf($sql, sanitize($parent_id)), $return);
?>

<body onload="setFocus('body')">

<?php require_once("navigation.php"); ?>

<div class="mainpanel center round">

	<?php require_once("show_comments.php"); ?>
	
	<?php
	// Initialize comment from session
	$comment2 = new comment($r);
	?>
	
	<div class="comment">
	
		<form action="new_verify.php" method="post">
		    <input type="hidden" name="user_id" value="<?=$user->get_user_id()?>">
		    <input type="hidden" name="subject" value="<?=$comment->get_subject()?>">
	        <input type="hidden" name="parent_id" value="<?=$parent_id?>">
		
		    <?php msg_display($r); ?>
		
		    <p class="com_subject_reply">Re: <?= $comment->get_subject()?></p>
		    
		    <textarea class=com_entry id="body" name="body"><?=$comment2->get_body()?></textarea>
		        
		    <br/>
		    <input type="submit" name="submit" value="Submit Reply">
		</form>
	
	</div>
	
</div>

<?php require_once("footer.php");?>
