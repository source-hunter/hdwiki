<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script>
	function checknull(path){
		if(confirm('ȷ�����˷������ΪĬ�Ϸ����')==false){
			return false;
		}else{
			window.location='index.php?admin_theme-setdefaultstyle-'+path.replace(/\./g,'*');
		}
	}
	function add(pathname){
		if(confirm('ȷ����Ӵ�ģ��?')==false){
			return false;
		}else{
			window.location='index.php?admin_theme-add-'+pathname.replace(/\./g,'*');
		}
	}
</script>
    
<p class="map">���/ģ�壺ģ��</p>
<p class="sec_nav">ģ�壺
    <a href="index.php?admin_theme" class="on"><span>����Ĭ�Ϸ��</span></a>
    <a href="index.php?admin_theme-create" ><span>�������</span></a>
    <a href="index.php?admin_theme-list" ><span>���߰�װ</span></a>
    <a href="index.php?admin_theme-edit" ><span>ģ��༭</span></a>
</p>

<?php if($defaultstyle['name']!='') { ?>
<h3 class="col-h3">����Ĭ�Ϸ��</h3>
<div class="tem_style link_blue">
    <?php if($defaultstyle['img']!='1') { ?>
    <img alt="<?php echo $defaultstyle['name']?>" src="style/default/screenshot.jpg"/>
    <?php } else { ?>
    <img alt="<?php echo $defaultstyle['name']?>" src="style/<?php echo $defaultstyle['path']?>/screenshot.jpg"/>
    <?php } ?>
    <ul>
    <li><a href="<?php echo $defaultstyle['weburl']?>" target="_blank"><?php echo $defaultstyle['name']?></a> version:<?php echo $defaultstyle['version']?>  BY <a href="<?php echo $defaultstyle['authorurl']?>" target="_blank"> <?php echo $defaultstyle['author']?></a></li>
    <li>����:<?php echo $defaultstyle['desc']?></li>
    <li>��ǩ:
    <?php foreach((array)$defaultstyle['tag'] as $defaulttag) {?>
        <a href="<?php echo $appurl?>/template.php?action=search&tag=<?php echo urlencode(string::hiconv($defaulttag,'utf-8','gbk'))?>" target="_blank"><?php echo $defaulttag?></a>
    <?php } ?>
    <?php foreach((array)$defaultstyle['charset'] as $defaultcharset) {?>
        <a href="<?php echo $appurl?>/template.php?action=search&tag=<?php echo urlencode(string::hiconv($defaultcharset,'utf-8','gbk'))?>" target="_blank"><?php echo $defaultcharset?></a>
    <?php } ?>
    </li>
    <li><input class="inp_btn2" name="Button1" type="button" onclick="javascript:window.location='index.php?admin_theme-editxml-<?php echo $defaultstyle['path']?>-share'" value="����" /><input type="button" onclick="javascript:window.location='index.php?admin_theme-edit-<?php echo $defaultstyle['path']?>';" value="�༭ģ��" class="inp_btn2"/></li>
	</ul>
</div>
<?php } ?>


        
<?php if($toaddlist!=null) { ?>
<h3 class="col-h4 m-t10">�����ģ����</h3>
<div class="tem_style link_blue">
    <ul>
        <li>
    <?php foreach((array)$toaddlist as $style) {?>
        <a href="#" onclick="return add('<?php echo $style['ename']?>')"><?php echo $style['zname']?>(<?php echo $style['ename']?>)</a>&nbsp;&nbsp;&nbsp;&nbsp;
    <?php } ?>
        </li>
    </ul>
</div>
<?php } ?>


<?php if($stylelist!=null) { ?>
<h3 class="col-h4 m-t10">���õ�ģ����</h3>

<?php foreach((array)$stylelist as $style) {?>
<div class="tem_style link_blue">	  		
    <?php if($style['img']!=1) { ?>
    <img alt="<?php echo $style['name']?>" class="plugin_img" src="style/default/screenshot.jpg"/>
    <?php } else { ?>
    <img alt="<?php echo $style['name']?>" class="plugin_img" src="style/<?php echo $style['path']?>/screenshot.jpg"/>
    <?php } ?>
    
	<ul>
	<li><a href="<?php echo $style['weburl']?>" target="_blank" ><?php echo $style['name']?></a>�汾 <?php echo $style['version']?>  BY <a href="<?php echo $style['authorurl']?>" target="_blank"><?php echo $style['author']?></a></li>
	<li>����:<?php echo $style['desc']?></li>
    <li>���ð汾:<?php echo $style['hdversion']?></li>
	<li>��ǩ:<?php foreach((array)$style['tag'] as $tag) {?><a href="<?php echo $appurl?>/template.php?action=search&tag=<?php echo urlencode(string::hiconv($tag,'utf-8','gbk'))?>" target="_blank"><?php echo $tag?></a> <?php } ?><?php foreach((array)$style['charset'] as $charset) {?><a href="<?php echo $appurl?>/template.php?action=search&tag=<?php echo urlencode(string::hiconv($charset,'utf-8','gbk'))?>" target="_blank"><?php echo $charset?></a> <?php } ?></li>
    <li>
    <input type="button" class="inp_btn2" value="����" onclick="window.location='index.php?admin_theme-editxml-<?php echo $style['path']?>-share';" class="btn_plug"/>
    <input type="button" class="inp_btn2" value="�༭" onclick="window.location='index.php?admin_theme-edit-<?php echo $style['path']?>'" class="btn_plug"/>
    <input type="button" class="inp_btn2" value="Ĭ��" onclick="checknull('<?php echo $style['path']?>');" class="btn_plug"/>
    <input type="button" class="inp_btn2" value="ж��" onclick="window.location='index.php?admin_theme-removestyle-<?php echo $style['path']?>'" class="btn_plug"/>
	</li>
	</ul>
</div>
<?php } ?>
    <div id="fenye" class="link_gray">
        <?php echo $departstr?>
    </div>
<?php } ?>

<dl class="col-dl">
<dt >��ȡ����ģ�壺</dt>
<dd>��������HDWikiģ��ҳ���ҵ�����ģ�塣Ҫ��װһ����ģ�棬����Ҫ��FTP�����Fillza��FlashFXP�ȵȣ�������.htmģ���ļ�Ŀ¼�ϴ����ٿ���վĿ¼�е� view �ļ����������ͼƬ��.css��ʽ����ļ�Ŀ¼�ŵ� style �ļ����У���blockĿ¼�µ��ļ�Ŀ¼�ŵ� block �ļ����У�Ȼ��ˢ�±�ҳ�Ϳ��������￴�����ϴ���ģ�������ˡ�</dd>
</dl>
</div>
      
        
<?php include $this->gettpl('admin_footer');?>