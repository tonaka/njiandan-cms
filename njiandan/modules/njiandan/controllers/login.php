<?php defined('SYSPATH') OR die('No direct access allowed.');

class Login_Controller extends Controller {

	/**
	 * user login page, redirect to the request page
	 * when the cookies is valid.
	 */
    public function index($status = '') {
		$username = '';
		$password = '';
		$remember_me = FALSE;
		$errors = '';
		$tips = '';

		# get the redirect uri
		$redirect_uri = $this->input->get('redirect_uri');

		if ($_POST) {
		    $status = '';
		    $username = $this->input->post('user_login');
		    $password = $this->input->post('user_pass');
		    $remember_me = $this->input->post('remember_me');

		    if (empty($username)) { // check if the username is empty
		        $errors = T::_('ERROR: The username field is empty.');
		    } else if (empty($password)) { // check if the password is empty
		        $errors = T::_('ERROR: The password field is empty.');
		    }

            if (!empty($remember_me)) { // check if remember user
                $remember_me = TRUE;
            }

		    // get a security username
		    $username = security::sanitize_username($username);
		    if (empty($errors)) {
		        if (empty($username)) {
		            $errors = T::_('ERROR: Invalid username.');
		            $password = '';
		        } else {
                    // Attempt a login
		            if (Auth::instance()->login($username, $password, $remember_me)) {
                        if (empty($redirect_uri)) {
                            AdminMenu::initialize();
                            $menus = array_shift(AdminMenu::$menus);
                            $redirect_uri = $menus[0]['uri'];
                        }
					    url::admin_redirect($redirect_uri);
		            } else {
		                $errors = T::_('ERROR: The username or password you entered is incorrect.');
		                $password = '';
		            }
		        }
		    }
		} else {
		    if (!empty($this->user->id)) {
                if (empty($redirect_uri)) {
                    AdminMenu::initialize();
                    $menus = array_shift(AdminMenu::$menus);
                    $redirect_uri = $menus[0]['uri'];
                }
		        url::admin_redirect($redirect_uri);
		    }
		}

		// if the user logout done , show the status
		if ($status == 'logout_done') {
		    $tips = T::_('You are now logged out.');
		}

		$view = new View('layouts/login');
        $view->user_login = $username;
        $view->user_pass = $password;
        $view->errors = $errors;
        $view->tips = $tips;
        $view->checked = $remember_me;
        $view->render(TRUE);
    }

    public function logout() {
        $authentic = new Auth;
        $authentic->logout(TRUE);
        url::admin_redirect('/login/logout_done/');
    }

    public function lost_password($status = '') {
        $errors = null;
        $tips = $status == 'success' ? T::_('Check your e-mail for the confirmation link.') : T::_('Please enter your username or e-mail address. You will receive a new password via e-mail.');
        if ($_POST) {
            $user_login = trim($this->input->post('user_login'));
            if (empty($user_login)) {
                $errors = T::_('ERROR: Enter a username or e-mail address.');
            } else {
                // check if is an email
                if (valid::email($user_login)) {
                    $user = ORM::factory('user')->where('email', $user_login)->find();
                } else {
                    $user = ORM::factory('user')->where('username', $user_login)->find();
                }
                // if user not exists
                if (empty($user->id)) {
                    $errors = T::_('ERROR: The username or email isn&#8217;t exists.');
                } else {
                    // if all right, send the email
                    $to = $user->email;
                    $driver = Kohana::config('email.driver');

                    if ($driver == 'smtp') {
                        $options = Kohana::config('email.options');
                        $from = $options['username'];
                    } else {
                        $root = ORM::factory('user')->where('is_superuser', '1')->orderby(array('id', 'ASC'))->find();
                        $from = $root->email;
                    }

                    $activation_key = $this->_activation_key($user->username . $user->email . time());
                    $user->activation_key = $activation_key;
                    $user->save();

                    $subject = T::_('Verify password reset');
                    Kohana::config_set('core.index_page', 'index.php');
                    Kohana::config_set('core.url_suffix', '');
                    $reset_url = Kohana::config('njiandan.site_url') . url::admin_site('login/reset_password/' . $activation_key);
                    $message = sprintf(T::_('Hi %s,<br><br>A request has been made to reset your password for Njiandan admin.<br><br>You can reset your password by copying and pasting the following URL into the location bar of your web browser:<br><br>%s<br><br>If you did not make this request, please disregard this email and your password will remain unchanged.'), $user->username, $reset_url);
                    email::send($to, $from, $subject, $message, TRUE);
                    url::admin_redirect('login/lost_password/success');
                }
            }
        }

        $view = new View('layouts/login');
        $view->tips = $tips;
        $view->errors = $errors;
        $view->status = $status;
        $view->render(true);
    }

	/**
	 * when a method of a controller is called that doesn't exist.
	 * show the login page and set the method to the index method's status
	 */
    public function __call($status, $arguments) {
        Router::$method = 'index';
        $this->index($status);
    }

    // for user reset his password
    public function reset_password($key) {
        $key = trim($key);
        $user = ORM::factory('user')->where('activation_key', $key)->find();
        $tips = sprintf(T::_('Hi <b>%s</b>: Create your new password.'), $user->username);
        $errors = '';
        $hide_input = false;
        if (empty($user->id)) {
            $errors = T::_('This link is invalid, has already been used, or has expired, please request a new one.');
            $tips = '';
            $hide_input = true;
        } else {
            if ($_POST) {
                $password = $this->input->post('password');
                $re_password = $this->input->post('re_password');
                if (empty($password)) {
                    $errors = T::_('The password field is empty.');
                } else if ($password != $re_password) {
                    $errors = T::_('Please type the same password in the two password fields.');
                }
                // if empty errors , reset user's password
                if (empty($errors)) {
                    $user->activation_key = '';
                    $user->password = $password;
                    $user->save();
                    Auth::instance()->login($user->username, $password);
                    Tip::set('New password create success.');
                    url::admin_redirect('profile');
                }
            }
        }
        $view = new View('layouts/login');
        $view->tips = $tips;
        $view->errors = $errors;
        $view->hide_input = $hide_input;
        $view->render(true);
    }

    /*
        find a not repeat activation key
    */
    protected function _activation_key($string) {
        $activation_key = Auth::instance()->hash_password($string);
        $user = ORM::factory('user')->where('activation_key', $activation_key)->find();
        if (!empty($user->id)) {
            return $this->_activation_key($string);
        } else {
            return $activation_key;
        }
    }
}
