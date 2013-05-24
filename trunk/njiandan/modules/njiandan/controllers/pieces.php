<?php defined('SYSPATH') OR die('No direct access allowed.');

class Pieces_Controller extends Controller {

    public function index() {
        $pieces = ORM::factory('post')->where(array('diagram_id'=>0, 'status'=>0))->find_all();
        $view = new View('layouts/admin');
        $view->page_title = 'Profile';
        $view->pieces = $pieces;
        $view->render(True);
    }

    public function add($id = 0) {
        $id = (int)$id;
        // check capability
        if (empty($id) and !$this->user->can('add_piece')) {
            die(T::_('You are not access allowed.'));
        } else if (!empty($id) and !$this->user->can('edit_piece')) {
            die(T::_('You are not access allowed.'));
        }
        $piece = ORM::factory('post')->where(array('id'=>$id, 'diagram_id'=>0, 'status'=>0))->find();
        $title_error = '';
        $tag_error = '';

        if ($_POST) {
            $piece->title = trim($this->input->post('title'));
            if (empty($piece->title)) {
                $title_error = T::_('Title can not be empty.');
            }
            $piece->content = trim($this->input->post('content'));
            $piece->uri = trim($this->input->post('tag'));
            if (!valid::tag($piece->uri)) {
                $tag_error = T::_('Invalid tag.');
            }

            $piece->status = 0;
            $piece->date = time();
            $piece->user_id = $this->user->id;
            if (empty($title_error) and empty($tag_error)) {
                $piece->diagram_id = 0;
                $piece->excerpt = '';
                $piece->password = '';
                $piece->to_ping = '';
                $piece->save();
                $redirect_uri = $this->input->get('redirect_uri');
                if (empty($redirect_uri)) {
                    //redirect to add done
                    Tip::set(T::_('Add done.'));
                    url::admin_redirect('pieces/add');
                } else {
                    // redirect to the form uri
                    Tip::set(T::_('Edit done.'));
                    url::redirect($redirect_uri);
                }
            }
        }
        $view = new View('layouts/admin');
        $view->page_title = 'Add A Piece';
        $view->piece = $piece;
        $view->title_error = $title_error;
        $view->tag_error = $tag_error;
        if (empty($piece->id)) {
            $view->submit_title = 'Add';
        } else {
            $view->submit_title = 'Edit';
        }
        $view->render(True);
    }

    public function delete($id) {
        if (!$this->user->can('delete_piece')) {
            die(T::_('You are not access allowed.'));
        }
        $id = (int)$id;
        $piece = ORM::factory('post')->where(array('id'=>$id, 'diagram_id'=>0, 'status'=>0))->find();
        if (!empty($piece->id)) {
            $piece->delete();
            $message = 'Delete done.';
        } else {
            $message = 'Delete failed.';
        }
        Tip::set($message);
        url::admin_redirect('pieces');
    }
}
