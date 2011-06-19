<?php
/**
 * Contains the data fields for a omment.,
 * and methods to generate html entry fields,
 * and to validate each field.
 */
class Comment extends Model {
	
    protected $comment_id = null;
    protected $parent_id = 0;
    protected $user_id = null;
    protected $subject = "";
    protected $body = "";
    protected $date_entered = "";
    
    protected $flds = Array("comment_id" => 0, "parent_id" => 0,
                    "user_id" => 0, "subject" => 60, "body" => 65535,
                    "date_entered" => 0);
    protected $keys = Array("comment_id");
    protected $nowrite = Array("comment_id");
    protected $desc = Array("Comment Id", "Parent Id", "User Id",
                     "Subject", "Comment", "Date Entered");

    public function get_comment_id() {
    	return $this->comment_id;
    }
    
    public function set_user_id($user_id) {
        $this->user_id = $user_id;
    }
    
    public function get_body() {
    	return $this->body;
    }
    
    public function set_body($body) {
    	$this->body = $body;
    }
    
    public function get_subject() {
    	return $this->subject;
    }

    public function set_subject() {
    	$this->subject = $subject;
    }
    
    public function get_parent_id() {
    	return $this->parent_id;
    }
    
    public function get_date_entered() {
    	return $this->date_entered;
    }
    
    public function get_signin_id() {
    	$keylist = array('user_id' => $this->user_id);
    	$user = new User();
    	if ($user->get($keylist)) {
    		return $user->get_signin_id();
    	} else {
    		return "unknown";
    	}
    }

    public function set_date_entered($date_entered) {
    	$this->date_entered = $date_entered;
    }

}



?>