<?php defined('SYSPATH') OR die('No direct access allowed.');

class Comment_Model extends ORM {
    protected $sorting = array('id' => 'asc');
    protected $belongs_to = array('post');
    protected $has_one = array('user', 'post', 'diagram');

    public function __get($key) {
        $value = '';
        switch($key) {
            case 'username':
                $value = parent::__get($key);
                if (empty($value) and !empty($this->user->id)) {
                    $value = $this->user->username;
                }
            break;
            case 'email':
                $value = parent::__get($key);
                if (empty($value) and !empty($this->user->id)) {
                    $value = $this->user->email;
                }
            break;
            case 'content':
                $value = parent::__get($key);
                if (Auth::instance()->logged_in()) {
                    $status = parent::__get('is_approved');
                    if ($status) {
                        $value .= '<p>' . html::admin_anchor("/comments/cancel_approve/$this->id?redirect_uri=" . Router::$complete_uri, T::_('Approved'), array('style'=>'color:#ff0000;')) . '</p>';
                    } else {
                        $value .= '<p>' . html::admin_anchor("/comments/approve/$this->id?redirect_uri=" . Router::$complete_uri, T::_('Approve'), array('style'=>'color:#ff0000;')) . '</p>';
                    }
                }
            break;
            case 'id':
            case 'parent_id':
            case 'parent':
            case 'post_id':
            case 'post':
            case 'diagram_id':
            case 'diagram':
            case 'url':
            case 'ip':
            case 'date':
            case 'is_approved':
            case 'agent':
            case 'user_id':
            case 'user':
                $value = parent::__get($key);
            break;
        }
        return $value;
    }
}
