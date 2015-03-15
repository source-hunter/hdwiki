<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class ucentermodel {

	var $db;
	var $base;

	function ucentermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function avatar(){
		$this->base->user["image"]=UC_API."/avatar.php?uid=".$this->base->user["uid"]."&size=middle";
	}
	
	function register($username, $password, $email){
		$uid = uc_user_register($username,$password,$email);
		if ($uid <= 0) {
			if($uid == -1) {
				$msg= "�û������Ϸ�";
			} elseif($uid == -2) {
				$msg= "����������ע��Ĵ���";
			} elseif($uid == -3) {
				$msg= "�û����Ѿ�����";
			} elseif($uid == -4) {
				$msg= "Email ��ʽ����";
			} elseif($uid == -5) {
				$msg= "Email ������ע��";
			} elseif($uid == -6) {
				$msg= "�� Email �Ѿ���ע��";
			} else {
				$msg= "�쳣����δ����";
			}
			$this->base->message($msg,"BACK",0);
		} else {
			$result=$_ENV["user"]->add_user($username, md5($password), $email,$this->base->time,$this->base->ip,2,$uid);
			$_ENV["user"]->refresh_user($result);
			$_ENV["user"]->add_credit($this->base->user["uid"],"user-register",$this->base->setting["credit_register"]);
			$synlogin=uc_user_synlogin($uid);
			$this->base->message("��ϲ <b>$username</b> ע��ɹ���".$synlogin,"index.php",0);
		}
	}
	
	function login($username){
		list($uid, $username, $password, $email) = uc_user_login($username, $this->base->post["password"]);
		if ($uid > 0) {
			$msg = "OK";
			$synlogin=uc_user_synlogin($uid);
		} elseif ($uid == -1) {
			$msg= "�û�������,�����Ѿ���ɾ��";
		} elseif ($uid == -2) {
			$msg= "�������";
		} elseif ($uid == -3) {
			$msg= "��ȫ���ʴ���";
		} else {
			$msg= "�������󣬿���������UC�����ʧ���ˡ�";
		}
		if ($msg != "OK") {
			if ($this->base->post["submit"] == "ajax") {
				exit($msg);
			} else {
				$this->base->message($msg,"BACK",0);
			}
		} else {
			$user = $_ENV["user"]->get_user("username",$username);
			if(is_array($user) && $user["uid"]!=$uid){//������û���������uid�������
				if($usernum=$this->db->result_first("select count(*) from ".DB_TABLEPRE."user where uid= $uid")){
					$maxuid=$this->db->result_first("select max(uid) from ".DB_TABLEPRE."user");
					$maxuid+=1;
					$this->db->query("update ".DB_TABLEPRE."user set uid=$maxuid where uid=$uid");
					$this->update_field($uid,$maxuid);
				}
				$_ENV["user"]->update_field("uid",$uid,$user["uid"],1);
				$this->update_field($user["uid"],$uid);
				$user["uid"]=$uid;
			}elseif(!is_array($user)){//û������û�����
				if($usernum=$this->db->result_first("select count(*) from ".DB_TABLEPRE."user where uid= $uid")){
					$maxuid=$this->db->result_first("select max(uid) from ".DB_TABLEPRE."user");
					$maxuid+=1;
					$this->db->query("update ".DB_TABLEPRE."user set uid=$maxuid where uid=$uid");
					$this->update_field($uid,$maxuid);
				}
				$_ENV["user"]->add_user($username, md5($password), $email,$this->base->time,$this->base->ip,2,$uid);
				$_ENV["user"]->add_credit($uid,"user-register",$this->base->setting["credit_register"]);
				$user = $_ENV["user"]->get_user("username",$username);
			}else{//������û�����uidҲ��ȷ��
				$lasttime=$user["lasttime"];
				if($this->base->time>($lasttime+24*3600)){
					$_ENV["user"]->add_credit($user["uid"],"user-login",$this->base->setting["credit_login"]);
				}
				//�޸��û������ϡ�
				$_ENV["user"]->edit_user($user["uid"],$password,$email,$user["groupid"]);
				$_ENV["user"]->update_user($user["uid"],$this->base->time,$this->base->ip);
			}
			
			
			$_ENV["user"]->refresh_user($user["uid"]);
			$this->base->view->assign("adminlogin",$this->base->checkable("admin_main-login"));
			if ($this->base->post["submit"] == "ajax") {
				echo $synlogin;
				exit;
			} else {
				$this->base->message("��¼�ɹ�".$synlogin,"index.php",0);
			}
		}
	}
	
	function checkname(&$msg,$username,$type){
		$uid = uc_user_checkname($username);
		if($uid <= 0) {
			if ($uid == -1) {
				$msg= "�û������Ϸ�";
			} elseif ($uid == -2 || $uid == -3) {
				if ($type>0) {
					$msg = $uid == -3?"�û����Ѿ�����":"����������ע��Ĵ���";
				} elseif ($type==0) {
					$msg=  "OK";
				}
			} 
		} else {
			if ($type>0) {
				$msg= "OK";
			} elseif ($type==0) {
				$msg= "�û���������";
			}
		}	
		
	}
	
	function checkemail(&$msg,$email){
		$ucresult = uc_user_checkemail($email);
		if($ucresult == -4) {
			$msg= "Email ��ʽ����";
		} elseif($ucresult == -5) {
			$msg="Email ������ע��";
		} elseif($ucresult == -6) {
			$msg="�� Email �Ѿ���ע��";
		} 
	}
	
	function logout(){
		return uc_user_synlogout();
	}
	
	function iscredit(){
		$outextcredits=unserialize($this->base->setting["outextcredits"]);
		if((bool)$outextcredits){
			$this->base->view->assign("iscredit",true);
		}
	}
	
	function editpass($newpass){
		$userarr = $_ENV["user"]->get_user("uid",$this->base->user["uid"]);
		$username=$userarr["username"];
		$ucresult = uc_user_edit($username,$this->base->post["oldpass"],$newpass);
		if ($ucresult == -1) {
			$this->base->message("�����벻��ȷ","BACK",0);
		} elseif ($ucresult == 1) {
			$msg = "OK";
		} else {
			$this->base->message("ĳЩԭ������ˣ�û���κ��޸�","BACK",0);
		}
		return $msg;
	}
	
	function edit_user_image(){
		$image_html=uc_avatar($this->base->user["uid"]);
		if(uc_check_avatar($this->base->user["uid"])){
			$uid_image=UC_API."/avatar.php?uid=".$this->base->user["uid"]."&size=middle";
			$this->base->view->assign("uid_image",$uid_image);
		}
		$this->base->view->assign("image_html",$image_html);
	}
	
	function getpass($uid,$newpass){
		$userarr = $_ENV["user"]->get_user("uid",$uid);
		$username=$userarr["username"];
		$ucresult = uc_user_edit($username,"",$newpass,"",1);
		if($ucresult == -1) {
			$this->base->message("�����벻��ȷ","BACK",0);
		} elseif($ucresult == 1) {
			$msg = "OK";
		}else{
			$this->base->message("ĳЩԭ������ˣ�û���κ��޸�","BACK",0);
		}
		return $msg;
	}						
	
	function doc_user_image(&$editors,$doc){
		if(uc_check_avatar($editors[$doc["author"]][uid])){
			$editors[$doc["author"]]["image"]=UC_API."/avatar.php?uid=".$editors[$doc["author"]][uid]."&size=small";
		}
		if(uc_check_avatar($editors[$doc["lasteditor"]][uid])){
			$editors[$doc["lasteditor"]]["image"]=UC_API."/avatar.php?uid=".$editors[$doc["lasteditor"]][uid]."&size=small";
		}
	}
	
	function create_feed($doc,$did){
		$isimg=util::getfirstimg($doc[content]);
		if(false!==strpos($isimg,"http://")){
			$img=empty($isimg)?"":$isimg;
		}else{
			$img=empty($isimg)?"":WIKI_URL."/".$isimg;
		}
		$doc[did]=$did;
		$feed=unserialize($this->base->setting[feed]);
		$uid=$this->base->user[uid];
		if(@in_array("create",$feed)){
			$feed = array();
			$feed["icon"] = "post";
			$feed["type"] = "create";
			$feed["title_data"] = array(
				"title" => "<a href=\\\"".WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}\\\">$doc[title]</a>",
				"author" => "<a href=\\\"space.php?uid={$uid}\\\">$this->user[username]</a>",
				"app"=>$this->base->setting["site_name"]
			);
			$feed["body_data"] = array(
				"subject"=> "<a href=\\\"".WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}\\\">$doc[title]</a>",
				"message"=> $doc["summary"]
			);
			$feed["images"][]= array("url" => "{$img}", "link" => WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}");
			$this->postfeed($feed);
		}
	
	}
	
	function edit_feed($doc){
		$isimg=util::getfirstimg($doc[content]);
		if(false!==strpos($isimg,"http://")){
			$img=empty($isimg)?"":$isimg;
		}else{
			$img=empty($isimg)?"":WIKI_URL."/".$isimg;
		}
		$feed=unserialize($this->base->setting[feed]);
		$uid=$this->base->user[uid];
		if(@in_array("edit",$feed)){
			$feed = array();
			$feed["icon"] = "post";
			$feed["type"] = "edit";
			$feed["title_data"] = array(
				"title" => "<a href=\\\"".WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}\\\">$doc[title]</a>",
				"author" => "<a href=\\\"space.php?uid={$uid}\\\">{$this->base->user['username']}</a>",
				"app"=>$this->base->setting["site_name"]
			);
			$feed["body_data"] = array(
				"subject"=> "<a href=\\\"".WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}\\\">$doc[title]</a>",
				"message"=> $doc["summary"]
			);
			$feed["images"][]= array("url" => "{$img}", "link" => WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}");
			$this->postfeed($feed);
		}
	
	}
	
	function admin_register(){
		$username=$this->base->post["username"];
		$password=$this->base->post["password"];
		$email=$this->base->post["email"];
		$uid = uc_user_register($username,$password,$email);
		if($uid <= 0) {
			if($uid == -1) {
				$msg= "�û������Ϸ�";
			} elseif($uid == -2) {
				$msg= "����Ҫ����ע��Ĵ���";
			} elseif($uid == -3) {
				$msg= "�û����Ѿ�����";
			} elseif($uid == -4) {
				$msg= "Email ��ʽ����";
			} elseif($uid == -5) {
				$msg= "Email ������ע��";
			} elseif($uid == -6) {
				$msg= "�� Email �Ѿ���ע��";
			} else {
				$msg= "δ����";
			}
			$this->base->message($msg,"BACK",0);
		}	
	}
	
	function edituser($uid){
		$userarr = $_ENV["user"]->get_user("uid",$uid);
		$username=$userarr["username"];
		$newpass=$this->base->post["password"];
		$email=$this->base->post["email"];
		$ismail=$userarr["email"]!=$email?1:0;
		if((bool)$newpass && $ismail){
			$ucresult = uc_user_edit($username,"",$newpass,$email,1);
		}elseif((bool)$newpass && !$ismail){
			$ucresult = uc_user_edit($username,"",$newpass,"",1);
		}elseif($ismail){
			$ucresult = uc_user_edit($username,"","",$email,1);
		}else{
			$ucresult = 1;
		}
		if($ucresult == 1) {
			$msg= "OK";
		} elseif($ucresult == 0) {
			$msg= "�¾�������ͬ��������ɡ�";
		} elseif($ucresult == -4) {
			$msg= "Email ��ʽ����";
		} elseif($ucresult == -5) {
			$msg= "Email ������ע��";
		} elseif($ucresult == -6) {
			$msg= "�� Email �Ѿ���ע��";
		}else {
			$msg= "UCenter����ʧ��";
		}
		if($msg != "OK"){
			$this->base->message($msg,"BACK");
		}
	}
	
	function delete(){
		foreach($this->base->post["uid"] as $uid){
		$userarr = $_ENV["user"]->get_user("uid",$uid);
		$username=$userarr["username"];
			uc_user_delete($username);
		}
	}
	
	
	//���ֵĿ��ƣ������û����ͻ��֣����͵�ucenter��
	function send_credit($uid,$credit,$outextcredits){
		if((bool)$outextcredits){
			foreach($outextcredits as $outextcredit){
				$credit=intval($credit/$outextcredit["ratio"]);
				uc_credit_exchange_request($uid, $outextcredit["creditsrc"] , $outextcredit["creditdesc"] , $outextcredit["appiddesc"] , $credit);
				return $uid;
			}
		}
	}

	function postfeed($feed) {
		$feed['title_template'] = $feed['type']=='create' ? '<b>{actor} �� {app} �������´���</b>':'<b>{actor} �� {app} �༭�˴���</b>';
		$feed['body_template'] = '<b>{subject}</b><br />{message}';
		uc_feed_add($feed['icon'], $this->base->user['uid'], $this->base->user['username'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], '', '', $feed['images']);
	}

	//�����������uidͬ����ʱ��ʹ��
	function update_field($uid,$newuid) {
		$this->db->query("UPDATE ".DB_TABLEPRE."activation SET uid='$newuid' WHERE uid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."attachment SET uid='$newuid' WHERE uid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."creditdetail SET uid='$newuid' WHERE uid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."doc SET authorid='$newuid' WHERE authorid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."edition SET authorid='$newuid' WHERE authorid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."comment SET authorid='$newuid' WHERE authorid='$uid'");
	}
	
}
?>
