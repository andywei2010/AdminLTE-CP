<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Rbac配置文件
 */

//不需要登录的资源模块
$config['notneedlogin'] = array(
    'login',
    'api',
);

//不需要认证的 array(模块,方法)
$config['notneedauth'] = array(
    array('', ''),
    array('home', 'index'),
    array('home', 'main'),
    array('home', 'keepalive'),
    array('api', 'upload'),
);

//需要记录日志的操作(对应 rbac_function方法名)
$config['rbac_log_function'] = array(
    'add',
    'edit',
    'enable',
    'delete',
    'send',
    'export',
);

/**
 * 功能列表，类定义。同时显示到菜单，顺序决定菜单中排序。
 */
$config['rbac_class'] = array(
    //显示为一级菜单
    'master' => '用户管理',
    'action' => '系统管理',

    //显示到某一个一级菜单下
    'role' => '角色管理',
    'log' => '日志管理',
);

//操作列表，方法名
$config['rbac_function'] = array(
    'view'       => '浏览',
    'viewdetail' => '查看详情',
    'add'        => '添加',
    'edit'       => '修改',
    'enable'     => '启用/禁用',
    'delete'     => '删除',
    'export'     => '导出',
);

//用户状态
$config['rbac_master_status'] = array(
    -1=> '删除',
    0 => '禁用',
    1 => '正常',
);

//用户性别
$config['rbac_master_sex'] = array(
    1 => '男',
    2 => '女',
);















