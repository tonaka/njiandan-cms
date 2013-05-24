<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Disk_Space_Controller extends Controller {
    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = 'Disk Space';
        $view->render(TRUE);
    }
}
