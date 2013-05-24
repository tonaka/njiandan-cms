<?php defined('SYSPATH') OR die('No direct access allowed.');

class Options_permalink_Controller extends Controller {

    public function index($status = '') {
        if ($_POST) {
            // check is use have role
            if (!$this->user->can('edit_options_permalink')) {
                die(T::_('You are not access allowed.'));
            }

            $url_model = $this->input->post('url_model');
            switch($url_model) {
                case 'default':
                    Njiandan::config_save('config.index_page', 'index.php');
                    Njiandan::config_save('config.url_suffix', '');
                    Njiandan::config_save('njiandan.uri_optimize', 'id');
                    break;
                case 'htaccess':
                    $this->_write_htaccess();
                    Njiandan::config_save('config.index_page', '');
                    Njiandan::config_save('config.url_suffix', '');
                    Njiandan::config_save('njiandan.uri_optimize', 'id');
                    break;
                case 'html':
                    $this->_write_htaccess();
                    Njiandan::config_save('config.index_page', '');
                    Njiandan::config_save('config.url_suffix', '.html');
                    Njiandan::config_save('njiandan.uri_optimize', 'id');
                    break;
                case 'htaccess_and_uri':
                    $this->_write_htaccess();
                    Njiandan::config_save('config.index_page', '');
                    Njiandan::config_save('config.url_suffix', '');
                    Njiandan::config_save('njiandan.uri_optimize', 'uri');
                    break;
                case 'html_and_uri':
                    $this->_write_htaccess();
                    Njiandan::config_save('config.index_page', '');
                    Njiandan::config_save('config.url_suffix', '.html');
                    Njiandan::config_save('njiandan.uri_optimize', 'uri');
                    break;
            }
            Tip::set('Options saved.');
            Kohana::config_set('core.index_page', 'index.php');
            Kohana::config_set('core.url_suffix', '');
            url::admin_redirect('options_permalink');
        }

        $rewrite = false;
        // check rewrite module
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            if (in_array('mod_rewrite', $modules)) {
                $rewrite = true;
            }
        }

        $view = new View('layouts/admin');
        $view->page_title = 'Options Permalink';
        $view->rewrite = $rewrite;
        $view->default = false;
        $view->htaccess = false;
        $view->html =  false;
        $view->html_and_uri = false;
        $view->htaccess_and_uri = false;

        if (Kohana::config('core.index_page')) {
            $view->default = true;
        } else if (!Kohana::config('core.url_suffix') &&  Kohana::config('njiandan.uri_optimize') == 'id') {
            $view->htaccess = true;
        } else if (Kohana::config('core.url_suffix') == '.html' && Kohana::config('njiandan.uri_optimize') == 'id') {
            $view->html = true;
        } else if (!Kohana::config('core.url_suffix') && Kohana::config('njiandan.uri_optimize') == 'uri') {
            $view->htaccess_and_uri = true;
        } else if (Kohana::config('core.url_suffix') == '.html' && Kohana::config('njiandan.uri_optimize') == 'uri') {
            $view->html_and_uri = true;
        }

        $view->render(true);
    }

    protected function _write_htaccess() {
        $base = url::base();
        $output = "# Turn on URL rewriting
RewriteEngine On

# Installation directory
RewriteBase $base

# Protect application and system files from being viewed
RewriteRule ^(application|modules|system) - [F,L]

# Allow any files or directories that exist to be displayed directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Rewrite all other URLs to index.php/URL
RewriteRule .* index.php/$0 [PT,L]";
    $handle = fopen(DOCROOT . '.htaccess', 'wb');
    fwrite($handle, $output);
    fclose($handle);
    }
}
