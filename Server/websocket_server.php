<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2019/9/25
 * Time: 下午11:04
 */

class ws {

    public $ws = null;

    public $host = '0.0.0.0';

    public $port = 9001;
    /**
     * ws constructor.
     * 初始化
     */
    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server($this->host,$this->port);

        $this->ws->on('open',[$this,'onopen']);

        $this->ws->on('message',[$this,'onmessage']);

        $this->ws->on('close',[$this,'onclose']);

        $this->ws->start();
    }


    public function onopen($server,$request){
        echo "server: handshake success with fd{$request->fd}\n";
    }

    public function onmessage($server,$frame) {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd, "this is server");
    }

    public function onclose($ser, $fd) {
        echo "client {$fd} closed\n";
    }


}

$ws = new ws();