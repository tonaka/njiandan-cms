<?php defined('SYSPATH') OR die('No direct access allowed.');

class PieceTag_Core {
    public static $pieces = array();

    public static function content($tag) {
        if (empty(self::$pieces[$tag])) {
            self::$pieces[$tag] = ORM::factory('post')->where(array('uri'=>$tag, 'diagram_id'=>0, 'status'=>0))->find();
        }
        return self::$pieces[$tag]->content;
    }

    public static function title($tag) {
        if (empty(self::$pieces[$tag])) {
            self::$pieces[$tag] = ORM::factory('post')->where(array('uri'=>$tag, 'diagram_id'=>0, 'status'=>0))->find();
        }
        return self::$pieces[$tag]->title;
    }
}
