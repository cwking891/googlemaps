<?php
/**
  * Partial to display comments.
  * The SQL $result must be set by caller.  
  * Allow signed-in user to post a new
  * comment or reply to existing comment.
  * TODO: Move all Sql into the Model
  * TODO: Add a Search Comments bar.
 */
require_once("Comment.php");
?>

<h2>Comment on the GoogleMaps Application</h2>

<?php while ($row = mysql_fetch_array($result)) { ?>
<?php $comment = new Comment($row); ?>

<div class="comment round">

    <p class="com_subject"><?=$comment->get_subject()?></p>
    <p class="com_signature">by <?=$comment->get_signin_id()?> on <?=$comment->get_date_entered()?></p>
    <p class="com_body"><?=$comment->get_body()?></p>
        
    <?php 
        $sql = "SELECT * FROM comments"
             . " WHERE parent_id=%s"
             . " ORDER BY date_entered DESC";
        $result2 = sql(sprintf($sql, $comment->get_comment_id()), $return2);
        while ($row2 = mysql_fetch_array($result2)) {
        $comment2 = new Comment($row2);
    ?>
    <div class="comment_reply round">
    
        <p class="com_subject_reply">Re: <?=$comment2->get_subject()?></p>
        <p class="com_signature">by <?=$comment2->get_signin_id()?> on <?=$comment2->get_date_entered()?></p>
        <p class="com_body"><?=$comment2->get_body()?></p>
            
    </div>
    <?php } ?>

    <p class="dark_link">
        <a href="comments/new_reply.php?parent_id=<?=$comment->get_comment_id()?>">Post Reply</a>
        <a href="comments/new.php">Post New Comment</a>
    </p>

</div>
<?php } ?>

<?php if (!$return) {?>
    &nbsp;<a href="comments/new.php">Post New Comment</a>
<?php } ?>



