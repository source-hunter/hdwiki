<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script type="text/javascript">
var searchdata = "<?php echo $searchdata?>".replace(/-/g, ",");
function ChangeRadionew(number){
	if($("input[name='chkdid[]']:checked").length==0){
		$.dialog.box('immageshow', 'ע��', '��ѡ�����!');
		return false;
	}else{
		switch(number){
			case 2:
				if(confirm('ȷ����˴�����')==false){
					return false;
				}else{
					document.formdoclist.action='index.php?admin_doc-audit'+'-'+searchdata;
					document.formdoclist.submit();
				}
			break;
			case 3:
				if(confirm('ȷ������������')==false){
					return false;
				}else{
					document.formdoclist.action='index.php?admin_doc-lock'+'-'+searchdata;
					document.formdoclist.submit();
				}
		 	break;
			case 4:
				if(confirm('ȷ�Ͻ���������')==false){
					return false;
				}else{
					document.formdoclist.action='index.php?admin_doc-unlock'+'-'+searchdata;
					document.formdoclist.submit();
				}
		 	break;
			case 7:
				if (confirm('ɾ��')==false){
					return false;
				}else{
					document.formdoclist.action='index.php?admin_doc-remove'+'-'+searchdata;
					document.formdoclist.submit();
				}
			break;
		 	case 1:
				if(confirm('ȷ���޸Ĵ������?')==false){
					return false;
				}else{
					if($('#doctype').val()==0){
						document.formdoclist.action='index.php?admin_doc-cancelrecommend'+'-'+searchdata;
					}else{
						document.formdoclist.action='index.php?admin_doc-recommend-'+$('#doctype').val()+'-'+searchdata;
					}
					document.formdoclist.submit();
				}
		 	break;
		 	default:
				$.dialog.box('immageshow', 'ע��', '��ѡ�����ѡ��!');
		 	break;
		}
		
		
	}
}
function changecategory(cats){
	if(!cats){
		$('#scnames').fadeOut();
		$('#scnames').html('&nbsp;&nbsp;���಻����Ϊ��').fadeIn();
		return false;
	}
	var chk=document.getElementsByName('chkdid[]');
	var num='',	tag='';
	for(var i=0;i<chk.length;i++){	
		if(chk[i].checked==true){
			num+=tag+chk[i].value;
			tag=",";
		}
	}
	$.post(
		"index.php?admin_doc-move",
		{chkdid:num,cid:cats},
		function(xml){
			if(xml!='0'){
				//MSG = ' �ƶ�����ɹ�';
				MSG = '';
				location.href="index.php?<?php echo $searchdata?>";
			}else{
				MSG = ' �ƶ�����ʧ��';
			}
			if(MSG) {
				$.dialog.box('changecategory', '�ƶ�����', MSG);
			}
			
		}
	);
	return true;
}
function inputnewname(){
		if (CheckboxNum('chkdid[]') > 1){
			$.dialog.box('inputnewname', 'ע��', '��ֻѡ��һ������');
			return false;
		}else if(CheckboxNum('chkdid[]') == 0){
			$.dialog.box('inputnewname', 'ע��', '��ѡ�����!');
			return false;
		}else{
			
			var name, num=$("input[name='chkdid[]']:checked").val();
			name=num + '_title';

			var msg="������������<input id='newname' name='newname' type='text' class='inp_txt' value='"
			+ document.getElementById(name).value+"' maxlength='80'><br><br>"
			+ "<input name='renamesbumit' type='button' onclick='changename()' class='inp_btn2 m-r10' value='ȷ��'>"
			+ "<input name='cancel' type='button' onclick='$.dialog.close(\"inputnewname\")' class='inp_btn2' value='ȡ��'>";
			
			$.dialog.box('inputnewname', '������', msg);
		}
}
function changename(){
	var num=$("input[name='chkdid[]']:checked").val();
	$.post(
		"index.php?admin_doc-rename",
		{did:num, newname:$('#newname').val()},
		function(xml){
			var Msg = '';
			if(xml=='1'){
				location.href="index.php?<?php echo $searchdata?>";
			}else if(xml=='-2'){
				Msg = ' �������Ѵ���!����������';
			}else if(xml=='-1'){
				Msg = ' �������µ�����!';
			}else{
				Msg = ' ������ʧ��';
			}
			if(Msg) {
				$.dialog.box('inputnewname', '������', Msg);
			}
		}
	);
}

function CheckboxNum(eleName){
	return $("input[name='"+eleName+"']:checked").length;
}

function selectAll(){
	$("input[name='chkdid[]']").attr('checked',$("input[name='checkbox']").attr('checked'));
}
function changepage(){
	document.formdoclist.action='index.php?admin_doc-list';
	document.formdoclist.submit();
}
var catevalue = {
	input:null,
	scids:new Array(),
	scnames:new Array(),
	ajax:function(cateid, E){
		var snum=$("input[name='chkdid[]']:checked").length;
	   	if(snum==0){
			$.dialog.box('catevalue', '����', '��ѡ�����!');
			return false;
  	 	}else if(snum==1 && arguments.length==2){
			var did=$("input[name='chkdid[]']:checked")[0].value;
			$.ajax({
				url: 'index.php?doc-hdgetcat',				
				data: {did:did},
				cache: false,
				dataType: "xml",
				type:"post",
				async:false, 
				success: function(xml){
					var message=xml.lastChild.firstChild.nodeValue;
					if(message!=''){
						eval(message);
					}
				}
			});
		}
		if(!cateid)cateid=0;
		$.ajax({
			url: 'index.php?category-ajax-'+cateid,
			cache: false,
			dataType: "xml",
			type:"get",
			async:false, 
			success: function(xml){
				var message=xml.lastChild.firstChild.nodeValue;
				
				if($('#dialog_category:visible').size()){
					$.dialog.content('category', '<div id="flsx" class="chose_cate">'+message+'</div>');
					catevalue.selectCategory();
				}else{
					$.dialog({
						id:'category',
						title:'�ƶ�����',
						content: '<div id="flsx" class="chose_cate">'+message+'</div>',
						height:450,
						width:680,
						position:'c',
						resizable:0,
						resetTime:0,
						onOk:function(){
							$.dialog.close('category');
							catevalue.ok();
						},
						callback:function(){
							catevalue.selectCategory();
						},
						styleContent:{'text-align':'left', 'overflow-y':'scroll', 'padding-right':'0', height:'380px'},
						styleOk:{'font-size':'14px','line-height':'20px', 'padding':'2px 6px 1px','margin-right':'3px'}
					});
				}
			}
		});
	},
	
	cateOk:function(id,title,handle){
		var point;
		if(handle){
			this.scids.push(id);
			this.scnames.push(title);
		}else{
			for(i=0;i<this.scids.length;i++){
				if(this.scids[i]==id){
					point=i;
				}
			}
			this.scids.splice(point,1);
			this.scnames.splice(point,1);
		}
		catevalue.pushCategory()
	},
	
	pushCategory:function(){
		$('#category').val(this.scids.toString());
		$('#scnames').text(this.scnames.toString());
	},
	
	getCatUrl:function(){
		var catstring='';
		for(i=0;i<this.scids.length;i++){
			catstring=catstring+'<a target="_blank" href="<?php echo $setting['seo_prefix']?>category-view-'+this.scids[i]+'">'+this.scnames[i]+'</a>,';
		}
		catstring=catstring.substring(0, catstring.length-1);   
		return catstring;
	},
	
	selectCategory:function(){
		var cb=$(":checkbox");
		catevalue.pushCategory();
		for(i=0;i<cb.length;i++){
			if(catevalue.inArray(cb[i].id, this.scids)){
				cb[i].checked = true; 
			}
		}		
	},
	
	inArray:function(stringToSearch, arrayToSearch) {
		for (s = 0; s <arrayToSearch.length; s++) {
			if (stringToSearch == arrayToSearch[s]) {
				 return true;
			}
		}
		return false;
	},
	
	removeCateTree:function(){
		this.clear();
		//$('#flsx').hide();
		$.dialog.close('category');
	},
	
	ok:function(){
		if(changecategory(this.scids.toString())){
			this.clear();
			//$('#flsx').hide();
			$.dialog.close('category');
		}
	},
	
	init:function(){
		if('<?php echo $category['cid']?>'!=''){
			this.scids.push(<?php echo $category['cid']?>);
			this.scnames.push('<?php echo $category['name']?>');
		}
	},
	
	clear:function(){
		this.scids.length=0;
		this.scnames.length=0;	
	}
	
}
function openclose(obj){
	var patrn=/close.gif$/;
	var s=obj.src;
	var id=obj.id;
	if(patrn.test(s)){
		obj.src='style/default/open.gif';
		var t=$('#'+id).find("dd");
		t.show();
	}else{
		obj.src='style/default/close.gif';
		var t=$('#'+id).find("dd");
		t.hide();
	}
}

function dialogalert(title, content){
	content = "<p>"+content+"</p><p>&nbsp;</p><p><input type='button' value='ȷ��' onclick='$.dialog.close(\"dialog_alert\")' class='inp_btn2'></p>"
	$.dialog({
		id:'dialog_alert',
		position:'center',
		width:250,
		height:100,
		title:title,
		onClose:function (){window.location.reload();},
		content:content
	});
}
</script>
<script type="text/javascript" src="js/calendar.js"></script>
<p class="map">���ݹ�����������</p>
<p class="sec_nav">�������� <a href="index.php?admin_doc" class="on"  > <span>�������</span></a> <a href="index.php?admin_focus-focuslist"  ><span>�Ƽ�����</span></a> <a href="index.php?admin_synonym" ><span>����ͬ���</span></a> <a href="index.php?admin_relation" ><span>��ش���</span></a> <a href="index.php?admin_edition"  ><span>�汾����</span></a> <a href="index.php?admin_cooperate" ><span>�����ƴ���</span></a> <a href="index.php?admin_nav" class="new"><span>����ģ��<label class="red">new</label></span></a> </p>
<h3 class="col-h3">�������</h3>
<div class="synonym">
	<form name="list" method="POST" action="index.php?admin_doc-search">
		<ul class="col-ul ul_li_sp m-t10">
			<li><span>��ѡ�����: </span>
				<select name="qcattype">
					<option value="0" >���з���</option>
					<?php echo $catstr?>
				</select>
			</li>
			<li><span>���Բ�ѯ:</span>
				<select name="typename">
					<option value="" >��������</option>
					<option value="1" >�Ƽ�����</option>
					<option value="2" >���Ŵ���</option>
					<option value="3" >���ʴ���</option>
					<option value="4" >����</option>
					<option value="5" >�����</option>
					<option value="6" >δ���</option>
				</select>
			</li>
			<li><span>��������:</span>
				<input name="qtitle" type="text" class="inp_txt" size="30" value="<?php echo $qtitle?>" />
			</li>
			<li><span>������:</span>
				<input name="qauthor" type="text" class="inp_txt" size="30" value="<?php echo $qauthor?>" />
			</li>
			<li><span>����ʱ��:</span>
				<input name="qstarttime" type="text" class="inp_txt" onclick="showcalendar(event, this);" readonly="readonly" value="<?php echo $qstarttime?>" />
				��
				<input name="qendtime" type="text" class="inp_txt" onclick="showcalendar(event, this);" readonly="readonly"  value="<?php echo $qendtime?>"/>
			</li>
			<li>
				<input name="Submit1" type="submit" value="�� ��"   class="inp_btn"/>
			</li>
		</ul>
	</form>
	<h3 class="tol_table">[ �� <b><?php echo $docsum?></b> ������ ]</h3>
	<form method="POST" name="formdoclist">
		<table class="table">
			<thead>
				<tr>
					<td style="width:30px;">ѡ��</td>
					<td style="width:150px;">��������</td>
					<td style="width:80px;">������</td>
					<td style="width:100px;">����</td>
					<td style="width:60px;">�������</td>
					<td style="width:90px;">����ʱ��</td>
					<td style="width:80px;">�������</td>
					<td style="width:50px;">����</td>
					<td>���</td>
				</tr>
			</thead>
			<!-- <?php if($doclist != null) { ?> -->
			<?php foreach((array)$doclist as $doc) {?>
			<tr>
				<td><input type="checkbox" name="chkdid[]" value="<?php echo $doc['did']?>" /></td>
				<td><a target="_blank" class="e" href="index.php?doc-view-<?php echo $doc['did']?>" title="<?php echo $doc['title']?>"><?php echo $doc['title']?></a>
					<input type="hidden" value="<?php echo $doc['title']?>" id="<?php echo $doc['did']?>_title" name="<?php echo $doc['did']?>_title"></td>
				<td><a target="_blank" class="e" href="index.php?user-space-<?php echo $doc['authorid']?>"  title="<?php echo $doc['author']?>"><?php echo $doc['author']?></a></td>
				<td><?php echo htmlspecialchars($doc['category'])?></td>
				<td><?php echo $doc['views']?></td>
				<td><?php echo $doc['time']?></td>
				<td><?php if($doc['doctype'] == 1) { ?><font color="Red">�Ƽ�����</font><?php } elseif($doc['doctype'] == 2) { ?><font color="blue">���Ŵ���</font><?php } elseif($doc['doctype'] == 3) { ?><font color="green">���ʴ���</font><?php } else { ?>��<?php } ?></td>
				<td><input type = "hidden" name = "<?php echo $doc['did']?>focus" value = "<?php echo $doc['title']?>">
					<?php if($doc['locked'] == 0) { ?>��<?php } else { ?><font color="Red">��</font><?php } ?></td>
				<td><?php if($doc['visible'] == 0) { ?>δ���<?php } else { ?><font color="Red">�����</font><?php } ?></td>
			</tr>
			<?php } ?>
			<!-- <?php } ?> -->
			<!-- <?php if($doclist == null) { ?> -->
			<tr>
				<td colspan="9"> û���ҵ��κδ���</td>
			</tr>
			<!-- <?php } ?> -->
			<tr>
				<td colspan="9">
					<label class="m-r10"><input name="checkbox" type="checkbox" id="chkall" onclick="selectAll();">&nbsp;&nbsp;ȫѡ</label>
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(2);" value="���" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(3);" value="����" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(4);" value="����" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(7);" value="ɾ��" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="javascript:catevalue.ajax(0,this);" value="�ƶ�����" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="inputnewname();" value="������" />
					<select name="doctype" id="doctype" class="m-r10">
						<option value="0" >ȡ�����</option>
						<option value="1" >�Ƽ�����</option>
						<option value="2" >���Ŵ���</option>
						<option value="3" >���ʴ���</option>
					</select>
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(1);" value="ѡ��" />
				</td>
			</tr>
			<tr>
				<td colspan="9"><p class="fenye a-r"> <?php echo $departstr?> </p></td>
			</tr>
		</table>
	</form>
</div>
<?php include $this->gettpl('admin_footer');?> 