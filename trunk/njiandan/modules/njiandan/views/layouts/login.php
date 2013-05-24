<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head><title><?php echo Kohana::config('njiandan.site_title'); ?> › <?php T::_e('Login'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php echo html::admin_stylesheet('login.css'); ?>
<script type="text/javascript">
function focusit() {
    document.getElementById('user_login').focus();
}
window.onload = focusit;
</script>
</head><body class="login">
<div id="login"><h1>Njiandan</h1>
<?php
    echo !empty($errors) ? '<div class="errormsg" >' . $errors . '</div>' : null;
    echo !empty($tips) ? '<p class="message">' . $tips . '</p>' : null;
?>
<?php
require(Kohana::find_file('views', sprintf('%s/%s', Router::$controller, Router::$method)));
?>
</div>
</body></html>
