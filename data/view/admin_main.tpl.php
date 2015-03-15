<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=<?php echo WIKI_CHARSET?>" http-equiv="Content-Type" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="style/default/admin/admin.css" type="text/css" rel="stylesheet" media="all"/>
<title>HDWIKI ��̨-��������</title>
<style type="text/css">
body{padding-left:160px;background:#f8f8f9 url(style/default/admin/left_bg.gif) repeat-y left;}
html,body{height:100%;position:relative;overflow:hidden;}
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/help_user.js"></script>
<script type="text/javascript">
	$.dialog.setConfig('base', '<?php echo WIKI_URL?>/style/default');
	var isSetSearchWordColor=0;
	function setSearchWordColor(keywords){
		if(isSetSearchWordColor){
			return;
		}

		isSetSearchWordColor = 1;
		keywords = keywords.split(" ");

		$("#main").bind("load.search", function(){
			$("#main").unbind("load.search");
			var main = this.contentWindow.document;
			$("td span" ,main).each(function(){
				var o = $(this);
				$.each(keywords, function(i, n){
					var html = o.html();
					html = html.replace(n, "<font color='#c60a00'>"+n+"</font>");
					o.html(html);
				});
			});
			isSetSearchWordColor = 0;
		});
	}

	$(function() {
		initMenu();
		shortcut.getShortcut();
	});
	function getMenu(id,obj){
		$("#nav li").removeClass("on");
		$(obj).addClass("on");
		changeMenu(id);
		$("#main").attr("src",$("#"+id+" .on").attr("href"));
	}
	function changeMenu(id){
		$("#menu").html($("#"+id).html());
		$("#menu a").click( function () {$("#menu a").removeClass("on");$(this).addClass("on")});
	}
	function initMenu(url){
		var url = url || "index.php?<?php echo $admin_mainframe?>";
		$("#menulist").find('a').each(function(i){
			if(url.indexOf($(this).attr('href')) >=0){
				var menuId=$(this).parent().parent().parent().attr("id");
				$("#nav li").removeClass("on");
				$("#"+menuId+"menu").addClass("on");
				$("#"+menuId+" a").removeClass("on");
				$(this).addClass("on");
				changeMenu(menuId);
			}
		})
	}
	var topMenu=['��ҳ','ȫ��','�û�����','���ݹ���','ģ��/���','���ݿ����','�ٿ�����','ģ��','վ��ͳ��','Map'];
	var shortcut={
		displayShortcut:function(type){
			if(type=='display'){
				$("#shortcut").css("display",'block');
			}else{
				$("#shortcut").css("display",'none');
			}
		},
		getShortcut:function (){
			var shortstr="<?php if(isset($setting['shortcut'])) { ?><?php echo $setting['shortcut']?><?php } ?>";
			var html='';
			$('#menubox input').each(function(i){
				if(shortstr.indexOf($(this).val()) >=0){
					$(this).attr('checked','checked');
				}
			})
		},
		clearShortcut:function (){
			$("input[name='menu[]']").attr('checked','');
		},
		setShortcut:function (){
			var html='';
			var link='';
			var shortcutstate=$("input[name='shortcutstate']:checked").length;
			if($("input[name='menu[]']:checked").length>5){
				alert("�������5����ݹ���");return false;
			}
			$("input[name='menu[]']:checked").each(function(i){
				html+="<li><a href=\""+$(this).val()+"\" target=\"main\">"+$(this).attr('title')+"</a></li>";
				link+=$(this).val()+','+$(this).attr('title')+';';
			});
			$.ajax({
				url: "index.php?admin_setting-shortcut",data: {link:link,shortcutstate:shortcutstate},cache: false,dataType: "xml",type:"post",async:false,
				success: function(xml){
					var	message=xml.lastChild.firstChild.nodeValue;
					if(message=='1'){
						$("#shortcutmenu").html(html);
						shortcut.displayShortcut('none');
					}else{
						alert('��ݷ�ʽ����δ�ɹ�');
					}
				}
			});
		},
		hideShortcut:function (){
			dispaly='none';
			$("#shortcutimg").attr('src','style/default/admin/menu-box_b2.gif');
			if($("#shortcutmenu").css('display')=='none'){
				dispaly='block';
				$("#shortcutimg").attr('src','style/default/admin/menu-box_b.gif');
			}
			$("#shortcutmenu").css('display',dispaly);
		}
	}

	function onsearch(){

	}

	$(document).ready(function(){
		$("form[name=search]").submit(function(){
			var input=$(this).find(':text'), keywords=$.trim(input.val());

			if(!keywords){
				alert("������Ҫ�����Ĳ˵����ƹؼ��֣��� �������� �ȡ�");
				input.focus();
				return false;
			}else if(/[`~!@#$%^&*<>'"\/\\]/i.test(keywords)){
				alert("�˵����ؼ��ֲ�Ҫ���������ַ�����~!@#$%^&*<>'\"\\\/�ȡ�");
				input.focus();
				return false;
			}

		});
	});
</script>
</head>
<body scrolling="no">


<table width="100%" height="100%">
	<tr>
		<td colspan="2" height="76px">
			<div class="head">
				<p class="r a-r t-tips">
				<a href="index.php" target="_blank">ǰ̨��ҳ</a>|<a href="http://kaiyuan.hudong.com/bbs/" target="_blank" class="red">����</a>|<a onclick="javascript:apply(1);" href="javascript:viod(0);">��������</a>|<a href="http://kaiyuan.hudong.com/kaiyuanhome/c/contact_us.htm" target="main">��ϵ����</a>|<a href="http://kaiyuan.hudong.com/bbs" target="_blank">��Ҫ����</a>[<a href="index.php?admin_main-logout">�˳���������</a>]
				</p>
				<form name="search" class="seach m-r10" action="index.php?admin_actions" target="main" method="post">
				<p class="search">
				<input name="keywords" type="text" class="inp_txt w-140" value="������̨���ܲ˵�" onfocus="if (value =='������̨���ܲ˵�'){value =''}" onblur="if (value ==''){value='������̨���ܲ˵�'}"/> <input type="submit" value="�Ѳ˵�" class="inp_btn3"/>
				</p>
				</form>
				<ul id="nav">
				<li onclick="getMenu('index',this)" id="indexmenu" ><a href="javascript:void(0);"><span>��ҳ</span></a></li>
				<li onclick="getMenu('global',this)" id="globalmenu"><a href="javascript:void(0);"><span>ȫ ��</span></a></li>
				<li onclick="getMenu('user',this)" id="usermenu"><a href="javascript:void(0);"><span>�û�����</span></a>|</li>
				<li onclick="getMenu('content',this)"  id="contentmenu"><a href="javascript:void(0);"><span>���ݹ���</span></a>|</li>
				<li onclick="getMenu('plug',this)" id="plugmenu"><a href="javascript:void(0);"><span>ģ��/���</span></a>|</li>
				<li onclick="getMenu('database',this)" id="databasemenu"><a href="javascript:void(0);"><span>���ݿ����</span></a>|</li>
				<li onclick="getMenu('unions',this)" id="toolsmenu"><a href="javascript:void(0);"><span>�ٿ�����</span></a>|</li>
				<li onclick="getMenu('model',this)" id="toolsmenu"><a href="javascript:void(0);"><span>ģ��</span></a>|</li>
				<li onclick="getMenu('statistics',this)" id="statisticsmenu"><a href="javascript:void(0);"><span>վ��ͳ��</span></a></li>
				<li onclick="getMenu('map',this)" id="mapmenu"><a href="javascript:void(0);"><span>Map</span></a></li>
				</ul>
			</div>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<div class="left">
				<a href="index.php?admin_main-mainframe" target="main" class="logo"><img src="style/default/admin/logo.gif" alt="HDWIKI�����̨" /></a>
				<div class="sidebar">
					<div class="menu-box">
						<h2><span class="r m-r8"><span onclick="shortcut.displayShortcut('display');">����</span></span>�Զ���˵�</h2>
						<ul id='shortcutmenu' <?php if(isset($setting['shortcutstate'])) { ?> style="display:none" <?php } ?>>
						<?php foreach((array)$shortlist as $shorcut) {?>
							<li><a href="<?php echo $shorcut[0]?>" target="main"><?php echo $shorcut[1]?></a></li>
						<?php } ?>
						</ul>
						<p class="menu-box_b"><span onclick="shortcut.hideShortcut()"><img src="style/default/admin/menu-box_b.gif" id='shortcutimg'/></span></p>
					</div>
					<div id='menu'>
					</div>
				</div>

				<div style="display:none" id='menulist'>
					<div id='index'>
						<ul>
						<li><a href="index.php?admin_main-mainframe" target="main" class="on">��ҳ��Ϣ</a></li>
						</ul>
					</div>

					<div id='global'>
						<ul>
						<li><a href="index.php?admin_setting-base" target="main"  class="on">վ������</a></li>
						<li><a href="index.php?admin_channel" target="main">��������</a></li>
						<li><a href="index.php?admin_setting-sec" target="main">��չ����</a></li>
						<li><a href="index.php?admin_setting-index" target="main">��������</a></li>
						<li><a href="index.php?admin_friendlink" target="main">��������</a></li>
						<li><a href="index.php?admin_adv" target="main">������</a></li>
						<li><a href="index.php?admin_sitemap" target="main">Sitemap</a></li>
						<li><a href="index.php?admin_upgrade" target="main">�Զ�����</a></li>
						</ul>
					</div>

					<div id='user'>
						<ul>
						<li><a href="index.php?admin_setting-baseregister" target="main" >ע������</a></li>
						<li><a href="index.php?admin_user" target="main"  class="on">�����û�</a></li>
						<li><a href="index.php?admin_regular-groupset" target="main">����Ȩ��</a></li>
						<li><a href="index.php?admin_usergroup" target="main">�����û���</a></li>
						</ul>
					</div>

					<div id='content'>
						<ul>
							<li><a href="index.php?admin_category-list" target="main">�������</a></li>
							<li><a href="index.php?admin_doc" target="main"  class="on">��������</a></li>
							<li><a href="index.php?admin_attachment" target="main">��������</a></li>
							<li><a href="index.php?admin_comment" target="main">���۹���</a></li>
							<li><a href="index.php?admin_tag-hottag" target="main">���ű�ǩ</a></li>
							<li><a href="index.php?admin_hotsearch" target="main">��������</a></li>
							<li><a href="index.php?admin_word" target="main">�������</a></li>
							<li><a href="index.php?admin_datacall" target="main">���ݵ���</a></li>
							<li><a href="index.php?admin_recycle" target="main">����վ</a></li>
						</ul>
					</div>

					<div id='plug'>
						<ul>
						<li><a href="index.php?admin_theme" target="main" class="on">ģ�����</a></li>
						<li><a href="index.php?admin_plugin" target="main">�������</a></li>
						<li><a href="index.php?admin_language" target="main">��վ���Ա༭</a></li>
						</ul>
					</div>

					<div id='database'>
						<ul>
						<li><a href="index.php?admin_db-backup" target="main" class="on">���ݿⱸ��</a></li>
						<li><a href="index.php?admin_db-tablelist" target="main">���ݿ��Ż�</a></li>
						<li><a href="index.php?admin_db-sqlwindow" target="main">SQL��ѯ����</a></li>
						<li><a href="index.php?admin_db-storage" target="main">���ݴ洢����</a></li>
						</ul>
					</div>

					<div id='unions'>
						<ul>
						<li><a href="index.php?admin_hdapi" target="main" class="on">������ҳ</a></li>
						<li><a href="index.php?admin_hdapi-set" target="main">������</a></li>
						<li><a href="index.php?admin_share-set" target="main">������֪��</a></li>
						<li><a href="index.php?admin_hdapi-down" target="main">���ش���</a></li>
						<li><a href="index.php?admin_hdapi-info" target="main">�޸���������</a></li>
						</ul>
					</div>

					<div id='model'>
						<ul>
						<li><a href="index.php?admin_image" target="main" class="on">ͼƬ�ٿ�</a></li>
						<li><a href="index.php?admin_gift" target="main">��Ʒ�̵�</a></li>
						<li><a href="index.php?admin_safe" target="main">ľ��ɨ��</a></li>
						<li><a href="index.php?admin_filecheck" target="main">�ļ����</a></li>
						<li><a href="index.php?admin_dbcheck" target="main">���ݿ���</a></li>
						<li><?php if($setting['FTP_ENABLE']==1) { ?><a href="index.php?admin_fileftpmanage" target="main" class="on">�ļ�����</a><?php } else { ?><a href="index.php?admin_filemanager" target="main" class="on">�ļ�����</a> <?php } ?></li>
						<li><a href="index.php?admin_ftpsetting" target="main">FTP����</a></li>
						</ul>
					</div>

					<div id='statistics'>
						<ul>
						<li><a href="index.php?admin_statistics-stand" target="main" class="on">�����ſ�</a></li>
						<li><a href="index.php?admin_statistics-cat_toplist" target="main">��������</a></li>
						<li><a href="index.php?admin_statistics-doc_toplist" target="main">��������</a></li>
						<li><a href="index.php?admin_statistics-edit_toplist" target="main">�༭���а�</a></li>
						<li><a href="index.php?admin_statistics-credit_toplist" target="main">��������</a></li>
						<li><a href="index.php?admin_statistics-admin_team" target="main">�����Ŷ�</a></li>
						<li><a href="index.php?admin_log" target="main">��̨������¼</a></li>
						</ul>
					</div>

					<div id='map'>
						<ul>
						<li><a href="index.php?admin_actions-map" target="main" class="on">Map</a></li>
						</ul>
					</div>
				</div>
			</div>
		</td>
		<td height="90%"  valign="top">
			<iframe name="main" id="main" marginheight="0" marginwidth="0" frameborder="0" scrolling="yes"  style="width:100%; height:100%; overflow:visible;" src="index.php?<?php echo $admin_mainframe?>"></iframe>
		</td>
	</tr>
</table>

<div class="tc_bg" id="shortcut" style="display:none">
	<div class="tc">
		<form method="post" name='shortcutform'>
		<h2><a href="javascript:void(0);" onclick="shortcut.displayShortcut('none');" class="close"></a>���ÿ�ݹ���</h2>
		<ul class="col-ul ul_li_sp">
			<li><span>��ʾ״̬</span><input name="shortcutstate" type="checkbox" id="shortcutstate" <?php if($setting['shortcutstate']) { ?> checked="checked" <?php } ?>/>�۵���ʾ��ݲ˵�</li>
			<li><span>ѡ����</span><label class="s-tips">�������5����ݹ���</label>	<a href="javascript:void(0);" onclick="shortcut.clearShortcut();">���ѡ��</a></li>
			<li id="menubox">
				<div class="l">
					<dl>
						<dt>��������</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_channel" title="Ƶ������"/>Ƶ������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-cache" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-seo" title="SEO����"/>SEO����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-code" title="��֤��"/>��֤��</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-time" title="ʱ������"/>ʱ������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-cookie" title="COOKIE����"/>COOKIE����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-credit" title="����������"/>����������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-logo" title="LOGO����"/>LOGO����</dd>
					</dl>
					<dl>
						<dt>��������</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_friendlink" title="���������б�"/>���������б�</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_friendlink-add" title="�����������"/>�����������</dd>
					</dl>
					<dl>
						<dt>������</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_adv-default" title="������"/>������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_adv-config" title="���ù��"/>���ù��</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_adv-add" title="��ӹ��"/>��ӹ��</dd>
					</dl>
				</div>
				<div class="l">
					<dl>
						<dt>��������</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-index" title="��ҳ����"/>��ҳ����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-listdisplay" title="�б�����"/>�б�����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-watermark" title="ͼƬ����"/>ͼƬ����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-docset" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-search" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-attachment" title="��������"/>��������</dd>
					</dl>
					<dl>
						<dt>��չ����</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-sec" title="����ˮ����"/>����ˮ����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-anticopy" title="���ɼ�����"/>���ɼ�����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-mail" title="�ʼ�����"/>�ʼ�����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-noticemail" title="�ʼ���������"/>�ʼ���������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_banned" title="IP��ֹ"/>IP��ֹ</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-passport" title="ͨ��֤����"/>ͨ��֤����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-ucenter" title="UCenter����"/>UCenter����</dd>
                        <dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-ldap" title="UCenter����"/>LDAP����</dd>
					</dl>
				</div>
				<div class="l">
					<dl>
						<dt>���ݹ���</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_category-list" title="�������"/>�������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_doc" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_attachment" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_comment" title="���۹���"/>���۹���</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_tag-hottag" title="���ű�ǩ"/>���ű�ǩ</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hotsearch" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_word" title="�������"/>�������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_datacall" title="���ݵ���"/>���ݵ���</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_recycle" title="����վ"/>����վ</dd>
					</dl>
					<dl>
						<dt>ģ��</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_image" title="ͼƬ�ٿ�"/>ͼƬ�ٿ�</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_gift" title="��Ʒ�̵�"/>��Ʒ�̵�</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_safe" title="ľ��ɨ��"/>ľ��ɨ��</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_filecheck" title="�ļ����"/>�ļ����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_dbcheck" title="���ݿ���"/>���ݿ���</dd>
					</dl>
				</div>
				<div class="l">
					<dl>
						<dt>���|ģ��</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_theme" title="ģ�����"/>ģ�����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_plugin" title="�������"/>�������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_language" title="��վ���Ա༭"/>��վ���Ա༭</dd>
					</dl>
					<dl>
						<dt>�û�����</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-baseregister" title="ע������"/>ע������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_user" title="�����û�"/>�����û�</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_regular-groupset" title="����Ȩ��"/>����Ȩ��</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_usergroup" title="�����û���"/>�����û���</dd>
					</dl>
					<dl>
					<dt>�ٿ�����</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hdapi" title="������ҳ"/>������ҳ</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hdapi-set" title="������"/>������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_share-set" title="������֪��"/>������֪��</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hdapi-down" title="���ش���"/>���ش���</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hdapi-info" title="�޸���������"/>�޸���������</dd>
					</dl>
				</div>
				<div class="l">
					<dl>
						<dt>���ݿ�</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_db-backup" title="���ݿⱸ��"/>���ݿⱸ��</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_db-tablelist" title="���ݿ��Ż�"/>���ݿ��Ż�</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_db-sqlwindow" title="SQL��ѯ����"/>SQL��ѯ����</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_db-storage" title="���ݴ洢����"/>���ݴ洢����</dd>
					</dl>
					<dl>
						<dt>վ��ͳ��</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-stand" title="�����ſ�"/>�����ſ�</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-cat_toplist" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-doc_toplist" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-edit_toplist" title="�༭���а�"/>�༭���а�</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-credit_toplist" title="��������"/>��������</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-admin_team" title="�����Ŷ�"/>�����Ŷ�</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_log" title="��̨������¼"/>��̨������¼</dd>
					</dl>
				</div>
			</li>
			<li><input name="menusubmit" type="button" value="�� ��"  class="inp_btn m-r10" onclick="shortcut.setShortcut();"/><a href="javascript:void(0);" onclick="shortcut.displayShortcut('none');" class="m-lr10">ȡ ��</a></li>
		</ul>
		</form>
	</div>
	<span class="biaoshi"></span>
</div>

<div class="tc_bg  tc_map" id="mapbox" style="display:none">
	<div class="tc">
		<h2><a href="javascript:void(0);" class="close" onclick="$('#mapbox').css('display','none')"></a>�������ĵ���</h2>
		<ul class="col-ul" id="maplist">
		</ul>
	</div>
</div>
<p class="copy">Copyright 2005-2010 HDWiki V<?php echo HDWIKI_VERSION?> release <?php echo HDWIKI_RELEASE?> All rights reserved</p>
<?php if($env) { ?><script src="<?php echo $env?>"></script><?php } ?>
<?php if($diary) { ?><script src="<?php echo $diary?>"></script><?php } ?>
<script type="text/javascript" >
function loadScript(url, callback){
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.onload = script.onreadystatechange = function(){
		if(!this.readyState || this.readyState=='loaded' || this.readyState=='complete'){
			callback();
		}
	}
	script.src = url;
	document.body.appendChild(script);
}
</script>
<script type="text/javascript" src="js/api.js"></script>
<div id="step1" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>��һ�����޸�վ�������Ϣ</dt>
		  <dd>վ�������Ϣ��������վ����������վ��ı�ʶ�����������������Ҫ���ݡ�</dd>
		</dl>
		<dl class="col-dl">
		  <dt><b>�������裺</b></dt>
		  <dd> 1��ȫ��--&gt;վ������,�޸�վ�����ƣ���վurl��վ�㹫��ȡ�</dd>
		</dl>
		<p class="a-r"><font color="green">1</font>/5 	<input name="Button1" type="button" value="��һ��" class="inp_btn2" onclick="javascript:apply(2);" /><span class='checkboxspan'></span>�´ε�¼ʱ��ʾ</p>
</div>
<div id="step2" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>�ڶ�������ͨ�ٿ����ˣ����ش���</dt>
		  <dd>�ٿ�������Hdwiki���е������齨��ʽ������վ�������ٴ�Լ���վ�㡣</dd>
		</dl>
		<dl class="col-dl">
		  <dt>�������裺</dt>
		  <dd>1�����ݹ���--&gt; �������������������վ�ķ���</dd>
		  <dd>2���ٿ�����-��&gt; ���ش������ѻ�ȡ�ķ����������������վ����Ӧ�ķ��ࡣ</dd>
		  <dd>3�����ݹ���--&gt; ���������ֱ��������Ŵ��������ʴ������Ƽ�����</dd>
		</dl>
		<p class="a-r"><font color="green">2</font>/5   <input name="Button1" type="button" value="��һ��" class="inp_btn2" onclick="javascript:apply(3);" /><span class='checkboxspan'></span>�´ε�¼ʱ��ʾ</p>
</div>

<div id="step3" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>���������Ż�վ�㸺�أ������û�����</dt>
		  <dd>վ��ĸ��أ�ͨ������ָһ��վ��ͬһʱ�䣬���ܳ��ܵ�����������ͨ������£��߷������Ʊش����ͷ���Ч�����Ӷ������û����顣ͨ���������ã����ڲ��Ķ�Ӳ������������£��Ż�վ�㸺�أ������û����飬������Ӫ���ա�</dd>
		</dl>
		<dl class="col-dl">
		   <dt>�������裺</dt>
		  <dd>1���ٿ�����--&gt; �����������ÿ����������������������飬���Ϳռ������ѹ��</dd>
		  <dd>2�����ݿ����--&gt; ���ݿ�洢���ã�������ʷ�汾���洢Ϊ�ı���ʽ���������ݿ�����</dd>
		</dl>
		<p class="a-r"><font color="green">3</font>/5	<input name="Button1" type="button" value="��һ��" class="inp_btn2"  onclick="javascript:apply(4);" /><span class='checkboxspan'></span>�´ε�¼ʱ��ʾ</p>
</div>

<div id="step4" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>���Ĳ�:�������;������ݣ���������֪�磬�����վ�ط���</dt>
		  <dd>Hdwiki5.0�����˶��صķ���ģʽ�������������û������ɷ������ģ����ˣ�����΢���������ٿƻ�Ϊhdwikiվ���������˶��еķ���ƽ̨--��֪�硣</dd>
		  <dd>��֪�磬�ǻ����ٿƵ�һ���Է���֪ʶ��ά���Ļ���Χ��Ũ��һ��ƽ̨������������ݵ���֪�磬�ܰ���վ�����ٽ����Լ�վ��ķ�˿Ⱥ������û��ȣ��������õģ���ʵ���û�Ⱥ�塣</dd>
		</dl>
		<dl class="col-dl">
		  <dt>�������裺</dt>
		  <dd>1���ٿ�����--&gt; ������֪�稤�ֶ���������ѡ�񾫲����ݣ�������֪�磬���ٽ����Լ�վ��ķ�˿Ⱥ��������ʵ�û���</dd>
		  <dd>2�����뿪ͨ�Զ�����ʽ���� <a href="http://kaiyuan.hudong.com/bbs/" target="_blank">����˽�����&gt;&gt;</a> </dd>
		</dl>
		<p class="a-r"><font color="green">4</font>/5 	<input name="Button1" type="button" value="��һ��" class="inp_btn2" onclick="javascript:apply(5);" /><span class='checkboxspan'></span>�´ε�¼ʱ��ʾ</p>
</div>

<div id="step5" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>���岽��ʹ���Զ���˵��������Լ����õĲ˵�</dt>
		  <dd>Hdwiki5.0������ֻ����˵����ܣ�վ���������ã��Լ�����ʹ�õĹ��ܲ˵����������5��������̰��Ŷ��</dd>
		</dl>
		<dl class="col-dl">
		  <dt>�������裺</dt>
		  <dd>1���ں�̨�����Ͻǣ�logo�£��ҵ��Զ���˵���</dd>
		  <dd>2������ұߵ����ã��������ÿ�ݹ��ܲ㡣</dd>
		  <dd>3����ѡ�Լ����õĹ��ܡ�</dd>
		  <dd>4��������ð�ť��ȷ���޸ġ�</dd>
		</dl>
		<p class="a-r"><font color="green">5</font>/5	<input name="Button1" type="button" value="��һ��" class="inp_btn2" onclick="javascript:apply_six();" /><span class='checkboxspan'></span>�´ε�¼ʱ��ʾ</p>
</div>

<div id="step6" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>���վ�����ѣ�������Hdwiki��ʽ�棬��̨�˵��������µ���</dt>
		  <dd>1���ٿ���������Ϊһ���˵���������������</dd>
		  <dd>2��ͼƬ�ٿƣ���Ʒ�̵꣬ľ���ɱ������һ���˵�ģ���¡�</dd>
		  <dd>3���������ӣ�������������ȫ���¡�</dd>
		  <dd>4����ȫ�����������Զ�������</dd>
		</dl>
		<dl class="col-dl">
		  <dd>��������������뵽վ���ͼ<a href="#" target="_self" onclick="getMenu('map',this)" id="mapmenu">MAP</a>�в鿴</dd>
		</dl>
		<p class="a-r">	<input name="Button1" type="button" value="�ر�" class="inp_btn2" onclick="javascript:mycancel_six();"/><span class='checkboxspan'></span>�´ε�¼ʱ��ʾ</p>
</div>
<span class="n_guide_sign" onclick="javascript:apply(1);"></span>
</body>
</html>

<script type="text/javascript">
var set_show=0;
<?php if($setting['login_show']==1) { ?>
	$.dialog.open('gift', '��ӭʹ������ָ��', "selector:#step1");
	$('.checkboxspan').html('<input name="Checkbox1" id="Checkbox1" class="ischeck" type="checkbox" checked="checked" onclick="login_set(this.checked);"/>');
<?php } else { ?>
	$('.checkboxspan').html('<input name="Checkbox1" id="Checkbox1" class="ischeck" type="checkbox" onclick="login_set(this.checked);"/>');
<?php } ?>
function apply(num){
	$.dialog.open('gift', '��ӭʹ������ָ��', "selector:#step"+num);
}
function apply_six(){
	mycancel();
	$.dialog.open('gift_six', 'Hdwiki��̨���ܲ˵�����', "selector:#step6");
}
function mycancel(){
	$.dialog.close('gift');
}
function mycancel_six(){
	$.dialog.close('gift_six');
}
function login_set(setval){
	if(setval)
		set_show=1;
	else
		set_show=2;
	$.post("index.php?admin_main-loginshow",  {isshow:set_show},function(data){
		switch (data) {
			case '1' :
				$('.checkboxspan').html('<input name="Checkbox1" id="Checkbox1" class="ischeck" type="checkbox" checked="checked" onclick="login_set(this.checked);"/>');
				break;
			case '2' :
				$('.checkboxspan').html('<input name="Checkbox1" id="Checkbox1" class="ischeck" type="checkbox"  onclick="login_set(this.checked);"/>');
				break;
			case '3' :
				alert('����ʧ�ܣ�');
				break;
		}
	});
}
</script>