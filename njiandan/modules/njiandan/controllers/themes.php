<?php defined('SYSPATH') OR die('No direct access allowed.');

class Themes_Controller extends Controller {

    public function index() {
        $view = new View('layouts/admin');
        $view->page_title = 'Themes';
        $view->current = Theme::current();
        $view->themes = Theme::get_all();
        $view->render(true);
    }

    public function change_theme($theme) {
        if (!$this->user->can('manage_themes')) {
            die(T::_('You are not access allowed.'));
        }
        ini_set('max_execution_time', '180'); 
        Njiandan::config_save('config.theme', $theme);
        Install::initial_data($theme);

        $info = Theme::get_info($theme);
        Tip::set(sprintf(T::_('Theme %s update done.'), $info['name']));
        url::admin_redirect('themes');
    }
}
