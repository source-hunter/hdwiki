<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script type="text/javascript">
function docheck(){
	var reg = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
    if( reg.test($('#email').val()) == false ){
        alert("邮件格式不正确!");
        return false;
    }
    return true;
}
</script>
<p class="map">用户管理：管理用户</p>
<p class="sec_nav">管理用户： <a href="index.php?admin_user"> <span>用户列表</span></a> <a href="index.php?admin_user-uncheckeduser"  ><span>待审核用户</span></a> <a href="index.php?admin_user-add" ><span>添加用户</span></a> <a href="index.php?admin_user-edit-<?php echo $user['uid']?>" class="on"><span>编辑用户</span></a></p>
<h3 class="col-h3">编辑用户</h3>

<form action="index.php?admin_user-edit" method="post" name="userform"  onSubmit="return docheck();">
	<ul class="col-ul ul_li_sp m-t10"><input type='hidden' name='id' value="<?php echo $gift['id']?>"/>
		<li><span>用户名</span><?php echo $user['username']?><input type="hidden" name="uid" value="<?php echo $user['uid']?>"></li>
		<li><span>密  码</span><input type='password' class="inp_txt m-r10" name='password' id="password" value="" size='50' /><b class="red">*</b></li>
		<li><span>电子邮件</span><input type="text" class="inp_txt m-r10" name='email' id="email" value="<?php echo $user['email']?>" size='50' /><b class="red">*</b></li>
		<li><span>用户组</span>
             <select name="groupid">
				<?php foreach((array)$usergrouplist as $usergroup) {?>
				 	<option value="<?php echo $usergroup['groupid']?>" <?php if($usergroup['groupid']==$user['groupid']) { ?>selected<?php } ?> ><?php echo $usergroup['grouptitle']?></option>
				<?php } ?>
              </select>
		<b class="red">*</b>
		</li>
		<li class="m-t10">
            <input name="submit" class="inp_btn" type="submit" value="确定"/>
            <input name="reset" class="inp_btn" type="reset" value="重置" />
		</li> 
		</li>
	</ul>
</form>
<?php include $this->gettpl('admin_footer');?>