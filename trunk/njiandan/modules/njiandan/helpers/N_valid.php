<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Validation helper class.
 *
 */
class valid extends valid_Core {

    public static function username($username) {
        $original_username = $username;
        $username = strip_tags($username);
        // Kill octets
        $username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);
        $username = preg_replace('/&.+?;/', '', $username);
        if ($username != $original_username) {
            return false;
        } else {
            return true;
        }
    }

    public static function uri($uri) {
        if (empty($uri)) {
            return false;
        }
        $search = array('?', '#', '%', '^', '&', '*', '"', "'", '<', '>', '\\');
        $new_uri = str_replace($search, '', $uri);
        if ($new_uri != $uri) {
            return false;
        } else {
            return true;
        }
    }

    public static function tag($tag) {
        $new_tag = preg_replace('/[^a-z-_]/i', '', $tag);
        if ($new_tag != $tag) {
            return false;
        } else {
            return true;
        }
    }
}
