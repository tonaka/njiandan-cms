<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Router
 *
 */
class URI extends URI_Core {

    public static function sanitize($uri) {
        $uri = trim($uri);
        $search = array('?', '#', '/', '%', '^', '&', '*', '"', "'", '<', '>');
        $uri = str_replace($search, '-', $uri);
        $uri = preg_replace('/-+/', '-', $uri);
        $uri = trim($uri, '-');
        return $uri;
    }
}
