## 简介

webman的爬山虎插件，[PHPCreeper | 爬山虎](https://github.com/blogdaren/PHPCreeper)：让爬取工作变得更加简单高效。


## 安装
```
composer require blogdaren/webman-phpcreeper
```

## 使用说明
* 首先要明确一个概念：爬山虎有三种容器分别是：生产器、下载器、解析器。
* 编写一个爬虫非常简单: 配置搞定以后，只需要在对应容器内的`onXXXX`回调方法内编写业务逻辑即可。
* 由于爬虫应用相对WEB应用而言比较独立，所以app内的爬虫目录结构建议自行独立部署。
* 首先在自己的app项目下手动创建有效的爬虫目录, 比如：app/spider。
* 然后在爬虫目录内(app/spider)创建相应的容器句柄类Hanlder。
* 最后在对应容器内的`onXXXX`回调方法内编写业务逻辑.

## 举个例子

模拟抓取未来3天内北京的天气预报 

## 开发步骤

1、创建爬虫目录：app/spider    

2、创建生产器句柄类文件 app/spider/Myproducer.php

```php
<?php 
/**
 * @script   Myproducer.php
 * @brief    生产器Handler
 * @author   blogdaren<blogdaren@163.com>
 * @create   2022-04-01
 */

namespace app\spider;

use PHPCreeper\Timer;
use PHPCreeper\Crontab;

class Myproducer extends \Webman\PHPCreeper\Producer
{
    /**
     * @brief   生产任务
     *
     * @return  mixed 
     */
    public function makeTask()
    {   
        //注意：本方法中所说的版本并不是webman爬山虎插件的版本，而是爬山虎的版本.
        //注意：本方法中所说的版本并不是webman爬山虎插件的版本，而是爬山虎的版本.
        //注意：本方法中所说的版本并不是webman爬山虎插件的版本，而是爬山虎的版本.

        //在v1.6.0之前，爬山虎主要使用OOP风格的API来创建任务：
        //$producer->newTaskMan()->setXXX()->setXXX()->createTask()
        //$producer->newTaskMan()->setXXX()->setXXX()->createTask($task)
        //$producer->newTaskMan()->setXXX()->setXXX()->createMultiTask()
        //$producer->newTaskMan()->setXXX()->setXXX()->createMultiTask($task)

        //自v1.6.0开始，爬山虎提供了更加短小便捷的API来创建任务, 而且参数类型更加丰富：
        //注意：仅仅只是扩展，原有的API依然可以正常使用，提倡扩展就是为了保持向下兼容。
        //1. 单任务API：$task参数类型可支持：[字符串 | 一维数组]
        //1. 单任务API：$producer->createTask($task);
        //2. 多任务API：$task参数类型可支持：[字符串 | 一维数组 | 二维数组]
        //2. 多任务API：$producer->createMultiTask($task);

        //使用字符串：不推荐使用，配置受限，需要自行处理抓取结果
        //$task = "http://www.weather.com.cn/weather/101010100.shtml";
        //$producer->createTask($task);
        //$producer->createMultiTask($task);

        //任务私有context，其上下文成员与全局context完全相同，最终会采用合并覆盖策略
        $task_private_context = array(
            //是否缓存下载数据(可选项，默认false)
            'cache_enabled'   => true,
            //缓存下载数据存放目录  (可选项，默认位于系统临时目录下)
            'cache_directory' => sys_get_temp_dir() . '/DownloadCache4PHPCreeper/',
            //在特定的生命周期内是否允许重复抓取同一个URL资源（可选项，默认false）
            'allow_url_repeat' => true,
            //要不要跟踪完整的HTTP请求参数，开启后终端会显示完整的请求参数 [默认false]
            'track_request_args' => true,  
            //要不要跟踪完整的TASK数据包，开启后终端会显示完整的任务数据包 [默认false]
            'track_task_package' => true,
            //在v1.6.0之前，如果rulename留空，默认会使用 md5($task_url)作为rulename
            //自v1.6.0开始，如果rulename留空，默认会使用 md5($task_id) 作为rulename
            //所以这个配置参数是仅仅为了保持向下兼容，但是不推荐使用，因为有潜在隐患
            //换句话如果使用的是v1.6.0之前旧版本，那么才有可能需要激活本参数 [默认false]
            'force_use_md5url_if_rulename_empty' => false,
            //强制使用多任务创建API的旧版本参数风格，保持向下兼容，不再推荐使用 [默认false]
            'force_use_old_style_multitask_args' => false,
            //cookies成员的配置格式和guzzle官方不大一样，屏蔽了cookieJar，取值[false|array]
            'cookies' => [
                //'domain' => 'domain.com',
                //'k1' => 'v1',
                //'k2' => 'v2',
            ],  
            //除了内置参数之外，还可以自由配置自定义参数，在上下游业务链应用场景中十分有用
            'user_define_arg1' => 'user_define_value1',
            'user_define_arg2' => 'user_define_value2',
            //更多参数请参看手册
        );


        $task = array(
            'active' => true,       //是否激活当前任务，只有配置为false才会冻结任务，默认true
            'url'    => 'http://www.weather.com.cn/weather/101010100.shtml',
            "rule" => array(        //如果该字段留空默认将返回原始下载数据
                '日子' => ['div#7d ul.t.clearfix h1',      'text', [], 'function($field_name, $data){
                    return  date("Y-m-d") . " | " . $data;
                }'],                //关于回调字符串的用法务必详看官方手册
                '天气'  => ['div#7d ul.t.clearfix p.wea',   'text'],
                '温度'  => ['div#7d ul.t.clearfix p.tem',   'text'],
            ),
            'rule_name' =>  '',     //如果留空将使用md5($task_id)作为规则名
            'refer'     =>  '',
            'type'      =>  'text', //可以自由设定类型
            'method'    =>  'get',
            'context'   =>  $task_private_context, //任务私有context，其上下文成员与全局context完全相同，最终会采用合并覆盖策略
        );

        $this->createTask($task);
    }  

    /**
     * @brief    onProducerStart  
     *
     * @param    object $producer
     *
     * @return   mixed
     */
    public function onProducerStart($producer)
    {
        $this->makeTask();

        //使用Timer定时器创建任务
        //Timer::add(5, [$this, "makeTask"], [], true);

        //使用Crontab定时器创建任务
        //new Crontab('*/5 * * * * *', function(){
            //$this->makeTask();
        //});
    }

    /**
     * @brief    onProducerStop
     *
     * @param    object $producer
     *
     * @return   mixed
     */
    public function onProducerStop($producer)
    {
    }

    /**
     * @brief    onProducerReload     
     *
     * @param    object $producer
     *
     * @return   mixed
     */
    public function onProducerReload($producer)
    {
    }

}

```

3、创建下载器句柄类文件 app/spider/Mydownloader.php
```php
<?php 
/**
 * @script   Mydownloader.php
 * @brief    下载器Handler
 * @author   blogdaren<blogdaren@163.com>
 * @create   2022-04-01
 */

namespace app\spider;

class Mydownloader extends \Webman\PHPCreeper\Downloader
{
    /**
     * @brief    onDownloaderStart  
     *
     * @param    object $downloader
     *
     * @return   mixed
     */
    public function onDownloaderStart($downloader)
    {
        $downloader->setClientSocketAddress([
            'ws://127.0.0.1:8888',
        ]);
    }

    /**
     * @brief    onDownloaderStop
     *
     * @param    object $downloader
     *
     * @return   mixed
     */
    public function onDownloaderStop($downloader)
    {
    }

    /**
     * @brief    onDownloaderReload     
     *
     * @param    object $downloader
     *
     * @return   mixed
     */
    public function onDownloaderReload($downloader)
    {
    }

    /**
     * @brief    onDownloaderMessage
     *
     * @param    object $downloader
     * @param    string $parser_reply
     *
     * @return   mixed
     */
    public function onDownloaderMessage($downloader, $parser_reply)
    {
        //pprint($parser_reply, __METHOD__);
    }

    /**
     * @brief    onBeforeDownload
     *
     * @param    object $downloader
     * @param    array  $task
     *
     * @return   mixed
     */
    public function onBeforeDownload($downloader, $task)
    {
        //$downloader->httpClient->setConnectTimeout(3);
        //$downloader->httpClient->setTransferTimeout(10);
        //$downloader->httpClient->setHeaders(array());
        //$downloader->httpClient->setProxy('http://180.153.144.138:8800');
    }

    /**
     * @brief    onStartDownload
     *
     * @param    object $downloader
     * @param    array  $task
     *
     * @return   mixed 
     */
    public function onStartDownload($downloader, $task)
    {
    }

    /**
     * @brief    onAfterDownload
     *
     * @param    object $downloader
     * @param    array  $download_data
     * @param    array  $task
     *
     * @return   mixed
     */
    public function onAfterDownload($downloader, $download_data, $task)
    {
        //pprint($downloader->getDbo('test'), __METHOD__);
    }
}
```

4、创建解析器句柄类文件 app/spider/Myparser.php
```php
<?php 
/**
 * @script   Myparser.php
 * @brief    解析器Handler
 * @author   blogdaren<blogdaren@163.com>
 * @create   2022-04-01
 */

namespace app\spider;

class Myparser extends \Webman\PHPCreeper\Parser
{
    /**
     * @brief    onParserStart  
     *
     * @param    object $parser
     *
     * @return   mixed
     */
    public function onParserStart($parser)
    {
    }

    /**
     * @brief    onParserStop
     *
     * @param    object $parser
     *
     * @return   mixed 
     */
    public function onParserStop($parser)
    {
    }

    /**
     * @brief    onParserReload
     *
     * @param    object $parser
     *
     * @return   mixed
     */
    public function onParserReload($parser)
    {
    }

    /**
     * @brief    onParserMessage
     *
     * @param    object $parser
     * @param    object $connection
     * @param    string $download_data
     *
     * @return   mixed
     */
    public function onParserMessage($parser, $connection, $download_data)
    {
         //pprint(strlen($download_data), __METHOD__);
    }

    /**
     * @brief    onParserFindUrl
     *
     * @param    object $parser
     * @param    string $url
     *
     * @return   mixed 
     */
    public function onParserFindUrl($parser, $url)
    {
        return $url;
    }

    /**
     * @brief    onParserExtractField
     *
     * @param    object $parser
     * @param    string $download_data
     * @param    array  $fields
     *
     * @return   mixed
     */
    public function onParserExtractField($parser, $download_data, $fields)
    {
        !empty($fields) && pprint($fields[$parser->task['rule_name']]);
    }
}
```
5、修改插件的process配置文件设置对应的Handler
```php
<?php
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
```

## 注意事项
* 爬虫应用自有的配置文件要保持相对独立;
* process配置内的关于进程构造函数的配置一般不要动;
* 目前需要手动设置下载器的$downloader->setClientSocketAddress([]);
* 依赖redis服务，所以务必启动redis-server;
* 按照规范每一个独立的容器实例最好对应唯一的一个Handler;
* 爬山虎新版新增了许多新特性和API，而且完全向下兼容，所以建议将本插件和爬山虎更新到最新版。


## 爬山虎技术文档
* 爬山虎中文官方网站：[http://www.phpcreeper.com](http://www.phpcreeper.com)
* 中文开发文档主节点：[http://www.blogdaren.com/docs/](http://www.blogadren.com/docs/)
* 中文开发文档备节点：[http://www.phpcreeper.com/docs/](http://www.phpcreeper.com/docs/)
* 爬山虎开源项目地址：[https://github.com/blogdaren/PHPCreeper](https://github.com/blogdaren/PHPCreeper)


