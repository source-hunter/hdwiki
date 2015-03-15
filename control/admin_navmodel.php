<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->view->setlang($this->setting['lang_name'], 'back');
		$this->load("nav");
		$this->load("navmodel");
		$this->load("doc");
	}

	function dodefault(){
		$count = $_ENV['navmodel']->get_navmodel_num();
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage']) ? $this->setting['list_prepage'] : 20;
		$start_limit = ($page - 1) * $num;
		$departstr = $this->multi($count, $num, $page);
		$navmodellist = $_ENV['navmodel']->get_navmodel($start_limit, $num);
		$this->view->assign("navmodelnum", $count);
		$this->view->assign("departstr", $departstr);
		$this->view->assign("navmodellist", $navmodellist);
		$this->view->display('admin_navmodel');
	}
	
	/**
	 * ���
	 */
	function doadd(){
		if(!isset($this->post['submit'])){
			$navmodellist = $_ENV['navmodel']->get_all(array('status'=>1));
			$this->view->assign("navmodellist", $navmodellist);
			$this->view->display('admin_navmodeladd');
		}else{
			$name = string::stripscript(trim($this->post['name']));
			if(!$name){
				$this->message('JAVASCRIPT�ű��ᱻ����,���˺����Ʋ���Ϊ��!','BACK');
			}
			$code = trim($this->post['content']);
			if($_ENV['navmodel']->get_by_navname($name)){
				$this->message('�õ���ģ��ģ�������Ѿ�����!','BACK');
			}
			$nav = array('name'=>$name, 'code'=>$code, 'status'=>1);
			$_ENV['navmodel']->add($nav);
			$this->message('��ӳɹ�','index.php?admin_navmodel');
		}
	}
	
	/**
	 * ȡ����ģ������
	 */	
	function dogetmodel(){
		$modelid = intval($this->post['id']);
		$model = $_ENV['navmodel']->get_by_id($modelid);
		if($model){
			$code = $model['code'];
		}
		$this->message($code,'',2);	
	}
	
	/**
	 * ɾ��
	 */	
	function dodel(){
		$navmodelid = intval(($this->get['2']));
		$_ENV['navmodel']->del($navmodelid);
		$this->message('ɾ���ɹ�','index.php?admin_navmodel');
	}
	
	/**
	 * ����״̬
	 */	
	function dostatus(){
		$id = intval(($this->post['id']));
		$status = intval(($this->post['status']));
		$_ENV['navmodel']->update(array('status'=>$status), array('id'=>$id));
		$this->message('���³ɹ�', '', '2');
	}
}
?>