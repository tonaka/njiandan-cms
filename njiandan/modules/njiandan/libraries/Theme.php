<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Theme_Core {
    public static $themes;

    public static function current() {
        $current_theme = Kohana::config('core.theme');
        $themes = self::get_all();
        foreach($themes as $theme) {
            if ($theme['folder'] == $current_theme) {
                return $theme;
            }
        }
        return array();
    }

    // check the theme is exists
    public static function is_exists($current_theme) {
        $themes = self::get_all();
        foreach($themes as $theme) {
            if ($theme['folder'] == $current_theme) {
                return true;
            }
        }
        return false;
    }

    // get all the themes
    public static function get_all() {
        $themes = array();
        $themes_dir = APPPATH . 'themes';
        if (empty(self::$themes)) {
            self::$themes = array();
            if (is_dir($themes_dir)) {
                if ($dh = opendir($themes_dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (strpos($file, '.') !== 0) {
                            if (is_dir($themes_dir . '/' . $file)) {
                                if (is_file("$themes_dir/$file/readme.html")) {
                                    $info = self::get_info($file);
                                    $info['folder'] = $file;
                                    array_unshift(self::$themes, $info);
                                }
                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }
        return self::$themes;
    }

    public static function get_info($theme) {
        $file = APPPATH . "themes/$theme/readme.html";
        $theme_data = file_get_contents($file);
        preg_match('|Theme Name:(.*)$|mi', $theme_data, $name);
        preg_match('|Theme URI:(.*)$|mi', $theme_data, $uri);
        preg_match('|Description:(.*)$|mi', $theme_data, $description);
        preg_match('|Version:(.*)$|mi', $theme_data, $version);
        preg_match('|Author:(.*)$|mi', $theme_data, $author);
        preg_match('|Author URI:(.*)$|mi', $theme_data, $author_uri);
        preg_match('|Tags:(.*)$|mi', $theme_data, $tags);

        $info = array('name'=>'', 'uri'=> '', 'description'=>'', 'version'=>'', 'author'=>'', 'author_uri'=>'', 'tags'=>'');
        $info['name'] = isset($name[1]) ? trim($name[1]) : '';
        $info['uri'] = isset($uri[1]) ? trim($uri[1]) : '';
        $info['description'] = isset($description[1]) ? trim($description[1]) : '';
        $info['version'] = isset($version[1]) ? trim($version[1]) : '';
        $info['author'] = isset($author[1]) ? trim($author[1]) : '';
        $info['author_uri'] = isset($author_uri[1]) ? trim($author_uri[1]) : '';
        $info['tags'] = isset($tags[1]) ? trim($tags[1]) : '';

        return $info;
    }
}
