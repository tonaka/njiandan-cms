<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Posts Template tags that can go anywhere in a template..
 * @packpage	Arlicle
 * @author 		Edison Rao
 * @copyright 	(c) 2009 Arlicle Team
 * @license 	GNU General Public License 2.0 
 */
class PostsTag_Core {
    public static $diagram;
    public static $diagrams;
    public static $post_list; // current page posts list
    public static $star_post_list; //  start_post_list
    public static $count_all; // all posts count
    public static $pagelink;
    public static $pagelinks;
    public static $current_uri;
    public static $uri;
    public static $thumb_post_list;
    public static $the_post_list;

	/**
	 * Prepares the uri and get the post list , diagram info
	 * loads customvalues.
	 * loads attachments
	 *
	 * @return  void
	 */
	public static function __initialize($args=array()) {
	    $default = array('uri'=>'','per_page'=>0, 'pagination'=>'gmail', 'order'=>array('date'=>'desc'), 'children'=>false, 'limit'=>0, 'star'=>false, 'thumb'=>false);
        $args = $args + $default;

        self::$current_uri = Router::template_uri();
	    // get current visit uri
	    self::$uri = empty($args['uri']) ? self::$current_uri : $args['uri'];
	    // 如果args['uri']为空,设置为当前uri
	    $args['uri'] = self::$uri;
	    if (self::$uri == 'arlicle_template') {
	        self::$uri = '/';
	    }

        if (empty(self::$uri)) {
            return null;
        }

        if (!empty($args['uri']) and is_string($args['uri'])) {
            $args['uri'] = (array)$args['uri'];
	    } else if (!empty($args['uri']) and is_array($args['uri'])) {
	        self::$uri = implode(',', $args['uri']);
	    }

        // 如果已经进行过该结果的查询,则可以直接获取而不用再去查询一次
        if (!empty(self::$pagelinks[self::$uri]) and !empty(self::$post_list[self::$uri])) {
            // return null;
        }

        $all_diagram = array();
        $conditions = array('status' => 1);

        if (self::$uri != '/') {
            // 获取所有uri等于当前uri的diagram, 可能是一个栏目和一个列表,或者一个栏目和一个页面
            $diagrams = array();
            // 如果当前的uri是多个uri组成的数组

            if (!empty($args['uri']) and is_array($args['uri'])) {
                // 去除值相同的uri
                $args['uri'] = array_unique($args['uri']);
                foreach($args['uri'] as $uri) {
                    $diagram = ORM::factory('diagram')->where(array('uri'=>$uri, 'type!='=>'item'))->find();
                    $diagram_item = ORM::factory('diagram')->where(array('uri'=>$uri, 'type='=>'item'))->find();
                    //判断当前非item记录是否在内
                    if (!empty($diagram->id) and !in_array($diagram->id, $diagrams)) {
                        $diagrams[] = $diagram->id;
                    }
                    // 判断item是否在内
                    if (!empty($diagram_item->id) and !in_array($diagram_item->id, $diagrams)) {
                        $diagrams[] = $diagram_item->id;
                    }
                }
            }

            // if set children true, get current diagram posts and children's posts
            $all_children = array();

            if (!empty($args['children'])) {

                // get the diagram id and it's children
                foreach($diagrams as $diagram_id) {
                    $all_children = array_merge($all_children, Diagram::get_diagram_all_level_children($diagram_id));
                }
            }
            $all_diagram = array_unique(array_merge($diagrams, $all_children));
        }
        //如果设置了获取推荐加星
        if ($args['star'] == true) {
            $conditions['is_star'] = 1;
        }

        // 如果设置了只要带缩略图的
        if ($args['thumb'] == true) {
            $conditions['is_thumb'] = 1;
        }

        // 如果设置了limit
        if (!empty($args['limit'])) {
            $posts = ORM::factory('post')->where($conditions);
            if (!empty($all_diagram)) {
                $posts = $posts->in('diagram_id', $all_diagram);
            }
            $posts = $posts->limit($args['limit']);
            self::$post_list[self::$uri] = $posts->orderby($args['order'])->find_all();
        } else {

            // 获取总的记录数用在分页
            $count_query = ORM::factory('post')->where($conditions);
            if (!empty($all_diagram)) {
                $count_query = $count_query->in('diagram_id', $all_diagram);
            }
            $count = $count_query->count_all();

            // 获取结果
            $posts = ORM::factory('post')->where($conditions);
            if (!empty($all_diagram)) {
                $posts = $posts->in('diagram_id', $all_diagram);
            }

            // get the list config,
            $pagination_config_file = Kohana::find_file('themes/' . Kohana::config('core.theme') . '/application/config', 'pagination');
            if ($pagination_config_file) {
                require $pagination_config_file;
                $config = $config['default'];
            } else {
                $config = Kohana::config('pagination.default');
            }

            $config['total_items'] = $count;
            // if user set the perpage custom
            if (!empty($args['per_page'])) {
                $config['items_per_page'] = $args['per_page'];
            }

            $paging = new Pagination($config);
            self::$pagelinks[self::$uri] = self::$pagelink = $paging->render($args['pagination']);

            // get all posts
            self::$post_list[self::$uri] = $posts->limit($paging->items_per_page, $paging->sql_offset)->orderby($args['order'])->find_all();
        }
	}

    public static function __initialize_uri($args=array()) {
        self::$current_uri = Router::template_uri();
	    // get current visit uri
	    self::$uri = empty($args['uri']) ? self::$current_uri : $args['uri'];
	    if (self::$uri == 'arlicle_template') {
	        self::$uri = '/';
	    }
    }

    public static function post_list($args = array()) {
        self::__initialize($args);
        return self::$post_list[self::$uri];
    }

    public static function post_list_as_ul($args = array()) {
        $default_args = array('ul_class'=>'', 'li_class'=>'', 'a_class'=>'', 'date'=>true, 'date_format' => '', 'author'=>false, 'id'=>false);
        $args += $default_args;
        $a_class = !empty($args['a_class']) ? array('class'=>$args['a_class']) : array();
        $posts = self::post_list($args);
        $output = '<ul';
        $output .= !empty($args['ul_class']) ? ' class="' . $args['ul_class'] . '">' : '>';
        foreach($posts as $post) {
            $output .= '<li';
            $output .= !empty($args['li_class']) ? ' class="' . $args['li_class'] . '">' : '>';

            if (!empty($args['id'])) {
                $output .= '<span class="id">' . $post->id . '</span>';
            }

            // if use date in the list
            if (!empty($args['date'])) {
                $output .= '<span class="date">';
                if (!empty($args['date_format'])) {
                    $output .= date($args['date_format'], $post->date);
                } else {
                    $output .= date::default_format($post->date);
                }
                $output .= '</span>';
            }

            if (!empty($args['author'])) {
                $output .= '<span class="author">' . $post->user->username . '</span>';
            }

            $output .= html::anchor($post->link, $post->title, $a_class);
            $output .= '</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    public static function pagelink($args = array()) {
        self::__initialize($args);
        return self::$pagelinks[self::$uri];
    }

    // 获取首页部分文章列表
    // 获取整站推荐列表
    // 获取整站最新带图片文章
    // 获取某个分类推荐文章
    // 获取某个分类
    // archive by year, by month, by week
    
}
