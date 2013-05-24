<?php defined('SYSPATH') OR die('No direct access allowed.');

class Options_Image_Controller extends Controller {

    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = 'Options Image';
        $view->render(true);
    }

}
