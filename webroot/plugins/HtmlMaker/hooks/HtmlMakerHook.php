<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 
 */
class HtmlMakerHook {

    public static function admin_menu() {
        Event::$data['生成html'] = array(
            array('title'=>'生成html', 'uri'=>'create_html', 'roles'=>array('View'=>'view_create_html', 'Action'=>'update_create_html')),
            array('title'=>'删除html', 'uri'=>'delete_html', 'roles'=>array('View'=>'view_delete_html', 'Action'=>'update_delete_html')),
        );
    }
}

Event::add('njiandan.admin_menu', array('HtmlMakerHook', 'admin_menu'));
