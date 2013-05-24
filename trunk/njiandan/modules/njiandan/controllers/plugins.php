<?php defined('SYSPATH') OR die('No direct access allowed.');

class Plugins_Controller extends Controller {

    public function index() {
        $in_use_plugins = Kohana::config('core.plugins');
        $plugins = Plugin::get_all();

        foreach($plugins as $key => $plugin) {
            if (in_array($plugin['folder'], $in_use_plugins)) {
                $plugins[$key]['status'] = 'Active';
            } else {
                $plugins[$key]['status'] = 'Inactive';
            }
        }

        $view = new View('layouts/admin');
        $view->page_title = 'Plugins';
        $view->plugins = $plugins;
        $view->render(TRUE);
    }


    public function activate($plugin) {
        $plugins = Kohana::config('core.plugins');
        if (!in_array($plugin, $plugins)) {
            $plugins[] = $plugin;
            Njiandan::config_save('config.plugins', $plugins);
            Tip::set(T::_('Activete done.'));
        }
        url::admin_redirect('plugins');
    }


    public function deactivate($plugin) {
        $plugins = Kohana::config('core.plugins');
        if (in_array($plugin, $plugins)) {
            foreach($plugins as $key => $value) {
                if ($value == $plugin) {
                    unset($plugins[$key]);
                }
            }
            Njiandan::config_save('config.plugins', $plugins);
            Tip::set(T::_('Deactivete done.'));
        }
        url::admin_redirect('plugins');
    }
}
