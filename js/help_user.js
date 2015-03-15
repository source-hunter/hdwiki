/*!
 * jQuery.dialog  0.88
 *
 * Copyright 2010, Baike.com
 * Dual licensed under the MIT and GPL licenses.
 * author: panxuepeng
 * blog: http://dushii.blog.163.com
 * $Date: 2008-08-31 $
 * $Last: 2010-08-26 $
 */
 
 /*
 run:
 Firefox 2+, IE6+, Chrome2+, Safari3+
 
 usage:
$.dialog({
	id:'demo',
	align:'left',
	position:'center',
	width:400,
	title:'Hi! This is a demo of the $.dialog',
	content:"$.dialog({<br>&nbsp;id:'demo',<br>&nbsp;align:'left',<br>&nbsp;width:400,<br>&nbsp;title:'Hi! This is a demo of the $.dialog',<br>&nbsp;content:'......'<br>});"
});
 */

(function($){

var userAgent = navigator.userAgent;
$.IE = $.browser.msie;
$.Firefox = /Firefox/i.test(userAgent);

$.IE6 = (function(){
	if (/MSIE [7891]\d?/.test(userAgent)) return false;
	if (/MSIE [56]/.test(userAgent)) return true;
	return false;
})();

//����һЩ��������
function init_size(){
	var self = $._dialog;if(!self) return;
	self.scrollTop   =$(document).scrollTop();
	self.scrollLeft  =$(document).scrollLeft();
	self.windowHeight=$(window).height();
	self.windowWidth =$(window).width();
	
	self.bodyPosition=$(document).find('body').css('position');
	self.bodyWidth   =$(document).find('body').width();
	self.bodyLeft    =$(document).find('body').position().left;
	if(!self.bodyLeft && self.windowWidth > self.bodyWidth){
		self.bodyLeft=(self.windowWidth - self.bodyWidth)/2;
	}
}

function init_dialog(){
	var self=$._dialog, clockScroll=0, clockResize=0;
	self.init();
	init_size();
	
	$(window).bind('resize', function(){
		var self=$._dialog;
		if(clockResize){clearTimeout(clockResize)}
		
		clockResize=setTimeout(function(){
			init_size();
			var self=$._dialog, options;
			for(id in self.list){
				if (self.isShow(id)){
					options=self.options[id];
					self.show(id);
					if (options.overlay){self.setOverlay(id)}
				}
			}
		}, 200);
		return false;
	});
	
	$(window).bind('scroll',function(){
		var self=$._dialog;
		if(clockScroll){clearTimeout(clockScroll)}
		
		clockScroll=setTimeout(function(){
			//���ڹ���ֹͣ��ִ�����´���
			self.scrollTop = $(document).scrollTop();//���¼��� self.scrollTop
			
			for(id in self.options){
				var options=self.options[id], pos =self.getPosition(options.id), dialog=self.list[id];
				
				dialog.stop();
				if(options.minScrollTop){
					if(options.minScrollTop >self.scrollTop){
						dialog.css({visibility:'hidden'});
					}else{
						dialog.show().css({left:pos.left, top:pos.top, visibility:'visible'});
					}
					
					//if(options.autoClose){fn_clockAutoClose(options.id)}
					return;
				}
				
				if($.IE6 && options.fixed && self.isShow(id) ){
					//��IE6�£�ʹ��animate����������display���Իᱨjs����
					//dialog.animate({left:pos.left, top:pos.top, display:'block'}, 200);
					if(typeof options.resetTime == "number" && options.resetTime>0){
						dialog.show().animate({left:pos.left, top:pos.top}, options.resetTime);
					}else{
						dialog.css({left:pos.left, top:pos.top}).show();
					}
					//if(options.autoClose){fn_clockAutoClose(options.id)}
				}
			}
		}, 200);
		return false;
	});
}

$(document).ready(function(){
	init_dialog();
});

//$._dialog ����ͨ�� $.dialog() ������
$._dialog = {
	version:0.88,
	list : {}, // dialog object
	isOpen:[], //�Ƿ��ڴ�״̬
	isClose:[], //�Ƿ��ڹر�״̬
	options : {}, // dialog options
	parts : {}, //���ڵ���ɲ��֣����⣬�رհ�ť���������򣬵ײ���ť����
	fn_clockScroll:[],
	skins:{},
	keys:{},
	
	config : {
		lastid:'',
		id:'default',
		skin:'default',
		offsetClick:{},
		focusStart:false,
		zIndex:2010,
		effect_time_up:500,
		effect_time_down:500,
		effect_time_fade:200,
		base:'.', //ͼƬ��Դ·��
		htmlImgLoading:'',
		imgLoading:'indicator.gif',
		imgClose:'admin/close.gif',
		imgTitleBg:'bg_box_hand.gif'
	},
	defaults: {
		id:'default',
		key:'', //Ψһ��
		skin:'default',
		move:true, //�Ƿ�����϶�
		overlay:true, //�Ƿ������ֲ�
		model:'default',//default  mini  together  alone
		zIndex:2010,
		title:'', //���ڱ���
		content:'', //��������
		type:'',//img url iframe selector��ע�� type �����ȼ����� content
		url:'', //���ݵ�url��ַ���� type=img||url ʱ��Ч
		callback:null, //�ص�����
		callbackTimeout:500,
		position:'middle', //����λ��
		offsetX:0,//���Ӧ��Ļ��ˮƽƫ����
		offsetY:0,//���Ӧ��Ļ�Ĵ�ֱƫ����
		fixed:false, //�Ƿ�̶�����Ļ��
		effects:'',// fade  up down
		autoClose:0, //�Ƿ�ʱ�Զ��رգ���λ����
		forceClose:1, //������ڴ�����ʱ��ȡ���Զ��رգ��뿪ʱ�����ùر�
		width:564, //���ڿ�
		//height:200, //���ڸ�
		fixedWidth:0,//�������ڿ��
		minScrollTop:0,
		onOk:null,//ȷ����ť�����ĺ���
		onCancel:null,//ȡ����ť�����ķ���
		onClose:null,//�رմ���ʱ�����ķ���
		textOk:'ȷ��',
		textCancel:'ȡ��',
		closeImg:true,
		resetTime:100,//���¶�λ���ڵ��ƶ�ʱ��
		resizable:1, //�Ƿ�������������Զ��������ڴ�С
		styleDialog:{},
		styleTitle:{},
		styleContent:{},
		styleBtn:{},
		styleOk:{},
		styleCancel:{},
		styleOverlay:{opacity:0.3},
		valign:''//��ֱ����
	},
	
	/*
	* ��ʼ������ document.ready ʱִ��һ�Σ��ҽ�ִ��һ�Σ�
	*/
	init : function(){
		var self=this;
		self.config.htmlImgLoading='<img src="'+self.config.base+'/'+self.config.imgLoading+'"/>';
		//Ĭ����ʽ������ͨ��$.dialog.addSkin(strStyle) ��������� css Ƥ��
		var style='div.hudong_dialog {padding-top:20px;color:#46A600;border:1px solid #86B9D6;background-color:#FFFFFF;overflow:hidden;}\
			\n div.hudong_dialog .close{float:right;cursor:pointer;\
				top:15px;right:15px;width:12px;height:11px;position:absolute;background:url('+self.config.base+'/'+self.config.imgClose+') no-repeat;}\
			\n div.hudong_overlay{z-index:2001;cursor:default;background-color:#ffffff;\
				width:100%;height:100%;top:0px;left:0px;position:absolute;margin:0 auto;}';			
		style+='div.bluebox {border:5px solid #666666;}\
			\n div.bluebox h2.title{background:none;background-color:#009DF0;color:#FFFFFF;font-size:14px;}\
			\n div.bluebox .close{top:5px;right:5px;}\
			\n div.bluebox div.button{background-color:#E8E8E8;padding:5px 10px 2px;}';
			
		style+='div.noborder {border:0;background-color:transparent;}\n div.noborder .close{top:10px;right:10px;}';
		
		if($("style#hudong_dialog_style").size()==0){
			$('head').append('<style id="hudong_dialog_style">'+ style +'</style>');
		}
	},
	
	/*
	* ��һ������
	*/
	open :function(options){
		var self=this;
		if(document.getElementsByTagName('body').length == 0){
			$(document).ready(function(){
				self.open(options);
			});
			return false;
		}
		
		if (typeof options != 'object'){
			alert('arguments must be a object, like {id:"id", title:"title"}.');
			return false;
		}
		
		var id =self.config.id =  options.id;
		if (typeof id != 'string' && typeof id != 'number'){
			alert('the type of id must be string or number.');
			return false;
		}
		
		if(! options["skin"]){
			options["skin"] = self.config["skin"];
		}
		
		
		//����δ�����ѡ��ʹ��Ĭ��ֵ self.defaults[i]
		function extend(defaults, options){
			var type;
			for (var i in defaults){
				type=typeof options[i];
				if (type =='undefined'){//ʹ��Ĭ��ֵ
					options[i] = defaults[i];
				}else if(type == 'object'){
					extend(defaults[i], options[i])
				}
			}
		}
		
		extend(self.defaults, options);
		
		/*
		//ʹ������ĵݹ鷽ʽ��������ķ�ʽ
		var type;
		for (var i in self.defaults){
			type=typeof options[i];
			if (type =='undefined'){//ʹ��Ĭ��ֵ
				options[i] = self.defaults[i];
			}else if(type == 'object'){
				for(var j in self.defaults[i]){
					if (typeof options[i][j] =='undefined'){//ʹ��Ĭ��ֵ
						options[i][j] = self.defaults[i][j];
					}
				}
			}
		}
		*/
		
		function isInt(x, defaultValue){
			return (typeof x == 'number') ? x : defaultValue;
		}
		options.autoClose = isInt(options.autoClose, 0);
		if(options.autoClose > 0 && options.autoClose < 100){
			options.autoClose *= 1000;
		}
		
		if (typeof options.position=="object"||options.height >self.windowHeight){options.fixed=false}
		if (typeof options.fixed == "string"){options.fixed=options.fixed.toLowerCase()}
		options.effects= /up|down|fade/i.test(options.effects)?options.effects.toLowerCase():'';
		
		if(!options.type && options.content){
			if(/^[\w\/:&?,=-]+\.(jpg|gif|png)$/i.test(options.content)){
				//�����Ǹ�ͼƬ��ַ������ͼƬ��ʽ��
				options.type='img';
				options.url=options.content;
			}else if(/^[\w\/:&?,=-]+\.html?$/i.test(options.content)){
				//�����Ǹ���ҳ��ַ��������ҳ��ʽ��
				options.type='url';
				options.url=options.content;
			}
			
		}
		
		self.options[id] =options;
		if (options.overlay){self.setOverlay(id)}
		
		if(options.model.indexOf('alone')>-1){
			for(i in self.list){
				if(i != id && self.options[i] && self.options[i].model.indexOf('together')<0 ){
					self.close(i);
				}
			}
		}
		
		self.build(options);
		
		options['isLoad']=1;
		return self;
	},
	
	/*
	* ���ô�������
	*/
	setContent : function(id, content){
		var self=this, dialog = self.list[id];
		if (!dialog){alert('dialog '+id+ ' is not exist.')}
		self.parts[id].content.html(content);
		return self;
	},
	
	reset: function(id, ms){
		var self=this;
		ms = ms||100;
		setTimeout(function(){
			//�ȸ��ݴ������ݵ������ڵĴ�С
			self.resize(id);
			
			//�����¶�λ���ڵ�λ��
			var dialog = self.list[id], pos = self.getPosition(id), options=self.options[id];
			if(options.resetTime > 0){
				//ע��˴�����ʹ�� dialog.stop()�����򽫵���һЩʹ����Ч�Ĵ�������ȫ��ʾ֮ǰ����ֹ
				dialog.animate({top:pos.top, left:pos.left}, options.resetTime);
			}else if(options.resetTime == 0){
				dialog.css({top:pos.top, left:pos.left});
			}
		}, ms);
	},
	
	/*
	* ���ô���
	*/
	setOptions : function(options){
		var id = options.id, content='', url='';
		var self=this, dialog = self.list[id], part = self.parts[id];
		
		/*
		//�Ѿ��޸�Ϊ $._dialog.reset()
		
		//�ӳ����¶�λ
		function _reset(id, ms){
			ms = ms||100;
			setTimeout(function(){
				self.resize(id);
				var pos = self.getPosition(id);
				if(options.resetTime>0){
					//ע��˴�����ʹ�� dialog.stop()�����򽫵���һЩʹ����Ч�Ĵ�������ȫ��ʾ֮ǰ����ֹ
					dialog.animate({top:pos.top, left:pos.left}, options.resetTime);
				}else if(options.resetTime==0){
					dialog.css({top:pos.top, left:pos.left});
				}
			}, ms);
		}
		*/
		
		if (options.type == 'url'){
			url = options.url;
			if (self.config.url == url){
				self.show(id);
				return self;
			}
			self.config.url = url;
			self.setContent(id, self.config.htmlImgLoading);
			$.get(url, function(data, state){
				if (self.isClose[id]){return}
				if (state == 'success'){
					self.setContent(id, data);
					self.reset(id);
					if($.IE) self.reset(id, 200);
					if ($.isFunction(options.callback)){options.callback(dialog)}
				}else {
					self.setContent(id, "Loading failure!");
				}
			});
		}else if (options.type == 'img'){
			url = options.url;
			if (self.config.url == url){
				self.show(id);
				return self;
			}
			self.config.url = url;
			self.setContent(id, self.config.htmlImgLoading);
			var pos = self.getPosition(id);
			dialog.css({top:pos.top,left:pos.left,opacity:''});
					
			var img = new Image();
			img.onload = function(){
				if (self.isClose[id]){return}
				var width = img.width >950 ?950 : img.width;
				self.setContent(id, '<img src="'+url+'" width="'+width+'" />');
				self.reset(id);
				if($.IE) self.reset(id, 200);
				if ($.isFunction(options.callback)){options.callback(dialog)}
			}
			img.onerror = function(){self.setContent(id, options.error)}
			img.src= url;
			
			self.parts[id].content.dblclick(function(){$.dialog.close(id)});

		}else if(options.type == 'iframe'){
			content = "<iframe id='"+id+"_iframe' name='"+id+"_iframe' border='0' width='"+(options.width-20)+"' height='"+(options.height-50)+"' frameborder='no' "
				+ " marginwidth='0' marginheight='0' scrolling='no' allowtransparency='yes'></iframe>";
			self.setContent(id, content);
			self.reset(id);
			var iframe=$("#"+id+"_iframe");
			iframe.load(function(){
				if ($.isFunction(options.callback)){
					setTimeout(function(){options.callback(dialog)}, options.callbackTimeout);
				}

			}).attr('src', options.url);
		}else if(options.type == 'selector'){
			content = $(options.url||options.content).html();
			if(content){
				self.setContent(id, content);
				self.reset(id);
			}
			if ($.isFunction(options.callback)){
				setTimeout(function(){options.callback(dialog)}, options.callbackTimeout);
			}
		}else{				
			if(options.type != 'customize'){
				if(options.valign && /center|top|middle|baseline|bottom/.test(options.valign)){
					if("center" == options.valign){ options.valign = "middle"; }
					var _height=options.height-40;
					if(typeof options.onOk == "function" || typeof options.onCancel == "function"){
						_height -= 25;
					}
					self.setContent(id, '<table width="100%" height="'+ _height +'"><tr><td valign="'+ options.valign +'">'+ options.content +'</td></tr></table>');
				}else{
					self.setContent(id, options.content);
				}
			}
			
			self.reset(id);
			if (!options.type && $.isFunction(options.callback)){
				setTimeout(function(){options.callback(dialog)}, options.callbackTimeout);
			}
		}
		
		var part = self.parts[id];
		options.title?part.title.show(): part.title.hide();
		
		if (options.move){part.title.css('cursor', 'move')}
		
		//���ô��ڵĳ�ʼ�߶ȺͿ��
		if(options.type != 'customize'){
			if (options.width){dialog.width(options.width)}
			
			if (options.height){dialog.height(options.height)}
		}
		
		if(options.closeImg){
			part.close.show().unbind('click').click(function(){
				var id=$(this).parent().attr('id').replace('dialog_', '');
				$.dialog.close(id);
			});
		}else{
			part.close.hide();
		}
		
		
		if($.isFunction(options.onOk)|| $.isFunction(options.onCancel)){
			part.button.show();
			$.isFunction(options.onOk)? part.ok.show(): part.ok.hide();
			$.isFunction(options.onCancel)? part.cancel.show(): part.cancel.hide();
			
			if($.isFunction(options.onOk)) part.ok.unbind('click').click(function(){options.onOk(dialog)});
			if($.isFunction(options.onCancel)) part.cancel.unbind('click').click(function(){options.onCancel(dialog)});
		}else{
			part.button.hide();
		}
		
		//�����Զ�����ʽ�������ò��Ϸ���css����ʱ����IE�»ᱨjs����
		function _css(o, s){
			if (typeof options[s] == 'object'){
				try{
					o.css(options[s]);
				}catch(e){
					alert("id = [" + options.id + "]�Ĵ��ڣ��Զ���css��ʽ���ڴ�������css�� "+s+" ���֡�");
				}
			}
		}
		_css(part.content, 'styleContent');
		_css(part.title, 'styleTitle');
		_css(part.button, 'styleBtn');
		_css(part.ok, 'styleOk');
		_css(part.cancel, 'styleCancel');
		_css(dialog, 'styleDialog');

		self.show(id);
		return self;
	},
	
	
	/*
	* ���������������ô��ڴ�С
	* �� reset ��������
	*/
	resize: function(id){
		var self =this, dialog=self.list[id], part=self.parts[id], options=self.options[id];
		if(options.type == 'customize' || !options.resizable){//������Զ���Ĵ��ڣ�����resize()
			return;
		}
		var W, H, otherHeight=0, child=part.content.children('div,img,table,iframe').eq(0),
			//�������������padding֮��
			paddingTB=parseInt(part.content.css('paddingTop')) + parseInt(part.content.css('paddingBottom')) +5,
			paddingLR=parseInt(part.content.css('paddingLeft')) + parseInt(part.content.css('paddingRight'));
		
		dialog.css({height:''});
		if(!$.IE6) part.content.css({height:''});//��IE6�£����û��ָ��options.height�����ܵ������ݲ���ʾ
		
		if(options.onOk || options.onCancel) otherHeight +=part.button.outerHeight();
		if(options.title) otherHeight +=part.title.outerHeight();
		
		if(child.size()){
			W =child.outerWidth() +paddingLR;
		}else{
			W =part.content.outerWidth();
		}
		
		H=part.content.outerHeight();
		
		//������������ĸ߶�
		if(options.height && H < options.height -otherHeight-paddingTB){
			//�����ݸ߶�С�ڴ��ڸ߶�ʱ��Ϊ��ȷ�� ȷ�� ȡ�� ��ť �ڴ��ڵĵײ���
			//��Ҫ�������������ø߶�
			part.content.height(options.height -otherHeight -paddingTB);
		}
		
		
		//�ڲ��������ʱ�ŵ������ڿ��
		if(!options.fixedWidth){
			
			//������������Ŀ��
			if((options.width && options.width > W) || !child.css('width')){
				//�����ݿ��С��ָ���Ŀ��ʱ��
				//��Ҫ�������������ÿ�ȣ��Ե������ڵĿ��
				W = options.width;
				part.content.width(options.width - parseInt(part.title.css('paddingTop'))*2 );
			}
			
			//���� fixed ����ʱ�����dialogָ����ȣ�������������
			dialog.css({width: W+'px'});
			/*
			if(options.fixed){
				dialog.css({width: W+'px'});
			}else{
				if(!$.IE) dialog.css({width: ''});//���width��ʽ����IE�»ᵼ�±���������100%
			}
			*/
		}
				
		//���dialog�ĸ߶ȴ��ڵ�ǰ���ڵĸ߶ȣ���dialog�Ķ����ʹ��ڶ�������
		if(H > self.windowHeight){dialog.css('top', self.scrollTop)}
	},
	
	/*
	* ��ʾ����
	*/
	show : function(id){
		if (!id) id = this.config.id;
		var self = this, dialog = self.list[id], options =self.options[id], pos;
		var removeOpacity =function(){
				dialog.css({opacity:''});
			};	
		pos = self.getPosition(id);
		
		self.setPosition(id, pos);
		if(options.minScrollTop){
			if(options.minScrollTop > self.scrollTop){
				//scrollTop ���� minScrollTop ������
				//Ҳ����ʹ��hide()������������ܵ�����ʾλ�ò���ȷ
				dialog.css({visibility:'hidden'});
			}
			
		}else{
			if(options.effects && typeof options['isLoad'] == 'undefined'){
				//ָ���˴���Ч�Ĵ��ڣ������״μ���ʱ
				var top=pos.top, height=dialog.height();
				dialog.stop().show();
				switch(options.effects){
					case 'down':
						dialog.css({left:pos.left, top:-1000, opacity:0.1})
							.animate({top:top,opacity:1},self.config.effect_time_down, removeOpacity );
						setTimeout(function(){removeOpacity()}, self.config.effect_time_down);
						//IE������ĳ�������ò�Ʋ���ִ������� animate ������������Ҫʹ��setTimeout����ͬ
					break;case 'up':
						dialog.css({left:pos.left, top:top+height, opacity:0.1})
							.animate({top:top,opacity:1},self.config.effect_time_up, removeOpacity );
						setTimeout(function(){removeOpacity()}, self.config.effect_time_up);
					break;case 'fade':
						//var duration = (typeof options.position == "object")?200:300;
						dialog.css({left:pos.left, top:pos.top, opacity:0.1});
						dialog.animate({opacity:1}, self.config.effect_time_fade, removeOpacity);
						setTimeout(function(){removeOpacity()}, self.config.effect_time_fade);
					break;
				}
				
			}else if(options.effects){
				//ָ���˴���Ч�Ĵ��ڣ��ٴ�ʵ�� show()
				var dialog = self.list[id];
				if(/^(?:up|down)$/i.test(options.effects) && options.resetTime>0){
					dialog.show().animate({top:pos.top, left:pos.left, opacity:1}, options.resetTime, removeOpacity);
				}else{
					dialog.show().css({top:pos.top, left:pos.left, opacity:''});
				}
			}else{
				//δָ������Ч�Ĵ���
				dialog.css({top:pos.top, left:pos.left, opacity:''}).show();
			}
			//self.config.zIndex+=1;
			dialog.css({zIndex: ++self.config.zIndex});
		}
		
		setTimeout(function(){
			var inputTexts = dialog.find(":text").filter(":visible");
			if (!inputTexts.length) {
				inputTexts = dialog.find("textarea:visible");
			}
			if (inputTexts.length) {
				inputTexts.eq(0).focus();
				//ִ��focus()����input��ֵ��FFĬ���ǽ���궨λ��ֵ�ĺ��棬��IE��Ĭ�϶�λ����ǰ��
				//ͳһ����ΪĬ�϶�λ���ı����ֵ���棬����ͨ������ self.config.focusStart ����
				var el=inputTexts.eq(0)[0];
				if(el.createTextRange){
					var re = el.createTextRange();
					re.select();
					re.collapse(self.config.focusStart);
					re.select();
				}else if(el.setSelectionRange){//������ FF Chrome ��
					if(self.config.focusStart){
						el.setSelectionRange(0, 0);
					}else{
						el.setSelectionRange(el.value.length, el.value.length);
					}
				}
			}
		}, 200);
		self.isOpen[id]=1;
		self.isClose[id]=0;
		return self;
	},
	
	autoClose: function(id){
		var self=this, options =self.options[id];
		if(options['clockAutoClose']){clearTimeout(options['clockAutoClose'])}
		options['clockAutoClose']=setTimeout(function(){
			$.dialog.close(id);
		}, options.autoClose);
		return false;
	},
	
	/*
	* �رմ���
	*/
	close : function(id){
		if (!id) id = this.list.length -1;
		if(typeof this.list[id] == 'undefined') return;
		var self=this, dialog = self.list[id], options =self.options[id], height=dialog.height(),pos=self.getPosition(id);

		function fn_dialogClose(){
			dialog.stop().css({top:-1999, opacity:'', display:'none'});
			self.isOpen[id]=0;//�˾������ self.hideOverlay() ֮ǰ�������ܹر����ֲ�
			self.isClose[id]=1;
			self.hideOverlay();
		}
		
		if (dialog && self.isShow(id)){
			if(self.fn_clockScroll[options.id]) $(window).unbind('scroll', self.fn_clockScroll[options.id]);
			if(options['clockAutoClose']) clearTimeout(options['clockAutoClose']);
			dialog.unbind('mouseleave').stop();
			
			var o_dialogClose={duration:200, complete:fn_dialogClose};
			switch(options.effects){
				case 'down':
					dialog.animate({top:-1999, opacity:0.1}, o_dialogClose);
				break;case 'up':
					dialog.animate({top:pos.top+height+100, opacity:0.1}, o_dialogClose);
				break;case 'fade':
					dialog.animate({opacity:0.1}, o_dialogClose);
				break;default:
					fn_dialogClose();
			}
			
		}else if(dialog){
			//Ϊ��ֹһЩ���⣬����رհ�ťһ���ᴥ���رղ���
			fn_dialogClose();
		}
		
		if(dialog){
			if(options.effects || $.IE6) setTimeout(function(){fn_dialogClose()}, 500);
		}
	},
	
	//�жϴ����Ƿ���ʾ
	isShow : function(id){
		if(this.isClose[id]) return false;
		if(this.isOpen[id]) return true;
	},
	
	/*
	* ��������
	*/
	build : function(options){
		var self =this, id =options.id, dialog=self.list[id], part, width=options.width, _id="dialog_"+id, isExist=0;
		self.config.zIndex++;
		if (!dialog){
			//��ID�Ĵ����״δ�
			isExist=0;
			dialog = $('#'+_id);
			var position=(options.fixed && !$.IE6)?'fixed':'absolute';
			
			if(dialog.size()){//�� ID �� dialog �Ѿ����ڣ�ֱ��ʹ�ã��ʺ��Զ��� dialog ����
				dialog.css({position:position}).attr('customize', '1');
				if(options.skin) dialog.addClass(options.skin);
				options['type']='customize';
			}else{
				//����һ��dialog
				var html='<div class="hudong_dialog new_guide" id="'+_id+'" style="position:'+position+';">';
				html +='<h2 class="title">'+options.title+'</h2>';
				html +='<div class="content"></div>';
				html +='<div class="button">';
				html +='<input type="button" class="ok" name="ok" value="'+options.textOk+'"/>';
				html +='<input type="button" class="cancel" name="cancel" value="'+options.textCancel+'"/>';
				html +='</div><a class="close"/></div>';
				$('body').append(html);
				dialog = $('#'+_id);
			}
			
			self.list[id] = dialog;
			
			part=self.parts[id] ={
				title:dialog.children("h2.title"),
				close:dialog.children("a.close"),
				content:dialog.children("div.content"),
				button:dialog.children("div.button"),
				ok:dialog.find("input.ok"),
				cancel:dialog.find("input.cancel")
			};
			
			if(!part.title){
				part.title=dialog.find("[name='dialog_title']");
			}
			if(!part.close){
				part.close=dialog.find("[name='dialog_close']");
			}
		}else{
			isExist=1;
			part=self.parts[id];
			part.title.html(options.title);
		}
		
		dialog.stop().css({'zIndex':self.config.zIndex++, 'opacity':''});
		
		if(options.key && self.keys[id]==options.key){
			//ͨ���ж� key �����⵱�����ظ���ͬһ������ʱ�������ظ�����
			if (self.isShow(id)){
				//��ǰ�����Ѿ�������ʾ״̬�������ظ�ִ����ʾ����
				$.dialog.close(id);
			}else{
				self.show(id);
				
				//�ٴ�ִ�� callback()
				if ($.isFunction(options.callback)){
					setTimeout(function(){options.callback(dialog)}, options.callbackTimeout);
				}
			}
			//========================
			//����Ҫ�ٴΰ��¼�����ֹ
			//========================
			return;
		}else{
			//��ID�Ĵ���δָ��options.key���Ƽ�ָ��options.key�����Ƕ���ÿ�δ򿪶���Ҫ��ʼ�����ݵ������Ҫָ��options.key
			//�����״δ�
			
			self.setOptions(options);
			//�����Ѿ���ʾ����
			self.keys[id]=options.key ?options.key :'';
		}
		
		//�����Զ��ر��¼�,ÿ�δ򿪴��ڶ���Ҫ����
		if(options.autoClose){
			options.autoClose = parseInt(options.autoClose);
			options.autoClose = isNaN(options.autoClose)?2000:options.autoClose;
			
			/*
			//���˺�����Ϊ�� $._dialog.autoClose()
			function fn_clockAutoClose(id){
				var options =self.options[id];
				if(options['clockAutoClose']){clearTimeout(options['clockAutoClose'])}
				options['clockAutoClose']=setTimeout(function(){
					$.dialog.close(id);
				}, options.autoClose);
				return false;
			}
			*/
			self.autoClose(options.id);
			
			if(options.title && !options.forceClose){
				//������ڱ��������������dialog��ʱȡ���Զ��ر�
				//����뿪dialogʱ�ٴΰ��Զ��ر�
				dialog.unbind('mouseover').mouseover(function(){
					clearTimeout(options['clockAutoClose']);
				}).unbind('mouseleave').mouseleave(function(e){
					var id=$(this).attr('id').replace('dialog_', '');
					self.autoClose(id);
				});
			}
		}
		
		if(isExist){//�� ID �Ĵ����Ѿ�����
			return;
			//============================================
			// ������Ҫ�ظ����¼�����ֹ
			//============================================
		}
		
		dialog.unbind('click').click(function(){
			//ʹ�þֲ���������ȫ�ֱ����������ȶ�
			var id, dialog;
			id=$(this).attr("id").replace("dialog_", "");
			dialog=$.dialog.get(id);

			if (self.config.id != id){
				//�������Ĵ��ڲ��ǵ�ǰ�����Ĵ���
				self.config.lastid = self.config.id;
				self.config.id = id;
				self.config.zIndex +=1;
				dialog.css({'opacity':'', 'zIndex':self.config.zIndex});
			}
		});
		
		//���¼�
		if(part.title.length){
			//mousedown move
			if (options.move){
				part.title.unbind('mousedown').mousedown(function(e){
					//ʹ�þֲ���������ȫ�ֱ����������ȶ�
					var id, dialog;
					id=$(this).parents(".hudong_dialog").attr("id").replace("dialog_", "");
					dialog=$.dialog.get(id);
					self.config.zIndex +=1;
					dialog.css({'zIndex':self.config.zIndex});
					var offset = dialog.offset();
					self.config.offsetClick = {
						width: e.pageX - offset.left,
						height:e.pageY - offset.top,
						left:dialog.css('left'),//left:offset.left,
						top:dialog.css('top') //top:offset.top
					};
					
					var fn_mousemove=function(e){self.move(e, id);return false;};
					var fn_selectstart=function(){return false};
					$(document).mousemove(fn_mousemove).bind("selectstart", fn_selectstart);
					
					$(document).one('mouseup',function(e){
						var o=self.config.offsetClick, pos={left:o.left, top:o.top};
						if (e.clientY < 0 || e.clientX < 2
							|| e.clientX > $(document).width()
							|| e.clientY > $(window).height()
						){
							dialog.css(pos);
						}
						$(document).unbind("mousemove", fn_mousemove).unbind("selectstart", fn_selectstart);
						return false;
					});
					return false;
				});
			}
			
			if(options.title && options.model.indexOf('mini')>-1){
				part.title.unbind('dblclick').bind("dblclick", function(){
					//ʹ�þֲ���������ȫ�ֱ����������ȶ�
					var id=$(this).parents(".hudong_dialog").attr("id").replace("dialog_", "");
					var part = self.parts[id];
					part.content.toggle();
					return false;
				});
			}
			
			part.title.unbind('selectstart').bind("selectstart", function(){return false});
		}
		
		/*
		var fn_clockScroll = self.fn_clockScroll[options.id] = function(e){
			if(options['clockScroll']){clearTimeout(options['clockScroll'])}
			
			options['clockScroll']=setTimeout(function(){
				//���ڹ���ֹͣ��ִ�����´���
				self.scrollTop = $(document).scrollTop();//���¼��� self.scrollTop
				var pos = self.getPosition(options.id);
				dialog.stop();
				if(options.minScrollTop){
					if(options.minScrollTop >self.scrollTop){
						dialog.css({visibility:'hidden'});
					}else{
						dialog.css({left:pos.left, top:pos.top, visibility:'visible'});
					}
					
					if(options.autoClose){fn_clockAutoClose(options.id)}
					return;
				}
				
				if($.IE6 && options.fixed){
					dialog.animate({left:pos.left, top:pos.top}, 200);
					if(options.autoClose){fn_clockAutoClose(options.id)}
				}
			}, 200);
			return false;
		}
		
		$(window).unbind('scroll', fn_clockScroll).bind('scroll',fn_clockScroll);
		*/
		return this;
	},
	
	//�ƶ�����
	move : function(e, id){
		var self=this, dialog=self.list[id], options=self.options[id], offset=self.config.offsetClick,
			left=e.pageX-offset.width, top=e.pageY-offset.height;
		self.bodyLeft=self.bodyLeft||$('body').position().left;
		
		if (options.fixed){
			if(!$.IE6){top -=self.scrollTop} //$(document).scrollTop()
		}else{
			left = parseInt(left-self.bodyLeft);
		}
		
		dialog.css({left:left, top:top});
		return false;
	},
	
	//���ô���λ��
	setPosition :function(id, pos){
		var dialog = this.list[id], options = this.options[id];
		pos=pos||this.getPosition(id);
		if (typeof pos == 'object'){
			if(!/up|down/i.test(options.effects)){
				dialog.css(pos);
			}
		}
	},
	
	/*
	* ����option�������ش��ڵĺ�������
	*/
	getPosition : function(id){
		var self=this, left=0, top=0;
		var dialog =self.list[id], options = self.options[id];
		//var winW =$(window).width(), winH =$(window).height(), sL=$(document).scrollLeft(), sT =$(document).scrollTop();
		var winW =self.windowWidth, winH =self.windowHeight, sT=self.scrollTop, sL=self.scrollLeft;
		
		var dH = dialog.outerHeight(), dW = dialog.outerWidth();
		options.position = options.position || 'middle';
		switch(options.position){
			case 'center':
			case 'middle':
			case 'c':
			case 'm':
				left = (winW - dW)/2;
				top  = (winH - dH)/2;
				break;
			case 'rb':
			case 'br':
			case 'rightBottom':
			case 'bottomRight':
				left = winW - dW -4;
				top  = winH - dH -3;
				break;
			case 'rt':
			case 'tr':
			case 'rightTop':
			case 'topRight':
				left = winW - dW -4;
				top  = 1;
				break;
			case 'lt':
			case 'tl':
			case 'leftTop':
			case 'topLeft':
				left = 0;
				top  = 0;
				break;
			case 'ct':
			case 'tc':
			case 'centerTop':
			case 'topCenter':
				left = (winW - dW)/2;
				top  = 0;
				break;
			case 'cb':
			case 'bc':
			case 'centerBottom':
			case 'bottomCenter':
				left = (winW - dW)/2;
				top  = winH - dH;
				break;
			case 'lb':
			case 'bl':
			case 'leftBottom':
			case 'bottomLeft':
				left = 0;
				top  = winH - dH -3;
				break;
			default:
				if (typeof options.position != "object") break;
				var E = $(options.position);
				
				//��IE��������ܲ�����ȷ��ȡ�ⲿԪ�صĸ߶ȣ�����ҪתΪ�ڲ���ͼƬ
				if (!$.IE && E.find('img').length > 0){
					E = E.find('img');
				}
				
				var offset = E.offset(), eH = E.outerHeight(), eW = E.outerWidth();
				
				var w1, w2, h1, h2;
				//��Ҫ˼·�����ݶ�λ�Ĳο����󣬽����ڷ�Ϊ4���������ϡ����¡����ϡ����£��ж��ĸ�����Ƚ��ʺ���ʾ����
				w1 = offset.left - sL + eW; //������಻�����������֣���ο������Ҳ�ľ��룬��������
				w2 = winW + sL - offset.left; //�����Ҳ�
				h1 = offset.top - sT + eH; //�ϣ�������ı�����
				h2 = winH + sT - offset.top; //�£�������ı���
				
				if (w2 > dW && w1 > dW){//���Ҷ�����
					left = (w2>w1) //ѡ������һ��
						? offset.left -3
						: offset.left + eW - dW +3;
				}else if (w2 > dW){//ѡ���Ҳ�
					left = offset.left -3;
				}else if (w1 > dW){//ѡ�����
					left = offset.left + eW - dW +3;
				}else {//���Ҿ���
					left = sL + (winW - dW)/2;
				}
				
				//left = (dW < 800) ? left : sL + (winW - dW)/2;
				
				if (h2 > dH){//ѡ������
					top = (eH < 50) ? offset.top + eH : offset.top;
				}else if (h1 > dH){//ѡ������
					top = (eH < 50) ? offset.top - dH : offset.top -dH + eH;
				}else {//���¾���
					top  = sT + (winH - dH)/2;
				}
				top = top > sT ? top : sT;//���������ܱ�����ʱ������������Ϊ��scrollTop���
				top = (winH + sT > offset.top + eH)//�жϵ�ǰ����Ķ����Ƿ񳬳��˵�ǰ��������ڵ����¶�
					? top //��ǰ����Ķ����ڵ�ǰ��������ڷ�Χ֮�ڣ�����λ�ñ��ֲ���
					: winH + sT - dH; //��ǰ����Ķ��󳬳��˵�ǰ��������ڵ����¶ˣ�dialog����������ڵ����¶˶��룬�������ͼƬ�����򶥲����ܱ�����
				
		}
		
		if (typeof options.position != "object" && (!options.fixed || $.IE6 )){
			left += sL;
			top  += sT;
		}

		if(!self.bodyLeft){init_size()}
		
		if (!options.fixed && self.bodyPosition == 'relative'){
			left = left - self.bodyLeft;
		}else if(options.fixed && $.IE6){
			left = left - self.bodyLeft;
		}
		
		left +=options.offsetX;
		top +=options.offsetY;
		
		top = top > 0 ? top : 0;
		left = left > 0 ? left : 0;
		return {"left":left, "top":top};
	},
	
	/*
	* �������ֲ�
	*/
	hideOverlay : function(){
		var self=this;
		for(id in self.options){
			var options = self.options[id];
			if (options.overlay && self.isShow(id)){return false}
		}
		$("div.hudong_overlay").hide();
	},
	
	/*
	* �������ֲ�
	*/
	setOverlay : function(did){
		var self=this, id = 'hudong_overlay'+did, height = $(document).height(), width="100%",left=0;
		var options=self.options[did], overlay = $('div#'+id);
		
		//��body.style.position == 'relative' ����window.resize()ʱ��Ҫ����setOverlay()
		//if ($(".hudong_overlay:visible").length > 0){return self}
		if (overlay.length > 0){
			overlay.show();
		}else{
			$('body').append('<div class="hudong_overlay" id="'+id+'"></div>');
			overlay = $('div#'+id);
		}
		
		self.windowWidth =$(window).width();
		self.bodyLeft    =$(document).find('body').position().left;
		if(!self.bodyLeft && self.windowWidth > self.bodyWidth){
			self.bodyLeft=(self.windowWidth - self.bodyWidth)/2;
		}
	
		if(!self.bodyLeft){init_size()}
		if (self.bodyPosition == 'relative'){
			width = self.windowWidth;
			left=-self.bodyLeft;
		}
		
		if(options['styleOverlay']){overlay.css(options['styleOverlay'])}
		overlay.css({left:left, width:width, height:height, zIndex:self.config.zIndex});
		return self;
	}
}


/*
 * $.dialog
 * 
 */
$.dialog = function(options){$._dialog.open(options)}
$.extend($.dialog, {
	box:function(id, title, content, position, callback){
		var options={valign:'center', styleOverlay:{backgroundColor:'#FFFFFF'}}
		this.open(id, title, content, position, callback, options);
	},
	get: function(id){
		var dialog=$._dialog.list[id];
		if(dialog){return dialog}else{alert("��ʾ��idΪ"+id+"��dialog�����ڡ�")}
		
	},
	options:function(id){
		return $._dialog.options[id];
	},
	open:function(id, title, content, position, callback, config){
		var options = {id:id, title:title, position:position, callback:callback};
		if(config && typeof config =='object'){
			for(var key in config){
				options[key]=config[key];
			}
		}
		if (content.substr(0,4) == 'url:'){
			options.type = 'url';
			options.url = content.substr(4);
		}else if (content.substr(0,4) == 'img:'){
			options.type = 'img';
			options.url = content.substr(4);
		}else if (content.substr(0,7) == 'iframe:'){
			options.type = 'iframe';
			options.url = content.substr(7);
		}else if (content.substr(0,9) == 'selector:'){
			options.type = 'selector';
			options.content = content.substr(9);
		}else {
			options.content = content;
		}
		$._dialog.open(options);
	},
	tip:function(content, title, autoClose, skin, onOk, id){
		title = title || '��ʾ';
		autoClose = autoClose || 30000;
		skin = skin || $._dialog["config"]["skin"];
		id = id||'jquery_dialog_tip';
		
		height = typeof onOk == "function" ? 180 : 200;
		
		var options = {id:id, title:title, position:'c', skin:skin,
			content:content,
			valign:'center',
			width:320,
			height:height,
			autoClose:autoClose,
			onOk:onOk,
			styleContent:{'verticalAlign':'middle'},
		//	styleBtn:{},
			offsetY:-80,
			resetTime:0,
			resizable:0
		};
		$._dialog.open(options);
	},
	alert:function(content, title, autoClose, skin, id){
		id = id || 'jquery_dialog_alert';
		this.tip(content, title, autoClose, skin, function(){$._dialog.close(id)}, id);
	},
	addSkin: function(strStyle){
		strStyle = strStyle.replace(/{base}/i, $._dialog.config.base);
		$('head').append('<style>'+ strStyle +'</style>');
	},
	close:function(id){
		var options=$._dialog.options[id];
		if(typeof options == "undefined"){
			alert('��ʾ��id='+id+' ��dialog�����ڣ�');
			return;
		}
		if($.isFunction(options.onClose)){options.onClose()}
		$._dialog.close(id);
	},
	resize: function(id){$._dialog.resize(id)},
	show: function(id){$._dialog.show(id)},
	ok:function(id){$._dialog.close(id)},
	exist:function(id){return $._dialog.list[id]},
	setConfig:function(key, value, config){
		if (!key) return this;
		config = config || 'config';
		if (typeof key == 'string' && value !== null) {
			$._dialog[config][key] = value;
		}else if (typeof key == 'object') {
			$.extend($._dialog[config], key);
		}
		return this;
	},
	setDefaults: function(key, value){
		return this.setConfig(key, value, 'defaults');
	},
	content : function(id, content){
		if(content){
			$._dialog.setContent(id, content);
			$._dialog.resize(id);
		}else{
			var dialog=this.get(id);
			if(dialog) return dialog.find('div.content').html();
			else return "";
		}
	},
	remove: function(id){
		var dialog=this.get(id);
		dialog.remove();
		$._dialog.list[id]=null;
		$._dialog.options[id]=null;
	},

	initSize: function(){
		init_size();
	}
});

if (/^http:\/\/(\w+\.){1,2}hudong\.com/i.test(location.href)){
	$.dialog.setConfig({'base':'http://www.huimg.cn/lib/dialog', skin:'bluebox'});
}

})(jQuery);
