<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Validation helper class.
 *
 */
class text extends text_Core {

    /*
        change html to text
    */
    public static function strip_html($text) {
        $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                       '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                       '@<![\\s\\S]*?--[ \\t\\n\\r]*>@',    // Strip multi-line comments including CDATA
                       '@<[\\/\\!]*?[^<>]*?>@si',            // Strip out HTML tags
                       '@&nbsp;@',                          // Strip space
                       '@\s+@',                             // change wrap or break space
        );
        $text = preg_replace($search, ' ', $text);
        $text = strip_tags($text);
        $text = trim($text);
        return $text;
    }
}
