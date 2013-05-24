<?php defined('SYSPATH') OR die('No direct access allowed.');

class Delete_Html_Controller extends Controller {

    public function index() {
        if ($_POST) {
            @unlink(DOCROOT . 'index.html');
            file::clean_dir(APPPATH . 'html');
            Tip::set('html删除完成.');
            url::admin_redirect('delete_html');
        }
        $view = new View('layouts/admin');
        $view->page_title = '删除HTML';
        $view->render(TRUE);
    }
}
