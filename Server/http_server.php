<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2019/9/25
 * Time: 下午11:25
 */

class hp {

    public $host = '0.0.0.0';

    public $port = 8000;

    public $hp   = null;

    /**
     * hp constructor.
     */
    public function __construct()
    {
        $this->hp = new Swoole\Http\Server($this->host,$this->port);

        $this->hp->on('request',[$this,'onRequest']);

        $this->hp->set([
            'document_root'         => '/usr/local/var/www/swoole/Html',
            'enable_static_handler' => true
        ]);

        $this->hp->start();
    }

    public function onRequest($request, $response){
        echo "http request";

        $response->end("<h1>yes</h1>");
    }

    public function onEnd(){
        echo "http response";
    }
}

$hpClass = new hp();