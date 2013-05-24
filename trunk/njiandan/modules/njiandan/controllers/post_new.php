<?php defined('SYSPATH') OR die('No direct access allowed.');

class Post_New_Controller extends Controller {

    public function __call($id, $arguments) {
        Router::$method = 'index';
        $status = isset($arguments[0]) ? $arguments[0] : '';
        $this->index($id, $status);
    }

    public function index($id = NULL, $status = '') {
        $id = (int)$id;

        switch($status) {
            case 'add_done':
                $pre_post = new Post_Model($id);
                $diagram_id = $pre_post->diagram_id;
                unset($pre_post);
                $id = NULL;
                break;
            case 'edit':
                $post = new Post_Model($id);
                $diagram_id = $post->diagram_id;
                break;
            case 'add_from_diagram':
                $diagram_id = $id;
                $id = NULL;
                break;
            default:
                $diagram_id = NULL;
                break;
        }

        if (!isset($post)) {
            $post = new Post_Model($id); // if id is not empty, means is edit action, show the post content;
        }

        if ($_POST) {

            if (!$this->user->can('post_new')) {
                die(T::_('You are not access allowed.'));
            }

            $save = TRUE;
            $title = trim($this->input->post('title'));
            $content = trim($this->input->post('content'));

            $diagram_id = (int)$this->input->post('diagram_id');
            // check diagram type

            $diagram = new Diagram_Model($diagram_id);


            if ($diagram->type != 'list') {
                $save = FALSE;
                Tip::set(T::_('The Category choose wrong.'));
            }

            Event::run('njiandan.post.title_save_pre', $title); // run a hook before title save
            Event::run('njiandan.post.content_save_pre', $content); // run a hook before content save
            Event::run('njiandan.post.uri_save_pre', $uri); // run a hook before uri save

            // if empty uri use the title
            $uri = URI::sanitize($this->input->post('uri'));
            $uri = !empty($uri) ? $uri : URI::sanitize($title);

            $post->title = $title;
            $post->content = $content;
            $post->uri = $uri;
            // if add new
            if (empty($post->id)) {
                $post->user_id = $this->user->id;
                $post->excerpt = '';
                $post->password = '';
                $post->to_ping = '';
            }

            $post->diagram_id = $diagram_id;
            $date = $this->input->post('date');
            $post->date = empty($date) ? date('Y-m-d H:i:s') : $date; // if empty date, use the current time

            // if title and content is not empty, save it.
            if ($save) {
                $post->save();

                // add search index
                Google_Model::save_index(array('post_id'=>$post->id, 'title'=>$post->title, 'content'=>$post->content, 'type'=>'post', 'date'=>$post->date));

                // if still empty post uri, use the id for it's uri
                if (empty($post->uri)) {
                    $post->uri = $post->id;
                    $post->save();
                }

                // save thnumbnail
                $thumb = FALSE;
                $delete_thumbnail = trim($this->input->post('delete_thumbnail'));
                if ($delete_thumbnail and !empty($post->id)) {
                    $thumbnail = ORM::factory('attachment')->where(array('post_id'=>$post->id, 'is_thumb'=>1))->find();
                    if (!empty($thumbnail->filename)) {
                        if (upload::delete($thumbnail->filename)) {
                            $thumbnail->delete();
                        }
                    }
                }
                if (!empty($_FILES['thumbnail'])) {
                    $files = Validation::factory($_FILES)->add_rules('thumbnail', 'upload::valid', 'upload::required', 'upload::type[gif,jpg,png]', 'upload::size[2M]');
                    if ($files->validate()) {
                        if ($filename = upload::save('thumbnail')) {
                            $attach = new Attachment_Model();
                            $attach->post_id = $post->id;
                            $attach->diagram_id = 0;
                            $attach->title = $_FILES['thumbnail']['name'];
                            $attach->filename = $filename;
                            $attach->date = time();
                            $attach->is_thumb = 1;
                            $attach->downloads = 0;
                            // get the thumbnail size
                            $image_path = DOCROOT . $filename;
                            $attach->size = filesize($image_path);
                            $attach->mime = file::mime($image_path);
                            $attach->save();
                            $thumb = TRUE;
                        }
                    }
                }

                $post->is_thumb = ($post->thumb or $thumb) ? 1 : 0;


	            $post->save();

                // save custom values
                CustomField::save($this->input->post(), $post->diagram_id, $post->id);
                Tip::set(T::_('Your post has been successfully saved.') . ' ' . html::admin_anchor("/post_new/$post->id/edit", T::_('View or edit')));

                // save success redirect to the success page or redirect_url
                $redirect_uri = $this->input->get('redirect_uri'); // get the redirect_url if it was exsits
                if (empty($redirect_uri)) { // if the redirect_url not set, redirect to the add success url
                    Tip::set(T::_('Your post has been successfully published.') . ' ' . html::admin_anchor("/post_new/$post->id/edit", T::_('View or edit')));
                    url::admin_redirect("post_new/$post->id/add_done/");
                } else {
                    url::redirect($redirect_uri);
                }
            }
        }

        // load the template view
        $view = new View('layouts/admin');
        $view->select_options = Diagram::get_diagram_select(array('selected'=>$diagram_id, 'none'=>T::_('Please select the category')));
        $view->page_title = 'Post New';
        $view->title = $post->title;
        $view->thumb = $post->thumb;
        $view->post_id = $post->id;
        $view->content = $post->content;
        $view->uri = $post->uri;
        $view->date = !empty($post->date) ? date('Y-m-d H:i:s', $post->date) : date('Y-m-d H:i:s');
        $view->customfields = !empty($diagram_id) ? CustomField::form($diagram_id, $post->id) : '';
        $view->category_list = Diagram::category_as_javascript_array();
        $view->render(TRUE);
    }

    public function custom_fields($diagram_id, $post_id) {
        echo CustomField::form($diagram_id, $post_id);
    }

}
