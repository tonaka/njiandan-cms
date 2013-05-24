<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * modify from phpbb
 * backup or restore the database.
 * list the diagram.
 * show the manage buttom on the diagram.
 */
 
define('BACKUP_DIR', APPPATH . 'backup/');
class Data_core {

    protected static function _get_driver_name() {
        $database_config = Kohana::config('database.default');
        // Set driver name
		$driver_name = 'Data_'.ucfirst($database_config['connection']['type']).'_Driver';
		return $driver_name;
    }

    public static function backup($action, $file_type) {
        $download = false;
        $store = false;

        $time = time();
        $filename = 'njiandan_db_' . date('Y-m-d H_i_s');
        switch($action) {
            case 'download':
                $download = true;
                break;
            case 'store':
                $store = true;
                break;
            case 'store_and_download':
                $download = true;
                $store = true;
                break;
        }

        if ($store && !file_exists(BACKUP_DIR) || !is_writable(BACKUP_DIR)) {
            return 'File is not exists or is not writable.';
        }

        $driver = self::_get_driver_name();
        // Initialize the driver
        $driver = new $driver($filename, $file_type, $download, $store);
        $database_config = Kohana::config('database.default');
        $driver->database = $database_config['connection']['database'];
        $driver->backup();
        return true;
    }

    public static function restore($file_name) {
        $driver_name = self::_get_driver_name();
        return Data_Mysql_Driver::restore(BACKUP_DIR . $file_name);
    }

    // list the datafiles as a array
    public static function get_datafiles() {
        $files = array();

        if (!file_exists(BACKUP_DIR)) {
            return $files;
        }
        if ($handle = opendir(BACKUP_DIR)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..') {
                    $files[$file] = $file . ' ( ' . SpaceInfo::size_format(filesize(BACKUP_DIR . $file)) . ' ) ';
                    }
            }
            closedir($handle);
        }
        return $files;
    }
}
