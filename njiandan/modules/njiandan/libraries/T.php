<?php defined('SYSPATH') OR die('No direct access allowed.');

class T {
    public static $languages;
    public static $instance;
    public static $files;

	/**
	 * Loads languages and initializes it. 
	 *
	 * @return  bool  is instance
	 */
	public static function & instance() {
        if (self::$instance === NULL) {
            if (empty(self::$files)) {
                self::$files = array();
            }

            if (empty(self::$languages)) {
                self::$languages = array();
            }
            // get default language
            $default_language = Kohana::config('njiandan.default_language');
            if (empty($default_language)) {
                $default_languages = Kohana::user_agent('languages');
                if (isset($default_languages[0])) {
                    $default_language = $default_languages[0];
                }
            }
            if (!empty($default_language)) {
                $language_file = Kohana::find_file('languages', $default_language);
                if ($language_file) {
                    array_unshift(self::$files, $language_file);
                }
            }

            // cache the languages
            if (is_array(self::$files)) {
                foreach(self::$files as $file) {
                    include $file;
                    if (isset($lang) && is_array($lang)) {
                        self::$languages += $lang;
                    }
                }
            }
            self::$instance = true;
        }
        return self::$instance;
    }

    public static function _($text) {
        self::instance();
        if( isset(self::$languages[$text]) && self::$languages[$text] !== '' ) {
            return self::$languages[$text];
        }
        return $text;
    }

    public static function _e($text) {
        self::instance();
        echo self::_($text);
    }


    public static function get_language_data($language_file) {
        $language_data = file_get_contents($language_file);
        preg_match('|Language Name:(.*)$|mi', $language_data, $language_name);
        $name = '';

        if (!empty($language_name[1])) {
            $name = trim($language_name[1]);
        }

        return array('name' => $name);
    }

    public static function add_language($folder, $language_file) {
        $file = Kohana::find_file($folder, $language_file);
        if ($file) {
            self::$files[] = $file;
        }
    }

    // get all the languages in the languages folder
    public static function get_languages() {
        $languages = array();
        $languages_dir = APPPATH . 'languages/';
        if (is_dir($languages_dir)) {
            if ($dh = opendir($languages_dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (strpos($file, '.') !== 0) {
                        if (is_file($languages_dir . $file)) {
                                $languages_data = self::get_language_data("{$languages_dir}{$file}");
                                if (!empty($languages_data['name'])) {
                                    $languages_data['file_name'] = rtrim($file, '.php');
                                    $languages[] = $languages_data;
                                }
                        }
                    }
                }
                closedir($dh);
            }
        }
        $english[0] = array('name'=>'English', 'file_name'=>'en');
        $languages = array_merge($english, $languages);
        foreach($languages as $language) {
            $language_options[$language['file_name']] = $language['name'];
        }
        return $language_options;
    }
}
