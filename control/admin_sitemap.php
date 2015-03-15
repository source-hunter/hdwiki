<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{
	
	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('sitemap');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	

	function dosetting() {
		if(!isset($this->post['submit'])){
			$sitemap_config=unserialize(isset($this->setting['sitemap_config']) ? $this->setting['sitemap_config'] : '');
			$sitemap_config['auto_baiduxml'] = $this->setting['auto_baiduxml'];
			$this->view->assign('config',$sitemap_config);
 			$this->view->display("admin_sitemap_setting");
 		}else{
 			$setting['sitemap_config']=serialize($this->post['sitemap_conf']);
 			$setting['auto_baiduxml'] = $this->post['auto_baiduxml'];
 			$this->load('setting');
 			$setting=$_ENV['setting']->update_setting($setting);
 			$this->cache->removecache('setting');
 			$this->message('�����ɹ�','BACK');
 		}
	}

	function dodefault() {
		$this->view->assign('baidu_update', $_ENV['sitemap']->get_last_update('baidu.xml') ? $this->date($_ENV['sitemap']->get_last_update('baidu.xml')) : '��δ����');
		$this->view->assign('sitemap_update', $_ENV['sitemap']->get_last_update(HDWIKI_ROOT.'/data/sitemap_last_page.log') ? $this->date($_ENV['sitemap']->get_last_update(HDWIKI_ROOT.'/data/sitemap_last_page.log')) : '��δ����');
		$this->view->display('admin_sitemap');
	}
	
	function docreatedoc() {
		$_ENV['sitemap']->rebuild();
		$this->message('�����ؽ�����Sitemap�����Ժ�','index.php?admin_sitemap-updatedoc');
	}
	
	function doupdatedoc() {
		if(($next_offset = $_ENV['sitemap']->create_doc_page()) !== false) {
			$next_offset ++;
			$end_offset = $next_offset + 999;
			$this->message("����ɵ�{$next_offset}-{$end_offset}�������ڼ��������Ժ�",'index.php?admin_sitemap-updatedoc');
		} else {
			$this->message('Sitemap�����ļ������ɡ�ȫ������ɡ�', 'index.php?admin_sitemap');
		}
	}
	
	function dosubmit() {
		$rs = $_ENV['sitemap']->submit();
		if($rs === false) {
			$this->message('��Ӧ��sitemap�����ڣ�����ˢ��sitemap���ύ', 'index.php?admin_sitemap');
		} else {
			$message = '';
			foreach ($rs as $site=>$response) {
				if(strpos($response, '200') !== false) {
					$message .= $site.' �ύ�ɹ�������״̬��'.$response.'<br />';
				} else {
					$message .= $site.' �ύʧ�ܣ�����״̬��'.$response.'<br />';
				}
			}
				
			$this->message($message, 'index.php?admin_sitemap');
		}
	}
	
	function dobaiduxml() {
		$_ENV['sitemap']->create_baiduxml();
		$this->message('�����ɹ�', 'index.php?admin_sitemap');
	}
}

?>
