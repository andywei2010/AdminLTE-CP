<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 基类 通用方法
 * @author    Andy wei <andywei2010@163.com>
 * @created   2016-06-25 16:20
 */
class Base_Model extends CI_Model
{
    //表名
    private $_table;
    //表主键
    private $_id;

    public function __construct($table='', $id='') {
        $this->_table = $table;
        $this->_id = $id;

        parent::__construct();
        $this->load->database();
    }

    /**
     * 通用方法 保存信息
     * @param  array $data key/value数据
     * @return int   自增ID/影响行数
     */
    public function save($data) {
        if($this->db->insert($this->_table, $data)) {
            $insert_id = $this->db->insert_id();  //insert_id,数据表如果没有自增id会返回0
            if ($insert_id) {
                return $insert_id;
            } else {
                return $this->db->affected_rows();
            }
        }
    }

    /**
     * 通用方法 批量保存信息
     * @param  array $data key/value数据
     * @return int   影响行数
     */
    public function save_batch($data) {
        if ($data) {
            $this->db->insert_batch($this->_table,$data);
            return $this->db->affected_rows();
        }
        return 0;
    }

    /**
     * 通用方法 更改信息
     * @param  array $data  key/value数据
     * @param  array $where where条件
     * @return int   影响行数
     */
    public function update($data, $where) {
        $this->db->update($this->_table, $data, $where);
        return $this->db->affected_rows();
    }

    /**
     * 通用方法 批量更改信息
     * @param  array  $data key/value数据
     * @param  string $key  where更新字段
     * @return int    影响行数
     */
    public function update_batch($data, $key) {
        $this->db->update_batch($this->_table, $data, $key);
        return $this->db->affected_rows();
    }

    //alias get_one
    public function get_by_id($id, $field='*') {
        return $this->get_one($id, $field);
    }

    /**
     * 根据主键获取单条数据或一组数据
     * @param  integer $id    初始化主键ID
     * @param  string  $field 要显示的字段
     * @return array
     */
    public function get_one($id, $field="*") {
        if (is_array($field)) {
            $field = implode(', ', $field);
        }
        if(is_array($id)) {
            $where = " WHERE `{$this->_id}` IN ('".join("', '",$id)."')";
            $tmp = $this->db->query("SELECT ".$field.",".$this->_id." FROM `{$this->_table}` {$where}")->result_array();
            $resp = array();
            foreach($tmp as $v) {
                $resp[$v[$this->_id]] = $v;
            }
            return $resp;
        }
        return $this->db->query("SELECT ".$field.",".$this->_id." FROM `{$this->_table}` WHERE `$this->_id` = ".intval($id))->row_array();
    }

    /**
     * 通用方法 根据条件获取单条数据
     * @param  array   $search   条件
     * @param  string  $order_by 排序字段
     * @param  string  $asc      排序(默认升序)
     * @param  integer $limit    限制条数
     * @return array
     */
    public function get_row($search, $order_by='', $asc='ASC', $limit=0) {
        $res = $this->get_all($search, $order_by, $asc, 1);
        return $res && is_array($res[0]) ? $res[0] : array();
    }

    /**
     * 通用方法 根据条件获取多条数据
     * @param  array   $search   条件
     * @param  string  $order_by 排序字段
     * @param  string  $asc      排序(默认升序)
     * @param  integer $limit    限制条数
     * @return array
     */
    public function get_all($search=array(), $order_by='', $asc='ASC', $limit=0) {
        $sql = $this->_get_all_sql($search, $order_by, $asc, $limit);
        return $this->db->query($sql)->result_array();
    }

    /**
     * 通用方法 根据主键ID删除某条数据
     * @param  integer $id 初始化定义的主键ID
     * @return integer
     */
    public function delete($id) {
        $sql = "DELETE FROM `{$this->_table}` WHERE 1 ";
        if (is_array($id)) {
            foreach ($id as $k => $v) {
                $sql .= " AND `{$k}` = " . $this->db->escape($v);
            }
        } else {
            $sql .= " AND `{$this->_id}` = " . intval($id);
        }
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    /**
     * 搜索条件规则
     * @param  array   $search   支持条件(_like、_gt、_gte、_lt、_lte、_neq)
     * @param  string  $order_by 排序字段
     * @param  string  $asc      排序(默认升序)
     * @param  integer $limit    限制条数
     * @return array
     */
    private function _get_all_sql($search=array(), $order_by='', $asc='ASC', $limit=0) {
        $where = ' WHERE 1 ';
        foreach ($search as $k=>$v) {
            if (is_array($v)) {
                $where .= " AND `{$k}` IN ('".join("', '",$v)."')";
            } elseif (substr($k, -5) == '_like') {
                $k = str_replace('_like','',$k);
                $where .= " AND `{$k}` LIKE '%".$v."%'";
            } elseif (substr($k, -3) == '_gt') {
                $k = str_replace('_gt','',$k);
                if (is_string($v)) {
                    $where .= " AND `{$k}` > ".$this->db->escape($v);
                } else {
                    $where .= " AND `{$k}` > '".$this->db->escape($v)."'";
                }
            } elseif (substr($k, -4) == '_gte') {
                $k = str_replace('_gte','',$k);
                if (is_string($v)) {
                    $where .= " AND `{$k}` >= ".$this->db->escape($v);
                } else {
                    $where .= " AND `{$k}` >= '".$this->db->escape($v)."'";
                }
            } elseif (substr($k, -3) == '_lt') {
                $k = str_replace('_lt','',$k);
                if (is_string($v)) {
                    $where .= " AND `{$k}` < ".$this->db->escape($v);
                } else {
                    $where .= " AND `{$k}` < '".$this->db->escape($v)."'";
                }
            } elseif (substr($k, -4) == '_lte') {
                $k = str_replace('_lte','',$k);
                if (is_string($v)) {
                    $where .= " AND `{$k}` <= ".$this->db->escape($v);
                } else {
                    $where .= " AND `{$k}` <= '".$this->db->escape($v)."'";
                }
            } elseif (substr($k, -4) == '_neq') {
                $k = str_replace('_neq','',$k);
                if (is_string($v)) {
                    $where .= " AND `{$k}` != ".$this->db->escape($v);
                } else {
                    $where .= " AND `{$k}` != '".$this->db->escape($v)."'";
                }
            } else {
                $where .= " AND `{$k}` = ".$this->db->escape($v);
            }
        }
        $order_by = $order_by ? $order_by : $this->_id;
        $asc = strtolower($asc) == 'asc' ? 'ASC' : 'DESC';
        if (is_numeric($limit)) {
            $limit = $limit ? 'LIMIT '.intval($limit) : '';
        } else if (is_array($limit)) {
            $limit = $limit ? "LIMIT ".join(',', $limit) : '' ;
        } else if (is_string($limit)) {
            $limit = $limit ? 'LIMIT '. $limit : '';
        }
        return "SELECT * FROM `{$this->_table}` {$where} ORDER BY `{$order_by}` {$asc} {$limit}";
    }

    //count line num
    public function get_count($search=array())
    {
        return count($this->get_all($search));
    }

    
}