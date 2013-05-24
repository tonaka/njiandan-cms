<?php defined('SYSPATH') OR die('No direct access allowed.');

$config['admin_uri'] = 'admin';
$config['uri_model'] = 'default'; // index.php, htaccess, html (default is index.php)
$config['uri_optimize'] = 'id'; // id, uri

$config['post_uri'] = 'post'; // for post link

$config['site_title'] = 'Njiandan Is a fast and simple site tools!';
$config['site_description'] = 'Njiandan is a simple cms';
$config['site_url'] = 'http://www.njiandan.com';

$config['default_language'] = '';

$config['space_size'] = '100MB';
$config['database_size'] = '100MB';
$config['upload_max_filesize'] = '2M';
$config['editor_width'] = '100%';
$config['editor_height'] = '500';

$config['default_date_format'] = 'smart_time';
$config['is_comment_need_approve'] = '';

$config['version'] = 'Njiandan 1.3.0';

$config['reserve_uris'] = array(
    $config['admin_uri'],
    'feed',
    'feed/rss2',
    'feed/atom'
);
