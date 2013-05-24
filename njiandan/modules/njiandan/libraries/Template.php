<?php defined('SYSPATH') OR die('No direct access allowed.');

class Template_Core {
    public static $templates;

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

    /*
        check template file is exists
    */
    public static function is_template_exists($template) {
        $theme = Kohana::config('core.theme');
        return file_exists( APPPATH . 'themes/' . $theme . '/' . $template . '.php');
    }

    // get all the templates as array filename=>template name
    public static function all_as_array($theme, $type = array()) {
        if (empty(self::$templates[$theme])) {
            self::$templates[$theme] = self::get_all($theme);
        }
        $output = array();
        foreach(self::$templates[$theme] as $template) {
            if (str_replace($type, '', $template['type']) != $template['type']) {
                $output[$template['filename']] = $template['name'];
            }
        }
        return $output;
    }

    // get all the templates
    public static function get_all($theme) {
        $templates = array();
        $template_dir = APPPATH . 'themes/' . $theme;
        if (empty(self::$templates[$theme])) {
            self::$templates[$theme] = array();
            if (is_dir($template_dir)) {
                if ($dh = opendir($template_dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (strpos($file, '.php')) {
                            if (is_file("$template_dir/$file")) {
                                $info = self::get_info("$template_dir/$file");
                                if (!empty($info['name'])) {
                                    $info['filename'] = substr($file, 0, -4);
                                    array_unshift(self::$templates[$theme], $info);
                                }
                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }
        return self::$templates[$theme];
    }

    public static function get_info($file) {
        $theme_data = file_get_contents($file);
        preg_match('|Template Type:(.*)$|mi', $theme_data, $type);
        preg_match('|Template Name:(.*)$|mi', $theme_data, $name);
        preg_match('|Template Description:(.*)$|mi', $theme_data, $uri);

        $info = array('name'=>'', 'type'=>'', 'description'=>'');
        $info['name'] = isset($name[1]) ? trim($name[1]) : '';
        $info['type'] = isset($type[1]) ? trim($type[1]) : '';
        $info['description'] = isset($description[1]) ? trim($description[1]) : '';

        return $info;
    }
}
