<?php defined('SYSPATH') OR die('No direct access allowed.');

class Diagram_Model extends ORM_Tree {
    protected $ORM_Tree_children = 'diagrams';
    protected $has_one = array('user');
    protected $has_many = array('customvalues', 'posts', 'customfields', 'comments');
    protected $sorting = array('order' => 'asc');
    public static $metavalues;

    public function __get($key) {
        $customvalues = parent::__get('customvalues');
        foreach($customvalues as $customvalue) {
            if ($key == $customvalue->key or $key == $customvalue->key . '_original') {
                if ($customvalue->customfield->type == 'multiple') {
                    return unserialize($customvalue->value);
                } else if ($customvalue->customfield->type == 'upload') {
                    if (empty($customvalue->value)) {
                        return null;
                    } else if (defined('IN_TEMPLATE') and $key != $customvalue->key . '_original') {
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
                // filter the right upload file path
                $value = Njiandan::upload_file_filter($value);

                if (defined('IN_TEMPLATE')) {
                    Event::run('njiandan.page.content_output_pre', $value);
                    $value = str_replace(']]>', ']]&gt;', $value);
                    //show admin edit in the web
                    //if (parent::__get('id') && Auth::instance()->logged_in()) {
                        //$value .= '<p>' . html::admin_anchor("/diagram/page_edit/$this->id/edit?redirect_uri=" . Router::$complete_uri, T::_('Edit'), array('style'=>'color:#ff0000;')) . '</p>';
                    //}
                }
            break;
            case 'post_template':
                $value = '';
                $metavalue = parent::__get('metavalue');
                if (!empty($metavalue)) {
                    $metavalue = unserialize($metavalue);
                    if (isset($metavalue['post_template'])) {
                        $value = $metavalue['post_template'];
                    }
                }
            break;
            case 'id':
            case 'user_id':
            case 'user':
            case 'type':
            case 'title':
            case 'content':
            case 'parent_id':
            case 'parent':
            case 'uri':
            case 'template':
            case 'metavalue':
            case 'order':
            case 'customvalues':
            case 'date':
            case 'comments':
            case 'posts':
            case 'customfields':
            case 'children':
                $value = parent::__get($key);
            break;
        }
        return $value;
    }

    // check if the diagram uri is unique
    // just one item can have the same uri with page or list
    public function is_unique_uri($uri, $type) {
        $is_unique = true;
        $reserve_uris = Kohana::config('njiandan.reserve_uris');
        if (in_array($uri, $reserve_uris)) {
            return false;
        }

        if (!empty($this->id)) {
            // get the diagram if has the same uri with current diagram
            $diagrams = $this->db->from($this->table_name)->where(array('uri'=>$uri, 'id !='=>$this->id))->get();
        } else {
            $diagrams = $this->db->from($this->table_name)->where(array('uri'=>$uri))->get();
        }
        // if is index
        if ($uri == '/' and count($diagrams) >= 1) {
            return false;
        }

        // if already have one more diagrams
        if (count($diagrams) > 1) {
            $is_unique = false;
        // if there is no diagram use this uri
        } else if (count($diagrams) == 0) {
            $is_unique = true;
        } else {

            foreach($diagrams as $diagram) {
                // if the this item use the uri, and exits diagram is not item
                if ($type == 'item' && $diagram->type != 'item') {
                    $is_unique = true;
                // if the this diagram is not item, and exits diagram is item
                } else if ($type != 'item' && $diagram->type == 'item') {
                    $is_unique = true;
                } else {
                    $is_unique = false;
                }
                break;
            }
        }
        return $is_unique;
    }

    // get diagram metavalue by key
    public function metavalue($key) {
        if (!is_array(self::$metavalues)) {
            $metavalues = unserialize($this->metavalue);
        }
        if (isset($metavalues[$key])) {
            return $metavalues[$key];
        }
    }
}
