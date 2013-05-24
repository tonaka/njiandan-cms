<?php defined('SYSPATH') OR die('No direct access allowed.');

class Update_Cache_Controller extends Controller {

    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = 'Update Cache';
        $view->render(true);
    }

}
