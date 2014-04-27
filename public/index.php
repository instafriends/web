<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @version  3.1.9
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 * @link     http://laravel.com
 */



function onRequestStart() {
	$dat = getrusage();
	define('PHP_TUSAGE', microtime(true));
	define('PHP_RUSAGE', $dat["ru_utime.tv_sec"]*1e6+$dat["ru_utime.tv_usec"]);
}
 
function getCpuUsage() {
    $dat = getrusage();
    $dat["ru_utime.tv_usec"] = ($dat["ru_utime.tv_sec"]*1e6 + $dat["ru_utime.tv_usec"]) - PHP_RUSAGE;
    $time = (microtime(true) - PHP_TUSAGE) * 1000000;
 
    // cpu per request
    if($time > 0) {
        $cpu = sprintf("%01.2f", ($dat["ru_utime.tv_usec"] / $time) * 100);
    } else {
        $cpu = '0.00';
    }

    $cpu .= '<br>' . number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'mb';
 	$cpu .= '<br>' . ( microtime(true) - PHP_TUSAGE );
    return $cpu;
}

onRequestStart();

// --------------------------------------------------------------
// Set environment variable.
// --------------------------------------------------------------
$_SERVER['LARAVEL_ENV'] = 'development';

// --------------------------------------------------------------
// Tick... Tock... Tick... Tock...
// --------------------------------------------------------------
define('LARAVEL_START', microtime(true));

// --------------------------------------------------------------
// Indicate that the request is from the web.
// --------------------------------------------------------------
$web = true;

// --------------------------------------------------------------
// Set the core Laravel path constants.
// --------------------------------------------------------------
require '../paths.php';

// --------------------------------------------------------------
// Unset the temporary web variable.
// --------------------------------------------------------------
unset($web);

// --------------------------------------------------------------
// Launch Laravel.
// --------------------------------------------------------------
require path('sys').'laravel.php';

// if( !Request::ajax() ){ echo getCpuUsage(); }