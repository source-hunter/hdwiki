<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script type="text/javascript" src="js/popWindow.js"></script>
<script type="text/javascript">
var getSize = function (datatype){
	url='index.php?admin_main-datasize-'+datatype;
	$("#"+datatype).html('��ѯ��,���Ժ�......');
	$.get(url, function(data){
		$("#"+datatype).html(data);
	});
}
<?php if($show_upgrade) { ?>
$(function(){
	$('#new_release_info').text('���ڼ���°汾...');
	$.get('index.php?admin_upgrade-check', function(data){
		if(isNaN(data)) {
			$('#new_release_info').text('��ʱû��������');
		} else {
			$('#new_release_info').html('�ҵ�'+data+'��������').css('color', 'red');
			$('#new_release_info').html($('#new_release_info').html()+' <a href="index.php?admin_upgrade">�������</a>')
			divDance('new_release_info');
		}
	});
	
});
<?php } ?>
</script>
<div class="sy">
	<h4 class="col-h5">HDwiki��̨��������</h4>
	<h3 class="col-h3">��ȫ��ʾ</h3>
	<ul class="col-ul p-l30">
		<li>��������config.php�ļ���������Ϊ644��linux/unix����ֻ����NT�� </li>
		<li>���������ڸ�����������Ա���룬�Ա�֤�˺Ű�ȫ </li>
		<li>���������ڵ��ٷ���̳�˽�hdwiki���¶�̬�����²��� </li>
	</ul>
	<p class="dcl"><span>����������</span>��<?php if($newunewd_on) { ?><a href="index.php?admin_edition-newusernewdoc">�״α༭���</a><?php } else { ?><a href="index.php?admin_doc">��˴���</a><?php } ?><a href="index.php?admin_user">����û�</a><a href="index.php?admin_comment">������� </a></p>
	<h3 class="col-h3">����Ա</h3>
	<p class="gly p-10 p-b10 p-l30">
		<?php foreach((array)$adminlist as $admin) {?>
		<a href="index.php?user-space-<?php echo $admin['uid']?>" target="_blank"><?php echo $admin['username']?></a>
		<?php } ?>
	</p>
	
	<h3 class="col-h3">��������Ϣ</h3>
	<ul class="col-ul p-l30 p-b10">
		<li>HDwiki����汾 HDWiki V<?php echo HDWIKI_VERSION?>  release <?php echo HDWIKI_RELEASE?> <span id="new_release_info"></span><a href="http://kaiyuan.hudong.com/down.php" target="_blank" class="m-lr10">�鿴���°汾</a><a href="http://kaiyuan.hudong.com/bbs/" target="_blank">רҵ֧�������</a></li>
		<li>������ϵͳ���汾 <?php echo $sys['server']?></li>
		<li>������ MySQL �汾 <?php echo $sys['mysql']?></li>
		<li>ϵͳ��װ·�� <?php echo HDWIKI_ROOT?></li>
		<li>���ݿ��С&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dbsize?></li>
		<li>������С&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="attsize"><a href="javascript:void(0);" onclick="getSize('attsize')">[����]</a></span></li>
		<li>�ϴ�ͼƬ��С&nbsp;&nbsp;<span id="uploadsize"><a href="javascript:void(0);" onclick="getSize('uploadsize')">[����]</a></span></li>
		<li>��ǰ��������<a href="index.php?admin_doc"  class="m-l8">[����]</a></li>
		<li>��ǰͼƬ����<a href="index.php?admin_image"  class="m-l8">[����]</a></li>
		<li><a href="index.php?admin_log-phpinfo">����鿴�����������Ϣ</a></li>
	</ul>

	<h3 class="col-h3">HDwiki�����Ŷ�</h3>
	<ul class="col-ul p-l30 team">
		<li>��Ȩ���� �������ߣ������� �Ƽ����޹�˾</li>
		<li class="link_gray">ϵͳ�ܹ�ʦ <a href="http://hi.baidu.com/songdenggao" target="_blank">lovewiki</a> </li>
		<li class="link_gray">������֧���Ŷ� <a href="http://hi.baidu.com/songdenggao" target="_blank">lovewiki</a>
											 <a href="http://hi.baidu.com/jobs_lee" target="_blank">jobs</a>
											 <a href="javascript:void(0)" target="_blank">Ѥ��</a>
											 <a href="http://i.baike.com/yejingran" target="_blank">ҹ��Ȼ</a>
											 <a href="http://dushii.blog.163.com/blog/" target="_blank">ѩ��</a>
											 <a href="javascript:void(0)" target="_blank">Walker</a>
											 <a href="http://www.rjf.cc/" target="_blank"> truk</a>
											 <a href="http://riverlet.me/" target="_blank">С��</a>
		</li>
		<li class="link_gray">�������û������Ŷ�  <a href="http://i.baike.com/aadesign/index" target="_blank">������</a><a href="http://i.baike.com/banma" target="_blank">��������</a><a href="http://i.baike.com/wenyanwen/index" target="_blank">����Ӿ����</a> </li>
		<li class="link_gray">��л������ </li>
		<li>������� <a href="http://www.baike.com" target="_blank">��˾��վ</a>,  <a href="http://kaiyuan.hudong.com/sq/authorize.html" target="_blank">������Ȩ</a>,  <a href="http://kaiyuan.hudong.com/template.php" target="_blank">ģ��</a>,  <a href="http://kaiyuan.hudong.com/plugin.php" target="_blank">���</a>,  <a href="http://kaiyuan.hudong.com/product.php" target="_blank">��Ʒ</a>, <a href="http://kaiyuan.hudong.com/bbs/" target="_blank"> ������</a></li>
	</ul>
</div>
<?php include $this->gettpl('admin_footer');?>
