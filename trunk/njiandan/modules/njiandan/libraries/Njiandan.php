<?php defined('SYSPATH') OR die('No direct access allowed.');

class Njiandan {
    // is the admin visit
    public static $is_admin_uri = false;
    public static $config_data = array();
	/**
	 * Check is the admin status,
	 * set the $is_admin login static status
	 *
	 * @return  null
	 */
    public static function check_admin_uri() {
        if (isset(Router::$segments[0]) && Router::$segments[0] == Kohana::config('njiandan.admin_uri')) {
            self::$is_admin_uri = TRUE;
        } else {
            self::$is_admin_uri = FALSE;
        }
        return self::$is_admin_uri;
    }

	/**
	 * Sets a configuration item and save it into config file, if allowed. a method like Kohana::config_set
	 *
	 * @param   string   config key string
	 * @param   string   config value
	 * @return  boolean
	 */
    public static function config_save($key, $value, $create = true) {
		//get original value
        $original_value = Kohana::config($key);
        if (is_numeric($value)) {
            $value = $value . '';
        }
		// Get the group name from the key
		$keys = explode('.', $key, 2); // $keys[0] is the config filename, $keys[1] is the parameter name
        $config_file = APPPATH . 'config/' . $keys[0] . '.php';

        if (!file_exists($config_file) && $create) {
            $config_files = Kohana::find_file('config', $keys[0]);
            self::$config_data[$keys[0]] = file_get_contents(array_pop($config_files));
            $handle = fopen($config_file, 'w+b');
            fclose($handle);
        }

        if (empty(self::$config_data[$keys[0]])) {
            self::$config_data[$keys[0]] = file_get_contents($config_file);
        }

        /*
            check if is array to array or string to string
        */
        $value = var_export($value, true);
        self::$config_data[$keys[0]] = preg_replace('#(\$config\[\'' . $keys[1] . '\'\] = )(.*?)([\)\']{1})(;\s+)#s', '$1' . $value . '$4', self::$config_data[$keys[0]]);

        file_put_contents($config_file, self::$config_data[$keys[0]]);
        return true;
    }

    // check if njiandan is installed
    public static function is_installed() {
		
        if (!file_exists(APPPATH . 'config/database.php')) {
			return false;
        }

        if (!file_exists(APPPATH . 'cache')) {
            return false;
        }

        $cache = Cache::instance();
        if ($cache->get('njiandan_installed')) {
            return true;
        } else {
            return false;
        }
    }

    public static function upload_file_filter($value) {
        $base = trim(url::base(), '/');
        $replace = empty($base) ? '' : '/' . $base;
        $replace = $replace . '$1';
        $result = preg_replace('/\/?[a-z]*(\/webroot\/attach\/[0-9\/]*\.?[a-z0-9]*)/i', $replace, $value);
        return $result;
    }
}
