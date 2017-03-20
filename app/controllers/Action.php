<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统管理 功能权限
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Action extends Base_Controller {
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
		$this->load->library('smarty');
		$this->load->model(array('Action_model'));
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
		$action_list = $this->Action_model->get_all($where,'classid','ASC',$limit);
		$total = $this->Action_model->get_count($where);
		
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
		$this->load->model('User_model');
		$action = array(
			'add' => $this->User_model->check_my_action('action','add'),
			'edit' => $this->User_model->check_my_action('action','edit'),
			'delete' => $this->User_model->check_my_action('action','delete'),
		);

		$this->smarty->assign(array(
			'action_list' => $action_list,
			'get' => $get,
			'links' => $links,
			'total' => $config['total_rows'],
			'action' => $action,
		));
		$this->smarty->display('action/view.html');
	}

	public function add()
	{
		$get = $this->input->get();
		if (isset($get['opt']) && $get['opt'] == 'add') {
			if ( ! $this->_post) {
				$this->y_view($this->_json);
			}
			$this->_parameters($this->a);
			$action_data = array(
				'classid' => strval($this->_post['classid']),
				'functionid' => strval($this->_post['functionid']),
				'actionname' => strval($this->_post['actionname']),
				'ismenu' => strval($this->_post['ismenu']),
			);
			$action_id = $this->Action_model->save($action_data);
			$this->_json['code'] = self::CODE_SUCC;
			$this->_json['msg'] = self::MSG_SUCC;
			$this->y_view($this->_json);
		} 
		else 
		{
			$rbac_class = $this->config->item('rbac_class');
			ksort($rbac_class);
			$this->smarty->assign(array(
				'get' => $get,
				'class' => $rbac_class,
			));
			$this->smarty->display('action/add.html');
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
			$action_data = array(
				'classid' => strval($this->_post['classid']),
				'functionid' => strval($this->_post['functionid']),
				'actionname' => strval($this->_post['actionname']),
				'ismenu' => strval($this->_post['ismenu']),
			);
			$where = array(
				'actionid' => intval($this->_post['actionid']),
			);
			$action_id = $this->Action_model->update($action_data, $where);
			$this->_json['code'] = self::CODE_SUCC;
			$this->_json['msg'] = self::MSG_SUCC;
			$this->y_view($this->_json);
		} 
		else 
		{
			$rbac_class = $this->config->item('rbac_class');
			ksort($rbac_class);
			$action_info = $this->Action_model->get_one(intval($get['actionid']));
			$this->smarty->assign(array(
				'get' => $get,
				'class' => $rbac_class,
				'action_info' => $action_info,
			));
			$this->smarty->display('action/edit.html');
		}
	}

	public function delete()
	{
		$get = $this->input->get();
		$actionid = intval($get['actionid']);
		if ( ! isset($get['actionid']) && ! intval($get['actionid'])) {
			$this->y_view($this->_json);
		}
		$this->Action_model->delete_action($actionid);
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
    	if (isset($get['classid']) && $get['classid'] != '') {
    		$where['classid'] = strval(trim($get['classid']));
    	}
    	if (isset($get['functionid']) && $get['functionid'] != '') {
    		$where['functionid'] = strval(trim($get['functionid']));
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
            	'field' => 'classid',
            	'label' => '类名',
            	'rules' => 'required'
            ),
            array(
            	'field' => 'functionid',
            	'label' => '方法名',
            	'rules' => 'required'
            ),
            array(
            	'field' => 'actionname',
            	'label' => '权限名称',
            	'rules' => 'required'
            ),
        );
        // print_r($rules);exit;
        $this->check_parameters($rules);
        if (!preg_match('/^[0-9a-z_-]{3,32}$/i', $this->_post['classid']) || !preg_match('/^[0-9a-z_-]{3,32}$/i', $this->_post['functionid'])) {
			$this->_json['msg'] = '类名和方法名填写不正确';
			$this->y_view($this->_json);
		}
		if ($this->Action_model->check_action_exists($this->_post['classid'], $this->_post['functionid'])) {
			$this->_json['msg'] = '权限已经存在';
			$this->y_view($this->_json);
		}
    }


}