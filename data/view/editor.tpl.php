<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo WIKI_CHARSET?>"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
<title><?php echo $navtitle?> <?php echo $setting['site_name']?> <?php echo $setting['seo_title']?> - Powered by HDWiki!</title>
<link  rel="stylesheet" type="text/css" href="<?php echo $setting['site_url']?>/style/default/editor.css" media="all" />
<link href="<?php echo WIKI_URL?>/js/jqeditor/skins/skin_base.css" rel="stylesheet" type="text/css" media="all" />
<style>
#tags input{width:50%;margin-bottom:2px;}
#tags ,#doc_verification_code{padding-bottom:10px;}
#tags div ,#doc_verification_code div{text-align:left;padding-left:10px;width:95%;}
</style>
</head>
<body>
<div id="nav-top">
	<ul class="l">
		<li><a href="<?php echo WIKI_URL?>" target="_blank">��ҳ</a></li>
		<?php if(count($channellist)!=0) { ?>
		<?php foreach((array)$channellist as $channel) {?>
		<li><a href="<?php echo $channel['url']?>" target="_blank"><?php echo $channel['name']?></a></li>
		<?php } ?>
		<?php } ?>		
		<li><a href="index.php?category" target="_blank">�ٿƷ���</a></li>
		<li><a href="index.php?list" target="_blank">���а�</a></li>
		
	</ul>
	<ul class="r a-r" id="top_usernav">
		<li><a href="index.php?doc-innerlink-<?php echo urlencode('����')?>">����</a></li>
		<?php if($user['groupid']!='1') { ?>
		<li><a href="index.php?user-logout<?php echo $referer?>" >�˳�</a></li>
		<?php if($adminlogin ) { ?><li><a href="index.php?admin_main">ϵͳ����</a></li><?php } ?>
		<li><a  href="index.php?user-profile">���˹���</a></li>
		<li><a href="index.php?pms" id="header-pms">�ҵ���Ϣ&nbsp;(<span <?php if($newpms[0]) { ?> style="color:#FF0000" <?php } ?>><?php echo $newpms[0]?></span>/<?php echo $newpms[1]?>)</a></li>
		<li>��ӭ�㣬<a href="index.php?user-space-<?php echo $user['uid']?>"><?php echo $user['username']?></a></li>
		<?php } else { ?>
		<li><a href="index.php?user-register" >ע��</a></li>
		<li><a href="index.php?user-login" >��¼</a></li>
		<?php } ?>
	</ul>
</div>

<form name="edit_doc" id="hdwiki_editor" class="jqeditor" method="post" enctype="multipart/form-data" action="<?php if($page_action == 'create') { ?>index.php?doc-create<?php } elseif($page_action == 'edit') { ?>index.php?doc-edit<?php } else { ?>index.php?doc-editsection<?php } ?>" name='editor' onsubmit="return check();">
	<input type="hidden" name='did' id='did' value="<?php echo $doc['did']?>" />
	<input type="hidden" name='section_id' value="<?php echo $doc['section_id']?>" />
	<input type="hidden" name='create_submit' value="1" />
	<input type="hidden" name='title' id='title' value="<?php echo $doc['title']?>" />
	<input type="hidden" name="category" value="<?php echo $doc['cid']?>"/>

<div class="instrument">
	<div style="height:32px;margin:2px 0;">
		<div class="jqe-toolbar"></div>
	</div>
</div>
<div id="editoring" class="hd-box editor_left size">
<strong>�����ڱ༭��</strong><a href="index.php?doc-view-<?php echo $doc['did']?>" target="_blank"><?php echo $doc['title']?></a>
</div>
<div id="text" class="hd-box editor_left">
	<h2>����</h2>
	<textarea id="content" name="content" style="width:98%;height:400px;visibility:hidden;display:none;">
	<?php echo $doc['content']?>
	</textarea>
	<div class="jqe-content"></div>
</div>

<div id="summary" class="hd-box editor_left">
	<h2>ժҪ</h2>
	<div>
		<textarea rows="3" class="size" name='summary'><?php echo html_entity_decode($doc['summary']);?></textarea>
	</div>
</div>
<div id="tags" class="hd-box editor_left">
	<h2>��ǩ</h2>
	<div>
	<input type="text" class="inp_txt" name='tags' value="<?php echo $doc['tag']?>" /> <br /><span class="gray">[�����ǩʹ�÷ֺ�"; ��"��ո����]</span>
	</div>
</div>
<?php if($setting['base_isreferences'] !== '0' ) { ?>
<div id="reference" class="hd-box editor_left">
	<h2>�ο�����</h2>
	<dl class="f8" id="0" style="display:none;">
		<dt><strong name="order">[0]</strong><span></span></dt>
		<dd name="url"></dd>
		<dd name="edit">
			<a href="javascript:;" onclick="docReference.edit(this);return false;">�༭</a> 
			| <a name="remove" href="javascript:;" onclick="docReference.remove(this);return false;">ɾ��</a>
		</dd>
	</dl>
	<?php foreach((array)$referencelist as $i=>$ref) {?>
	<dl class="f8" id="<?php echo $ref['id']?>">
		<dt><strong name="order">[<?php echo ($i+1)?>]</strong><span><?php echo $ref['name']?></span></dt>
		<dd name="url"><?php echo $ref['url']?></dd>
		<dd name="edit">
			<a href="javascript:;" onclick="docReference.edit(this);return false;">�༭</a> 
			| <a name="remove" href="javascript:;" onclick="docReference.remove(this);return false;">ɾ��</a>
		</dd>
	</dl>
	<?php }?>
	
	<dl id="edit_reference" style="display:none">
		<dt class="mar-bottom-10"><strong>����:</strong>
			<input id="editrefrencename" type="text" class="inp_txt" size="60"/>
			<span class="red" id="refrencenamespan"></span>
		</dt>
		<dd class="size black mar-bottom-10"><strong>��ַ:</strong>
			<input id="editrefrenceurl" type="text" class="inp_txt" size="60"/>
			<span class="red" id="refrenceurlspan"></span>
		</dd>
		
		<dd name="verifycode" class="size black mar-bottom-10" style="display:none"><strong>��֤��:</strong>
			<input name="code" id="editrefrencecode" type="text" class="inp_txt" size="10" maxlength="4"/>
			<span name="img" style="display:none"><img id="verifycode2" src="./js/hdeditor/skins/spacer.gif"/> <a href="javascript:docReference.updateVerifyCode();">������ͼƬ</a></span>
			<span name="tip"></span>
			<span class="red" id="refrencecodespan"></span>
		</dd>
		
		<dd>
			<a id="save_1" href="javascript:;" onclick="docReference.save();return false;">����</a>
			<span id="save_0" style="display:none">����</span>
			<a href="javascript:;" onclick="docReference.reset();return false;">����</a>
		</dd>
	</dl>
</div>
<?php } ?>
<?php if($page_action != 'create') { ?>
<div id="reason" class="hd-box editor_left">
	<h2>�޸�ԭ��<span class="red">[����]</span></h2>
	<div>
	<label><input type="checkbox" value="ȫ�ı༭" name="editreason[]"/>ȫ�ı༭</label>
	<label><input type="checkbox" value="��������" name="editreason[]"/>��������</label>
	<label><input type="checkbox" value="��������" name="editreason[]"/>��������</label>
	<label><input type="checkbox" value="����ͼƬ" name="editreason[]"/>����ͼƬ</label>
	<label><input type="checkbox" value="����Ŀ¼" name="editreason[]"/>����Ŀ¼</label>
	<label><input type="checkbox" value="��������" name="editreason[]"/>��������</label>
	<label><input type="checkbox" value="����ҳ��" name="editreason[]"/>����ҳ��</label>
	<br /><br />����ԭ��<br />
	<textarea rows="2" name="editreason[]" id="editreason" class="inp_txt"></textarea>
	</div>
</div>
<?php } ?>

<?php if(($doc_verification_edit_code && ($page_action == 'edit'||$page_action == 'editsection' )) || (!empty($doc_verification_create_code) && $page_action == 'create')) { ?>
<div id="doc_verification_code" class="hd-box editor_left">
	<h2>��֤��<span class="red">[����]</span></h2>
	<div>
	<input name="code" type="text" class="inp_txt" size="10" maxlength="4"/>
	<span name="img" style="display:none"><img id="verifycode" src="./js/hdeditor/skins/spacer.gif"/> <a href="javascript:updateverifycode();">������ͼƬ</a></span>
	<span name="tip"></span>
	</div>
</div>
<?php } ?>

<div class="pushbutton">
	<input name="publishsubmit" class="conserve" type="submit" value="����" />
	<input type="button" value="�˳�" onclick="abort();"/>
</div>
</form>

<div id="editor_right" class="hd-box">
<ul>
<?php if($page_action != 'editsection') { ?>
<li><input name="autosave" type="checkbox" id="autosave" onclick="isAutoSave()" checked="checked"/>�Զ�����</li>
<?php } ?>
<li id='AutoSaveStatus'></li>
<li id="editor_tip"></li>
<li class="help"><a href="http://service.baike.com/article-940349.html" target="_blank">�˽����ٿƱ༭����</a></li>
</ul>
<p><a href="<?php echo WIKI_URL?>" target="_blank"><img src="style/default/logo.gif" width='168px' height='54px'/></a></p>
</div>

<div style="display:none">
<form method="post" id="previewdocform" target="_blank" action="index.php?doc-edit-<?php echo $doc['did']?>">
<textarea name="content"></textarea>
<input name="predoctitle" type="hidden" value="<?php echo $doc['title']?>"/>
</form>
</div>
<style>
.jqe-plugin-HdImage .uploadBoxTop span.last_span {display:none;}
</style>
<script type="text/javascript">
<?php if($filter_external ) { ?>
var g_filterExternal = 1;
<?php } else { ?>
var g_filterExternal = 0;
<?php } ?>
<?php if(($doc_verification_edit_code && ($page_action == 'edit'||$page_action == 'editsection' )) || ($doc_verification_create_code && $page_action == 'create')) { ?>
var g_check_code = "1";
<?php } else { ?>
var g_check_code = "0";
<?php } ?>
var g_page_action = "<?php echo $page_action?>";
var g_logout_editor = "index.php?doc-unseteditlock-<?php echo $doc['did']?>-<?php echo $page_action?>";
var g_docid = "<?php echo $doc['did']?>";
var savetime=<?php echo $savetime?>;
var g_content_md5='';

var g_img_big="<?php echo $g_img_big?>";
var g_img_small="<?php echo $g_img_small?>";
var jqe_static_url = './js/jqeditor';
</script>
<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/jqeditor/jqeditor-hdwiki-1.0.3.min.js"></script>
<script type="text/javascript" src="./js/jqeditor/hdwiki.js"></script>
<script type="text/javascript">
isAutoSave();
</script>
</body>
</html>
