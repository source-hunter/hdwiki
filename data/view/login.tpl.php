<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript">
var indexlogin = 0;
var loginTip1 = '�û�������Ϊ��!';
var loginTip2 = '<?php echo $loginTip2?>';
var loginTip3 = '�û�������!';
var name_max_length = <?php echo $name_max_length?>;
var name_min_length = <?php echo $name_min_length?>;
var editPassTip1 = '���벻��Ϊ�գ����32λ!';
var loginTip4 = '��ƥ��!';
var checkcode = '<?php echo $checkcode?>';
$(function() {$('#username').focus();});
</script> 
<div class="register">
	<div class="r reg-r">
	<div class="login-static reg_main">
	<h2 class="h3 a-r">����δע���˺���<a href="index.php?user-register" target="_blank" class="m-l8">ע��</a></h2>
	<form name="box-login" action="index.php?user-login" method="post" onsubmit="return docheck();">
	<ul class="col-ul">
		<li><span>�û�����</span>	<input name="username" id="username"  tabindex="3" type="text" class="inp_txt" onblur="check_username()" maxlength="32" /><label id="checkusername"><?php echo $loginTip2?></label></li>
		<li><span>���룺</span>	<input name="password" id="password"  tabindex="4"  type="password" class="inp_txt" onblur="check_passwd()" maxlength="32" /><label id="checkpassword">*��ĸ���ִ�Сд�����32λ��</label></li>
		<?php if($checkcode != "3") { ?>
		<li class="yzm"><span>��֤�룺</span><input name="code" id="code"  tabindex="5" type="text" onblur="check_code()" maxlength="4" />
		<label class="m-lr8"><img id="verifycode" src="index.php?user-code" onclick="updateverifycode();" /></label><a href="javascript:updateverifycode();">������ͼƬ</a>
		<label id="checkcode"></label></li>		
		<?php } ?>
		<li><input name="submit" type="submit" value="��¼" class="btn_inp" tabindex="6" /><a href="index.php?user-getpass" target="_blank">�һ�����</a></li>
	</ul>
	</form>
	</div>
	</div>
<dl class="col-dl">
<dt><img alt="����Լ���֪ʶ�����������ô��"  src="style/default/reg_01.gif"/></dt>
<dd>�����������Եõ��������ѹ����֪ʶ��Ҳ���԰����֪ʶ���׸�������Ҫ�����������ǣ�</dd>
</dl>
<dl class="col-dl">
<dt><img alt="�����Լ��İٿƴ�����"  src="style/default/reg_02.gif"/></dt>
<dd>���ﲻ�������㴴���Լ��봴���Ĵ�����������ͨ���༭���������ʶ�Ͱ�����������ѡ�</dd>
</dl>
<dl class="col-dl">
<dt><img alt="���׷��ģ�幩��ѡ��"  src="style/default/reg_03.gif"/></dt>
<dd>�ṩ����ʵ��ģ�幩��ѡ�������Ӿ�ʢ�磬�������Ѵ�͡�</dd>
</dl>
</div>
<div class="c-b"></div>
<?php include $this->gettpl('footer');?>