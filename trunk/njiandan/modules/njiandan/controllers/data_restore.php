<?php defined('SYSPATH') OR die('No direct access allowed.');

class Data_Restore_Controller extends Controller {
    protected $per_size = 102400; // byte;

    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = 'Data Restore';
        $view->data_files = Data::get_datafiles();
        $view->render(TRUE);
    }

    public function ajax_restore($filename = '') {
        $data_file = APPPATH . 'backup/' . $filename;
	    $handle = @fopen($data_file,"r");
	    flock($handle,LOCK_SH);
	    $data_size = filesize($data_file);

	    if ($data_size <= $this->per_size) {
	        $sql = file_get_contents($data_file);
            $this->_query_data($sql);
	        echo 'done';
	    } else {
	        echo 'javascript_restore';
	    }
    }

    public function javascript_restore($filename, $offset = 0) {
        $data_file = APPPATH . 'backup/' . $filename;
	    $handle = @fopen($data_file,"r");
	    flock($handle,LOCK_SH);
	    fseek($handle, $offset);
	    // get the count
	    $data_size = filesize($data_file);
        $count = ceil(($data_size - $offset)/$this->per_size);

	    $sql = fread($handle, $this->per_size);
	    if (!empty($sql) and $pos = strrpos($sql, ";\n")) {
	        $sql = substr($sql, 0, $pos + 2);
	        $offset += strlen($sql);
	        $this->_query_data($sql);
        } else {
            Tip::set('Restore done.');
            url::admin_redirect('data_restore');
        }
        $view = new View('layouts/admin');
        $view->page_title = 'Data Restore';
        $view->offset = $offset;
        $view->filename = $filename;
        $view->count = $count;
        $view->render(TRUE);
    }

    protected function _query_data($sql) {
        $sql_data = explode(";\n", $sql);
        $db = new Database();
        $db_config = Kohana::config('database.default');
        $prefix = $db_config['table_prefix'];
        foreach($sql_data as $query) {
            if (!empty($query)) {
                $db->query($query . ';');
            }
        }
    }
}
