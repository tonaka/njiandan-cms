<?php defined('SYSPATH') OR die('No direct access allowed.');

class AdminMenu_Core {
    // admin menu default items
    public static $use_submenu = true;
    public static $menus = array('Dashboard' => array(
                                    array('title' => 'Dashboard', 'uri' => 'dashboard', 'roles'=>array('View' => 'view_dashboard')),
                                    array('title' => 'Disk Space', 'uri' => 'disk_space', 'roles'=>array('View' => 'view_disk_space')),
                                 ),
                                 'Write' => array(
                                    array('title' => 'Write Post', 'uri' => 'post_new', 'roles'=>array('View' => 'view_post_new', 'Add' => 'post_new')),
                                    #array('title' => 'Drafts', 'uri' => 'drafts'),
                                 ),
                                 'Manage' => array(
                                    array('title' => 'Diagram', 'uri' => 'diagram', 'roles'=>array('View'=>'view_diagram', 'Edit'=>'edit_page')),
                                    array('title' => 'Posts', 'uri' => 'posts', 'roles'=>array('View' => 'view_posts', 'Delete' => 'delete_post', 'Star' => 'star_post')),
                                    array('title' => 'Comments', 'uri' => 'comments', 'roles'=>array('View' => 'view_comments', 'Approve' => 'approve_comment', 'Delete' => 'delete_comment')),
                                    array('title' => 'Pieces', 'uri' => 'pieces', 'roles'=>array('View' => 'view_pieces', 'Add' => 'add_piece', 'Edit' => 'edit_piece', 'Delete' => 'delete_piece')),
                                    array('title' => 'Diagram Manage', 'uri' => 'diagram_manage', 'roles'=>array('View'=>'view_diagram_manage', 'Manage Diagram'=>'manage_diagram')),
                                    array('title' => 'Plugins', 'uri' => 'plugins', 'roles'=>array('View'=>'view_plugins', 'Manage'=>'view_plugins')),
                                    array('title' => 'Themes', 'uri' => 'themes', 'roles'=>array('View'=>'view_themes', 'Manage'=>'manage_themes')),
                                 ),
                                 'Users' => array(
                                    array('title' => 'Users', 'uri' => 'users', 'roles'=>array('View'=>'view_users', 'Delete'=>'delete_user')),
                                    array('title' => 'Add New User', 'uri' => 'user_new', 'roles'=>array('View'=>'view_user_new', 'Add And Edit'=>'add_new_user')),
                                    array('title' => 'Your Profile', 'uri' => 'profile', 'roles'=>array('View'=>'view_profile', 'Edit'=>'edit_profile')),
                                 ),
                                 'Maintenance' => array(
                                    array('title' => 'Database Backup', 'uri' => 'data_backup', 'roles'=>array('View'=>'view_data_backup', 'Operation'=>'operation_data_backup')),
                                    array('title' => 'Database Restore', 'uri' => 'data_restore', 'roles'=>array('View'=>'view_data_restore', 'Operation'=>'operation_data_restore')),
                                    array('title' => 'Backup Theme Data', 'uri' => 'backup_theme_data', 'roles'=>array('View'=>'view_backup_theme_data', 'Operation'=>'operation_backup_theme_data')),
                                 ),
                                 'Options' => array(
                                    array('title' => 'General', 'uri' => 'options_general', 'roles'=>array('View'=>'view_options_general', 'Edit'=>'edit_options_general')),
                                    #array('title' => 'Image setting', 'uri' => 'options_image'),
                                    array('title' => 'Permalinks', 'uri' => 'options_permalink', 'roles'=>array('View'=>'view_options_permalink', 'Edit'=>'edit_options_permalink')),
                                    array('title' => 'Mail', 'uri' => 'options_mail', 'roles'=>array('View'=>'view_options_mail', 'Edit'=>'edit_options_mail')),
                                    array('title' => 'Admin uri', 'uri' => 'options_admin_uri', 'roles'=>array('View'=>'view_options_admin_uri', 'Edit'=>'edit_options_admin_uri')),
                                    array('title' => 'Comment', 'uri' => 'options_comment', 'roles'=>array('View'=>'view_options_comment', 'Edit'=>'edit_options_comment')),
                                 ),
                            );

    // show the admin menu
    public static function instance($type = NULL) {
        self::initialize();
        $current_main_menu_title = '';
        $output = '<div id="main-menu"><ul>'; // list the admin main menu
        foreach(self::$menus as $main_menu_title => $sub_menus) {
            $li_class = '';
            // get the current main menu title
            if (self::_in_array(Router::$controller, $sub_menus)) {
                $li_class = ' class="selected"';
                $current_main_menu_title = $main_menu_title;
            }
            $output .= "<li{$li_class}>" . html::admin_anchor($sub_menus[0]['uri'], T::_($main_menu_title)) . '</li>'; // get the sub menus first item uri for the main menus's uri
        }
        $output .= '</ul><div class="clear"></div></div>';
        if (self::$use_submenu) {
            $output .= '<div id="sub-menu-box"><ul id="sub-menu">'; // list the admin sub menu
            foreach(self::$menus[$current_main_menu_title] as $key => $sub_menu) {
                $li_class = '';
                if ((Router::$controller == $sub_menu['uri'])) {
                    $li_class = ' class="selected"';
                }
                $output .= "<li{$li_class}>" . html::admin_anchor('/' . $sub_menu['uri'] . '/', T::_($sub_menu['title'])) . '</li>';
            }
            $output .= '</ul></div>';
        }
        return $output;
    }

    public static function initialize() {
        Event::run('njiandan.admin_menu', self::$menus); // run the hooks to modify the admin menu
        // check the current user's capability, list the current user can do
        $user = Auth::instance()->get_user();

        foreach(self::$menus as $main_menu_title => $sub_menus) {
            foreach($sub_menus as $key => $sub_menu) {
                if (!$user->can($sub_menu['roles']['View'])) { //$sub_menu['uri']
                    unset(self::$menus[$main_menu_title][$key]); // if current user haven't have this role, unset it, so it won't show in the menus
                }
            }
            self::$menus[$main_menu_title] = array_values(self::$menus[$main_menu_title]);
            // if haven't all the sub menu role, unset the main menu item too
            if (empty(self::$menus[$main_menu_title])) {
                unset(self::$menus[$main_menu_title]); // unset the main menu item
            }
        }
    }

    private static function _in_array($value, $arrays) {
        foreach($arrays as $array) {
            if (in_array($value, $array)) {
                return true;
            }
        }
        return false;
    }
}
