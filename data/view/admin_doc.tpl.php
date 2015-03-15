<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script type="text/javascript">
var searchdata = "<?php echo $searchdata?>".replace(/-/g, ",");
function ChangeRadionew(number){
	if($("input[name='chkdid[]']:checked").length==0){
		$.dialog.box('immageshow', '注意', '请选择词条!');
		return false;
	}else{
		switch(number){
			case 2:
				if(confirm('确认审核词条？')==false){
					return false;
				}else{
					document.formdoclist.action='index.php?admin_doc-audit'+'-'+searchdata;
					document.formdoclist.submit();
				}
			break;
			case 3:
				if(confirm('确认锁定词条？')==false){
					return false;
				}else{
					document.formdoclist.action='index.php?admin_doc-lock'+'-'+searchdata;
					document.formdoclist.submit();
				}
		 	break;
			case 4:
				if(confirm('确认解锁词条？')==false){
					return false;
				}else{
					document.formdoclist.action='index.php?admin_doc-unlock'+'-'+searchdata;
					document.formdoclist.submit();
				}
		 	break;
			case 7:
				if (confirm('删除')==false){
					return false;
				}else{
					document.formdoclist.action='index.php?admin_doc-remove'+'-'+searchdata;
					document.formdoclist.submit();
				}
			break;
		 	case 1:
				if(confirm('确认修改词条类别?')==false){
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
				$.dialog.box('immageshow', '注意', '请选择管理选项!');
		 	break;
		}
		
		
	}
}
function changecategory(cats){
	if(!cats){
		$('#scnames').fadeOut();
		$('#scnames').html('&nbsp;&nbsp;分类不允许为空').fadeIn();
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
				//MSG = ' 移动分类成功';
				MSG = '';
				location.href="index.php?<?php echo $searchdata?>";
			}else{
				MSG = ' 移动分类失败';
			}
			if(MSG) {
				$.dialog.box('changecategory', '移动分类', MSG);
			}
			
		}
	);
	return true;
}
function inputnewname(){
		if (CheckboxNum('chkdid[]') > 1){
			$.dialog.box('inputnewname', '注意', '请只选择一个词条');
			return false;
		}else if(CheckboxNum('chkdid[]') == 0){
			$.dialog.box('inputnewname', '注意', '请选择词条!');
			return false;
		}else{
			
			var name, num=$("input[name='chkdid[]']:checked").val();
			name=num + '_title';

			var msg="请输入新名称<input id='newname' name='newname' type='text' class='inp_txt' value='"
			+ document.getElementById(name).value+"' maxlength='80'><br><br>"
			+ "<input name='renamesbumit' type='button' onclick='changename()' class='inp_btn2 m-r10' value='确定'>"
			+ "<input name='cancel' type='button' onclick='$.dialog.close(\"inputnewname\")' class='inp_btn2' value='取消'>";
			
			$.dialog.box('inputnewname', '重命名', msg);
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
				Msg = ' 新名称已存在!请重新命名';
			}else if(xml=='-1'){
				Msg = ' 请输入新的名称!';
			}else{
				Msg = ' 重命名失败';
			}
			if(Msg) {
				$.dialog.box('inputnewname', '重命名', Msg);
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
			$.dialog.box('catevalue', '警告', '请选择词条!');
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
						title:'移动分类',
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
	content = "<p>"+content+"</p><p>&nbsp;</p><p><input type='button' value='确定' onclick='$.dialog.close(\"dialog_alert\")' class='inp_btn2'></p>"
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
<p class="map">内容管理：词条管理</p>
<p class="sec_nav">词条管理： <a href="index.php?admin_doc" class="on"  > <span>管理词条</span></a> <a href="index.php?admin_focus-focuslist"  ><span>推荐词条</span></a> <a href="index.php?admin_synonym" ><span>管理同义词</span></a> <a href="index.php?admin_relation" ><span>相关词条</span></a> <a href="index.php?admin_edition"  ><span>版本评审</span></a> <a href="index.php?admin_cooperate" ><span>待完善词条</span></a> <a href="index.php?admin_nav" class="new"><span>导航模块<label class="red">new</label></span></a> </p>
<h3 class="col-h3">管理词条</h3>
<div class="synonym">
	<form name="list" method="POST" action="index.php?admin_doc-search">
		<ul class="col-ul ul_li_sp m-t10">
			<li><span>请选择分类: </span>
				<select name="qcattype">
					<option value="0" >所有分类</option>
					<?php echo $catstr?>
				</select>
			</li>
			<li><span>属性查询:</span>
				<select name="typename">
					<option value="" >所有属性</option>
					<option value="1" >推荐词条</option>
					<option value="2" >热门词条</option>
					<option value="3" >精彩词条</option>
					<option value="4" >锁定</option>
					<option value="5" >已审核</option>
					<option value="6" >未审核</option>
				</select>
			</li>
			<li><span>词条名称:</span>
				<input name="qtitle" type="text" class="inp_txt" size="30" value="<?php echo $qtitle?>" />
			</li>
			<li><span>创建者:</span>
				<input name="qauthor" type="text" class="inp_txt" size="30" value="<?php echo $qauthor?>" />
			</li>
			<li><span>创建时间:</span>
				<input name="qstarttime" type="text" class="inp_txt" onclick="showcalendar(event, this);" readonly="readonly" value="<?php echo $qstarttime?>" />
				—
				<input name="qendtime" type="text" class="inp_txt" onclick="showcalendar(event, this);" readonly="readonly"  value="<?php echo $qendtime?>"/>
			</li>
			<li>
				<input name="Submit1" type="submit" value="搜 索"   class="inp_btn"/>
			</li>
		</ul>
	</form>
	<h3 class="tol_table">[ 共 <b><?php echo $docsum?></b> 条词条 ]</h3>
	<form method="POST" name="formdoclist">
		<table class="table">
			<thead>
				<tr>
					<td style="width:30px;">选择</td>
					<td style="width:150px;">词条名称</td>
					<td style="width:80px;">创建者</td>
					<td style="width:100px;">分类</td>
					<td style="width:60px;">浏览次数</td>
					<td style="width:90px;">创建时间</td>
					<td style="width:80px;">词条类别</td>
					<td style="width:50px;">锁定</td>
					<td>审核</td>
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
				<td><?php if($doc['doctype'] == 1) { ?><font color="Red">推荐词条</font><?php } elseif($doc['doctype'] == 2) { ?><font color="blue">热门词条</font><?php } elseif($doc['doctype'] == 3) { ?><font color="green">精彩词条</font><?php } else { ?>无<?php } ?></td>
				<td><input type = "hidden" name = "<?php echo $doc['did']?>focus" value = "<?php echo $doc['title']?>">
					<?php if($doc['locked'] == 0) { ?>否<?php } else { ?><font color="Red">是</font><?php } ?></td>
				<td><?php if($doc['visible'] == 0) { ?>未审核<?php } else { ?><font color="Red">已审核</font><?php } ?></td>
			</tr>
			<?php } ?>
			<!-- <?php } ?> -->
			<!-- <?php if($doclist == null) { ?> -->
			<tr>
				<td colspan="9"> 没有找到任何词条</td>
			</tr>
			<!-- <?php } ?> -->
			<tr>
				<td colspan="9">
					<label class="m-r10"><input name="checkbox" type="checkbox" id="chkall" onclick="selectAll();">&nbsp;&nbsp;全选</label>
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(2);" value="审核" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(3);" value="锁定" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(4);" value="解锁" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(7);" value="删除" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="javascript:catevalue.ajax(0,this);" value="移动分类" />
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="inputnewname();" value="重命名" />
					<select name="doctype" id="doctype" class="m-r10">
						<option value="0" >取消类别</option>
						<option value="1" >推荐词条</option>
						<option value="2" >热门词条</option>
						<option value="3" >精彩词条</option>
					</select>
					<input type="button" class="inp_btn2 m-r10" name="casemanage" onClick="ChangeRadionew(1);" value="选择" />
				</td>
			</tr>
			<tr>
				<td colspan="9"><p class="fenye a-r"> <?php echo $departstr?> </p></td>
			</tr>
		</table>
	</form>
</div>
<?php include $this->gettpl('admin_footer');?> 