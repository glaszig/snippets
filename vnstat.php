<?php

/**
 *
 * vnstat web script
 * by glaszig@gmail.com
 *
 *
 * USAGE
 *
 * call this script as follows:
 *   vnstat.php?i=<interface-name>&c=<command>
 * where <interface-name> is to be substituted with one of the monitored interfaces
 * and <command> with one of the allowed commands, respectively
 *
 */


/**
 *
 * CONFIGURATION
 *
 * INTERFACES => space-seperated list of configured interfaces
 * COMMANDS   => space-seperated list of available commands
 * USE_CACHE  => instructs vnstati to use built-in caching functionallity
 * CACHE_TIME => time to cache an image in minutes
 * CACHE_DIR  => directory to store the cached files (defaults to /tmp)
 *
 */
define('INTERFACES', 'ng0 fxp1 ath0');
define('COMMANDS', 's d m hs vs t h');
define('USE_CACHE', true);
define('CACHE_TIME', 5);
#define('CACHE_DIR', '/tmp');


### LEAVE THE FOLLOWING UNTOUCHED


// sanity checks
if(!in_array($_GET['i'], explode(' ', INTERFACES)))
    die('invalid interface.');

if(!in_array($_GET['c'], explode(' ', COMMANDS)))
    die('invalid command.');

// default output file
$outfile = '-'; // stdout

// check for cache dir
if(constant('USE_CACHE')):
    defined('CACHE_DIR') or define('CACHE_DIR', '/tmp');
    defined('CACHE_TIME') or define('CACHE_TIME', 5);
    if(!is_dir(CACHE_DIR)) die('cache directory doesn\'t exist.');
    if(!is_writable(CACHE_DIR)) die('can\'t write to cache directory.');
    // create a file name
    $outfile = CACHE_DIR.'/vnstati-'.md5($_GET['i'].$_GET['c']).'.png';
endif;

// do the math
$output = shell_exec('/usr/local/bin/vnstati -nh -ne -c '.intval(CACHE_TIME).' -'.$_GET['c'].' -o '.$outfile.' -i '.$_GET['i']);

// put image through
header('Content-Type: image/png');
header('Content-Disposition: inline; filename=vnstat-'.$_GET['i'].'.png');
if(USE_CACHE)
    readfile($outfile);
else
    echo $output;

