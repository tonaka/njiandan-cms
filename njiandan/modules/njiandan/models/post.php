<?php defined('SYSPATH') OR die('No direct access allowed.');

class Post_Model extends ORM {
    protected $has_one = array('user', 'diagram');
    protected $has_many = array('attachments', 'customvalues', 'comments');
    protected $sorting = array('date' => 'desc');

    public function __construct($id = NULL) {
           // load database library into $this->db (can be omitted if not required)
           parent::__construct($id);
    }

    public function __set($key, $value) {
        if ($key == 'date') {
            $value = strtotime($value);
        }
        if ($key == 'uri') {
            $value = $this->unique_uri($value);
        }
        parent::__set($key, $value);
    }

    // do the post template pre
    public function __get($key) {

        $customvalues = parent::__get('customvalues');
        foreach($customvalues as $customvalue) {
            if ($key == $customvalue->key) {
                if ($customvalue->customfield->type == 'multiple') {
                    return unserialize($customvalue->value);
                } else if ($customvalue->customfield->type == 'upload') {
                    if (defined('IN_TEMPLATE')) {
                        return url::base() . $customvalue->value;
                    } else {
                        return $customvalue->value;
                    }
                } else {
                    return $customvalue->value;
                }
            }
        }
        $value = '';
        switch($key) {
            case 'content':
                $value = parent::__get($key);
                // get the right upload file path
                $value = Njiandan::upload_file_filter($value);
                // if in template use
                if (defined('IN_TEMPLATE')) {
                    Event::run('njiandan.post.content_output_pre', $value);
                    $value = str_replace(']]>', ']]&gt;', $value);
                    //show admin edit in the web
                    if (parent::__get('id') && Auth::instance()->logged_in()) {
                        if (parent::__get('status') and parent::__get('diagram_id')) {
                            $value .= '<p>' . html::admin_anchor("/post_new/$this->id/edit?redirect_uri=" . Router::$complete_uri, T::_('Edit'), array('style'=>'color:#ff0000;')) . '</p>';
                        }

                    }
                }
            break;
            case 'thumb':
            case 'thumb_original':
                $value = '';
                $attachments = parent::__get('attachments');
                foreach($attachments as $attach) {
                    if ($attach->is_thumb) {
                        $value = $attach->filename;
                    }
                }

                if (defined('IN_TEMPLATE') and $key != 'thumb_original' and !empty($value)) {
                    $value = url::file($value);
                }
            break;
            case 'link':
                $value = parent::__get('uri');
                $key = Kohana::config('njiandan.uri_optimize');
                if ($key == 'id') {
                    $value = parent::__get('id');
                }
                $value = Kohana::config('njiandan.post_uri') . '/' . $value;

            break;
            case 'id':
            case 'user':
            case 'user_id':
            case 'diagram':
            case 'diagram_id':
            case 'title':
            case 'password':
            case 'allow_ping':
            case 'to_ping':
            case 'allow_comment':
            case 'date':
            case 'uri':
            case 'is_star':
            case 'view':
            case 'status':
            case 'attachments':
            case 'customvalues':
            case 'comment_id':
            case 'comments':
                $value = parent::__get($key);
            break;
        }
        return $value;
    }

    public function unique_uri($uri, $count = 1) {
        // for the second recurssion
        if ($count != 1) {
            $uri_2 = sprintf($uri, $count);
        } else {
            $uri_2 = $uri;
        }

        // check if add new or edit
        if (!empty($this->id)) {
            $post_count = $this->db->where(array('uri' => $uri_2, 'id !=' => $this->id))->count_records($this->table_name); // edit
        } else {
            $post_count = $this->db->where(array('uri' => $uri_2))->count_records($this->table_name); // add new
        }
        // if no post record use this uri, return it
        if (empty($post_count)) {
            return $uri_2;
        } else {
            // if it was been use, add_$count on it
            if ($count == 1) {
                $uri = "{$uri}-%s";
            }
            $count++;
            return $this->unique_uri($uri, $count);
        }
    }

}
