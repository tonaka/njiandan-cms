<?php defined('SYSPATH') OR die('No direct access allowed.');

class Comments_Controller extends Controller {

    public function index($id = NULL) {
        $view = new View('layouts/admin');
        $config = Kohana::config('pagination.default');
        $config['total_items'] = ORM::factory('comment')->count_all();
        $paging = new Pagination($config);
        $view->pagelink = $paging->render('gmail');
        $view->comments = ORM::factory('comment')->orderby(array('id'=>'DESC'))->limit($paging->items_per_page, $paging->sql_offset)->find_all();

        $view->page_title = 'Comments';
        $view->render(TRUE);
    }

    public function add($id = 0, $type = '') {
        $id = (int)$id;
        $uri_extra = '';
        if (empty($id) or empty($type) or !in_array($type, array('post', 'page'))) {
            // if empty id visit for hack
            $redirect_url = request::referrer();
            if (!empty($redirect_url)) {
                url::redirect($redirect_url);
            } else {
                url::redirect('/');
            }
        }

        // get the redirect 
        if ($type == 'post') {
            $post = ORM::factory('post')->where(array('id' => $id, 'status'=>1, 'diagram_id!=' => 0))->find();
            $redirect_uri = $post->link;
        } else {
            $diagram = ORM::factory('diagram')->where(array('id' => $id, 'type' => 'page'))->find();
            $redirect_uri = $diagram->uri;
        }

        $username = trim($this->input->post('username'));
        $email = trim($this->input->post('email'));
        $url = trim($this->input->post('url'));
        $content = trim($this->input->post('content'));
        $parent_id = (int)trim($this->input->post('parent_id'));
        
        $username_error = '';
        $email_error = '';
        $url_error = '';
        $content_error = '';

        if (empty($this->user->id)) {
            if (empty($username)) {
                $username_error = T::_('Name is required.');
            }
            if (empty($email)) {
                $email_error = T::_('Email is required.');
            }

            if (empty($username_error) and empty($email_error)) {
                if (!valid::email($email)) {
                    $email_error = T::_('Invalid email.');
                }
                if (!empty($url)) {
                    $url_types = array('http://', 'https://');
                    $url = 'http://' . str_replace($url_types, '', $url);
                    if ( !valid::url($url)) {
                        $url_error = T::_('Invalid url.');
                    }
                }
            }
        } else {
            $username = $this->user->username;
            $email = $this->user->email;
        }


        if (empty($content)) {
            $content_error = T::_('Content is required.');
        }

        // if empty error, save comment
        if (empty($username_error) and empty($email_error) and empty($url_error) and empty($content_error)) {
            $comment = new Comment_Model();

            $comment->user_id = !empty($this->user->id) ? $this->user->id : 0;
            $comment->username = $username;
            $comment->email = $email;
            $comment->url = $url;

            $comment->ip = $this->input->ip_address();
            $comment->date = time();
            $comment->parent_id = $parent_id;
            $comment->content = $content;

            // if comment need audit or user is a manager, set the status 0
            if (!empty($this->user->id) or !Kohana::config('njiandan.is_comment_need_approve')) {
                $comment->is_approved = 1;
            } else {
                $comment->is_approved = 0;
            }

            $comment->agent = $_SERVER['HTTP_USER_AGENT'];
            if ($type == 'post') {
                $comment->post_id = $id;
                $comment->diagram_id = 0;
            } else if ($type == 'page') {
                $comment->diagram_id = $id;
                $comment->post_id = 0;
            }
            cookie::set('Comment_status', 'comment_done');
            $comment->save();
            $uri_extra = '#comment_' . $comment->id;

            // record comment count to post
            $condition = array('post_id'=>$comment->post_id, 'diagram_id'=>$comment->diagram->id);
            if (Kohana::config('njiandan.is_comment_need_approve')) {
                $condition['is_approved'] = 1;
            }

            $comment->post->comment_count = ORM::factory('comment')->where($condition)->count_all();
            $comment->post->save();

            /*
                keep the unlongin userinfo
            */
            if (empty($this->user->id)) {
                cookie::set('Comment_username', $username);
                cookie::set('Comment_email', $email);
                cookie::set('Comment_url', $url);
            }
        } else {
            cookie::set('Comment_username', $username);
            cookie::set('Comment_username_error', $username_error);
            cookie::set('Comment_email', $email);
            cookie::set('Comment_email_error', $email_error);
            cookie::set('Comment_url', $url);
            cookie::set('Comment_url_error', $url_error);
            cookie::set('Comment_content', $content);
            cookie::set('Comment_content_error', $content_error);
            cookie::set('Commnet_parent_id', $parent_id);
            $uri_extra = '#comment_form';
        }
        url::redirect($redirect_uri . $uri_extra);
    }


    /*
        approve the comment
    */
    public function approve($id) {
        $id = (int)$id;
        if ($this->user->can('approve_comment')) {
            $comment = ORM::factory('comment')->where('id', $id)->find();
            $comment->is_approved = 1;
            $comment->save();
            Tip::set(T::_('Comment approved.'));
        }

        $redirect_uri = $this->input->get('redirect_uri');
        if (empty($redirect_uri)) {
            url::admin_redirect('comments');
        } else {
            url::redirect($redirect_uri);
        }
    }

    public function cancel_approve($id) {
        $id = (int)$id;
        if ($this->user->can('approve_comment')) {
            $comment = ORM::factory('comment')->where('id', $id)->find();
            $comment->is_approved = 0;
            $comment->save();
            Tip::set(T::_('Comment cancel approve done.'));
        }

        $redirect_uri = $this->input->get('redirect_uri');
        if (empty($redirect_uri)) {
            url::admin_redirect('comments');
        } else {
            url::redirect($redirect_uri);
        }
    }

    /*
        delete the comment
    */
    public function delete($id) {
        $id = (int)$id;
        if ($this->user->can('delete_comment')) {
            $comment = ORM::factory('comment')->where('id', $id)->find();
            $comment->delete();
            Tip::set(T::_('Comment deleted.'));
        }

        $redirect_uri = $this->input->get('redirect_uri');
        if (empty($redirect_uri)) {
            url::admin_redirect('comments');
        } else {
            url::redirect($redirect_uri);
        }
    }
}
