<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * RBAC模型 角色
 * Class Role_model
 */
class Role_model extends Base_Model {
    public function __construct() {
        parent::__construct('rbac_role' , 'roleid');
        $this->load->database();
    }

    //验证角色名称的唯一性
    //参数$rolename角色名称，参数$roleid为排除的角色ID。
    public function check_rolename_exists($rolename, $roleid = 0) {
        $sql = 'SELECT `roleid` FROM `rbac_role` WHERE `rolename`=? AND `roleid`!=? LIMIT 1';
        if ($this->db->query($sql, array($rolename, $roleid))->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //添加角色到数据库,返回角色ID,失败时返回false
    public function add_role($role) {
        $sql = 'INSERT INTO `rbac_role` SET `rolename`=?, `roleinfo`=?, `creatorid`=?, `createtime`=NOW(), `updatetime`=NOW()';
        $this->db->query($sql, array($role['rolename'], $role['roleinfo'], $role['creatorid']));
        if ($this->db->affected_rows() == 1) {
            //添加功能权限
            $role['roleid'] = $this->db->insert_id();
            foreach ($role['actionid'] as $id) {
                $data = array(
                    'actionid' => $id,
                    'roleid' => $role['roleid'],
                    'creatorid' => $role['creatorid'],
                );
                $this->db->set('createtime', 'NOW()', FALSE);
                $this->db->insert('rbac_actionrole', $data);
            }
            return intval($role['roleid']);
        } else {
            return FALSE;
        }
    }

    //修改角色到数据库,成功返回true,失败时返回false
    public function edit_role($role) {
        $sql = 'UPDATE `rbac_role` SET `rolename`=?, `roleinfo`=?, `updatetime`=NOW() WHERE `roleid`=?';
        $this->db->query($sql, array($role['rolename'], $role['roleinfo'], $role['roleid']));
        if ($this->db->affected_rows() == 1) {
            //删除旧的角色功能权限,
            $sql = 'DELETE FROM `rbac_actionrole` WHERE `roleid`=?';
            $this->db->query($sql, $role['roleid']);
            //添加新的角色功能权限
            foreach ($role['actionid'] as $id) {
                $sql = 'INSERT INTO `rbac_actionrole` SET `actionid`=?, `roleid`=?, `creatorid`=?, `createtime`=NOW()'; //插入角色权限
                $this->db->query($sql, array($id, $role['roleid'], $role['creatorid']));
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * 获取某个角色信息
     * $roleid 角色id
     * return array(roleid, rolename...)
    */
    public function get_role($roleid) {
        $sql = 'SELECT * FROM `rbac_role` WHERE `roleid`=? LIMIT 1';
        $result = $this->db->query($sql, $roleid)->row_array();
        return $result;
    }

    /*
     * 获取某个角色所有用户
     * $roleid 角色id
     * return array()
    */
    public function get_role_user($roleid) {
        $this->db->where('roleid', $roleid);
        return $this->db->get('rbac_masterrole')->result_array();
    }

    /*
     * 获取角色功能权限
     * param $roleid 角色id
     * return array(array(actionid, actionname...),...)
    */
    public function get_role_action($roleid) {
        $sql = 'SELECT a.* FROM `rbac_actionrole` AS ar LEFT JOIN `rbac_action` AS a ON ar.`actionid`=a.`actionid` WHERE ar.`roleid`=?';
        $result = $this->db->query($sql, $roleid)->result_array();
        return $result;
    }

    /*
     * 获取角色数据权限
     * param $roleid 角色id
     * return array()
    */
    public function get_role_res($roleid) {
        if (is_array($roleid)) {
            $this->db->where_in('roleid', $roleid);
        } else {
            $this->db->where('roleid', $roleid);
        }
        $res = $this->db->get('rbac_resrole')->result_array();
        $return = array();
        if ($res) {
            foreach ($res as $vr) {
                $return[$vr['restype']][] = $vr['resid'];
            }
        }
        return $return;
    }


    //删除角色
    public function delete_role($roleid) {
        $roleid = intval($roleid);
        $sql = 'DELETE FROM `rbac_role` WHERE `roleid`=?';
        $this->db->query($sql, $roleid);
        //删除角色对应功能权限
        $sql = 'DELETE FROM `rbac_actionrole` WHERE `roleid`=?';
        $this->db->query($sql, $roleid);
        //删除用户对应角色
        $sql = 'DELETE FROM `rbac_masterrole` WHERE `roleid`=?';
        $this->db->query($sql, $roleid);
        return TRUE;
    }

}
