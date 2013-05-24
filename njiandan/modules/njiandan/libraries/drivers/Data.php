<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Data_Driver {

    protected $fp;
    protected $write;
    protected $close;
    protected $store;
    protected $download;
    protected $format;
    protected $run_comp = false;
    protected $db;
    protected $db_config;
    public $sql_data = '';
    public $database;
    public $filename;

    public function __construct($filename, $format = 'none', $download = false, $store = false) {
        $this->download = $download;
        $this->store = $store;
        $this->format = $format;
        // set the database table prefix null
        $database = Kohana::config('database.default');
        $database['table_prefix'] = '';
        Kohana::config_set('database.default', $database);

        $this->db_config = Kohana::config('database.default');
        $this->db = new Database();

        switch ($format) {
            case 'bzip2':
                $ext = '.sql.bz2';
                $open = 'bzopen';
                $this->write = 'bzwrite';
                $this->close = 'bzclose';
                $mimetype = 'application/x-bzip2';
                break;
            case 'gzip':
                $ext = '.sql.gz';
                $open = 'gzopen';
                $this->write = 'gzwrite';
                $this->close = 'gzclose';
                $mimetype = 'application/x-gzip';
                break;
            case 'none':
            case 'sql':
            default:
                $ext = '.sql';
                $open = 'fopen';
                $this->write = 'fwrite';
                $this->close = 'fclose';
                $mimetype = 'text/x-sql';
                break;
        }

        $this->filename = $name = $filename . $ext;

        if ($download == true) {
            //header('Pragma: no-cache');
            //header("Content-Type: $mimetype; name=\"$name\"");
            //header("Content-disposition: attachment; filename=$name");

            switch ($format) {
                case 'gzip':
                    if ((isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie') === false) {
                        ob_start('ob_gzhandler');
                    } else {
                        $this->run_comp = true;
                    }
                    break;
                case 'bzip2':
                default:
                    ob_start();
                    break;
            }
        }
        
        if ($store == true) {
            $file = BACKUP_DIR . $name;
            $this->fp = $open($file, 'w');
            if (!$this->fp) {
                trigger_error('Unable to write temporary file to storage folder', E_USER_ERROR);
            }
        }
    }

    public function write_start() {
        $sql_data = "#\n";
        $sql_data .= "# Njiandan Backup Script\n";
        $sql_data .= "# Dump of tables for " . $this->db_config['connection']['database'] . "\n";
        $sql_data .= "# DATE : " . gmdate("d-m-Y H:i:s", time()) . " GMT\n";
        $sql_data .= "#\n";
        $this->flush($sql_data);
    }

    public function write_end() {
        if ($this->store) {
            $close = $this->close;
            $close($this->fp);
        }

        // bzip2 must be written all the way at the end
        if ($this->download) {
            $content = ob_get_clean();
            if ($this->format === 'bzip2') {
                $content = bzcompress($content);
            }
            download::force($this->filename, $content);
            exit();
        }
        //exit();
    }

    public function flush($data) {
        if ($this->store === true) {
            $write = $this->write;
            $write($this->fp, $data);
        }

        if ($this->download === true) {
            if ($this->format === 'bzip2' || $this->format === 'none' || ($this->format === 'gzip' && !$this->run_comp)) {
                echo $data;
            }
            // we can write the gzip data as soon as we get it
            if ($this->format === 'gzip' and $this->run_comp) {
                    echo gzencode($data);
            }
        }
    }

	/**
	 * backup to our data.
	 * Returns FALSE on failure or a MySQL resource.
	 *
	 * @return mixed
	 */
	abstract public function backup();

    public function fgetd(&$fp, $delim, $read, $seek, $eof, $buffer = 8192) {
        $record = '';
        $delim_len = strlen($delim);

        while (!$eof($fp)) {
            $pos = strpos($record, $delim);
            if ($pos === false) {
                $record .= $read($fp, $buffer);
                if ($eof($fp) && ($pos = strpos($record, $delim)) !== false) {
                    $seek($fp, $pos + $delim_len - strlen($record), SEEK_CUR);
                    return substr($record, 0, $pos);
                }
            } else {
                $seek($fp, $pos + $delim_len - strlen($record), SEEK_CUR);
                return substr($record, 0, $pos);
            }
        }

        return false;
    }

    public function fgetd_seekless(&$fp, $delim, $read, $seek, $eof, $buffer = 8192) {
        static $array = array();
        static $record = '';

        if (!sizeof($array)) {
            while (!$eof($fp)) {
                if (strpos($record, $delim) !== false) {
                    $array = explode($delim, $record);
                    $record = array_pop($array);
                    break;
                } else {
                    $record .= $read($fp, $buffer);
                }
            }
            if ($eof($fp) && strpos($record, $delim) !== false) {
                $array = explode($delim, $record);
                $record = array_pop($array);
            }
        }

        if (sizeof($array)) {
            return array_shift($array);
        }

        return false;
    }
}
