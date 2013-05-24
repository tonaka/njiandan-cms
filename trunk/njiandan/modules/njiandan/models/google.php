<?php defined('SYSPATH') OR die('No direct access allowed.');

class Google_Model extends ORM {
    protected $sorting = array('date' => 'desc', 'view'=>'desc');

    public static function save_index($args = array()) {
        $default_args = array('post_id'=>0, 'title'=>'', 'content'=>'', 'type'=>'', 'date'=>'', 'view'=>0);
        $args += $default_args;
        $post = ORM::factory('google')->where(array('post_id'=>$args['post_id'], 'type'=>$args['type']))->find();
        $post->post_id = $args['post_id'];
        $post->title = $args['title'];
        //$post->content = text::strip_html($args['content']);
        $post->content = strip_tags($args['content']);
        $post->type = $args['type'];
        $post->date = $args['date'];
        $post->view = $args['view'];
        $post->save();
    }
}
