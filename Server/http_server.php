<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2019/9/25
 * Time: 下午11:25
 */
use Tools\WsRedis;

include_once "../config/lib.php";
class hp {

    public $host  = '0.0.0.0';

    public $port  = 8000;

    public $hp    = null;

    public $redis = null;

    /**
     * hp constructor.
     */
    public function __construct()
    {
        $this->hp = new Swoole\Http\Server($this->host,$this->port);

        $this->hp->on('connect',[$this,'onConnect']);

        /* 接收请求 */
        $this->hp->on('request',[$this,'onRequest']);


        $this->hp->on('close',[$this,'onClose']);

        $this->hp->set([
            'document_root'         => __DIR__.'/../Html',
            'enable_static_handler' => true,
            'log_file'              => '../Html/swoole.log'
        ]);

        $this->redis = WsRedis::getRedis();

        $this->hp->start();
    }


    public function onConnect( $serv, $fd)
    {
        /* 将用户的ID插入到redis的set中 */

        $this->redis->sAdd('fd',$fd);
    }

    public function onRequest($request, $response){
        $response->end("<h1>yes</h1>");
    }



    public function onClose($ser, $fd){
        $this->redis->sRem('fd',$fd);
    }


    public function onEnd(){
        echo "http response";
    }
}

$hpClass = new hp();