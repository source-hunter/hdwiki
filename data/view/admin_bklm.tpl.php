<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>

<p class="map">�ٿ����ˣ�������ҳ</p>

<?php if(!$is_login) { ?>

	<script type="text/javascript">
	String.prototype.Trim = function(){ 
		return this.replace(/(^\s*)|(\s*$)/g, ""); 
	}
	
	function getFormData(FormElement){
		var data={};
		var inputs=$(FormElement).find(":input"), fields=inputs.serializeArray();
		$.each(fields, function(i, field){
			var type=inputs.filter('[name='+field.name+']').attr('type');
			
			if(/checkbox/i.test(type)){
				if(!data[field.name]){
					data[field.name]=field.value;
				}else{
					data[field.name] += ', '+field.value;
				}
			}else{
				data[field.name]=field.value;
			}
		});
		
		return data;
	}
	
	function setTip(name, msg){
		$("#errorinfo").html(msg);
	}

	function check_login(FormElement){
		var re_email = /^[a-z0-9]([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2,3})?$/i,
			re_qq = /^\d{5,12}$/,
			params=getFormData(FormElement);
		
		setTip('', '');
		
		if (params.email == '' || !re_email.test(params.email)){
			setTip('form-'+params.action+'-email', '��������Ч��Email��ַ');
			return false;
		}
		
		if (params.pwd.length <6 ){
			setTip('form-'+params.action+'-pwd', '����������6--16λ');
			return false;
		}

		params.wikiurl = '';
		
		//��ȡhdwiki����·���Լ���վ����
		if (location.href.indexOf('index.php?')){
			params.wikiurl = location.href.split('index.php?')[0];
		}
		params.sitedomain = location.host;
		
		var submit=$(FormElement).find(':submit');
		
		$.ajax({
			url:'index.php?admin_hdapi-login',
			type:'POST',
			data:params,
			timeout:60000,
			dataType:'html',
			beforeSend:function(){
				submit.attr('disabled', true);
				setTip('form-'+params.action+'-error', '�����ύ��Ϣ...');
			},
			success :function(data, state){
				setTip('form-'+params.action+'-error', '');
				
			   var message=$.trim(data);
			   if(message.indexOf('<') > -1){
					setTip('form-'+params.action+'-error', '�ύʧ�ܣ�ԭ���������������ʧ�ܡ�');
			   }else if(message == 'OK'){
					alert('�ٿ����˿�ͨ�ɹ�����֪���������ɹ���');
					location.reload();
			   }else{
					message = message || "����������ʧ�ܡ�";
					setTip('form-'+params.action+'-error', '�ύʧ�ܣ�ԭ�������'+message);
			   }
			},
			complete :function(response, state){
				if (state != 'success'){
					setTip('form-'+params.action+'-error', '�����������Ӵ����ύʧ��!');
				}
				submit.attr('disabled', false);
			}
		});
	}

	</script>

	<ul class="col-ul">
	<li>��ʹ�����˹���ǰ������Ҫ����һ�ε�¼����¼֮�����Ϳ��Ժͻ����ٿ���վͨ�š�<br/> 
	Email������������ڻ����ٿ���վ(baike.com)ע��ʱ��д��Email�����롣 <br/> 
	����������ǻ����ٿ���վ���û������Ƚ���<a href="http://passport.baike.com/user/userRegister.jsp" target="_blank">ע��</a>��</li>
	</ul>
	<form id="form-login" action="#" method="post" target="_self" onsubmit="check_login(this);return false;">
	<ul class="col-ul ul_li_sp">
	<li><span>E-mail��</span><input name="email" type="text" maxlength="50" class="inp_txt w-140 m-r10" />���ڻ����ٿ�ע���E-mail��</li>
	<li><span>�������룺</span><input name="pwd" type="password" maxlength="30" class="inp_txt w-140 m-r10" />�������ٿ��ʺŶ�Ӧ�����룩</li>
	<li><input type="hidden" name="action" value="login" /><input type="submit" value="�� ¼"  class="inp_btn"/></li>
	<li id="errorinfo" class="red"></li>
	</ul>
	</form>
<?php } else { ?>

	<style>
		.bklmok, .bklmerr{display:none;}
	</style>
	<form method="post">
	<ul class="col-ul">
	
	<li class="bklm">���ڻ�ȡ������Ϣ�����Ե�...</li>
	
	<li class="bklmok">��ӭ�㣬<span name="name"><?php echo $name?></span> վ����ȥ<a href="http://i.baike.com/profile.do?useriden=<?php echo $site_key?>" target="_blank">��֪��</a>���</li>
	<li class="bklmok">
		վ�����ͣ�<span name="site_class"><?php echo $site_class?></span> <br />
		�ڻ����ٿ���վ���ף�<span name="user_credit"><?php echo $user_credit?></span>������ <br />
		�Ѿ��һ���������<span name="user_credit_exchange"><?php echo $user_credit_exchange?></span> <br />
		��ʣ����������<span name="user_credit_left"><?php echo $user_credit_left?></span> <br />
		�ڻ�����վ����������<span name="doc_create"><?php echo $doc_create?></span> <br />
		�ڻ�����վЭ��������<span name="doc_cooper"><?php echo $doc_cooper?></span> <br />
		Υ������<span name="warning_count"><?php echo $warning_count?></span> <br /> 
  </li>
	
	<li class="bklmerr">������Ϣ��ȡʧ�ܣ����Ժ����ԡ�</li>
	<li class="bklmerr">���ִ����⣬����ȷ�Ϸ������Ƿ���Է���������</li>
	
	
	<li><br />������������ѯ��<a href="http://kaiyuan.hudong.com/bbs/forumdisplay.php?fid=41" target="_blank">HDWiki��̳�ٿ�����Ƶ��</a></li>
	<li class="wiki_url" style="display:none;color:red"></li>
	</ul>
	</form>
	
	<script>
	var wikiurl, sitedomain=location.host;
	
	if (location.href.indexOf('index.php?')>-1){
		wikiurl = location.href.split('index.php?')[0];
	}
	
	function getindexinfo(){
		var url="index.php?admin_hdapi-getindexinfo-"+Math.random();
		$.ajax({
			url:url,
			dataType:"json",
			timeout: 25000,
			type: "GET",
			success:function(obj){
				setindexinfo(obj);
			},
			complete:function(xmlhttp, state){
				if(state == "parsererror" && xmlhttp.responseText){
					alert("���ݸ�ʽ���󣬿���HDWikiϵͳ�ļ����޸ģ����顣\n"+xmlhttp.responseText);
				}
				
				if(state!= "success"){
					$("li.bklm, li.bklmok").hide();
					$("li.bklmerr").show();
				} 
			}
		});
	}
	
	function setindexinfo(obj){
		if(obj && (typeof obj == 'object') && obj['success']){
			obj = obj['data'];
			for (var sitenick in obj){
				obj=obj[sitenick];
				$("span[name=name]").text(sitenick);
				for(var name in obj){
					$("span[name="+name+"]").text(obj[name]);
					if(name == 'wiki_url' && wikiurl && obj[name] != wikiurl && obj[name]+'/' != wikiurl){
						$("li.wiki_url").html('�ٿ����˼�¼����ַ('+obj[name]+
							')�͵�ǰ��ַ��һ�£����ڡ��޸��������ϡ�ҳ�������ύվ����Ϣ��'
						).show();
					}
				}
				break;
			}
			
			$("li.bklmok").show();
			$("li.bklm,li.bklmerr").hide();
		}else{
					
		}
	}
	
	$(document).ready(function(){
		getindexinfo();
	});
	</script>
<?php } ?>

<?php include $this->gettpl('admin_footer');?> 