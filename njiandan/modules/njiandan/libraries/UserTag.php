<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class UserTag_Core {

    /*
        if empty args, get the current login user
    */
    public static function get_user($args = array()) {
	    $default_args = array('id' => '', 'username' => '', 'email' => '');
        $args = $args + $default_args;
        if (empty($args['id']) and empty($args['username']) and empty($args['email'])) {
            $authentic = new Auth;
            $user = $authentic->get_user();
        } else {
            if (!empty($args['id'])) {
                $user = ORM::factory('user')->where('id', $args['id'])->find();
            } else if (!empty($args['username'])) {
                $user = ORM::factory('user')->where('username', $args['username'])->find();
            } else if (!empty($args['email'])) {
                $user = ORM::factory('user')->where('email', $args['email'])->find();
            }
        }

        if (empty($user->id)) {
            return new User_Model;
        }
        return $user;
    }

    /*
        check is current user login
    */
    public static function is_login() {
        if (self::id()) {
            return true;
        } else {
            return false;
        }
    }

    public static function user($args = array()) {
        return self::get_user($args);
    }

    public static function id($args = array()) {
        return self::get_user($args)->id;
    }

    public static function username($args = array()) {
        return self::get_user($args)->username;
    }

    public static function email($args = array()) {
        return self::get_user($args)->email;
    }  
}
