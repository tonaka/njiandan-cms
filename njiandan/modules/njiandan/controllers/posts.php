<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * for manage the posts

 */
class Posts_Controller extends Controller {

    public function __call($diagram_id, $arguments) {
        Router::$method = 'index';
        $this->index($diagram_id);
    }

    public function index($diagram_id = 0) {
        $diagram_id = (int)$diagram_id;
        $view = new View('layouts/admin');
        $view->page_title = 'Posts';
        $view->select_options = Diagram::get_diagram_select(array('selected'=>$diagram_id, 'none'=>T::_('All')));

        // get current page posts
        if (empty($diagram_id)) {
            $posts = ORM::factory('post')->where('status', 1);
        } else {
            $posts = ORM::factory('post')->where(array('diagram_id' => $diagram_id, 'status' => 1));
        }

        $config = Kohana::config('pagination.default');
        $config['total_items'] = $posts->count_all();
        $paging = new Pagination($config);
        $view->pagelink = $paging->render('gmail');
        if (empty($diagram_id)) {
            $view->posts = ORM::factory('post')->where('status', 1)->limit($paging->items_per_page, $paging->sql_offset)->find_all();
        } else {
            $view->posts = ORM::factory('post')->where(array('diagram_id' => $diagram_id, 'status' => 1))->limit($paging->items_per_page, $paging->sql_offset)->find_all();
        }

        $view->all_posts_count = ORM::factory('post')->where('status', 1)->count_all();
        $view->render(True);
    }

    public function delete($ids) {
        if (!$this->user->can('delete_post')) {
            die(T::_('You are not access allowed.'));
        }
        $ids = trim($ids, '.');
        $id_array = explode('.', $ids);
        // should add the role check
        ORM::factory('post')->delete_all($id_array);
        $count = 0;
        foreach($id_array as $post_id) {
            $count += 1;
            $attachments = ORM::factory('attachment')->where('post_id', $post_id)->find_all();
            foreach($attachments as $attach) {
                if (file_exists(DOCROOT . $attach->filename)) {
                    @unlink(DOCROOT . $attach->filename);
                }
            }
            ORM::factory('attachment')->where('post_id', $post_id)->delete_all();
            // delete custom values
            ORM::factory('customvalue')->where('post_id', $post_id)->delete_all();
        }
        Tip::set(sprintf(T::_('%s posts have been deleted.'), $count));
        echo 'delete_done';
    }

    // ajax change the star
    public function change_star($id) {
        if (!$this->user->can('star_post')) {
            die(T::_('You are not access allowed.'));
        }
        $post = new Post_Model($id);
        $star = false;
        if (!empty($post->id)) {
            if (!$post->is_star) {
                $post->is_star = 1;
                $star = true;
            } else {
                $post->is_star = 0;
            }
            $post->save();
        }

        if ($star) {
            echo 'on';
        } else {
            echo 'off';
        }
    }
}
