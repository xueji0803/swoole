<?php
use Tools\WsRedis;

/**
 * Created by PhpStorm.
 * User: james
 * Date: 2019/9/25
 * Time: 下午11:04
 */
include_once "../config/lib.php";

class ws {

    public $ws = null;

    public $host = '0.0.0.0';

    public $port = 9001;

    public $redis = null;
    /**
     * ws constructor.
     * 初始化
     */
    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server($this->host,$this->port);

        $this->ws->set(
            [
               'log_file'  => 'swoole.log'
            ]
        );

        $this->ws->on('open',[$this,'onopen']);

        $this->ws->on('message',[$this,'onmessage']);

        $this->ws->on('close',[$this,'onclose']);

        $this->redis = WsRedis::getRedis();

        $this->ws->start();
    }


    public function onopen($server,$request){
        echo "server: handshake success with fd{$request->fd}\n";
        /* 将用户的ID插入到redis的set中 */
        $this->redis->sAdd('fd',$request->fd);
    }

    /* 转发用户消息 */
    public function onmessage($server,$frame) {
        $list = $this->redis->sMembers('fd');

        foreach ($list as $k ) {
            try {
                $server->push($k, "think you for ".$frame->data." | ".$frame->fd);
            } catch (\Throwable $t) {
                continue;
            }
        }

    }

    public function onclose($ser, $fd) {
        /* 将当前用户的线程从redis中去除 */
        $this->redis->sRem('fd',$fd);
    }


}


$ws = new ws();


