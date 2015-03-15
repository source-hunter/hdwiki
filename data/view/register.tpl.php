<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<script type="text/javascript" src="js/function.js"></script>
<script type="text/javascript">
var g_is_ok_username=1, g_is_ok_passwd=1, g_is_ok_email=1, g_is_ok_code=1;
var g_submited = false;
function check_username(){
	$('#checkusername').fadeOut();
	var result=false;
	var username=$.trim($('#username').val());
	var length=bytes(username);
	if(length==0){
		g_is_ok_username=0;
		$('#checkusername').html('�û�������Ϊ��!').fadeIn();
		divDance('checkusername');
	}else if( length <<?php echo $minlength?> || length ><?php echo $maxlength?>){
		$('#checkusername').html('<?php echo $loginTip2?>').fadeIn();
		divDance('checkusername');
		g_is_ok_username=0;
	}else{
		jQuery.ajax({
			url: "index.php?user-checkusername",
			cache: false,
			dataType: "xml",
			type:"post",
			//async:false, 
			data: { username: username ,type:2 },
			success: function(xml){
				var	message=xml.lastChild.firstChild.nodeValue;
				if(message!='OK'){
					$('#checkusername').html(message).fadeIn();
					divDance('checkusername');
					g_is_ok_username=0;
				}else{
					$('#checkusername').html("<font color='green'>OK</font>").fadeIn();
					result=true;
					g_is_ok_username=1;
				}
			}
		});
	}
	return result;
}

function check_passwd(){
	$('#checkpasswd').fadeOut();
	var result=false;
	var passwd=$('#password').val();
	if( bytes(passwd) <1|| bytes(passwd)>32){
		$('#checkpasswd').html('���벻��Ϊ�գ����32λ!').fadeIn();
		divDance('checkpasswd');
		g_is_ok_passwd=0;
	}else{
		$('#checkpasswd').html("<font color='green'>OK</font>").fadeIn();
		result=true;
		g_is_ok_passwd=1;
	}
	return result;
}

function check_repasswd(){
	$('#checkrepasswd').fadeOut();
	var result=false;
	var repassword=$('#repassword').val();
	if( bytes(repassword) <1|| bytes(repassword)>32){
		$('#checkrepasswd').html('���벻��Ϊ�գ����32λ!').fadeIn();
		divDance('checkrepasswd');
		g_is_ok_passwd=0;
	}else{
		if($('#password').val()==$('#repassword').val()){
			$('#checkrepasswd').html("<font color='green'>OK</font>").fadeIn();
			result=true;
			g_is_ok_passwd=1;
		}else{
			$('#checkrepasswd').html('�����������벻һ��').fadeIn();
			divDance('checkrepasswd');
			g_is_ok_passwd=0;
		}
	}
	return result;
}

function check_email(email){
	$('#checkemail').fadeOut();
	var result=false;
	var email=$.trim($('#email').val());
	if (email=="" || !email.match(/^[\w\.\-]+@([\w\-]+\.)+[a-z]{2,4}$/ig)){
		g_is_ok_email=0;
		$('#checkemail').html('����������ʽ����ȷ!').fadeIn();
		divDance('checkemail');
	}else{
		jQuery.ajax({
			url: "index.php?user-checkemail",
			cache: false,
			dataType: "xml",
			//async:false, 
			type:"post",
			data: { email: email },
			success: function(xml){
				var	message=xml.lastChild.firstChild.nodeValue;
				if(message!='OK'){
					g_is_ok_email=0;
					$('#checkemail').html(message).fadeIn();
					divDance('checkemail');
				}else{
					g_is_ok_email=1;
					$('#checkemail').html("<font color='green'>OK</font>").fadeIn();
					result=true;
				}
			}
		});
	}
	return result;
}

function check_code(){
	$('#checkcode').fadeOut();
	var result=false;
	var code=$.trim($('#code').val());
	jQuery.ajax({
			url: "index.php?user-checkcode",
			cache: false,
			dataType: "xml",
			type:"post",
			//async:false, 
			data: { code: code },
			success: function(xml){
				var	message=xml.lastChild.firstChild.nodeValue;
				if(message=='OK'){
					g_is_ok_code=1;
					$('#checkcode').html("<font color='green'>OK</font>").fadeIn();
					result=true;
				}else{
					g_is_ok_code=0;
					$('#checkcode').html('��ƥ��!').fadeIn();
					divDance('checkcode');
				}
			}
	});
	return result;
}

function docheck(){
	//if( check_username()&&check_passwd()&&check_repasswd()&&check_email()){
	if(!g_submited && g_is_ok_username && g_is_ok_passwd && g_is_ok_email && g_is_ok_code){
		if(! $('#agree').attr('checked')){
			$('#chkagree').html('����û��ͬ��!').fadeIn();
			return false;
		}
		<?php if($checkcode != 3 ) { ?>
			//return check_code();
		<?php } ?>
		g_submited = true;
		return true;
	}else{
		return false;
	}
}

function updateverifycode() {
	var img = "index.php?user-code-"+Math.random();
	$('#verifycode').attr("src",img);
}

</script>
<div class="register">
	<div class="r reg-r">
	<div class="login-static reg_main">
	<form id="registerform" method="post" action="<?php echo $formAction?>" onsubmit="return docheck();">
		<h2 class="h3 a-r">����ע�ᣬ��<a href="index.php?user-login" target="_blank" class="m-l8">��¼</a></h2>
		<ul class="col-ul">
			<?php if(isset($forward) && $forward ) { ?>
			<input name="forward"   type="hidden" value='<?php echo $forward?>' />
			<?php } ?>
			<?php if((isset($error))) { ?>
			<li style="color:red"><?php echo $error?></li>
			<?php } ?>
			<li><span>�û�����</span>	<input name="username" tabindex="3"  id="username" type="text" maxlength="<?php echo $maxlength?>" class="inp_txt" onblur="check_username()" /><label id="checkusername"><?php echo $loginTip2?></label></li>
			<li><span>���룺</span>	<input name="password" tabindex="4" id="password" type="password" class="inp_txt" onblur="check_passwd()" maxlength="32" /><label id="checkpasswd">*���벻��Ϊ�գ����32λ!</label></li>
			<li><span>ȷ�����룺</span><input name="repassword" tabindex="5" id="repassword" type="password" class="inp_txt" onblur="check_repasswd()" maxlength="32"/><label id="checkrepasswd"></label></li>
			<li><span>E-mail��</span><input name="email" tabindex="6" id="email"  type="text" class="inp_txt" onblur="check_email()"  maxlength="50"/><label id="checkemail">*��д�������䣬ͨ�а�ȫ����</label></li>
			<?php if($checkcode != "3") { ?>
			<li class="yzm">
				<span>��֤��</span>
				<input name="code" tabindex="7" type="text" id="code"  maxlength="4" onblur="check_code()" />
					<label class="m-lr8"><img id="verifycode" src="index.php?user-code" onclick="updateverifycode();" /></label>&nbsp;
					<a  href="javascript:updateverifycode();">������ͼƬ</a>
					<label id="checkcode">&nbsp;</label> 
			</li>
			<?php } ?>
			<li><input name="agree" id="agree" type="checkbox"  checked="checked" />���ѿ�����ͬ��"<a href="index.php?doc-innerlink-<?php echo urlencode('��վ��������')?>" target="_blank">��վ��������</a>" <label id="chkagree">&nbsp;</label></li>
			<li><input type="hidden" id="fromuid" name="fromuid" value="<?php echo $fromuid?>"><input name="submit" tabindex="8" type="submit" value="�ύ" class="btn_inp" /></li>
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
<script type="text/javascript"> 
$('#username').focus();
</script>
<div class="c-b"></div>
<?php include $this->gettpl('footer');?>