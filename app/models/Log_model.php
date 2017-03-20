<?PHP
/**
 * Rbac日志表 model
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:29:40+0800
 */
class Log_model extends Base_Model
{
	public function __construct()
	{
		parent::__construct('rbac_log', 'logid');
		$this->load->database();
	}

	//记录用户操作日志
	public function record($mastername, $actionid)
	{
	    $this->load->helper('url');
		$url = uri_string();
		$get = print_r($this->input->get(), TRUE);
		$post = print_r($this->input->post(), TRUE);
		$sql = 'INSERT INTO `rbac_log` SET `mastername`=?, `actionid`=?, `url`=?, `get`=?, `post`=?, `createtime`=NOW()';
		$this->db->query($sql, array($mastername, $actionid, $url, $get, $post));
		return TRUE;
	}

	//记录日志文件 $file日志文件名 $str要记录的数据字附串 $flag url或附加标注信息
	public function log_file($file, $str, $flag='') {
		$dir = rtrim($_SERVER['SITE_LOG_DIR'], '/') . '/' . date('Ym') . '/';
		!is_dir($_SERVER['SITE_LOG_DIR']) && @mkdir($_SERVER['SITE_LOG_DIR'], 0777);
		!is_dir($dir) && @mkdir($dir, 0777);
		$file = $dir . date('Ym') . $file . '.log';
		$str = date('Y-m-d H:i:s') . " {$flag} {$str} \r\n";
		file_put_contents($file, $str, FILE_APPEND);
	}

}
