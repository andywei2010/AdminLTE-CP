<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Api 公共接口
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-14T10:29:40+0800
 */
class Api extends Base_Controller {
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
		$this->load->model(array(''));
		$this->load->library(array('fn'));
		$this->_post = $this->input->post();
		$this->_json = array('code'=>self::CODE_EARG,'msg'=>self::MSG_EARG,'data'=>array());
	}

	//上传
    public function upload()
    {
    	if (empty($_FILES)) {
    		$this->_json['msg'] = 'selected img is empty.';
			$this->y_view($this->_json);
    	}
    	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
    	$tempFile = $_FILES['Filedata']['tmp_name'];
		$fileParts = pathinfo($_FILES['Filedata']['name']);
		$fileExt = $fileParts['extension'];

    	if ( ! in_array($fileExt, $fileTypes)) {
    		$this->_json['msg'] = 'Invalid file type.';
			$this->y_view($this->_json);
    	}

		$new_filename = Fn::build_filename($fileExt);
    	$targetFolder = 'uploads';
		$targetFile = rtrim($targetFolder,'/') . $new_filename;
		$dir_path = $this->upload_path($targetFile);
		if ( ! $dir_path) {
			$this->_json['msg'] = 'upload folder created fail.';
			$this->y_view($this->_json);
		}
		move_uploaded_file($tempFile,$targetFile);
		$this->_json['code'] = self::CODE_SUCC;
		$this->_json['msg'] = self::MSG_SUCC;
		$this->_json['data'] = array('pic' => $targetFile);
		$this->y_view($this->_json);
    }

 



















}