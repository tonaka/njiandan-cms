<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_New_Controller extends Controller {

    public function index($id = null) {
        $id = (int)$id;
        if (empty($id)) {
            $content_title = 'Add a new user';
            $username = '';
            $password = '';
            $password_confirm = '';
            $email = '';
            $is_admin = false;
            $admin_roles = array();
            $user_id = null;
            $submenu = 'Add user';
        } else {
            $content_title = 'Edit user';
            $user = ORM::factory('user', $id);
            // user can't edit hisself, can't edit the first superuser
            if (empty($user->id) || $user->id == 1 || $user->id == $this->user->id) {
                die(T::_('You are not access allowed.'));
            }
            $user_id = $user->id;
            $username = $user->username;
            $password = '';
            $password_confirm = '';
            $email = $user->email;
            $admin_roles = array();
            foreach($user->roles as $role) {
                $admin_roles[] = $role->name;
            }
            $is_admin = $user->is_superuser;
            $submenu = 'Edit user';
        }

        $error_username = '';
        $error_password = '';
        $error_email = '';

        if ($_POST) {
            if (!$this->user->can('add_new_user')) {
                die(T::_('You are not access allowed.'));
            }
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $password_confirm = $this->input->post('password_confirm');
            $email = $this->input->post('email');
            $is_admin = $this->input->post('is_admin');
            $admin_roles = $this->input->post('AdminRoles');
            if (empty($admin_roles)) {
                $admin_roles = array();
            }
            $user = ORM::factory('user', $id);

            if (empty($username) || !valid::username($username)) {
                $error_username = T::_('Invalid username.');
            }

            if (empty($email) || !valid::email($email)) {
                $error_email = T::_('Invalid email.');
            }

            // if add a new user
            if (empty($user->id)) {
                if (empty($error_username) && $user->username_exists($username)) {
                    $error_username = T::_('This username is already registered, please choose another one.');
                }

                if (empty($password)) {
                    $error_password = T::_('The password field is empty.');
                }

                if (empty($error_password) && $password != $password_confirm) {
                    $error_password = T::_('Please type the same password in the two password fields.');
                }

                if (empty($error_email) && !$user->email_available($email)) {
                    $error_email = T::_('This email is already exists, please choose another one.');
                }

            } else {
                // if edit user
                if (empty($error_username) && $username != $user->username && $user->username_exists($username)) {
                    $error_username = T::_('This username is already registered, please choose another one.');
                }

                if (!empty($password) && $password != $password_confirm) {
                    $error_password = T::_('Please type the same password in the two password fields.');
                }

                if (empty($error_email) && $email != $user->email && !$user->email_available($email)) {
                    $error_email = T::_('This email is already exists, please choose another one.');
                }
            }

            // if no error, save the user
            if (empty($error_username) && empty($error_password) && empty($error_email)) {
                $user->username = $username;
                $user->email = $email;
                $user->status = 1; // status:1:Active, 2:Inactive, 0:hide(delete)
                if (empty($user->id)) {
                    $user->activation_key = '';
                }
                if ($is_admin == 'admin') {
                    $user->is_superuser = 1;
                } else {
                    $user->is_superuser = 0;
                }
                if (!empty($password)) {
                    $user->password = $password;
                }
                // remove user roles
                if (!empty($user->id)) {
                    foreach($user->roles as $role) {
                        $user->remove($role);
                    }
                }
                $user->save();

                // if user is superuser, user have all roles.
                if (!$user->is_superuser) {
                    // add user roles
                    if (!empty($admin_roles)) {
                        //if is edit user

                        foreach($admin_roles as $role) {
                            // check the role is exists, if exists , add it to user
                            if (ORM::factory('role', $role)->id) {
                                // add the user role
                                $user->add(ORM::factory('role', $role));
                            }
                        }
                        $user->save();
                    }
                }
                // login role
                $user->add(ORM::factory('role', 'login'));
                $user->save();

                if (!empty($id)) {
                    Tip::set(sprintf(T::_('Edit user %s done.'), $username));
                } else {
                    Tip::set(sprintf(T::_('Add user %s done.'), $username));
                }
                $redirect_uri = $this->input->get('redirect_uri');
                if (empty($redirect_uri)) {
                    $redirect_uri = url::admin_site('user_new');
                }
                url::redirect($redirect_uri);
            }
        }

        $view = new View('layouts/admin');
        $view->page_title = 'Add New User';
        $view->content_title = $content_title;
        $view->error_username = $error_username;
        $view->error_password = $error_password;
        $view->error_email = $error_email;
        $view->user_id = $user_id;
        $view->username = $username;
        $view->password = $password;
        $view->password_confirm = $password_confirm;
        $view->email = $email;
        $view->is_admin = $is_admin;

        $view->admin_roles = array_flip($admin_roles);
        $view->submenu = $submenu;
        $view->render(true);
    }
}
