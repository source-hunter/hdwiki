<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	
	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('gift');
		$this->load('setting');
		$this->view->setlang($this->setting['lang_name'],'back');
	}

	function dodefault(){
 		$this->dosearch();
	}

	
	/*��Ʒ����(��̨����)*/
	function dosearch(){
		$title=isset($this->post['title'])?$this->post['title']:$this->get[2];//��Ʒ����
		$type=isset($this->post['type'])?$this->post['type']:$this->get[3];//�۸�����
		$qstarttime=isset($this->post['qstarttime'])?strtotime($this->post['qstarttime']):(int)$this->get[4];
		$endtime=isset($this->post['endtime'])?strtotime($this->post['endtime']):(int)$this->get[5];
		/*��ȡ�۸�����*/
		$gift_range=array();
		$gift_range=unserialize($this->setting['gift_range']);
		$minprice=array_keys($gift_range);
		$maxprice=array_values($gift_range);
		$this->view->assign("minprice",$minprice);
		$this->view->assign("maxprice",$maxprice);
		$startprice = $endprice = '';
		if(-1!=$type && $type !=NULL){
			$startprice = $minprice[$type]; //�۸���ʼֵ
			$endprice = $maxprice[$type];//�۸����ֵ
		}
		$page = max(1, intval($this->get[6])); //��ǰҳ��
		$total=$this->db->fetch_total('gift','1');//����Ʒ��¼��
		$limit=20;//ÿҳ��ʾ��
	 	$start_limit = ($page - 1) * $limit;
		$giftlist=$_ENV['gift']->get_list($title,$startprice,$endprice,$qstarttime,$endtime,$start_limit,$limit,3);		
		/*��ҳ�ַ���*/
		$departstr=$this->multi($total, $limit, $page,"admin_gift-search-$title-$type-$starttime-$endtime");
		$this->view->assign('total',$total);
		$this->view->assign('giftlist',$giftlist);
		$this->view->assign('departstr',$departstr);
		$this->view->assign('page',$page);
		$titles=stripslashes($title);

		$this->view->assign("title",$titles);
		$this->view->assign("type",$type);
		$this->view->assign("qstarttime",$qstarttime?date("Y-m-d",$qstarttime):"");
		$this->view->assign("endtime",$endtime?date("Y-m-d",$endtime):"");

		$this->view->display('admin_gift');
	}
	
	/*�����Ʒ(��̨����)*/
	function doadd(){
		if(!isset($this->post['submit'])){
			$this->view->display('admin_addgift');
		}else{
			$title = htmlspecialchars(string::haddslashes(string::hiconv(trim($this->post['title']))));
			$credit = trim($this->post['credit']);
			$description = htmlspecialchars(string::haddslashes(string::hiconv(trim($this->post['description']))));
			$imgname=$_FILES['giftfile']['name'];
			$extname=file::extname($imgname);
			$destfile = 'uploads/gift/'.util::random(8).'.'.$extname;
			$uploadreturn = file::uploadfile($_FILES['giftfile'],$destfile);
			
			util::image_compress($destfile,'',500,500,'');
			$iamge=util::image_compress($destfile,'',106,106,'_s');
			
			$destfile=$iamge['tempurl'];
			
			if($uploadreturn['result'] === false){
				$this->message($uploadreturn['msg'],'index.php?admin_gift-search');
			}
			$_ENV['gift']->add($title, $destfile, $credit, $description);
			$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_gift-search');
		}
	}
	
	/*�༭��Ʒ(��̨����)*/
	function doedit(){
		if(!isset($this->post['submit'])){
			$id=$this->get[2];
			$gift=$_ENV['gift']->get($id);
			$this->view->assign("gift",$gift);
			$this->view->display('admin_editgift');
		}else{
			$id=trim($this->post['id']);
			$gift=$_ENV['gift']->get($id);
			$title = htmlspecialchars(trim($this->post['title']));
			$credit = trim($this->post['credit']);
			$description = htmlspecialchars(trim($this->post['description']));
			$imgname=$_FILES['giftfile']['name'];
			
			/*
			if($gift['image']){
				$destfile=str_replace('_s.', '.', $gift['image']);
			}else{
				$extname=file::extname($imgname);
				$destfile = 'uploads/gift/'.util::random(8).'.'.$extname;
			}
			*/
			
			if(''!=$imgname){
				$extname=file::extname($imgname);
				$destfile = 'uploads/gift/'.util::random(8).'.'.$extname;
				
				file::uploadfile($_FILES['giftfile'], $destfile);
				util::image_compress($destfile,'',500,500,'');
				$iamge=util::image_compress($destfile,'',106,106,'_s');
				$destfile=$iamge['tempurl'];
			}
			
			$_ENV['gift']->edit($id,$title, $credit, $description, $destfile);
			$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_gift-search');
		}
	}
	
	/*ɾ����Ʒ(��̨����)*/
	function doremove(){
		$chkid=$this->post['chkid'];
		$_ENV['gift']->remove($chkid);
		$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_gift-search');
	}
	
	
	
	/*������Ʒ״̬(��̨����)*/
	function doavailable(){
		$chkid=$this->post['chkid'];
		$available=$this->get[2];
		$ids=implode(',',$chkid);
		$this->db->update_field('gift','available',$available,"  id IN ($ids)  " );
		$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_gift-search');
	}
	
	
	/*��Ʒ�۸���������(��̨����)*/
	function doprice(){
		if(!isset($this->post['submit'])){
			$gift_range=array();
			$gift_range=unserialize($this->setting['gift_range']);
			$minprice=array_keys($gift_range);
			$maxprice=array_values($gift_range);
			$this->view->assign("minprice",$minprice);
			$this->view->assign("maxprice",$maxprice);
			$this->view->display('admin_giftprice');
		}else{
			$minprice=$this->post['minprice'];
			$maxprice=$this->post['maxprice'];
			for($i=0;$i<count($minprice);$i++){
				if(is_numeric($minprice[$i]))
					$arraymin[]=$minprice[$i];
			}
			for($i=0;$i<count($maxprice);$i++){
				if(is_numeric($maxprice[$i]))
					$arraymax[]=$maxprice[$i];
			}
			if(count($arraymin)>0 && count($arraymax)>0){
				if(count($arraymax)!=count($arraymin))
					$this->message($this->view->lang['usermanageOpterror'],'index.php?admin_gift-price');
				$gift_range=array_combine($arraymin,$arraymax);//һ��������������һ���������value
			}
			$setting['gift_range']=serialize($gift_range); //���л����ַ���
			$_ENV['setting']->update_setting($setting);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_gift-price');
		}
	}
	
	/*��Ʒ��������(��̨����)*/
	function donotice(){
		if(isset($this->post['submit'])){
			$setting=$this->post['setting'];
			$_ENV['setting']->update_setting($setting);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_gift-notice');
		}
		$this->view->display('admin_giftnotice');
	}
	
	/*�һ���Ʒ��־*/
	function dolog(){
		$title=isset($this->post['title'])?$this->post['title']:$this->get[2];//��Ʒ����
		$username=isset($this->post['username'])?$this->post['username']:$this->get[3];
		$type=isset($this->post['type'])?$this->post['type']:$this->get[4];//�۸�����
		$qstarttime=isset($this->post['qstarttime'])?strtotime($this->post['qstarttime']):(int)$this->get[5];
		$endtime=isset($this->post['endtime'])?strtotime($this->post['endtime']):(int)$this->get[6];
		/*��ȡ�۸�����*/
		$gift_range=unserialize($this->setting['gift_range']);
		$minprice=array_keys($gift_range);
		$maxprice=array_values($gift_range);
		$this->view->assign("minprice",$minprice);
		$this->view->assign("maxprice",$maxprice);
		if(-1!=$type){
			$startprice = $minprice[$type]; //�۸���ʼֵ
			$endprice = $maxprice[$type];//�۸����ֵ
		}
		$page = max(1, intval($this->get[7])); //��ǰҳ��
		$total=$this->db->fetch_total('giftlog','1');//����Ʒ��¼��
		$limit=20;//ÿҳ��ʾ��
	 	$start_limit = ($page - 1) * $limit;
		$loglist=$_ENV['gift']->get_loglist($title,$username,$startprice ,$endprice ,$qstarttime,$endtime,$start_limit,$limit);		
		/*��ҳ�ַ���*/
		$departstr=$this->multi($total, $limit, $page,"admin_gift-log-$title-$username-$type-$starttime-$endtime");
		$this->view->assign('total',$total);
		$this->view->assign('loglist',$loglist);
		$this->view->assign('departstr',$departstr);
		$this->view->assign('page',$page);
		$titles=stripslashes($title);
		$usernames=stripslashes($username);

		$this->view->assign("title",$titles);
		$this->view->assign("type",$type);
		$this->view->assign("username",$usernames);
		$this->view->assign("qstarttime",$qstarttime?date("Y-m-d",$qstarttime):"");
		$this->view->assign("endtime",$endtime?date("Y-m-d",$endtime):"");

		$this->view->display('admin_giftlog');
	}
	
	/*��Ϊ�Ѿ�����״̬*/
	function doverify(){
		$chkid=$this->post['chkid'];
		$names=$this->post['names'];
		$subject = '������Ʒ';
		$content = '���һ�����Ʒ�Ѽĳ�,��ע�����!';
		$sendarray = array(
			'sendto'=>$names,
			'subject'=>$subject,
			'content'=>$content,
			'isdraft'=>0,
			'user'=>$this->user
		);
		if($names){
			$this->load('pms');
			$_ENV['pms']->send_ownmessage($sendarray);
		}
		$ids=implode(',',$chkid);
		$this->db->update_field('giftlog','status',1,"  id IN ($ids)  " );
		$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_gift-log');
	}
	

}
?>
