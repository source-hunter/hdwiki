<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<script type="text/javascript">
function docheck(E){
	var action = $(E).attr('name'), str='';
	var F = $("form#userform");
	F.attr('action', F.attr('action') +'-'+action);
	if(action == 'remove'){
		str = 'ȷ��ɾ����';
	}else{
		str = 'ȷ����ˣ�';
	}
	if(confirm(str)){
		var names = Coin.getUsername();
		if (!names){
			alert('û��ѡ���κ��û�!');
			return false;
		}	
		$('#userform').submit();
	}else{
		return false;
	}
}
function selectALL(obj){
	$("input[name='uid[]']:enabled").attr('checked',obj.checked);
}

var Coin = {
	num:5,
	names: '',
	ids:'',
	coinPms: "�𾴵��û����ã�Ϊ��л����ͻ�����ף��ؽ��� $ ��ҡ�",
	
	getUserId : function(){
		var checkedbox = $("input[name='uid[]']:checked");
		var idlist = [];
		checkedbox.each(function(i){
			idlist.push(this.value);
		});
		
		return idlist.join(",");
	},
	getUsername : function(){
		var checkedbox = $("input[name='uid[]']:checked");
		var namelist = [];
		checkedbox.each(function(i){
			namelist.push($(this).attr('username'));
		});
		
		return namelist.join(",");
	},
	box : function(){
		var names = this.getUsername();
		if (!names){
			return alert('û��ѡ���κ��û�!');
		}
		this.names = names;
		
		$.dialog({
			id:'coin',
			align:'left',
			width:400,
			title:'���ͽ��',
			type:'selector',
			content:"#box-coin",
			styleTitle:{fontSize:'12px'},
			styleOverlay:{backgroundColor:'#FFFFFF'}
		});
		
		this.blur();
	},
	
	send: function(){
		var self=this;
		var url = "index.php?admin_user-addcoins-"+Math.random();
		var names=this.names, uid=this.getUserId();
		
		var dialog = $._dialog.parts['coin'].content;
		var num = dialog.find('input[name=coin_num]').val();
		num = $.trim(num);
		num = parseInt(num);
		if (num == '' || isNaN(num)){
			num = this.num;
		}else{
			this.num = num;
		}
		var content = dialog.find('textarea').val();
		var ispms = dialog.find('input[name=ispms]').attr('checked')?1:0;
		
		
		$.ajax({
			url:url,
			dataType:'html',
			data:{uid:uid, names:names, coin:num, ispms:ispms, content:content},
			timeout: 25000,
			type: 'POST',
			success:function(data){
				if('OK' == data){
					alert('������ͳɹ�');
				}else{
					alert(data)
				}
			},
			complete:function(xmlhttp, status){
				switch(status){
					case 'success':
						self.sendOk();
						self.close();
					break;
					case 'error':
					
					break;
					case 'timeout':
					
					break;
				}
			}
		});
		return false;
	},
	
	pms: function(){
		
	},
	
	close: function(){
		$.dialog.close('coin');
	},
	
	blur:function(){
		var dialog = $._dialog.parts['coin'].content;
		var num = dialog.find('input[name=coin_num]').val();
		num = $.trim(num);
		num = parseInt(num);
		if (num == '' || isNaN(num)){
			num = this.num;
		}
		dialog.find('input[name=coin_num]').val(num);
		var coinPms = this.coinPms.replace('$', num);
		dialog.find('textarea').val(coinPms);
	},
	sendOk:function(){
		var el, uid=this.getUserId();
		if (!uid) return;
		uid = uid.split(",");
		for (var i=0; i<uid.length; i++){
			el = $("#user"+uid[i]);
			el.html( this.num + parseInt(el.html()));
		}
	}
	
}


$(document).ready(function(){

});
</script>
<p class="map">�û����������û�</p>
<p class="sec_nav">�����û��� <a href="index.php?admin_user" <?php if($checkup==1) { ?>class="on"<?php } ?>> <span>�û��б�</span></a> <a href="index.php?admin_user-uncheckeduser" <?php if($checkup==0) { ?>class="on"<?php } ?> ><span>������û�</span></a> <a href="index.php?admin_user-add" ><span>����û�</span></a></p>
<h3 class="col-h3"><?php if($checkup==0) { ?>������û�<?php } else { ?>�û��б�<?php } ?></h3>
<div class="synonym">
<?php if($checkup==1) { ?>
	<form name="usersearch"  action="index.php?admin_user-list"  method="post">
		<ul class="col-ul ul_li_sp m-t10">
			<li><span>�û���: </span>
				<input name="username" type="text" class="inp_txt" size="30" value="<?php echo $username?>" />
			</li>
			<li><span>�û���: </span>
				<select name="groupid">
					<option value="0"  >����</option>
					<?php foreach((array)$usergrouplist as $usergroup) {?>
						<option value="<?php echo $usergroup['groupid']?>" <?php if($groupid==$usergroup['groupid']) { ?>selected<?php } ?> ><?php echo $usergroup['grouptitle']?></option>
					<?php } ?>
				</select>
			</li>
			<li>
				<input name="searchsubmit" type="submit" value="�� ��"   class="inp_btn"/>
			</li>
		</ul>
	</form>
<?php } else { ?>
	<form name="usersearch"  action="index.php?admin_user-uncheckeduser"  method="post">
		<ul class="col-ul ul_li_sp m-t10">
			<li><span>�û���: </span>
				<input name="username" type="text" class="inp_txt" size="30" value="<?php echo $username?>" />
			</li>
			<li>
				<input name="searchsubmit" type="submit" value="�� ��"   class="inp_btn"/>
			</li>
		</ul>
	</form>
<?php } ?>

<h3 class="tol_table">[ �� <b><?php echo $usercount?></b> ���û� ]</h3>
<form name="userform" id="userform" action="index.php?admin_user"  method="post" >
<table class="table">
		<thead>
			<tr>
				<td style="width:50px;">ѡ��</td>
				<td style="width:80px;">�û���</td>
				<td style="width:50px;">���</td>
				<td style="width:100px;">�����ʼ�</td>
				<td style="width:100px;">����¼ʱ��</td>
				<td style="width:100px;">ע��ʱ��</td>
				<td style="width:60px;">���</td>
				<td style="width:80px;">�û���</td>
				<td>�༭</td>
			</tr>
		</thead>
		<!-- <?php if($userlist) { ?>  -->
		<?php foreach((array)$userlist as $user) {?>
		<tr>
			<td>
				<input type="checkbox" <?php if(isset($user['disabled'])) { ?>disabled="disabled"<?php } ?> name="uid[]" id="uid[]" value="<?php echo $user['uid']?>" username="<?php echo $user['username']?>" />
			</td>
			<td><a href="index.php?user-space-<?php echo $user['uid']?>" target="_blank"><?php echo $user['username']?></a></td>
			<td id="user<?php echo $user['uid']?>"><?php echo $user['credit1']?></td>
			<td><?php echo $user['email']?></td>
			<td><?php echo $user['lasttime']?></td>
			<td><?php echo $user['regtime']?></td>
			<td>
				<?php if($user['checkup']==1  ) { ?>
					�����
				<?php } else { ?>
					δ���
				<?php } ?>
			</td>
			<td ><?php echo $user['grouptitle']?></td>
			<td><?php if(isset($user['disabled'])) { ?>X<?php } else { ?><a href="index.php?admin_user-edit-<?php echo $user['uid']?>">�༭</a><?php } ?> </td>
		</tr>
		<?php } ?>
		<!--<?php } else { ?> -->
		<tr>
			<td colspan="9">û���ҵ���Ҫ�������û�!</td>
		</tr>
		<!-- <?php } ?> -->
		<tr>
			<td><label class="m-r10" id="tip">
			<input name="chkall" class="box" onclick="selectALL(this);"  type="checkbox" />ȫѡ</label></b></td>
			<td colspan="8"><input name="remove" type="button" class="inp_btn2 m-r10" value="ɾ��" onclick="return docheck(this);"/>
			<!-- <?php if($checkup==1 ) { ?> -->
			&nbsp;&nbsp; <input name="checkup" type="button" class="inp_btn2 m-r10" value="���ͽ��" onclick="Coin.box()"/>
			<!-- <?php } ?> -->
			<!-- <?php if($checkup==0 ) { ?> -->
			&nbsp;&nbsp; <input name="checkup" type="submit" class="inp_btn2 m-r10" value="���" onclick="return docheck(this);"/>
			<!-- <?php } ?> -->
			</td>
		</tr>
		<tr>
			<td colspan="9"><p class="fenye a-r"> <?php echo $departstr?> </p></td>
		</tr>
</td>
</tr>
</table>
</form>
</div>
<div id="box-coin" style="display:none">
	<form onsubmit="return Coin.send()">
	<table border="0" align="center">
		<tr style="display:none">
			<td></td>
			<td height="20" align="left" name="error"></td>
		</tr>
		<tr>
			<td style="width:50px" height="30">�����:</td>
			<td><input name="coin_num" type="text" class="inp_txt" style="width:240px;" maxlength="5" onblur="Coin.blur()"/></td>
		</tr>
		<tr>
			<td></td>
			<td height="25" align="left">
			<input type="checkbox" name="ispms" checked="true"/>ͬʱ����վ����
			</td>
		</tr>
		<tr name="trpms">
			<td height="30" valign="top">վ����:</td>
			<td><textarea name="pms" type="textarea" class="inp_txt" style="width:240px;height:60px;"></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td height="24">
			<input type="button" value="ȷ��" onclick="Coin.send()"/>
			<input type="button" value="ȡ��" onclick="Coin.close();"/>
			</td>
		</tr>
	</table>
	</form>
</div>
<?php include $this->gettpl('admin_footer');?>