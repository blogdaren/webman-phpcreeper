<?php
/**
 * @script   main.php
 * @brief    main.config
 * @author   blogdaren<blogdaren@163.com>
 * @version  1.0.0
 * @modify   2022-04-01
 */

return array(
    'language' => 'zh',
    //默认初始任务
    'task' => array(
        'method'          => 'get',
        'crawl_interval'  => 1,
        'max_depth'       => 1,
        'max_number'      => 1000,
        'max_request'     => 1000,
        'compress'  => array(
            'enabled'   =>  true,
            'algorithm' => 'gzip',
        ),
        'limit_domains' => array(
        ),
        'url' => array(
        ),
        'rule' => array(
        ),

        //支持其他N多配置参数......
        'context' => array(
            //'cache_enabled'     => true,
            //'cache_directory'   => '/tmp/DownloadCache4PHPCreeper/download/',
        ),
   ),
   'logger' => array(
       'PRODUCER' => array(
           //'log_disable_level' => array('info', 'debug', 'warn', 'error', 'crazy'),
           //'log_file_path' => '/tmp/logs/data/producer.log',
       ),
       'DOWNLOADER' => array(
           //'log_disable_level' => array('debug', 'warn'),
           //'log_file_path' => '/tmp/logs/data/downloader.log',
       ),
       'PARSER' => array(
           //'log_disable_level' => array('debug'),
           //'log_file_path' => '/tmp/logs/data/parser.log',
       ),
   ),
);



