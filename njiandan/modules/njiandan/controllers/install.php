<?php defined('SYSPATH') OR die('No direct access allowed.');

class Install_Controller extends Controller {

    public function index() {
        $this->_install_initialize();
        if (Njiandan::is_installed()) {
            return $this->_installed();
        }
        if ($_POST) {
            $default_language = $this->input->post('language');
            if (!empty($default_language)) {
                cookie::set('default_language', $default_language);
            }
            url::admin_redirect('install/step1');
        }
        $view = new View('layouts/install');
        $view->page_title = 'Installation';
        $view->languages = T::get_languages();
        $browser_language = '';
        $languages = Kohana::user_agent('languages');
        if (isset($languages[0])) {
            $browser_language = $languages[0];
        }
        $view->browser_language = $browser_language;
        $view->render(true);
    }

    public function step1() {
        $this->_install_initialize();
        if (Njiandan::is_installed()) {			
            return $this->_installed();
        }
        $njiandan = false;
        $apppath = false;
        $config_folder = false;
        $config_file = false;

        if (is_writable(DOCROOT)) {
            $njiandan = true;
        }

        if (is_writable(APPPATH)) {
            $apppath = true;
        }

        if (is_writable(APPPATH . 'config')) {
            $config_folder = true;
            Njiandan::config_save('njiandan.default_language', cookie::get('default_language'));
        }

        if (is_writable(APPPATH . 'config/config.php')) {
            $config_file = true;
        }


        $view = new View('layouts/install');
        $view->page_title = 'Installation step 1';
        $view->njiandan = $njiandan;
        $view->apppath = $apppath;
        $view->config_folder = $config_folder;
        $view->config_file = $config_file;
        $view->languages = T::get_languages();
        $view->render(true);
    }

    public function step2() {
        $this->_install_initialize();
        if (Njiandan::is_installed()) {
            return $this->_installed();
        }
        $dbname = '';
        $dbusername = '';
        $dbpassword = '';
        $dbhost = 'localhost';
        $tbprefix = 'n_';
        $error_message = '';
        if ($_POST) {
            $database_config = Kohana::config('database.default');
            //$dbname = $database = $this->input->post('dbname');
            $database_config['connection']['database'] = $this->input->post('dbname');
            $dbusername = $database_config['connection']['user'] = $this->input->post('dbusername');
            $dbpassword = $database_config['connection']['pass'] = $this->input->post('dbpassword');
            $dbhost = $database_config['connection']['host'] = $this->input->post('dbhost');
            $tbprefix = $database_config['table_prefix'] = $this->input->post('tbprefix');
            $auto_create = $this->input->post('run_auto_create');
            Kohana::config_set('database.default', $database_config);
            $db = new Database();
            $db->connect();
            Njiandan::config_save('database.default', $database_config);
            url::admin_redirect('install/step3');
            // get db instance
            #$db = new Database();
            // check username, password, host
            #$db->connect();
            /*
            // check if database exists
            $exists = false;
            $databases = $db->query("SHOW DATABASES");
            foreach($databases as $object) {
                if ($database == $object->Database) {
                    $exists = true;
                }
            }
            if (!$exists && !empty($auto_create)) {
                $db->query("CREATE DATABASE IF NOT EXISTS `$database`");
                $exists = true;
            }
            if ($exists) {
                $database_config['connection']['database'] = $database;
                Njiandan::config_save('database.default', $database_config);
                url::admin_redirect('install/step3');
            } else {
                $error_message = sprintf(T::_("Database '%s' is not exists, Auto create it or input it again?"), $database);
            }
            */
        }
        $view = new View('layouts/install');
        $view->page_title = 'Installation step 2';
        $view->dbname = $dbname;
        $view->dbusername = $dbusername;
        $view->dbpassword = $dbpassword;
        $view->dbhost = $dbhost;
        $view->tbprefix = $tbprefix;
        $view->error_message = $error_message;
        $view->render(true);
    }

    public function step3() {
        $this->_install_initialize();
        if (Njiandan::is_installed()) {
            return $this->_installed();
        }
        $view = new View('layouts/install');
        $view->page_title = 'Installation step 3';
        $view->render(true);
    }

    // njiandan is_installed 应该返回true或者false
    public function step4() {
        $this->_install_initialize();
        if (Njiandan::is_installed()) {
            return $this->_installed();
        }
        $view = new View('layouts/install');

        $view->page_title = 'Installation step 4';
        $view->error_message = '';
        $view->username = '';
        $view->password = '';
        $view->password_confirm = '';
        $view->site_title = '';
        $view->admin_uri = 'admin';
        $view->admin_email = '';
        $view->initial_data = true;

        if ($_POST) {
            $view->username = trim($this->input->post('username'));

            if (empty($view->username)) {
                $view->error_message = T::_('ERROR: The username is empty.');
            }

            if (empty($view->error_message) && !valid::username($view->username)) {
                $view->error_message = T::_('ERROR: Invalid username.');
            }

            $view->password = $this->input->post('password');
            $view->password_confirm = $this->input->post('password_confirm');

            if (empty($view->error_message) && (empty($view->password) || empty($view->password_confirm))) {
                $view->error_message = T::_('ERROR: The password is empty.');
            }

            if (empty($view->error_message) && $view->password != $view->password_confirm) {
                $view->error_message = T::_('ERROR: The password not equal.');
            }

            $view->site_title = trim($this->input->post('site_title'));

            $view->admin_uri = trim($this->input->post('admin_uri'));
            if (empty($view->error_message) && empty($view->admin_uri)) {
                $view->error_message = T::_('ERROR: Admin uri is empty.');
            }
            if (empty($view->error_message) && !valid::alpha_dash($view->admin_uri)) {
                $view->error_message = T::_('ERROR: Invalid admin uri.');
            }

            $view->admin_email = trim($this->input->post('admin_email'));
            if (empty($view->error_message) && !valid::email($view->admin_email)) {
                $view->error_message = T::_('ERROR: Invalid email.');
            }

            $view->initial_data = $this->input->post('initial_data') == 'yes' ? true : false;

            if (empty($view->error_message)) {
                // create folder
                // attach, backup, cache, logs
				if (!file_exists(APPPATH . 'attach'))  {
					mkdir(APPPATH . 'attach');
				}
				if (!file_exists(APPPATH . 'backup'))  {
					mkdir(APPPATH . 'backup');
				}
				if (!file_exists(APPPATH . 'cache'))  {
					mkdir(APPPATH . 'cache');
				}
				if (!file_exists(APPPATH . 'logs'))  {
					mkdir(APPPATH . 'logs');
				}
				
				Install::db();

                // add admin user
                $user = ORM::factory('user');
                $user->username = $view->username;
                $user->email = $view->admin_email;
                $user->is_superuser = 1;
                $user->status = 1;
                $user->activation_key = '';
                $user->password = $view->password;
                $user->save();


                // save site info
                Njiandan::config_save('njiandan.site_title', $view->site_title);

                // save current http post for site url
                $site_url = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
                if (!empty($site_url)) {
                    $site_url = 'http://' . $site_url;
                    Njiandan::config_save('njiandan.site_url', $site_url);
                }

                // save upload_max_filesize
                Njiandan::config_save('njiandan.upload_max_filesize', ini_get('upload_max_filesize'));

                $cache = Cache::instance();
                $cache->set('njiandan_installed', true, null, 0);

                if ($view->initial_data) {
                    Install::initial_data(Kohana::config('core.theme'));
                }

                // save admin uri
                Njiandan::config_save('njiandan.admin_uri', $view->admin_uri);
                Kohana::config_set('njiandan.admin_uri', $view->admin_uri);
                url::admin_redirect('install/step5');
            }

        }
        $view->render(true);
    }

    public function step5() {
        $this->_install_initialize();
        $view =  new View('layouts/install');
        $view->page_title = 'Installation step 5';
        $view->render(true);
    }

    protected function _install_initialize() {
        // get default language
        $default_language = strtolower(cookie::get('default_language'));
        if (empty($default_language)) {
            $default_languages = Kohana::user_agent('languages');
            if (!empty($default_languages)) {
                foreach($default_languages as $language) {
                    $language = strtolower($language);
                    T::add_language('languages', $language . '_install');
                }
            }
        } else {
            T::add_language('languages', $default_language . '_install');
        }
    }

    protected function _installed() {
        $view =  new View('install/installed');
        $view->page_title = 'Installation step 5';
        $view->render(true);
    }
}
