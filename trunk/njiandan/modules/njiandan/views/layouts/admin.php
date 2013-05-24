<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php T::_e($page_title); ?> - Njiandan</title>
    <?php echo html::admin_stylesheet('style.css'); ?>
    <?php echo html::admin_script('jquery-1.3.2.min.js'); ?>
    <?php echo html::admin_script('script.js'); ?>
</head>
<body>
    <div id="header">
        <ul id="header-description">
            <li id="header-logo"></li>
            <li id="description-admin"><?php T::_e('Welcome'); ?>, <b> <?php echo $this->user->can('view_profile') ? html::admin_anchor('profile', $this->user->username) : $this->user->username; ?> </b></li>
            <li id="description-help"><a href="http://www.njiandan.com/help/" target="_blank"><?php T::_e('Help'); ?></a></li>
            <li id="description-site"><?php echo html::anchor('/', T::_('View site'), array('target'=>'_blank')); ?></li>
            <li id="description-logout"><?php echo html::admin_anchor('/login/logout/', T::_('Log Out')); ?></li>
        </ul>
    </div>
<?php
echo Tip::output();
echo AdminMenu::instance();
require(Kohana::find_file('views', sprintf('%s/%s', Router::$controller, Router::$method)));
?>
<div id="footer">
  <p> &#169; Njiandan - <a href="http://www.njiandan.com/suggestion/" target="_blank"><?php T::_e('Suggest a feature'); ?></a>
  </p>
  <span dir="ltr"><?php T::_e('Thank you for creating with njiandan.'); ?></span>
</div>
</body>
</html>
