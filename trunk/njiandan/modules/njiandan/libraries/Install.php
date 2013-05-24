<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * njiandan install class
 */

class Install_core {

    public static function db() {
        $db = new Database();
        $database_config = Kohana::config('database.default');
        $prefix = $database_config['table_prefix'];

        $db->query('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";');

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}attachments` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `post_id` int(10) unsigned NOT NULL,
          `diagram_id` smallint(5) unsigned NOT NULL,
          `title` varchar(100) NOT NULL,
          `filename` varchar(100) NOT NULL,
          `mime` varchar(32) NOT NULL,
          `size` int(10) unsigned NOT NULL,
          `date` int(10) unsigned NOT NULL,
          `is_thumb` tinyint(1) unsigned NOT NULL,
          `downloads` int(10) unsigned NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}comments` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `post_id` int(10) unsigned NOT NULL,
          `diagram_id` smallint(5) unsigned NOT NULL,
          `parent_id` int(10) unsigned NOT NULL,
          `username` varchar(100) NOT NULL,
          `email` varchar(200) NOT NULL,
          `url` varchar(200) NOT NULL,
          `ip` varchar(15) NOT NULL,
          `date` int(10) unsigned NOT NULL,
          `content` text NOT NULL,
          `is_approved` tinyint(1) unsigned NOT NULL,
          `agent` varchar(200) NOT NULL,
          `user_id` mediumint(8) unsigned NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}customfields` (
          `id` smallint(5) unsigned NOT NULL auto_increment,
          `diagram_id` smallint(5) unsigned NOT NULL,
          `type` varchar(15) NOT NULL,
          `title` varchar(50) NOT NULL,
          `key` varchar(50) NOT NULL,
          `order` tinyint(3) unsigned NOT NULL,
          `metavalue` text NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}customvalues` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `customfield_id` smallint(5) unsigned NOT NULL,
          `post_id` int(10) unsigned NOT NULL,
          `diagram_id` smallint(5) unsigned NOT NULL,
          `key` varchar(50) NOT NULL,
          `value` text NOT NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}diagrams` (
          `id` smallint(5) unsigned NOT NULL auto_increment,
          `user_id` mediumint(8) unsigned NOT NULL,
          `type` varchar(10) NOT NULL,
          `title` varchar(100) NOT NULL,
          `content` longtext NOT NULL,
          `parent_id` smallint(5) unsigned NOT NULL,
          `uri` varchar(250) NOT NULL,
          `template` varchar(100) NOT NULL,
          `metavalue` text NOT NULL,
          `date` int(10) unsigned NOT NULL,
          `order` smallint(5) unsigned NOT NULL default '0',
          `status` tinyint(1) unsigned NOT NULL default '1',
          PRIMARY KEY  (`id`),
          KEY `uri` (`uri`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}googles` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `post_id` int(10) unsigned NOT NULL,
          `title` varchar(250) NOT NULL,
          `content` longtext NOT NULL,
          `type` varchar(10) NOT NULL,
          `date` int(10) unsigned NOT NULL,
          `view` int(10) unsigned NOT NULL,
          PRIMARY KEY  (`id`),
          FULLTEXT KEY `title` (`title`),
          FULLTEXT KEY `content` (`content`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}posts` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `user_id` mediumint(8) unsigned NOT NULL,
          `diagram_id` smallint(5) unsigned NOT NULL,
          `title` varchar(250) NOT NULL,
          `excerpt` text NOT NULL,
          `content` longtext NOT NULL,
          `password` varchar(50) NOT NULL,
          `allow_ping` tinyint(1) unsigned NOT NULL default '1',
          `to_ping` text NOT NULL,
          `allow_comment` tinyint(1) unsigned NOT NULL default '1',
          `date` int(10) unsigned NOT NULL,
          `uri` varchar(250) NOT NULL,
          `is_thumb` tinyint(1) unsigned NOT NULL default '0',
          `is_star` tinyint(1) unsigned NOT NULL default '0',
          `view` int(10) unsigned NOT NULL default '0',
          `status` tinyint(1) unsigned NOT NULL default '1',
          `comment_count` int(10) unsigned NOT NULL default '0',
          PRIMARY KEY  (`id`),
          KEY `uri` (`uri`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}roles` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `name` varchar(32) NOT NULL,
          `description` varchar(255) NOT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `uniq_name` (`name`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}roles_users` (
          `user_id` mediumint(8) unsigned NOT NULL,
          `role_id` int(10) unsigned NOT NULL,
          PRIMARY KEY  (`user_id`,`role_id`),
          KEY `fk_role_id` (`role_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}users` (
          `id` mediumint(8) unsigned NOT NULL auto_increment,
          `email` varchar(127) NOT NULL,
          `username` varchar(32) NOT NULL default '',
          `password` char(50) NOT NULL,
          `is_superuser` tinyint(1) unsigned NOT NULL,
          `logins` int(10) unsigned NOT NULL default '0',
          `last_login` int(10) unsigned default NULL,
          `activation_key` char(50) NOT NULL,
          `status` tinyint(1) unsigned NOT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `uniq_username` (`username`),
          UNIQUE KEY `uniq_email` (`email`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $db->query("CREATE TABLE IF NOT EXISTS `{$prefix}user_tokens` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `user_id` mediumint(8) unsigned NOT NULL,
          `user_agent` varchar(40) NOT NULL,
          `token` varchar(32) NOT NULL,
          `created` int(10) unsigned NOT NULL,
          `expires` int(10) unsigned NOT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `uniq_token` (`token`),
          KEY `fk_user_id` (`user_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
/*
        $db->query("ALTER TABLE `{$prefix}roles_users`
            ADD CONSTRAINT `{$prefix}roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE,
            ADD CONSTRAINT `{$prefix}roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `{$prefix}roles` (`id`) ON DELETE CASCADE;");

        $db->query("ALTER TABLE `{$prefix}user_tokens`
            ADD CONSTRAINT `{$prefix}user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `{$prefix}users` (`id`) ON DELETE CASCADE;");
*/

        $db->query("TRUNCATE TABLE `{$prefix}roles`");
        $db->query("INSERT INTO `{$prefix}roles` (`id`, `name`, `description`) VALUES 
        (NULL , 'login', 'login to the admin'),

        (NULL , 'view_dashboard', 'View menu Dashboard'), 
        (NULL , 'view_disk_space', 'View menu Disk Space'),

        (NULL , 'view_post_new', 'View menu Post New'),
        (NULL , 'post_new', 'Post new'),

        (NULL , 'view_diagram', 'View menu Diagram'),
        (NULL , 'edit_page', 'Edit diagram page'),

        (NULL , 'view_posts', 'View menu Posts'),
        (NULL , 'delete_post', 'Delete post'),
        (NULL , 'star_post', 'Star post'),

        (NULL , 'view_comments', 'View comments'),
        (NULL , 'approve_comment', 'Approve a comment'),
        (NULL , 'delete_comment', 'Delete a comment'),

        (NULL , 'view_pieces', 'View pieces'),
        (NULL , 'add_piece', 'Add a piece'),
        (NULL , 'edit_piece', 'Edit a piece'),
        (NULL , 'delete_piece', 'Delete a piece'),

        (NULL , 'view_diagram_manage', 'View menu Diagram Manage'),
        (NULL , 'manage_diagram', 'Manage Diagram'),

        (NULL , 'view_themes', 'View menu Themes'),
        (NULL , 'manage_themes', 'Manage themes'),

        (NULL , 'view_users', 'View menu Users'),
        (NULL , 'delete_user', 'Delete user'),
        (NULL , 'view_user_new', 'View menu user new'),
        (NULL , 'add_new_user', 'Add new user'),
        (NULL , 'view_profile', 'View menu Profile'),
        (NULL , 'edit_profile', 'Edit profile'),

        (NULL , 'view_data_backup', 'View data backup'),
        (NULL , 'operation_data_backup', 'Operation data backup'),
        (NULL , 'view_data_restore', 'view data restore'),
        (NULL , 'operation_data_restore', 'operation data restore'),
        (NULL , 'view_backup_theme_data', 'view backup theme data'),
        (NULL , 'operation_backup_theme_data', 'operation backup theme data'),

        (NULL , 'view_options_admin_uri', 'View options admin uri'),
        (NULL , 'edit_options_admin_uri', 'Edit options admin uri'),

        (NULL , 'view_options_mail', 'View options mail'),
        (NULL , 'edit_options_mail', 'Edit options mail'),

        (NULL , 'view_options_general', 'View menu Options General'),
        (NULL , 'edit_options_general', 'Edit options general'),
        (NULL , 'view_options_permalink', 'View menu Permalinks'),
        (NULL , 'edit_options_permalink', 'Edit menu Permalinks');");
    }

    // install the theme initial data
    public static function initial_data($theme = 'default') {
        $data_file = Kohana::find_file("themes/$theme/application/data", 'data');

        $db = new Database();
        $database_config = Kohana::config('database.default');
        $prefix = $database_config['table_prefix'];

        // clear out attachments
        $db->query("TRUNCATE TABLE `{$prefix}attachments`");
        // clear out comments
        $db->query("TRUNCATE TABLE `{$prefix}comments`");
        // clear out customvalues
        $db->query("TRUNCATE TABLE `{$prefix}customvalues`");
        // clear out customfields
        $db->query("TRUNCATE TABLE `{$prefix}customfields`");
        // clear out posts
        $db->query("TRUNCATE TABLE `{$prefix}posts`");
        // clear out diagram
        $db->query("TRUNCATE TABLE `{$prefix}diagrams`");

        if ($data_file) {
            require_once $data_file;
            $sourse_folder = APPPATH . "themes/$theme/application/data/webroot";
            $destination = DOCROOT . 'webroot';
            $old_folder = APPPATH . 'attach';
            
            // clean original attach folder
            if (is_dir($old_folder)) {
                file::clean_dir($old_folder, true);
            }

            // copy theme data attach to attach folder
            if (is_dir($sourse_folder)) {
                file::copy_dir($sourse_folder, $destination, true);
            }
            Diagram::cache_diagrams();
        }
    }
}
