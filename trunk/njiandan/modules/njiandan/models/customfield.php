<?php defined('SYSPATH') OR die('No direct access allowed.');

class Customfield_Model extends ORM {
    protected $sorting = array('order' => 'asc');
    protected $has_one = array('diagram');

}
