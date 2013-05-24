<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller extends Controller_Core {

    public function __construct() {

        $no_xss_filtering = array('post_new', 'piece', 'diagram', 'piece', 'diagram_manage');
        Event::run('njiandan.no_xss_filtering');
        if (in_array(Router::$controller, $no_xss_filtering)) {
            // disable global XSS filtering
            Kohana::config_set('core.global_xss_filtering', FALSE);
        }

        parent::__construct();
        // if is install
        if (Router::$controller != 'install') {
            // check is njiandan installed
            if (!Njiandan::is_installed()) {
                die(sprintf(T::_('It seemed that you haven&#8217;t installed Njiandan. You can <a href="%s">install it through a web interface</a>.'), url::admin_site('install')));
            }

            // get current user
            $authentic = new Auth;
            $this->user = $authentic->get_user();

            // some controller method need'nt login
            $pool = array('comments' => 'add');
            Event::run('njiandan.unlogin_controller', $pool);

            if (Njiandan::check_admin_uri() && Router::$controller != 'login') {
                // if matches, un check login status
                if (!empty($pool[Router::$controller]) and $pool[Router::$controller] == Router::$method) {
                    // do some other unlogin check
                } else {
                    // if user is not login
                    if (!$authentic->logged_in()) {
                        url::admin_redirect('/login?redirect_uri=' . Router::$routed_uri);
                    } else {
                        // check current user have this role
                        if (!$this->user->can('view_' . Router::$controller)) {
                            die(T::_('You are not access allowed.'));
                        }
                    }
                }
            }

        }
    }
}
