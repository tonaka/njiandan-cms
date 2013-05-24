<?php defined('SYSPATH') OR die('No direct access allowed.');

class PageTag_Core {
    public static $uri_page;
    public static $uri;
    public static $id_page;

	public static function get_page($args = array()) {
	    $default = array('uri'=>'', 'id'=>'');
        $args = $args + $default;
        if (!empty($args['id'])) {
	        if (!isset(self::$id_page[$args['id']]) or !is_object(self::$id_page[$args['id']])) {
	            self::$id_page[$args['id']] = ORM::factory('diagram')->find($args['id']);
	        }
	        return self::$id_page[$args['id']];
	    } else {
	        self::$uri = !empty($args['uri']) ? $args['uri'] : Router::$current_uri;
	        if (self::$uri == 'njiandan_template') {
	            self::$uri = '/';
	        }
	        if (!isset(self::$uri_page[self::$uri]) or !is_object(self::$uri_page[self::$uri])) {
	            self::$uri_page[self::$uri] = ORM::factory('diagram')->where(array('uri'=>self::$uri, 'type'=>'page'))->find();
	        }
	        return self::$uri_page[self::$uri];
	    }
	}

    public static function is_page($args = array()) {
        if (self::id($args)) {
            return true;
        } else {
            return false;
        }
    }

    public static function page($args = array()) {
        return self::get_page($args);
    }

    public static function id($args = array()) {
        return self::page($args)->id;
    }

    public static function title($args = array()) {
        return self::page($args)->title;
    }

    public static function content($args = array()) {
        return self::page($args)->content;
    }

    // get current page uri
    public static function uri($args = array()) {
        return self::page($args)->uri;
    }

    public static function parent_id($args = array()) {
        return self::page($args)->parent_id;
    }

    // get the parent
    public static function parent($args = array()) {
        return self::page($args)->parent;
    }

    // get the page children
    public static function children($args = array()) {
        $children = array();
        foreach(self::page($args)->children as $row) {
            $children[$row->id] = $row;
        }
        return $children;
    }

    public static function template($args = array()) {
        return self::page($args)->template;
    }

    public static function order($args = array()) {
        return self::page($args)->order;
    }

    public static function as_div($args = array()) {
        $output = '<div id="content" class="widecolumn post-page post-page-' . self::id($args) . '">';
        $output .= '<div class="post" id="post_' . self::id($args) . '">';
        $output .= '<h2>' .  self::title($args) . '</h2>';
        $output .= '<div class="post_content">';
        $output .= '<p>' . self::content($args) . '</p>';
        $output .= '</div></div>';
        $output .= '</div>';
        return $output;
    }
}
