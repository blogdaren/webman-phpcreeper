<?php
/**
 * @script   main.php
 * @brief    main.config
 * @author   blogdaren<blogdaren@163.com>
 * @link     http://www.phpcreeper.com
 * @create   2022-04-08
 */


return array(
    'language' => 'zh',             //设置语言环境，目前暂支持中文和英文 (可选项，默认zh)
    //全局任务配置参数：每条任务也可以单独配置自己的context成员，最终采用merge合并覆盖策略
    'task' => array(
        //'crawl_interval'  => 1,     //任务爬取间隔，单位秒，最小支持0.001秒 (可选项，默认1秒)
        //'max_depth'       => 1,     //最大爬取深度, 0代表爬取深度无限制 (可选项，默认1)
        //'max_number'      => 1000,  //任务队列最大task数量, 0代表无限制 (可选项，默认0)

        //当前Socket连接累计最大请求数，0代表无限制 (可选项，默认0)
        //如果当前Socket连接的累计请求数超过最大请求数时，
        //parser端会主动关闭连接，同时客户端会自动尝试重连
        //'max_request'     => 1000,

        //限定爬取站点域，留空表示不受限
        'limit_domains' => [],

        //根据预期任务总量和误判率引擎会自动计算布隆过滤器最优的bitmap长度以及hash函数的个数
        /*
         *'bloomfilter' => [
         *    'expected_insertions' => 10000,  //预期任务总量
         *    'expected_falseratio' => 0.01,   //预期误判率
         *],
         */

        //特别注意: 此处配置的context是全局context，我们也可以为每条任务设置私有context，
        //其上下文成员完全相同，全局context与任务私有context最终采用合并覆盖的策略，
        //context上下文成员主要是针对任务设置的，但同时拥有很大灵活性，可以间接影响依赖性服务，
        //比如可以通过设置context上下文成员来影响HTTP请求时的各种上下文参数 (可选项，默认为空)
        //HTTP引擎默认采用Guzzle客户端，兼容支持Guzzle所有的请求参数选项，具体参考Guzzle手册。
        //特别注意：个别上下文成员的用法是和Guzzle官方不一致的，一方面主要就是屏蔽其技术性概念，
        //另一方面面向开发者来说，关注点主要是能进行简单的配置即可，所以不一致的会注释特别说明。
        'context' => array(
            //是否缓存下载数据(可选项，默认false)
            'cache_enabled'   => true,                               
            //缓存下载数据存放目录  (可选项，默认位于系统临时目录下)
            'cache_directory' => sys_get_temp_dir() . '/DownloadCache4PHPCreeper/',
            //在特定的生命周期内是否允许重复抓取同一个URL资源（可选项，默认false）
            'allow_url_repeat' => true,
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
        ),
        //无头浏览器，如果是动态页面考虑启用，否则应当禁用 [默认使用chrome且为禁用状态]
        'headless_browser' => [
            'headless' => false, 
        ],
   ),
);

