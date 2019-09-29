<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2019/9/29
 * Time: 上午10:08
 */
namespace Tools;
class WsRedis {

    private static $_instance;

    private function __construct()
    {
        /* 将用户的ID插入到redis的set中 */
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379,3600);
        self::$_instance = $redis;
    }

    public static function getRedis()
    {
        if (!(self::$_instance instanceof \Redis )) {
            new self();
        }

        return self::$_instance;
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }


}