<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

//权限认证
$hook['post_controller_constructor'][] = array(
    'class'    => 'Rbac',
    'function' => 'auth',
    'filename' => 'rbac.php',
    'filepath' => 'hooks'
);

//记录操作日志
$hook['post_controller_constructor'][] = array(
    'class'    => 'Rbac',
    'function' => 'log',
    'filename' => 'rbac.php',
    'filepath' => 'hooks'
);
