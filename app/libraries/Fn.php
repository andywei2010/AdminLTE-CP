<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fn {
	/**
     * 生成文件名
     * @Author   Andy wei <andywei2010@163.com>
     * @DateTime 2016-07-14T16:43:48+0800
     * @param    string $ext 扩展名（包含.）
     * @return   string
     */
    public static function build_filename($ext='') {
        $filename = date("/Ym/dH/").uniqid().mt_rand(100000, 999999);
        if ($ext) {
            $filename = $filename.'.'.$ext;
        }
        return $filename;
    }

    /**
     * 导出xls
     * @param  [type] $filename 自定义文件名，扩展名为.xls
     * @param  [type] $header   表头定义，索引数组或者关联数组
     *                          索引数组：$data中的列数和顺序要和$header中的一一对应
     *                          关联数组：key为表头显示字段 value为对应$data中的字段名
     *                          表头字段结尾添加"__int"、"__str"可以指定对应列的类型
     * @param  [type] $data     数据数组，二维数组
     * @param  [type] $default  字段未设置时的默认值
     * @param  [type] $template 模板名称
     * @return [type]           [description]
     */
    public static function export_xls($filename, $header, $data, $default='--', $template='') {
        $h_keys = array_keys($header);
        $xls_data = $data;
        //关联数组
        if($h_keys !== range(0, count($header) - 1)){
            $xls_data = array();
            $h_values = array_values($header);
            $header = $h_keys;
            foreach($data as $k=>$v){
                $line = array();
                foreach($h_values as $dk=>$dv){
                    $line[$dv] = isset($v[$dv]) ? $v[$dv] : $default;
                }
                $xls_data[] = $line;
            }
        }
        $ftype = array();
        foreach ($header as $key => $value) {
            $ftype[$key] = "x:str";
            if(preg_match('/__(\w+)$/', $value,$m)){
                $ftype[$key] = "x:$m[1]";
                $header[$key] = str_replace($m[0], '', $value);
            }
        }
        $CI = &get_instance();
        $CI->load->library("smarty");
        $CI->smarty->assign('header', $header);
        $CI->smarty->assign('ftype', $ftype);
        $CI->smarty->assign('data', $xls_data);
        // $CI->smarty->display("export/{$template}.html");exit;
        if ($template != '') {
            $xls = $CI->smarty->fetch("export/{$template}.html");
        } else {
            $xls = $CI->smarty->fetch("export/xls.html");
        }
        $CI->load->helper('download');
        force_download(urlencode($filename), $xls);
    }

    //发送邮件 多人$to用英文逗号分隔
    public static function send_email($to, $subject, $message, $attch='', $cc='') {
        $CI = &get_instance();
        $CI->load->library('email');
        $from = 'Admin';
        $CI->email->from('', $from);
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if($attch) { //发附件
            $CI->email->attach($attch);
        }
        if($cc) { //抄送
            $CI->email->cc($cc);
        }
        $CI->email->send();
    }

    








}