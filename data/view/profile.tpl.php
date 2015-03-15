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
	<a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt; <a href="index.php?user-profile">个人管理</a> &gt; 个人资料</div>
	
<div class="r w-710 o-v m-t10 p-b10 gl_manage_main">
	<h2 class="h3 bold">个人资料</h2>
	<dl class="col-dl gr_info font-14 ">
	<dd><a href="#" target="_blank" class="a-img2 l"><img id="userimage" alt="" src="<?php if($user['image'] == '') { ?>style/default/user.jpg<?php } else { ?><?php echo $user['image']?><?php } ?>"/></a></dd>
	<dd>用户名:<?php echo $user['username']?> (<?php if($user['gender']==1) { ?>男<?php } else { ?>女<?php } ?>)</dd>
	<dd>人气指数:<span class="red"><?php echo $user['views']?></span></dd>
	<dd>总经验:<span class="gray"><?php echo $user['credits']?></span>
	<?php if(isset($iscredit)) { ?> <a href="index.php?user-exchange">UC积分兑换</a><?php } ?>
	</dd>
	<dd>头衔: <font color="<?php echo $user['color']?>"><?php echo $user['grouptitle']?></font>
			<?php for($i=0; $i<$user['editorstar'][3]; $i++) {?>
			<img src="style/default/star_level3.gif"/>
			<?php } ?>
			<?php for($i=0; $i<$user['editorstar'][2]; $i++) {?>
			<img src="style/default/star_level2.gif"/>
			<?php } ?>
			<?php for($i=0; $i<$user['editorstar'][1]; $i++) {?>
			<img src="style/default/star_level1.gif"/>
			<?php } ?></dd>
	<dd>所在地: <span class="gray"><?php echo $user['location']?></span></dd>
	<dd>生日: <span class="gray"><?php echo $user['birthday']?></span></dd>
	<dd>注册时间: <span class="gray"><?php echo $user['regtime']?></span></dd>
	</dl>
<div class="info_other">
	<h3 class="h2 bor_b-ccc" >积分明细</h3>
	<table class="mar-t8 table">
	<thead>
	<tr>
		<td>总经验</td>
		<td>经验</td>
		<td>金币</td>
		<td>日常操作</td>
		<td>创建词条</td>
		<td>编辑词条</td>
		<td>删除词条</td>
		<td>宣传中心</td>
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
	<h3 class="h2 bor_b-ccc" >个人介绍</h3>
	<p><?php echo $user['signature']?></p>
</div>
</div>
<div class="l w-230">
<div class="m-t10 p-b10 sidebar gl_manage">
	<h2 class="col-h2"><span onclick="expand('usermanage');">个人资料</span></h2>	
	<ul id="usermanage">
		<li><a href="index.php?user-profile" target="_self" class="on"><img alt="" src="style/default/gl_manage/grzl.gif" />个人资料</a></li>
		<li><a href="index.php?user-editprofile" target="_self" ><img src="style/default/gl_manage/grzl_set.gif"/>个人资料设置</a></li>
		<li><a href="index.php?user-editpass" target="_self"><img src="style/default/gl_manage/change_pw.gif"/>修改密码</a></li>
		<li><a href="index.php?user-editimage" target="_self"><img src="style/default/gl_manage/grzl_set.gif" />修改头像</a></li>
		<li><a href="index.php?doc-managesave" target="_self"><img src="style/default/gl_manage/ctbccgx.gif"/>词条保存草稿箱</a></li>
		<li><a href="index.php?user-invite" target="_self"><img src="style/default/gl_manage/invite.png"/>邀请注册</a></li>
	</ul>
	<h2 class="col-h2"><span onclick="expand('userpms');">短消息</span></h2>		
	<ul id="userpms">
		<li><a href="index.php?pms-box-inbox" target="_self"><img alt="" src="style/default/gl_manage/sjx.gif" />收件箱</a></li>
		<li><a href="index.php?pms-box-outbox" target="_self" ><img src="style/default/gl_manage/fjx.gif"/>发件箱</a></li>
		<li><a href="index.php?pms-sendmessage" target="_self" ><img src="style/default/gl_manage/fdxx.gif"/>发短消息</a></li>
		<li><a href="index.php?pms-box-drafts" target="_self"><img src="style/default/gl_manage/cgx.gif" />草稿箱</a></li>
		<li><a href="index.php?pms-blacklist" target="_self"><img src="style/default/gl_manage/hllb.gif"/>忽略列表</a></li>
	</ul>
</div>
<div id="block_left"></div>
</div>
<div class="c-b"></div>
<script type="text/javascript">
	$('#userimage').attr('src',$('#userimage').attr('src')+'?'+Math.random());
</script>
<?php include $this->gettpl('footer');?>