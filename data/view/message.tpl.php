<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php if(isset($ajax) ) { ?>
<?php ob_end_clean();?>
<?php ob_start();?>
<?php @header("Expires: -1");?>
<?php @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);?>
<?php @header("Pragma: no-cache");?>
<?php @header("Content-type: application/xml; charset=$charset");?>
<?php echo '<?xml version="1.0" encoding="'.$charset.'"?>';?>
<root><![CDATA[<?php echo $message?>]]></root>
<?php } else { ?>
<?php include $this->gettpl('header');?>
<div class="success">
 	<dl>
 	<dt class="h2">��ʾ��Ϣ:</dt>
 	<dd><?php echo $message?></dd>
 	<?php if($redirect == 'BACK') { ?>
	<dd><a href="javascript:void(0);" onclick="history.back();return false;">������ﷵ��</a></dd>
	<?php } elseif($redirect) { ?>
	<dd>ҳ�潫��3����Զ���ת����һҳ��<a href="<?php echo $redirect?>" >������ת</a></dd>
	<script type="text/javascript">
	function redirect(url, time) {
		setTimeout("window.location='" + url + "'", time * 1000);
	}
	redirect('<?php echo $redirect?>', 3);
	</script>
	<?php } ?>
 	</dl>
</div>
<?php include $this->gettpl('footer');?>
<?php } ?>