<?php defined('SYSPATH') OR die('No direct access allowed.');

class Options_Mail_Controller extends Controller {

    public function index() {
        $smtp_hostname_error = '';
        $smtp_mail_error = '';
        $smtp_password_error = '';
        $sendmail_path_error = '';
        
        $smtp_hostname = '';
        $smtp_port = 25;
        $smtp_mail = '';
        $smtp_password = '';
        $sendmail_path = '';

        if ($_POST) {
            // check is use have role
            if (!$this->user->can('edit_options_mail')) {
                die(T::_('You are not access allowed.'));
            }
            $driver = trim($this->input->post('driver'));
            $options = array();
            switch($driver) {
                case 'smtp':
                    $smtp_hostname = trim($this->input->post('smtp_hostname'));
                    $smtp_port = trim($this->input->post('smtp_port'));
                    $smtp_mail = trim($this->input->post('smtp_mail'));
                    $smtp_password = $this->input->post('smtp_password');
                    if (empty($smtp_hostname)) {
                        $smtp_hostname_error = T::_('Invalid smtp server.');
                    }
                    if (empty($smtp_port) or !is_numeric($smtp_port)) {
                        $smtp_port = 25;
                    }
                    if (empty($smtp_mail) or !valid::email($smtp_mail)) {
                        $smtp_mail_error = T::_('Invalid email.');
                    }
                    if (empty($smtp_password)) {
                        $smtp_password_error = T::_('Invalid password.');
                    }

                    $options['hostname'] = $smtp_hostname;
                    $options['port'] = $smtp_port;
                    $options['username'] = $smtp_mail;
                    $options['password'] = $smtp_password;
                    break;
                case 'sendmail':
                    $sendmail_path = trim($this->input->post('sendmail_path'));
                    $options = $sendmail_path;
                    break;
                case 'native':
                case 'default':
                    $options = '';
                    break;
            }
            // save options
            if (empty($smtp_hostname_error) and empty($smtp_mail_error) and empty($smtp_password_error)) {
                Njiandan::config_save('email.driver', $driver);
                Njiandan::config_save('email.options', $options);
                Tip::set('Options saved.');
                url::admin_redirect('options_mail');
            }
        } else {
            $driver = Kohana::config('email.driver');
            $options = Kohana::config('email.options');
            if ($driver == 'smtp') {
                $smtp_hostname = $options['hostname'];
                $smtp_port = $options['port'];
                $smtp_mail = $options['username'];
                $smtp_password = $options['password'];
            } else if ($driver == 'sendmail') {
                $sendmail_path = $options;
            }
        }
        $view = new View('layouts/admin');
        $view->page_title = 'Mail settings';
        $view->native = false;
        $view->smtp = false;
        $view->sendmail = false;
        $view->$driver = true;
        $view->driver = $driver;
        $view->smtp_hostname_error = $smtp_hostname_error;
        $view->smtp_mail_error = $smtp_mail_error;
        $view->smtp_password_error = $smtp_password_error;
        
        $view->smtp_hostname = $smtp_hostname;
        $view->smtp_port = $smtp_port;
        $view->smtp_mail = $smtp_mail;
        $view->smtp_password = $smtp_password;
        $view->sendmail_path = $sendmail_path;
        $view->render(true);
    }
}
