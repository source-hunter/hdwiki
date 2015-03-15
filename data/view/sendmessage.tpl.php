<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript" src="js/popWindow.js"></script>
<script type="text/javascript">
	function docheck(){
		checksize();
		if(!checkrecipient()){
			return false;
		}
		var subject=$.trim($('#subject').val());
		if(subject==""){
			alert('主题不能为空');
			return false;
		}
		var content=$.trim($('#content').val());
		if(content==""){
			alert('内容不能为空');
			return false;
		}
		return true;
	}
	
	function checksubject(){
		var subject=$.trim($('#subject').val());
		if(subject==""){
			$('#subjecttip').html('&nbsp;&nbsp;主题不能为空').fadeIn();
			divDance('subjecttip');
			return false;
		}else{
			$('#subjecttip').html('&nbsp;&nbsp;OK').fadeIn();
			divDance('subjecttip');
			return true;		
		}
	}

	function checkcontent(){
		var content=$.trim($('#content').val());
		if(content==""){
			$('#pmssize').html('&nbsp;&nbsp;内容不能为空').fadeIn();
			divDance('pmssize');
			return false;
		}
	}
	
	function checkrecipient(){
		var sendto=$.trim($('#sendto').val());
		var result=false;
		var groupid=<?php echo $groupid?>;
		
		if(groupid==4){
			var selectid = $.trim($('#usergroup').val());
			var draft=$('#checkbox').attr("checked");	
			if(selectid != 0 && draft){
				$('#checksendto').html('&nbsp;&nbsp;系统消息不能存入草稿箱!').fadeIn();
				divDance('checksendto');
				return false;
			}
		}
			
		if(sendto==""){
			if(groupid==4 && selectid != 0){
				return true;
			}		
			$('#checksendto').html('&nbsp;&nbsp;收件人不能为空').fadeIn();
			divDance('checksendto');
			return false;
		}else{	
			jQuery.ajax({
				url: "index.php?pms-checkrecipient",
				cache: false,
				dataType: "xml",
				type:"post",
				async:false, 
				data: { sendto: sendto },
				success: function(xml){
					var	message=xml.lastChild.firstChild.nodeValue;
					if(message!='OK'){
						$('#checksendto').html('&nbsp;&nbsp;'+message).fadeIn();
						divDance('checksendto');
					}else{
						$('#checksendto').html('OK').fadeIn();
						divDance('checksendto');
						result=true;
					}
				}
			});
			return result;
		}
	}
	
	function expand(id){
		if(id=='usermanage'){
			$("ul#usermanage").toggle(); 
		}else{
			$('ul#userpms').toggle();
		}
	}
	
	function checksize(){
		var pmssize=300-$('#content').val().length;
		if(pmssize<=0){
			$('#content').val($('#content').val().substr(0,300));
			pmssize=0;
		}
		$('#pmssize').html('您还可以输入&nbsp;'+pmssize+'个字符');
		divDance('pmssize');
	}
	function changGroup(obj){
		if(obj.value != 0){
			$("#checksendto").attr('innerHTML','');
		}else{
			if(!$.trim($('#sendto').val())){
				$("#checksendto").attr('innerHTML','&nbsp;&nbsp;收件人不能为空');
				divDance('checksendto');
			}
		}
	}
</script>
<div class="hd_map">
	<a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt; <a href="index.php?pms">短消息</a> &gt; 发短消息</div>
<div class="r w-710 o-v m-t10 p-b10 gl_manage_main">
	<h2 class="h3 bold">发短消息</h2>
	<form name="sendform" action="index.php?pms-sendmessage" method="post" onsubmit="return docheck();">
	<ul id="send" class="col-ul">
		<li><span>收件人</span><input id="sendto" name="sendto" type="text" class="inp_txt"  onblur="checkrecipient();" value="<?php echo $sendto?>" />
		<?php if($usergroups) { ?>
			<select id="usergroup" name="usergroup" class="m-lr8" onchange="changGroup(this)" >
				<option value="0">请选择</option>
				<option value="99999">全部</option>
				<?php foreach((array)$usergroups as $usergroup) {?>
				<option value="<?php echo $usergroup['groupid']?>"><?php echo $usergroup['grouptitle']?></option>
				<?php } ?>
			</select>
		<?php } ?>
		<br /><p id="checksendto">添加多个发件人(群发上限10人)时请用英文 "," 隔开(如:绚烂,夜静然,fairy)，<?php if($usergroups) { ?>下拉框里选择群发的对象(注意:群发消息不能存入草稿箱)<?php } ?></p></li>
		<li><span>主题</span><input name="subject" type="text" class="inp_txt" id="subject"  value="<?php echo $subject?>" maxlength="35" onblur="checksubject();" /><br/><p id="subjecttip">您可以输入35个字符</p></li>
		<li><span>内容</span><textarea cols="60" name="content" rows="6" id="content" onblur="checkcontent();" onKeyUp="checksize();"><?php echo $message?></textarea><br /><p id="pmssize">您可以输入<strong>300</strong>个字符</p></li>
		<li><input id="checkbox" name="checkbox" type="checkbox"  class="m-r8"/>不发送，只保存到草稿箱中</li>
		<li><input name="submit" type="submit"  value="发送" class="btn_inp"/></li>
	</ul>
	</form>
</div>
<div class="l w-230">
<div class="m-t10 p-b10 sidebar gl_manage">
	<h2 class="col-h2"><span onclick="expand('usermanage');">个人管理</span></h2>	
	<ul id="usermanage">
		<li><a href="index.php?user-profile" target="_self"><img alt="" src="style/default/gl_manage/grzl.gif" />个人资料</a></li>
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
		<li><a href="index.php?pms-sendmessage" target="_self" class="on"><img src="style/default/gl_manage/fdxx.gif"/>发短消息</a></li>
		<li><a href="index.php?pms-box-drafts" target="_self"><img src="style/default/gl_manage/cgx.gif" />草稿箱</a></li>
		<li><a href="index.php?pms-blacklist" target="_self"><img src="style/default/gl_manage/hllb.gif"/>忽略列表</a></li>
	</ul>
</div>
</div>
<div class="c-b"></div>
<?php include $this->gettpl('footer');?>