<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Njiandan &rsaquo; <?php T::_e($page_title); ?></title>
    <?php echo html::admin_stylesheet('error.css'); ?>
</head>
<body id="error-page">
<h1 id="logo"><?php echo html::admin_image('logo_login.gif', 'Njiandan'); ?></h1>
<?php
require(Kohana::find_file('views', sprintf('%s/%s', Router::$controller, Router::$method)));
?>
</body>
</html>
