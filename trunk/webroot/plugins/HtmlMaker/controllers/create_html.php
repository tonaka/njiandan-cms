<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
    需要修复的地址, 非网站架构图网址和正则表达式网址的生成.
*/
class Create_Html_Controller extends Controller {
    protected $base = '';
    protected $html_dir = '';
    protected $diagram_uris = array();
    protected $pagelinks = array();
    protected static $docroot = '';

    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = '生成HTML';
        $view->render(TRUE);
    }

    public function create_diagram($page = 1) {
        $this->_init();
        $page = (int)$page;
        $page = $page <= 0 ? 1 : $page;
        // 每次生成3个页面
        $per = 3;

        // fist step
        if (empty($this->diagram_uris) or $page == 1) {
            // ge theme uris
	        $uris = array();
	        Event::run('njiandan.theme_uris', $uris);
	        $theme_uris = $this->cache->get('theme_uris');

	        if (!empty($uris)) {
	            $theme_uris = !empty($theme_uris) ? $theme_uris : array();
	            $theme_uris += $uris;
	        }

            if (empty($theme_uris)) {
                $theme_uris = array();
            }

            $this->diagram_uris = array();
	        // 把正则表达式类的暂时去掉
	        foreach($theme_uris as $uri => $template) {
	            if ($uri == '/' or (strpos($uri, '/') !== 0 and !empty($template))) {
	                $this->diagram_uris[$uri] = 0;
	            }
	        }

            $this->cache->set('plugin_html_maker_diagram_uris', $this->diagram_uris, '', 0);
        }

        //开始生成 diagram 的html
        $current_uris = array_slice($this->diagram_uris, ($page - 1) * $per, $per);

        foreach($current_uris as $uri => $value) {
            $content = $this->_get_page_content($uri);
            // 转化页面中的uri
            $new_content = $this->_parse_content($content);
            $this->_save_html($uri, $new_content);
        }

        $data = array();
        $data['count'] = $page * $per;
        if (empty($current_uris)) {
            $data['status'] = 'done';
            echo json_encode($data);
        } else {
            $data['status'] = 'continue';
            echo json_encode($data);
        }
        
    }

    /*
        生成内容页,如单个新闻
    */
    public function create_post($page = 1) {
        $this->_init();
        $page = (int)$page;
        $page = $page <= 0 ? 1 : $page;
        // 每次生成5个
        $per = 5;
        $posts = ORM::factory('post')->limit($per, ($page-1) * $per)->find_all();
        foreach($posts as $post) {
            $content = $this->_get_page_content($post->link);
            $new_content = $this->_parse_content($content);
            $this->_save_html($post->link, $new_content);
        }
        $data['count'] = $page * $per;
        if (count($posts)) {
            $data['status'] = 'continue';
            echo json_encode($data);
        } else {
            $data['status'] = 'done';
            echo json_encode($data);
        }
    }

    /*
        生成列表页 list
        count:diagram中的第几个列表
        page: 某一个列表的第几页
    */
    public function create_list($count = 1, $page = 1) {
        $this->_init();
        $count = (int)$count;
        $count = $count <= 0 ? 1: $count;
        $page = (int)$page;
        $page = $page <= 0 ? 1: $page;
        // 每次生成1页列表
        $per_page = 1;

        $diagrams = ORM::factory('diagram')->where('type', 'list')->limit(1, $count -1)->find_all();
        $last_content = '';
        $continue = false;

        foreach($diagrams as $diagram) {
            if ($page == 1) {
                $content = $this->_get_page_content($diagram->uri);
                $this->_parse_pagelinks($content, true);
            }

            $counter = 1;
            foreach($this->pagelinks as $id => $status) {
                if (empty($status) and $id > 1) {
                    $content = $this->_get_page_content($diagram->uri . '?page=' . $id);
                    $this->_parse_pagelinks($content);
                    $new_content = $this->_parse_content($content);
                    $this->_save_html($diagram->uri . '_page-' . $id, $new_content);
                    $this->_set_pagelinks_status($id, 1);
                    $continue = true;
                    break;
                }
                $counter++;
            }
        }

        $data = array();
        $data['count'] = $count;
        $data['page'] = $page * $per_page;
        if ($continue) {
            $data['status'] = 'continue';
        } else {
            $data['status'] = 'done';
            if (!count($diagrams)) {
                $data['status'] = 'all_done';
            }
        }
        echo json_encode($data);
    }

    /*
        初始化变量
    */
    protected function _init() {
        $this->base = trim(url::base(), '/');
        $this->html_dir = '/' . WEBROOT . '/html';
        $this->cache = Cache::instance();
        $this->diagram_uris = $this->cache->get('plugin_html_maker_diagram_uris');
        $this->pagelinks = $this->cache->get('plugin_html_maker_pagelinks');
        // 修复windows下路径问题
        $this->docroot = str_replace('\\', '/', DOCROOT);
    }

    /*
        往all uris中添加uri, 使用于其它url
    */
    protected function _add_uri($uri) {
        if (is_array($uri)) {
            $this->diagram_uris += $uri;
            $this->cache->set('plugin_html_maker_all_uris', $this->diagram_uris, '', 0);
        } else {
            if (!isset($this->diagram_uris[$uri])) {
                $this->diagram_uris[$uri] = 0;
                $this->cache->set('plugin_html_maker_all_uris', $this->diagram_uris, '', 0);
            }
        }
    }

    /*
        分析页面并保存分页链接
    */
    protected function _parse_pagelinks($content, $first = false) {
        if ($first) {
            $this->pagelinks = array();
        }
        preg_match_all('#<a[^>]*?href=\"/[^"?]*?\?page=(\d+)".*?</a>#is', $content, $pagelinks);
        foreach($pagelinks[1] as $uri) {
            if ($uri > 1 and !isset($this->pagelinks[$uri])) {
                $this->pagelinks[$uri] = 0;
            }
        }
        $this->cache->set('plugin_html_maker_pagelinks', $this->pagelinks, '', 0);
    }


    /*
        设置uri已经被生成状态
    */
    protected function _set_pagelinks_status($uri, $value = 1) {
        if (isset($this->pagelinks[$uri])) {
            $this->pagelinks[$uri] = $value;
            $this->cache->set('plugin_html_maker_pagelinks', $this->pagelinks, '', 0);
        }
    }


    /*
        获取指定url页面的内容
    */
    protected function _get_page_content($uri) {
        $uri = str_replace(array('%2F', '+', '%3F', '%3D'), array('/', '%20', '?', '='), urlencode($uri));
        $url = url::base(FALSE, 'http') . 'index.php/' . $uri;

        $url = trim($url, '/');
        $content = @file_get_contents($url);
        return $content;
    }

    /*
        生成html页面
    */
    protected function _save_html($uri, $content) {
        if ($uri == '/') {
            file_put_contents($this->docroot . 'index.html', $content);
        } else {
            $file = $this->docroot . WEBROOT . '/html/' . $uri . '.html';
            $directory = dirname($file);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, True);
            }
            file_put_contents($file, $content);
        }
    }

    protected function _parse_content($content) {

        //将分页第一页替换为列表首页网址
        $content = preg_replace('#(<a[^>]*?href=")(/[^"]*?)(\?page=1)(".*?</a>)#is', '$1$2$4', $content);
        // 将分页中的?和&改为_,=改为-
        $replace_function = 'return $matches[1] . str_replace(array("?", "&", "="), array("_", "_", "-"), $matches[2]) . $matches[3];';
        $content = preg_replace_callback('#(<a[^>]*?href=")(/[^"]*)(".*?</a>)#is', create_function('$matches', $replace_function), $content);

        // 将剩下的 网址都加上.html后缀, 如果有#,继续保留在.html后面
        $base = url::base();
        $base = ($base == '/') ? '/' : rtrim($base, '/');

		$content = preg_replace_callback('#(<a[^>]*?href="' . rtrim($base, '/') . ')(/index.php)?(/[^"]*?)(\#[^"]*?)?(")#is', create_function('$matches', 'return Create_Html_Controller::filter_content($matches);'), $content);

        // 将首页网址转换为index.html
        $content = preg_replace('#(<a[^>]*?href=")(.*?)(' . $this->html_dir . '/\.html)(".*</a>)#is', '${1}${2}/index.html${4}', $content);
        return $content;
    }

	public static function filter_content($matches) {
		$link = $matches[3];
        $base = url::base();
        $base = ($base == '/') ? '/' : rtrim($base, '/');
		$is_file = false;
		if (strpos($link, $base) === 0) {
			$link = substr($link, strlen($base));
			$link = ltrim($link, "/");
			if (strpos($link, "index.php") !== 0) {
				if(is_file(self::$docroot . $link)) {
					return $matches[1] . $matches[2] . $matches[3] . $matches[4] . $matches[5];
				}
			}
		}
		return $matches[1] . '/' . WEBROOT . '/html' . $matches[3] . ".html" . $matches[4] . $matches[5];
	}
}
