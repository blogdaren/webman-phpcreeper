<?php
/**
 * @script   global.php
 * @brief    global config
 * @author   blogdaren<blogdaren@163.com>
 * @version  1.0.0
 * @modify   2022-04-01
 */

/** !!! hey, don't try to modify this file unless you understand what u are doing !!! **/
/** !!! hey, don't try to modify this file unless you understand what u are doing !!! **/
/** !!! hey, don't try to modify this file unless you understand what u are doing !!! **/

/** !!! hey, don't try to delete this line unless you understand what u are doing !!! **/
//!defined('USE_PHPCREEPER_APPLICATION_FRAMEWORK') && define('USE_PHPCREEPER_APPLICATION_FRAMEWORK', 1);
/** !!! hey, don't try to delete this line unless you understand what u are doing !!! **/

$main_config = $db_config = [];
$main_config_file = __DIR__ . '/main.php';
$db_config_file   = __DIR__ . '/database.php';

if(is_file($main_config_file) && file_exists($main_config_file))
{
    $main_config = require($main_config_file);
}

if(is_file($db_config_file) && file_exists($db_config_file))
{
    $db_config = require($db_config_file);
}

/*
 *$global_config = [
 *    'main'      =>  $main_config,
 *    'database'  =>  $db_config,
 *];
 */

return array_merge($main_config, $db_config);


/** !!! hey, don't try to modify this file unless you understand what u are doing !!! **/
/** !!! hey, don't try to modify this file unless you understand what u are doing !!! **/
/** !!! hey, don't try to modify this file unless you understand what u are doing !!! **/


