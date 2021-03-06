<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
.feedbacks {
}
.feedbacks .item {
	padding: 10px;
	border-bottom: 1px solid #CCC;
	line-height: 20px;
}
.feedbacks .item .title {
	font-weight: bold;
	font-size: 16px;
	margin: 10px 0;
}
.feedbacks .item .content {
	padding: 2px;
	font-size: 14px;
	font-family: arial;
	margin: 5px 0;
}
.feedbacks .item .quote {
	border-left: 4px solid #ADF;
	padding-left: 10px;
	margin-left: 10px;
}
.feedbacks .item .title a {
	font-size: 12px;
	color: #557;
	margin-left: 30px;
}
.feedbacks a:hover {
	text-decoration: underline;
}
</style><script type="text/javascript" src="__PUBLIC__/kindeditor-min.js"></script><link rel="stylesheet" type="text/css" href="__PUBLIC__/themes/default/default.css"/><script type="text/javascript">
function reply(email, id) {
	var dialog = KindEditor.dialog({
		title: '回复： ' + email,
		body: '<form id="replyForm" action="<?php echo U('replyFeedback');?>" method="post">' +
				'<textarea name="content" style="width: 300px; height: 100px; border: 0; background: #FFF; padding: 5px; background: #F0F0F0"></textarea>' +
				'<input type="hidden" name="feedbackid" value="' + id + '"/>' +
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
			name: '发送',
			click: function() {
				$('#replyForm')[0].submit();
			}
		}
	});
	$('#replyForm textarea').focus();
}
function del(id) {
	var dialog = KindEditor.dialog({
		title: '操作确认',
		body: '<div style="padding: 10px">真的要删除这个留言么?</div>',
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
			name: '删除',
			click: function() {
				document.location.href = '<?php echo U('deleteFeedback', array('id'=>''));?>' + id;
			}
		}
	});
}
$(function() {
	$('.feedbacks .item:odd').css('background', '#F7F7F7');
});
</script><div class="pager" style="padding: 10px 0"><?php echo show_pager($pageTotal, $pageCurrent, U('feedback', array('page'=>'$$')));?></div><div class="feedbacks"><?php foreach($feedback as $item) { ?><div class="item"><div class="title"><?php echo ($item["email"]); ?> - <?php echo ($item["time"]); ?><a href="<?php echo ($item["frompage"]); ?>" target="_blank">前往留言者留言时所在页面</a></div><pre class="content"><?php echo ($item["content"]); ?></pre><div class="quote"><?php echo ($item["reply"]); ?></div><button onclick="reply('<?php echo ($item["email"]); ?>', <?php echo ($item["feedbackid"]); ?>)">回复</button>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<button onclick="del(<?php echo ($item["feedbackid"]); ?>)">删除</button></div><?php } ?></div><div class="pager" style="padding: 10px 0"><?php echo show_pager($pageTotal, $pageCurrent, U('feedback', array('page'=>'$$')));?></div>