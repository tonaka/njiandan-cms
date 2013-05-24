<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class PostTag_Core {

    public static $uri_post;
    public static $id_post;
    public static $previous_post;
    public static $next_post;

	/**
	 * Prepares the post data,        $default = array('uri'=>'', 'id'=>'');
        $args += $default;
	 * loads customvalues.
	 * loads attachments
	 *
	 * @return  void
	 */
	public static function get_post($args = array()) {
	    $default = array('uri'=>'', 'id'=>'');
        $args = $args + $default;
        $uri = trim(URI::segment(2));
        // check the uri is a id or a truly uri
        // make sure the id is always available
        $post_id = (int)$uri;
        if ($post_id == $uri) {
            $args['id'] = $post_id;
        }

        // if use id to get post
        if (!empty($args['id'])) {
            if (!is_object(self::$id_post[$args['id']])) {
                self::$id_post[$args['id']] = ORM::factory('post')->find($args['id']);
            }
            return self::$id_post[$args['id']];
            // if use set uri to get post
        } else if (!empty($args['uri'])) {
            // check if it has been get
            if (!isset(self::$uri_post[$args['uri']]) or !is_object(self::$uri_post[$args['uri']])) {
                self::$uri_post[$args['uri']] = ORM::factory('post')->where('uri', $args['uri'])->find();
            }
            return self::$uri_post[$args['uri']];
            // else use current uri get post
        } else if (strtolower(URI::segment(1)) == strtolower(Kohana::config('njiandan.post_uri')) && !empty($uri)) {
            $uri_optimize = Kohana::config('njiandan.uri_optimize');
	        if ($uri_optimize == 'uri') {
	            if (!isset(self::$uri_post[$uri]) or !is_object(self::$uri_post[$uri])) {
	                self::$uri_post[$uri] = ORM::factory('Post')->where('uri', $uri)->find();
	            }
	            return self::$uri_post[$uri];
	        } else if ($uri_optimize == 'id') {
	            if (!isset(self::$id_post[$uri]) or !is_object(self::$id_post[$uri])) {
	                self::$id_post[$uri] = ORM::factory('Post')->find($uri);
	            }
	            return self::$id_post[$uri];
	        }
        } else {
            return new Post_Model();
        }
	}

    // check current uri is a valid post uri
    public static function is_post($args = array()) {
        if (self::id($args)) {
            return true;
        } else {
            return false;
        }
    }

    public static function post($args = array()) {
        return self::get_post($args);
    }

    public static function id($args = array()) {
        return self::post($args)->id;
    }

    public static function title($args = array()) {
        return self::post($args)->title;
    }

    public static function content($args = array()) {
        return self::post($args)->content;
    }

    public static function date($args = array()) {
        $format = 'Y-m-d H:i';
        if (isset($args['format'])) {
            $format = $args['format'];
        }
        return date($format, self::post($args)->date);
    }

    public static function author($args = array()) {
        return self::post($args)->user->username;
    }

    public static function thumb($args = array()) {
        return self::post($args)->thumb;
    }

    public static function is_star($args = array()) {
        if (self::post($args)->is_star) {
            return true;
        } else {
            return false;
        }
    }

    public static function uri($args = array()) {
        return self::post($args)->uri;
    }

    public static function diagram($args = array()) {
        return self::post($args)->diagram;
    }

    public static function attachments($args = array()) {

    }

    public static function comment_count($args = array()) {
        
    }

    public static function comments($args = array()) {
    
    }

    // check if has next post
    public static function has_next_post($args = array()) {
        if (self::next_post($args)->id) {
            return true;
        } else {
            return false;
        }
    }

    // get next post
    public static function next_post($args = array()) {
        if (!is_object(self::$next_post[self::id($args)])) {
            self::$next_post[self::id($args)] = ORM::factory('post')->where('diagram_id', self::post($args)->diagram_id)->where('date<', self::post($args)->date)->orderby(array('date' => 'DESC'))->find();
        }
        return self::$next_post[self::id($args)];
    }

    // get next post link
    public static function next_post_link($args = array()) {
        if (self::next_post($args)->id) {
            return html::anchor(self::next_post($args)->link, T::_('Next post') . ': ' . self::next_post($args)->title);
        }
    }

    // check if has previous post
    public static function has_previous_post($args = array()) {
        if (self::previous_post($args)->id) {
            return true;
        } else {
            return false;
        }
    }

    // get previous post
    public static function previous_post($args = array()) {
        if (!is_object(self::$previous_post[self::id($args)])) {
            self::$previous_post[self::id($args)] = ORM::factory('Post')->where('diagram_id', self::post($args)->diagram_id)->where('date>', self::post($args)->date)->orderby(array('date' => 'ASC'))->find();
        }
        return self::$previous_post[self::id()];
    }

    // get previous post link
    public static function previous_post_link($args = array()) {
        if (self::previous_post($args)->id) {
            return html::anchor(self::previous_post($args)->link, T::_('Previous post') . ': ' . self::previous_post($args)->title);
        }
    }

    // a lazy way out put post
    public static function as_div($args = array()) {
        $output = '<div id="content" class="widecolumn post-page post-page-' . self::id($args) . '">';
        $output .= '<div class="post" id="post_' . self::id($args) . '">';
        $output .= '<h2>' .  self::title($args) . '</h2>';
        $output .= '<p class="post_meta">' . self::author($args) . ' post at ' . self::date($args) . '</p>';
        $output .= '<div class="post_content">';
        $output .= '<p>' . self::content($args) . '</p>';
        $output .= '</div></div>';
        $output .= '
        <div class="navigation">
            <div class="alignleft">' . self::previous_post_link($args) . '</div>
            <div class="alignright">' . self::next_post_link($args) . '</div>
        </div>';
        $output .= '</div>';
        return $output;
    }

    public static function as_ul($args = array()) {
        
    }

    public static function as_table($args = array()) {
        
    }
}
