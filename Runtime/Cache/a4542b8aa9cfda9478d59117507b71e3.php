<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
.form-content {
	margin-left: 20px;
	padding: 17px;
}
.timelist {
	margin: 0 auto;
	padding: 0;
	width: 100px;
}
.timelist li {
	float: left;
	width: 20px;
	height: 20px;
	display: block;
	margin: 1px;
	font-size: 10px;
	line-height: 20px;
	text-align: center;
	background: #AED;
	cursor: pointer;
	-moz-user-select: none;
	-webkit-user-select: none;
	-ms-user-select: none;
}
.timelist li.ordered {
	background: #DAC;
}
.timelist li.restrict {
	background: #397;
	color: #FFF;
}
.form-content textarea {
	display: block;
	margin: 5px auto;
	width: 550px;
	height: 100px;
}
.form-content label {
	color: #000;
	font-weight: bold;
}
</style><link rel="stylesheet" type="text/css" href="__PUBLIC__/jqueryui.css"/><script type="text/javascript" src="__PUBLIC__/jqueryui.js"></script><script type="text/javascript">
$(function() {
	$('.timelist .ordered').attr('title', '已被预约');
	$('.timelist .restrict').attr('title', '已被锁定');
	$('.datepicker').datepicker({minDate: "+1D", maxDate: "+2W", onSelect: changeDate});
	var cdate = '<?php echo $date; ?>';
	changeDate(cdate);
	cdate = cdate.substr(0, 4) + '-' + cdate.substr(4, 2) + '-' + cdate.substr(6, 2);
	$('.datepicker').datepicker('setDate', cdate);
	unitChange();
});
var emptyTimeList = false;
function initTimelist() {
	if(!emptyTimeList) {
		emptyTimeList = $('.timelist').html();
	} else {
		$('.timelist').html(emptyTimeList);
	}
	$('#timestart').val('00');
	$('#timeend').val('00');
	var time1, time2, down, bad;
	$('.timelist li').mousedown(function() {
		down = true;
		time1 = $(this).html() * 1;
		time2 = time1;
		highLight();
		return false;
	});
	$(document).mouseup(function() {
		if(bad) {
			time1 = time2 = 0;
			highLight();
			$('#timeend').html('00');
		}
		down = false;
	});
	$('.timelist li').hover(function() {
		if(down) {
			time2 = $(this).html() * 1;
			highLight();
		}
	}, function () {
	});
	function highLight() {
		var a = time1 > time2 ? time2 : time1;
		var b = time1 + time2 - a;
		var lit = [];
		bad = false;
		$('.timelist li').css({background: '', color: ''});
		$('.timelist li').each(function() {
			var c = $(this).html() * 1;
			if(c >= a && c <= b) {
				lit.push(this);
				if($(this).hasClass('ordered') || $(this).hasClass('restrict')) {
					bad = true;
				}
			}
		});
		$(lit).css({background: bad ? '#C33':'#A4C050', color: '#222'});
		if(a < 10)
			a = '0' + a;
		b++;
		if(b < 10)
			b = '0' + b;
		$('#timestart').val(a);
		$('#timeend').val(b);
	}
}
function unitChange() {
	var v = $('#unit').val();
	$('.unitinfo').hide();
	$('.unit' + v).show();
}
var undefined;
function changeDate(dateText) {
	initTimelist();
	dateText = dateText.replace(/\-/g, '');
	$('#selectedDate').val(dateText);
	$.get('<?php echo U('Query/roomDayTimelist');?>', {'room': <?php echo $roomid; ?>, 'date': dateText}, function(result) {
		if(result) {
			$('.timelist li').each(function() {
				var k = $(this).html();
				if(result[k] != undefined) {
					if(result[k] == 1) {
						$(this).addClass('restrict');
						$(this).attr('title', '已锁定');
					} else {
						$(this).addClass('ordered');
						$(this).attr('title', '已占用');
					}
				}
			});
		}
	}, 'JSON');
}
function commit() {
	if(!$('#confess').attr('checked')) {
		alert('请同意遵守管理条例');
		$('#confess').next('label').animate({marginLeft: 10}, 100).animate({marginLeft: 0}, 100).animate({marginLeft: 10}, 100).animate({marginLeft: 0}, 100);
		return;
	}
	var formData = $('#orderForm').serialize();
	$.post('<?php echo U('commit');?>', formData, function(result) {
		if(result == 'ok') {
			document.location.href = '<?php echo U('pendingorder', array('sendmail'=>1));?>';
		} else {
			alert(result);
		}
	}, 'HTML');
}
</script><div class="float-fix"><div style="float: right; width: 35%" class="nav-bar"><div class="nav-bar2"><div class="nav-bar3"><a class="active">您正在预约...</a><div style="text-align: center; padding: 10px; padding-right: 50px; color: #222"><br/><strong><?php echo ($room["name"]); ?></strong> (<?php echo ($room["number"]); ?>)
					<br/><br/><a href="<?php echo U('Index/room', array('id'=>$roomid));?>" target="_blank">查看房间简介</a><br/><br/><br/><input type="checkbox" id="confess"/><label for="confess" style="font-size: 13px; color: #222;">我同意遵守学生活动服务中心<a href="<?php echo ($readme); ?>" target="_blank">管理条例</a></label><br/><br/><button class="btn" onclick="commit()">提交申请表单</button><br/><i>表单提交后还要经过邮箱认证才会真的提交</i><?php if($room['maxhour'] > 0 && $room['maxhour'] < 14) { ?><br/><br/><i>本房间单个预约最多可申请 <?php echo ($room["maxhour"]); ?> 小时</i><?php } if($room['autoverify']) { ?><br/><br/><i>本房间预约由系统自动审核，如无冲突将自动通过</i><?php } ?></div></div></div></div><form id="orderForm" style="float: left; width: 63%"><input type="hidden" name="room" value="<?php echo ($room["roomid"]); ?>"/><input type="hidden" name="roomname" value="<?php echo ($room["name"]); ?> (<?php echo ($room["number"]); ?>)"/><a name="div-top" class="form-content" style="color: #222">
			请认真填写以下预约表格，申请人学号必须是北京交通大学在校学员的学号。
		</a><div class="ribbon"><div class="padding">
			1. 预约人基本资料
		</div></div><div class="form-content"><label>预约人姓名：</label><input type="text" class="field" name="orderer"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<label>预约人学号：</label><input type="text" class="field" name="ordererid"/><br/><br/><label>负责人联系方式：</label><input type="text" class="field" name="contact"/></div><div class="ribbon"><div class="padding">
			2. 预约时间选择
			<a href="#div-top">回到顶部</a></div></div><div class="form-content"><table><tr><td rowspan="2" style="padding:0 30px"><div class="datepicker"></div></td><td style="text-align:center;padding: 20px;"><label>时段：</label><input type="text" id="timestart" name="starthour" class="plaintext"/>点 到
					<input type="text" id="timeend" name="endhour" class="plaintext"/>点
					<input type="hidden" name="date" id="selectedDate"/></td></tr><td valign="top" style="text-align: center"><ul class="float-fix timelist"><li>08</li><li>09</li><li>10</li><li>11</li><li>12</li><li>13</li><li style="margin-right:20px;">14</li><li>15</li><li>16</li><li>17</li><li>18</li><li>19</li><li>20</li><li>21</li></ul><br/><i>用鼠标<u>划过</u>想要的时段，<br/>如在11按下在14松开代表11点到15点</i></td></tr></table></div><div class="ribbon"><div class="padding">
			3. 活动详情
			<a href="#div-top">回到顶部</a></div></div><div class="form-content"><label>活动主题：</label><input type="text" name="topic" class="field" style="width: 200px"/>
			&nbsp;&nbsp;
			<?php if($room['hasmedia']) { ?><label for="needmedia">使用多媒体：</label><input type="checkbox" name="needmedia" id="needmedia" />
			&nbsp;&nbsp;
			<?php } ?><label>活动人数：</label><select name="people"><option>&lt; 5</option><option>5-10</option><option>10-50</option><option>50+</option></select><br/><br/><label>举办单位：</label><select name="unit" id="unit" onchange="unitChange()"><option value="personal">个人</option><option value="school">学院社团</option><option value="top">校级社团</option></select><span class="unitinfo unitpersonal unitschool">
				&nbsp;
				<select name="school"><option value="0">- 学院 -</option><?php echo options($schools);?></select></span><span class="unitinfo unitschool unittop">
				&nbsp;
				<label>社团名称：</label><input type="text" name="unitname" class="field"/></span><br/><br/><label>活动内容：</label><textarea name="content" class="field"></textarea><?php if($room['needsecure']) { ?><br/><br/><label>安保措施：</label><textarea name="secure" class="field"></textarea><?php } ?></div></form></div>