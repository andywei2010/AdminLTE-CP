/**
 * 用户管理 编辑用户
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:50:24+0800
 */
define(function(require, exports) {
	
	//页面加载 初始化
	$(function() {
		//初始化大区城市
		var provinceid = $(":input[name='provinceid']").val();
		var cityid = $(":input[name='cityid']").val();
		if (provinceid && cityid) {
			$("#city").citySelect({ prov:provinceid,city:cityid });
		} else {
			$("#city").citySelect({ prov:provinceid,city:cityid });
		}
		
	});

})

