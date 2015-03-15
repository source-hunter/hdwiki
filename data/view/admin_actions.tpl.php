<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>

<p class="map">系统设置：后台搜索</p>
<div class="seach_list" id="seach_list">
<h3 class="col-h4">搜索结果列表</h3>

<?php if($list) { ?>
	<?php foreach((array)$list as $data) {?>
	<dl class="col-dl">
		<dt><?php echo $data?></dt>
	</dl>
	<?php } ?>
<?php } else { ?>
	<dl class="col-dl">
		<dd>对不起，没有找到相关设置或功能菜单！</dd>
<?php } ?>
</div>
<a id="keywords" style="display:none;"><?php echo $keywords?></a>
<script type="text/JavaScript">
function go(AElement){
	var a = $(AElement), menu = a.attr('menu');
	if( menu == "2"){
		var url = a.parent().find("a[menu=1]").attr('href');
		if(url){
			parent.initMenu(url);
		}
	}
	parent.initMenu($(AElement).attr('href'));
	parent.setSearchWordColor($("#keywords").text());
}
</script>

<?php include $this->gettpl('admin_footer');?>