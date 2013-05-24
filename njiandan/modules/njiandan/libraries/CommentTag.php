<?php defined('SYSPATH') OR die('No direct access allowed.');

class CommentTag_Core {
    protected static $id;
    protected static $type;
    protected static $comment_list = array();

    protected static function __initialize($args = array()) {
        if (PostTag::is_post($args)) {
            self::$type = 'post';
            self::$id = PostTag::id($args);
        } else if (DiagramTag::is_page($args)) {
            self::$type = 'page';
            self::$id = DiagramTag::id($args);
        }
    }

    public static function form_url() {
        self::__initialize();
        $type = self::$type;
        $id = self::$id;
        Kohana::config_set('core.url_suffix', '');
        return $url = url::admin_site('comments/add') . "/$id/$type";
    }

    // catch the value, errors
    public static function values($key = '') {
        $key = 'Comment_' . $key;
        $output = cookie::get($key);
        cookie::set($key, '');
        return $output;
    }

    public static function count($args = array()) {
        return count(self::comment_list($args));
    }

    // get all comments
    public static function comment_list($args = array()) {
        self::__initialize($args);
        $type = self::$type;
        $id = self::$id;
        if (!isset(self::$comment_list[$type][$id])) {
            $comments = array();
            if ($type == 'post') {
                $comments = PostTag::post($args)->comments;
            } else {
                $comments = DiagramTag::diagram($args)->comments;
            }
            self::$comment_list[$type][$id] = $comments;
        }
        return self::$comment_list[$type][$id];
    }


    public static function comment_list_as_ul($args = array()) {
        $default_args = array('list_type'=>'ol', 'list_id'=>'comment_list', 'li_class'=>'');
        $args += $default_args;
        $output ='<' . $args['list_type'] . ' id="' . $args['list_id'] . '">' . "\n";
        $comments = self::comment_list($args);
        foreach($comments as $comment) {
            $output .= '<li id="comment_' . $comment->id . '"';
            if (!empty($args['li_class'])) {
                $output .= ' class="' . $args['li_class'] . '"';
            }
            $output .=">\n";
            $output .= '<p>' . $comment->username . ':</p>' . "\n";
            $output .= '<p>' . $comment->content . '</p>' . "\n";
            $output .= '<p>' . date('m/d/Y H:i:s a', $comment->date) . '</p>' . "\n";
            $output .= '</li>' . "\n";
        }
        $output .= '</' . $args['list_type'] . '>' . "\n";
        return $output;
    }

    public static function comment_form($args = array()) {
        $default_args = array('form_id'=>'comment_form');
        $args += $default_args;
        $output = '<form action="' . CommentTag::form_url() . '" method="post" id="' . $args['form_id'] . '">' . "\n";
        if (UserTag::is_login()) {
            $output .= '<p>' . T::_('Logged in as') . ' ' . html::admin_anchor('profile', UserTag::username()) . '.</p>';
        } else {
            $output .= '<p><input name="username" size="30" value="' . CommentTag::values('username') . '"/> <label for="author">' . T::_('Username') . '(*) </label>';
            if (CommentTag::values('username_error')) {
                $output .= ' <span class="error">' . CommentTag::values('username_error') . '</span>';
            }
            $output .= '</p>';
            $output .= '<p><input name="email" size="30" value="' . CommentTag::values('email') . '"/> 
            <label for="email">' . T::_('Mail') . '(*) </label> ';
            if (CommentTag::values('email_error')) {
                $output .= '<span class="error">' . CommentTag::values('email_error') . '</span>';
            }
            $output .= '</p>';
            $output .= '<p><input name="url" size="30" value="' . CommentTag::values('url') . '"/>  
            <label for="url">' . T::_('Website') . '</label> ';
            if (CommentTag::values('url')) {
                $output .= ' <span class="error">' . CommentTag::values('url_error') . '</span>';
            }
            $output .= '</p>';
        }
        $output .= '<p><textarea name="content" rows="10" cols="70%" >' . CommentTag::values('content') . '</textarea>';
        if (CommentTag::values('content_error')) {
            $output .= '<span class="error">' . CommentTag::values('content_error') . '</span>';
        }
        $output .= '</p>';
        $output .= '<input type="hidden" id="parent_id" name="parent_id" />';
        $output .= '<p><input type="submit" id="submit" tabindex="5" value="' . T::_('Submit Comment') . '" /></p>
        </form>';
        return $output;
    }
}
