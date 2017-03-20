<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Rbac用户表 model
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Master_model extends Base_Model {
    public function __construct() {
        parent::__construct('rbac_master' , 'masterid');
    }

    //验证用户的唯一性
	public function check_mastername_exists($mastername, $masterid=0) {
		$sql = 'SELECT `masterid` FROM `rbac_master` WHERE `mastername`=? AND `masterid`!=? LIMIT 1';
		if ($this->db->query($sql, array($mastername, $masterid))->num_rows() > 0) return TRUE;
		return FALSE;
	}

}