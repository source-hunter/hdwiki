var g_content_id="content",
	clientWidth=document.documentElement.clientWidth || document.body.clientWidth;
if (typeof g_filterExternal == 'undefined') var g_filterExternal = 0;
//只有IE6才需要这个方法
function hdeditor_resize( ){
	var doc = document.getElementsByTagName('BODY')[0];
	if (clientWidth > 1440){
		doc.style['marginLeft'] = 'auto';
		doc.style['marginRight'] = 'auto';
		if ($.browser.msie && $.browser.version <= 6){
			doc.style['width'] = '1440px';
		}
	}
}

$(window).bind("resize", function(){
	hdeditor_resize();
});

$(window).unload(function(){
	if(g_page_action == 'create'){
		$.get(g_logout_editor);
	}
});

function check_editreason(){
	var reasons = document.getElementsByName('editreason[]');
	for(var i = 0;i<reasons.length;i++){
		if(reasons[i].tagName.toLowerCase() == "input"){
			if (reasons[i].checked){ return 1;}
		}else if(reasons[i].tagName.toLowerCase() == "textarea"){
			if ($.trim(reasons[i].value) != ''){ return 1;}
		}
	}
	return 0;
}

function check(){
	var content = $.trim(jQEditor.getHTML());
	if(content == '' || content == '<p>&nbsp;</p>' || content == '<br />'){
		alert("内容不能为空！");
		return false;
	}
	if (g_page_action != 'create'){
		if(!check_editreason()){
			alert("请填写修改原因!");
			$("#editreason").get(0).focus();
			return false;
		}
	}
	
	if (g_check_code == "1"){
		var code = $("#doc_verification_code").find("input[name=code]");
		var value = $.trim(code.val());
		if (value.length < 4){
			alert("请填写验证码！");
			code.get(0).focus();
			return false;
		}
	}
	
	$(window).unbind("unload");
	jQEditor.mod('Confirm').clear();
	
	$('#content').val(jQEditor.getHTML());
	var tags = $("input[name=tags]").val();
	$("input[name=tags]").val(tags.replace(/；/g,";").replace(/ /g,';').replace(/;{2,}/g,';'));
}

refreshlock();
function refreshlock(){
	$.get("index.php?doc-refresheditlock-"+g_docid, function(data){setTimeout("refreshlock()",60000);}); 
}

function abort(){
	try{
		if( confirm("确定要退出编辑页面吗？") ){
			jQEditor.mod('Confirm').clear();
			window.location.href = g_logout_editor;
		}
	}catch(e){}
}

function updateverifycode(){
	$('#verifycode').attr('src', "index.php?user-code-"+Math.random());
}

function get_content_md5(){
	var str='';
	try{
		str = jQEditor.getHTML();
	}catch(e){
		
	}
	return str;
}

function set_verification_code(){
	var span = $("#doc_verification_code").find("span[name=img]");
	$("#doc_verification_code").find("span[name=tip]").html("[点击输入框显示验证码]").show();
	span.hide();
	
	$("#doc_verification_code").find("input[name=code]").one('focus', function(){
		updateverifycode();
		docReference.setVerifyCode();
		span.show();
		$("#doc_verification_code").find("span[name=tip]").hide();
	});
}
var notfirst=0;
var timeoutProcess=0;
function isAutoSave(){
	if($("#autosave").attr("checked")){
		timeoutProcess=setTimeout("autoSave(0)",savetime);
	}else{
		clearTimeout(timeoutProcess);
	}
}
function autoSave(button, callback){
	var E = jQEditor,
		dialog = E.plugin('Dialog');
	if (g_page_action == 'editsection'){
		return alert('保存功能只能在全文编辑下使用，当前是分段编辑。');
	}
	
	var id;
	if (g_page_action == 'edit'){
		id = -1;
	}else if (g_page_action == 'create'){
		id = -2;
	}
	
	if (typeof callback == 'function') {dialog.showNotice('正在保存。');}
	if(g_content_md5 == get_content_md5()){
		if (typeof callback == 'function') {
			dialog.showError('当前<b>正文内容</b>不需要保存。');
		}
		return false;
	}
	var savecontent= E.getHTML();
	
	$.ajax({
		url:"index.php?doc-autosave-"+g_docid,
		type:'POST',
		dataType:'xml',
		data:{id:id,notfirst:notfirst,savecontent:savecontent},
		timeout:25000,
		beforeSend:function(){
			
		},
		success : function(xml, state){
			if ('sucess' == xml.lastChild.firstChild.nodeValue){
				notfirst++;
				var CurrentTime=new Date();
				$("#AutoSaveStatus").html('状态:已于'+CurrentTime.toLocaleTimeString()+'保存');
				g_content_md5 = get_content_md5();
				if(button==0){
					if($("#autosave").attr("checked")){
						timeoutProcess=setTimeout("autoSave(0)",savetime);
					}
				}else{
					alert('{lang autosaveSucess}');
				}
				if (typeof callback == 'function') {callback(); }
			}else{
				alert('exception');
			}
		},
		complete :function(response, state){
			var id=g_content_id;
			if(state == 'timeout'){
				dialog.showError('保存操作超时，可能是网络连接较慢，稍候再试！');
			}else if(state != 'success'){
				dialog.showError('保存失败！');
			}
			
			if(state != 'success'){
				if (typeof callback == 'function') callback();
			}
		}
	});
}

function delSave(){
	$.post("index.php?doc-delsave",{did:g_docid},function(xml){
		if ('sucess' != xml.lastChild.firstChild.nodeValue){
			alert('exception');
		}
	});
}

//预览操作
function previewDoc(){
	var form = $('#previewdocform');
	//form.find('textarea').val(HD.util.getData());
	form.find('textarea').val(jQEditor.getHTML());
	form.submit();
}

var docReference = {
	//记录右侧模块页面初始化或者页面resize后的是位置
	right_mod_offset : null,
	//右侧模块固定样式类
	fixedClass : 'hdwiki-right-fixed',
	editid:0,
	verify_code:0,
	text_name:"请填入参考资料的名称，可以是书籍、文献，或网站的名称。（必填）",
	text_url:"请填写详细网址，以 http:// 开头",
	
	init: function(){
		var self = this;
		$('div#reference dl dd[name=edit]').css('visibility', 'hidden');
		
		$("#editrefrencename").focus(function(){
			if(this.value == self.text_name){
				this.value='';
				this.style.color='#333';
			}
		});
		
		$("#editrefrenceurl").focus(function(){
			if(this.value == self.text_url){
				this.value='';
				this.style.color='#333';
			}
		});
		
		$.get('index.php?reference-add-checkable-'+Math.random(), function(data, state){
			if ('OK' == data || 'CODE' == data){
				$("#edit_reference").show();
				$("div#reference dl").mouseover(function(){
					$(this).find('dd[name=edit]').css('visibility', '');
				});
				
				$("div#reference dl").mouseout(function(){
					$(this).find('dd[name=edit]').css('visibility', 'hidden');
				});
				
				if('CODE' == data){
					self.setVerifyCode();
					self.verify_code = 1;
					$("div#reference dd[name=verifycode]").show();
				}
			}else{
				if( !$("div#reference dl.f8:visible").size() ){
					$("div#reference").hide();
				}
			}
		});
		return this;
	},
	
	reset: function(){
		var self = this;
		$("#editrefrencename").val(this.text_name).css('color', '#999');
		$("#editrefrenceurl").val(this.text_url).css('color', '#999');
		self.setVerifyCode();
		return this;
	},
	
	resort: function(){
		var strongs = $('div#reference strong[name=order]');
		for (var i=0;i<strongs.length; i++){
			$(strongs.get(i)).html("["+(i)+"]");
		}
	},
	
	check: function(){
		var self=this, name,url, code="";
		$("#refrencenamespan").html('');
		$("#refrenceurlspan").html('');
		$("#refrencecodespan").html('');
		
		name = $.trim($("#editrefrencename").val());
		url = $.trim($("#editrefrenceurl").val());
		code = $.trim($("#editrefrencecode").val());
		
		if ('' == name || this.text_name == name){
			$("#refrencenamespan").html('参考资料名称为必填项');
			return false;
		}
		
		if (url == this.text_url){
			url = '';
		}
		
		if (url && !/^https?:\/\//i.test(url)){
			$("#refrenceurlspan").html('参考资料URL必需为以 http:// 或 https:// 开头的网址');
			return false;
		}
		
		if(self.verify_code && !code){
			$("#refrencecodespan").html('请输入验证码');
			return false;
		}
		
		if(self.verify_code && code.length != 4){
			$("#refrencecodespan").html('验证码需要输入4个字符');
			return false;
		}
		
		return {name:name, url:url, code:code};
	},
	
	save: function(){
		var self=this, value = this.check();
		if (value == false) return;
			
		if (this.editid == 0){
			this.add(value);
		}else{
			var name = value.name, url = value.url, code=value.code;
			
			$("#save_1").hide();
			$("#save_0").show();
			$.ajax({
				url:'index.php?reference-add',
				data:{'data[id]':self.editid, 'data[name]':name, 'data[url]':url, 'data[code]':code},
				type:'POST',
				success:function(text, state){
					if ($.trim(text) == '1'){
						var dl = $('div#reference dl[id='+self.editid+']');
						dl.find('span').html(name);
						dl.find('dd[name=url]').html(url);
						self.editid = 0;
						self.resort();
						self.reset();
					}else if( 'code.error' == text ){
						$("#refrencecodespan").html('验证码错误');
					}else{
						alert('提示：参考资料修改失败！');
					}
				},
				complete:function(XMLHttpRequest, state){
					if (state != 'success'){
						alert('提示：参考资料修改失败！');
					}
					$("#save_0").hide();
					$("#save_1").show();
				}
			});
		}
	},
	
	add: function(value){
		var name = value.name, url = value.url, code=value.code, self=this;
		$("#save_1").hide();
		$("#save_0").show();
		$.ajax({
			url:'index.php?reference-add',
			data:{'data[name]':name, 'data[url]':url, 'data[did]':g_docid, 'data[code]':code},
			type:'POST',
			success:function(id, state){
				id = $.trim(id);
				if (/[1-9]+/.test(id)){
					var dl = $('div#reference dl:first').clone(true);
//					dl.attr('id', id).show();
//					dl.find('span').html(name);
//					dl.find('dd[name=url]').html(url);
						if(state=='success'){
							alert('添加成功，词条保存后查看');
								//location.reload(true);
						}
					
					$('div#reference dl:last').before(dl);
					self.resort();
					self.reset();
				}else if( 'code.error' == id ){
					$("#refrencecodespan").html('验证码错误');
				}else{
					alert('提示：参考资料添加失败！');
				}
			},
			complete:function(XMLHttpRequest, state){
				if (state != 'success'){
					alert('提示：参考资料添加失败！');
				}
				$("#save_0").hide();
				$("#save_1").show();
			}
		});
	},
	
	edit: function(el){
		if (typeof el != 'object') return;
		var dl = $(el).parents('dl');
		this.editid = dl.attr('id');
		var name, url;
		name = $(dl).find('span').html();
		url = $(dl).find('dd[name=url]').html();
		
		$("#editrefrencename").val(name).css('color', '#333');
		$("#editrefrenceurl").val(url).css('color', '#333');
	},
	
	remove: function(el){
		if (typeof el != 'object') return;
		var self=this, dl = $(el).parents('dl');
		$(el).attr('onclick', '');
		var id = dl.attr('id');
		$.ajax({
			url:'index.php?reference-remove-'+id,
			success:function(text, state){
				text = $.trim(text);
				if (text != '0'){
					
					$(dl).remove();
					self.resort();
					self.reset();
				}else{
					alert('提示：参考资料删除失败！');
					$(el).attr('onclick', 'docReference.remove(this)');
				}
			},
			complete:function(XMLHttpRequest, state){
				if (state != 'success'){
					alert('提示：参考资料删除失败！');
					$(el).attr('onclick', 'docReference.remove(this)');
				}
			}
		});
	},
	
	setVerifyCode: function(){
		var self=this, dl = $("#edit_reference"), span = dl.find("span[name=img]");
		dl.find("span[name=tip]").html("[点击输入框显示验证码]").show();
		span.hide();
		$("#editrefrencecode").val('')
		dl.find("input[name=code]").one('focus', function(){
			self.updateVerifyCode();
			set_verification_code();
			span.show();
			dl.find("span[name=tip]").hide();
		});
	},
	
	updateVerifyCode: function(){
		$('#verifycode2').attr('src', "index.php?user-code-"+Math.random());
	},
	//固定右侧模块
	fixedRightMod : function(){
		var editorRight = $('#editor_right'),
			parms = self.fixedParms ? self.fixedParms : {topheight:$('#nav-top').height(),top:65},
			top = parms.top,
			topheight = parms.topheight,
			scrollTop = $(document).scrollTop();
		if(scrollTop > topheight) {
			editorRight.css('top',top+scrollTop-topheight);
		}else{
			editorRight.css('top',top);
		}
	}
}
docReference.init().reset();
/*
	浏览器插件外加配置
*/
var pluginSetting = {
	HdImage:{
		settings:{
			moreSize : false,
			maxUploadSize:'1M',
			moreAlign : false,
			getFileAction : function(){
				return 'index.php?attachment-uploadimg-'+$('#did').val();
			}
		}
	}
}
$(document).ready(function(){
	hdeditor_resize();
	$("#AutoSaveStatus").html("状态：正在编辑中...");
	set_verification_code();
	setTimeout(function(){
		g_content_md5 = get_content_md5();
	},3000);
	jQEditor.config('jqePath', './js/jqeditor/');
	$.editor({
		id: 'hdwiki_editor',
		contentId: 'content',
		toolbar: [
			'Source','Undo','Redo','H1','H2','Bold','ForeColor','BackColor',
			'RemoveFormat','PasteText','PasteWord',
			'JustifyLeft','FontSize','FontName',
			'InsertOrderedList','InsertUnorderedList',
			'HdImage','BaikeLink','Link',
			'SpecialChar','InsertTable',
			'Video','Code','GoogleMap',
			'Abort','Submit'
		],
		plugin : pluginSetting,
		
		disabled: ['filter.paragraph']
	});
	//固定右侧模块位置
	docReference.fixedParms = {top:$('#editor_right').offset().top,topheight:$('#nav-top').height()};
	$(window).scroll(function(){
		docReference.fixedRightMod();
	});
});

/**
 * 查看源码插件
 */
(function(E){
var Consts = E.consts;

E.plugin("Submit", {
	icon: {
		width: 50,
		text: '发 布',
		
		'default': {
			'Submit': {XY:"none" }
		}
	},
	click: function( ){
		//$("form[name=edit_doc]").submit();
		$("input[name=publishsubmit]").click();
	}
});

E.plugin("Abort", {
	icon: {
		width: 40,
		text: '退 出',
		
		'default': {
			'Abort': {XY:"none" }
		}
	},
	click: function( ){
		abort();
	}
});
})(jQEditor);