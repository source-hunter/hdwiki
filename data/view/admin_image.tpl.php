<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script type="text/javascript">
var searchdata = "<?php echo $searchdata?>".replace(/-/g, ",");
var manage = function (number){
	if($("input[name='chkdid[]']:checked").length==0){
		alert('����ѡ��ͼƬ');
		return false;
	}else{
		var imageform=$("form[name='imageform']");
		var tip=['ȷ��Ҫɾ��ͼƬ��?','ȷ��Ҫ���þ���ͼƬ��?','ȷ��Ҫȡ������ͼƬ��?'];
		var action=['remove','editimage-focus-1','editimage-focus-0'];
		if(confirm(tip[number])==false){
			return false;
		}else{
			imageform.attr("action", 'index.php?admin_image-'+action[number]+'-'+searchdata);
			imageform.submit();
		}
	}
}
var selectAll = function (){
	$("input[name='chkdid[]']").attr('checked',$("input[name='checkbox']").attr('checked'));
}
$(document).ready(function(){
	$("img").each(function(i){
		var img = this; 
		img.title="��������ͼ";
	}).click(function(){
		var url=$(this).attr("src");
		$.dialog.open('image', 'ͼƬ���', 'img:'+url);
	});
});
</script>

<p class="map">���ݹ���ͼƬ�ٿ�</p>

<div class="tpbk">
	<form name="search" method="post" action="index.php?admin_image-default" >
	<ul class="col-ul ul_li_sp m-t10">
		<li><span>����������: </span><select name="qcattype">
						<option value="0" >���з���</option>
						<?php echo $catstr?>
					</select></li>
		<li><span>��������: </span><input name="qtitle" type="text" class="inp_txt m-r10" value="<?php echo $qtitle?>"/></li>
		<li><span>��������������:</span><input name="qauthor" type="text" class="inp_txt m-r10" value="<?php echo $qauthor?>"/></li>
		<li><span>����������ʱ��:</span><input readonly name="qstarttime" type="text" class="inp_txt" onclick="showcalendar(event, this);" value="<?php echo $qstarttime?>" /> �� <input readonly name="qendtime" type="text" class="inp_txt" onclick="showcalendar(event, this);" value="<?php echo $qendtime?>"/></li>
		<li><input name="submit" type="submit" value="�� ��"  class="inp_btn"/></li>
	</ul>
	</form>
	<h3 class="tol_table">[ �� <b><?php echo $docsum?></b> ��ͼƬ ]</h3>
	<form method="post" name="imageform">
	<table class="table w-img">
		<thead>
			<tr>
				<td style="width:60px;">ѡ��</td>
				<td style="width:100px;">ͼƬ</td>
				<td style="width:150px;">����</td>
				<td style="width:80px;">��С</td>
				<td style="width:280px;">����</td>
				<td>����</td>
			</tr>
		</thead>
		<?php foreach((array)$imagewiki as $image) {?>	
		<tr>
			<td><input type="checkbox" name="chkdid[]" value="<?php echo $image['id']?>" /></td>
			<td><a class="a-img3">
				<?php if(file_exists($image['attachment'])) { ?>
					<img src="<?php echo $image['attachment']?>" />
				<?php } else { ?>
					<img src="style/default/plugin.jpg" />
				<?php } ?></a>
			</td>
			<td><?php echo $image['filename']?></td>
			<td><?php echo $image['filesize']?></td>
			<td><?php echo $image['description']?></td>
			<td><?php if($image['focus']==1) { ?><font class="red">��</font><?php } else { ?>��<?php } ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td><label> <input name="checkbox" type="checkbox" id="chkall" onclick="selectAll();"> ȫѡ</label></td>
			<td colspan="5"><input name="Button1" type="button" value="�������վ"  class="inp_btn2 m-r10" onclick="manage(0);" /><input name="Button1" type="button" value="�� ��"  class="inp_btn2 m-r10" onclick="manage(1);" /><input name="Button1" type="button" value="ȡ���Ӿ�"  class="inp_btn2" onclick="manage(2);" /></td>
		</tr>
		<tr>
			<td colspan="6"><p class="fenye a-r"><?php echo $departstr?></p></td>
		</tr>
	</table>
	</form>
</div>

<?php include $this->gettpl('admin_footer');?>
