<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * RBAC辅助类
 * Class Action_model
 */
class Action_model extends Base_Model {
    public function __construct() {
        $this->load->database();
        parent::__construct('rbac_action', 'actionid');
    }

    //验证权限名称的唯一性
    //参数$classid类id，参数$functionid为方法ID。
    //参数 $actionid不为0，排除此$actionid
    public function check_action_exists($classid, $functionid, $actionid = 0) {
        if ($actionid = intval($actionid)) {
            $sql = 'SELECT `actionid` FROM `rbac_action` WHERE `classid`=? AND `functionid`=? AND `actionid` <> ' . $actionid . ' LIMIT 1';
        } else {
            $sql = 'SELECT `actionid` FROM `rbac_action` WHERE `classid`=? AND `functionid`=? LIMIT 1';
        }
        if ($this->db->query($sql, array($classid, $functionid))->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //添加权限到数据库,返回权限ID,失败时返回false
    public function add_action($action) {
        $sql = 'INSERT INTO `rbac_action` SET `classid`=?, `functionid`=?, `actionname`=?, `ismenu`=?';
        $this->db->query($sql, array($action['classid'], $action['functionid'], $action['actionname'], $action['ismenu']));
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    //修改权限到数据库,成功返回true,失败时返回false
    public function edit_action($action, $actionid) {
        $sql = 'UPDATE `rbac_action` SET  `classid`=?, `functionid`=?, `actionname`=?, `ismenu`=? WHERE `actionid`=?';
        $this->db->query($sql, array($action['classid'], $action['functionid'], $action['actionname'], $action['ismenu'], $actionid));
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 根据用户Action取得当前用户的相关权限{odDesc:根据权限classid,functionid获取权限}
     * @param $classid    类权限
     * @param $functionid 动作权限
     * @return mixed
     */
    public function get_action_by_classid_functionid($classid, $functionid) {
        $sql = 'SELECT * FROM `rbac_action` WHERE `classid`=? AND `functionid`=? LIMIT 1';
        $result = $this->db->query($sql, array($classid, $functionid))->row_array();
        return $result;
    }

    //根据资源模块classid获取权限列表
    public function get_action_by_classid($classid) {
        $result = array();
        if (is_array($classid) && !empty($classid)) {
            $where_in = "('" . implode("','", $classid) . "')";
            $sql = 'SELECT * FROM `rbac_action` WHERE `classid` IN ' . $where_in;
            $result = $this->db->query($sql)->result_array();
        } else {
            $sql = 'SELECT * FROM `rbac_action` WHERE `classid`=?';
            $result = $this->db->query($sql, $classid)->result_array();
        }
        return $result;
    }

    //根据资源模块functionid获取权限列表
    public function get_action_by_functionid($functionid) {
        $result = array();
        if (is_array($functionid) && !empty($functionid)) {
            $where_in = "('" . implode("','", $functionid) . "')";
            $sql = 'SELECT * FROM `rbac_action` WHERE `functionid` IN ' . $where_in;
            $result = $this->db->query($sql)->result_array();
        } else {
            $sql = 'SELECT * FROM `rbac_action` WHERE `functionid`=?';
            $result = $this->db->query($sql, $functionid)->result_array();
        }
        return $result;
    }

    //删除权限
    public function delete_action($actionid) {
        $sql = 'DELETE FROM `rbac_action` WHERE `actionid`=?';
        $this->db->query($sql, $actionid);
        //删除权限对应角色
        $sql = 'DELETE FROM `rbac_actionrole` WHERE `actionid`=?';
        $this->db->query($sql, $actionid);
        return TRUE;
    }
}
