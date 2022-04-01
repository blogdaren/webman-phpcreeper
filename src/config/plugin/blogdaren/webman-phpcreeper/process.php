<?php
//务必参照爬山虎插件文档来运行模拟DEMO：
//1. 首先在自己的app项目下手动创建有效的爬虫目录;
//2. 在爬虫目录内创建相应的生产器、下载器和解析器Hanlder
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


