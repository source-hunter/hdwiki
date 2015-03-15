<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->load("language");
	}
	
	function dodefault(){
		//$langtag ҳ�����ص������ļ����
		$langtype = isset($this->get[2])?$this->get[2]:isset($this->post['langtype'])?$this->post['langtype']:'';
		$langtag = $langtype;
		if(!$langtype){
			$langtag = '0';
			$this->view->setlang($this->setting['lang_name'],'front');
		}
		//timeoffset����Ϊ���飬�����ͷ�
		unset($this->view->lang['timeoffset']);
		
		$keyword = !empty($this->post['keyword']) ? trim($this->post['keyword']) : '';
		if($keyword){
			$matches = array();
			$pattern='/(.*?)'.$keyword.'(.*?)/i';
			foreach($this->view->lang as $key=>$lang){
				if(is_string($lang)){
					if(preg_match($pattern,$lang)){
						$matches[$key]=$lang;
					}
				}
			}
		}else{
			foreach($this->view->lang as $key=>$lang){
				if(is_string($lang)){
					$matches[$key]=$lang;
				}
			}
		}
		$this->view->assign("langtype",$langtag);
		$this->view->assign("langtag",$langtag);
		$this->view->assign("lang",$matches);
		$this->view->display('admin_language');
	}
		
	function doeditlang(){
		switch($this->get[3]){
			case 0:
				$langname = 'front.php';
				break;
			case 1:
				$langname = 'back.php';
				break;
		}
		
		if(!$this->get[3]){
			template::setlang('zh','front');
			$this->view->lang = $this->lang;
		}
		
		$lang = array_merge($this->view->lang,$this->post['lang']);
		
		if(is_file(HDWIKI_ROOT.'/lang/zh/'.$langname)) {
			if(copy(HDWIKI_ROOT.'/lang/zh/'.$langname, HDWIKI_ROOT.'/lang/zh/bak_'.$langname)){
				$data ="<?php\r\n";
				foreach($lang as $key=>$value){
					$data.='$lang[\''.$key."']='".str_replace("'", "\'", str_replace("\\", "\\\\", stripslashes($value)))."';\r\n"; //ֻ��Ҫ��\���� \\, ��' ����\' ����
					$lang[$key]=$value;
				}
				if($this->get[3]==1){
					$data.='$lang[\'timeoffset\']'." = array(
							'-12'=>'(��׼ʱ-12:00) �ս�����',
							'-11'=>'(��׼ʱ-11:00) ��;������Ħ��Ⱥ��',
							'-10'=>'(��׼ʱ-10:00) ������',
							'-9'=>'(��׼ʱ-9:00) ����˹��',
							'-8'=>'(��׼ʱ-8:00) ̫ƽ��ʱ��(�����ͼ��ô�)',
							'-7'=>'(��׼ʱ-7:00) ɽ��ʱ��(�����ͼ��ô�)',
							'-6'=>'(��׼ʱ-6:00) �в�ʱ��(�����ͼ��ô�)��ī�����',
							'-5'=>'(��׼ʱ-5:00) ����ʱ��(�����ͼ��ô�)�������',
							'-4'=>'(��׼ʱ-4:00) ������ʱ��(���ô�)��������˹',
							'-3.5'=>'(��׼ʱ-3:30) Ŧ����',
							'-3'=>'(��׼ʱ-3:00) ����������ŵ˹����˹�����ζ�',
							'-2'=>'(��׼ʱ-2:00) �д�����',
							'-1'=>'(��׼ʱ-1:00) ���ٶ�Ⱥ������ý�Ⱥ��',
							'0'=>'(�������α�׼ʱ) ��ŷʱ�䡢�׶ء�����������',
							'1'=>'(��׼ʱ+1:00) ��ŷʱ�䡢��������������',
							'2'=>'(��׼ʱ+2:00) ��ŷʱ�䡢���ޣ��ŵ�',
							'3'=>'(��׼ʱ+3:00) �͸������ء�Ī˹��',
							'3.5'=>'(��׼ʱ+3:30) �º���',
							'4'=>'(��׼ʱ+4:00) �������ȡ���˹���ء��Ϳ�',
							'4.5'=>'(��׼ʱ+4:30) ������',
							'5'=>'(��׼ʱ+5:00) Ҷ�����ձ�����˹������������',
							'5.5'=>'(��׼ʱ+5:30) ���򡢼Ӷ������µ���',
							'6'=>'(��׼ʱ+6:00) ����ľͼ�� �￨�����ǲ�����',
							'7'=>'(��׼ʱ+7:00) ���ȡ����ڡ��żӴ�',
							'8'=>'(��׼ʱ+8:00)���������졢��ۡ��¼���',
							'9'=>'(��׼ʱ+9:00) ���������ǡ����桢�ſ�Ŀ�',
							'9.5'=>'(��׼ʱ+9:30) �������¡������',
							'10'=>'(��׼ʱ+10:00) Ϥ�ᡢ�ص�',
							'11'=>'(��׼ʱ+11:00) ��ӵ���������Ⱥ��',
							'12'=>'(��׼ʱ+12:00) �¿���������١�����Ӱ뵺');\r\n";
				}
				$data.="?>";
				file::writetofile(HDWIKI_ROOT.'/lang/zh/'.$langname,$data);
			}
		}
		file::cleardir(HDWIKI_ROOT.'/data/cache');
		file::cleardir(HDWIKI_ROOT.'/data/view');		
		$this->view->assign("langtype",$this->get[3]);
		$this->view->assign("langtag",$this->get[3]);
		$this->view->assign("lang",$lang);
		$this->message('�����ļ��޸ĳɹ�!','index.php?admin_language-default-'.$this->get[3]);
	}
	
	function doaddlang(){
		switch ($this->post['addlangtype']){
			case 0:
				$langname = 'front.php';
				break;
			case 1:
				$langname = 'back.php';
				break;
		}
		$langcon = trim($this->post['langcon']);
		$langvar = trim($this->post['langname']);
		if(!$langcon || !$langvar){
			$this->message('���Ա������ݲ���Ϊ��!','index.php?admin_language');
		}

		if(is_file(HDWIKI_ROOT.'/lang/zh/'.$langname)) {
			$filelang = substr($langname,0,-4);
			$this->view->setlang($this->setting['lang_name'],$filelang);
			if(array_key_exists($langvar,$this->view->lang)){
				$this->message('ģ��������Ѵ���,��������д!','index.php?admin_language');
			}
			if(copy(HDWIKI_ROOT.'/lang/zh/'.$langname, HDWIKI_ROOT.'/lang/zh/bak_'.$langname)){
				$data = file::readfromfile(HDWIKI_ROOT.'/lang/zh/'.$langname);
				$con = '$lang[\''.$langvar."']='".str_replace("'", "\'", str_replace("\\", "\\\\", stripslashes($langcon)))."';\r\n?>";
				$content = str_replace('?>',$con,$data);
				file::writetofile(HDWIKI_ROOT.'/lang/zh/'.$langname,$content);
			}
		}
		$langtype=$this->post['addlangtype'];
		$this->message('�����ļ���ӳɹ�!','index.php?admin_language-default-'.$langtype);
	}
	
	/*
	function dodefault(){
		$languagelist=$_ENV['language']->get_all_list();
		$this->view->assign("languagelist",$languagelist);
		$this->view->assign("lang_name",$this->setting['lang_name']);
		$this->view->display('admin_lang');
	}
	
	function doaddlanguage(){
		$addlanguage['addlangname']=trim($this->post['addlangname']);
		$addlanguage['addlangpath']=trim($this->post['addlangpath']);
		$addlanguage['addlangcopyright']=trim($this->post['addlangcopyright']);
		if($addlanguage['addlangname']==''||$addlanguage['addlangpath']==''||$addlanguage['addlangcopyright']==''){
			$this->message($this->view->lang['langConNull'],'index.php?admin_language');
		}
		$langname=$_ENV['language']->add_check_language($addlanguage);
		if($langname){
			$this->message($this->view->lang['langFileExist'],'index.php?admin_language');			
		}else{
			$_ENV['language']->add_language($addlanguage);
		}
		$this->cache->removecache('language');
		header("Location:index.php?admin_language");
	}
	
	function doremovelanguage(){
		$removelanguageid = isset($this->post['lang_id'])?$this->post['lang_id']:array();
		$this->load('setting');
		$lang_name=$this->setting['lang_name'];
		if(is_array($removelanguageid)){
			foreach($removelanguageid as $languageid){
				$lang=$this->db->fetch_by_field('language','id',$languageid);
				if($lang_name!=$lang['path']){
					$_ENV['language']->remove_language($languageid);
				}
			}
			$this->cache->removecache('language');
		}
		header("Location:index.php?admin_language");
	}
	
	function doupdatelanguage(){
		$languageids = isset($this->post["all_lang_id"])?$this->post["all_lang_id"]:array();
		if(is_array($languageids)){
			foreach($languageids as $id){
				$name = $this->post["lang_name_".$id];
				$path = $this->post["lang_path_".$id];
				$state = isset($this->post["lang_state_".$id])?1:0;
				$_ENV['language']->update_language($name,$path,$state,$id);
			}
			$this->cache->removecache('language');
		}
		header("Location:index.php?admin_language");
	}
	
	function dosetdefaultlanguage(){
		$langpath = "lang_path_".$this->post['lang_id'][0];
		$langfilepath = HDWIKI_ROOT.'/lang/'.$this->post[$langpath];
		if(is_dir($langfilepath)){
			$_ENV['language']->default_language($this->post[$langpath]);
			$this->cache->removecache('setting');
		}else{
			$this->message($this->view->lang['langFileNone'],'index.php?admin_language');
		}
		header("Location:index.php?admin_language");
	}
	*/
}
?>
