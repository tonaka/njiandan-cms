<?php defined('SYSPATH') OR die('No direct access allowed.');

class DiagramTag_Core {
    public static $id_diagram;
    public static $uri_diagram;
    public static $diagram_item;
    public static $current_uri;
    public static $the_diagrams;
    public static $parent_list;
    public static $root_list;
    public static $diagram;

    // 如果有一个item和一个page或者list的uri相同,则正面item页面默认到了list(page)页面,则默认diagram变为了不是item的diagram, 如果需要获取item使用diagram_item方法
    // 需要完成判断类型,获取对应类型的内容,不同类型进行相应处理
    public static function __initialize($args = array()) {
        if (is_string($args)) {
            $args = array('uri'=>$args);
        }
	    $default = array('uri'=>'', 'id'=>'');
        $args = $args + $default;
        $diagram = '';
        if (!empty($args['id'])) {
            if (!isset(self::$id_diagram[$args['id']]) || !is_object(self::$id_diagram[$args['id']])) {
                $diagram = self::$id_diagram[$args['id']] = ORM::factory('diagram')->find($args['id']);
            }
        } else if (!empty($args['uri'])) {
            $uri = $args['uri'];
            if (!isset(self::$uri_diagram[$uri]) || !is_object(self::$uri_diagram[$uri])) {
                self::$uri_diagram[$uri] = ORM::factory('diagram')->where(array('uri'=>$uri))->find();
            }
            $diagram = self::$uri_diagram[$uri];
            // if this a post uri
        } else if (PostTag::post()->id) {
            if (!isset(self::$uri_diagram[PostTag::post()->diagram->uri]) || !is_object(self::$uri_diagram[PostTag::post()->diagram->uri])) {
                self::$uri_diagram[PostTag::post()->diagram->uri] = PostTag::post()->diagram;
            }
            $diagram = self::$uri_diagram[PostTag::post()->diagram->uri];
        } else {
            // if empty uri and empty id and not a post uri, get current diagram uri
            $uri = Router::template_uri();
            $diagrams = ORM::factory('diagram')->where(array('uri'=>$uri))->find_all();
            $count = count($diagrams);
            // if item has the same uri with list or page
            if (!isset(self::$uri_diagram[$uri]) || !is_object(self::$uri_diagram[$uri])) {
                if ($count == 2) {
                    self::$uri_diagram[$uri] = ($diagrams[0]->type == 'item') ? $diagrams[1] : $diagrams[0];
                    self::$diagram_item[$uri] = ($diagrams[0]->type == 'item') ? $diagrams[0] : $diagrams[1];
                } else {
                    self::$uri_diagram[$uri] = isset($diagrams[0]) ? $diagrams[0] : new Diagram_model();
                }
            }
            $diagram = self::$uri_diagram[$uri];
        }

        // cache the current diagram parent
        self::$parent_list = Diagram::get_parentid_list_as_array($diagram->id);
        self::$diagram = $diagram;
        return $diagram;
    }

    public static function diagram($args = array()) {
        return self::__initialize($args);
    }

    // check current uri is a valid diagram
    public static function is_diagram() {
        if (self::id()) {
            return true;
        } else {
            return false;
        }
    }

    public static function is_page() {
        if (self::type() == 'page') {
            return true;
        } else {
            return false;
        }
    }

    // get current diagram_item
    // if current item uri equal to list/page uri current item change to diagram_item
    public static function diagram_item($args = array()) {
        self::__initialize();
        return self::$diagram_item;
    }

    public static function id($args = array()) {
        return self::diagram($args)->id;
    }

    public static function uri($args = array()) {
        return self::diagram($args)->uri;
    }

    public static function type($args = array()) {
        return self::diagram($args)->type;
    }

    // get current diagram title
    public static function title($args = array()) {
        $default = array('uri'=>'', 'id'=>'', 'before'=>'', 'after'=>'');
        $args += $default;
        return $args['before'] . self::diagram($args)->title . $args['after'];
    }

    // get current diagram content
    public static function content($args = array()) {
        return self::diagram($args)->content;
    }

    public static function template($args = array()) {
        return self::diagram($args)->template;
    }

    public static function order($args = array()) {
        return self::diagram($args)->order;
    }

    public static function metavalue($args = array()) {
        $default = array('uri'=>'', 'id'=>'');
        $args += $default;
        return unserialize(self::diagram($args)->metavalue);
    }

	 public static function position_as_ul($args = array()) {
		$default = array('uri'=>'', 'id'=> '', 'space'=> ' / ', 'home'=>false);
        $args += $default;
        // if is home page and not allow show in home page 
        if (!$args['home'] && self::diagram($args)->uri == '/') {
            $output = '';
        } else {
            $output = '<ul id="breadcrumb" class="breadcrumb">';
            $list = '';

            foreach(self::$parent_list as $diagram_id) {
                $diagram = Diagram::get_diagram_by_id($diagram_id);
                $list = '<li>'.html::anchor($diagram->uri, $diagram->title) . '<span class="divider">' .$args['space'].'</span></li>' .  $list;
            }

            if (self::diagram($args)->uri != '/') {
                $list = '<li>'.html::anchor('/', T::_('Home')) . '<span class="divider">' .$args['space'].'</span></li>' . $list;
            }

            if (PostTag::post()->id) {
                $list .= '<li>'.html::anchor(PostTag::post()->link, PostTag::title()).'</li>';
            }

            $list = trim($list);
            $output .= $list;
            $output .= '</ul>';
        }

        return $output;
	 }
    // navigator
    public static function position_as_span($args = array()) {
        $default = array('uri'=>'', 'id'=> '', 'space'=> ' &gt; ', 'home'=>false);
        $args += $default;
        // if is home page and not allow show in home page 
        if (!$args['home'] && self::diagram($args)->uri == '/') {
            $output = '';
        } else {
            $output = '<span id="position" class="position">';
            $list = '';

            foreach(self::$parent_list as $diagram_id) {
                $diagram = Diagram::get_diagram_by_id($diagram_id);
                $list = html::anchor($diagram->uri, $diagram->title) . $args['space'] .  $list;
            }

            if (self::diagram($args)->uri != '/') {
                $list = html::anchor('/', T::_('Home')) . $args['space'] . $list;
            }

            if (PostTag::post()->id) {
                $list .= html::anchor(PostTag::post()->link, PostTag::title());
            }

            $list = trim($list, $args['space']);
            $output .= $list;
            $output .= '</span>';
        }

        return $output;
    }

    // visit history
    public static function trace() {
        return array();
    }

    // get diagram mainmenu as array
    public static function mainmenu() {
        if (!isset(self::$root_list)) {
            self::$root_list = ORM::factory('diagram')->where('parent_id', 0)->find_all();
        }
        return self::$root_list;
    }

    // get diagram mainmenu list as ul
    public static function mainmenu_as_ul($args = array()) {
        $default = array('ul_class'=>'', 'ul_id'=>'', 'li_class'=>'', 'li_selected_class'=>'selected', 'a_class'=>'', 'a_selected_class'=>'');
        $init = $args + $default;

        self::__initialize();
        $root_diagrams = self::mainmenu();
        $ul_id = !empty($init['ul_id']) ? ' id="' . $init['ul_id'] . '"' : '';
        $ul_id = !empty($init['ul_class']) ? $ul_id . ' class="' . $init['ul_class'] . '"' : $ul_id;
        $output = "<ul{$ul_id}>";
        foreach($root_diagrams as $diagram) {
            $a_class = $init['a_class'];
            $li_class = $init['li_class'];
            # for the current diagram
            if (in_array($diagram->id, self::$parent_list)) {
                $a_class = $init['a_selected_class'];
                $li_class = $init['li_selected_class'];
            }
            $output .= !empty($li_class) ? '<li id="mainmenu_' . $diagram->id . '" class="' . $li_class . '">' : '<li id="mainmenu_' . $diagram->id . '">';
            $a_array = !empty($a_class) ? array('class'=>$a_class) : array();

            $output .= html::anchor($diagram->uri, $diagram->title, $a_array) . '</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    // 获取当前页的根目录
    public static function root($args=array()) {
        self::__initialize($args);
        return Diagram::get_root(self::$diagram->id);
    }

    // 获取当前页的子菜单
    public static function submenu($args = array()) {
        self::__initialize($args);
		if (!isset($args) || !isset($args['uri'])) {
			$root_diagram = self::root($args);
			return ORM::factory('diagram')->where('parent_id', $root_diagram->id)->find_all();
		} else {
			return ORM::factory('diagram')->where('parent_id', self::$diagram->id)->find_all();
		}
    }

    /*
        用户获取页面的子菜单
        可以设置从哪一个层开始,也可以设置是否显示该开始层级下所有的,
    */
    // get diagram root child list as sub menu
    public static function submenu_as_ul($args = array()) {
        self::__initialize($args);
        $default = array('ul_class'=>'', 'page_class'=>'page', 'list_class'=>'list', 'item_class'=>'item', 'item_selected_class'=>'', 'li_class'=>'',  'li_selected_class'=>'selected', 'current_uri'=>'', 'uri'=>'', 'a_class'=>'', 'a_selected_class'=>'', 'start_level'=>0, 'only_click_in_show_child'=>false);
        $args = $args + $default;
        // get curent diagram
        $args['current_uri'] = self::diagram($args)->uri;

        // get the current diagram parent
        $parent_diagram_id = Diagram::get_parent_diagram_by_level(self::diagram($args)->id, $args['start_level']);
        if ($args['only_click_in_show_child']) {
            return self::submenu_as_url_with_click_in_show_child($parent_diagram_id, $args);
        } else {
            return Diagram::submenu_list($parent_diagram_id, $args);
        }
    }


    /*
        显示子菜单的时候,如果子菜单中还有子菜单,但是显示的时候只显示第一级别的菜单,点了有子菜单的菜单,才会显示该菜单的子菜单
    */
    public static function submenu_as_url_with_click_in_show_child($diagram_id, $args) {
        $root_diagram = ORM::factory('diagram')->where('id', $diagram_id)->find();
        $ul_class = '';
        if (!empty($args['ul_class'])) {
            $ul_class = ' class="' . $args['ul_class'] . '"';
        }
        $output = "<ul{$ul_class}>";
        foreach($root_diagram->children as $diagram) {
            $li_class = '';
            $a_class = array();
            if ($args['current_uri'] == $diagram->uri) {
                if (!empty($args['li_selected_class'])) {
                    $li_class = ' class="' . $args['li_selected_class'] . '"';
                }
                if (!empty($args['a_selected_class'])) {
                    $a_class['class'] =$args['a_selected_class'];
                }
            } else if (!empty($args['li_class'])  or !empty($args['a_class'])) {
                if (!empty($args['li_class'])) {
                    $li_class = ' class="' . $args['li_class'] . '"';
                }
                if (!empty($args['a_class'])) {
                    $a_class['class'] =$args['a_class'];
                }
            }
            $a = html::anchor($diagram->uri, $diagram->title, $a_class);

            $output .= "<li$li_class>$a</li>\n";

            $children = Diagram::get_diagram_all_level_children($diagram->id);

            if ($diagram->id == DiagramTag::id() or in_array(DiagramTag::id(), $children)) {
                $output .= self::submenu_as_url_with_click_in_show_child($diagram->id, $args);
            }
        }
        $output .= '</ul>';
        return $output;
    }

    public static function all_as_ul($args = array()) {
        $default = array('type'=>'all', 'selected'=>'', 'home'=>false);
        $args += $default;
        $args['selected'] = !empty($args['selected']) ? $args['selected'] : Router::$current_uri;
        $output = Diagram::all_as_ul($args);
        return $output;
    }

    // a lazy way to get current page content
    public static function lazy_content($args = array()) {
        $output = '';
        // if is a post uri
        if (PostTag::is_post()) {
            $output = PostTag::as_div($args);
        } else if (DiagramTag::is_diagram($args)) {
            switch(DiagramTag::type($args)) {
                case 'page':
                    $output = PageTag::as_div($args);
                break;
                case 'list':
                    $output = PostsTag::as_div($args);
            }
        }
        return $output;
    }

}
