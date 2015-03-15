<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript" src="js/popWindow.js"></script>
<script type="text/javascript">
	var preid='';
	function showpm(id,type,isnew) {
		var row1=id+'-message';
		var row2=id+'-operation';
		var td=id+'-td';
		if($('#'+row1)[0].style.display == 'none'){
			$('#'+row1)[0].style.display = '';
		}else{
			$('#'+row1)[0].style.display = 'none';
		}		
		if($('#'+row2)[0].style.display == 'none'){
			$('#'+row2)[0].style.display = '';
		}else{
			$('#'+row2)[0].style.display = 'none';
		}
//		$('#'+row1).toggle();
//		$('#'+row2).toggle();
		if(preid!='' && preid!=id){
			var row1=preid+'-message';
			var row2=preid+'-operation';
			$('#'+row1).css('display','none');
			$('#'+row2).css('display','none');
		}
		preid=id;
		if(type=='inbox' && isnew==1){
			jQuery.ajax({
				url: "index.php?pms-setread",
				cache: false,
				dataType: "xml",
				type:"post",
			//	async:false, 
				data: {id:id},
				success: function(xml){
					message=xml.lastChild.firstChild.nodeValue;
					if(message){
					$('#'+td).css('fontWeight','normal');
					}
				}
			});
		}	
	}
	
	function deletepms(id,type){
		if (confirm('删除')){
			type=(type=='inbox')?1:2;
			jQuery.ajax({
				url: "index.php?pms-remove-single",
				type:"post",
				data: {id:id,type:type}
			});
			row1=id+'-pms';
			row2=id+'-message';
			row3=id+'-operation';
			$('#'+row1).css('display','none');
			$('#'+row2).css('display','none');
			$('#'+row3).css('display','none');
			$('#'+id+'-td').parents('tr').remove();
		}
	}
	
	function delsubmit(){
		if($("input[name='checkid[]']:checked").length==0){
			alert('请选择删除');
		}else{
			if (confirm('删除')){
				$("#form1").submit();
			}
		}
		return false;
	}
	
	function expand(id){
		if(id=='usermanage'){
			$("ul#usermanage").toggle(); 
		}else{
			$('ul#userpms').toggle();
		}
	}
	
	function selectAll(){
		$("input[name='checkid[]']").attr('checked',$("input[name='checkbox']").attr('checked'));
	}	
</script>
<div class="hd_map">
	<a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt; <a href="index.php?pms">短消息</a> &gt; <?php if($type=='outbox' ) { ?>发件箱<?php } ?><?php if($type=='drafts') { ?>草稿箱<?php } ?><?php if($type=='inbox') { ?>收件箱<?php } ?></div>
<div class="r w-710 o-v m-t10 p-b10 gl_manage_main">
	<h2 class="h3 bold"><?php if($type=='outbox') { ?>发件箱<?php } ?><?php if($type=='drafts') { ?>草稿箱<?php } ?><?php if($type=='inbox') { ?>收件箱<?php } ?><?php if($count>200 && $type=='inbox') { ?>  &nbsp;&nbsp;<span style="color:#FF0000">[您的信箱已满，在阅读短消息前必须删除一些不用的信息]</span><?php } ?></h2>
	<form  name="delform" id="form1" action="index.php?pms-remove-muli-<?php echo $type?>-<?php echo $current?>" method="post">
	<?php if($type=='inbox') { ?>
		<span style="border:1px solid #999999; padding:3px;<?php if($current=='owner') { ?>background:#E0E0E0 none repeat scroll 0 0<?php } ?>" ><a href="index.php?pms-box-inbox-owner" <?php if($current=='owner') { ?>style='color:red'<?php } ?>>私人消息</a></span>
		<span style="border:1px solid #999999; padding:3px;<?php if($current!='owner') { ?>background:#E0E0E0 none repeat scroll 0 0<?php } ?>" ><a href="index.php?pms-box-inbox-system" <?php if($current!='owner') { ?>style='color:red'<?php } ?>>系统消息</a></span>
	<?php } ?>
	<table cellspacing="0" cellpadding="0" class="table l  message">
	<thead class="bold">
		<tr>
			<td style="width: 25px;"><input type="checkbox" onclick="selectAll();" id="chkall" name="checkbox"/></td>
			<td style="width: 330px;">标题</td>
			<td style="width: 184px;"><?php if($type=='inbox') { ?>来自<?php } else { ?>收件人<?php } ?></td>
			<td style="width: 130px;">时间</td>
		</tr>
	</thead>
	<tbody>
	<?php if($pmslist) { ?>
		<?php foreach((array)$pmslist as $pms) {?>
		<tr>
			<td><input name="checkid[]" type="checkbox" value="<?php echo $pms['id']?>" /></td>
			<td <?php if($pms['new']==1 and $type=='inbox') { ?>class="bold"<?php } ?> id="<?php echo $pms['id']?>-td"><?php if($type=='drafts') { ?><a href="index.php?pms-sendmessage-drafts-<?php echo $pms['id']?>" ><?php } else { ?><a href="javascript:void(0)" onclick="showpm('<?php echo $pms['id']?>','<?php echo $type?>','<?php echo $pms['new']?>');" ><?php } ?><?php echo $pms['subject']?></a></td>
			<td><?php if($type=='inbox') { ?><?php if($current=='owner') { ?><?php echo $pms['from']?><?php } else { ?>系统<?php } ?><?php } else { ?><?php echo $pms['to']?><?php } ?></td>
			<td><?php echo $pms['time']?></td>
		</tr>
		<tr  style="display:none;" id="<?php echo $pms['id']?>-message">
			<td colspan="4"  ><?php echo $pms['message']?></td>
		</tr>
		<tr  style="display:none" id="<?php echo $pms['id']?>-operation">
			<td colspan="4"  >&nbsp;<?php if($type=='inbox' && $group=='owner') { ?><a href="index.php?pms-sendmessage-reply-<?php echo $pms['id']?>">回复</a>&nbsp;&nbsp;<?php } ?><a href="index.php?pms-sendmessage-forward-<?php echo $pms['id']?>">转发</a>&nbsp;&nbsp;<a href='#' onclick="deletepms('<?php echo $pms['id']?>','<?php echo $type?>');">删除</a>&nbsp;&nbsp;<a href='#' onclick="showpm('<?php echo $pms['id']?>');">关闭</a></td>
		</tr>
		<?php } ?>
	<?php } ?>
	</tbody>	
	<tfoot>
		<?php if($type=='inbox' && $count>200) { ?>
		<tr>
			<td colspan="2" align="center"><span style="color:red; text-align:center">您的收件箱邮件超过系统限制的200条,请及时删除,否则会影响您新邮件的接收!</span></td>
		</tr>
		<?php } ?>		
		<tr>
			<td colspan="2"><a href="#" onclick='delsubmit();'>[删除]</a></td>
			<td  class="a-r" colspan="2"><span class="message_tip"><?php if($type=='inbox') { ?>共有短消息: <?php echo $count?> 私有: <?php echo $ownercount?> 系统: <?php echo $publiccount?>, 短消息上限: 200 <?php } ?></span></td>
		</tr>
	</tfoot>
	</table>	
	<div class="c-b"></div>
	  <div id="fenye" class="m-t10 a-r"><?php echo $departstr?></div>
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
		<li><a href="index.php?pms-box-inbox" target="_self" <?php if($type=='inbox') { ?> class="on" <?php } ?>><img alt="" src="style/default/gl_manage/sjx.gif" />收件箱</a></li>
		<li><a href="index.php?pms-box-outbox" target="_self" <?php if($type=='outbox') { ?> class="on" <?php } ?> ><img src="style/default/gl_manage/fjx.gif"/>发件箱</a></li>
		<li><a href="index.php?pms-sendmessage" target="_self"><img src="style/default/gl_manage/fdxx.gif"/>发短消息</a></li>
		<li><a href="index.php?pms-box-drafts" target="_self" <?php if($type=='drafts') { ?> class="on" <?php } ?>><img src="style/default/gl_manage/cgx.gif" />草稿箱</a></li>
		<li><a href="index.php?pms-blacklist" target="_self"><img src="style/default/gl_manage/hllb.gif"/>忽略列表</a></li>
	</ul>
</div>
</div>
<div class="c-b"></div>
<?php include $this->gettpl('footer');?>