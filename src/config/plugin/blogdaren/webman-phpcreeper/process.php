<?php
/**
 * @script   process.php
 * @brief    自定义进程配置
 *
 * 务必参照爬山虎插件文档来运行DEMO
 *
 * 1. 首先在自己的应用项目下手动创建有效的爬虫目录, 比如: app/spider
 * 2. 在爬虫目录(app/spider)内创建相应的生产器、下载器和解析器Hanlder
 *
 * @author   blogdaren<blogdaren@163.com>
 * @link     http://www.phpcreeper.com
 * @create   2022-04-08
 */

use app\spider\Myproducer;
use app\spider\Mydownloader;
use app\spider\Myparser;

return [
    'myproducer' => [
        'handler'     => Myproducer::class,
        'listen'      => '',
        'count'       => 1,
        'constructor' => ['config' => 
            include('spider/global.php')
        ],
    ],
    'mydownloader' => [
        'handler'     => Mydownloader::class,
        'listen'      => '',
        'count'       => 1,
        'constructor' => ['config' => 
            include('spider/global.php')
        ],
    ],
    'myparser' => [
        'handler'     => Myparser::class,
        'listen'      => 'websocket://0.0.0.0:8888',
        'count'       => 1,
        'constructor' => ['config' => 
            include('spider/global.php')
        ],
    ],
];


