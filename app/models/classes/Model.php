<?php
/**
 * Contains the abstraction of a data file and record.
 * This class is extended by all records in the application.
 * The class automatically performs value sanitization, 
 * so that fields can safely be output to browser or database.
 * TODO: Abstract the database layer.
 * TODO: Use database SHOW to construct fields.
 * TODO: addslashes just before write to database, stripslashes after read
 * TODO: Htmlentities on display.
 * TODO: Add email regex, or filter_var
 * TODO: change get() to get_where($keyvals) and get() to use $this->keys.
 * TODO: Verify result after every SQL database statement.
 */
abstract class Model {

	public static $NOT_FOUND = "Cannot find the requested record.";
    public static $DUP = "This record has already been added.";
	
    private $table_name = "";

    protected $flds = Array();
    protected $keys = Array();
    protected $nowrite = Array();
    protected $desc = Array();

    /**
     * Constructs model using input associative array.
     * TODO: Perform validators on each field.
     * TODO: Separate setting Table Name from setting attributes.
     * @param $flds is an associative array with field name = value.
     * @return boolean true/false depending on validators.
     */
    public function __construct(Array $flds = null) {

    	//Set Table name
    	$this->table_name = strtolower(get_class($this) ."s");
    	
    	//Set Record attributes from associated array
        if ($flds == null) {
        	return true;
        } else {
        	return $this->set_attributes($flds);
        }
    }

    /**
     * Constructs model using input associative array.
     * TODO: Perform validators on each field.
     * @param $flds is an associative array with field name = value.
     * @return boolean true/false depending on validators.
     */
    public function set_attributes(Array $flds = array()) {
        //Set Record attributes from associated array
        foreach (array_keys($this->flds) as $fld) {
            if (isset($flds[$fld])) {
                //echo "Field:", $fld, " value:", $flds[$fld], br;
            	$this->$fld = sanitize($flds[$fld]);
            }
        }
        return true;
    }

    /**
     * Generates html label and input tags for all fields.
     * @return the html string.
     */
    public function generate_inputs() {
        $count = 0;
        $html = "";
        foreach ($this->flds as $fld => $len) {
            if ($len == 0) {
                $count++;
                continue;
            }
            $label = $this->desc[$count++];
            $html .= "<label for=\"$fld\">$label</label>";
            $val = $this->$fld;
            if (in_array($fld, array("body"))) {
                $html .= "<br/><textarea rows=\"10\" cols=\"74\" name=\"$fld\" id=\"$fld\">"
                      . $val . "</textarea><br/>\n";
            } else if (in_array($fld, array("state"))) {
                $html .= makeStateSelector($this->state, false) . "<br/>\n";
            } else {
	            $type = (in_array($fld, array("pass_enter", "pass_verify")))
	                ?"password" :"text";
	            $html .= "<input type=\"$type\" name=\"$fld\" id=\"$fld\" size=\"$len\" value=\"$val\"><br />\n";
            }
        }
        return $html;
    }

    /**
     * Validate all the input fields
     * @param Array $r is the $_REQUEST
     * @return Array of errors
     */
    public function verify_inputs(Array $r) {
        $errors = Array();
        $count = 0;

        foreach ($this->flds as $fld => $len) {
            if (!isset($r[$fld]) || $len == 0) {
                $count++;
                continue;
            }
            $val = trim($r[$fld]);
            $fld_desc = $this->desc[$count++];
            if ($val == "") {
                $errors[$fld] = "$fld_desc has not been entered.";
            } elseif (strlen($val) > $len) {
                $errors[$fld] = "$fld_desc is " . strlen($val) . " characters but cannot be "
                . "longer than $len characters.";
            }
        }

        if (isset($r['signin_id']) && !isset($errors['signin_id'])
            && preg_match( "/^\w+[-\w\.]*\w*$/i", $r['signin_id']) == 0) {
            //Starts/ends with word char, contains word char, . or -
            $errors['signin_id'] = "Signin Id can contain only letters, digits, dash, dot, hyphen.";
        }

        if (isset($r['pass_enter']) && !isset($errors['pass_enter'])
            && !isset($errors['pass_verify']) && $r['pass_enter'] != $r['pass_verify']) {
            $errors['pass_enter'] = "Password does not match password verification.";
        }

       if (isset($r['email']) && !isset($errors['email'])
 	        && !filter_var($r['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email address is not valid.";
	    }

        return $errors;
    }

    /**
     * Write this model to the database
     * @return Sql message.  Ccontains 1 on success, or error message on
     *  failure.
     */
    public function create() {
        $sql = "INSERT INTO $this->table_name SET " . $this->make_set_list();
        //echo "Creating $sql", br;
        sql($sql, $return_msg);
        return $return_msg;
    }

    /**
     * Update this model to the database
     * @return Sql message.  Ccontains 2 on success, or error message on
     *  failure.
     * TODO: Check for failure
     */
    public function update() {
    	$sql = "UPDATE $this->table_name SET " . $this->make_set_list()
             . " WHERE " . $this->key_values();
        sql($sql, $return_msg);
        return $return_msg;
    }

    /**
   * Get a record from database, and populates this instance of model with attributes.
   * @param $keys = associative array of search key/value pairs.
   * @return boolean true if record was found
   */
  public function get(Array $keys) {
    $where = $this->field_value_list($keys, " AND ");
    $sql = "SELECT * FROM " . $this->table_name . " WHERE $where LIMIT 1";
    $row = sqlGetRow($sql);
    if ($row) {
        $this->set_attributes($row);
        return true;
    } else {
        return false;
    }
  }
  
  /**
   * Looks for a record from database.
   * TODO: Can make this more efficient in the SELECT
   * @param $keys = associative array of search key/value pairs.
   * @return boolean true if record was found
   */
  public function is_found($keys) {
  	$where = $this->field_value_list($keys, " AND ");
  	$select = array_shift((array_keys($keys)));
    $sql = "SELECT $select FROM $this->table_name WHERE $where LIMIT 1";
    return sqlGetRow($sql);
  }
  
  /**
   * Builds the unique key values string for this model
   * @return String in format <key>='<value>' AND <key>='<value>', ...
   */
  public function key_values() {
  	$string = "";
  	$delimiter = " AND ";
  	foreach ($this->keys as $key) {
  		$string .= sprintf( $delimiter .$key ."='%s'", $this->$key);
  	}
  	//Remove final delimiter
  	return substr($string,strlen($delimiter));
  }
  
  /**
   * Builds a field=value string.
   * @param $flds = associative array of field name/value pairs.
   * @param $delimiter separates each pair, defaults to comma. 
            Use comma for a field/value string used in sql SET clause.
            Use "AND" for a field/value string used in sql WHERE clause.  
   * @return String in format <field>='<value>' $delimiter <field>='<value>', ...
   */
  public function field_value_list($flds, $delimiter = ", ") {
    $string = "";
    foreach ($flds as $key => $value) {
        $string .= sprintf( $delimiter .$key ."='%s'", sanitize($value));
    }
    //Remove final delimiter
    return substr($string,strlen($delimiter));
  }
  
  /**
   * Builds a field=value string for INSERT.
   * @return String suitable for sql INSERT statement.
   */
  private function make_set_list() {
    $string = "";
    $delimiter = ", ";
    foreach (array_keys($this->flds) as $fld) {
    	if (!in_array($fld, $this->nowrite)) {
            $string .= sprintf($delimiter .$fld ."='%s'",
                               sanitize($this->$fld));
    	}
    }
    //Remove final delimiter
    return substr($string,strlen($delimiter));
  }
  
}

?>