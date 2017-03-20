<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /**
  * 发送邮件配置
  */

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.exmail.qq.com';
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['smtp_port'] = 25;
$config['smtp_timeout'] = 5;
$config['charset'] = 'utf-8';

$config['crlf']="\r\n";
$config['newline']="\r\n";

$config['mailtype'] = "html";//html格式