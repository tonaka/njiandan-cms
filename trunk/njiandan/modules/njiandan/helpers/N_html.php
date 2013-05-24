<?php defined('SYSPATH') OR die('No direct access allowed');

class html extends html_Core {

    public static function admin_image($src = null, $alt = null, $index = false) {
        $image_src = self::admin_webroot_file($src, 'img');
        return parent::image($image_src, $alt, $index);
    }

    public static function admin_stylesheet($style, $media = false, $index = false) {
        $style = self::admin_webroot_file($style, 'css');
        return parent::link($style, 'stylesheet', 'text/css', false, $media, $index);
    }

    public static function admin_script($script, $index = false) {
        $script = self::admin_webroot_file($script, 'js');
        return parent::script($script, $index);
    }

	/**
	 * Create HTML link anchors.
	 *
	 * @param   string  URL or URI string
	 * @param   string  link text
	 * @param   array   HTML anchor attributes
	 * @param   string  non-default protocol, eg: https
	 * @return  string
	 */
    public static function admin_anchor($uri, $title = null, $attributes = null, $protocol = null) {
        $uri = trim($uri, '/');
        $uri = '/' . Kohana::config('njiandan.admin_uri') . '/' . $uri;
        return parent::anchor($uri, $title, $attributes, $protocol);
    }

    // theme html
    public static function theme_image($src = null, $alt = null, $index = false) {
        $image_src = WEBROOT . '/themes/' . $src;
        $image_src = trim($image_src, '/');
        return parent::image($image_src, $alt, $index);
    }

    // get the webroot file
    public static function admin_webroot_file($file, $folder = '') {
        $extra = file::extra($file);
        $basename = file::basename($file);
        $file = kohana::find_file("webroot/$folder", $basename, false, $extra);
        $find = str_replace('\\', '/', DOCROOT);
        return $webfile = str_replace($find, '', $file);
    }
}
