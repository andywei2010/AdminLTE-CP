/**
 * 角色管理 编辑角色
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:50:24+0800
 */
define(function(require, exports) {
	
	//功能权限全选、全不选
	$(".checked-parent").click(function(){
		var data = $(this).attr("data");
		if ( ! data) {
			return false;
		}
		if ($(this).prop('checked') == true) {
			$(".checked-children-"+data).prop('checked', true);
		} else {
			$(".checked-children-"+data).prop('checked', false);
		}
	});

	//页面加载 初始化
	$(function() {
		
	});

})
