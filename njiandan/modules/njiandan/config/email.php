<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * SwiftMailer driver, used with the email helper.
 * Valid drivers are: native, sendmail, smtp
 */
$config['driver'] = 'native';

/**
 * To use secure connections with SMTP, set "port" to 465 instead of 25.
 * To enable TLS, set "encryption" to "tls".
 *
 * Driver options:
 * @param   null    native: no options
 * @param   string  sendmail: executable path, with -bs or equivalent attached
 * @param   array   smtp: hostname, (username), (password), (port), (auth), (encryption)
 */
$config['options'] = '';
