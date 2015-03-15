<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class giftmodel {

	var $db;
	var $base;

	function giftmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	/*�õ���Ʒ�б�*/
	function get_list($title='',$startprice='',$endprice='',$starttime='',$endtime='',$start_limit=0,$limit=10,$available=1){
		$giftlist = array();
		$sql="SELECT * FROM ".DB_TABLEPRE."gift WHERE 1=1  ";
		if($title){
			$sql=$sql." AND title LIKE '%{$title}%' ";
		}
		//��ʼ������Ǯ 0 ����ͨ��
		if($startprice !== ''){
			$sql=$sql." AND credit BETWEEN  $startprice AND  $endprice ";
		}
		if($starttime){
			$sql=$sql." AND time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND time<=$endtime ";
		}
		if(0===$available||1===$available){
			$sql=$sql." AND available=$available ";
		}
		$sql=$sql." ORDER BY time DESC LIMIT $start_limit,$limit ";
		$query = $this->db->query($sql);
		while($gift = $this->db->fetch_array($query)){
			$gift['addtime'] = $this->base->date($gift['time']);
			$gift['shorttitle'] = string::substring($gift['title'],0,8);;
			$giftlist[] = $gift;
		}
		return $giftlist;
	}

	/*��ȡ������Ʒ��Ϣ*/
	function get($id){
		$id = is_numeric($id) ? $id : 0;
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."gift WHERE id =".$id);
	}

	/*�����Ʒ*/
	function add($title,$image,$credit,$description=''){
		$this->db->query("INSERT INTO ".DB_TABLEPRE."gift (title,image,credit,description,time) VALUES ('$title','$image','$credit','$description','".$this->base->time."')");
	}

	/*�޸���Ʒ*/
	function edit($id,$title, $credit, $description='', $image=''){
		$id = is_numeric($id) ? $id : 0;
		$sql="UPDATE ".DB_TABLEPRE."gift SET title = '$title',credit='$credit', time = '".$this->base->time."'";
		if($description)$sql.=", description ='$description'";
		if($image)$sql.=", image ='$image'";
		$sql.="  WHERE id = ".$id;
		$this->db->query($sql);
	}

	/*�����Ʒ�һ���¼*/
	function addlog($gid,$uid,$extra=''){
		$gid = is_numeric($gid) ? $gid : 0;
		$uid = is_numeric($uid) ? $uid : 0;
		//��Ʒ���ֶ�status=0����ʾ�����У��ȴ�����Ա���
		//��Ʒ���ֶ�status=1����ʾ�Ѿ�ͨ�����룬�������ڼ�����
		$this->db->query("INSERT INTO ".DB_TABLEPRE."giftlog (gid,uid,extra,time) VALUES ('$gid','$uid','$extra','".$this->base->time."')");
	}

 	/*�õ���Ʒ�һ���¼�б�*/
	function get_loglist($title='',$username='',$startprice='',$endprice='',$starttime='',$endtime='',$start_limit=0,$limit=20){
		$loglist = array();
		$sql="SELECT l.id,g.title,g.image,u.uid,u.username,u.truename,u.location,u.postcode,u.telephone,u.qq,u.email,l.extra,l.time,l.status FROM ".DB_TABLEPRE."gift g,".DB_TABLEPRE."giftlog l,".DB_TABLEPRE."user u  WHERE g.id=l.gid and u.uid=l.uid  ";
		if($title){
			$sql=$sql." AND g.title LIKE '%{$title}%' ";
		}
		if($username){
			$sql=$sql." AND u.username LIKE '%{$username}%' ";
		}
		if($startprice){
			$sql=$sql." AND g.credit BETWEEN  $startprice AND  $endprice ";
		}
		if($starttime){
			$sql=$sql." AND l.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND l.time<=$endtime ";
		}

		$sql=$sql." ORDER BY l.time DESC LIMIT $start_limit,$limit ";
		$query = $this->db->query($sql);
		while($giftlog = $this->db->fetch_array($query)){
			$giftlog['time'] = $this->base->date($giftlog['time']);
			$giftlog['title'] = string::substring($giftlog['title'],0,10);
			$giftlog['location'] = strip_tags($giftlog['location']);
			$giftlog['truename'] = strip_tags($giftlog['truename']);
			$giftlog['extra'] = strip_tags($giftlog['extra']);
			$giftlog['email'] = strip_tags($giftlog['email']);
			$giftlog['postcode'] = intval($giftlog['postcode']);
			$giftlog['telephone'] = floatval($giftlog['telephone']);
			$giftlog['qq'] = floatval($giftlog['qq']);
			$loglist[] = $giftlog;
		}
		return $loglist;
	}

	/*ɾ����Ʒ�����浽����վ*/
	function remove($ids){
		$idsql = implode($ids,',');
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."gift WHERE id IN ($idsql)");
		while($gift=$this->db->fetch_array($query)){
			$sqladd.=" ('gift','".$gift['title']."','".addslashes(serialize($gift))."','N;','".$this->base->user['uid']."','".$this->base->user['username']."','".$this->base->time."'),";
		}
		$this->db->query("INSERT INTO ".DB_TABLEPRE."recycle (type,keyword,content,file,adminid,admin,dateline) values ".substr($sqladd,0,-1));
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."gift WHERE id IN ($idsql) ");
	}

	/*�ָ���Ʒ*/
	function recover($data){
		$data=string::haddslashes($data,1);
		$this->db->query("INSERT INTO  ".DB_TABLEPRE."gift (id,title,image,credit,description,time,available)
					VALUES ('".$data['id']."','".$data['title']."','".$data['image']."','".$data['credit']."','".$data['description']."','".$data['time']."','".$data['available']."')");
	}



}


?>