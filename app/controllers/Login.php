<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 登录用户
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('smarty');
        $this->load->model(array('User_model','Role_model'));
    }

    /**
     * 登录动作
     */
    public function index() {
        $this->login_form('');
    }

    /**
     * 登录处理
     */
    public function check() {
        $post = $this->input->post();
        if (!$post['username'] OR !$post['password']) {
            $this->login_form('未输入用户名或密码');
            exit();
        }
        $user = $this->User_model->check($post['username'], $post['password']);
        if ($user === FALSE) { //用户登录失败
            $this->login_form('用户名和密码错误');
            exit();
        }
        
        //用户登录成功 取权限
        $role = $this->User_model->get_user_role($user['masterid']);
        if (is_array($role)) {
            $action = $res = array();
            foreach ($role as $v) {
                $action = array_merge($action, $this->Role_model->get_role_action($v['roleid']));
                $res = array_merge_recursive($res, $this->Role_model->get_role_res($v['roleid']));
            }
            //去除重复权限
            $useraction = array();
            foreach ($action as $v) {
                $a = array($v['classid'], $v['functionid']);
                if (!in_array($a, $useraction)) array_push($useraction, $a);
            }
            //合并数据权限
            $user['action'] = $useraction;
            $user['res'] = $res;
        }
        // print_r($user);exit;
        //种session
        $this->User_model->set_login_session($user);
        redirect('/home/index/');
    }

    /**
     * WEB登录页面显示
     * @param $msg  页面显示信息
     */
    protected function login_form($msg) {
        $sessid = $this->User_model->get_sessionid();
        $this->smarty->assign('msg', $msg);
        $this->smarty->display("login.html");
    }

    //退出
    public function out() {
        $this->User_model->destroy_session();
        redirect('/login/index/');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
