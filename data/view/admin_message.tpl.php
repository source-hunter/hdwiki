<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<dl class="col-dl error">
	<dt>��ʾ��Ϣ</dt>
	<dd class="red"><?php echo $message?></dd>
	<dd><?php if($redirect == 'BACK') { ?>
		<a href="javascript:history.back();">������ﷵ��</a>
		<?php } elseif($redirect) { ?>
			<a href="<?php echo $redirect?>">ҳ�潫�� 3 ����Զ���ת����һҳ��<a href="<?php echo $redirect?>">�������</a>ֱ����ת...</a>
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