<?php defined('SYSPATH') OR die('No direct access allowed.');

class NjiandanTag_Core {

    // get curent page title
    public static function smart_title() {
        // check if is a post page
        $title = '';
        if (PostTag::post()->id) {
            $title .= PostTag::title();
        } else if (DiagramTag::id() && DiagramTag::uri() != '/') {
            $title .= DiagramTag::title();
        }
        if (!empty($title)) {
            $title = $title . ' - ';
        }
        $title = $title . self::site_title();
        return $title;
    }

    // get site title
    public static function site_title() {
        return Kohana::config('njiandan.site_title');
    }

    // get site description
    public static function site_description() {
        return Kohana::config('njiandan.site_description');
    }

    // get site url
    public static function site_url() {
        return Kohana::config('njiandan.site_url');
    }

}
