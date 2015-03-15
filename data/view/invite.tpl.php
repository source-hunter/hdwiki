<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<div id="append_parent"></div>
<script type="text/javascript">
var userAgent = navigator.userAgent.toLowerCase();
var is_ie = (userAgent.indexOf('msie') != -1 && !(userAgent.indexOf('opera') != -1 && opera.version())) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
function copyTxt(txt){
	if(is_ie) {
		clipboardData.setData('Text',txt);
		alert ("网址已经复制到您的剪贴板中n您可以使用Ctrl+V快捷键粘贴到需要的地方");
	} else {
		prompt("请复制注册地址:",txt); 
	}
}
function autoPreview(val, id) {
	var previewObj = document.getElementById(id);
	if(typeof previewObj == 'object') {
		if(val.length >= 300) 
			return false;
		else
			previewObj.innerHTML = val.replace(/\n/ig, "<br />");
	}
}

</script>
<div class="hd_map">
	<a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt; <a href="index.php?user-profile">个人管理</a> &gt; 个人资料设置</div>
	
	<div class="r w-710 o-v m-t10 p-b10 gl_manage_main">
		<h2 class="h3 bold">我的好友邀请链接</h2>
		
		
		
		<div style="padding: 10px 20px; color: #666;">
		您可以通过QQ、MSN等IM工具，或者发送邮件，把下面的链接告诉你的好友，邀请他们加入进来。<br />
			<span style="font-size: 16px; font-weight: bold;">
			<a onclick="javascript:copyTxt(this.href);return false;" href="<?php echo $invite_url?>"><?php if(!empty($invite_url)) { ?><?php echo $invite_url?><?php } ?></a>
			</span>
		</div>
		<br />
		
		<h2 class="h3 bold">给好友发送 Email 邀请</h2>
		<div style="padding: 10px 20px; color: #666;">
		通过直接发送邮件的方式，邀请您的好友。<br />
		<form action="index.php?user-invite" style="color: #000;" method="POST">
		请输入您的好友Email地址，多个Email使用","分割:<br />
		<textarea name="toemails" cols="70" rows="8" /><?php if(!empty($toemails)) { ?><?php echo $toemails?><?php } ?></textarea><br />
		<span style="color:red"><?php if(!empty($mail_error)) { ?><?php echo $mail_error?><?php } ?></span><br />
		想对好友说的话:<br />
		<textarea name="ps" cols="70" rows="3" onkeyup="autoPreview(this.value, 'PreContainer')"><?php if(!empty($ps)) { ?><?php echo $ps?><?php } ?></textarea><br />
		<span style="color:red"><?php if(!empty($ps_error)) { ?><?php echo $ps_error?><?php } ?></span><br />
		<input type="submit" name="submit" value="发送邀请" />
		</form>
		<br />
		邀请函预览：
		<div style="border: 1px solid #CCC; background: #F0F0F0; padding: 10px; line-height: 14px;">
		<?php if(!empty($preview)) { ?><?php echo $preview?><?php } ?>
		</div>
		</div>
		
		<br />
	
</div>

<div class="l w-230">
<div class="m-t10 p-b10 sidebar gl_manage">
	<h2 class="col-h2"><span onclick="expand('usermanage');">个人资料</span></h2>	
	<ul id="usermanage">
		<li><a href="index.php?user-profile" target="_self"><img alt="" src="style/default/gl_manage/grzl.gif" />个人资料</a></li>
		<li><a href="index.php?user-editprofile" target="_self"><img src="style/default/gl_manage/grzl_set.gif"/>个人资料设置</a></li>
		<li><a href="index.php?user-editpass" target="_self"><img src="style/default/gl_manage/change_pw.gif"/>修改密码</a></li>
		<li><a href="index.php?user-editimage" target="_self"><img src="style/default/gl_manage/grzl_set.gif" />修改头像</a></li>
		<li><a href="index.php?doc-managesave" target="_self"><img src="style/default/gl_manage/ctbccgx.gif"/>词条保存草稿箱</a></li>
		<li><a href="index.php?user-invite" target="_self" class="on"><img src="style/default/gl_manage/invite.png"/>邀请注册</a></li>
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
</div>
<div class="c-b"></div>
<?php include $this->gettpl('footer');?>