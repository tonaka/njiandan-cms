<?php defined('SYSPATH') OR die('No direct access allowed.');

class Tip_Core {
    public static $tip = '';

    public static function set($tip) {
        self::$tip = $tip;
        cookie::set('njiandan_tip', $tip);
    }

    public static function get() {
        $tip = cookie::get('njiandan_tip');
        if (empty($tip)) {
            $tip = self::$tip;
        }
        cookie::set('njiandan_tip', '');
        return $tip;
    }

    public static function output() {
        $tip = self::get();
        if (empty($tip)) {
            $tip_status = ' style="display:none;" ';
        } else {
            $tip_status = '';
        }
        $output = '<div id="noticemessage" align="center"' . $tip_status . '>
    <table class="notice_table" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td class="notice_coner"></td>
                <td class="notice_table"></td>
                <td class="notice_coner"></td>
            </tr>
            <tr>
                <td class="notice_table"></td>
                <td class="notice_message_content" id="noticemessage_content">' . T::_($tip) . '</td>
                <td class="notice_table"></td>
            </tr>
            <tr>
                <td class="notice_coner"></td>
                <td class="notice_table"></td>
                <td class="notice_coner"></td>
            </tr>
        </tbody>
    </table>
</div>';
    return $output;
    }

    public static function clear() {
        cookie::set('njiandan_tip', '');
        return true;
    }
}
