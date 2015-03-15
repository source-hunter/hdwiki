<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript">
	function expand(id){
		if(id=='usermanage'){
			$("ul#usermanage").toggle(); 
		}else{
			$('ul#userpms').toggle();
		}
	}
</script>
<div class="hd_map">
	<a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt; <a href="index.php?user-profile">���˹���</a> &gt; ��������</div>
	
<div class="r w-710 o-v m-t10 p-b10 gl_manage_main">
	<h2 class="h3 bold">��������</h2>
	<dl class="col-dl gr_info font-14 ">
	<dd><a href="#" target="_blank" class="a-img2 l"><img id="userimage" alt="" src="<?php if($user['image'] == '') { ?>style/default/user.jpg<?php } else { ?><?php echo $user['image']?><?php } ?>"/></a></dd>
	<dd>�û���:<?php echo $user['username']?> (<?php if($user['gender']==1) { ?>��<?php } else { ?>Ů<?php } ?>)</dd>
	<dd>����ָ��:<span class="red"><?php echo $user['views']?></span></dd>
	<dd>�ܾ���:<span class="gray"><?php echo $user['credits']?></span>
	<?php if(isset($iscredit)) { ?> <a href="index.php?user-exchange">UC���ֶһ�</a><?php } ?>
	</dd>
	<dd>ͷ��: <font color="<?php echo $user['color']?>"><?php echo $user['grouptitle']?></font>
			<?php for($i=0; $i<$user['editorstar'][3]; $i++) {?>
			<img src="style/default/star_level3.gif"/>
			<?php } ?>
			<?php for($i=0; $i<$user['editorstar'][2]; $i++) {?>
			<img src="style/default/star_level2.gif"/>
			<?php } ?>
			<?php for($i=0; $i<$user['editorstar'][1]; $i++) {?>
			<img src="style/default/star_level1.gif"/>
			<?php } ?></dd>
	<dd>���ڵ�: <span class="gray"><?php echo $user['location']?></span></dd>
	<dd>����: <span class="gray"><?php echo $user['birthday']?></span></dd>
	<dd>ע��ʱ��: <span class="gray"><?php echo $user['regtime']?></span></dd>
	</dl>
<div class="info_other">
	<h3 class="h2 bor_b-ccc" >������ϸ</h3>
	<table class="mar-t8 table">
	<thead>
	<tr>
		<td>�ܾ���</td>
		<td>����</td>
		<td>���</td>
		<td>�ճ�����</td>
		<td>��������</td>
		<td>�༭����</td>
		<td>ɾ������</td>
		<td>��������</td>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><?php echo $creditDetail['creditTotal']?> </td>
		<td><?php echo $user['credit2']?> </td>
		<td><?php echo $user['credit1']?> </td>
		<td><?php echo $creditDetail['dailyOperate']?> </td>
		<td><?php echo $creditDetail['createDoc']?></td>
		<td><?php echo $creditDetail['editDoc']?></td>
		<td><?php echo $creditDetail['removedDoc']?></td>
		<td><?php echo $creditDetail['promotion-visit']+$creditDetail['promotion-register']?></td>
	</tr>
	</tbody>
	</table>
	<h3 class="h2 bor_b-ccc" >���˽���</h3>
	<p><?php echo $user['signature']?></p>
</div>
</div>
<div class="l w-230">
<div class="m-t10 p-b10 sidebar gl_manage">
	<h2 class="col-h2"><span onclick="expand('usermanage');">��������</span></h2>	
	<ul id="usermanage">
		<li><a href="index.php?user-profile" target="_self" class="on"><img alt="" src="style/default/gl_manage/grzl.gif" />��������</a></li>
		<li><a href="index.php?user-editprofile" target="_self" ><img src="style/default/gl_manage/grzl_set.gif"/>������������</a></li>
		<li><a href="index.php?user-editpass" target="_self"><img src="style/default/gl_manage/change_pw.gif"/>�޸�����</a></li>
		<li><a href="index.php?user-editimage" target="_self"><img src="style/default/gl_manage/grzl_set.gif" />�޸�ͷ��</a></li>
		<li><a href="index.php?doc-managesave" target="_self"><img src="style/default/gl_manage/ctbccgx.gif"/>��������ݸ���</a></li>
		<li><a href="index.php?user-invite" target="_self"><img src="style/default/gl_manage/invite.png"/>����ע��</a></li>
	</ul>
	<h2 class="col-h2"><span onclick="expand('userpms');">����Ϣ</span></h2>		
	<ul id="userpms">
		<li><a href="index.php?pms-box-inbox" target="_self"><img alt="" src="style/default/gl_manage/sjx.gif" />�ռ���</a></li>
		<li><a href="index.php?pms-box-outbox" target="_self" ><img src="style/default/gl_manage/fjx.gif"/>������</a></li>
		<li><a href="index.php?pms-sendmessage" target="_self" ><img src="style/default/gl_manage/fdxx.gif"/>������Ϣ</a></li>
		<li><a href="index.php?pms-box-drafts" target="_self"><img src="style/default/gl_manage/cgx.gif" />�ݸ���</a></li>
		<li><a href="index.php?pms-blacklist" target="_self"><img src="style/default/gl_manage/hllb.gif"/>�����б�</a></li>
	</ul>
</div>
<div id="block_left"></div>
</div>
<div class="c-b"></div>
<script type="text/javascript">
	$('#userimage').attr('src',$('#userimage').attr('src')+'?'+Math.random());
</script>
<?php include $this->gettpl('footer');?>