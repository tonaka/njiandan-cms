<?php defined('SYSPATH') OR die('No direct access allowed.');

class Backup_Theme_Data_Controller extends Controller {

    public function index() {
        $theme = Kohana::config('core.theme');
        $data_folder = APPPATH . "themes/$theme/application/data/";
        $data_file = $data_folder . 'data.php';
        $njiandan_folder = "/webroot/themes/$theme/application/data/";
        $njiandan_path = "/webroot/themes/$theme/application/data/data.php";
        $errors = '';
        if ($_POST) {
            // check the ability
            if (!is_dir($data_folder)) {
                $errors = sprintf(T::_('Error: Folder %s isn&#8217;t exist, please create it and set it writable.'), $njiandan_folder);
            } else if (!is_writable($data_folder)) {
                $errors = sprintf(T::_('Error: Folder %s isn&#8217;t writable.'), $njiandan_folder);
            } else {
                if (file_exists($data_file) and !is_writable($data_file)) {
                    $errors = sprintf(T::_('Error: File %s isn&#8217;t writable.'), $njiandan_path);
                } else {

                    /*
                        backup data
                    */
                    $this->db = new Database();
                    $tables = array('diagrams', 'customfields', 'customvalues', 'posts', 'attachments');
                    $output = "<?php defined('SYSPATH') OR die('No direct access allowed.');\n\n";
                    foreach($tables as $table) {
                        $output .= $this->_write_data($table);
                    }
                    $output .= "\n?>";

                    $handle = fopen($data_file, 'w+');
                    fwrite($handle, $output);
                    fclose($handle);


                    /*
                        delete previous theme backup file
                    */
                    file::rm_dir($data_folder . 'webroot');

                    /*
                        backup file
                    */
                    //$this->_backup_attach($theme);
                    file::copy_dir(APPPATH . 'attach', APPPATH . 'themes/' . $theme . '/application/data/webroot/attach', true);

                    Tip::set('Backup done.');
                    url::admin_redirect('backup_theme_data');
                }
            }
        }

        $view = new View('layouts/admin');
        $view->page_title = 'Backup theme data';
        $view->errors = $errors;
        $view->njiandan_folder = $njiandan_folder;
        $view->render(true);
    }

    protected function _write_data($table_name) {
        $results = $this->db->from($table_name)->get();
        $count = count($results);
        if (empty($count)) {
            return '';
        }
        $fields = $this->db->list_fields($table_name);
        $fields_count = count($fields);

        $search = array("\\", "'", "\x00", "\x0a", "\x0d", "\x1a", '"', '$');
        $replace = array("\\\\", "\\'", '\0', '\n', '\r', '\Z', '\\"', '\$');
        $fields_array = array_keys($fields);
        $fields_list = '';
        foreach($fields_array as $field) {
            $fields_list .= '`' . $field . '`,';
        }
        $fields_list = trim($fields_list, ',');

        $sql_data = "// data from $table_name\n";
        $sql_data .= '$db->query("INSERT INTO `{$prefix}' . $table_name . '` (' . $fields_list . ') VALUES ' . "\n";

        $i = 1;
        # write each filed value
        foreach($results as $row) {
            $sql_data .= '(';
            $values = array();
            foreach($fields as $key => $field) {
                if (!isset($row->$key) || is_null($row->$key)) {
                    $values[$key] = 'NULL';
                } else if ($field['type'] == 'int') {
                    $values[$key] = $row->$key;
                } else {
                    $values[$key] = "'" . str_replace($search, $replace, $row->$key) . "'";
                }
            }
            $ext = ',';
            if ($i == $count) {
                $ext = ';';
            }
            $i++;
            $sql_data .= implode(', ', $values) . ')' . $ext . "\n";
        }
        $sql_data .= '");' . "\n\n";
        return $sql_data;
    }

    protected function _backup_attach($theme) {
        $destination_path = APPPATH . "themes/$theme/application/data/";
        $attachments = ORM::factory('attachment')->find_all();
        foreach($attachments as $attachment) {
            $current_file = DOCROOT . $attachment->filename;
            $destination_file = $destination_path . $attachment->filename;
            if (file_exists($current_file)) {
                $destination_folder = dirname($destination_file);
                if (!is_dir($destination_folder)) {
                    mkdir($destination_folder, 0777, true);
                }
                copy($current_file, $destination_file);
            }
        }
    }

}
