<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class actionsmodel {
	var $db;
	var $base;
	
	function actionsmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getHTML($action, $text, $kws, &$num){
		$html=array();
		$action = explode(',', $action);
		$action = array_map('trim', $action);
		foreach($action as $i => $menu){
			if($i==0){
				$html[]="<a href='index.php?admin_{$this->index[$menu]}' onclick='go(this)'>".$this->data[$menu].'</a>';
			}else {
				if($i == 1){
					$text=$this->data[$action[0].', '.$menu];
				}else if($i == 2){
					$text=$this->data[$action[0].', '.$action[1].', '.$menu];
				}
				
				foreach($kws as $kw) {
					if(strpos(strtolower($text), strtolower($kw)) !== FALSE) {
						if(strpos(strtolower($text), strtolower(implode('', $kws))) !== FALSE){
							$num+=10;
						}
						
						$text = str_replace($kw, '<font color="#c60a00">'.$kw .'</font>', $text);
						$num++;
					}
				}
				
				$html[]="<a menu='$i' href='index.php?admin_$menu' onclick='go(this)'>$text</a>";
			}
			
		}
		
		return implode('&gt;&gt;', $html);
	}

	function getMap(){
		$outstr = $prenum = '';
		$text = array(1=>'</dl></li>',2=>'</dd>',3=>'</ul>');
		foreach($this->data as $key=>$value) { 
			if(preg_match("/-[0-9]+$/", $key)||in_array($key, $this->ignorelist)){
				continue;
			}
			$kws = explode(',', $key);
			$kws = array_map('trim', $kws);
			$num = count($kws);
			if($num==3){
				$outstr .=$prenum!=3?'<ul>':'';
			}else{
				$subnum = $prenum?$prenum-$num:-1;
				if($subnum >= 0){
					if($subnum==0){
						$outstr .= $text[$num];
					}elseif($subnum==1){
						$outstr .= $text[$num+1].$text[$num];
					}elseif($subnum==2){
						$outstr .= $text[$num+2].$text[$num+1].$text[$num];
					}
				}
			}
			switch($num){
				case 1:
					$outstr .= '<li><dl><dt><a href="index.php?admin_'.$this->index[$key].'">'.$value."</a></dt>";
					break;
				case 2:
					$outstr .= '<dd> <a href="index.php?admin_'.$kws[1].'">'.$value.'</a>';
					break;
				case 3:
					$outstr .= '<li><a href="index.php?admin_'.$kws[2].'">'.$value."</a></li>";
					break;
			}
			$prenum = $num;
		}
		for($i=1;$i<=$prenum;$prenum--){
			$outstr .= $text[$prenum];
		}
		return $outstr;
	}
	var $ignorelist = array('index', 'index, main-mainframe', 'global, setting-base');
	var $index = array(
		'index'=>'main-mainframe',
		'global'=>'setting-base',
		'user'=>'user',
		'content'=>'doc',
		'plug'=>'theme',
		'db'=>'db-backup',
		'unions'=>'hdapi',
		'moduls'=>'image',
		'stat'=>'statistics-stand'
	);
	
	var $data = array(
		'index'=>'��ҳ',
			'index, main-mainframe'=>'��ҳ��Ϣ',
				
		'global'=>'ȫ��',
			'global, setting-base'=>'վ������',
			'global, setting-base-1'=>'վ������ ��վ����',
			'global, setting-base-2'=>'վ������ ��վURL',
			'global, setting-base-3'=>'վ������ վ�ڹ���',
			'global, setting-base-4'=>'վ������ ��վ������Ϣ',
			'global, setting-base-5'=>'վ������ ������ͳ�ƴ���',
			'global, setting-base-6'=>'վ������ �������',
			'global, setting-base-7'=>'վ������ �Ƿ���Ҫ������ǰ�汾ģ��',
			'global, setting-base-8'=>'վ������ �ر���վ',
			'global, setting-base-9'=>'վ������ �ر�ԭ��',
			
			'global, channel'=>'��������',
				'global, channel, channel'=>'Ƶ������',
				'global, channel, setting-cache'=>'��������',
				'global, channel, setting-seo'=>'SEO����',
				'global, channel, setting-code'=>'��֤��',
				'global, channel, setting-time'=>'ʱ������',
				
				'global, channel, setting-time-1'=>'ʱ������ Ĭ��ʱ������',
				'global, channel, setting-time-2'=>'ʱ������ ����ʱ�����������ʱ���(����)',
				'global, channel, setting-time-3'=>'ʱ������ ��վ��ʾ���ڸ�ʽ',
				'global, channel, setting-time-4'=>'ʱ������ ��վ��ʾʱ���ʽ',
				
				'global, channel, setting-cookie'=>'COOKIE����',
				'global, channel, setting-credit'=>'����������',
				'global, channel, setting-logo'=>'LOGO����',

			
			'global, setting-sec'=>'��չ����',
				'global, setting-sec, setting-sec'=>'����ˮ����',
				'global, setting-sec, setting-sec-1'=>'����ˮ���� ����������������֤��',
				'global, setting-sec, setting-sec-2'=>'����ˮ���� �༭������������֤��',
				'global, setting-sec, setting-sec-3'=>'����ˮ���� ����ʱ��',
				
				'global, setting-sec, setting-anticopy'=>'���ɼ�����',
				'global, setting-sec, setting-mail'=>'�ʼ�����',
				'global, setting-sec, setting-noticemail'=>'�ʼ���������',
				'global, setting-sec, banned'=>'IP��ֹ',
				'global, setting-sec, setting-passport'=>'ͨ��֤����',
				'global, setting-sec, setting-ucenter'=>'UCenter����',
				 
			'global, setting-index'=>'��������',
				'global, setting-index, setting-index'=>'��ҳ����',
				'global, setting-index, setting-listdisplay'=>'�б�����',
				'global, setting-index, setting-watermark'=>'ͼƬ����',
				
				'global, setting-index, setting-watermark-1'=>'ͼƬ���� ͼƬ���ػ�',
				'global, setting-index, setting-watermark-2'=>'ͼƬ���� ������ͼ�ߴ�',
				'global, setting-index, setting-watermark-3'=>'ͼƬ���� ����Сͼ�ߴ�',
				'global, setting-index, setting-watermark-4'=>'ͼƬ���� ͼƬ���������',
				'global, setting-index, setting-watermark-5'=>'ͼƬ���� ImageMagick ����װ·��',
				'global, setting-index, setting-watermark-6'=>'ͼƬ���� ˮӡ',
				'global, setting-index, setting-watermark-7'=>'ͼƬ���� ˮӡ�������',
				'global, setting-index, setting-watermark-8'=>'ͼƬ���� ˮӡͼƬ����',
				'global, setting-index, setting-watermark-9'=>'ͼƬ���� ˮӡ�ں϶�',
				'global, setting-index, setting-watermark-10'=>'ͼƬ���� JPEG ˮӡ����',
				'global, setting-index, setting-watermark-11'=>'ͼƬ���� �ı�ˮӡ����',
				'global, setting-index, setting-watermark-12'=>'ͼƬ���� �ı�ˮӡ TrueType �����ļ���',
				'global, setting-index, setting-watermark-13'=>'ͼƬ���� �ı�ˮӡ�����С',
				'global, setting-index, setting-watermark-14'=>'ͼƬ���� �ı�ˮӡ������ɫ',
				'global, setting-index, setting-watermark-15'=>'ͼƬ���� �ı�ˮӡ��Ӱ����ƫ����',
				'global, setting-index, setting-watermark-16'=>'ͼƬ���� �ı�ˮӡ��Ӱ����ƫ����',
				'global, setting-index, setting-watermark-17'=>'ͼƬ���� �ı�ˮӡ��Ӱ��ɫ',
				'global, setting-index, setting-watermark-18'=>'ͼƬ���� �ı�ˮӡ����ƫ����(ImageMagick)',
				'global, setting-index, setting-watermark-19'=>'ͼƬ���� �ı�ˮӡ����ƫ����(ImageMagick)',
				'global, setting-index, setting-watermark-20'=>'ͼƬ���� �ı�ˮӡ������б�Ƕ�(ImageMagick)',
				'global, setting-index, setting-watermark-21'=>'ͼƬ���� �ı�ˮӡ������б�Ƕ�(ImageMagick)',

				'global, setting-index, setting-docset'=>'��������',
				'global, setting-index, setting-docset-1'=>'�������� ָ���༭ʵ�������ID',
				'global, setting-index, setting-docset-2'=>'�������� ��˴���',
				'global, setting-index, setting-docset-3'=>'�������� �Ƿ�������������ʽ��������',
				'global, setting-index, setting-docset-4'=>'�������� �Ƿ��ڱ༭���й����ⲿ����',
				'global, setting-index, setting-docset-5'=>'�������� �´��������±༭�����Ƿ񱣴�Ϊ��ʷ�汾',
				
				'global, setting-index, setting-search'=>'��������',
				'global, setting-index, setting-attachment'=>'��������',
				 
			'global, friendlink'=>'��������',
				'global, friendlink, friendlink'=>'���������б�',
				'global, friendlink, friendlink-add'=>'�����������',

			'global, adv'=>'������',
				'global, adv, adv-default'=>'������',
				'global, adv, adv-config'=>'���ù��',
				'global, adv, adv-add'=>'��ӹ��',
						
			'global, sitemap'=>'Sitemap',
				'global, sitemap, sitemap'=>'���²���',
				'global, sitemap, sitemap-setting'=>'��������',

			'global, upgrade'=>'�Զ�����',
				
		'user'=>'�û�����',
			'user, setting-baseregister'=>'ע������',
			'user, setting-baseregister-1'=>'ע������ �������û�ע��',
			'user, setting-baseregister-2'=>'ע������ �����߽�������ֵ',
			'user, setting-baseregister-3'=>'ע������ �������߽�������ֵ',
			'user, setting-baseregister-4'=>'ע������ �����ʼ�����',
			'user, setting-baseregister-5'=>'ע������ �����ʼ�����',
			'user, setting-baseregister-7'=>'ע������ �ر��û�ע���ԭ��',
			'user, setting-baseregister-8'=>'ע������ ��ֹע����û���',
			'user, setting-baseregister-9'=>'ע������ ���û��Ƿ������',
			'user, setting-baseregister-10'=>'ע������ ע���û�����С����',
			'user, setting-baseregister-11'=>'ע������ �û�����󳤶�',
			'user, setting-baseregister-12'=>'ע������ IP ע��������',
			'user, setting-baseregister-13'=>'ע������ ���ͻ�ӭ��Ϣ',
			'user, setting-baseregister-14'=>'ע������ ��ӭ��Ϣ����',
			'user, setting-baseregister-15'=>'ע������ ��ӭ��Ϣ����',

			
			'user, user'=>'�����û�',
				'user, user, user'=>'�û��б�',
				'user, user, user-uncheckeduser'=>'������û�',
				'user, user, user-add'=>'����û�',
				
			'user, regular-groupset'=>'����Ȩ��',
				'user, regular-groupset, regular-groupset-2'=>'����Ȩ��',
				'user, regular-groupset, regular'=>'Ȩ���б�',
			
			'user, usergroup'=>'�����û���',	
			
		'content'=>'���ݹ���',
			'content, category-list'=>'�������',
				'content, category-list, category-list'=>'�������',
				'content, category-list, category-add'=>'��ӷ���',
				'content, category-list, category-merge'=>'�ϲ�����',
				
			'content, doc'=>'��������',
				'content, doc, doc'=>'�������',
				'content, doc, focus-focuslist'=>'�Ƽ�����',
				'content, doc, synonym'=>'����ͬ���',
				'content, doc, relation'=>'��ش���',
				'content, doc, edition'=>'�汾����',
				'content, doc, cooperate'=>'�����ƴ���',
				
			'content, attachment'=>'��������',
			'content, comment'=>'���۹���',
			'content, tag-hottag'=>'���ű�ǩ',
			'content, hotsearch'=>'��������',
			'content, word'=>'�������',
			'content, datacall'=>'���ݵ���',
				'content, datacall, datacall'=>' �����б�',
				'content, datacall, datacall-addsql'=>'SQL����',
				
			'content, recycle'=>'����վ',
			
			
		'plug'=>'ģ��/���',
			'plug, theme'=>'ģ�����',
				'plug, theme, theme'=>'����Ĭ�Ϸ��',
				'plug, theme, theme-create'=>'�������',
				'plug, theme, theme-list'=>'���߰�װ',
				'plug, theme, theme-edit'=>'ģ��༭',
			'plug, plugin'=>'�������',
				'plug, plugin, plugin'=>'�Ѱ�װ���',
				'plug, plugin, plugin-will'=>'ȫ���Ƽ����',
				'plug, plugin, plugin-find'=>'�������в��',
			'plug, language'=>'��վ���Ա༭',
			
			
		'db'=>'���ݿ����',
			'db, db-backup'=>'���ݿⱸ��',
			'db, db-tablelist'=>'���ݿ��Ż�',
			'db, db-sqlwindow'=>'SQL��ѯ����',
			'db, db-storage'=>'���ݴ洢����',
				'db, db-storage, db-storage'=>'���ݴ洢����',
				'db, db-storage, db-convert'=>'����ת��',

		'unions'=>'�ٿ�����',
			'unions, hdapi'=>'������ҳ',
			'unions, hdapi-set'=>'������',
			'unions, share-set'=>'������֪��',
				'unions, share-set, share-set'=>'��������',
				'unions, share-set, share'=>'�ֶ�����',
			'unions, hdapi-down'=>'���ش���',
				'unions, hdapi-down, hdapi-down'=>'���ش���',
				'unions, hdapi-down, hdapi-nosynset'=>'��ͬ���б�',
			'unions, hdapi-info'=>'�޸���������',
			
		'moduls'=>'ģ��',
			'moduls, image'=>'ͼƬ�ٿ�',
			'moduls, gift'=>'��Ʒ�̵�',
				'moduls, gift, gift'=>'��Ʒ����',
				'moduls, gift, gift-add'=>'�����Ʒ',
				'moduls, gift, gift-price'=>'��Ʒ�۸�����',
				'moduls, gift, gift-notice'=>'��Ʒ����',
				'moduls, gift, gift-notice-1'=>'��Ʒ���� ��Ʒ�̵꿪��',
				'moduls, gift, gift-log'=>'��Ʒ�һ���־',
			'moduls, safe'=>'ľ��ɨ��',
				'moduls, safe, filecheck-create'=>'�����ļ�У�龵��',
				'moduls, safe, safe-list'=>'�ϴ�ɨ����',
			
		'stat'=>'վ��ͳ��',
			'stat, statistics-stand'=>'�����ſ�',
			'stat, statistics-cat_toplist'=>'��������',
			'stat, statistics-doc_toplist'=>'��������',
			'stat, statistics-edit_toplist'=>'�༭���а�',
			'stat, statistics-credit_toplist'=>'��������',
			'stat, statistics-admin_team'=>'�����Ŷ�',
			'stat, log'=>'��̨������¼'	
	);

}