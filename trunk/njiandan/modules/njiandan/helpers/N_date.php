<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Date helper class.
 *
 */
class date extends date_Core {

	/**
	 * Converts a UNIX timestamp to a smart time.
	 *
	 * @param   integer  UNIX timestamp
	 * @return  string
	 */
    public static function smart_time($time) {
        $output = '';
        $time_lag = time() - $time;
        // just now
        if ($time_lag <= 5) {
            $output = T::_('Just now');
        } else if ($time_lag > 5 and $time_lag < 60) {
            $output = sprintf(T::_('%s seconds ago'), $time_lag);
        } else if ($time_lag >= 60 and $time_lag < 3600) {
            $time_lag = (int)($time_lag / 60);
            $output = sprintf(T::_('%s minutes ago'), $time_lag);
        } else if ($time_lag >= 3600 and $time_lag < 86400) {
            $time_lag = (int)($time_lag / 3600);
            $output = sprintf(T::_('%s hours ago'), $time_lag);
        } else {
            $output = date('Y-m-d', $time);
        }
        return $output;
    }

    public static function date_formats() {
        $formats = array();
        $formats['normal_date'] = array('date', 'normal_date', date('Y-m-d'));
        $formats['date_and_time'] = array('date', 'date_and_time', date('Y-m-d H:i'));
        $formats['day_month_year'] = array('date', 'day_month_year', date('j M y'));
        $formats['smart_time'] = array('date', 'smart_time', T::_('Smart time'));
        Event::run('njiandan.default_time_format', $formats);
        return $formats;
    }

    public static function default_format($time) {
        $format = Kohana::config('njiandan.default_date_format');
        $formats = self::date_formats();

        if (isset($formats[$format])) {
            $format_class = $formats[$format][0];
            $format_method = $formats[$format][1];

            if (class_exists($format_class)) {
                $object = new $format_class;
                if (method_exists($object, $format_method)) {
                    return call_user_func(array($format_class, $format_method), $time);
                }
            }
        }
        //if haven't have this format, return a normal date
        return self::normal_date($time);
    }

    public static function normal_date($time) {
        return date('Y-m-d', $time);
    }

    public static function date_and_time($time) {
        return date('Y-m-d H:i', $time);
    }

    public static function day_month_year($time) {
        return date('j M y', $time);
    }
}
