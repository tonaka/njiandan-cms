<?php defined('SYSPATH') OR die('No direct access allowed.');

class Profile_Controller extends Controller {

    public function index() {
        $password_error = '';
        $email_error = '';
        $password = '';
        $password_confirm = '';
        $email = $this->user->email;

        if ($_POST) {
            // check role
            if (!$this->user->can('edit_profile')) {
                die(T::_('You are not access allowed.'));
            }
            $password = $this->input->post('password');
            $password_confirm = $this->input->post('password_confirm');
            $email = $this->input->post('email');

            if (!empty($password) && $password != $password_confirm) {
                $password_error = T::_('Please type the same password in the two password fields.');
            }

            // update the email
            if (empty($email) or !valid::email($email)) {
                $email_error = T::_('Invalid email.');
            }

            if (empty($email_error) && $email != $this->user->email && !$this->user->email_available($email)) {
                $email_error = T::_('This email is already exists, please choose another one.');
            }

            if (empty($password_error) && empty($email_error)) {
                $this->user->email = $email;
                if (!empty($password)) {
                    $this->user->password = $password;
                }
                $this->user->save();
                Tip::set('Update profile done.');
                url::admin_redirect('profile');
            }
        }

        $view = new View('layouts/admin');
        $view->page_title = 'Profile';
        $view->password_error = $password_error;
        $view->email_error = $email_error;
        $view->password = $password;
        $view->password_confirm = $password_confirm;
        $view->email = $email;
        $view->render(true);
    }
}
