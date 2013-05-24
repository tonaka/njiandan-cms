<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Options_Comment_Controller extends Controller {
    public function index() {

        if ($_POST) {
            $approve_status = $this->input->post('approve_status');

            if (empty($approve_status)) {
                $approve_status = '';
            }

            Njiandan::config_save('njiandan.is_comment_need_approve', $approve_status);
            Tip::set('Options saved.');
            url::admin_redirect('options_comment');
        }

        $approve_status = Kohana::config('njiandan.is_comment_need_approve');
        $view = new View('layouts/admin');
        $view->page_title = 'Comment Settings';
        $view->approve_status = $approve_status;
        $view->render(TRUE);
    }
}
