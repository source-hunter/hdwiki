<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class uploadmodel {

	var $db;
	var $base;

	function uploadmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$base->load('filemanager');
		$base->load('dir');
		$base->load('ftp');
	}
	var $savepath;
	var $alowexts;
	var $maxsize;
	var $overwrite;
	var $files = array();
	var $uploads = 0;
	var $uploadeds = 0;
	var $imageexts = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
	var $uploadedfiles = array();
	var $error;
	function upload($inputname, $savepath = '', $savename = '',  $maxsize = 0, $overwrite = 0,$isftp=0)
	{
		$alowexts = 'php|jpg|jpeg|gif|bmp|png|doc|docx|xls|ppt|pdf|txt|rar|zip';
		if(!isset($_FILES[$inputname]) && !is_array($_FILES)) return false;
		$savepath = str_replace("\\", '/', $savepath);
		$savepaths = $this->set_savepath($savepath);
		if($isftp==1){//$isftp�Ƿ�����ftp 1Ϊ�ǣ�0Ϊ�����������FTP�洢·����Ϊweb��Ŀ¼������ftp���Զ����
			$savepaths = str_replace("\\", '/', HDWIKI_ROOT);
		}
		$this->savename = $savename;
		$this->alowexts = $alowexts;
		$this->maxsize = $maxsize;
		$this->overwrite = $overwrite;
		$this->uploads = count($_FILES[$inputname]['name']);
		if(1 == $this->uploads)
		{
			$this->uploads = 1;
			$uploadfiles[0] = array('tmp_name' => $_FILES[$inputname]['tmp_name'], 'name' => $_FILES[$inputname]['name'], 'type' => $_FILES[$inputname]['type'], 'size' => $_FILES[$inputname]['size'], 'error' => $_FILES[$inputname]['error']);
		}
		else
		{
			foreach($_FILES[$inputname]['name'] as $key => $error) 
			{
				$uploadfiles[$key] = array('tmp_name' => $_FILES[$inputname]['tmp_name'][$key], 'name' => $_FILES[$inputname]['name'][$key], 'type' => $_FILES[$inputname]['type'][$key], 'size' => $_FILES[$inputname]['size'][$key], 'error' => $_FILES[$inputname]['error'][$key], 'description'=>$description[$key]);
			}
			
		}
		if(!is_dir($savepaths) && !mkdir($savepaths, 0777))
		{
			$this->error = 8;
			return false;
		}

		@chmod($savepaths, 0777);
		if(!is_writeable($savepaths) && ($savepaths != '/'))
		{
			$this->error = 9;
			return false;
		}
		$this->files = $uploadfiles;
		return $this->files;
	}

	function up($isftp,$dst_dir='/')
	{
		if(empty($this->files)) return false;
		foreach($this->files as $k=>$file)
		{
			$fileext = $_ENV['filemanager']->fileext($file['name']);
			if(!preg_match("/^(".$this->alowexts.")$/", $fileext))
			{
				$this->error = 10;
				return false;
			}
			if($this->maxsize && $file['size'] > $this->maxsize)
			{
				$this->error = 11;
				return false;
			}
			if(!$this->isuploadedfile($file['tmp_name']))
			{
				$this->error = 12;
				return false;
			}
			$savename = $this->savename ? $this->savename : $file['name'];
			$savefile = $this->savepath.$savename;
			if($isftp==1){
				$savefile = str_replace("\\", '/', HDWIKI_ROOT)."/".$savename;
			}
			if(!$this->overwrite && file_exists($savefile)) continue;
			//$isftp�Ƿ�����ftp 1Ϊ�ǣ�0Ϊ��
			if($isftp==1){
				$ftp_server=$this->base->setting['FTP_HOST'];
				$ftp_user=$this->base->setting['FTP_USER'];
				$ftp_passwd=$this->base->setting['FTP_PW'];
				if ($_ENV['ftp']->ftp($ftp_server)) {
					if ($_ENV['ftp']->login($ftp_user,$ftp_passwd)) {
						if($this->isuploadedfile($file['tmp_name'])){
							$src_dir = $file['tmp_name'];
							$dst_dir = $dst_dir .$savename;
							$_ENV['ftp']->ftp_copy($src_dir, $dst_dir);
							$this->uploadedfiles[] = array('saveto'=>$savefile, 'filename'=>$file['name'], 'filepath'=>$filepath, 'filetype'=>$file['type'], 'filesize'=>$file['size'], 'fileext'=>$fileext, 'description'=>$file['description']);
						}
					} else {
						$error="login failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
					}
					$_ENV['ftp']->disconnect();
				}else{
					$error="connection failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
				}
				if($error!="")
					$this->message($error,'index.php?admin_fileftpmanage-default');
			}else{
				//δ������Ĭ��Ϊphp�����ϴ�
				if(move_uploaded_file($file['tmp_name'], $savefile) || @copy($file['tmp_name'], $savefile))
				{
					$this->uploadeds++;
					@chmod($savefile, 0644);
					@unlink($file['tmp_name']);
					$this->uploadedfiles[] = array('saveto'=>$savefile, 'filename'=>$file['name'], 'filepath'=>$filepath, 'filetype'=>$file['type'], 'filesize'=>$file['size'], 'fileext'=>$fileext, 'description'=>$file['description']);
				}
			}
		}
		return $this->uploadedfiles;
	}

	
	/**
     * ���ñ���·��
     * @param string �ļ�����·������ "/" ��β
     */
    function set_savepath($savepath)
    {
		$savepath = str_replace("\\", "/", $savepath);
	    $savepath = substr($savepath,-1)=="/" ? $savepath : $savepath."/";
        $this->savepath = $savepath;
		return $this->savepath;
    }

	function isuploadedfile($file)
	{
		return is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file));
	}

	function error()
	{
		$UPLOAD_ERROR = array(0 => '�ļ��ϴ��ɹ�',
							  1 => '�ϴ����ļ������� php.ini �� upload_max_filesize ѡ�����Ƶ�ֵ',
							  2 => '�ϴ��ļ��Ĵ�С������ HTML ���� MAX_FILE_SIZE ѡ��ָ����ֵ',
							  3 => '�ļ�ֻ�в��ֱ��ϴ�',
							  4 => 'û���ļ����ϴ�',
							  5 => '',
							  6 => '�Ҳ�����ʱ�ļ��С�',
							  7 => '�ļ�д����ʱ�ļ���ʧ��',
							  8 => '����Ŀ¼�������ɹ�',
							  9 => '����Ŀ¼û��д��Ȩ��',
							  10 => '�������ϴ��������ļ�',
							  11 => '�ļ������˹���Ա�޶��Ĵ�С',
							  12 => '�Ƿ��ϴ��ļ�',
							  13 => '����ͬ���ļ�',
							 );
		return $UPLOAD_ERROR[$this->error];
	}
}
?>