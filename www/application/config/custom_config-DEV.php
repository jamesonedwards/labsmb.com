<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|    http://example.com/
|
*/
$config['base_url']    = "http://labsmb.localhost/"; // Overriden from config.php
$config['base_url_with_index'] = $config['base_url'] . config_item('index_page');

/*
|--------------------------------------------------------------------------
| Custom configuration
|--------------------------------------------------------------------------
|
*/

// Caching params.
$config['tweet_cache_seconds'] = 600;
$config['tweet_backup_cache_seconds'] = 86400; // Keep the backup cache for 24 hours.
$config['tumblr_cache_seconds'] = 600;
$config['pinterest_cache_seconds'] = 600;
$config['pinterest_backup_cache_seconds'] = 86400; // Keep the backup cache for 24 hours.
$config['flickr_photo_set_cache_seconds'] = 600;

// Twitter account whitelist.
$config['twitter_whitelist'] = array(
    'dpapworth',
    'jamesonedwards',
    'marymartin207',
    'bkdigitalgreen',
    'labsmb',
    'jameson_mb'
);

// Twitter search hashtag.
$config['twitter_search_hashtag'] = '#labsmb';

// Twitter credentials.
$config['twitter_screen_name'] = 'labsmb';
$config['twitter_consumer_token'] = 'xxx';
$config['twitter_consumer_secret'] = 'xxx';
$config['twitter_access_token'] = 'xxx';
$config['twitter_access_secret'] = 'xxx';

// Moderation tool credentials.
//$config['admin_user'] = 'admin';
//$config['admin_pass'] = 'lAbs-123';

// File uploads.
$config['file_upload_path'] = getcwd() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR;
$config['file_upload_base_url'] = $config['base_url'] . 'images/projects/';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|    0 = Disables logging, Error logging TURNED OFF
|    1 = Error Messages (including PHP errors)
|    2 = Debug Messages
|    3 = Informational Messages
|    4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 2; // Overridden from config.php

// Logging. Note: Used define() instead of $config so that these parameters could be used in static classes.
//define('LOGGING_LOGGLY_INPUT_URL', 'https://logs.loggly.com/inputs/09deed34-c6ac-418e-b58f-2a8c06e6b809'); // The Loggly endpoint.
//define('LOGGING_ECHO_MESSAGES', true); // Print messages to standard out.
//define('LOGGING_USE_SYSLOG', true); // If true then write to syslog, else post directly to Loggly.

/* End of file custom_config.php */
/* Location: ./system/application/config/custom_config.php */
