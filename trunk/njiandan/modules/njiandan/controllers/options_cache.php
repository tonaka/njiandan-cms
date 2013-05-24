<?php defined('SYSPATH') OR die('No direct access allowed.');

class Options_Cache_Controller extends Controller {

    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = 'Options Cache';
        $view->render(true);
    }
}
