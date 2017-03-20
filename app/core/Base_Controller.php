<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 基类（公共方法）
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Base_Controller extends CI_Controller {

	public $user = null;

	/**
	 * 接口返回状态码及描述
	 */
	const CODE_SUCC = 1;
	const CODE_FAIL = -1;
	const CODE_EARG = -2;
	const CODE_EXCN = -3;

	const MSG_SUCC = '操作成功';
	const MSG_FAIL = '操作失败';
	const MSG_EARG = '参数传递错误';
	const MSG_EXCN = '数据更新异常';

	/**
	 * 请求参数
	 * @var array
	 */
	private $_param = array();
	/**
	 * 返回数据结构
	 * @var array
	 */
	private $_json = array();

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->user = $this->User_model->login_info();
		$this->c = $this->uri->rsegments[1];
		$this->a = $this->uri->rsegments[2];
		$this->uri_string = $this->uri->uri_string;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_param = $this->input->post();
        } else {
            $this->_param = $this->input->get();
        }
        $this->_json = array('code'=>self::CODE_EARG,'msg'=>self::MSG_EARG,'data'=>array());
	}

	/**
	 * 通用方法 写日志文件名
	 * @param  string $file 文件名
	 * @param  string 要记录的数据字附串
	 * @return void
	 */
	public function y_log($file, $str) {
		$this->load->model('log_model');
		$this->log_model->log_file($file, $str, "{$this->c}/{$this->a}");
	}

	/**
	 * 通用方法 接口最终输出
	 * @DateTime  2016-07-04T15:54:01+0800
	 * @param     array $data 参数
	 * @return    json
	 */
	public function y_view($data) {
		header('Content-type: application/json');
		echo json_encode($data);
		exit();
	}

	/**
	 * 通用方法 参数验证
	 * @DateTime  2016-07-04T15:29:07+0800
	 * @param     array $rules 要检测的参数
	 * @return    json
	 */
	public function check_parameters($rules=array()) {
		if ( ! $rules) {
			$this->y_view($this->_json);
		}
		foreach ($rules as $k => $v) {
			$rules = explode('|', $v['rules']);
			$field_value = isset($this->_param[$v['field']]) ? $this->_param[$v['field']] : '';
			if (in_array('required', $rules) && !isset($this->_param[$v['field']])) {
                $this->_json['code'] = self::CODE_EARG;
                $this->_json['msg'] = "缺少{$v['label']}参数";
                $this->y_view($this->_json);
            } elseif (in_array('required', $rules) && !$field_value) {
            	$this->_json['code'] = self::CODE_EARG;
                $this->_json['msg'] = "{$v['label']}不可为空";
                $this->y_view($this->_json);
            }

		}
	}

	//创建上传目录
    public function upload_path($filename = '') {
    	if (! $filename) return FALSE;
    	$path = explode("/", $filename);

    	!is_dir($path[0]) && @mkdir($path[0], 0777);
    	!is_dir($path[0].'/'.$path[1]) && @mkdir($path[0].'/'.$path[1], 0777);
    	!is_dir($path[0].'/'.$path[1]) && @mkdir($path[0].'/'.$path[1], 0777);
    	!is_dir($path[0].'/'.$path[1].'/'.$path[2]) && @mkdir($path[0].'/'.$path[1].'/'.$path[2], 0777);
    	return TRUE;
    }
	




}
