<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Router
 *
 */
class Router extends Router_Core {

    // get current template uri,if the uri is njiandan_template controller, set it as /
    public static function template_uri() {
        return self::$current_uri == 'njiandan_template' ? 'index' : self::$current_uri;
    }
}
