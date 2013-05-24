<?php defined('SYSPATH') OR die('No direct access allowed.');

class Diagram_Controller extends Controller {

    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = T::_('Diagram');
        $view->diagram_list = Diagram::draw_diagram();
        $view->render(TRUE);
    }

    public function page_edit($page_id) {
        $diagram = new Diagram_Model($page_id);

        if ($_POST) {
            $diagram->title = $this->input->post('title');
            $diagram->content = $this->input->post('content');
            $diagram->save();
            CustomField::save($this->input->post(), $diagram->id);
            $redirect_uri = $this->input->get('redirect_uri');
            if (empty($redirect_uri)) {
                $redirect_uri = url::admin_site('/diagram');
            }
            Tip::set('Edit done.');
            url::redirect($redirect_uri);
        }
        $view = new View('layouts/admin');
        $view->page_title = T::_('Page Edit');
        $view->title = $diagram->title;
        $view->content = $diagram->content;
        $view->customfields = CustomField::form($diagram->id, 0, $diagram->id);
        $view->render(TRUE);
    }
}
