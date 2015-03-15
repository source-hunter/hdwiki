HD.util.showTextarea = function(id, cmd, height, tip,text){
	var title = '����'+HD.lang[cmd];
	//Ĭ�������ָ���
	var innerlink_default_fig = {"�ո�":"&nbsp;&nbsp;","����":"&nbsp;,&nbsp;","�ֺ�":"&nbsp;;&nbsp;","�ٺ�":"&nbsp;��&nbsp;","�س�":"<br/>","������":"&nbsp;&nbsp;|&nbsp;&nbsp;"};
	if(typeof innerlink_fig=="object"){
		innerlink_default_fig = innerlink_fig;
	}
	if (title.indexOf(' '>0)){
		title = title.split(' ')[0];
	}
	var html ='';
	if (tip) html += '<li><div style="width:300px">'+tip+'</div></li>';
	html += '<li><textarea id="hd_input_'+cmd+'" style="width:98%;border: 1px solid #AAAAAA;padding:3px;">'+(typeof text!="undefined"?text:"")+'</textarea></li>';
	html += '<li>ѡ��ָ�����<select name="innerlink_fig">';
	for(var key in innerlink_default_fig){
		html+= '<option value="'+innerlink_default_fig[key]+'">'+key+'</option>';
	}
	html += '</select></li>';
	html += '<li style="min-height:30px;"><div id="error_'+cmd+'" style="color:red;text-align:left;"></div></li>';
	var dialog = new HD.dialog2({
		id : id,
		cmd:cmd,
		content : html,
		width : 350,
		height : height>0?height:200,
		title : title,
		yesButton : HD.lang['yes'],
		noButton : HD.lang['close']
	});
	dialog.show();
	HD.iframeClickTag = '';
	return true;
};

HD.plugin['innerlink'] = {
	click : function(id,text) {
		var obj = HD.g[id].toolbarIcon['innerlink'];
		if (!obj ||obj.className.indexOf('disabled') > -1){
			return false;
		}
		var html = HD.util.getSelectedText(id,'html');
		if (html && /<\/table>|hdwiki_tmml/i.test(html)){
			this.showBox(id,html);
			return this.showError('��ǰ��ѡ��Χ���а���������⣬����תΪ�ڲ����ӣ�');
		}
		var text=text || HD.util.getSelectedText(id);
		var el = HD.util.getParentElement(id);
		this.checkClick(id,text,el);
		HD.iframeClickTag = '';
	},
	exec : function(id) {
		var text = HD.$('hd_input_innerlink').value;
		text = text.replace(/<.*?>/g, '');
		//HD.util.focus(id);
		if (text != ''){
			this.checkClick(id,text);
		}else{
			this.showError('���������ݲ���Ϊ�գ�');
		}
		return false;
    },
	/*
	id��ģ��id
	text�������������ַ���
	el�������Ƿ�����ҳ�������õģ�Ϊ���Ǵ�����������
	*/
	checkClick : function(id,text,el){
		var url, tag;
		if(el){
			tag =el.nodeName;
		}
		if('' != text){
			var text = HD.util.trim(text);
			if (/[\*#%~><\/\\]/i.test(text)){
				this.showBox(id,text);
				return this.showError('���������ݲ��ܰ����ո���������"% * �� < > # \ / +�ۣݡ���"�ȣ�');
			}else if(text.replace(/[\;\s]+/g,"").length==0){
				this.showBox(id,text);
				return this.showError('���������ݲ���Ϊ�գ�');
			}else{
				if(el && HD.util.inArray(el.className,['hdwiki_tmml','hdwiki_tmmll','img'])){
					this.showBox(id,text);
					return this.showError('��ǰѡ�е����ݲ������Ϊ�ڲ����ӣ�');
				}
				text = text.replace(/\s+/g, '');
				HD.util.focus(id);
				HD.util.insertHtml(id, this.getLinkHtml(text));
				HD.layout.hide(id);
				HD.toolbar.disable(id, ['save','preview','cut','copy','paste','source','bold','fontstyle','innerlink']);
			}
		}else if(el){
			pel = el.parentNode;
			if ((tag == 'STRONG' || tag == 'B') && pel.nodeName == 'A'){
				tag = 'A';
			}
			if('A' == tag){
				HD.shortcutMenu.a_unlink();
			}else{
				this.showBox(text);
			}
		}
	},
	/*
	��ȡhtml�ַ���
	text���Էֺŷָ����ַ���
	*/
	getLinkHtml : function(text){
		var str = "";
		var url = 'index.php?doc-innerlink-';
		//Ĭ�Ϸָ���Ϊ�ո�
		var default_fig = " ";
		if($("select[name='innerlink_fig']").length>0){
			default_fig = $("select[name='innerlink_fig']").val();
		}else if(typeof innerlink_fig=="object"){
			for(var key in innerlink_fig){
				default_fig = innerlink_fig[key];
				break;
			}
		}
		var items = text.split(";");
		for(var i= 0 ; i< items.length ; i++){
			var item = items[i];
			if(item&&item!=' '){
				str +='<a class="innerlink" title="'+item+'" href="'+url + encodeURI(item)+'">'+item+'</a>';
				if(items[i+1]){
					str +=default_fig;
				}
			}
		}
		return str;
	},
	/*
	�����Ի�������Ի����Ѿ������򲻵���
	id:�ò����id
	text:ѡ�е����ݣ����û����Ϊ��
	*/
	showBox : function(id,text){
		if(!$("#hd-dialog").length){
			HD.util.showTextarea(id,'innerlink', 450, 'һ���������40���ַ���һ�������������ַ���һ�ο���������[<span style="color:red;font-size:11px;">�����Ӣ�ķֺ�(;)�ָ�</span>]',text);
			$("#hd_input_innerlink").click(function(){
				$("#error_innerlink").hide();
			});
		}
	},
	/*
	��ʾ������Ϣ
	*/
	showError : function(message){
		$("#error_innerlink").html(message).show();
	}
};