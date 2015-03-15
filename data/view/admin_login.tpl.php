<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo WIKI_CHARSET?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>管理登录</title>
<script type="text/javascript" src="js/jquery.js"></script>
<link href="style/default/admin/admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body{font-size:12px;color:#666;}
.login{left:50%;top:50%; position:absolute;}
.login .main{width:550px;height:425px;margin:-230px 0 0 -275px;background:url(style/default/admin/login_bg.gif) no-repeat bottom left;position:relative;}
.login .main ul{width:450px;padding:170px 0 0 100px;height:200px;background:url(style/default/admin/login_head.gif) no-repeat;}
.login .main li{padding-bottom:8px;}
.login .inp_btn{width:76px;height:32px;background:url(style/default/admin/login_btn.gif) no-repeat;padding:0;}
</style>
</head>
<body>
<form name="adminform"  style="margin: 0;" action="index.php?admin_main-login" method="post">
<div class="login">
<div class="main">
<ul class="col-ul ul_li_sp">
<li><span>用户名:</span><?php echo $user['username']?></li>
<li><span>密  码: </span><input tabindex="0" type="password" class="inp_txt w-140" name="password"></li>
<li><input name="submit" type="submit" class="inp_btn" tabindex="1" value="登录"></li>
</ul>
<p class="col-p a-c"><a href="http://kaiyuan.hudong.com/" target="_blank">HDWiki V<?php echo HDWIKI_VERSION?> release <?php echo HDWIKI_RELEASE?></a>, All rights reserved</p>
</div>
</div>
</form>
<script type="text/javascript"> 
<?php if(isset($loginmsg) ) { ?>alert('<?php echo $loginmsg?>');<?php } ?>
	$("input[name='password']").focus();
</script>
</body>
</html>
