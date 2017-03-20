/**
 * 所有页面用到的公共js方法
 * @Author   Andy wei <andywei2010@163.com>
 * @DateTime 2016-07-08T10:50:24+0800
 */
define(function(require,exports){

	//保存、编辑提交
	$(".add-edit").click(function(e){
		if(confirm('确认要此操作吗？')) {
			var data = $(".data-form").serialize();
			var action = $(".data-form").attr("action");
			var opt = $(":input[name='opt']").val();
			if (! action || !opt) {
				return false;
			}
			$(".add-edit").prop("disabled",true);
			$.post(action+"?opt="+opt, data , function(ret){
				// console.log(ret);
				alert(ret.msg);
				if (ret.code > 0) {
					window.history.back();
				} else {
					$(".add-edit").prop("disabled",false);
					return false;
				}
			},"json");
		}
	});

	//取消返回上一页
	$(".cancel").click(function(e){
		window.history.back();
	});

	//删除
	$(".delete").click(function(e){
		e.preventDefault();
		var url = $(this).attr("href");
		if ( ! url) {
			return false;
		}
		if (confirm("确认要此操作吗，此操作不可恢复！")) {
	        $.get(url, function(ret){
	            alert(ret.msg);
				if (ret.code > 0) {
					window.location.reload();
				} else {
					return false;
				}
	        },"json");
	    }
	});

	//启用、禁用
	$(".enable").click(function(e){
		e.preventDefault();
		var url = $(this).attr("href");
		if ( ! url) {
			return false;
		}
		if (confirm("确认要此操作吗？")) {
	        $.get(url, function(ret){
	            alert(ret.msg);
				if (ret.code > 0) {
					window.location.reload();
				} else {
					return false;
				}
	        },"json");
	    }
	});



})
