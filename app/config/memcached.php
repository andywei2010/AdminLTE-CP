<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Memcached settings
| -------------------------------------------------------------------------
| Your Memcached servers can be specified below.
|
|	See: https://codeigniter.com/user_guide/libraries/caching.html#memcached
|
*/

// 多个机器以英文逗号分隔，如： SITE_REDIS_SERVER '192.168.0.225:11211, 123.59.142.74:11211'
// $memc_servers = explode(',', $_SERVER['SITE_MEMC_SERVER']);
// foreach ($memc_servers as $v)
// {
//     $v = explode(':', $v);
//     $config['memcached'][] = array(
//         'hostname' => $v[0],
//         'port'     => $v[1],
//         'weight'   => 1,
//         'prefix'   => ''
//     );
// }