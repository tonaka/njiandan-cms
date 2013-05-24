<?php defined('SYSPATH') OR die('No direct access allowed.');

class Customvalue_Model extends ORM {
    protected $has_one = array('post', 'customfield', 'diagram');

    public function __get($key) {
        switch($key) {
            case 'attachment':
                $value = parent::__get('value');
                return ORM::factory('attachment')->where('filename', $value)->find();
            break;
            default:
                $value = parent::__get($key);
            break;
        }
        return $value;
    }
}
