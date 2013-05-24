<?php defined('SYSPATH') OR die('No direct access allowed.');

class Users_Controller extends Controller {

    public function index() {
        $users = ORM::factory('user');
        $view = new View('layouts/admin');
        // get users with pagination
        $config = Kohana::config('pagination.default');
        $config['total_items'] = $users->count_all();
        $paging = new Pagination($config);
        $view->pagelink = $paging->render('gmail');
        $view->users = $users->limit($paging->items_per_page, $paging->sql_offset)->orderby(array('id'=>'desc'))->find_all();
        $view->page_title = 'Users';
        $view->render(true);
    }

    public function delete($user_id) {
        if (!$this->user->can('delete_user')) {
            die(T::_('You are not access allowed.'));
        }
        $user_id = (int)$user_id;
        $user =  ORM::factory('user')->where('id', $user_id)->find();
        $username = $user->username;
        $user->delete();
        $redirect_uri = $this->input->get('redirect_uri');
        if (empty($redirect_uri)) {
            $redirect_uri = url::admin_site('users');
        }
        Tip::set(sprintf(T::_('Delete user %s done.'), $username));
        url::redirect($redirect_uri);
    }
}
