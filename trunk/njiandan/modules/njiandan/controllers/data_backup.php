<?php defined('SYSPATH') OR die('No direct access allowed.');

class Data_Backup_Controller extends Controller {

    public function index($status = '') {

        if ($_POST) {
            $action = $this->input->post('action');
            $type = 'none';
            Data::backup($action, $type);
            Tip::set('Backup done.');
            url::admin_redirect('data_backup/index/done');
        }

        $view = new View('layouts/admin');
        $view->page_title = 'Data Backup';
        $view->render(TRUE);
    }
}
