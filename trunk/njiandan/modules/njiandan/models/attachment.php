<?php defined('SYSPATH') OR die('No direct access allowed.');

class Attachment_Model extends ORM {
    protected $belongs_to = array('post');
    protected $sorting = array('id' => 'desc');

}
