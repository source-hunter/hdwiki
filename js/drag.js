//��Ҫ�ǶԼ��ؽ�����iframeԪ�صĲ������϶���ɾ�����༭�����棬���������

$.IE6 = (function(){
	var userAgent = navigator.userAgent;
	if (/MSIE [7891]\d?/.test(userAgent)) return false;
	if (/MSIE [56]/.test(userAgent)) return true;
	return false;
})();
var Action={
	obj:null,//�������ĳһ���Ѿ���ӵ�����,һ�������ڲ��������顣
	draging:0,
	contents:'',
	offsetBody:0,
	offsetClick:{width:0, height:0},
	isview:1,
	confirm:{
		set:function(msg){
			window.onbeforeunload = function(){
				window.frames['themeFrame'].onbeforeunload = function(){};
				return '�����޸���δ���棬��� ȷ�� ����ʧ�����޸ģ�\n\n�����༭��� ȡ�� ��ť��';
			}
			window.frames['themeFrame'].onbeforeunload = function(){
				return '�����޸���δ���棬��� ȷ�� ����ʧ�����޸ģ�\n\n�����༭��� ȡ�� ��ť��';
			}
		},
		clear : function(){
			window.onbeforeunload = function(){};
			window.frames['themeFrame'].onbeforeunload = function(){};
		}
	},
	
	view:function(){//��ʾ���߲���ʾ���������
		if(this.isview>0){
			area.addClass('view');
			$("#view").val('ȥ�����߿�');
		}else{
			area.removeClass('view');
			$("#view").val('�鿴���������');
		}
		this.isview*=-1;
	},
	
	//���η�װ $.ajax 
	post: function(url, data, callback, dataType){
		$.ajax({
			url:url,
			type:'POST',
			data:data,
			dateType:dataType,
			success: function(data){
				if (/xml/i.test(dataType)){
					if('object' == typeof data){
						callback(data);
					}else{
						//���⣬�����¼��ʱ�ȡ����صĲ���XML��Ϣ������û��Ȩ�޵�htmlҳ��
						alert("��ʾ������������������ĵ�¼�Ѿ���ʱ����رյ�ǰ�����������롣");
					}
				}else{
					callback(data);
				}
			},
			error: function(XMLHttpRequest, textStatus){
				alert('��ʾ�������������쳣�����Ժ����ԡ�');
			}
		});
	},
	
	del:function(){
		if(confirm('ȷ�Ͻ�������ɾ��?')){
			var self=this.obj;
			/*
			//���ַ�ʽ���������쳣ʱ���ܸ�����ʾ��
			//����һ����Ҫ���ؽ��������£����ʹ��$.ajax()��ʽ
			//ͬʱ��Ϊ�˼�$.ajax()���ɶ�����ж��η�װ
			$.post("?admin_theme-delblock",{bid:self.attr('bid')},function(xml){
				var message=xml.lastChild.firstChild.nodeValue;
				if(message=='ok'){
					self.remove();
					toolbar.hide();
					bindblk();
				}
			},'xml');
			*/
			this.post("?admin_theme-delblock",{bid:self.attr('bid')},function(xml){
				var message=xml.lastChild.firstChild.nodeValue;
				if(message=='ok'){
					self.remove();
					toolbar.hide();
					bindblk();
				}
			},'xml');
			
			Action.confirm.set();
		}
	},
	exit: function(){
		window.close();
	},
	edit:function(){
		var bid=this.obj.attr('bid');
		this.post("?admin_theme-getconfig",{bid:bid},function(xml){
			var params=xml.getElementsByTagName("params")[0].childNodes[0].nodeValue;
			var contents=xml.getElementsByTagName("contents")[0].childNodes[0].nodeValue;
			$.dialog({
				id:'edit_block',
				position:'center',
				title:'�༭���',
				width:550,
				height:500,
				content:'<form><div id="editconfig" class="main2"></div></from><p class="col-p m-l140"><input id="editbtn" type="button" class="btn" value="�༭" /></p>',
				callback:function(){
					$("#config").html('');
					if(contents==''){
						contents='�����û��Ҫ���õĲ�����';
						params='';
						$("#editbtn").val('���').click(function(){
							$.dialog.close('edit_block');
							Action.confirm.set();
							block.istpl = 1;
						});
					}else{
						$("#editbtn").val('�༭').click(function(){
							block.complete(bid);
							Action.confirm.set();
							block.istpl = 1;
						});
					}
					$("#editconfig").html(contents);
					Action.contents = $('#tplcontent').val();
					if(params!=''){
						eval("params="+params);
						var paramsObj=$("[name^='params']");
						$.each(paramsObj,function(i,n){
							var s = $(n).attr('name');
							var name=s.slice(s.indexOf('[')+1,s.lastIndexOf(']'));
							name=name.replace(/[\'\"]/g,'');
							$(n).val(params[name]);
						});
					}
				}
			});
		},'xml'); 
	},
	drag:function(e){
		var self=this, obj=this.obj, offset=obj.offset();//self=this  ���ǽ�Action���󴫸���self��
		space.height(obj.height()-5).show();
		obj.before(space);//blockǰ�����space
		obj.unbind('mouseover').css('opacity', 0.8);//ж��block��mouseover�¼���������͸���Ƚ���60.
		toolbar.hide();//����toolbar
		obj.css('width', obj.width()).addClass('draging').css({left:offset.left-Action.offsetBody, top:offset.top-10});
		Action.draging=1;
		
		self.offsetClick.width=e.pageX - offset.left;
		self.offsetClick.height=e.pageY - offset.top;
		
		//��ס������ƶ����
		$(Fdocument).mousemove(function(e){
			var x=e.clientX + Fdocument.documentElement.scrollLeft, y=e.clientY + Fdocument.documentElement.scrollTop;//�õ���ǰ�����ֵ��������������
			var left = x, top = y-15;
			obj.css({left:left -Action.offsetBody -self.offsetClick.width, top:top});//block��������ƶ���
			self.move(x,y);//��������괫��self.move.
			return false;
		});
		
		//�ſ������������϶�Ԫ�ط��õ���λ��
		$(Fdocument).one('mouseup',function(){
			self.draging=0;
			$(Fdocument).unbind("mousemove");
			obj.removeClass('draging').removeAttr("style");
			space.before(obj).hide();
			
			obj.mouseover(function(){
				self.showToolbar($(this));
			});
			Action.confirm.set();
		});
	},
	
	/*
	* ����������꣬���ĸ��������
	* ���ﲻ��ʹ�� onmouseover �¼�����Ϊ���϶��������ڵ������������� onmouseover �¼���
	*/
	move: function(x,y){
		var self=this, width, height, left, top, thisArea, thisBlk;
		for(var i=0; i<area.length; i++){//ѭ��area�������顣
			var div=$(area[i]);
			left = div.offset().left;
			top = div.offset().top;
			if(x > left && x < left + div.width() && y > top && y < top + div.height()){//������������Χ�ˡ�
				thisArea=div;
				break;
			}
		}
		if(!thisArea) return;//��겻���κ����򣬷��ء�
		var thisBlks=thisArea.children("div[bid!='"+self.obj.attr('bid')+"']");//�õ���ǰ������µ��������飨ȥ����P��ǩ��space�������϶������򣩡�
		if(thisBlks.length==0){//û�����飬��ֱ��������������space,���ء�
			thisArea.append(space);
			return;
		}
		for(var i=0; i<thisBlks.length; i++){
			var blk=$(thisBlks[i]);
			left = blk.offset().left;
			top = blk.offset().top;
			if(x > left && x < left + blk.width() && y > top && y < top + blk.height()){//�������������鷶Χ�ˡ�
				thisBlk = blk;
				break;
			}
		}
		if(thisBlk){//�����ĳһ������ִ�����������
			var method= (y < thisBlk.offset().top + thisBlk.height()/2)? 'before': 'after';
			thisBlk[method](space);
		}
	},
	
	showToolbar: function(block){
		if(!Action.draging){ //��������϶�״̬��
			Action.obj=block;//��obj��ֵ��
			var offset=block.offset(), width=block.width(), height=block.height();
			var left=offset.left- Action.offsetBody;
			if($.IE6){
				left+=10;
			}
			if(toolbar.css('display') == 'none'){
				toolbar.css({left:left, top:offset.top, width:width, height:height}).show();
			}else{
				toolbar.stop().animate({left:left, top:offset.top, width:width, height:height}, 100);
			}
		}
	}
	
}

