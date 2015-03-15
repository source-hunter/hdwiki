<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=<?php echo WIKI_CHARSET?>" http-equiv="Content-Type" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="style/default/admin/admin.css" type="text/css" rel="stylesheet" media="all"/>
<title>HDWIKI 后台-管理中心</title>
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
	var topMenu=['首页','全局','用户管理','内容管理','模板/插件','数据库管理','百科联盟','模块','站内统计','Map'];
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
				alert("最多设置5个快捷功能");return false;
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
						alert('快捷方式保存未成功');
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
				alert("请输入要搜索的菜单名称关键字，如 缓存设置 等。");
				input.focus();
				return false;
			}else if(/[`~!@#$%^&*<>'"\/\\]/i.test(keywords)){
				alert("菜单名关键字不要包含特殊字符，如~!@#$%^&*<>'\"\\\/等。");
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
				<a href="index.php" target="_blank">前台首页</a>|<a href="http://kaiyuan.hudong.com/bbs/" target="_blank" class="red">帮助</a>|<a onclick="javascript:apply(1);" href="javascript:viod(0);">我是新手</a>|<a href="http://kaiyuan.hudong.com/kaiyuanhome/c/contact_us.htm" target="main">联系我们</a>|<a href="http://kaiyuan.hudong.com/bbs" target="_blank">我要提问</a>[<a href="index.php?admin_main-logout">退出管理中心</a>]
				</p>
				<form name="search" class="seach m-r10" action="index.php?admin_actions" target="main" method="post">
				<p class="search">
				<input name="keywords" type="text" class="inp_txt w-140" value="搜索后台功能菜单" onfocus="if (value =='搜索后台功能菜单'){value =''}" onblur="if (value ==''){value='搜索后台功能菜单'}"/> <input type="submit" value="搜菜单" class="inp_btn3"/>
				</p>
				</form>
				<ul id="nav">
				<li onclick="getMenu('index',this)" id="indexmenu" ><a href="javascript:void(0);"><span>首页</span></a></li>
				<li onclick="getMenu('global',this)" id="globalmenu"><a href="javascript:void(0);"><span>全 局</span></a></li>
				<li onclick="getMenu('user',this)" id="usermenu"><a href="javascript:void(0);"><span>用户管理</span></a>|</li>
				<li onclick="getMenu('content',this)"  id="contentmenu"><a href="javascript:void(0);"><span>内容管理</span></a>|</li>
				<li onclick="getMenu('plug',this)" id="plugmenu"><a href="javascript:void(0);"><span>模板/插件</span></a>|</li>
				<li onclick="getMenu('database',this)" id="databasemenu"><a href="javascript:void(0);"><span>数据库管理</span></a>|</li>
				<li onclick="getMenu('unions',this)" id="toolsmenu"><a href="javascript:void(0);"><span>百科联盟</span></a>|</li>
				<li onclick="getMenu('model',this)" id="toolsmenu"><a href="javascript:void(0);"><span>模块</span></a>|</li>
				<li onclick="getMenu('statistics',this)" id="statisticsmenu"><a href="javascript:void(0);"><span>站内统计</span></a></li>
				<li onclick="getMenu('map',this)" id="mapmenu"><a href="javascript:void(0);"><span>Map</span></a></li>
				</ul>
			</div>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<div class="left">
				<a href="index.php?admin_main-mainframe" target="main" class="logo"><img src="style/default/admin/logo.gif" alt="HDWIKI管理后台" /></a>
				<div class="sidebar">
					<div class="menu-box">
						<h2><span class="r m-r8"><span onclick="shortcut.displayShortcut('display');">设置</span></span>自定义菜单</h2>
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
						<li><a href="index.php?admin_main-mainframe" target="main" class="on">首页信息</a></li>
						</ul>
					</div>

					<div id='global'>
						<ul>
						<li><a href="index.php?admin_setting-base" target="main"  class="on">站点设置</a></li>
						<li><a href="index.php?admin_channel" target="main">基本设置</a></li>
						<li><a href="index.php?admin_setting-sec" target="main">扩展设置</a></li>
						<li><a href="index.php?admin_setting-index" target="main">内容设置</a></li>
						<li><a href="index.php?admin_friendlink" target="main">友情链接</a></li>
						<li><a href="index.php?admin_adv" target="main">广告管理</a></li>
						<li><a href="index.php?admin_sitemap" target="main">Sitemap</a></li>
						<li><a href="index.php?admin_upgrade" target="main">自动升级</a></li>
						</ul>
					</div>

					<div id='user'>
						<ul>
						<li><a href="index.php?admin_setting-baseregister" target="main" >注册设置</a></li>
						<li><a href="index.php?admin_user" target="main"  class="on">管理用户</a></li>
						<li><a href="index.php?admin_regular-groupset" target="main">管理权限</a></li>
						<li><a href="index.php?admin_usergroup" target="main">管理用户组</a></li>
						</ul>
					</div>

					<div id='content'>
						<ul>
							<li><a href="index.php?admin_category-list" target="main">分类管理</a></li>
							<li><a href="index.php?admin_doc" target="main"  class="on">词条管理</a></li>
							<li><a href="index.php?admin_attachment" target="main">附件管理</a></li>
							<li><a href="index.php?admin_comment" target="main">评论管理</a></li>
							<li><a href="index.php?admin_tag-hottag" target="main">热门标签</a></li>
							<li><a href="index.php?admin_hotsearch" target="main">热门搜索</a></li>
							<li><a href="index.php?admin_word" target="main">词语过滤</a></li>
							<li><a href="index.php?admin_datacall" target="main">数据调用</a></li>
							<li><a href="index.php?admin_recycle" target="main">回收站</a></li>
						</ul>
					</div>

					<div id='plug'>
						<ul>
						<li><a href="index.php?admin_theme" target="main" class="on">模板管理</a></li>
						<li><a href="index.php?admin_plugin" target="main">插件管理</a></li>
						<li><a href="index.php?admin_language" target="main">网站语言编辑</a></li>
						</ul>
					</div>

					<div id='database'>
						<ul>
						<li><a href="index.php?admin_db-backup" target="main" class="on">数据库备份</a></li>
						<li><a href="index.php?admin_db-tablelist" target="main">数据库优化</a></li>
						<li><a href="index.php?admin_db-sqlwindow" target="main">SQL查询窗口</a></li>
						<li><a href="index.php?admin_db-storage" target="main">数据存储设置</a></li>
						</ul>
					</div>

					<div id='unions'>
						<ul>
						<li><a href="index.php?admin_hdapi" target="main" class="on">联盟首页</a></li>
						<li><a href="index.php?admin_hdapi-set" target="main">云搜索</a></li>
						<li><a href="index.php?admin_share-set" target="main">分享到新知社</a></li>
						<li><a href="index.php?admin_hdapi-down" target="main">下载词条</a></li>
						<li><a href="index.php?admin_hdapi-info" target="main">修改联盟资料</a></li>
						</ul>
					</div>

					<div id='model'>
						<ul>
						<li><a href="index.php?admin_image" target="main" class="on">图片百科</a></li>
						<li><a href="index.php?admin_gift" target="main">礼品商店</a></li>
						<li><a href="index.php?admin_safe" target="main">木马扫描</a></li>
						<li><a href="index.php?admin_filecheck" target="main">文件检测</a></li>
						<li><a href="index.php?admin_dbcheck" target="main">数据库检测</a></li>
						<li><?php if($setting['FTP_ENABLE']==1) { ?><a href="index.php?admin_fileftpmanage" target="main" class="on">文件管理</a><?php } else { ?><a href="index.php?admin_filemanager" target="main" class="on">文件管理</a> <?php } ?></li>
						<li><a href="index.php?admin_ftpsetting" target="main">FTP设置</a></li>
						</ul>
					</div>

					<div id='statistics'>
						<ul>
						<li><a href="index.php?admin_statistics-stand" target="main" class="on">基本概况</a></li>
						<li><a href="index.php?admin_statistics-cat_toplist" target="main">分类排行</a></li>
						<li><a href="index.php?admin_statistics-doc_toplist" target="main">词条排行</a></li>
						<li><a href="index.php?admin_statistics-edit_toplist" target="main">编辑排行榜</a></li>
						<li><a href="index.php?admin_statistics-credit_toplist" target="main">经验排行</a></li>
						<li><a href="index.php?admin_statistics-admin_team" target="main">管理团队</a></li>
						<li><a href="index.php?admin_log" target="main">后台操作记录</a></li>
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
		<h2><a href="javascript:void(0);" onclick="shortcut.displayShortcut('none');" class="close"></a>设置快捷功能</h2>
		<ul class="col-ul ul_li_sp">
			<li><span>显示状态</span><input name="shortcutstate" type="checkbox" id="shortcutstate" <?php if($setting['shortcutstate']) { ?> checked="checked" <?php } ?>/>折叠显示快捷菜单</li>
			<li><span>选择功能</span><label class="s-tips">最多设置5个快捷功能</label>	<a href="javascript:void(0);" onclick="shortcut.clearShortcut();">清空选项</a></li>
			<li id="menubox">
				<div class="l">
					<dl>
						<dt>基本设置</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_channel" title="频道管理"/>频道管理</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-cache" title="缓存设置"/>缓存设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-seo" title="SEO设置"/>SEO设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-code" title="验证码"/>验证码</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-time" title="时间设置"/>时间设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-cookie" title="COOKIE设置"/>COOKIE设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-credit" title="经验金币设置"/>经验金币设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-logo" title="LOGO设置"/>LOGO设置</dd>
					</dl>
					<dl>
						<dt>友情链接</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_friendlink" title="友情链接列表"/>友情链接列表</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_friendlink-add" title="添加友情链接"/>添加友情链接</dd>
					</dl>
					<dl>
						<dt>广告管理</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_adv-default" title="管理广告"/>管理广告</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_adv-config" title="设置广告"/>设置广告</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_adv-add" title="添加广告"/>添加广告</dd>
					</dl>
				</div>
				<div class="l">
					<dl>
						<dt>内容设置</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-index" title="首页设置"/>首页设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-listdisplay" title="列表设置"/>列表设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-watermark" title="图片设置"/>图片设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-docset" title="词条设置"/>词条设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-search" title="搜索设置"/>搜索设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-attachment" title="附件设置"/>附件设置</dd>
					</dl>
					<dl>
						<dt>扩展设置</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-sec" title="防灌水设置"/>防灌水设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-anticopy" title="防采集设置"/>防采集设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-mail" title="邮件设置"/>邮件设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-noticemail" title="邮件提醒设置"/>邮件提醒设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_banned" title="IP禁止"/>IP禁止</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-passport" title="通行证设置"/>通行证设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-ucenter" title="UCenter设置"/>UCenter设置</dd>
                        <dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-ldap" title="UCenter设置"/>LDAP设置</dd>
					</dl>
				</div>
				<div class="l">
					<dl>
						<dt>内容管理</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_category-list" title="分类管理"/>分类管理</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_doc" title="词条管理"/>词条管理</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_attachment" title="附件管理"/>附件管理</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_comment" title="评论管理"/>评论管理</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_tag-hottag" title="热门标签"/>热门标签</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hotsearch" title="热门搜索"/>热门搜索</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_word" title="词语过滤"/>词语过滤</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_datacall" title="数据调用"/>数据调用</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_recycle" title="回收站"/>回收站</dd>
					</dl>
					<dl>
						<dt>模块</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_image" title="图片百科"/>图片百科</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_gift" title="礼品商店"/>礼品商店</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_safe" title="木马扫描"/>木马扫描</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_filecheck" title="文件检测"/>文件检测</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_dbcheck" title="数据库检测"/>数据库检测</dd>
					</dl>
				</div>
				<div class="l">
					<dl>
						<dt>插件|模板</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_theme" title="模板管理"/>模板管理</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_plugin" title="插件管理"/>插件管理</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_language" title="网站语言编辑"/>网站语言编辑</dd>
					</dl>
					<dl>
						<dt>用户管理</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_setting-baseregister" title="注册设置"/>注册设置</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_user" title="管理用户"/>管理用户</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_regular-groupset" title="管理权限"/>管理权限</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_usergroup" title="管理用户组"/>管理用户组</dd>
					</dl>
					<dl>
					<dt>百科联盟</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hdapi" title="联盟首页"/>联盟首页</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hdapi-set" title="云搜索"/>云搜索</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_share-set" title="分享到新知社"/>分享到新知社</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hdapi-down" title="下载词条"/>下载词条</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_hdapi-info" title="修改联盟资料"/>修改联盟资料</dd>
					</dl>
				</div>
				<div class="l">
					<dl>
						<dt>数据库</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_db-backup" title="数据库备份"/>数据库备份</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_db-tablelist" title="数据库优化"/>数据库优化</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_db-sqlwindow" title="SQL查询窗口"/>SQL查询窗口</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_db-storage" title="数据存储设置"/>数据存储设置</dd>
					</dl>
					<dl>
						<dt>站内统计</dt>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-stand" title="基本概况"/>基本概况</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-cat_toplist" title="分类排行"/>分类排行</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-doc_toplist" title="词条排行"/>词条排行</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-edit_toplist" title="编辑排行榜"/>编辑排行榜</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-credit_toplist" title="经验排行"/>经验排行</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_statistics-admin_team" title="管理团队"/>管理团队</dd>
						<dd><input name="menu[]" type="checkbox" value="index.php?admin_log" title="后台操作记录"/>后台操作记录</dd>
					</dl>
				</div>
			</li>
			<li><input name="menusubmit" type="button" value="设 置"  class="inp_btn m-r10" onclick="shortcut.setShortcut();"/><a href="javascript:void(0);" onclick="shortcut.displayShortcut('none');" class="m-lr10">取 消</a></li>
		</ul>
		</form>
	</div>
	<span class="biaoshi"></span>
</div>

<div class="tc_bg  tc_map" id="mapbox" style="display:none">
	<div class="tc">
		<h2><a href="javascript:void(0);" class="close" onclick="$('#mapbox').css('display','none')"></a>管理中心导航</h2>
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
		  <dt>第一步：修改站点基本信息</dt>
		  <dd>站点基本信息，是你网站区别于其他站点的标识，搜索引擎收入的重要依据。</dd>
		</dl>
		<dl class="col-dl">
		  <dt><b>操作步骤：</b></dt>
		  <dd> 1：全局--&gt;站点设置,修改站点名称，网站url，站点公告等。</dd>
		</dl>
		<p class="a-r"><font color="green">1</font>/5 	<input name="Button1" type="button" value="下一步" class="inp_btn2" onclick="javascript:apply(2);" /><span class='checkboxspan'></span>下次登录时显示</p>
</div>
<div id="step2" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>第二步：开通百科联盟，下载词条</dt>
		  <dd>百科联盟是Hdwiki独有的内容组建形式，帮助站长，快速搭建自己的站点。</dd>
		</dl>
		<dl class="col-dl">
		  <dt>操作步骤：</dt>
		  <dd>1：内容管理--&gt; 分类词条管理；创建您网站的分类</dd>
		  <dd>2：百科联盟-—&gt; 下载词条，把获取的分类词条，导入您网站的相应的分类。</dd>
		  <dd>3：内容管理--&gt; 词条管理，分别设置热门词条，精彩词条，推荐词条</dd>
		</dl>
		<p class="a-r"><font color="green">2</font>/5   <input name="Button1" type="button" value="下一步" class="inp_btn2" onclick="javascript:apply(3);" /><span class='checkboxspan'></span>下次登录时显示</p>
</div>

<div id="step3" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>第三步：优化站点负载，提升用户体验</dt>
		  <dd>站点的负载，通俗上是指一个站点同一时间，所能承受的最大访问量。通常情况下，高访问量势必带来低访问效果，从而降低用户体验。通过以下设置，可在不改动硬件环境的情况下，优化站点负载，提升用户体验，降低运营风险。</dd>
		</dl>
		<dl class="col-dl">
		   <dt>操作步骤：</dt>
		  <dd>1：百科联盟--&gt; 云搜索；设置开启云搜索，提升搜索体验，降低空间的搜索压力</dd>
		  <dd>2：数据库管理--&gt; 数据库存储设置；设置历史版本，存储为文本格式。减少数据库消耗</dd>
		</dl>
		<p class="a-r"><font color="green">3</font>/5	<input name="Button1" type="button" value="下一步" class="inp_btn2"  onclick="javascript:apply(4);" /><span class='checkboxspan'></span>下次登录时显示</p>
</div>

<div id="step4" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>第四步:定期推送精彩内容，到互动新知社，提高网站回访率</dt>
		  <dd>Hdwiki5.0开启了独特的分享模式，不仅可以让用户，自由分享到开心，人人，新浪微薄。互动百科还为hdwiki站长度身定做了独有的分享平台--新知社。</dd>
		  <dd>新知社，是互动百科的一个以分享知识，维基文化氛围较浓的一个平台；分享词条内容到新知社，能帮助站长快速建立自己站点的粉丝群，提高用户黏度，建立良好的，忠实的用户群体。</dd>
		</dl>
		<dl class="col-dl">
		  <dt>操作步骤：</dt>
		  <dd>1：百科联盟--&gt; 分享到新知社à手动分享；定期选择精彩内容，分享到新知社，快速建立自己站点的粉丝群，培养忠实用户。</dd>
		  <dd>2：申请开通自动触发式分享， <a href="http://kaiyuan.hudong.com/bbs/" target="_blank">点击了解详情&gt;&gt;</a> </dd>
		</dl>
		<p class="a-r"><font color="green">4</font>/5 	<input name="Button1" type="button" value="下一步" class="inp_btn2" onclick="javascript:apply(5);" /><span class='checkboxspan'></span>下次登录时显示</p>
</div>

<div id="step5" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>第五步：使用自定义菜单，设置自己常用的菜单</dt>
		  <dd>Hdwiki5.0增加了只定义菜单功能，站长可以设置，自己经常使用的功能菜单。最多设置5个，不能贪多哦。</dd>
		</dl>
		<dl class="col-dl">
		  <dt>操作步骤：</dt>
		  <dd>1：在后台的右上角，logo下，找到自定义菜单。</dd>
		  <dd>2：点击右边的设置，弹出设置快捷功能层。</dd>
		  <dd>3：勾选自己常用的功能。</dd>
		  <dd>4：点击设置按钮，确认修改。</dd>
		</dl>
		<p class="a-r"><font color="green">5</font>/5	<input name="Button1" type="button" value="下一步" class="inp_btn2" onclick="javascript:apply_six();" /><span class='checkboxspan'></span>下次登录时显示</p>
</div>

<div id="step6" class="tc_bg new_guide" style="display:none;">
		<dl class="new_guide_c col-dl">
		  <dt>广大站长朋友，我们在Hdwiki正式版，后台菜单做了如下调整</dt>
		  <dd>1：百科联盟提升为一级菜单，调整到顶部。</dd>
		  <dd>2：图片百科，礼品商店，木马查杀调整到一级菜单模块下。</dd>
		  <dd>3：友情链接，广告管理，调整到全局下。</dd>
		  <dd>4：在全局里增加了自动升级。</dd>
		</dl>
		<dl class="col-dl">
		  <dd>更多详情调整，请到站点地图<a href="#" target="_self" onclick="getMenu('map',this)" id="mapmenu">MAP</a>中查看</dd>
		</dl>
		<p class="a-r">	<input name="Button1" type="button" value="关闭" class="inp_btn2" onclick="javascript:mycancel_six();"/><span class='checkboxspan'></span>下次登录时显示</p>
</div>
<span class="n_guide_sign" onclick="javascript:apply(1);"></span>
</body>
</html>

<script type="text/javascript">
var set_show=0;
<?php if($setting['login_show']==1) { ?>
	$.dialog.open('gift', '欢迎使用新手指南', "selector:#step1");
	$('.checkboxspan').html('<input name="Checkbox1" id="Checkbox1" class="ischeck" type="checkbox" checked="checked" onclick="login_set(this.checked);"/>');
<?php } else { ?>
	$('.checkboxspan').html('<input name="Checkbox1" id="Checkbox1" class="ischeck" type="checkbox" onclick="login_set(this.checked);"/>');
<?php } ?>
function apply(num){
	$.dialog.open('gift', '欢迎使用新手指南', "selector:#step"+num);
}
function apply_six(){
	mycancel();
	$.dialog.open('gift_six', 'Hdwiki后台功能菜单调整', "selector:#step6");
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
				alert('操作失败！');
				break;
		}
	});
}
</script>