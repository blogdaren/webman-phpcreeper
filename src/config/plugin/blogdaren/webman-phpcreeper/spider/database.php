<?php
/**
 * @script   database.php
 * @brief    爬虫独立数据库配置文件: 内置支持Medoo
 * @author   blogdaren<blogdaren@163.com>
 * @version  1.0.0
 * @modify   2022-04-01
 */

return array(
    'redis' => array(
        'prefix' => 'Demo',
        'host'   => '127.0.0.1',
        'port'   => 6379,
        'database' => 0,
    ),
    'dbo' => array(
        'test' => array(
            'database_type' => 'mysql',
            'database_name' => 'test',
            'server'        => '127.0.0.1',
            'username'      => 'root',
            'password'      => 'root',
            'charset'       => 'utf8'
        ),
    ),

);


