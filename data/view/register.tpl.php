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
		$('#checkusername').html('用户名不能为空!').fadeIn();
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
		$('#checkpasswd').html('密码不能为空，最多32位!').fadeIn();
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
		$('#checkrepasswd').html('密码不能为空，最多32位!').fadeIn();
		divDance('checkrepasswd');
		g_is_ok_passwd=0;
	}else{
		if($('#password').val()==$('#repassword').val()){
			$('#checkrepasswd').html("<font color='green'>OK</font>").fadeIn();
			result=true;
			g_is_ok_passwd=1;
		}else{
			$('#checkrepasswd').html('两次密码输入不一致').fadeIn();
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
		$('#checkemail').html('输入的邮箱格式不正确!').fadeIn();
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
					$('#checkcode').html('不匹配!').fadeIn();
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
			$('#chkagree').html('您还没有同意!').fadeIn();
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
		<h2 class="h3 a-r">如已注册，请<a href="index.php?user-login" target="_blank" class="m-l8">登录</a></h2>
		<ul class="col-ul">
			<?php if(isset($forward) && $forward ) { ?>
			<input name="forward"   type="hidden" value='<?php echo $forward?>' />
			<?php } ?>
			<?php if((isset($error))) { ?>
			<li style="color:red"><?php echo $error?></li>
			<?php } ?>
			<li><span>用户名：</span>	<input name="username" tabindex="3"  id="username" type="text" maxlength="<?php echo $maxlength?>" class="inp_txt" onblur="check_username()" /><label id="checkusername"><?php echo $loginTip2?></label></li>
			<li><span>密码：</span>	<input name="password" tabindex="4" id="password" type="password" class="inp_txt" onblur="check_passwd()" maxlength="32" /><label id="checkpasswd">*密码不能为空，最多32位!</label></li>
			<li><span>确认密码：</span><input name="repassword" tabindex="5" id="repassword" type="password" class="inp_txt" onblur="check_repasswd()" maxlength="32"/><label id="checkrepasswd"></label></li>
			<li><span>E-mail：</span><input name="email" tabindex="6" id="email"  type="text" class="inp_txt" onblur="check_email()"  maxlength="50"/><label id="checkemail">*填写保密邮箱，通行安全保障</label></li>
			<?php if($checkcode != "3") { ?>
			<li class="yzm">
				<span>验证码</span>
				<input name="code" tabindex="7" type="text" id="code"  maxlength="4" onblur="check_code()" />
					<label class="m-lr8"><img id="verifycode" src="index.php?user-code" onclick="updateverifycode();" /></label>&nbsp;
					<a  href="javascript:updateverifycode();">看不清图片</a>
					<label id="checkcode">&nbsp;</label> 
			</li>
			<?php } ?>
			<li><input name="agree" id="agree" type="checkbox"  checked="checked" />我已看过并同意"<a href="index.php?doc-innerlink-<?php echo urlencode('本站服务条款')?>" target="_blank">本站服务条款</a>" <label id="chkagree">&nbsp;</label></li>
			<li><input type="hidden" id="fromuid" name="fromuid" value="<?php echo $fromuid?>"><input name="submit" tabindex="8" type="submit" value="提交" class="btn_inp" /></li>
		</ul>
	</form>
	</div>
	</div>
<dl class="col-dl">
<dt><img alt="想把自己的知识分享给所有人么？"  src="style/default/reg_01.gif"/></dt>
<dd>在这里您可以得到其他朋友共享的知识，也可以把你的知识贡献给其他需要帮助的朋友们！</dd>
</dl>
<dl class="col-dl">
<dt><img alt="创建自己的百科词条！"  src="style/default/reg_02.gif"/></dt>
<dd>这里不仅能让你创建自己想创建的词条，还可以通过编辑更多词条认识和帮助更多的朋友。</dd>
</dl>
<dl class="col-dl">
<dt><img alt="多套风格模板供您选择！"  src="style/default/reg_03.gif"/></dt>
<dd>提供多套实用模板供您选择。体验视觉盛宴，享受饕餮大餐。</dd>
</dl>
</div>
<script type="text/javascript"> 
$('#username').focus();
</script>
<div class="c-b"></div>
<?php include $this->gettpl('footer');?>