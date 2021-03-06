<?php if (!defined('THINK_PATH')) exit(); include 'header.php'?><link rel="stylesheet" type="text/css" href="__PUBLIC__/home.css"/><script type="text/javascript">
// Slide show
var slide = function(selector) {
	var self = this;
	this.item = $(selector);
	this.content = this.item.find('.content');
	this.contentSlide = this.content.find('div');
	var images = [];
	var index = 0;
	var pagers = this.item.find('.pager');
	this.pagers = [];
	this.item.find('ul li').each(function() {
		images.push($(this).html());
		var a = document.createElement('a');
		a.href = "javascript:void(0)";
		var myi = index++;
		a.onclick = function() {
			self.go(myi);
		};
		pagers.append(a);
		$(a).hover(function() {
			if(self.index != myi) {
				$(this).css('background-position', '-15px 0');
			}
		}, function() {
			if(self.index != myi) {
				$(this).css('background-position', '0 0');
			}
		});
		self.pagers.push(a);
	});
	this.images = images;
	this.index = 0;
	this.content.css('background','url(' + images[this.index] + ') left center');
	$(this.pagers[this.index]).css('background-position', '15px 0');
	this.item.hover(function() {
		self.stop();
	}, function() {
		self.play();
	});
}
slide.prototype.next = function() {
	this.go(this.index + 1, 1);
}
slide.prototype.prev = function() {
	this.go(this.index - 1, -1);
}
slide.prototype.go = function(index, pos) {
	if(this.going || this.images.length == 0)
		return;
	index = (index + this.images.length) % this.images.length;
	if(index == this.index)
		return;
	this.going = true;
	if(!pos) {
		pos = (index > this.index) ? 1 : -1;
	}
	var self = this;
	$(this.pagers[this.index]).css('background-position', '0 0');
	$(this.pagers[index]).css('background-position', '15px 0');
	var width = this.item.width();
	this.contentSlide.css('background', 'url(' + this.images[this.index] + ') no-repeat left center');
	this.index = index;
	this.content.css('background', 'url(' + this.images[this.index] + ') no-repeat ' + (pos * width) + 'px center');
	var went = 0;
	var animation = setInterval(function() {
		went += (width - went) / 7;
		self.content.css('background-position', (pos * (width - went)) + 'px center');
		self.contentSlide.css('background-position', (-pos * went) + 'px center');
		if(width - went < 1) {
			self.going = false;
			self.content.css('background-position', '0 center');
			clearInterval(animation);
		}
	}, 20);
}
slide.prototype.stop = function() {
	if(this.autoplay) {
		clearTimeout(this.autoplay);
		this.autoplay = false;
	}
}
slide.prototype.play= function(interval) {
	var self = this;
	if(!interval)
		interval = 3000;
	this.stop();
	function doNext() {
		self.next();
		self.stop();
		self.autoplay = setTimeout(doNext, 4000);
	}
	self.autoplay = setTimeout(doNext, interval);
}
var myslide;
$(function() {
	myslide = new slide('.slide-show');
	myslide.play();
});
</script><div class="slide-show"><ul><?php foreach($slide as $item) { ?><li>__PUBLIC__/images/slides/<?php echo ($item); ?></li><?php } ?></ul><div class="content"><div class="content-slide"></div></div><div class="pager"></div><div class="head"></div><div class="foot"></div><a class="next" href="javascript:void(0)" onclick="myslide.next()"></a><a class="prev" href="javascript:void(0)" onclick="myslide.prev()"></a></div><div class="float-fix"><div class="tip-left"><h3>简单易用的全新申请系统</h3><p>
			查询房间、填写表单、邮箱验证，简单3步即可完成场地申请。<br/><a href="<?php echo U('Order/index');?>">点击这里</a> 开始预约场地
		</p></div><div class="tip-right"><h3>更加人性化的管理系统</h3><p>
			全面改善了所有类型管理者的操作界面，<br/>
			重新实现的预约冲突分析系统更加准确有效
		</p></div></div><div class="hr"></div><div class="float-fix"><div style="float: left; width: 62%;"><div class="ribbon"><div class="padding">
				学生活动服务中心 · 新闻
			</div></div><div class="content"><?php foreach($news as $item) { ?><div class="news-item float-fix"><a href="<?php echo U('article', array('id'=>$item['newsid']));?>" class="thumb"><img src="__PUBLIC__/<?php echo ($item["thumb"]); ?>"/></a><div class="summary"><h2><?php echo ($item["title"]); ?></h2><p><?php echo ($item["summary"]); ?>...</p><a href="<?php echo U('article', array('id'=>$item['newsid']));?>">查看详情</a>...
				</div></div><?php } ?></div></div><div style="float: right; width: 30%; margin-right: 20px"><div class="quote-1"><div class="quote-2"><div class="quote-3"><div style="height:1px"></div><?php echo ($announcement); ?><div style="height:1px"></div></div></div></div><div style="padding: 9px 30px;"><strong style="font-size: 15px; color: #000;">
				系统公告
			</strong>
			&nbsp;&nbsp;&nbsp;&nbsp;
			(<?php echo ($announcementTime); ?>)
		</div><div class="hr"></div><form style="padding: 0 17px;" action="<?php echo U('feedback');?>" method="post"><h1>反馈留言</h1><label style="color: #222">邮箱：</label><input style="width: 200px;" class="field" type="text" name="email"/><textarea style="width: 250px; height: 70px" class="field" name="content"></textarea><button onclick="submit()" class="btn">发送</button></form></div></div><?php include 'footer.php'?>