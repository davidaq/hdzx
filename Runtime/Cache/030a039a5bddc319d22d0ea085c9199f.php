<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="__PUBLIC__/kindeditor-min.js"></script><link rel="stylesheet" type="text/css" href="__PUBLIC__/themes/default/default.css"/><style type="text/css">
.accounts td {
	text-align: center;
}
.xform input {
	background: #EEE;
	border: 0;
	border-bottom: 1px solid #AAA;
	outline: none;
	padding: 5px;
}
</style><script type="text/javascript">
$(function() {
	$('.accounts tr:odd td').css('background', '#EEE');
});
var stage;
function commit(data) {
	$.post('<?php echo U('editAccount');?>', data, function(result) {
		stage = result;
	},'HTML');
}
function changeSchool(item) {
	var id = $(item).parent().parent().find('td:first').html() * 1;
	data = {'userid':id, 'school':$(item).val()};
	commit(data);
}
function changeAdmin(item) {
	var id = $(item).parent().parent().find('td:first').html() * 1;
	data = {'userid':id, 'isadmin':$(item).attr('checked') ? 1 : 0};
	commit(data);
}
function changeCheckout(item) {
	var id = $(item).parent().parent().find('td:first').html() * 1;
	data = {'userid':id, 'ischeckout':$(item).attr('checked') ? 1 : 0};
	commit(data);
}
function changePassword(item) {
	var id = $(item).parent().parent().find('td:first').html() * 1;
	var dialog = KindEditor.dialog({
		title: '修改密码',
		body: '<form class="xform" action="<?php echo U('editAccount');?>" method="post" style="padding:5px">' +
				'<label>密码：</label>' +
				'<input type="password" name="password" value=""/>' +
				'</form>',
		closeBtn: {
			name: '关闭',
			click: function() {
				dialog.remove();
			}
		},
		noBtn: {
			name: '取消',
			click: function() {
				dialog.remove();
			}
		},
		yesBtn: {
			name: '修改',
			click: function() {
				data = {'password':$('.xform input:first').val(), 'userid':id};
				commit(data);
				dialog.remove();
			}
		}
	});
	$('.xform input').focus();
}
function addUser() {
	var dialog = KindEditor.dialog({
		title: '添加用户',
		body: '<form class="xform" action="<?php echo U('editAccount');?>" method="post" style="padding:5px">' +
				'<label>用户名：</label>' +
				'<input type="text" name="username" value=""/>' +
				'<input type="hidden" name="password" value=""/>' +
				'</form>',
		closeBtn: {
			name: '关闭',
			click: function() {
				dialog.remove();
			}
		},
		noBtn: {
			name: '关闭',
			click: function() {
				dialog.remove();
			}
		},
		yesBtn: {
			name: '添加',
			click: function() {
				$('.xform')[0].submit();
			}
		}
	});
	$('.xform input').focus();
}
function remove(item) {
	var id = $(item).parent().parent().find('td:first').html() * 1;
	$.get('<?php echo U('deleteAccount', array('id'=>''));?>' + id, function() {
		$(item).parent().parent().remove();
	});
}
</script><div class="accounts"><table style="width: 100%; font-size: 13px;" cellspacing="1" cellpadding="5"><tr style="font-weight: bold"><td width="10%">用户ID</td><td	width="30%">用户名</td><td	width="10%">后台管理权</td><td	width="10%">开门条发放权</td><td	width="20%">审核权范围</td><td	width="20%">操作</td></tr><?php foreach($accounts as $item) { ?><tr><td><?php echo ($item["userid"]); ?></td><td><?php echo ($item["username"]); ?></td><td><input type="checkbox" onchange="changeAdmin(this)" <?php echo $item['isadmin']?'checked':'';?>/></td><td><input type="checkbox" onchange="changeCheckout(this)" <?php echo $item['ischeckout']?'checked':'';?>/></td><td><select onchange="changeSchool(this)"><option value="null">无</option><option value="0" <?php echo (!is_null($item['school']) && 0 == $item['school']) ? 'selected':'';?>>校级</option><?php foreach($schools as $school) { ?><option value="<?php echo ($school["schoolid"]); ?>" <?php echo ($school['schoolid'] == $item['school']) ? 'selected':'';?>><?php echo ($school["name"]); ?></option><?php } ?></select></td><td><button onclick="changePassword(this)">修改密码</button><button onclick="remove(this)">删除用户</button></td></tr><?php } ?><tr><td colspan="6"><button onclick="addUser()">添加用户</button></td></tr></table></div>