<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Get the space info.
 * show the info of diskspace you have used and left.
 * show the info of database you have used and left.
 */
class SpaceInfo_Core {
    // get the disk space you have used
    public static function disk_space() {
        $used_space_size = SpaceInfo::holdersize(DOCROOT);
        $space_size = SpaceInfo::size_format(Kohana::config('njiandan.space_size'), 'B');
        $info->total = SpaceInfo::size_format($space_size);
        $info->percent = round(($used_space_size / $space_size), 4) * 100;
        $info->used = SpaceInfo::size_format($used_space_size);
        return $info;
    }

    public static function database() {
        $orginal_database_size = SpaceInfo::used_database();
        $database_size = SpaceInfo::size_format(Kohana::config('njiandan.database_size'), 'B');
        $info->total = SpaceInfo::size_format($database_size);
        $info->percent = round(($orginal_database_size / $database_size ), 4) * 100;
        $info->used = SpaceInfo::size_format($orginal_database_size);
        return $info;
    }

    public static function used_database() {
        $db = new Database();
        $tables = $db->query("SHOW TABLE STATUS");
        $datasize = 0;
        $indexsize = 0;
        foreach($tables as $table) {
            $datasize  += $table->Data_length;
            $indexsize += $table->Index_length;
        }

        return $datasize + $indexsize;
    }
    
    // get the dir's size,
    public static function holdersize($dir , $holdersize = 0) {
        if(@$handle = opendir($dir)) {
            while(false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if(is_dir( $dir . '/' . $file)) {
                        $holdersize = SpaceInfo::holdersize($dir . '/' . $file, $holdersize);
                    }else {
                        $holdersize = $holdersize + filesize($dir . '/' . $file);
                    }
                }
            }
        } else {
            $holdersize = 'Can\'t read folder ' . $dir;
        }

        return $holdersize;
    }

    public static function notice_color($number) {
        $color = '#0a0';
        if ($number < 50) {
            $color = '#0a0';
        } else if ($number >= 85) {
            $color = '#ff0000';
        } else {
            $color = '#ff9900';
        }
        return $color;
    }

    public static function size_format($size, $unit = null) {
        $kb = 1024; // Kilobyte
        $mb = 1024 * $kb; // Megabyte
        $gb = 1024 * $mb; // Gigabyte
        $tb = 1024 * $gb; // Terabyte
        
        if (!is_numeric($size)) {
            $in_size = intval($size);
            $u = strtoupper(str_replace($in_size, '', $size));
            switch ($u) {
                case 'KB':
                case 'K':
                    $size = $in_size * $kb;
                    break;
                case 'MB':
                case 'M':
                    $size = $in_size * $mb;
                    break;
                case 'GB':
                case 'G':
                    $size = $in_size * $gb;
                    break;
                case 'TB':
                case 'T':
                    $size = $in_size * $tb;
                    break;
            }
        }
        if (!empty($unit)) {
            $unit = strtoupper($unit);
            switch($unit) {
                case 'KB':
                case 'K':
                    $result = round($size / $kb, 2) . ' KB';
                    break;
                case 'MB':
                case 'M':
                    $result = round($size / $mb, 2) . ' MB';
                    break;
                case 'GB':
                case 'G':
                    $result = round($size / $gb, 2) . ' GB';
                    break;
                case 'TB':
                case 'T':
                    $result = round($size / $tb, 2) . ' TB';
                    break;
                case 'B':
                case 'BITE':
                default:
                    $result = $size;
            }
        } else {
            if($size < $mb) {
                if($size < $kb) {
                    $result = $size . ' B';
                } else {
                    $result = round($size / $kb, 2) . ' KB';
                }
            } else {
                if($size < $gb) {
                    $result = round($size / $mb, 2) . ' MB';
                } else {
                    if($size < $tb) {
                        $result = round($size / $gb, 2) . ' GB';
                    }else {
                        $result = round($size / $tb, 2) . ' TB';
                    }
                }
            }
        }
        return $result;
    }
}
