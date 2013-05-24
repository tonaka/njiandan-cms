<?php
/**
 * Define the website environment status. When this flag is set to TRUE, some
 * module demonstration controllers will result in 404 errors.
 *
 */
define('IN_PRODUCTION', FALSE);

/**
 * Website application directory. This directory should contain your attach
 * backup, cache, configuration, languages, logs, themes and other resources.
 *
 * This path can be absolute or relative to this file.
 */
$njiandan_webroot = 'webroot';

/**
 * njiandan modules directory. This directory should contain all the modules used
 * by njiandan application. Modules are enabled and disabled by the application
 * configuration file.
 *
 * This path can be absolute or relative to this file.
 */
$njiandan_modules = 'njiandan/modules';

/**
 * Kohana system directory. 
 * This path can be absolute or relative to this file.
 */
$kohana_system = 'njiandan/system';

/**
 * Test to make sure that njiandan is running on PHP 5.2 or newer. Once you are
 * sure that your environment is compatible with njiandan, you can comment this
 * line out. When running an application on a new server, uncomment this line
 * to check the PHP version quickly.
 */
version_compare(PHP_VERSION, '5.2', '<') and exit('njiandan requires PHP 5.2 or newer.');

/**
 * Set the error reporting level. Unless you have a special need, E_ALL is a
 * good level for error reporting.
 */
error_reporting(E_ALL & ~E_STRICT);

/**
 * Turning off display_errors will effectively disable njiandan error display
 * and logging. You can turn off njiandan errors in webroot/config/config.php
 */
ini_set('display_errors', TRUE);

/**
 * If you rename all of your .php files to a different extension, set the new
 * extension here. This option can left to .php, even if this file has a
 * different extension.
 */
define('EXT', '.php');

//
// DO NOT EDIT BELOW THIS LINE, UNLESS YOU FULLY UNDERSTAND THE IMPLICATIONS.
// ----------------------------------------------------------------------------
//

$njiandan_pathinfo = pathinfo(__FILE__);
// Define the front controller name and docroot
define('DOCROOT', $njiandan_pathinfo['dirname'].DIRECTORY_SEPARATOR);
define('NJIANDAN',  $njiandan_pathinfo['basename']);
define('KOHANA',  $njiandan_pathinfo['basename']);

// If the front controller is a symlink, change to the real docroot
is_link(NJIANDAN) and chdir(dirname(realpath(__FILE__)));

// If njiandan folders are relative paths, make them absolute.
$njiandan_modules = file_exists($njiandan_modules) ? $njiandan_modules : DOCROOT.$njiandan_modules;
$kohana_system = file_exists($kohana_system) ? $kohana_system : DOCROOT.$kohana_system;

// Define application and system paths
define('APPPATH', str_replace('\\', '/', realpath($njiandan_webroot)).'/');
define('MODPATH', str_replace('\\', '/', realpath($njiandan_modules)).'/');
define('SYSPATH', str_replace('\\', '/', realpath($kohana_system)).'/');
// Define njiandan plugin path
define('PLUPATH', APPPATH . 'plugins/');
// Define njiandan user webroot directory name
define('WEBROOT', $njiandan_webroot);
define('SUBDIRECTORY', "");


// Clean up
unset($njiandan_webroot, $njiandan_modules, $kohana_system, $njiandan_pathinfo);

require SYSPATH.'core/Bootstrap'.EXT;
