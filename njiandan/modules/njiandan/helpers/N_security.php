<?php defined('SYSPATH') OR die('No direct access allowed.');

class security extends security_Core {

    public static function sanitize_username($username) {
        $username = strip_tags($username);
        // Kill octets
        $username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);
        $username = preg_replace('/&.+?;/', '', $username); // Kill entities
        return $username;
    }
}
