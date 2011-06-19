<?php
/**
 * Contains the data fields for a forum user, and methods to
 * generate html entry fields, and to validate each field.
 */
class Contact extends Model {

    protected $id = null;
	protected $first_name = "";
	protected $last_name = "";
	protected $address = "";
	protected $city = "";
	protected $state = "";
	protected $zip = "";
	protected $country = "";
    protected $user_id = null;
	
	protected $flds = Array("id" => 0, "first_name" => 20, "last_name" => 20, "address" => 40,
                      "city" => 30, "state" => 2, "zip" => 5,
	                  "country" => 40, "user_id" => 0);
    protected $keys = Array("id");
	protected $nowrite = Array("id");
	protected $desc = Array("Contact Id", "First Name", "Last Name", "Address", "City",
	                  "State", "Zip", " Country", "User Id");

    public function __construct(Array $args = array()) {
		return parent::__construct($args);
	}

    public function set_user_id($user_id) {
        return $this->user_id = $user_id;
    }
    
	public function get_id() {
        return $this->id;
    }
    
	public function get_first_name() {
    	return $this->first_name;
    }
    
    public function get_name() {
    	return $this->first_name . " " . $this->last_name;
    }
    
    public function get_sorted_name() {
    	return $this->last_name . ", " . $this->first_name;
    }
    
    public function get_city_state_zip() {
    	return $this->city . ", " . $this->state . "  " . $this->zip;
    }
}

?>