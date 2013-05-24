<?php defined('SYSPATH') OR die('No direct access allowed.');

class Data_Mysql_Driver extends Data_Driver {

    public function __construct($filename, $format, $download, $store) {
        parent::__construct($filename, $format, $download, $store);
    }

    public function backup() {
        $this->write_start();
        $tables = $this->db->list_tables();

        foreach ($tables as $table) {
            $this->write_table($table);
            $this->write_data($table);
        }
        $this->write_end();
    }

    public function write_table($table_name) {
        $result = $this->db->query("SHOW CREATE TABLE $table_name");
        $sql_data = str_replace("CREATE TABLE `$table_name`", "CREATE TABLE IF NOT EXISTS `$table_name`", $result[0]->{'Create Table'}) . ";\n";
        $sql_data .= "\nTRUNCATE TABLE $table_name;\n";
        $this->flush($sql_data);
    }

    public function write_data($table_name) {
        $results = $this->db->from($table_name)->get();
        $count = count($results);

        $fields = $this->db->list_fields($table_name);
        $fields_count = count($fields);

        $search = array("\\", "'", "\x00", "\x0a", "\x0d", "\x1a", '"');
        $replace = array("\\\\", "\\'", '\0', '\n', '\r', '\Z', '\\"');
        $fields_array = array_keys($fields);
        $fields_list = '';
        foreach($fields_array as $field) {
            $fields_list .= '`' . $field . '`,';
        }
        $fields_list = trim($fields_list, ',');

        $sql_data = 'INSERT INTO `' . $table_name . '` (' . $fields_list . ') VALUES ';
        $first_set = true;
        $query_len = 0;

        # write each filed value
        foreach($results as $row) {
            $query = $sql_data . '(';
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
            $query .= implode(', ', $values) . ')';
            $this->flush($query . ";\n");
        }
    }
}
