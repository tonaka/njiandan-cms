<?php defined('SYSPATH') OR die('No direct access allowed.');
 
// mark in the template
define('IN_TEMPLATE', TRUE);
class Njiandan_Template_Controller extends Controller {

	public function index($args = '') {
	    // clear tips
	    Tip::clear();
        $current_template = $this->_get_template();
        $view = new ThemeView($current_template);
        $view->render(TRUE);
	}

    // get the current uri's template
    protected function _get_template() {
	    $cache = Cache::instance();
	    // template uris hook
	    $theme_uris = array();
	    Event::run('njiandan.theme_uris', $theme_uris);

	    $uris = $cache->get('theme_uris');
	    if (!empty($theme_uris)) {
	        $uris = !empty($uris) ? $uris : array();
	        $uris += $theme_uris;
	    }

	    $current_uri = Router::template_uri();

        $retain_uri = array('feed', 'feed/rss2', 'feed/atom');
	    // check if is a retain uri
	    if (in_array($current_uri, $retain_uri)) {
	        if ($current_uri == 'feed' or $current_uri == 'feed/rss2') {
                header('Content-Type: application/atom+xml; charset=utf-8', TRUE);
	            echo FeedTag::rss2();
	        } else if ($current_uri == 'feed/atom') {
	            header('Content-Type: text/xml; charset=utf-8', TRUE);
	            echo FeedTag::atom();
	        }
	        exit();
	    }

	    // if is a post uri
	    $post_uri = trim(URI::segment(2));			
	    if (strtolower(URI::segment(1)) == Kohana::config('njiandan.post_uri') && !empty($post_uri)) {			
	        $uri = URI::segment(2);
	        $template = DiagramTag::diagram()->post_template;
	        // record this post views
	        $post = PostTag::post();
	        $post->view += 1;
	        $post->save();
	    } else {			
	        if (!empty($uris[$current_uri])) {
	            $template = $uris[$current_uri];
	        } else if (isset($uris[$current_uri])) {
	            throw new Kohana_404_Exception(T::_('This diagram template is not exists'));
	        } else {
	            $template = '404';
	        }
	    }
	    Event::run('njiandan.template_pre', $template);
	    if (!Template::is_template_exists($template)) {
	        $template = '404';
	    }
        return $template;
    }
}
