<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script language="JavaScript" >
function cofirmimport(filename){
	if(confirm('导入该sql文件会覆盖原来的数据!是否导入？')==false){
		return false;
	}else{
		window.location='index.php?admin_db-import-'+filename.replace(/\./g,'*');
	}
}
function removefile(filename){
	if(confirm('删除数据库备份文件不可恢复!是否删除？')==false){
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
		infotip.html("全不选");
	}else{
		checkAll(childname,false);
		infotip.html("全选");
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
		alert('文件名错误,请以字母或数字开头,并且名称中只允许有字母,数字和下划线');
		return false;
	}else if(isNaN($('#sizelimit').val())==true){
		alert('分卷大小请填写数字');
		return false;
	}else if($('#sizelimit').val()<512){
		alert('文件大小限制不要小于512K');
		return false;
	}else{
		return true;
	}
}
</script>
<p class="map">数据库管理：数据库备份</p>
<h3 class="col-h4 m-t10">数据库备份</h3>

<form action="index.php?admin_db-backup" method="post" onsubmit="return docheck();">
<table class="table">
	<colgroup>
		<col style="width:23%;"></col>
		<col style="width:23%;"></col>
		<col style="width:23%;"></col>
		<col></col>
	</colgroup>
	<tr>
		<td colspan="4"><strong>备份类型:</strong></td>
	</tr>
	<tr>
		<td><input type="radio" class="box" name="type" value="full" class="radio" checked="checked" onclick="document.getElementById('showtables').style.display='none'">
			全部备份(推荐)</td>
		<td colspan="3">备份数据库所有表</td>
	</tr>
	<tr>
		<td><input type="radio" class="box" name="type" value="stand" class="radio"  onclick="document.getElementById('showtables').style.display='none'">
			标准备份</td>
		<td  colspan="3">备份常用的数据表,包括分类表、词条表、历史表、用户表</td>
	</tr>
	<tr>
		<td><input type="radio" class="box" name="type" value="min" class="radio" onclick="document.getElementById('showtables').style.display='none'">
			最小备份</td>
		<td  colspan="3">仅包括词条表、用户表</td>
	</tr>
	<tr>
		<td><input type="radio" class="box" name="type" value="custom" class="radio" onclick="document.getElementById('showtables').style.display=''">
			自定义备份</td>
		<td colspan="3">根据自行选择备份数据表</td>
	</tr>
	
	<tbody id="showtables" style="display:none">
		<tr>
			<td colspan="4"><input name="chkall" id="chkall" onClick="selectAll('tip','chkall','tables[]');" type="checkbox"><label id="tip">全选</label></td>
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
		<td  colspan="4"><strong>其他选项:</strong></td>
	</tr>
	<tr>
		<td>备份文件名</td>
		<td colspan="3"><input type="text" class="box" id="sqlfilename" name="sqlfilename" value="<?php echo $sqlfilename?>" size="25">.sql</td>
	</tr>
	<tr>
		<td>分卷文件大小</td>
		<td colspan="3"><input type="text" class="box" id="sizelimit" name="sizelimit" value="2048" size="15">KB</td>
	</tr>
	<tr>
		<td>压缩分卷文件</td>
		<td colspan="3"><input type="radio" class="box" name="compression" value="1" >多分卷压缩成一个文件</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="3"><input type="radio" class="box" name="compression" value="0" checked>不压缩</td>
	</tr>
	<tr>
		<td colspan="4"><input name="backupsubmit" type="submit" class="inp_btn2" value="数据库备份" /></td>
	</tr>
</table>
</form>


<h3 class="col-h4 m-t10">数据库还原:</h3>
<table class="table">
	<thead>
		<tr>
			<td style="width:320px;">SQL文件</td>
			<td style="width:100px;">文件大小</td>
			<td style="width:160px;">文件修改日期</td>
			<td style="width:100px;">导入文件</td>
			<td style="width:100px;">下载文件</td>
			<td >删除文件</td>
		</tr>
	</thead>
	<?php foreach((array)$filename as $key=>$value) {?>
	<?php if(isset($value)=="true") { ?>
	<tr>
		<td><?php echo $value['filepath']?></td>
		<td><?php echo $value['filesize']?></td>
		<td><?php echo $value['filectime']?></td>
		<td><a href="#" onclick="cofirmimport('<?php echo $value['filename']?>')" >导入文件</a></td>
		<td><a href="#" onclick="download('<?php echo $value['filename']?>')">下载文件</a></td>
		<td><a href="#" onclick="removefile('<?php echo $value['filename']?>')">删除文件</a></td>
	</tr>
	<?php } else { ?>
	<tr>
	<td>{<?php echo $lang?>.dbBackupNoFile}</td>
	</tr>
	<?php } ?>
	<?php }?>
</table>
<?php include $this->gettpl('admin_footer');?>