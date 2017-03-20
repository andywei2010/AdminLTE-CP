<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统管理 日志
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Log extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('array');
		$this->load->library('smarty');
		$this->load->model(array('Log_model','Action_model','Master_model'));
	}

	public function view()
	{
		$get = $this->input->get();
		$page = $this->input->get('page');
		$page = max(1, $page);
		$per_page = 10; //每页个数
		$limit = array(($page - 1) * $per_page, $per_page);
		
		$where = $this->_build_view_where($get);
		$log_list = $this->Log_model->get_all($where,'logid','DESC',$limit);
		$total = $this->Log_model->get_count($where);

		$action_ids = array_muliti_field($log_list, 'actionid');
		$action_list = $this->Action_model->get_all(array('actionid'=>$action_ids));
		$action_list = array_set_key($action_list, 'actionid');

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
			'viewdetail' => $this->User_model->check_my_action('log','viewdetail'),
		);

		$this->smarty->assign(array(
			'log_list' => $log_list,
			'action_list' => $action_list,
			'get' => $get,
			'links' => $links,
			'total' => $config['total_rows'],
			'action' => $action,
		));
		$this->smarty->display('log/view.html');
	}

	public function viewdetail()
	{
		$get = $this->input->get();
		$logid = intval($get['logid']);
		$log_info = $this->Log_model->get_one($logid);
		$action_info = $this->Action_model->get_one(intval($log_info['actionid']));
		$this->smarty->assign(array(
			'log_info' => $log_info,
			'action_info' => $action_info,
		));
		$this->smarty->display('log/viewdetail.html');	
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
    	if (isset($get['url']) && $get['url'] != '') {
    		$where['url_like'] = strval($get['url']);
    	}
    	if (isset($get['fullname']) && $get['fullname'] != '') {
    		$master_info = $this->Master_model->get_row(array('fullname' => strval($get['fullname'])));
    		$where['op_id'] = intval($master_info['masterid']);
    	}
    	return $where;
    }

}