<?php
/**
 * @script   database.php
 * @brief    爬虫独立数据库配置文件: 内置支持Medoo
 * @author   blogdaren<blogdaren@163.com>
 * @link     http://www.phpcreeper.com
 * @create   2022-04-08
 */


return array(
    'redis' => array(
        'host'      =>  '127.0.0.1',
        'port'      =>  6379,
        'database'  =>  '0',
        'auth'      =>  false,
        'pass'      =>  'guest',
        'prefix'    =>  'PHPCreeper', 
        'connection_timeout' => 5,
        'read_write_timeout' => 0,
        //'use_red_lock'     => true,   //默认使用更安全的分布式红锁
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


