<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_Model extends Auth_User_Model {
	
	public function can($action, $argument = null) {
	    if ($this->is_superuser) {
	        return true;
	    }
	    if ($this->has(ORM::factory('role', $action))) {
	        return true;
	    }
	    return false;
	}

}
