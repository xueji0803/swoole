<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2019/9/29
 * Time: 下午1:51
 */
include "../config/env.php";
ini_set('default_socket_timeout', -1);

spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\','/',$class_name);
    require_once(__DIR__."/../".$class_name.'.php');
});
