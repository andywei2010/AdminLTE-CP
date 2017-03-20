<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 登录用户 model
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        if (!session_id()) {
            session_start();
        }
        $this->load->database();
    }

    //用户是否登录，TRUE/FALSE
    public function is_login() {
        if ($_SESSION['mastername']) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 返回当前用户Session
     * @return string
     */
    public function get_sessionid() {
        return session_id();
    }

    public function set_sessionid($sessid) {
        session_id($sessid);
        session_start();
        return true;
    }

    //用户登录信息，未登录返回FALSE
    public function login_info() {
        if ($_SESSION && $_SESSION['mastername']) {
            return $_SESSION;
        } else {
            return FALSE;
        }
    }

    //登录验证
    public function check($mastername, $password) {
        if ($mastername == '' OR $password == '') return FALSE;

        $this->db->where(array('mastername'=>$mastername));
        $m = $this->db->get('rbac_master')->row_array();
        if (!$m OR $m['status'] != 1) {
            return FALSE;//用户被删或者不存在
        }

        if ($m['masterpwd'] == md5($password)) {
            //记录最后登录时间
            $sql = 'UPDATE `rbac_master` SET `errorlogintimes`=0, `lastlogintime`=?, `thislogintime`=NOW() WHERE `mastername`=? LIMIT 1';
            $this->db->query($sql, array($m['thislogintime'], $mastername));
            return $m;
        }
        return FALSE;
    }

    /**
     * 设置用户登录(登录成功后种用户session)
     * @param array $user 用户登录信息
     */
    public function set_login_session($user) {
        $sess['masterid'] = $user['masterid'];
        $sess['mastername'] = $user['mastername'];
        $sess['fullname'] = $user['fullname'];
        $sess['mobile'] = $user['mobile'];
        $sess['master_sex'] = $user['master_sex'];
        $sess['deptname'] = $user['deptname'];
        $sess['lastlogintime'] = $user['lastlogintime'];
        $sess['cityid'] = $user['cityid'];
        $sess['action'] = $user['action'];
        $sess['res'] = $user['res'];
        $_SESSION = $sess;
    }

    /**
     * 清空所有session
     */
    public function destroy_session() {
        session_unset();
        session_destroy();
    }

    /**
     * 取出用户信息
     * @param $mastername   用户名称
     * @return mixed
     */
    public function get_user($mastername) {
        $sql = 'SELECT * FROM `rbac_master` WHERE `mastername`=? LIMIT 1';
        $result = $this->db->query($sql, $mastername)->row_array();
        return $result;
    }

    //取出用户数据 by userid
    public function get_user_by_id($masterid, $order_by = '') {
        if (is_array($masterid)) {
            $this->db->where_in('masterid', $masterid);
            if ($order_by) {
                $this->db->order_by($order_by);
            }
            return $this->db->get('rbac_master')->result_array();
        } else {
            $this->db->where('masterid', $masterid);
            return $this->db->get('rbac_master')->row_array();
        }
    }

    /**
     * 取用户角色
     * @param int $masterid 用户master_id
     * @return mixed
     */
    public function get_user_role($masterid) {
        $result = array();
        if ( ! $masterid) return $result;
        if (is_array($masterid)) {
            $sql = 'SELECT r.*,mr.masterid FROM `rbac_masterrole` AS mr 
                LEFT JOIN `rbac_role` AS r ON mr.roleid=r.roleid 
                WHERE mr.`masterid` IN ('.join(",",$masterid).') ORDER BY r.`roleid` DESC';
            $res = $this->db->query($sql)->result_array();
            if ($res) foreach ($res as $k => $v) {
                $result[$v['masterid']][] = $v;
            }
        } else {
            $sql = 'SELECT r.*,mr.masterid FROM `rbac_masterrole` AS mr 
                LEFT JOIN `rbac_role` AS r ON mr.roleid=r.roleid 
                WHERE mr.`masterid`=? ORDER BY r.`roleid` DESC';
            $result = $this->db->query($sql, $masterid)->result_array();
        }
        return $result;
    }

    //修改用户手机号
    public function set_my_mobile($mobile, $tel = '') {
        $user = $this->login_info();
        $data = array();
        $data['mobile'] = $mobile;
        if ($tel) {
            $data['tel'] = $tel;
        }
        $this->db->where('masterid', $user['masterid']);
        $this->db->update('rbac_master', $data);
        //更新session
        $user['mobile'] = $mobile;
        $this->set_login_session($user);
        return TRUE;
    }

    //获取当前登录用户功能权限
    public function get_my_action() {
        $user = $this->login_info();
        return $user['action'];
    }

    //验证当前登录用户功能权限
    public function check_my_action($class, $function) {
        $action = (array)$this->get_my_action();
        foreach ($action as $v) {
            if ($class == $v[0] && $function == $v[1]) {
                return TRUE;
            }
        }
        return FALSE;
    }

   

}
