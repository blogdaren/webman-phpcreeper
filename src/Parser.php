<?php 
/**
 * @script   Parser.php
 * @brief    wrapper for PHPCreeper.Parser
 * @author   blogdaren<blogdaren@163.com>
 * @version  1.0.0
 * @modify   2022-04-01
 */

namespace Webman\PHPCreeper;

use PHPCreeper\Kernel\PHPCreeper;

class Parser extends \PHPCreeper\Parser
{
    /**
     * @param    array  $config
     *
     * @return   null
     */
    public function __construct($config)
    {
        //强制使用多worker运作模式
        PHPCreeper::$isRunAsMultiWorker = true;

        parent::__construct($config);

        $callback_maps = [
            'onParserStart',
            'onParserStop',
            'onParserReload',
            'onParserExtractField',
            'onParserFindUrl',
            'onParserMessage',
        ];

        $this->rebindSpiderWorkerProps();

        foreach($callback_maps as $callback)
        {
            if(method_exists($this, $callback))
            {
                $this->{$callback} = [$this, $callback];
            }
        }
    }

    /**
     * @brief    重新绑定部分属性: InternalWorker --> SpiderWorker
     *
     * @return   object
     */
    public function rebindSpiderWorkerProps()
    {
        $process_config = config('plugin.blogdaren.webman-phpcreeper.process');

        if(empty($process_config) || !is_array($process_config)) return $this;

        foreach($process_config as $name => $v)
        {
            if(!empty($v['handler']) && get_class($this) == $v['handler'])
            {
                $this->setName($name);

                if(!empty($v['count'])) 
                {
                    $this->setCount($v['count']);
                }

                !empty($v['listen']) && $this->setServerSocketAddress($v['listen']);
                !empty($v['context']) && $this->setServerSocketContext($v['context']);

                break;
            }
        }

        return $this;
    }


}


