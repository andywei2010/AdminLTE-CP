<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rbac
{
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->config('rbac');
        $this->CI->load->model('User_model');
        $this->CI->load->helper('url');

        $this->url_dir = $this->CI->uri->segment(1);
        $this->url_model = $this->CI->uri->segment(1);
        $this->url_method = $this->CI->uri->segment(2);
    }

    //操作权限认证
    function auth() 
    {
        $notneedlogin = $this->CI->config->item('notneedlogin');
        //是否需要登录
        if (!in_array($this->url_model, $notneedlogin)) {
            //需要登录
            if ($this->CI->User_model->is_login()) {
                //不需要验证权限的
                $notneedauth = $this->CI->config->item('notneedauth');
                foreach ($notneedauth as $v) {
                    if ($v[0] == $this->url_model && $v[1] == $this->url_method) {
                        $pass = TRUE;
                        break;
                    } else {
                        $pass = FALSE;
                    }
                }
                $user_action = $this->CI->User_model->check_my_action($this->url_model, $this->url_method);
                //需要验证权限但用户没有权限
                if (!$pass && !$user_action) {
                    $this->auth_error();
                }
            } else {
                redirect('/login/');
            }
        }
    }
    
    function auth_error() 
    {
        show_error('你没有权限进行此操作', 403, '提示信息');
    }
    
    //系统日志记录
    function log() 
    {
        //哪些操作需要记录日志
        $rbac_log_function = $this->CI->config->item('rbac_log_function');
        if (in_array($this->url_method, $rbac_log_function) && ($this->CI->input->get() || $this->CI->input->post())) {
            $actionid = 0;
            $this->CI->load->model('Action_model');
            $action = $this->CI->Action_model->get_action_by_classid_functionid($this->url_model, $this->url_method);
            
            if ($action) {
                $actionid = $action['actionid'];
            }
            $user = $this->CI->User_model->login_info();
            if (!$user) {
                $user['mastername'] = '-';
            }
            $this->CI->load->model('Log_model');
            $this->CI->Log_model->record($user['mastername'], $actionid);
        }
    }

}
?>