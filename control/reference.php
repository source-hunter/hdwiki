<?php
/**
�����༭ҳ��Ĳο�����
*/
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load("reference");
		$this->load("user");
	}
	
	/**
	��ӡ��༭�ο�����
	�༭���������ϵ���add���У���$_ENV['reference']->add()ʵ�֣�
	��� $data ���а��� id ��Ϣ��ִ��edit����������ִ��add������
	*/
	function doadd(){
		if($this->get[2] == 'checkable'){
			if ($this->checkable('reference-add')){
				if($this->setting['doc_verification_reference_code']){
					exit('CODE');
				}else{
					exit('OK');
				}
			}else{
				exit('0');
			}
		}
		
		$data=$this->post['data'];
		$data['name'] = htmlspecialchars(string::stripscript($data['name']));
		$data['url'] = htmlspecialchars(string::stripscript($data['url']));
		//�����֤��
		if($this->setting['checkcode']!=3 && $this->setting['doc_verification_reference_code'] && strtolower($data['code']) != $_ENV['user']->get_code()){
			exit('code.error');
		}
		
		if (WIKI_CHARSET == 'GBK'){
			$data['name']=string::hiconv($data['name']);
		}
		
		if (empty($data['name'])){
			exit('0');
		}
		$insert_id = $_ENV['reference']->add($data);
		if (is_int($insert_id)){
			echo $insert_id;
		}else{
			echo $insert_id? '1':'0';
		}
	}
	
	/**
	ɾ���ο�����
	*/
	function doremove(){
		$id = $this->get[2];
		if(@is_numeric($id)){
			echo $_ENV['reference']->remove($id)?'1':'0';
		}else{
			echo '0';
		}
	}
}
?>