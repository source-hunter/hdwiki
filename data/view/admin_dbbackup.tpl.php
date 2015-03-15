<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script language="JavaScript" >
function cofirmimport(filename){
	if(confirm('�����sql�ļ��Ḳ��ԭ��������!�Ƿ��룿')==false){
		return false;
	}else{
		window.location='index.php?admin_db-import-'+filename.replace(/\./g,'*');
	}
}
function removefile(filename){
	if(confirm('ɾ�����ݿⱸ���ļ����ɻָ�!�Ƿ�ɾ����')==false){
		return false;
	}else{
		window.location='index.php?admin_db-remove-'+filename.replace(/\./g,'*');
	}
}
function download(filename){
	window.location='index.php?admin_db-downloadfile-'+filename.replace(/\./g,'*');
}
function selectAll(tipid,chkid,childname){
	var chk=$("#"+chkid);
	var infotip=$("#"+tipid);
	if(chk.attr('checked')==true){
		checkAll(childname,true);
		infotip.html("ȫ��ѡ");
	}else{
		checkAll(childname,false);
		infotip.html("ȫѡ");
	}
}
function checkAll(eleName,state){
	$("input[name='"+eleName+"']").attr('checked',state);
}
function checkname(s){ 
	var patrn=/^[a-zA-Z0-9]([a-zA-Z0-9]|[_])*$/;
	return patrn.test(s) ;
}
function docheck(){
	if($.trim($('#sqlfilename').val())==''|| !checkname($.trim($('#sqlfilename').val()))){
		alert('�ļ�������,������ĸ�����ֿ�ͷ,����������ֻ��������ĸ,���ֺ��»���');
		return false;
	}else if(isNaN($('#sizelimit').val())==true){
		alert('�־��С����д����');
		return false;
	}else if($('#sizelimit').val()<512){
		alert('�ļ���С���Ʋ�ҪС��512K');
		return false;
	}else{
		return true;
	}
}
</script>
<p class="map">���ݿ�������ݿⱸ��</p>
<h3 class="col-h4 m-t10">���ݿⱸ��</h3>

<form action="index.php?admin_db-backup" method="post" onsubmit="return docheck();">
<table class="table">
	<colgroup>
		<col style="width:23%;"></col>
		<col style="width:23%;"></col>
		<col style="width:23%;"></col>
		<col></col>
	</colgroup>
	<tr>
		<td colspan="4"><strong>��������:</strong></td>
	</tr>
	<tr>
		<td><input type="radio" class="box" name="type" value="full" class="radio" checked="checked" onclick="document.getElementById('showtables').style.display='none'">
			ȫ������(�Ƽ�)</td>
		<td colspan="3">�������ݿ����б�</td>
	</tr>
	<tr>
		<td><input type="radio" class="box" name="type" value="stand" class="radio"  onclick="document.getElementById('showtables').style.display='none'">
			��׼����</td>
		<td  colspan="3">���ݳ��õ����ݱ�,�����������������ʷ���û���</td>
	</tr>
	<tr>
		<td><input type="radio" class="box" name="type" value="min" class="radio" onclick="document.getElementById('showtables').style.display='none'">
			��С����</td>
		<td  colspan="3">�������������û���</td>
	</tr>
	<tr>
		<td><input type="radio" class="box" name="type" value="custom" class="radio" onclick="document.getElementById('showtables').style.display=''">
			�Զ��屸��</td>
		<td colspan="3">��������ѡ�񱸷����ݱ�</td>
	</tr>
	
	<tbody id="showtables" style="display:none">
		<tr>
			<td colspan="4"><input name="chkall" id="chkall" onClick="selectAll('tip','chkall','tables[]');" type="checkbox"><label id="tip">ȫѡ</label></td>
		</tr>
		<tr>
		<?php foreach((array)$tables as $key=>$value) {?>
		<?php if($key%4!=0) { ?>
		<td><input type="checkbox" value="<?php echo $value?>" name="tables[]"/><?php echo $value?></td>
		<?php } else { ?>
		</tr>
		<tr>
			<td><input type="checkbox" value="<?php echo $value?>" name="tables[]"/><?php echo $value?></td>
		<?php } ?>
		<?php }?>
		</tr>
	</tbody>

	<tr>
		<td  colspan="4"><strong>����ѡ��:</strong></td>
	</tr>
	<tr>
		<td>�����ļ���</td>
		<td colspan="3"><input type="text" class="box" id="sqlfilename" name="sqlfilename" value="<?php echo $sqlfilename?>" size="25">.sql</td>
	</tr>
	<tr>
		<td>�־��ļ���С</td>
		<td colspan="3"><input type="text" class="box" id="sizelimit" name="sizelimit" value="2048" size="15">KB</td>
	</tr>
	<tr>
		<td>ѹ���־��ļ�</td>
		<td colspan="3"><input type="radio" class="box" name="compression" value="1" >��־�ѹ����һ���ļ�</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="3"><input type="radio" class="box" name="compression" value="0" checked>��ѹ��</td>
	</tr>
	<tr>
		<td colspan="4"><input name="backupsubmit" type="submit" class="inp_btn2" value="���ݿⱸ��" /></td>
	</tr>
</table>
</form>


<h3 class="col-h4 m-t10">���ݿ⻹ԭ:</h3>
<table class="table">
	<thead>
		<tr>
			<td style="width:320px;">SQL�ļ�</td>
			<td style="width:100px;">�ļ���С</td>
			<td style="width:160px;">�ļ��޸�����</td>
			<td style="width:100px;">�����ļ�</td>
			<td style="width:100px;">�����ļ�</td>
			<td >ɾ���ļ�</td>
		</tr>
	</thead>
	<?php foreach((array)$filename as $key=>$value) {?>
	<?php if(isset($value)=="true") { ?>
	<tr>
		<td><?php echo $value['filepath']?></td>
		<td><?php echo $value['filesize']?></td>
		<td><?php echo $value['filectime']?></td>
		<td><a href="#" onclick="cofirmimport('<?php echo $value['filename']?>')" >�����ļ�</a></td>
		<td><a href="#" onclick="download('<?php echo $value['filename']?>')">�����ļ�</a></td>
		<td><a href="#" onclick="removefile('<?php echo $value['filename']?>')">ɾ���ļ�</a></td>
	</tr>
	<?php } else { ?>
	<tr>
	<td>{<?php echo $lang?>.dbBackupNoFile}</td>
	</tr>
	<?php } ?>
	<?php }?>
</table>
<?php include $this->gettpl('admin_footer');?>