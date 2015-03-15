<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script type="text/javascript">
function showCloseWebsiteReason(value){
	if (value == '0'){
		$("#closeWebsiteReason").hide();
	}else{
		$("#closeWebsiteReason").show();
	}
}

$(document).ready(function(){
	if ("<?php echo $basecfginfo['close_website']?>" == '1'){
		$("#closeWebsiteReason").show();
	}
	
	$(":radio[name*=close_website]").click(function(){
		showCloseWebsiteReason(this.value);
	});
});
</script>
<p class="map">ȫ�֣�վ������</p>
<form method="POST" action="index.php?admin_setting-base">
<table class="table">
	<colgroup>
		<col  style="width:300px;"></col>
		<col></col>
	</colgroup>
	<tr>
		<td><span>��վ����</span>
			<input maxlength="30" class="inp_txt" name="setting[site_name]" type="text" value="<?php echo $basecfginfo['site_name']?>" />
		</td>
		<td class="v-b" >��վ���ƣ�����ʾ��ҳ��Title��</td>
	</tr>	
	<tr>
		<td><span>��վURL</span><input maxlength="120" class="inp_txt" name="setting[site_url]" type="text" value="<?php echo $basecfginfo['site_url']?>" /></td>
		<td class="v-b" ><p>��վ URL����Ϊ��վ��Դ�ĸ�·��ʹ��<br/>
			������Ĵ˴�URL����Ҫȥ��̨������������<br />�Ա��ƶ˳�ʼ����վ��Ϣ��<br />
			ע: ��������д������ܵ���ͼƬ��ʾ�쳣</p>
			</td>
	</tr>	
	<tr>
		<td><span>վ�ڹ���</span><textarea cols="45" name="setting[site_notice]" rows="5" class="textarea"><?php echo $setting['site_notice']?></textarea></td>
		<td class="v" ><p>վ�ڹ�������ɹ���Ա������ӣ�����ʹ��Ĭ�Ϲ�������<br/> 
����Ա�����޸��ı����еĹ������ݺ󣬵�����水ť<br/>  
����ʹ��Ĭ�Ϲ������ݣ�ֻ�轫�����������ռ��ɡ� <br/> </p>
</td>
	</tr>
	<tr>
		<td><span>��վ������Ϣ</span><input maxlength="20" class="inp_txt" name="setting[site_icp]" type="text" value="<?php echo $basecfginfo['site_icp']?>" /></td>
		<td class="v-b" >�����վ�ѱ������ڴ����룬������ʾ��ҳ��ײ���û��������</td>
	</tr>	
	<tr>
		<td><span>������ͳ�ƴ���</span><textarea class="textarea" rows="6" name="setting[statcode]" cols="50" ><?php echo $basecfginfo['statcode']?></textarea></td>
		<td class="v-t" ><p>ҳ��ײ�������ʾ������ͳ��</p></td>
	</tr>
	<tr>
		
		<td><span>�������</span><br/>�����û��Ƿ����ѡ����</td>
		<td>
			<label><input type="radio"  name="setting[style_user_select]" value="1" <?php if($basecfginfo['style_user_select']=='1') { ?>checked<?php } ?>/>��</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio"  name="setting[style_user_select]" value="0" <?php if($basecfginfo['style_user_select']=='0') { ?>checked<?php } ?>/>��</label>
		</td>
	</tr>
	<tr>
		<td><span>�Ƿ���Ҫ������ǰ�汾ģ��</span><br />ѡ����ݣ���ҳ����ȡ��ǰģ����������ݣ������ٻ�ȡ���ݡ�</td>
		<td>
			<label><input type="radio"  name="setting[compatible]" value="1" <?php if($basecfginfo['compatible']=='1') { ?>checked<?php } ?>/>��</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio"  name="setting[compatible]" value="0" <?php if($basecfginfo['compatible']=='0') { ?>checked<?php } ?>/>��</label>
		</td>
	</tr>
	<tr>
		<td><span>�ر���վ</span><br />��ʱ��վ��رգ��������޷����ʣ�����Ӱ�����Ա���ʡ�</td>
		<td>
			<label><input type="radio"  name="setting[close_website]" value="1" <?php if($basecfginfo['close_website']=='1') { ?>checked<?php } ?>/>��</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio"  name="setting[close_website]" value="0" <?php if($basecfginfo['close_website']=='0') { ?>checked<?php } ?>/>��</label>
		</td>
	</tr>
	<tr id="closeWebsiteReason" style="display:none">
		<td><span>�ر�ԭ��</span><font color=red>��վ�رպ����Ա��¼�������ڵ�ַ������ http://wiki·��/?user-login ����������½��</font></td>
		<td>
		<textarea class="textarea" name="setting[close_website_reason]" style="width:300px" rows="3"><?php echo $basecfginfo['close_website_reason']?></textarea>
		</td>
	</tr>
</table>
<p class="submit">
	<input class="inp_btn" name="settingsubmit" type="submit" value="�� ��" />&nbsp;&nbsp;
	<input class="inp_btn" type="reset" value="����" />
</p>
</form>
<?php include $this->gettpl('admin_footer');?>