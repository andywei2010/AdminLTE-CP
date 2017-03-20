<?php

//取多维数据中某字段的值
if ( ! function_exists('array_muliti_field'))
{
	function array_muliti_field($array, $field)
	{
		$resp = array();
		foreach ($array as $k => $v) {
			if (is_array($field)) {
				foreach ($field as $f) {
					if (isset($v[$f]) && $v[$f] !== null) {
						$resp[$f][$v[$f]] = $v[$f];
					}
				}
			} elseif (isset($v[$field]) && $v[$field] !== null) {
				$resp[] = $v[$field];
			}
		}
		return $resp;
	}
}

/*
 * 将多为数组中的某一个元素作为键名
 * $array = array(0=>array('id'=>10,'title'=>'t10'),1=>array('id'=>11,'title'=>'t11'));
 * $array = array_set_key($array, 'id');
 * array(10=>array('id'=>10,'title'=>'t10'),11=>array('id'=>11,'title'=>'t11'));
 */
if ( ! function_exists('array_set_key'))
{
	function array_set_key($array, $field)
	{
		$resp = array();
		foreach($array as $k => $v) {
			$resp[$v[$field]] = $v;
		}
		return $resp;
	}
}

/*
 * 按二维数组中的某一个元素排序
 * $array = array(0=>array('id'=>10,'title'=>'t10'),1=>array('id'=>11,'title'=>'t11'));
 * $array = array_set_key($array, 'id');
 * array(10=>array('id'=>10,'title'=>'t10'),11=>array('id'=>11,'title'=>'t11'));
 */
if ( ! function_exists('array_sort'))
{
	function array_sort($arr,$keys,$type='asc')
	{
        $keysvalue = $new_array = array();
        foreach ($arr as $k=>$v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k=>$v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
}

