<?php
/**
  * Receive new comment, validate the data and if no problems, 
  * write to database, else return for corrections.
  * TODO: Need a date-handling library.
 */
require_once("../include/initialize.php");
require_once("Comment.php");

// Verify all the inputs.
$r = $_POST;
$comment = new Comment();
$errors = $comment->verify_inputs($r);

// Add comment to database
if (!$errors) {
    $comment->set_attributes($r);
    $comment->set_date_entered(date("Y-m-d h:i:s"));
    $return_msg = $comment->create();
    if ($return_msg != 1) {
        $errors[] = $return_msg;
    }
}

// If no errors, redirect to Home.
if (!$errors) {
    $redirect = $home;
    $_SESSION[basename($redirect)]['msg'] = "Thanks, "
     . $user->get_first_name() . ". Your comment has been added.";
} else {
    // Redirect back to referring page
    $r['errors'] = $errors;
    $redirect = $referer;
    $_SESSION[filename($redirect)] = $r;
}

//Redirect
header("Location: $redirect");
exit;

