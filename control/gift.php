<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('gift');
		$this->load('user');
	}

	/*��Ʒ�̵����*/
	function dodefault(){
		if($this->setting['gift_close']!='0' && $this->setting['gift_close']!=''){
			header('Location:'.WIKI_URL);
			exit;
		}

		/*��ȡ�۸�����*/
		$gift_range=unserialize($this->setting['gift_range']);
		$minprice=array_keys($gift_range);
		$maxprice=array_values($gift_range);
		$this->view->assign("minprice",$minprice);
		$this->view->assign("maxprice",$maxprice);
		/*��ȡ��Ʒ�б�*/
		$beginprice = $endprice = '';
		if(isset($this->get[2]) && $this->get[2]!==''){
			$beginprice =intval($this->get[2]); //�۸���ʼֵ
			$endprice =$gift_range[$beginprice];//�۸����ֵ
			$total=$this->db->fetch_total('gift'," available=1 AND credit BETWEEN  $beginprice AND  $endprice ");//����Ʒ��¼��
		}else{
			$total=$this->db->fetch_total('gift','available=1');//����Ʒ��¼��
		}
		$this->get[3] = empty($this->get[3]) ? 0 : $this->get[3];
		$page = max(1, intval($this->get[3])); //��ǰҳ��
		$limit=10;//ÿҳ��ʾ��
		$page=($page - 1) * $limit>$total?1:$page;
	 	$start_limit = ($page - 1) * $limit;
		$giftlist=$_ENV['gift']->get_list($title='',$beginprice ,$endprice ,$begintime='',$endtime='',$start_limit,$limit);
		/*��ҳ�ַ���*/
		$departstr=$this->multi($total, $limit, $page,'gift-default-'.$beginprice);
		$this->view->assign('giftlist',$giftlist);
		$this->view->assign('departstr',$departstr);
		$this->view->assign('page',$page);
 		/*���¶һ���̬*/
		$loglist=$_ENV['gift']->get_loglist();
		$this->view->assign('loglist',$loglist);
	//	$this->view->display('giftlist');
		$_ENV['block']->view('giftlist');
	}

	/*��Ʒ����*/
	function doapply(){
		/*��ȡ�û��ύ����*/
		$gid =$this->post['gid']; //��Ʒid
		$gid = is_numeric($gid) ? $gid : 0;
		$truename =htmlspecialchars($this->post['truename']);
		$telephone =floatval($this->post['telephone']);
		$email =htmlspecialchars($this->post['email']);
		$location =htmlspecialchars($this->post['location']); //�û���ַ
		$postcode =intval($this->post['postcode']); //�ʱ�
		$extra =htmlspecialchars($this->post['extra']); //��ע��Ϣ
		$qq =floatval($this->post['qq']);

		$gift=$_ENV['gift']->get($gid);//��ǰ��Ʒ
		if(!$gift) {
			$this->message($this->view->lang['gifNotExists'],'BACK',0);
		}
		/*�����û����������*/
		$_ENV['user']->update_extra($this->user['uid'],$truename,$telephone,$email,$location,$postcode,$qq);
		/*�Զ��۳�credit1 �����������������������������ʾ������ִֹ������*/
		if($gift['credit']>$this->user['credit1']){
			$this->message($this->view->lang['goldNotEnoughCheckGift'],'BACK',0);
		}
		$_ENV['user']->update_field('credit1',-$gift['credit'],$this->user['uid'],0); //�۳���ǰ�õĽ����
		/*����������Ʒ���¼ giftlog*/
		$_ENV['gift']->addlog($gid,$this->user['uid'],$extra);
		/*��ʾ����ɹ��������ĵȴ�����Ա����*/
		$this->message($this->view->lang['checkSuccess'],'BACK',0);
	}

}
?>