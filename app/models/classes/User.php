<?php
/**
 * User: Contains the data fields for a user.
 * Contains methods to generate html entry fields,
 * and methods to validate each field.
 */
class User extends Model {

	public static $NO_USER = "Cannot find user record for this id and password.";
    public static $DUP_ID = "Sorry, this user id is already taken.";
    
    protected $user_id = null;
    protected $first_name = "";
	protected $last_name = "";
	protected $email = "";
    protected $signin_id = "";
	protected $password = "";
	protected $pass_enter = "";
	protected $pass_verify = "";
	
	protected $flds = Array("first_name" => 20, "last_name" => 20, "email" => 40,
                   "signin_id" => 20, "password" => 0, "pass_enter" => 20,
	               "pass_verify" => 20, "user_id" => 0);
	protected $keys = Array("user_id");
    protected $nowrite = Array("pass_enter", "pass_verify", "user_id");
	protected $desc = Array("First Name", "Last Name", "Email", "User Id", "Password",
                    "Password", "Re-Enter Password", "Id");

    private $is_logged_in = false;
	
    public function __construct(Array $args = array()) {
		return parent::__construct($args);
	}

    public function get_user_id() {
        return $this->user_id;
    }
    
	public function get_first_name() {
    	return $this->first_name;
    }
    
    public function get_signin_id() {
    	return $this->signin_id;
    }

    public function is_logged_in() {
    	return $this->is_logged_in;
    }
    
    public function set_logged_in($is_logged_in) {
    	$this->is_logged_in = $is_logged_in;
    }
    
    /**
     * Write this user to the database
     * @return Sql return message
     */
    public function create() {
    	$this->password = md5($this->pass_enter);
        return parent::create();
    }

  public function login() {
    $this->set_logged_in(true);
    $_SESSION['signin_id'] = $this->signin_id;
    $_SESSION['password'] = $this->password;
  }
  
  public function logout() {
    $this->set_logged_in(false);
  	session_destroy();
  }

}

?>