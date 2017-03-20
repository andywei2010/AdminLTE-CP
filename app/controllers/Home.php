<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('smarty');
	}

	public function index()
	{
		$this->menu();
		$this->smarty->display('home/index.html');
	}

    public function main()
    {
        $this->load->model(array('User_model'));
        $user = $this->User_model->login_info();
        $user = $this->User_model->get_user_by_id($user['masterid']);
        //角色
        $user['role'] = $this->User_model->get_user_role($user['masterid']);
        $this->smarty->assign('user', $user);
        $this->smarty->display('home/main.html');
    }

	/**
     * 首页左侧在菜单
     * @param string $classid
     * @param string $functionid
     */
    public function menu($classid = '', $functionid = '') 
    {
        $this->load->model(array('User_model', 'Action_model'));
        $user = $this->User_model->login_info();

        //生成用户菜单
        $action = $this->User_model->get_my_action($user['masterid']);
        //取资源
        $this->load->config('rbac');
        $rbac_class = $this->config->item('rbac_class');

        $menu_class = array(); //用户有权限的资源
        $menu = array(); //用户有权限的功能
        if ($action) foreach ($action as $a) {
            $v = $this->Action_model->get_action_by_classid_functionid($a[0], $a[1]);
            if ($v['ismenu']) {
                $url = '/' . $v['classid'] . '/' . $v['functionid'] . '/';
                $menu[$v['ismenu']][] = array($url, $v['actionname']);
            }
        }
        //根据权限过滤$rbac_class中的没有权限的大项
        foreach ($rbac_class as $k => $v) {
        	if (array_key_exists($k, $menu)) {
        		$menu_class[$k] = $v;
        	}
        }
        // print_r($menu_class);echo "<br>";
        // print_r($menu);echo "<br>";exit;
        $this->smarty->assign('classid', $classid);
        $this->smarty->assign('functionid', $functionid);
        $this->smarty->assign('menu_class', $menu_class);
        $this->smarty->assign('menu', $menu);
        $this->smarty->assign('user', $user);
    }








}
