<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 角色管理
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Role extends Base_Controller {
	/**
	 * post参数
	 * @var array
	 */
	private $_post = array();
	/**
	 * 返回数据结构
	 * @var array
	 */
	private $_json = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('array');
		$this->load->library('smarty');
		$this->load->model(array('Role_model','Action_model','User_model'));
		$this->_post = $this->input->post();
		$this->_json = array('code'=>self::CODE_EARG,'msg'=>self::MSG_EARG,'data'=>array());
	}

	public function view()
	{
		$get = $this->input->get();
		$page = $this->input->get('page');
		$page = max(1, $page);
		$per_page = 10; //每页个数
		$limit = array(($page - 1) * $per_page, $per_page);
		
		$where = $this->_build_view_where($get);
		$role_list = $this->Role_model->get_all($where,'roleid','ASC',$limit);
		$total = $this->Role_model->get_count($where);
		
		//分页
		$this->load->config('page', TRUE);
		$config = $this->config->item('page');
		$config['page_query_string'] = TRUE;
		$query_array = $get;
		unset($query_array['page']);
		$config['base_url'] = '?' . http_build_query((array) $query_array);
		$config['total_rows'] = $total;
		$config['per_page'] = $per_page;
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$links = $this->pagination->create_links();

		//权限
		$action = array(
			'add' => $this->User_model->check_my_action('role','add'),
			'edit' => $this->User_model->check_my_action('role','edit'),
			'delete' => $this->User_model->check_my_action('role','delete'),
			'viewdetail' => $this->User_model->check_my_action('role','viewdetail'),
		);

		$this->smarty->assign(array(
			'role_list' => $role_list,
			'get' => $get,
			'links' => $links,
			'total' => $config['total_rows'],
			'action' => $action,
		));
		$this->smarty->display('role/view.html');
	}

	public function add()
	{
		$get = $this->input->get();
		if (isset($get['opt']) && $get['opt'] == 'add') {
			if ( ! $this->_post) {
				$this->y_view($this->_json);
			}
			$this->_parameters($this->a);
			if ($this->Role_model->check_rolename_exists(trim($this->_post['rolename']))) {
				$this->_json['msg'] = '填写的角色名称已经存在';
				$this->y_view($this->_json);
			}
			$role_data = array(
				'rolename' => strval($this->_post['rolename']),
				'roleinfo' => strval($this->_post['roleinfo']),
				'creatorid' => intval($this->user['masterid']),
				'actionid' => (array) $this->_post['actionid'],
			);
			$ret_roleid = $this->Role_model->add_role($role_data);
			if (!$ret_roleid) {
				$this->_json['code'] = self::CODE_EXCN;
				$this->_json['msg'] = self::MSG_EXCN;
				$this->y_view($this->_json);
			}
			$this->_json['code'] = self::CODE_SUCC;
			$this->_json['msg'] = self::MSG_SUCC;
			$this->y_view($this->_json);
		} 
		else 
		{
			//权限分类(具有的权限分类)
			$action['class'] = $this->config->item('rbac_class');
			foreach ($action['class'] as $classid => $classname) {
				 $action['action'][$classid] = $this->Action_model->get_action_by_classid($classid);
			}

			$this->smarty->assign(array(
				'get' => $get,
				'action' => $action,
			));
			$this->smarty->display('role/add.html');
		}
	}

	public function edit()
	{
		$get = $this->input->get();
		if (isset($get['opt']) && $get['opt'] == 'edit') {
			if ( ! $this->_post) {
				$this->y_view($this->_json);
			}
			$this->_parameters($this->a);
			if ($this->Role_model->check_rolename_exists(trim($this->_post['rolename']), $this->_post['roleid'])) {
				echo $this->db->last_query();exit;
				$this->_json['msg'] = '填写的角色名称已经存在';
				$this->y_view($this->_json);
			}
			$role_data = array(
				'rolename' => strval($this->_post['rolename']),
				'roleinfo' => strval($this->_post['roleinfo']),
				'creatorid' => intval($this->user['masterid']),
				'actionid' => (array) $this->_post['actionid'],
				'roleid' => intval($this->_post['roleid']),
			);
			$ret = $this->Role_model->edit_role($role_data);
			if ( ! $ret) {
				$this->_json['code'] = self::CODE_EXCN;
				$this->_json['msg'] = self::MSG_EXCN;
				$this->y_view($this->_json);
			}
			$this->_json['code'] = self::CODE_SUCC;
			$this->_json['msg'] = self::MSG_SUCC;
			$this->y_view($this->_json);
		} 
		else 
		{
			$roleid = intval($get['roleid']);
			$role_info = $this->Role_model->get_one($roleid);
			//具有的权限
			$action_yes = $this->Role_model->get_role_action($roleid);
			//权限分类
			$action['class'] = $this->config->item('rbac_class');
			foreach ($action['class'] as $classid => $classname)
			{
				 $action_class = $this->Action_model->get_action_by_classid($classid);
				 foreach ($action_class as $k=>$v)
				 {
					$action_class[$k]['yes'] = 0;
					foreach ($action_yes as $y)
					{
						if ($y['actionid'] == $v['actionid'])
						{
							$action_class[$k]['yes'] = 1;
							break;
						}
					}
				 }
				 $action['action'][$classid] = $action_class;
			}
			// print_r($action);
			$this->smarty->assign(array(
				'get' => $get,
				'role_info' => $role_info,
				'action' => $action,
			));
			$this->smarty->display('role/edit.html');
		}
	}

	public function viewdetail()
	{
		$get = $this->input->get();
		$roleid = intval($get['roleid']);
		$role = $this->Role_model->get_role($roleid);
		//角色功能权限
		$role['action'] = $this->Role_model->get_role_action($roleid);
		//功能权限分类(具有的权限分类)
		$role['class'] = array();
		$rbac_class = $this->config->item('rbac_class');
		foreach ($role['action'] as $v) {
			$key = $v['classid'];
			$role['class'][$key] = $rbac_class[$key];
		}
		//所有用户
		if ($role['user'] = $this->Role_model->get_role_user($roleid)) {
			$user = array_muliti_field($role['user'], 'masterid');
			$role['user'] = $this->User_model->get_user_by_id($user, 'mastername');
			//删除禁用的用户
			if ($role['user']) {
				foreach ($role['user'] as $key=>$val) {
					if ($val['status']!=1) {
						unset($role['user'][$key]);
					}
				}
			}
		}
		//创建者
		$role['creator'] = $this->User_model->get_user_by_id($role['creatorid']);
		$this->smarty->assign(array(
			'role' => $role,
		));
		$this->smarty->display('role/viewdetail.html');	
	}

	public function delete()
	{
		$get = $this->input->get();
		$roleid = intval($get['roleid']);
		if ( ! isset($get['roleid']) && ! intval($get['roleid'])) {
			$this->y_view($this->_json);
		}
		//超级管理员角色不能被删除
		if ($roleid == 1) {
			$this->_json['msg'] = '无效操作';
			$this->y_view($this->_json);	
		}
		$this->Role_model->delete_role($roleid);
		$this->_json['code'] = self::CODE_SUCC;
		$this->_json['msg'] = self::MSG_SUCC;
		$this->y_view($this->_json);
	}

	/**
     * 组装where条件
     * @param  array $get get参数
     * @return array
     */
    private function _build_view_where($get)
    {
    	$where = array();
    	if (isset($get['rolename']) && $get['rolename'] != '') {
    		$where['rolename_like'] = strval($get['rolename']);
    	}
    	return $where;
    }

    /**
     * 公共参数验证
     * @DateTime  2016-07-04T16:00:15+0800
     * @param string $action 方法名称
     * @return void
     */
    private function _parameters($action = '')
    {
    	$rules = array(
            array(
            	'field' => 'rolename',
            	'label' => '角色名称',
            	'rules' => 'required'
            ),
            array(
            	'field' => 'actionid',
            	'label' => '功能权限',
            	'rules' => 'required'
            ),
        );
        // print_r($rules);exit;
        $this->check_parameters($rules);
    }

}