<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Date helper class.
 *
 */
class file extends file_Core {
    
    //get the file basename
    public static function basename($file) {
        $pos = strrpos($file, '.');
        if ($pos !== false) {
            return substr($file, 0, $pos);
        } else {
            return $pos;
        }
    }

    // get the file extra
    public static function extra($file) {
        $pos = strrpos($file, '.');
        if ($pos !== false) {
            return substr($file, $pos + 1);
        } else {
            return '';
        }
    }

    /*
        rm all the files and folder under this path.
    */
    public static function clean_dir($dir_path) {
        self::rm_dir($dir_path, true);
    }

    /*
        delete folder and files
    */
    public static function rm_dir($dir_path, $only_children = false) {
        if (empty($dir_path)) {
           return null;
        }

        if (file_exists($dir_path)) {
            $dir = dir($dir_path);
            while($file = $dir->read()) {
                if($file != '.' && $file != '..') {
                    if(is_dir($dir_path.'/'.$file)) {
                        self::rm_dir($dir_path.'/'.$file);
                    } else {
                        @unlink($dir_path.'/'.$file);
                    }
                }
            }
            if (!$only_children) {
                @rmdir($dir_path.'/'.$file);
            }
        }
    }

    /*
        copy folder and files
    */
    public static function copy_dir($source, $dest, $overwrite = false) {
        if ($handle = opendir($source)) {        // if the folder exploration is sucsessful, continue
            while(false !== ($file = readdir($handle))) { // as long as storing the next file to $file is successful, continue
                if ($file != '.' && $file != '..') {
                    $path = $source . '/' . $file;
                    if (is_file($path)) {
                        if (!is_file($dest . '/' . $file) || $overwrite) {
                            if (!@copy($path, $dest . '/' . $file)) {
                                
                            }
                        }
                    } else if (is_dir($path)) {
                        if (!is_dir($dest . '/' . $file)) {
                            mkdir($dest . '/' . $file, 0777, true); // make subdirectory before subdirectory is copied
                        }
                        self::copy_dir($path, $dest . '/' . $file, $overwrite); //recurse!
                    }
                }
            }
            closedir($handle);
        }
    }
}
