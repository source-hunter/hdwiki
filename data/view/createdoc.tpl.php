<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript" src="js/popWindow.js"></script>
<script type="text/javascript">
	function verifydoc(){
		title=$.trim($('#title').val());
		if(title==""){
			$('#response').html('<label style="color:#FF0000">�������Ʋ���Ϊ��!</label>');
		}else if(title.length>80){
			$('#response').html('<label style="color:#FF0000">�������Ʋ��ܳ���80�����ֻ���ĸ!</label>');
		}else{
			$.ajax({
				url: "index.php?doc-verify",
				cache: false,
				dataType: "xml",
				type:"post",
				//async:false, 
				data: {title:title},
				success: function(xml){
				   var message=xml.lastChild.firstChild.nodeValue;
					if(message=='1'){
						$('#response').html('<label>���� "'+title+'" ���Դ���!</label>');
					}else if(message=='-1'){
						$('#response').html('<label style="color:#FF0000">���� "'+title+'" ����Υ����!</label>');
					}else if(message=='-2'){
						$('#response').html('<label style="color:#FF0000">���� ����Σ�մ���</label>');
					}else if(message.length>'3'){
						var message=$.trim(message).split(/ +/);
						$('#response').html('<label style="color:#FF0000">����"'+title+'"��<a href="<?php echo $setting["seo_prefix"]?>doc-view-'+message[0]+'">"'+message[1]+'"</a>��ͬ��ʡ�</label>');
					}else{
						$('#response').html('<label style="color:#FF0000">���� "'+title+'" �Ѿ�����!</label>');
					}
				}
			});
		}
	}

	function docheck(){
		title=$.trim($('#title').val());
		if(title==""){
			$('#response').html('<label style="color:#FF0000">�������Ʋ���Ϊ��!</label>');
			return false;
		}
		return true;
	}
	var catevalue = {
		input:null,
		scids:new Array(),
		scnames:new Array(),
		ajax:function(cateid, E){
			if(!cateid)cateid=0
			$.ajax({
				url: 'index.php?category-ajax-'+cateid,
				cache: false,
				dataType: "xml",
				type:"get",
				success: function(xml){
					var message=xml.lastChild.firstChild.nodeValue;
					if($('#dialog_category:visible').size()){
						$.dialog.content('category', '<div id="flsx" class="chose_cate">'+message+'</div>');
						catevalue.selectCategory();
					}else{
						$.dialog({
							id:'category',
							title:'��������',
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
			$('#columntitle').html(catevalue.getCatUrl());
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
			$('#flsx').hide();
		},
		
		ok:function(){
			$('#flsx').hide();
		},		
		
		init:function(){
			if('<?php if(!empty($category['cid'])) { ?><?php echo $category['cid']?><?php } ?>'){
				this.scids.push('<?php if(!empty($category['cid'])) { ?><?php echo $category['cid']?><?php } ?>');
				this.scnames.push('<?php if(!empty($category['name'])) { ?><?php echo $category['name']?><?php } ?>');
			}
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
	catevalue.init();
</script>
	<p class="azmsx w-950"><span class="col-h2 block bold">����ĸ˳�����:</span>
	<a href="index.php?list-letter-A" >A</a>
	<a href="index.php?list-letter-B" >B</a>
	<a href="index.php?list-letter-C" >C</a>
	<a href="index.php?list-letter-D" >D</a>
	<a href="index.php?list-letter-E" >E</a>
	<a href="index.php?list-letter-F" >F</a>
	<a href="index.php?list-letter-G" >G</a>
	<a href="index.php?list-letter-H" >H</a>
	<a href="index.php?list-letter-I" >I</a>
	<a href="index.php?list-letter-J" >J</a>
	<a href="index.php?list-letter-K" >K</a>
	<a href="index.php?list-letter-L" >L</a>
	<a href="index.php?list-letter-M" >M</a>
	<a href="index.php?list-letter-N" >N</a>
	<a href="index.php?list-letter-O" >O</a>
	<a href="index.php?list-letter-P" >P</a>
	<a href="index.php?list-letter-Q" >Q</a>
	<a href="index.php?list-letter-R" >R</a>
	<a href="index.php?list-letter-S" >S</a>
	<a href="index.php?list-letter-T" >T</a>
	<a href="index.php?list-letter-U" >U</a>
	<a href="index.php?list-letter-V" >V</a>
	<a href="index.php?list-letter-W" >W</a>
	<a href="index.php?list-letter-X" >X</a>
	<a href="index.php?list-letter-Y" >Y</a>
	<a href="index.php?list-letter-Z" >Z</a>
	</p>
<form id="form1" name="form1" action="index.php?doc-create" method="post" onsubmit="return docheck();">
<div id="map" class="hd_map bor_no"><a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt;&gt; ��������</div>
<div class="l w-710">
  <div class="p-10 columns cre_main">
    <h2 class="col-p">��������</h2>
      <ul class="ul_l_s" >
        <li><span>��������:</span>
          <input id="title" name="title" value="<?php if(isset($title)) { ?><?php echo $title?><?php } ?>" type="text" class="reg-inp" onblur="verifydoc();" maxlength="80"/>
          <label id="response"></label><input type="hidden" id="category" name="category" value="<?php echo $category['cid']?>" /></li>
        <li><span>��������:</span>
          <input name="Button2" type="button" value="ѡ��/�޸�" class="chose m-r8" onclick="javascript:catevalue.ajax(0,this);" />
          ��Ϊ��Ҫ�����Ĵ���ѡ��һ�����ʵķ��ࡣ</li>
        <li><span>��ѡ�����:</span><p id="columntitle" name="columntitle">&nbsp;<a href="index.php?category-view-<?php echo $category['cid']?>" ><?php if(!empty($category['name'])) { ?> <?php echo $category['name']?><?php } ?></a></p></li>	
        <li>
		  <input name="hdwiki" type='text' style="display:none"><input name="create_submit" type="submit" value="��������" class="btn_inp"/>
        </li>
      </ul>
  </div>
  <div id="block_left"></div>
</div>
</form>
<div class="r w-230">
  <div class="columns cre_r">
    <h3 class="bold">������׼�淶:</h3>
    <ul class="col-ul">
      <li>�������Ǵ����δʵĳ����Դ���</li>
      <li>�������Ƕ�������</li>
      <li>�����������۵Ļ���</li>
      <li>�����Ժ���������źͿո�</li>
      <li>�����Ժ��д����</li>
      <li>Ӣ��ʱ�����ȫ��</li>
      <li>�����ࡢ���������²�����Ϊ�ٿƴ���</li>
    </ul>
  </div>
  <div id="block_right"></div>
</div>
<?php include $this->gettpl('footer');?>