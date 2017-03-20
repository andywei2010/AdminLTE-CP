<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Smarty Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Smarty
 * @author		Kepler Gelotte
 * @link		http://www.coolphptools.com/codeigniter-smarty
 */
require_once( 'Smarty/Smarty.class.php' );

class CI_Smarty extends Smarty {
	function __construct()
	{
		parent::__construct();

		$this->compile_dir = $_SERVER['SITE_CACHE_DIR'];//编译
		$this->template_dir = APPPATH . "views";//载入模板
		$this->config_dir = APPPATH . "config";//配置
		$this->compile_check = true;//模板是否变更过

		//log_message('debug', "Smarty Class Initialized");
	}
}
// END Smarty Class