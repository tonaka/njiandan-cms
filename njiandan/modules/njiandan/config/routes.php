<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package  Core
 *
 * Sets the default route to "welcome"
 */

$admin_uri = Kohana::config('njiandan.admin_uri');
Event::run('njiandan.routes', $config);
$config['_default'] = 'njiandan_template';
$config["$admin_uri"] = 'login';
$config["$admin_uri/(.+)"] = '$1';
$config['(.+)'] = 'njiandan_template/index/$1';

