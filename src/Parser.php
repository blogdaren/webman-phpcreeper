<?php 
/**
 * @script   Parser.php
 * @brief    wrapper for PHPCreeper.Parser
 * @author   blogdaren<blogdaren@163.com>
 * @version  1.0.1
 * @modify   2022-04-08
 */

namespace Webman\PHPCreeper;

use PHPCreeper\Kernel\PHPCreeper;

class Parser extends \PHPCreeper\Parser
{
    /**
     * @brief procuder callback 
     */
    const CALLBACK_MAPS = [
        'onParserStart',
        'onParserStop',
        'onParserReload',
        'onParserExtractField',
        'onParserFindUrl',
        'onParserMessage',
    ];

    /**
     * @brief webman worker     
     */
    private $_worker = null;

    /**
     * @param    array  $config
     *
     * @return   null 
     */
    public function __construct($config)
    {
        //强制使用多worker运作模式
        PHPCreeper::$isRunAsMultiWorker = true;

        //必须调用
        parent::__construct($config);

        foreach(self::CALLBACK_MAPS as $callback)
        {
            if(method_exists($this, $callback))
            {
                $this->{$callback} = [$this, $callback];
            }
        }
    }

    /**
     * @brief    onWorkerStart  
     *
     * @param    object $worker
     *
     * @return   null
     */
    public function onWorkerStart($worker)
    {
        empty($this->_worker) && $this->_worker = $worker;
        $this->rebindSpiderWorkerProps();
        parent::onWorkerStart($this);
    }

    /**
     * @brief    重新绑定部分属性: InternalWorker --> SpiderWorker
     *
     * @return   object
     */
    public function rebindSpiderWorkerProps()
    {
        $this->id = $this->_worker->id;
        $this->setCount($this->_worker->count);
        $this->setName($this->_worker->name);
        $this->setServerSocketAddress($this->_worker->getSocketName());

        //注意：context option需要透析配置文件单独绑定
        $process_config = config('plugin.blogdaren.webman-phpcreeper.process');
        if(empty($process_config) || !is_array($process_config)) return $this;

        foreach($process_config as $name => $v)
        {
            if(!empty($v['handler']) && get_class($this) == $v['handler'])
            {
                !empty($v['context']) && $this->setServerSocketContext($v['context']);
                break;
            }
        }

        return $this;
    }

}

