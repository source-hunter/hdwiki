<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<div id="append_parent"></div>
<script type="text/javascript">
var userAgent = navigator.userAgent.toLowerCase();
var is_ie = (userAgent.indexOf('msie') != -1 && !(userAgent.indexOf('opera') != -1 && opera.version())) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
function copyTxt(txt){
	if(is_ie) {
		clipboardData.setData('Text',txt);
		alert ("��ַ�Ѿ����Ƶ����ļ�������n������ʹ��Ctrl+V��ݼ�ճ������Ҫ�ĵط�");
	} else {
		prompt("�븴��ע���ַ:",txt); 
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
	<a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt; <a href="index.php?user-profile">���˹���</a> &gt; ������������</div>
	
	<div class="r w-710 o-v m-t10 p-b10 gl_manage_main">
		<h2 class="h3 bold">�ҵĺ�����������</h2>
		
		
		
		<div style="padding: 10px 20px; color: #666;">
		������ͨ��QQ��MSN��IM���ߣ����߷����ʼ�������������Ӹ�����ĺ��ѣ��������Ǽ��������<br />
			<span style="font-size: 16px; font-weight: bold;">
			<a onclick="javascript:copyTxt(this.href);return false;" href="<?php echo $invite_url?>"><?php if(!empty($invite_url)) { ?><?php echo $invite_url?><?php } ?></a>
			</span>
		</div>
		<br />
		
		<h2 class="h3 bold">�����ѷ��� Email ����</h2>
		<div style="padding: 10px 20px; color: #666;">
		ͨ��ֱ�ӷ����ʼ��ķ�ʽ���������ĺ��ѡ�<br />
		<form action="index.php?user-invite" style="color: #000;" method="POST">
		���������ĺ���Email��ַ�����Emailʹ��","�ָ�:<br />
		<textarea name="toemails" cols="70" rows="8" /><?php if(!empty($toemails)) { ?><?php echo $toemails?><?php } ?></textarea><br />
		<span style="color:red"><?php if(!empty($mail_error)) { ?><?php echo $mail_error?><?php } ?></span><br />
		��Ժ���˵�Ļ�:<br />
		<textarea name="ps" cols="70" rows="3" onkeyup="autoPreview(this.value, 'PreContainer')"><?php if(!empty($ps)) { ?><?php echo $ps?><?php } ?></textarea><br />
		<span style="color:red"><?php if(!empty($ps_error)) { ?><?php echo $ps_error?><?php } ?></span><br />
		<input type="submit" name="submit" value="��������" />
		</form>
		<br />
		���뺯Ԥ����
		<div style="border: 1px solid #CCC; background: #F0F0F0; padding: 10px; line-height: 14px;">
		<?php if(!empty($preview)) { ?><?php echo $preview?><?php } ?>
		</div>
		</div>
		
		<br />
	
</div>

<div class="l w-230">
<div class="m-t10 p-b10 sidebar gl_manage">
	<h2 class="col-h2"><span onclick="expand('usermanage');">��������</span></h2>	
	<ul id="usermanage">
		<li><a href="index.php?user-profile" target="_self"><img alt="" src="style/default/gl_manage/grzl.gif" />��������</a></li>
		<li><a href="index.php?user-editprofile" target="_self"><img src="style/default/gl_manage/grzl_set.gif"/>������������</a></li>
		<li><a href="index.php?user-editpass" target="_self"><img src="style/default/gl_manage/change_pw.gif"/>�޸�����</a></li>
		<li><a href="index.php?user-editimage" target="_self"><img src="style/default/gl_manage/grzl_set.gif" />�޸�ͷ��</a></li>
		<li><a href="index.php?doc-managesave" target="_self"><img src="style/default/gl_manage/ctbccgx.gif"/>��������ݸ���</a></li>
		<li><a href="index.php?user-invite" target="_self" class="on"><img src="style/default/gl_manage/invite.png"/>����ע��</a></li>
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
</div>
<div class="c-b"></div>
<?php include $this->gettpl('footer');?>