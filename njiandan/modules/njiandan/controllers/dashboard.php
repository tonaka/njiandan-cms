<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Dashboard_Controller extends Controller {
    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = 'Dashboard';
        $view->render(TRUE);
    }
}
