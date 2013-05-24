<?php defined('SYSPATH') OR die('No direct access allowed.');
	 
class upload extends upload_Core {
	/**
	 * Save upload file to attach direcotry.
	 *
	 * @param   mixed    name of $_FILE input or array of upload data
	 * @param   integer  chmod mask
	 * @return  string   full path to new file
	 */
	public static function save($key, $chmod = 0644) {

        // Load file data from FILES if not passed as array
		$file = $_FILES[$key];

        // njiandan save directory
        $njiandan_directory = WEBROOT . '/attach/' . date('Y/n/j/');
		// Make sure the directory ends with a slash
		$directory = DOCROOT . $njiandan_directory;

		$filename = self::_get_unique_filename($file, $directory);

        $njiandan_filename = $njiandan_directory . $filename;
		if (!is_dir($directory)) {
			// Create the upload directory
			mkdir($directory, 0777, True);
		}

		if (!is_writable($directory)) {
			throw new Kohana_Exception('upload.not_writable', $directory);
	    }

		if (is_uploaded_file($file['tmp_name']) AND move_uploaded_file($file['tmp_name'], $filename = $directory . $filename)) {
			if ($chmod !== False) {
				// Set permissions on filename
				chmod($filename, $chmod);
			}

			// Return new file path
			return $njiandan_filename;
		}

		return False;
	}

	

	/**
	 * Delete attach file.
	 *
	 * @param   integer attach id  
	 * @return  true or false
	 */
    
	public static function delete($filename) {
        
		 $file_path = DOCROOT . $filename;
        
		 if (is_file($file_path)) {
            
			return unlink($file_path);
        
		 }
        
		 return False;
    
	}

    
	protected function _get_unique_filename($file, $directory, $step = 0) {
        $file_extra = ($pos = strrpos($file['name'], '.')) ? strtolower(substr($file['name'], $pos)) : '';
        $filename = (time() + $step) . $file_extra;
        
		if (file_exists($directory . $filename)) {
            
				return self::_get_unique_filename($file, $directory, $step +1);
        
		} 
		else {
            
			return $filename;
        
		}
    
	}
}
