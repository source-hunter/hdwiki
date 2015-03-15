<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class blockmodel {

	var $db;
	var $base;
	var $cachetime;

	function blockmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->cachetime = $this->base->setting['index_cache_time'];
	}

	function view($file){
		$theme=$GLOBALS['theme'];//�õ�ģ�������
		if($theme!='default' && !file_exists(HDWIKI_ROOT."/view/$theme/$file.htm")){
			$theme = 'default';
		}
		$blocklist=$this->load_block($theme,$file);//�����ļ������block�����ݿ���ȡ��������Ϊ���������ʽ��һ���ǰ�block����һ���ǰ���������
		$GLOBALS['blocklist']=$blocklist[1];//�������������е�����ȡ��������ȫ�ֱ������á�
		$cachename='data_'.$theme.'_'.$file;
		$blockdata=$this->base->cache->getcache($cachename,$this->cachetime);
		if(!is_array($blockdata)){
			$blockdata=array();
			foreach($blocklist[0] as $key=>$blocks){
				$filename=$_ENV['global']->block_file($theme,"/$key/$key.php");
				if(is_file($filename)){
					include_once $filename;
					$obj=new $key($this->base);
					foreach($blocks as $block){
						if($block['fun'] && method_exists ($obj, $block['fun'])){
							$block['params']=$block['params']?unserialize($block['params']):'';
							$blockdata[$block['id']]=$obj->$block['fun']($block['params']);
						}
					}
				}
			}
			$this->base->cache->writecache($cachename,$blockdata);
		}
		$GLOBALS['blockdata']=$blockdata;//���ؽ�block����õ������ݵ�ȫ�ֱ������á�
		$this->base->view->display($file);
	}
	
	function load_block($theme,$file){
		$cachename='block_'.$theme.'_'.$file;
		$cachedata=$this->base->cache->getcache($cachename,$this->cachetime);
		if(!is_array($cachedata)){
			$cachedata=array(array(),array());
			$sql="SELECT id,theme,fun,params,area,block,tpl FROM ".DB_TABLEPRE."block WHERE  theme='$theme' and file='$file' ORDER BY areaorder ASC";
			$query=$this->db->query($sql);
			if($query){
				while($data=$this->db->fetch_array($query)){
					//$cachedata[0][$data['block']][]=array_splice($data,0,3,$data['id']);
					$cachedata[0][$data['block']][]=$data;
					$cachedata[1][$data['area']][]=$data;
				}
				$this->base->cache->writecache($cachename,$cachedata);
			}
		}
		return $cachedata;
	}
	
}
?>
