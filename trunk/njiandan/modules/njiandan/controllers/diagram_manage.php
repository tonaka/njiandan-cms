<?php defined('SYSPATH') OR die('No direct access allowed.');

class Diagram_Manage_Controller extends Controller {

    public function __construct() {
        parent::__construct();
        // check the role
        if (Router::$method != 'index' && !$this->user->can('manage_diagram')) {
            die(T::_('You are not access allowed.'));
        }
    }


    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = 'Diagram Manage';
        $view->diagram_list = Diagram::draw_diagram('manage');
        $view->render(TRUE);
    }

	/**
	 * add a new diagram, add a child diagram by parent id, add diagram by a mate id, edit a diagram, 
	 *
	 * @param  integer
	 * @param  string
	 * a. $id=NULL and $action=NULL, add a new diagram
	 * b. $id!=NULL and $action='add_done', $id is diagram last insert id
	 * c. $id!=NULL and $action='add_a_child', $id is a diagram parent id
	 */
    public function diagram_new($id = NULL, $action = NULL) {
        $parent_id = 0; // current add parent id

        /*
            check the action type
        */
        
        /*
            if add a child, $id is a parent id
        */
        if ($action == 'add_a_child') {
            $parent_id = $id;
            $id = NULL;
        /*
            if add done, $id is previous diagram id, set the previous diagram parent id for current add id
        */
        } else if ($action == 'add_done') {
            $parent_id = ORM::factory('diagram')->where('id', $id)->find()->parent_id;
            $id = NULL;
        }

        $diagram = new Diagram_Model($id);
        $customfields = $diagram->customfields;

        // add new or save edit
        if ($post = $this->input->post()) {

            $type = $this->input->post('type');
            $parent_id = $this->input->post('parent_id');
            $uri = $this->input->post('uri');
            $uri = $uri == '/' ? '/' : trim($uri, '/');

            $diagram->type = $type;
            $diagram->uri = $uri;
            $diagram->parent_id = $parent_id;
            $diagram->title = trim($this->input->post('title'));
            $diagram->template = $this->input->post('template');
            $diagram->content = $this->input->post('content');

            $metavalue = array();
            $metavalue['post_template'] = $this->input->post('post_template');

            if (!empty($metavalue)) {
                $diagram->metavalue = serialize($metavalue);
            } else {
                $diagram->metavalue = '';
            }

            $set_order = false;
            if (empty($diagram->id)) {
                $set_order = true;
                $diagram->date = time();
                $diagram->user_id = $this->user->id;
            }

            $customfields_post = $this->input->post('customfields');
            // get a object list for customfields edit
            $customfields = self::_customfiled_to_object($customfields_post);

            if (!$diagram->is_unique_uri($uri, $type)) {
                Tip::set('Diagram uri is not unique.');
            } else if (!valid::uri($uri)) {
                Tip::set('Invalid diagram uri.');
            } else {
                $diagram->save();
                if ($set_order) {
                    $diagram->order = $diagram->id; // set the diagram display order
                }

                self::_save_customfields($diagram->id, $customfields_post); // save customfiels

                // save diagram
                $diagram->save();

                // update the diagrams cache
                Diagram::cache_diagrams();

                if ($action == 'edit') {
                    $tip = 'Edit done.';
                    $redirect = 'diagram_manage';
                } else {
                    // redirect to the tip add done
                    $tip = 'Add done.';
                    $redirect = "diagram_manage/diagram_new/$diagram->id/add_done/";
                }

                Tip::set(T::_($tip) . ' ' . html::admin_anchor("/diagram_manage/diagram_new/$diagram->id/edit?redirect_uri=diagram_manage/diagram_new", T::_('View or edit')));

                $redirect_uri = $this->input->get('redirect_uri');
                if (!empty($redirect_uri)) {
                    $redirect = $redirect_uri;
                }
                url::admin_redirect($redirect);
            }
        }

        $view = new View('layouts/admin');
        // type checked status
        $view->type_page = '';
        $view->type_list = '';
        $view->type_item = '';
        $view->type_url = '';

        // page content;
        $view->id = $diagram->id;
        $view->{"type_$diagram->type"} = TRUE;
        $view->title = $diagram->title;
        $view->content = $diagram->content;
        $view->uri = $diagram->uri;
        $view->template = $diagram->template;
        $view->post_template = $diagram->post_template;

        // set the diagram type check status
        if ($action == 'edit') {
            $view->buttom = 'Edit';
            $view->page_title = 'Diagram Edit';
            $parent_id = $diagram->parent_id;
        } else {
            $view->buttom = 'Add new';
            $view->page_title = 'Diagram New';
        }

        $view->customfields = !empty($customfields) ? $customfields : array();

        $view->select_options = Diagram::get_diagram_select(array('uses'=>'diagram', 'selected'=>$parent_id, 'current_diagram_id'=>$diagram->id));
        $theme = Kohana::config('core.theme');
        $view->templates = Template::all_as_array($theme, array('page', 'list'));
        $view->post_templates = Template::all_as_array($theme, 'post');
        $view->field_types = CustomField::types();
        $view->render(TRUE);
    }

     /*
     * delete diagram and custom fields by diagram id
     * @param integer diagram id
     */
    public function delete($id) {
        $diagram = new Diagram_Model($id);
        if (!empty($diagram->id)) {
            $diagrams = Diagram::get_diagram_all_level_children($diagram->id);
            $diagrams[] = $diagram->id;

            // customfilds的删除需要改进, 用foeach循环删除不是一个好办法
            foreach($diagrams as $child) {
                // delete custom fields
                ORM::factory('customfield')->where('diagram_id', $child)->delete_all();

                // delete posts
                $posts = ORM::factory('post')->where('diagram_id', $child)->find_all();
                foreach($posts as $post) {
                    // delete attachments
                    foreach($post->attachments as $attach) {
                        if (is_file(DOCROOT . $attach->filename)) {
                            unlink(DOCROOT . $attach->filename);
                        }
                    }
                    ORM::factory('attachment')->where('post_id', $post->id)->delete_all();

                    // delete custom values
                    ORM::factory('customvalue')->where('post_id', $post->id)->delete_all();
                }
                // delete all posts
                ORM::factory('post')->where('diagram_id', $child)->delete_all();
            }
    
            // delete diagrams
            ORM::factory('diagram')->delete_all($diagrams);
            // update the diagram cache
            Diagram::cache_diagrams();
        }

        Tip::set(sprintf(T::_('Diagram %s delete done.'), $diagram->title));
        url::admin_redirect("/diagram_manage/index/delete_done");
    }

    /*
     * move up diagram order
     * @param string up/down
     * @param integer diagram id
      */
    public function move($action, $id) {
        Diagram::move($action, $id);
        url::admin_redirect("/diagram_manage/index/$id/move_done/#diagram_$id");
    }

    protected function _customfiled_to_object($list = array()) {
        $results = array();
        // save list custom fields
        if (!empty($list['fields']['title']) && !empty($list['fields']['key']) && is_array($list['fields']['title']) && is_array($list['fields']['key'])) {
            foreach($list['fields']['title'] as $key => $value) {
                $customfield = new Customfield_Model();
                $customfield->type = $list['fields']['type'][$key];
                $customfield->metavalue = $list['fields']['metavalue'][$key];
                $customfield->title = $value;
                $customfield->key = $list['fields']['key'][$key];
                $customfield->order = empty($list['fields']['order'][$key]) ? 0 : $list['fields']['order'][$key];
                $results[$key] = $customfield;
            }
        }
        return $results;
    }

    protected function _save_customfields($diagram_id, $list = array()) {

        $fields = ORM::factory('customfield')->where('diagram_id', $diagram_id)->find_all();
        // cache the old fields
        $old_fields = array();
        foreach($fields as $field) {
            $old_fields[$field->id] = $field->id;
        }

        // save list custom fields
        if (!empty($list['fields']['title']) && !empty($list['fields']['key']) && is_array($list['fields']['title']) && is_array($list['fields']['key'])) {
            foreach($list['fields']['title'] as $key => $value) {
                if (!empty($list['fields']['key'][$key]) and !empty($value)) {
                    $customfield = ORM::factory('customfield')->where(array('diagram_id'=>$diagram_id, 'key'=>$list['fields']['key'][$key]))->find();
                    if (!empty($customfield->id)) {
                        if (isset($old_fields[$customfield->id])) {
                            unset($old_fields[$customfield->id]);
                        }
                    }
                    $customfield->type = $list['fields']['type'][$key];
                    $customfield->metavalue = $list['fields']['metavalue'][$key];
                    $customfield->diagram_id = $diagram_id;
                    $customfield->title = $value;
                    $customfield->key = $list['fields']['key'][$key];
                    $customfield->order = empty($list['fields']['order'][$key]) ? 0 : $list['fields']['order'][$key];
                    $customfield->save();
                }
            }
        }

        // delete fields
        foreach($old_fields as $field) {
            $customvalues = ORM::factory('customvalue')->where('customfield_id', $field)->find_all();
            foreach($customvalues as $customvalue) {
                ORM::factory('attachment')->where(array('diagram_id'=>$diagram_id, 'filename'=>$customvalue->value))->delete_all();
                upload::delete($customvalue->value);
                $customvalue->delete();
            }
            ORM::factory('customfield')->where('id', $field)->delete_all();
        }
    }
}
