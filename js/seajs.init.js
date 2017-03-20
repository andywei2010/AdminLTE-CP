/**
 * 初始化seajs
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:50:24+0800
 */
define(function(require,exports){
	$(function(){
		//加载公共的js文件
		seajs.use('seajs.common.js');
		//加载每个view页面里引入的js文件
		var ModuleName = $('#module').attr('js');
		if(! ModuleName ) return false;
		seajs.use(ModuleName);
	})
})