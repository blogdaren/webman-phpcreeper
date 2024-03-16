## 简介

webman的爬山虎插件，[PHPCreeper | 爬山虎](https://github.com/blogdaren/PHPCreeper)：让爬取工作变得更加简单。


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

> 模拟需求是抓取未来7天内北京的天气预报 

1、创建爬虫目录：app/spider    

2、创建生产器句柄类文件 app/spider/Myproducer.php
```
<?php 
/**
 * @script   Myproducer.php
 * @brief    生产器Handler
 * @author   blogdaren<blogdaren@163.com>
 * @modify   2022-04-01
 */

namespace app\spider;

use PHPCreeper\Timer;
use PHPCreeper\Crontab;

class Myproducer extends \Webman\PHPCreeper\Producer
{
    /**
     * @brief   抓取未来7天内北京的天气预报
     *
     * @return  mixed 
     */
    public function makeTask()
    {   
        //注意：这里说的版本并不是爬山虎插件的版本，而是爬山虎引擎的版本.
        //注意：这里说的版本并不是爬山虎插件的版本，而是爬山虎引擎的版本.
        //注意：这里说的版本并不是爬山虎插件的版本，而是爬山虎引擎的版本.


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


        $task = array(
            'active' => true,       //是否激活当前任务，只有配置为false才会冻结任务，默认true
            'url'    => 'http://www.weather.com.cn/weather/101010100.shtml',
            "rule" => array(        //如果该字段留空默认将返回原始下载数据
                'time' => ['div#7d ul.t.clearfix h1',      'text', [], 'function($field_name, $data){
                    return "具体日子: " . $data;
                }'],                //关于回调字符串的用法务必详看官方手册
                'wea'  => ['div#7d ul.t.clearfix p.wea',   'text'],
                'tem'  => ['div#7d ul.t.clearfix p.tem',   'text'],
            ), 
            'rule_name' =>  '',     //如果留空将使用md5($task_id)作为规则名
            'refer'     =>  '',
            'type'      =>  'text', //可以自由设定类型
            'method'    =>  'get',
            'context'   =>  $context??[], //任务私有context，其上下文成员与全局context完全相同，最终会采用合并覆盖策略
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
```
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
```
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
        !empty($fields) && pprint($fields, __METHOD__);
    }
}
```
5、修改插件的process配置文件设置对应的Handler
```
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
* ~~目前Debug界面第5列数据【即进程编号列】显示有异常，待有结果了再来更新下，不过对抓取业务没有任何影响;~~     
  **【已经解决：版本需要更新到 >=1.01】**


## 爬山虎技术文档
* 爬山虎中文官方网站：[http://www.phpcreeper.com](http://www.phpcreeper.com)
* 中文开发文档主节点：[http://www.blogdaren.com/docs/](http://www.blogadren.com/docs/)
* 中文开发文档备节点：[http://www.phpcreeper.com/docs/](http://www.phpcreeper.com/docs/)
* 爬山虎开源项目地址：[https://github.com/blogdaren/PHPCreeper](https://github.com/blogdaren/PHPCreeper)


