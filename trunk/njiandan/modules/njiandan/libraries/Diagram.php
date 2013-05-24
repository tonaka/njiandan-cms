<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Diagram class.
 */

class Diagram_Core {
    public static $diagrams = array();
    public static $parent_diagrams = array();
    public static $instance;
    public static $post_diagrams;
    public static $level_diagrams = array();
    protected static $tmp_diagrams = array();
    protected static $all_diagrams_all_level_children = array();

	/**
	 * Cache current diagram list by parent_id.
	 *
	 */
    public static function cache_diagram() {
        if (empty(self::$diagrams)) {
            $diagrams = ORM::factory('diagram')->find_all();
            $count = count($diagrams);
            if (empty($count)) {
                return array();
            }

            for ($i = 0; $i < $count; $i++) {
                if(empty($diagrams[$i])) {
                    break;
                }
                self::$parent_diagrams[$diagrams[$i]->parent_id][] = $diagrams[$i];
                self::$diagrams[$diagrams[$i]->id] = $diagrams[$i];
            }
        }
    }

	/**
	 * get the diagram list as ul and li.
	 *
	 * @param  string  for admin manage
	 */
    public static function draw_diagram($action = '') {
        self::cache_diagram();
        return self::get_diagram_list(0, 0, $action);
    }

    public static function get_diagram_list($parent_id = 0, $level = 0, $action = 'manage') {
        $list = '';
        if (empty(self::$parent_diagrams[$parent_id])) {
            return $list;
        }
        if ($parent_id != 0) {
            $list = "\n<ul>\n";
        } else {
            $list = '<ul id="browser" class="filetree">';
        }
        $authentic = new Auth;
        $user = $authentic->get_user();
        foreach (self::$parent_diagrams[$parent_id] as $diagram) {
            $list .= '<li><span class="' . $diagram->type . '" id="diagram_' . $diagram->id . '">' . $diagram->title;
            $list .= '<span class="diagram_action">';
            if ($action == 'manage' && $user->can('manage_diagram')) {
                $list .= '<span>' . html::admin_anchor('/diagram_manage/move/up/' . $diagram->id, T::_('Move up')) . '</span>';
                $list .= '<span>' . html::admin_anchor('/diagram_manage/move/down/' . $diagram->id, T::_('Move down')) . '</span>';
                $list .= '<span>' . html::admin_anchor('/diagram_manage/diagram_new/' . $diagram->id . '/add_a_child', T::_('Add child')) . '</span>';
                $list .= '<span>' . html::admin_anchor('/diagram_manage/delete/' . $diagram->id, T::_('Delete'), array('onclick'=>"return confirm('" . T::_('Are you sure you want to delete it?') . "')")) . '</span>';
                $list .= '<span>' . html::admin_anchor('/diagram_manage/diagram_new/' . $diagram->id . '/edit/', T::_('Edit'))  . '</span>';
            } else {
                $other_action = '';
                $title = '';
                $url = '';
                switch($diagram->type) {
                    case 'page':
                        if ($user->can('edit_page')) {
                            $title = T::_('Edit');
                            $url = "/diagram/page_edit/$diagram->id/edit/?redirect_uri=" . Router::$complete_uri;
                        }
                        break;
                    case 'list':
                        $title = T::_('Post New');
                        $url = "/post_new/$diagram->id/add_from_diagram";
                        $other_action = ' <span>' . html::admin_anchor('/posts/' . $diagram->id, T::_('Manage posts')) . '</span>';
                        break;
                    case 'item':
                    case 'url':
                    default:
                        $title = '';
                        $url = '';
                }
                $list .= ' <span>' . html::admin_anchor($url, $title) . '</span>' . $other_action;
            }
            $list .= '</span>';
            $list .='</span>';
            $list .= self::get_diagram_list($diagram->id, $level + 1, $action);
            $list .= '</li>' . "\n";
        }
        $list .= '</ul>' . "\n";
        return $list;
    }

	/**
	 * get the diagram list as a select.
	 *
	 * @param  Array  Array('selected'=> Integer, 'current_diagram_id'=> Integer, 'none'=> String, 'space' => String, 'uses' => String)
	 * selected current select selected option value
	 * current_diagram_id current diagram id, make you can't select your children for your parent when you edit the diagram
	 * none the first none title, value is 0
	 * space default is ' — '
	 * uses use for add diagram or any other else
	 */
    public static function get_diagram_select($params = array()) {
        $default_params = array('selected' => '', 'none' => T::_('None'), 'current_diagram_id'=>null, 'uses'=> 'post', 'space' => ' — ');
        $output = '';
        $params += $default_params;

        #$output .= '<select name="parent_id" id="diagram_parent"  style="width:100%;">';
        if (!empty($params['none'])) {
            $output .= '<option value="0">' . $params['none'] . '</option>' . "\n";
        }
        // cache the diagram
        self::cache_diagram();
        if ($params['uses'] == 'post') {
            self::cache_diagram_for_post();
            self::$tmp_diagrams = self::$post_diagrams;
        } else {
            self::$tmp_diagrams = self::$parent_diagrams;
        }
        $output .= self::get_diagram_select_option_list($params);

        #$output .= '</select>';
        return $output;
    }

	/**
	 * get the diagram list as a select.
	 *
	 * @param  Array  the same as self::get_diagram_select
	 * @param integer  diagram parent id
	 * @param integer  level
	 */
    public static function get_diagram_select_option_list($params = array(), $parent_id = 0, $level = 0) {
        $output = '';
        if (empty(self::$tmp_diagrams[$parent_id])) {
            return $output;
        }

        foreach(self::$tmp_diagrams[$parent_id] as $diagram) {
            $is_selected = '';
            $disabled = '';
            // if trip the current diagram and children
            if ($diagram->id == $params['current_diagram_id']) {
                continue;
            }

            if (($diagram->type == 'page' || $diagram->type == 'item') && $params['uses'] == 'post') {
                $disabled = ' disabled="disabled" ';
            }

            if ($diagram->id == $params['selected']) {
                    $is_selected = 'selected';
            }
            $output .= '<option ' . $disabled . ' value="' . $diagram->id . '" ' . $is_selected . ' >' . str_repeat($params['space'],$level) . $diagram->title . '</option>' . "\n";
            $output .= self::get_diagram_select_option_list($params, $diagram->id, $level + 1);
        }
        return $output;
    }

    public static function all_as_ul($params = array()) {
        $default = array('type'=>'all', 'selected'=>'', 'home'=>false);
        $params += $default;
        self::cache_diagram();
        self::cache_diagram_for_post();
        self::$tmp_diagrams = self::$post_diagrams;
        $output = self::get_list_as_ul($params);
        return $output;
    }

	/**
	 * get the category list as a ul.
	 *
	 * @param  Array  
	 * @param integer  diagram parent id
	 */
    protected static function get_list_as_ul($args = array(), $parent_id = 0) {
        $output = '';
        if (empty(self::$parent_diagrams[$parent_id])) {
            return $output;
        }
        $output .= '<ul>';
        foreach(self::$parent_diagrams[$parent_id] as $diagram) {
            if ($diagram->uri == '/' and !$args['home']) {
                continue;
            }

            if (empty($parent_id) && $args['type'] != 'all') {
                if ($diagram->type != $args['type']) {
                    continue;
                }
            }
            $selected = '';
            if ($args['selected'] == $diagram->uri) {
                $selected = ' class="selected"';
            }
            $output .= "<li{$selected}>" . html::anchor($diagram->uri, $diagram->title);
            $output .= self::get_list_as_ul($args, $diagram->id);
            $output .= '</li>' . "\n";
        }
        $output .= '</ul>';
        return $output;
    }

    /*
        缓存diagram for post 的列表
    */
    protected static function cache_diagram_for_post() {
        $parent_diagrams = ORM::factory('diagram')->where('type', 'list')->find_all();
        foreach($parent_diagrams as $diagram) {
            self::cache_diagrams_by_parent_id($diagram);
        }
    }

    /*
        缓存使用父类id为key的Diagram 列表
    */
    protected static function cache_diagrams_by_parent_id($diagram) {

        self::$post_diagrams[$diagram->parent_id][$diagram->id] = $diagram;
        if (!empty($diagram->parent_id) && isset(self::$diagrams[$diagram->parent_id]->id)) {
            self::cache_diagrams_by_parent_id(self::$diagrams[$diagram->parent_id]);
        }
    }

    // get the parent list as array id
    public static function get_parentid_list_as_array($diagram_id) {
        self::cache_diagram();
        if (!empty($diagram_id)) {
            $parent_list[] = $diagram_id;
            $parent_id = isset(self::$diagrams[$diagram_id]->parent_id) ? self::$diagrams[$diagram_id]->parent_id : '';
        } else {
            $parent_list = array();
            $parent_id = '';
        }

        if (!empty($parent_id)) {
            $parent_list  = array_merge($parent_list, self::get_parentid_list_as_array($parent_id));
        }
        return $parent_list;
    }

    // get the current root child by root uri
    public static function get_root($id) {
        self::cache_diagram();
		
        if (!isset(self::$diagrams[$id])) {
            return new Diagram_Model();
        }
        if (self::$diagrams[$id]->parent_id != 0) {
            return self::get_root(self::$diagrams[$id]->parent_id);
        } else {
            return self::$diagrams[$id];
        }
    }

    // get submenu list as url
    public static function submenu_list($diagram_id, $init) {
        $output = '';
        if (isset(self::$parent_diagrams[$diagram_id])) {
            $count = count(self::$parent_diagrams[$diagram_id]);
            $li = false;
            $ul = false;
            $ul_class = '';
            if (!empty($init['ul_class'])) {
                $ul_class = ' class="' . $init['ul_class'] . '"';
            }
            for($i = 0; $i < $count; $i++) {
                $diagram = self::$parent_diagrams[$diagram_id][$i];
                if ($i == 0) {
                    $output .= "<ul$ul_class>\n";
                    $ul = true;
                }

                $li_class = '';
                $a_class = array();
                if ($init['current_uri'] == $diagram->uri) {
                    if (!empty($init['li_selected_class'])) {
                        $li_class = ' class="' . $init['li_selected_class'] . '"';
                    }
                    if (!empty($init['a_selected_class'])) {
                        $a_class['class'] =$init['a_selected_class'];
                    }
                } else if (!empty($init['li_class'])  or !empty($init['a_class'])) {
                    if (!empty($init['li_class'])) {
                        $li_class = ' class="' . $init['li_class'] . '"';
                    }
                    if (!empty($init['a_class'])) {
                        $a_class['class'] =$init['a_class'];
                    }
                }
                /*
                if ($diagram->type == 'item') {
                    $a = $diagram->title;
                    $li_class = ' class="' . $init['item_class'] . '"';
                } else {
                */
                    $a = html::anchor($diagram->uri, $diagram->title, $a_class);
                //}
                $output .= "<li$li_class>$a\n";
                $output .=  self::submenu_list($diagram->id, $init);

                $output .= '</li>';
                if ($li) {
                    $output .=  '</li>';
                } else if ($ul && $i == ($count -1)) {
                    $output .= '</ul>';
                }
            }
        }
        return $output;
    }

    /*
        change diagram display order
    */
    public static function move($action, $id) {
        $diagram = ORM::factory('diagram')->find($id);
        $diagram_order = $diagram->order;

        switch($action) {
            case 'up':
                $next_diagram = ORM::factory('diagram')->where(array('parent_id'=>$diagram->parent_id, 'order<'=>$diagram->order))
                                ->orderby(array('order'=>'DESC'))->find();
                break;

            default:
            case 'down':
                $next_diagram = ORM::factory('diagram')->where(array('parent_id'=>$diagram->parent_id, 'order>'=>$diagram->order))
                                ->orderby(array('order'=>'ASC'))->find();
                break;
        }

        if (!empty($next_diagram) && !empty($next_diagram->order)) {
            $next_order = $next_diagram->order;
            $next_diagram->order = '';
            $next_diagram->save();

            $diagram->order = $next_order;
            $diagram->save();

            $next_diagram->order = $diagram_order;
            $next_diagram->save();
            return True;
        }
        return false;
    }


    /*
        cache all the diagram info
    */
    public static function cache_diagrams() {
        self::cache_diagram();

        $cache = cache::Instance();

        $all_diagrams_all_level_children = self::get_all_diagrams_all_level_children();
        $cache->set('all_diagrams_all_level_children', $all_diagrams_all_level_children, null, 0);

        $theme_uris = self::cache_uris();
        $cache->set('theme_uris', $theme_uris, null, 0);

        $level_diagrams = self::get_diagram_level_list();
        $cache->set('level_diagrams', $level_diagrams, null, 0);

        $post_templates = self::cache_post_templates();
        $cache->set('post_templates', $post_templates, null, 0);
    }

    /*
       根据指定的diagram_id获取该diagram指定层级的parent
    */
    public static function get_parent_diagram_by_level($diagram_id, $level = 0) {

        $diagrams = self::get_diagrams_by_level($level);

        /*
            如果$diagram_id就是,该层的root,则返回该id
        */
        if (in_array($diagram_id, $diagrams)) {
            return $diagram_id;
        }

        $parent_diagram = 0;

        /*
            
        
        */
        foreach($diagrams as $id) {
            $diagram_all_level_children = self::get_diagram_all_level_children($id);

            if (in_array($diagram_id, $diagram_all_level_children)) {
                $parent_diagram = $id;
                break;
            }
        }
        return $parent_diagram;
    }


    public static function get_diagrams_by_level($level = 0) {

        $cache = cache::Instance();
        $level_diagrams = $cache->get('level_diagrams');

        if (isset($level_diagrams[$level])) {
            return $level_diagrams[$level];
        } else {
            return array();
        }
    }

    /*
        根据diagram_id 缓存 diagram 的 children
    */
    public static function get_diagram_all_level_children($diagram_id) {
        $cache = cache::Instance();
        $all_level_children =  $cache->get('all_diagrams_all_level_children');

        if (isset($all_level_children[$diagram_id])) {
            return $all_level_children[$diagram_id];
        } else {
            return array();
        }
    }

    /*
        获取一个Diagram children的数组列表
    */
    public static function get_diagrams_children() {
    
    }

    /*
        返回每个diagram的所有下级的id列表, aray()
    */
    public static function get_all_diagrams_all_level_children() {
        $all_level_children = array();
        $all_level_children[0] = array();

        foreach(self::$diagrams as $diagram) {
            self::cache_diagram_all_level_children($diagram->id);
            $all_level_children[$diagram->id] = self::$all_diagrams_all_level_children;
            $all_level_children[0][] = $diagram->id;
            self::$all_diagrams_all_level_children = array();
        }
        return $all_level_children;
    }

    protected static function cache_diagram_all_level_children($diagram_id) {
        if (isset(self::$diagrams[$diagram_id])) {
            foreach(self::$diagrams[$diagram_id]->children as $diagram) {
                self::$all_diagrams_all_level_children[] = $diagram->id;
                self::cache_diagram_all_level_children($diagram->id);
            }
        }
    }

    /*
        获取每个diagram 的每一个层级的parent id
    */
    public static function get_diagram_level_list() {
        self::cache_diagram_id_by_level();
        return self::$level_diagrams;
    }


    protected static function cache_diagram_id_by_level($diagram_id = 0, $level = 0) {
        if (!empty(self::$parent_diagrams[$diagram_id])) {
            foreach(self::$parent_diagrams[$diagram_id] as $diagram) {
                self::$level_diagrams[$level][] = $diagram->id;
                self::cache_diagram_id_by_level($diagram->id, $level + 1);
            }
        }
    }


    // update when diagram was change, use for njiandan_template controller
    public static function cache_uris() {
        $uris = array();
        foreach(self::$diagrams as $diagram) {
            $uris[$diagram->uri] = $diagram->template;
        }
        return $uris;
    }

    public static function cache_post_templates() {
        $templates = array();
        foreach(self::$diagrams as $diagram) {
            if ($diagram->type == 'list' and !empty($diagram->metavalue)) {
                $metavalue = unserialize($diagram->metavalue);
                if (!empty($metavalue['post_template'])) {
                    $templates[$diagram->uri] = $metavalue['post_template'];
                }
            }
        }
        return $templates;
    }

    public static function get_diagram_by_id($diagram_id) {
        self::cache_diagram();
        if (isset(self::$diagrams[$diagram_id])) {
            return self::$diagrams[$diagram_id];
        }
    }

    public static function category_as_javascript_array() {
        $categories = array();
        $db = new Database;
        $diagrams = $db->from('diagrams')->select(array('id', 'type'))->where('type', 'list')->get();
        $output = '{';
        foreach($diagrams as $diagram) {
            $output .= $diagram->id . ':' . $diagram->id . ',';
        }
        $output = trim($output, ',');
        $output .= '}';
        return $output;
    }
}
