<?php defined('SYSPATH') OR die('No direct access allowed.');

class Options_Admin_Uri_Controller extends Controller {

    public function index() {
        $uri_error = '';
        $admin_uri = Kohana::config('njiandan.admin_uri');
        if ($_POST) {
            // check is use have role
            if (!$this->user->can('edit_options_admin_uri')) {
                die(T::_('You are not access allowed.'));
            }
            $admin_uri = trim($this->input->post('admin_uri'));
            if (empty($admin_uri)) {
                $uri_error = T::_('Admin uri can not be empty.');
            } else if (!valid::uri($admin_uri)) {
                $uri_error = T::_('Admin uri is invalid.');
            } else {
                Njiandan::config_save('njiandan.admin_uri', $admin_uri);
                $url = $admin_uri . '/options_admin_uri';
                $url = trim($url, '/');
                url::redirect($url);
            }
        }

        $view = new View('layouts/admin');
        $view->page_title = 'Admin uri';
        $view->admin_uri = $admin_uri;
        $view->uri_error = $uri_error;
        $view->render(true);
    }
}
