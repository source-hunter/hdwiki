<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<style>
	.LeftFrame{
		float:left;
		width:48%;
		height:570px;
		border:solid 1px;
		overflow-x:scroll;
		overflow-y:scroll
	}
	.RightFrame{
		float:left;
		width:48%;
		height:570px;
		border:solid 1px;
		overflow-x:scroll;
		overflow-y:scroll
	}
</style>
<script type="text/javascript">
function DriveRightScroll(){
	var RightDivObj = $('#RightScroll');
	var LeftDivObj = $('#LeftScroll');
	
	if (LeftDivObj.attr("scrollTop") < (RightDivObj.attr("scrollHeight") - RightDivObj.attr("clientHeight"))){
		RightDivObj.attr("scrollTop",LeftDivObj.attr("scrollTop"));
		RightDivObj.attr("scrollLeft",LeftDivObj.attr("scrollLeft"));
	}
}
function DriveLeftScroll(){
	var RightDivObj = $('#RightScroll');
	var LeftDivObj = $('#LeftScroll');
	if (RightDivObj.attr("scrollTop") < (LeftDivObj.attr("scrollHeight") - LeftDivObj.attr("clientHeight"))){
		LeftDivObj.attr("scrollTop",RightDivObj.attr("scrollTop"));
		LeftDivObj.attr("scrollLeft",RightDivObj.attr("scrollLeft"));
	}
}
</script>
<script type = "text/javascript" src = "js/compare.js"></script>
<div class="w-950 hd_map">
	<a href="index.php" target="_self"><?php echo $setting['site_name']?></a> &gt;&gt; <a href="index.php?doc-view-<?php echo $doc['did']?>" target="_self"><?php echo $doc['title']?></a> &gt;&gt; 版本对比 </div>
<div class="o-v">
	<h1 class="title_thema bor_b-ccc"><strong class="l"><?php echo $doc['title']?></strong><a href="index.php?doc-view-<?php echo $doc['did']?>" target="_self" class="r">返回词条</a></h1>
	<div class="edition">
		<ul class="l">
			<li>历史版本:<label><?php echo $edition[0]['editions']?></label>&nbsp;&nbsp;编辑时间:<label><?php echo $edition[0]['time']?></label>&nbsp;&nbsp;版本创建者:<a href="index.php?user-space-<?php echo $edition[0]['authorid']?>"><?php echo $edition[0]['author']?></a></li>
			<li>内容长度:<label><?php echo $edition[0]['words']?>字</label>&nbsp;&nbsp;图片<label><?php echo $edition[0]['images']?>个</label></li>
		</ul>
		<ul class="r">
			<li>历史版本:<label><?php echo $edition[1]['editions']?></label>&nbsp;&nbsp;编辑时间:<label><?php echo $edition[1]['time']?></label>&nbsp;&nbsp;版本创建者:<a href="index.php?user-space-<?php echo $edition[1]['authorid']?>"><?php echo $edition[1]['author']?></a></li>
			<li>内容长度:<label><?php echo $edition[1]['words']?>字</label>&nbsp;&nbsp;图片<label><?php echo $edition[1]['images']?>个</label></li>
		</ul>
	</div>
	<div id="LeftScroll" class="LeftFrame" onscroll="DriveRightScroll()">
		<span id="LeftContent" align="left"><?php echo $edition[0]['content']?></span>
	</div>
	<div id="RightScroll" class="RightFrame" onscroll="DriveLeftScroll()">
		<span id="RightContent" align="left"><?php echo $edition[1]['content']?></span>
	</div>
	
</div>
<script type="text/javascript">CompareById('LeftContent', 'RightContent');</script>
<p class="main l mar-t8"><label class="fanwei">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</label>1、浅绿色 表示一个范围 <label class="dian">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</label>2、浅紫色 不同点 </p>
<?php include $this->gettpl('footer');?>