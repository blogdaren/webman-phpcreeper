## 简介

webman的爬山虎插件，[PHPCreeper | 爬山虎](http://www.phpcreeper.com)：让爬取工作变得更加简单。


## 安装
```
composer require blogdaren/webman-phpcreeper
```

## 使用说明
* 编写一个爬虫非常简单: 配置搞定以后，只需要在对应容器内的`onXXXX`回调方法内编写业务逻辑即可。
* 由于爬虫应用相对WEB应用而言比较独立，所以app内的爬虫目录结构请自行部署。
* 首先在自己的app项目下手动创建有效的爬虫目录。
* 在爬虫目录内创建相应的容器【生产器、下载器和解析器】句柄类Hanlder。

## 举个栗子

> 模拟需求是抓取未来7天内的天气预报 

1、创建爬虫目录：app/spider    

2、创建生产器句柄类文件 app/spider/Myproducer.php
```
<?php 
/**
 * @script   Myproducer.php
 * @brief    生产器Handler
 * @author   blogdaren<blogdaren@163.com>
 * @version  1.0.0
 * @modify   2022-04-01
 */

namespace app\spider;

use Workerman\Timer;

class Myproducer extends \Webman\PHPCreeper\Producer
{
    /**
     * @brief   抓取未来7天内的天气预报DEMO
     *
     * @return  mixed 
     */
    public function makeTask()
    {   
        //Create One Task
        $task = array(
            'url' => 'http://www.weather.com.cn/weather/101010100.shtml',
            'rule' => array(
                'time' => ['div#7d ul.t.clearfix h1',      'text'],
                'wea'  => ['div#7d ul.t.clearfix p.wea',   'text'],
                'tem'  => ['div#7d ul.t.clearfix p.tem',   'text'],
                'wind' => ['div#7d ul.t.clearfix p.win i', 'text'],
            ),  
            'context' => array(
                'cache_enabled'     => true,
                'cache_directory'   => '/tmp/DownloadCache4PHPCreeper/download/',
                'allow_url_repeat'  => true,
            ),  
        );  

        $this->newTaskMan()->createTask($task);
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
        //$this->makeTask();
        Timer::add(2, [$this, "makeTask"], [], true);
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
 * @version  1.0.0
 * @modify   2022-04-01
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
 * @version  1.0.0
 * @modify   2022-04-01
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
        /*
         *$rule = array(
         *    'hotline' => ['div.qxfw-body > p:eq(1)', 'text'],
         *);
         *$data = $parser->extractor->setHtml($download_data)->setRule($rule)->extract();
         *pprint($data, __METHOD__);
         */
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
* 爬虫自有的配置文件要保持相对独立;
* process配置内的关于进程构造函数的配置一般不要动;
* 目前需要手动设置下载器的$downloader->setClientSocketAddress([]);
* 依赖redis服务，所以务必启动redis-server;
* 按照规范每一个独立的容器实例最好对应唯一的一个Handler;
* 目前Debug界面第5列数据【即进程编号列】显示有异常，由于爬山虎内核实现问题，造成目前这个问题暂不可抗力，后续只能寄托webman更新一处代码，待有结果了再来更新下，不过对抓取业务没有任何影响;


## 爬山虎技术文档
* 爬山虎中文官方网站：[http://www.phpcreeper.com](http://www.phpcreeper.com)
* 中文开发文档主节点：[http://www.blogdaren.com/docs/](http://www.blogadren.com/docs/)
* 中文开发文档备节点：[http://www.phpcreeper.com/docs/](http://www.phpcreeper.com/docs/)
* 爬山虎内核项目地址：[https://github.com/blogdaren/PHPCreeper](https://github.com/blogdaren/PHPCreeper)


