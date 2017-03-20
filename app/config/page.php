<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 分页配置文件
 */
$config['use_page_numbers'] = TRUE;
$config['first_link'] = '&lt;&lt;';
$config['prev_link'] = '&lt;';
$config['next_link'] = '&gt;';
$config['last_link'] = '&gt;&gt;';
$config['query_string_segment'] = 'page';
//自定义数字
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
//当前页
$config['cur_tag_open'] = '<li class="active"><a href="#">';
$config['cur_tag_close'] = '</a><li>';
//前一页
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';
//后一页
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';
//第一页
$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';
//最后一页
$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';
//把结果包在ul标签里
$config['full_tag_open'] = '<div class="box-footer clearfix"><ul class="pagination pagination-sm no-margin pull-right">';
$config['full_tag_close'] = '</ul></div>';