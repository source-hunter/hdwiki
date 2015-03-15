<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<dl class="col-dl error">
	<dt>提示信息</dt>
	<dd class="red"><?php echo $message?></dd>
	<dd><?php if($redirect == 'BACK') { ?>
		<a href="javascript:history.back();">点击这里返回</a>
		<?php } elseif($redirect) { ?>
			<a href="<?php echo $redirect?>">页面将在 3 秒后自动跳转到下一页，<a href="<?php echo $redirect?>">点击这里</a>直接跳转...</a>
			<script type="text/javascript">
			function redirect(url, time) {
				setTimeout("window.location='" + url + "'", time * 1000);
			}
			redirect('<?php echo $redirect?>', 3);
			</script>
		<?php } ?>
	</dd>
</dl>
<?php include $this->gettpl('admin_footer');?>