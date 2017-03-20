<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户管理
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Master extends Base_Controller {
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
		$this->load->model(array('Master_model','User_model','Role_model',
			'Masterrole_model'));
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
		$master_list = $this->Master_model->get_all($where,'masterid','ASC',$limit);
		$total = $this->Master_model->get_count($where);

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

		$this->load->config('rbac');
		$rbac_master_status = $this->config->item('rbac_master_status');

		$master_ids = array_muliti_field($master_list, array('masterid','cityid'));
		$users_role = $this->User_model->get_user_role($master_ids['masterid']);
		// $city_list = $this->Mt_city_model->get_all(array('city_id'=>$master_ids['cityid']));
		// $city_list = array_set_key($city_list,'city_id');

		//权限
		$action = array(
			'add' => $this->User_model->check_my_action('master','add'),
			'edit' => $this->User_model->check_my_action('master','edit'),
			'enable' => $this->User_model->check_my_action('master','enable'),
			'viewdetail' => $this->User_model->check_my_action('master','viewdetail'),
		);

		$this->smarty->assign(array(
			'master_list' => $master_list,
			'get' => $get,
			'links' => $links,
			'total' => $config['total_rows'],
			'rbac_master_status' => $rbac_master_status,
			'users_role' => $users_role,
			// 'city_list' => $city_list,
			'action' => $action,
		));
		$this->smarty->display('master/view.html');
	}

	public function add()
	{
		$get = $this->input->get();
		if (isset($get['opt']) && $get['opt'] == 'add') {
			if ( ! $this->_post) {
				$this->y_view($this->_json);
			}
			$this->_parameters($this->a);
			if ($this->Master_model->check_mastername_exists(trim($this->_post['mastername']))) {
				$this->_json['msg'] = '填写的用户名已经存在';
				$this->y_view($this->_json);
			}
			$master_data = array(
				'mastername' => strval(trim($this->_post['mastername'])),
				'masterpwd' => $this->_post['masterpwd'] ? md5(trim($this->_post['masterpwd'])) : md5('abc.123'),
				'fullname' => strval(trim($this->_post['fullname'])),
				'master_sex' => intval($this->_post['master_sex']),
				'mobile' => strval(trim($this->_post['mobile'])),
				'email' => strval($this->_post['email']),
				'deptname' => strval(trim($this->_post['deptname'])),
				'cityid' => intval($this->_post['cityid']),
				'creatorid' => intval($this->user['masterid']),
				'createtime' => date('Y-m-d H:i:s'),
			);
			$this->db->trans_start();
			$ret_masterid = $this->Master_model->save($master_data);
			$masterrole_data = array();
			if (count($this->_post['roleid']) > 0) {
				foreach ($this->_post['roleid'] as $k => $v) {
					$masterrole_data[$k] = array(
						'masterid' => intval($ret_masterid),
						'roleid' => $v,
						'creatorid' => intval($this->user['masterid']),
						'createtime' => date('Y-m-d H:i:s'),
					);
				}
			}
			$this->Masterrole_model->save_batch($masterrole_data);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
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
			$this->load->config('rbac');
			$rbac_master_sex = $this->config->item('rbac_master_sex');
			$role = $this->Role_model->get_all(array(), 'rolename');
			$this->smarty->assign(array(
				'get' => $get,
				'role' => $role,
				'rbac_master_sex' => $rbac_master_sex,
			));
			$this->smarty->display('master/add.html');
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
			if ( !isset($this->_post['masterid']) && !intval($this->_post['masterid'])) {
				$this->y_view($this->_json);
			}
			$masterid = intval($this->_post['masterid']);
			$masterpwd = trim($this->_post['masterpwd']);
			if ($this->Master_model->check_mastername_exists(trim($this->_post['mastername']),$masterid)) {
				$this->_json['msg'] = '填写的用户名已经存在';
				$this->y_view($this->_json);
			}
			$master = $this->User_model->get_user_by_id($masterid);
			$master_data = array(
				'mastername' => strval(trim($this->_post['mastername'])),
				'fullname' => strval(trim($this->_post['fullname'])),
				'master_sex' => intval($this->_post['master_sex']),
				'mobile' => strval(trim($this->_post['mobile'])),
				'email' => strval($this->_post['email']),
				'deptname' => strval(trim($this->_post['deptname'])),
				'cityid' => intval($this->_post['cityid']),
				'creatorid' => intval($this->user['masterid']),
			);
			if ( (trim($master['masterpwd']) != $masterpwd) &&  strlen($masterpwd) > 0) {
				$master_data['masterpwd'] = md5($masterpwd);
			} elseif (strlen($masterpwd) == 0) {
				$master_data['masterpwd'] = md5('abc.123');
			}
			$this->db->trans_start();
			$this->Master_model->update($master_data,array('masterid'=>$masterid));
			$this->Masterrole_model->delete(array('masterid'=>$masterid));
			$masterrole_data = array();
			if (count($this->_post['roleid']) > 0) {
				foreach ($this->_post['roleid'] as $k => $v) {
					$masterrole_data[$k] = array(
						'masterid' => intval($master['masterid']),
						'roleid' => $v,
						'creatorid' => intval($this->user['masterid']),
						'createtime' => date('Y-m-d H:i:s'),
					);
				}
			}
			$this->Masterrole_model->save_batch($masterrole_data);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
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
			$masterid = intval($get['masterid']);
			$master = $this->User_model->get_user_by_id($masterid);

			$this->load->config('rbac');
			$rbac_master_sex = $this->config->item('rbac_master_sex');

			//角色
			$role = $this->Role_model->get_all(array(), 'rolename');
			$role_yes = $this->User_model->get_user_role($masterid);
			$roleid = array();
			foreach ($role_yes as $y) {
				$roleid[] = $y['roleid'];
			}
			foreach ($role as $k=>$v) {
				if (in_array($v['roleid'], $roleid)) {
					$role[$k]['yes'] = 1;
				} else {
					$role[$k]['yes'] = 0;
				}
			}
			// $city_info = $this->Mt_city_model->get_one(intval($master['cityid']));
			// $master['provinceid'] = intval($city_info['prov_id']);
			// print_r($role);
			$this->smarty->assign(array(
				'get' => $get,
				'master' => $master,
				'rbac_master_sex' => $rbac_master_sex,
				'role' => $role,
			));
			$this->smarty->display('master/edit.html');
		}
	}

	public function viewdetail()
	{
		$get = $this->input->get();
		$masterid = intval($get['masterid']);
		$master = $this->Master_model->get_one($masterid);
		//角色
		$master['role'] = $this->User_model->get_user_role($masterid);
		//权限
		$master['action'] = array();
		if ($master['role']) {
			foreach($master['role'] as $v) {
				$act = $this->Role_model->get_role_action($v['roleid']);
				foreach ($act as $v) {
					if (!in_array($v, $master['action'])) {
						array_push($master['action'], $v);
					}
				}
			}
		}
		//权限分类(具有的权限分类)
		$master['class'] = array();
		$rbac_class = $this->config->item('rbac_class');
		foreach ($master['action'] as $v) {
			$key = $v['classid'];
			$master['class'][$key] = $rbac_class[$key];
		}
		//创建者
		$master['creator'] = $this->User_model->get_user_by_id($master['creatorid']);
		// print_r($master);
		$this->smarty->assign(array(
			'get' => $get,
			'master' => $master,
		));
		$this->smarty->display('master/viewdetail.html');
	}

	//禁用、启用 消息模板
	public function enable()
	{
		$get = $this->input->get();
		$masterid = intval($get['masterid']);
		if ( ! isset($get['masterid']) && ! intval($get['masterid'])) {
			$this->y_view($this->_json);
		}
		if (!in_array($get['opt'], array('on','off'))) {
			$this->y_view($this->_json);
		}
		$master_data['status'] = ($get['opt'] == 'on') ? 1 : 0;
		$where = array(
			'masterid' => intval($get['masterid']),
		);
		$this->Master_model->update($master_data, $where);
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
    	if (isset($get['mastername']) && $get['mastername'] != '') {
    		$where['mastername_like'] = strval($get['mastername']);
    	}
    	if (isset($get['mobile']) && $get['mobile'] != '') {
    		$where['mobile_like'] = strval($get['mobile']);
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
            	'field' => 'mastername',
            	'label' => '用户名',
            	'rules' => 'required'
            ),
            array(
            	'field' => 'fullname',
            	'label' => '真实姓名',
            	'rules' => 'required'
            ),
            array(
            	'field' => 'master_sex',
            	'label' => '性别',
            	'rules' => 'required'
            ),
            array(
            	'field' => 'mobile',
            	'label' => '手机号',
            	'rules' => 'required'
            ),
            array(
            	'field' => 'email',
            	'label' => '邮箱',
            	'rules' => 'required'
            ),
            array(
            	'field' => 'deptname',
            	'label' => '所属部门',
            	'rules' => 'required'
            ),
            // array(
            // 	'field' => 'cityid',
            // 	'label' => '所属城市',
            // 	'rules' => 'required'
            // ),
            array(
            	'field' => 'roleid',
            	'label' => '角色',
            	'rules' => 'required'
            ),
        );
        // print_r($rules);exit;
        $this->check_parameters($rules);
    }

}