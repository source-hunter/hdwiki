<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script>
	function checknull(path){
		if(confirm('确定将此风格设置为默认风格吗？')==false){
			return false;
		}else{
			window.location='index.php?admin_theme-setdefaultstyle-'+path.replace(/\./g,'*');
		}
	}
	function add(pathname){
		if(confirm('确认添加此模版?')==false){
			return false;
		}else{
			window.location='index.php?admin_theme-add-'+pathname.replace(/\./g,'*');
		}
	}
</script>
    
<p class="map">插件/模板：模板</p>
<p class="sec_nav">模板：
    <a href="index.php?admin_theme" class="on"><span>设置默认风格</span></a>
    <a href="index.php?admin_theme-create" ><span>创建风格</span></a>
    <a href="index.php?admin_theme-list" ><span>在线安装</span></a>
    <a href="index.php?admin_theme-edit" ><span>模板编辑</span></a>
</p>

<?php if($defaultstyle['name']!='') { ?>
<h3 class="col-h3">设置默认风格</h3>
<div class="tem_style link_blue">
    <?php if($defaultstyle['img']!='1') { ?>
    <img alt="<?php echo $defaultstyle['name']?>" src="style/default/screenshot.jpg"/>
    <?php } else { ?>
    <img alt="<?php echo $defaultstyle['name']?>" src="style/<?php echo $defaultstyle['path']?>/screenshot.jpg"/>
    <?php } ?>
    <ul>
    <li><a href="<?php echo $defaultstyle['weburl']?>" target="_blank"><?php echo $defaultstyle['name']?></a> version:<?php echo $defaultstyle['version']?>  BY <a href="<?php echo $defaultstyle['authorurl']?>" target="_blank"> <?php echo $defaultstyle['author']?></a></li>
    <li>描述:<?php echo $defaultstyle['desc']?></li>
    <li>标签:
    <?php foreach((array)$defaultstyle['tag'] as $defaulttag) {?>
        <a href="<?php echo $appurl?>/template.php?action=search&tag=<?php echo urlencode(string::hiconv($defaulttag,'utf-8','gbk'))?>" target="_blank"><?php echo $defaulttag?></a>
    <?php } ?>
    <?php foreach((array)$defaultstyle['charset'] as $defaultcharset) {?>
        <a href="<?php echo $appurl?>/template.php?action=search&tag=<?php echo urlencode(string::hiconv($defaultcharset,'utf-8','gbk'))?>" target="_blank"><?php echo $defaultcharset?></a>
    <?php } ?>
    </li>
    <li><input class="inp_btn2" name="Button1" type="button" onclick="javascript:window.location='index.php?admin_theme-editxml-<?php echo $defaultstyle['path']?>-share'" value="分享" /><input type="button" onclick="javascript:window.location='index.php?admin_theme-edit-<?php echo $defaultstyle['path']?>';" value="编辑模板" class="inp_btn2"/></li>
	</ul>
</div>
<?php } ?>


        
<?php if($toaddlist!=null) { ?>
<h3 class="col-h4 m-t10">待添加模版风格</h3>
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
<h3 class="col-h4 m-t10">可用的模版风格</h3>

<?php foreach((array)$stylelist as $style) {?>
<div class="tem_style link_blue">	  		
    <?php if($style['img']!=1) { ?>
    <img alt="<?php echo $style['name']?>" class="plugin_img" src="style/default/screenshot.jpg"/>
    <?php } else { ?>
    <img alt="<?php echo $style['name']?>" class="plugin_img" src="style/<?php echo $style['path']?>/screenshot.jpg"/>
    <?php } ?>
    
	<ul>
	<li><a href="<?php echo $style['weburl']?>" target="_blank" ><?php echo $style['name']?></a>版本 <?php echo $style['version']?>  BY <a href="<?php echo $style['authorurl']?>" target="_blank"><?php echo $style['author']?></a></li>
	<li>描述:<?php echo $style['desc']?></li>
    <li>适用版本:<?php echo $style['hdversion']?></li>
	<li>标签:<?php foreach((array)$style['tag'] as $tag) {?><a href="<?php echo $appurl?>/template.php?action=search&tag=<?php echo urlencode(string::hiconv($tag,'utf-8','gbk'))?>" target="_blank"><?php echo $tag?></a> <?php } ?><?php foreach((array)$style['charset'] as $charset) {?><a href="<?php echo $appurl?>/template.php?action=search&tag=<?php echo urlencode(string::hiconv($charset,'utf-8','gbk'))?>" target="_blank"><?php echo $charset?></a> <?php } ?></li>
    <li>
    <input type="button" class="inp_btn2" value="分享" onclick="window.location='index.php?admin_theme-editxml-<?php echo $style['path']?>-share';" class="btn_plug"/>
    <input type="button" class="inp_btn2" value="编辑" onclick="window.location='index.php?admin_theme-edit-<?php echo $style['path']?>'" class="btn_plug"/>
    <input type="button" class="inp_btn2" value="默认" onclick="checknull('<?php echo $style['path']?>');" class="btn_plug"/>
    <input type="button" class="inp_btn2" value="卸载" onclick="window.location='index.php?admin_theme-removestyle-<?php echo $style['path']?>'" class="btn_plug"/>
	</li>
	</ul>
</div>
<?php } ?>
    <div id="fenye" class="link_gray">
        <?php echo $departstr?>
    </div>
<?php } ?>

<dl class="col-dl">
<dt >获取更多模板：</dt>
<dd>您可以在HDWiki模版页面找到更多模板。要安装一个新模版，您需要用FTP软件（Fillza、FlashFXP等等）将带有.htm模板文件目录上传到百科网站目录中的 view 文件夹里，将带有图片与.css样式表的文件目录放到 style 文件夹中，将block目录下的文件目录放到 block 文件夹中，然后刷新本页就可以在这里看到新上传的模版主题了。</dd>
</dl>
</div>
      
        
<?php include $this->gettpl('admin_footer');?>