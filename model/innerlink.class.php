<?php
/*
* ����URL�����࣬��������index.php?doc-innerlink-title ����ʽ����Ϊ��SEO����һ�µ���ʽ��
*/
!defined('IN_HDWIKI') && exit('Access Denied');

class innerlinkmodel {
	
	var $db;
	var $base;
	var $re;
	var $titles=array();

	function innerlinkmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		//$this->re = '/[^\'\"]+?innerlink-([^\'\"]+)/';//��������Ч������
		
		//���ַ�ʽ�������ݵ��е�����������ǰ���ĳ���Ǻ����ĳ��������ǰ����ʱ��
		//���滻ǰ���������ʱ����Ҳ�Ѻ����Ǹ��������滻�����³�������http://domain/doc-view-60SD ����
		//$this->re = '/(?:http:[\/\w-\.]{8,80}|index\.php\?)doc-innerlink-([^\'\"]+)/';
		$this->re = '/[\'\"]?(?:http:[\/\w-\.]{8,80}|index\.php\?)doc-innerlink-([^\'\"]+)[\'\"]?/';
	}
	
	/*
	* ��������ʾ֮ǰ���ô˷���
	*/
	function get($did, &$content){
		
		//�����ݿ��ȡ�ô��������������Ϣ
		$rows=$this->db->get_array("SELECT title,titleid FROM ".DB_TABLEPRE."innerlinkcache WHERE did='$did'");
		$rows2=array();
		if(!empty($rows)){
			foreach($rows as $i=> $row){
				$rows2[ $row['title'] ] = $row['titleid'];
			}
		}
		$rows = $rows2;
		
		//�����ݵ��з����������������浽���� $matchs ����
		preg_match_all($this->re, $content, $matchs);
		
		//�����ݵ��е����������ݿ������������бȽϣ������ݵ��е��������ŵ� $new_titles ���С�
		$new_titles=array();
		
		if($matchs){
			//��PHP4.3�汾���� foreach ��䵱��ʹ�� & ���÷��ŵ����﷨����
			foreach($matchs[1] as $i=>$title){
				$title2=trim(urldecode($title));
				if('gbk' == strtolower(WIKI_CHARSET)) {$title2 = string::hiconv($title2,'gbk','utf-8');}
				$title2=addslashes($title2);
				
				if(isset($rows[$title2])){//titleid
					$this->titles[$title]=array($rows[$title2], $matchs[0][$i]);
				}else{ 
					if(!in_array($title2, $new_titles)) $new_titles[]=$title2;
					$this->titles[$title]=array(0, $matchs[0][$i]);
				}
			}
		}
		//���³��ֵ��������浽���ݿ⵱��
		if(!empty($new_titles)){
			$this->save($did, $new_titles);
		}
		
		//�޸�����URL
		return $this->change($did, $content);
	}
	
	//�����ݵ��е��������д���
	function change($did, $content){
		$setting = $this->base->setting;
		foreach($this->titles as $title=>$row){
			if($row[0] == -1){
				$content = str_replace($row[1], WIKI_URL.'/?doc-innerlink-'.$title, $content);
			}elseif($row[0]){
				//�������ڣ�����SEO���ý�����Ӧ����
				if($setting['seo_type_doc'] && $setting['seo_type']){
					//ʹ��title��rewrite
					$content = str_replace($row[1], WIKI_URL.'/wiki/'.$title, $content);
				}else if($setting['seo_type']){
					//ʹ��did��rewrite
					$content = str_replace($row[1], WIKI_URL.'/doc-view-'.$row[0].$setting['seo_suffix'], $content);
				}else{
					//��֧��rewrite
					$content = str_replace($row[1], WIKI_URL.'/'.$setting['seo_prefix'].'doc-view-'.$row[0].$setting['seo_suffix'], $content);
				}
			}else{
				//����������
				$content = str_replace($row[1], "javascript:innerlink('$title')", $content);
			}
		}
		
		return $content;
	}
	
	//�������ݿ⣬������ $this->titles
	function save($did, &$titlelist){
		if(empty($titlelist)){
			return;
		}
		//�����б��ͬ����б�
		$doclist = $this->_getdoc($titlelist);
		$synonymlist = $this->_getsynonym($titlelist);
		$sql = "insert INTO ".DB_TABLEPRE."innerlinkcache (`did`,`title`,`titleid`)VALUES";
		$data=array();
		foreach($titlelist as $key=>$title){
			$title2 = urlencode($title);
			$titleid=isset($doclist[$title2]) ? $doclist[$title2]:0;
			if(!$titleid){
				$titleid = isset($synonymlist[$title2]) ? -1 : 0;
			}
			$data[]="('$did','$title','$titleid')";
			
			$title2= ('gbk'==strtolower(WIKI_CHARSET))?string::hiconv($title,'utf-8','gbk'): $title;
			$title2=urlencode($title2);
			$this->titles[$title2][0]=$titleid;
		}
		
		$sql .= implode(',', $data);
		
		$this->db->query($sql);
	}

	/**
	 * ȡ����
	 * @param array $titlelist
	 */
	function _getdoc($titlelist){
		$titles = implode("','", $titlelist);
		//�����ݿ��ѯ�Ƿ����
		$rows=$this->db->get_array("select did, title from  ".DB_TABLEPRE."doc where title in('$titles')");
		if(!empty($rows)){
			$doclist = array();
			foreach($rows as $i=> $row){
				$doclist[ urlencode($row['title']) ] = $row['did'];
			}
		}
		return $doclist;
	}
	
	/**
	 * ȡͬ���
	 * @param array $titlelist
	 */
	function _getsynonym($titlelist){
		$titles = implode("','", $titlelist);
		//�����ݿ��ѯ�Ƿ����
		$rows = $this->db->get_array("select srctitle from  ".DB_TABLEPRE."synonym where srctitle in('$titles')");
		if(!empty($rows)){
			$doclist = array();
			foreach($rows as $i=> $row){
				$doclist[ urlencode($row['srctitle']) ] = -1;
			}
		}
		return empty($doclist) ? NULL : $doclist ;
	}
	
	/*
	* �ڴ�����������ʱ���ô˷������Ը��¶�Ӧ����������Ϣ
	*/
	function update($title, $titleid){
		$this->db->query("update ".DB_TABLEPRE."innerlinkcache set titleid='$titleid' where title='$title'");
	}
	
}
