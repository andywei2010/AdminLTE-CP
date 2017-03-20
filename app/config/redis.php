<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Redis 服务配置
 * 多个机器以英文逗号分隔，如： SITE_REDIS_SERVER '192.168.0.225:6379, 123.59.142.74:6379'
 */
// $redis_servers = explode(',', $_SERVER['SITE_REDIS_SERVER']);
// foreach ($redis_servers as $v)
// {
//     $v = explode(':', $v);
//     $config['redis'][] = array(
//         'hostname' => $v[0],
//         'port'     => $v[1],
//     );
// }