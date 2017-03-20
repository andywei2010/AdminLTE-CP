<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Rbac用户角色表 model
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Masterrole_model extends Base_Model {
    public function __construct() {
        parent::__construct('rbac_masterrole' , 'masterroleid');
    }

}