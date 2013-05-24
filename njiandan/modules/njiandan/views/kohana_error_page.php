<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Njiandan &rsaquo; <?php T::_e($error); ?></title>
    <?php echo html::admin_stylesheet('error.css'); ?>
</head>
<body id="error-page">
<h1 id="logo"><?php echo html::admin_image('logo_login.gif', 'Njiandan'); ?></h1>

<h3><?php echo html::specialchars($error) ?></h3>
<p><?php echo html::specialchars($description) ?></p>

<?php if ( ! empty($line) AND ! empty($file)): ?>
<p><?php echo Kohana::lang('core.error_file_line', $file, $line) ?></p>
<?php endif ?>

<p><code class="block"><?php echo $message ?></code></p>
<?php if ( ! empty($trace)): ?>
<h3><?php echo Kohana::lang('core.stack_trace') ?></h3>
<?php echo $trace ?>
<?php endif ?>

</body>
</html>
