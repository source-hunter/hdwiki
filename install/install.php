<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('IN_HDWIKI', TRUE);
define('HDWIKI_ROOT', '../');

$lang_name = 'zh';

require HDWIKI_ROOT."/lang/$lang_name/install.php";
require HDWIKI_ROOT.'/version.php';
require HDWIKI_ROOT.'/model/base.class.php';
$step = (isset ($_GET['step'])) ? $_GET['step'] : $_POST['step'];

if (file_exists(HDWIKI_ROOT.'/data/install.lock') && $step != '8') {
	echo "<font color='red'>{$lang['tipAlreadyInstall']}</font>";
	exit();
}


$dbcharset = $lang['commonDBCharset'];
header("Content-Type: text/html; charset={$lang['commonCharset']}");
$installfile = basename(__FILE__);
$configfile = HDWIKI_ROOT.'/config.php';
$logofile = HDWIKI_ROOT.'/style/default/logo.gif';

$sqlfile = HDWIKI_ROOT.'/install/hdwiki.sql';
if (!is_readable($sqlfile)) {
	exit ($strDBNoExists);
}

require HDWIKI_ROOT.'/install/install_func.php';
if (''==$step)
	$step = 1;
$arrTitle = array (
	"",
	$lang['commonLicenseInfo'],
	$lang['commonSystemCheck'],
	$lang['commonDatabaseSetup'],
	$lang['commonAdministratorSetup'],
	'�������ݱ�',
	$lang['commonInstallComplete']
);
$arrStep = range(0, 5);

$nextStep = $step +1;
$prevStep = $step -1;
if($step==3){
	$nextStep=$step;
	$prevStep=$step;
}
$nextAccess = 1;

$uploadsDir = HDWIKI_ROOT.'/uploads';
$dataDir = HDWIKI_ROOT.'/data';
$pluginDir =HDWIKI_ROOT.'/plugins';
$site_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-20);

//$post['domain']=$site_url;
//$post['step']=$step;
//$postquery = http_build_query($post);
$apiurl='http://localhost/count2/installlog.php';
$isone = false;
$extend = '';
//util::hfopen($apiurl,0,$postquery);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $lang['commonInstallTitle']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['commonCharset']?>">
<meta content="noindex, nofollow" name="robots">
<link rel="stylesheet" href="images/install.css" type="text/css" media="screen,projection" />
<script language="JavaScript" type="text/javascript">
	function selectlang(lang){
		var selectlang = document.getElementById(lang);
		var curstep = <?php echo $step?>;
		var langvalue = selectlang.options[selectlang.selectedIndex].value;
		window.location = "install.php?step="+curstep+"&lang="+langvalue;
	}
	
	function checkConfig(E){
		if(E.value) E.value = E.value.replace(/[^0-9a-z_]/gi, '');
	}
</script>
</head>
<body>
<div id="container">
<div id="header">
<div id="logo"></div>
<div id="topheader">
<p><strong>HDWiki V<?php echo HDWIKI_VERSION?> Release <?php echo HDWIKI_RELEASE?></strong></p>
<p><?php echo $lang['commonSetupLanguage'] ?>
<select id="lang" name="lang" onchange="selectlang('lang');">
	<option value="zh"<?php  if('zh' == $lang_name) { ?> selected="selected"<?php } ?>> <?php echo $lang['zh']?></option>
</select>
</p>
</div>
</div>
<div id="content-wrap">
<div id="menu">
<ul class="sidemenu">
<li class="navtitle"><?php echo $lang['commonSetupNavigate']?></li>
<?php
$steptotal = count($arrTitle);
for ($i = 1; $i < $steptotal; $i++) {
	if ($step >= $arrStep[$i]) {
		if($step==$i) {
			$href1 = "<li class=\"sidemenubg\">";
			$href2 = "</li>";
		}else{
			$href1 = "<li><a href='$installfile?step=" . $arrStep[$i] . "'>";
			$href2 = "</a></li>";
		}
	} else {
		$href1 = "<li><a>";
		$href2 = "</a></li>";
	}
?>
                    <?php echo $href1.$i.". ".$arrTitle[$i].$href2?>
					<?php } ?>
					</ul>
					<p class="lbox"> <?php echo $lang['tipLeftHelp']?></p>
</div>
<div id="main">
       	<?php if($step!=7){?><form name="settingsform" method="post" action="<?php echo $installfile; ?>"><?php }?>
      	<?php switch ($step) {
		case 1 :
			if ($msg) {
				$str = "<p>" . $msg . "</p>";
			}
			if ($nextAccess == 1)
				$str = "<div id=\"tips\"><div class=\"log\">{$lang['step1ReadLicense']}</div><div class=\"mes\"><div align=\"center\"><textarea style=\"width: 94%; height: 300px;\">" . $lang['step1LicenseInfo'] . "</textarea></div><br /><div align=\"center\"><input type=\"submit\" value=\"{$lang['step1Agree']}\" class=\"inbut1\">    <input type=\"button\" value=\"{$lang['step1Disagree']}\" class=\"inbut\" onclick=\"javascript:window.close();\"></div></div>";
			break;
		case 2 :
			
			$fileConfigAccess = file_writeable($configfile);
			$filelogoAccess=file_writeable($logofile);
			
			$dirUploadsAccess = file_writeable($uploadsDir);
			$dirDataAccess = file_writeable($dataDir);
			$dirPluginAccess = file_writeable($pluginDir);

			if(@ini_get("file_uploads")) {
				$max_size = @ini_get(upload_max_filesize);
				$curr_upload_status = "<font class=\"s4_color\">{$lang['step2AttachAllowSize']}: $max_size</font>";
			} else {
				$curr_upload_status = "<font color='red'>{$lang['step2AttachDisabled']}</font>";
				$msg .= "<span class='err'>{$lang['step2AttachDisabledTip']}</span><br>";
				$nextAccess=0;
				$extend .= '{A}'.$lang['step2AttachDisabledTip']."\n";
			}

			$curr_php_version = PHP_VERSION;

			if ($curr_php_version < '4.1.0') {
				$curr_php_version = "$curr_php_version <font color='red'>{$lang['step2PHPVersionTooLowTip']}</font>";
				$nextAccess = 0;
				$extend .= '{B}'.$lang['step2PHPVersionTooLowTip']."\n";
			}

			if (!function_exists('mysql_connect')) {
				$MySQLVersion = "<font color='s3_color'>{$lang['commonUnsupport']}</font>";
				$nextAccess = 0;
				$extend .= '{C}'.'mysql'.$lang['commonUnsupport']."\n";
			} else {
				$MySQLVersion = "<font class='s2_color'>{$lang['commonSupport']}</font>";;
			}

			$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

			$os = strtoupper(substr(PHP_OS, 0, 3));
			$curOs = PHP_OS;
			if ($fileConfigAccess) {
				$fileConfigAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$fileConfigAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{D}'.'configfile'.$lang['commonWriteable']."\n";
			}
			if ($filelogoAccess) {
				$filelogoAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$filelogoAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{E}'.'logofile'.$lang['commonWriteable']."\n";
			}
			if ($dirUploadsAccess) {
				$dirUploadsAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$dirUploadsAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{F}'.'logofile'.$lang['commonWriteable']."\n";
			}
			if ($dirDataAccess ) {
				$dirDataAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$dirDataAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{G}'.'dirData'.$lang['commonWriteable']."\n";
			}
			if ($dirPluginAccess ) {
				$dirPluginAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$dirPluginAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{H}'.'dirPlugin'.$lang['commonWriteable']."\n";
			}
			$str = $str."<div id=\"tips\">{$lang['step2Tip']}</div>";

			$str = $str."<div id=\"wrapper\">
  <table class=\"table_nav\">
    <tr class=\"nav_bar\">
      <td></td>
      <td>HDWiki {$lang['commonConfigRequire']}</td>
      <td>HDWiki {$lang['commonConfigOptimized']}</td>
      <td>{$lang['commonConfigCurrent']}</td>
    </tr>
    <tr>
      <td>{$lang['commonOS']}</td>
      <td>{$lang['commonUnlimited']}</td>
      <td class=\"s1_color\">UNIX/Linux/FreeBSD </td>
      <td class=\"s4_color\">$curOs</td>
    </tr>
    <tr>
      <td>PHP {$lang['commonVersion']}</td>
      <td>4.1.0+ </td>
      <td class=\"s1_color\">5.2.2+</td>
      <td class=\"s2_color\">$curr_php_version</td>
    </tr>
    <tr>
      <td>{$lang['commonAttachUpload']}</td>
      <td>{$lang['commonUnlimited']}</td>
      <td class=\"s1_color\">{$lang['commonAllow']}</td>
      <td >$curr_upload_status</td>
    </tr>
    <tr>
      <td>MySQL {$lang['commonSupport']}</td>
      <td>3.23+</td>
      <td class=\"s1_color\">{$lang['commonSupport']}</td>
      <td>$MySQLVersion</td>
    </tr>
    <tr>
      <td>{$lang['commonDiskSpace']}</td>
      <td>10M+</td>
      <td class=\"s1_color\">{$lang['commonUnlimited']}</td>
      <td class=\"s4_color\">$curr_disk_space</td>
    </tr>
  </table>
</div>";

$str = $str."<div id=\"wrapper1\">
						<table class=\"table_nav\">
    <tr class=\"nav_bar\">
      <td>{$lang['commonDirName']}</td>
      <td>{$lang['commonDirDescribe']}</td>
      <td>{$lang['commonStateOptimized']}</td>
      <td>{$lang['commonStateCurrent']}</td>
    </tr>
    <tr>
      <td>./uploads</td>
      <td>{$lang['commonDirAttach']}</td>
      <td class=\"s1_color\">{$lang['commonDirPower']} {$lang['commonWriteable']}</td>
      <td>$dirUploadsAccessTip</td>
    </tr>
    <tr>
      <td>./data</td>
      <td>{$lang['commonDirSysData']}</td>
      <td class=\"s1_color\">{$lang['commonDirPower']} {$lang['commonWriteable']}</td>
      <td>$dirDataAccessTip</td>
    </tr>
    <tr>
      <td>./plugins</td>
      <td>{$lang['commonDirSysPlugin']}</td>
      <td class=\"s1_color\">{$lang['commonDirPower']} {$lang['commonWriteable']}</td>
      <td>$dirPluginAccessTip</td>
    </tr>
    <tr>
      <td>./config.php</td>
      <td>{$lang['commonFileConfig']}</td>
      <td class=\"s1_color\">{$lang['commonFilePower']} {$lang['commonWriteable']}</td>
      <td>$fileConfigAccessTip</td>
    </tr>
    <tr>
      <td>./style/default/logo.gif</td>
      <td>{$lang['commonFileLogo']}</td>
      <td class=\"s1_color\">{$lang['commonFilePower']} {$lang['commonWriteable']}</td>
      <td>$filelogoAccessTip</td>
    </tr>
  </table></div>";
			break;
		case 3 :
			$saveconfig=$_REQUEST['saveconfig'];
			if($saveconfig=='1'){
				//db parameter
				$dbhost = trim($_POST['dbhost']);
				$dbuser = trim($_POST['dbuser']);
				$dbpassword = trim($_POST['dbpassword']);
				$dbname = trim($_POST['dbname']);
				$table_prefix = trim($_POST['table_prefix']);

				// ���ܵ�������д��CONFIG �ļ������ڻ���
				if (is_writeable($configfile) || (!file_exists($configfile))) {
						$configcontent = "<?php
	define('DB_HOST', '".$dbhost."');
	define('DB_USER', '".$dbuser."');
	define('DB_PW', '".$dbpassword."');
	define('DB_NAME', '".$dbname."');
	define('DB_TABLEPRE', '".$table_prefix."');
	define('WIKI_URL', '".$site_url."');
?>";
						$fp1 = fopen($configfile, 'wbt');
						$bytes=fwrite($fp1, $configcontent);
						@ fclose($fp1);
					} else {
						if (!file_exists($configfile)) {
							$msg .= "<SPAN class=err>{$lang['step3DBConfigWriteErrorTip']}</span><br />";
							$nextAccess = 0;
							$extend .= '{I}'.$lang['step3DBConfigWriteErrorTip']."\n";
						}else{
							$msg .= "<SPAN class=err>{$lang['step3DBConfigNotWriteTip']}</span><br />";
							$nextAccess = 0;
							$extend .= '{J}'.$lang['step3DBConfigNotWriteTip']."\n";
						}
					}
					
				if ($dbhost == "" or $dbuser == "" or $dbname == "" or $table_prefix == "") {
					$msg .= "<SPAN class=err>{$lang['step3IsNull']}</span><br />";
					$nextAccess = 0;
					$extend .= '{K}'.$lang['step3IsNull']."\n";
				}

				if (strstr($table_prefix, '.') and $nextAccess == 1) {
					$msg .= "<SPAN class=err>{$lang['step3DBPrefix']}</span><br />";
					$nextAccess = 0;
					$extend .= '{L}'.$lang['step3DBPrefix']."\n";
				}

				if ($nextAccess == 1) {
					if(!@mysql_connect($dbhost, $dbuser, $dbpassword)) {
						$msg .= '<SPAN class=err>'.$lang['step3NoConnDB'].'</span>';
						$nextAccess = 0;
						$extend .= '{M}'.$lang['step3NoConnDB']."\n";
					} else {
						if(mysql_get_server_info() > '4.1') {
							mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET $dbcharset");
						} else {
							mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname`");
						}
						if(mysql_errno()) {
							$msg .= "<SPAN class=err>{$lang['step3DBNoPower']}</span><br />";
							$nextAccess = 0;
							$extend .= '{N}'.$lang['step3DBNoPower']."\n";
						}

						mysql_close();
					}
				}

				if ($nextAccess == 1) {
					
						$configcontent = "<?php
	define('DB_HOST', '".$dbhost."');
	define('DB_USER', '".$dbuser."');
	define('DB_PW', '".$dbpassword."');
	define('DB_NAME', '".$dbname."');
	define('DB_CHARSET', '".$dbcharset."');
	define('DB_TABLEPRE', '".$table_prefix."');
	define('DB_CONNECT', 0);
	define('WIKI_FOUNDER', 1);
	define('WIKI_CHARSET', '".$lang['commonCharset']."');
	define('WIKI_URL', '".$site_url."');
?>";
						$fp1 = fopen($configfile, 'wbt');
						$bytes=fwrite($fp1, $configcontent);
						@ fclose($fp1);
				}

				if ($nextAccess == 0) {
					$msg .= "<br /><SPAN class=err>{$lang['tipGenErrInfo']}</span><br /><br />";
					$msg .= "</p>\n";
				} else {
					echo   "<script>window.location=\"{$_SERVER['PHP_SELF']}?step=4\";</script>";
				}
				$str=$str.$msg;
			}else{
				if (PHP_VERSION < '4.0.6') {
					$msg .= "<SPAN class=err>{$lang['step2PHPVersionTooLowTip']}</span><br /><br />";
					$nextAccess = 0;
					$extend .= '{O}'.$lang['step2PHPVersionTooLowTip']."\n";
				}
				if (!function_exists('mysql_connect')) {
					$msg .= "<SPAN class=err>{$lang['step3MySQLExtErrorTip']}</span><br /><br />";
					$nextAccess = 0;
					$extend .= '{P}'.$lang['step3MySQLExtErrorTip']."\n";
				}
				if ($msg) {
					$str = "<p>" . $msg . "</p>";
				}

				if ($nextAccess == 1) {
					// �Զ�������ݿ���Ϣ
					$db_config = get_db_config();

					$str = "<div id=\"tips\">{$lang['step3Tip']}</div>";

					$str .= "<div id=\"wrapper\">
	<div class=\"col\">
	<h3>{$lang['commonSetupOption']}        {$lang['commonSetupParameterValue']}        {$lang['commonSetupComment']}</h3>
	<p><span class=\"red\">{$lang['step3MySqlHost']}: </span> <input name=\"dbhost\" value=\"".$db_config['dbhost']."\" type=\"text\" size=\"20\"/>    {$lang['step3MySqlHostComment']}</p>
	<p>{$lang['step3MySqlUser']}: <input name=\"dbuser\" value=\"".$db_config['dbuser']."\" type=\"text\" size=\"20\" maxlength=\"16\"/>    {$lang['step3MySqlUserComment']}</p>
	<p>{$lang['step3MySqlPass']}:&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"dbpassword\" value=\"".$db_config['dbpassword']."\" type=\"password\" size=\"20\"/>    {$lang['step3MySqlPassComment']}</p>
	<p>{$lang['step3MySqlDBName']}:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"dbname\" value=\"".$db_config['dbname']."\" type=\"text\" size=\"20\" onblur=\"checkConfig(this)\" maxlength=\"64\"/>    {$lang['step3MySqlDBNameComment']}</p>
	<p><span class=\"red\">{$lang['step3MySqlDBTablePrefix']}:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"table_prefix\" value=\"".$db_config['table_prefix']."\" type=\"text\" size=\"20\" onblur=\"checkConfig(this)\" maxlength=\"30\"/>    {$lang['step3MySqlDBTablePrefixComment']}</p>
	</div></div><input type='hidden' name='saveconfig' value='1'/>";
				}
				$prevStep=$prevStep-1;
				$isone = true;
			}

			break;
		case 4 :
				require_once HDWIKI_ROOT.'/config.php';
				if(!@mysql_connect(DB_HOST, DB_USER, DB_PW)) {
					$msg .= '<SPAN class=err>'.$lang['step3NoConnDB'].'</span><br/>';
					$nextAccess = 0;
					$extend .= '{Q}'.$lang['step3NoConnDB']."\n";
				} else {
					$curr_mysql_version = mysql_get_server_info();
					if($curr_mysql_version < '3.23') {
						$msg .= '<SPAN class=err>'.$lang['step3MySqlVersionToLowTip'].'</span><br/>';
						$nextAccess = 0;
						$extend .= '{R}'.$lang['step3MySqlVersionToLowTip']."\n";
					}
					$islink=mysql_select_db(DB_NAME);
					if($islink){
						$result = mysql_query("SELECT COUNT(*) FROM ".DB_TABLEPRE."setting");
						if($result) {
							$msg .= '<SPAN class=err>'.$lang['step3DBAlreadyExist'].'</span><br/>';
							$alert = " onClick=\"return confirm('{$lang['step3DBDropTableConfirm']}');\"";
						}
					}
				}

				// ͨ����������ù���Ա�û���������
				$admin_info = $_SERVER['SERVER_ADMIN'];
				if(!empty($admin_info)) {
					$admin_email = $admin_info;
					$admin_master = explode('@', $admin_email);
					$admin_master = $admin_master[0];
				} else {
					$admin_email = '';
					$admin_master = '';
				}
				$str = "<div id=\"tips\">" .
							"<div class=\"log\">{$lang['commonInfotip']}</div><div class=\"mes\"><p>{$lang['step4Tip']}<br/>$msg</p></div>
							</div>";
				$str .="<div id=\"wrapper\"><div class=\"col\">" .
	"<h3>{$lang['commonSetupOption']}{$lang['commonSetupParameterValue']}{$lang['commonSetupComment']}</h3>
	<p><span class=\"red\">{$lang['step4AdministratorNick']}:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input name=\"admin_master\" value=\"$admin_master\" type=\"text\" size=\"20\" maxlength=\"32\"/>{$lang['step4AdministratorNickComment']}</p>
	<p><span class=\"red\">{$lang['step4AdministratorEmail']}:&nbsp;</span><input name=\"admin_email\" value=\"$admin_email\" type=\"text\" size=\"20\" />{$lang['step4AdministratorEmailComment']}</p>
	<p><span class=\"red\">{$lang['step4AdministratorPass']}:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input name=\"admin_pw\" value=\"\" type=\"password\" size=\"20\" maxlength=\"32\"/>{$lang['step4AdministratorPassComment']}</p>
	<p><span class=\"red\">{$lang['step4AdministratorRePass']}:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input name=\"admin_pw2\" value=\"\" type=\"password\" size=\"20\" maxlength=\"32\"/>{$lang['step4AdministratorRePassComment']}</p>
	</div></div>";
			break;
		case 5 :
			$admin_pw = encode($_POST['admin_pw']);
			$admin_pw2 = encode($_POST['admin_pw2']);
			$admin_email = encode(trim($_POST['admin_email']));
			$admin_master = encode(trim($_POST['admin_master']));
			$site_icp = "";

			if ($admin_pw == "" or $admin_pw2 == "" or $admin_email == "" or $admin_master == "") {
				$str = "<SPAN class=err>{$lang['step3IsNull']}</span>";
				$nextAccess = 0;
				$extend .= '{S}'.$lang['step3IsNull']."\n";
			}elseif (strlen($admin_pw) < 6) {
				$str = "<SPAN class=err>{$lang['step4AdministratorPassTooShortTip']}</span>";
				$nextAccess = 0;
				$extend .= '{T}'.$lang['step4AdministratorPassTooShortTip']."\n";
			}elseif ($admin_pw != $admin_pw2) {
				$str = "<SPAN class=err>{$lang['step4AdministratorPassNotSame']}</span>";
				$nextAccess = 0;
				$extend .= '{U}'.$lang['step4AdministratorPassNotSame']."\n";
			}elseif (check_email($admin_email) == 0) {
				$str = "<SPAN class=err>{$lang['step4AdministratorEmailInvalid']}</span>";
				$nextAccess = 0;
				$extend .= '{V}'.$lang['step4AdministratorEmailInvalid']."\n";
			} else {
				if ($nextAccess == 1) {
					require_once HDWIKI_ROOT.'/config.php';
					require_once HDWIKI_ROOT.'/lib/hddb.class.php';
					$db = new hddb(DB_HOST, DB_USER, DB_PW, DB_NAME, DB_CHARSET);
					$fp = fopen($sqlfile, 'rb');
					$sql = fread($fp, filesize($sqlfile));
					fclose($fp);
					$strcretip=runquery($sql);
 
					if($nextAccess==1) $msg .= "{$lang['step4ImportDefaultData']} <br />";

					$admin_email = strtolower($admin_email);
					$admin_email_len = strlen($admin_email);
					$adminpwd = md5($admin_pw);
					$regtime=time();
					$site_name = $lang['step4DefaultSiteName'];
					$auth_key = generate_key();
$installsql = <<<EOT

INSERT INTO wiki_usergroup (`groupid`, `grouptitle`, `regulars`, `default`, `type`, `creditslower`, `creditshigher`, `stars`, `color`, `groupavatar`) VALUES
(1, '�����û�', 'index-default|index-settheme|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-getpass|user-code|user-space|user-clearcookies|synonym-view|passport_client-login|passport_client-logout|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|search-agent', 'index-default|index-settheme|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-getpass|user-code|user-space|user-clearcookies|synonym-view|passport_client-login|passport_client-logout|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|search-agent', 1, 0, 0, 0, '', ''),
(3, '��������Ա', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status|admin_actions-map|index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|doc-remove|comment-remove|comment-add|comment-edit|edition-remove|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|admin_doc-cancelrecommend|doc-delsave|doc-managesave|admin_main-login|admin_main-default|admin_main-logout|admin_main-mainframe|admin_main-update|admin_doc-default|admin_doc-search|admin_doc-audit|admin_doc-recommend|admin_doc-lock|admin_doc-unlock|admin_doc-remove|admin_doc-move|admin_doc-rename|admin_comment-default|admin_comment-search|admin_comment-delete|admin_attachment-default|admin_attachment-search|admin_attachment-remove|admin_attachment-download|admin_focus-focuslist|admin_focus-remove|admin_focus-reorder|admin_focus-edit|admin_focus-updateimg|admin_focus-numset|admin_tag-hottag|admin_word-default|admin_synonym-default|admin_synonym-search|admin_synonym-delete|admin_synonym-save|admin_cooperate-default|admin_hotsearch-default|admin_image-default|admin_image-editimage|admin_image-remove|admin_relation-default|admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_editi|exchange-default|admin_share-default|admin_share-search|admin_share-share|admin_main-datasize|doc-editletter', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status|admin_actions-map|index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|doc-remove|comment-remove|comment-add|comment-edit|edition-remove|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|admin_doc-cancelrecommend|doc-delsave|doc-managesave|admin_main-login|admin_main-default|admin_main-logout|admin_main-mainframe|admin_main-update|admin_doc-default|admin_doc-search|admin_doc-audit|admin_doc-recommend|admin_doc-lock|admin_doc-unlock|admin_doc-remove|admin_doc-move|admin_doc-rename|admin_comment-default|admin_comment-search|admin_comment-delete|admin_attachment-default|admin_attachment-search|admin_attachment-remove|admin_attachment-download|admin_focus-focuslist|admin_focus-remove|admin_focus-reorder|admin_focus-edit|admin_focus-updateimg|admin_focus-numset|admin_tag-hottag|admin_word-default|admin_synonym-default|admin_synonym-search|admin_synonym-delete|admin_synonym-save|admin_cooperate-default|admin_hotsearch-default|admin_image-default|admin_image-editimage|admin_image-remove|admin_relation-default|admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_editi|exchange-default|admin_share-default|admin_share-search|admin_share-share|admin_main-datasize|doc-editletter', 1, 0, 0, 2, '', ''),
(4, '��������Ա', '', '', 1, 0, 0, 3, '', ''),
(5, '�׶�', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|doc-edit|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-view|synonym-savesynonym|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|doc-edit|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-view|synonym-savesynonym|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, -999999, 0, 0, '', ''),
(2, '��ͯ', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 0, 100, 1, '', ''),
(6, '���', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 100, 300, 4, '', ''),
(7, '����', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 300, 600, 5, '', ''),
(8, '��ʿ', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 600, 1000, 8, '', ''),
(9, '״Ԫ', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 1000, 1500, 16, '', ''),
(10, '����', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 1500, 2100, 18, '', ''),
(11, '̫��', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 2100, 2800, 24, '', ''),
(12, 'ʥ��', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 2800, 999999999, 33, '', ''),
(13, '��������', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 0, 0, 0, 5, '', ''),
(14, '����Ա', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status|admin_actions-map|index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|doc-remove|comment-remove|comment-add|comment-edit|edition-remove|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|admin_doc-cancelrecommend|doc-delsave|doc-managesave|admin_banned-default|admin_friendlink-default|admin_friendlink-add|admin_friendlink-edit|admin_friendlink-remove|admin_friendlink-updateorder|admin_main-login|admin_main-default|admin_main-logout|admin_main-mainframe|admin_main-update|admin_setting-base|admin_setting-code|admin_setting-time|admin_setting-cookie|admin_setting-logo|admin_setting-credit|admin_setting-seo|admin_setting-cache|admin_setting-renewcache|admin_setting-removecache|admin_setting-attachment|admin_setting-mail|admin_setting-noticemail|admin_task-default|admin_task-taskstatus|admin_task-edittask|admin_task-run|admin_log-default|admin_setting-notice|admin_setting-anticopy|admin_setting-listdisplay|admin_setting-sec|admin_setting-index|admin_setting-docset|admin_setting-search|admin_plugin-list|admin_plugin-default|admin_plugin-manage|admin_plugin-will|admin_plugin-find|admin|admin_plugin-install|admin_plugin-uninstall|admin_plugin-start|admin_plugin-stop|admin_plugin-setvar|admin_plugin-hook|admin_doc-default|admin_doc-search|admin_doc-audit|admin_doc-recommend|admin_doc-lock|admin_doc-unlock|admin_doc-remove|admin_doc-move|admin_doc-rename|admin_comment-default|admin_comment-search|admin_comment-delete|admin_attachment-default|admin_attachment-search|admin_attachment-remove|admin_attachment-download|admin_focus-focuslist|admin_focus-remove|admin_focus-reorder|admin_focus-edit|admin_focus-updateimg|admin_focus-numset|admin_tag-hottag|admin_word-default|admin_synonym-default|admin_synonym-search|admin_synonym-delete|admin_synonym-save|admin_recycle-default|admin_recycle-search|admin_recycle-remove|admin_recycle-recover|admin_recycle-|admin_cooperate-default|admin_hotsearch-default|admin_image-default|admin_image-editimage|admin_image-remove|admin_relation-default|admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_editi|admin_gift-default|admin_gift-view|admin_gift-search|admin_gift-add|admin_gift-edit|admin_gift-remove|admin_user-default|admin_user-list|admin_user-add|admin_user-edit|admin_usergroup-default|admin_usergroup-list|admin_category-default|admin_category-list|admin_category-add|admin_category-batchedit|admin_category-edit|admin_category-reorder|admin_statistics-stand|admin_statistics-cat_toplist|admin_statistics-doc_toplist|admin_statistics-edit_toplist|admin_statistics-credit_toplist|admin_statistics-admin_team|exchange-default|admin_share-default|admin_share-search|admin_share-share|doc-editletter|admin_sitemap-default|admin_sitemap-setting|admin_sitemap-createdoc|admin_sitemap-updatedoc|admin_sitemap-submit|admin_sitemap-baiduxml|admin_filecheck-docreate|admin_safe-default|admin_safe-setting|admin_safe-scanfile|admin_safe-validate|admin_safe-scanfuns|admin_safe-list|admin_safe-editcode|admin_safe-del', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status|admin_actions-map|index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|doc-remove|comment-remove|comment-add|comment-edit|edition-remove|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|admin_doc-cancelrecommend|doc-delsave|doc-managesave|admin_banned-default|admin_friendlink-default|admin_friendlink-add|admin_friendlink-edit|admin_friendlink-remove|admin_friendlink-updateorder|admin_main-login|admin_main-default|admin_main-logout|admin_main-mainframe|admin_main-update|admin_setting-base|admin_setting-code|admin_setting-time|admin_setting-cookie|admin_setting-logo|admin_setting-credit|admin_setting-seo|admin_setting-cache|admin_setting-renewcache|admin_setting-removecache|admin_setting-attachment|admin_setting-mail|admin_setting-noticemail|admin_task-default|admin_task-taskstatus|admin_task-edittask|admin_task-run|admin_log-default|admin_setting-notice|admin_setting-anticopy|admin_setting-listdisplay|admin_setting-sec|admin_setting-index|admin_setting-docset|admin_setting-search|admin_plugin-list|admin_plugin-default|admin_plugin-manage|admin_plugin-will|admin_plugin-find|admin|admin_plugin-install|admin_plugin-uninstall|admin_plugin-start|admin_plugin-stop|admin_plugin-setvar|admin_plugin-hook|admin_doc-default|admin_doc-search|admin_doc-audit|admin_doc-recommend|admin_doc-lock|admin_doc-unlock|admin_doc-remove|admin_doc-move|admin_doc-rename|admin_comment-default|admin_comment-search|admin_comment-delete|admin_attachment-default|admin_attachment-search|admin_attachment-remove|admin_attachment-download|admin_focus-focuslist|admin_focus-remove|admin_focus-reorder|admin_focus-edit|admin_focus-updateimg|admin_focus-numset|admin_tag-hottag|admin_word-default|admin_synonym-default|admin_synonym-search|admin_synonym-delete|admin_synonym-save|admin_recycle-default|admin_recycle-search|admin_recycle-remove|admin_recycle-recover|admin_recycle-|admin_cooperate-default|admin_hotsearch-default|admin_image-default|admin_image-editimage|admin_image-remove|admin_relation-default|admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_editi|admin_gift-default|admin_gift-view|admin_gift-search|admin_gift-add|admin_gift-edit|admin_gift-remove|admin_user-default|admin_user-list|admin_user-add|admin_user-edit|admin_usergroup-default|admin_usergroup-list|admin_category-default|admin_category-list|admin_category-add|admin_category-batchedit|admin_category-edit|admin_category-reorder|admin_statistics-stand|admin_statistics-cat_toplist|admin_statistics-doc_toplist|admin_statistics-edit_toplist|admin_statistics-credit_toplist|admin_statistics-admin_team|exchange-default|admin_share-default|admin_share-search|admin_share-share|doc-editletter|admin_sitemap-default|admin_sitemap-setting|admin_sitemap-createdoc|admin_sitemap-updatedoc|admin_sitemap-submit|admin_sitemap-baiduxml|admin_filecheck-docreate|admin_safe-default|admin_safe-setting|admin_safe-scanfile|admin_safe-validate|admin_safe-scanfuns|admin_safe-list|admin_safe-editcode|admin_safe-del', 1, 0, 0, 2, '', ''),
(15, '������', 'index-default|index-settheme|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-profile|user-editpass|user-getpass|user-code|user-space|user-clearcookies|pms-blacklist|synonym-view|doc-editletter', 'index-default|index-settheme|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-profile|user-editpass|user-getpass|user-code|user-space|user-clearcookies|pms-blacklist|synonym-view|doc-editletter', 1, 0, 0, 1, '', '');
				
REPLACE INTO wiki_setting(variable,value) VALUES
	('site_name', '{$site_name}'),
	('site_icp', ''),
	('cookie_domain', ''),
	('cookie_pre', 'hd_'),
	('app_url', 'http://kaiyuan.hudong.com'),
	('auth_key', '{$auth_key}'),

	('verify_doc', '0'),
	('cat_intro_set','0'),
	('time_offset','8'),
	('time_diff','0'),
	('time_format',''),
	('date_format','m-d'),
	('style_user_select','1'),

	('credit_create', '5'),
	('credit_edit', '3'),
	('credit_upload', '2'),
	('credit_register', '20'),
	('credit_login', '1'),
	('credit_pms','1'),
	('credit_comment','2'),
	('list_prepage', '20'),
	
	('list_focus', '10'),
	('list_recentupdate', '10'),
	('list_weekuser', '10'),
	('list_allcredit', '10'),
	('list_popularity', '10'),
	('list_letter', '10'),
	('login_show', '1'),
	('category_view', '10'),
	('category_letter', '10'),
	('index_commend', '15'),
	('index_recentupdate', '6'),
	('index_recentcomment', '5'),
	('index_hotdoc', '3'),
	('index_wonderdoc', '6'),
	('index_picture', '9'),
	('index_cooperate', '20'),

	('seo_prefix', 'index.php?'),
	('seo_separator', '-'),
	('seo_suffix', ''),
	('seo_title', ''),
	('seo_keywords', ''),
	('seo_description', ''),
	('seo_headers', ''),
	('seo_type', '0'),
	('seo_type_doc', '0'),

	('attachment_size', '2048'),
	('attachment_open', '0'),
	('attachment_type', 'jpg|jpeg|bmp|gif|png|gz|bz2|zip|rar|doc|ppt|mp3|xls|txt|swf|flv|php|pdf'),
		
	('index_cache_time', '300'),
	('list_cache_time', '300'),
	('doc_cache_time', '300'),
	('tpl_name', 'default'),
	('theme_name','default'),
	('lang_name','zh'),
	('auto_picture','0'),
	('checkcode','3'),
	('sandbox_id',''),
	('logowidth','220px'),
	('site_notice','��վ����<span style="color:#FF0000">1</span>λ����ͬ׫д�İٿ�ȫ�飬Ŀǰ����¼����<span style="color:#FF0000"> 0</span>��'),

	
	('search_time', '1'),
	('search_tip_switch', '1'),
	('search_num', '10000'),
	('close_register_reason', '�Բ�����վ��ͣע�ᣡ���������Ĳ��㻹���½⡣'),
	('error_names', '����Ա'),
	('register_check', '0'),
	('name_min_length', '3'),
	('name_max_length', '15'),
	('register_least_minute', '30'),
	('reg_status', '3'),
	('inviter_credit', '5'),
	('invitee_credit', '0'),
	('invite_subject', '���յ�_USERNAME_�������ˣ�'),
	('invite_content', '��ã�����_USERNAME_����_SITENAME_��ע���˻�Ա�������кܶ����õ�֪ʶ��������Ҳ���������\\r\\n\\r\\n���븽�ԣ�\\r\\n\\r\\n_PS_\\r\\n\\r\\n�������������ӣ����ܺ������룺_LINK_\\r\\n\\r\\n_SITENAME_ ����'),
	('welcome_subject', '_USERNAME_�����ã���ӭ���ļ���^_^'),
	('welcome_content', '�𾴵�_USERNAME_��\\r\\n\\r\\n���ã����ѳɹ�ע��Ϊ_SITENAME_�Ļ�Ա����ӭ�����һ�������֪ʶ��\\r\\n\\r\\n_SITENAME_ ����\\r\\n_TIME_'),
	('send_welcome', '0'),
	('close_website', '0'),
	('forbidden_edit_time', '0'),
	('comments', '1'),
	('base_toplist', '0'),		
	('base_createdoc', '0'),
	('doc_verification_edit_code', '0'),
	('doc_verification_create_code', '0'),
	('relateddoc', ''),
	('isrelate', '0'),
	('close_website_reason', '��վ��ʱ�رգ����Ͼͻ�ָ������Ժ��ע��лл��'),
	
	('coin_register', '20'),
	('coin_login', '1'),
	('coin_create', '2'),
	('coin_edit', '1'),
	('coin_upload', '1'),
	('coin_pms', '0'),
	('coin_comment', '1'),
	('credit_exchangeRate', '5'),
	('coin_exchangeRate', '1'),
	('credit_exchange', '0'),
	('coin_exchange', '0'),
	('credit_download', '0'),
	('coin_download', '10'),
	
	('img_width_big', '300'),
	('img_height_big', '300'),
	('img_width_small', '140'),
	('img_height_small', '140'),

	('cloud_search', '1'),
	('cloud_search_close_time', '30'),
	('cloud_search_timeout', '5'),
	('cloud_search_cache', '300'),
	
	('gift_range', 'a:4:{i:0;s:2:"50";i:50;s:3:"100";i:100;s:3:"200";i:200;s:3:"500";}'),
	('watermark', 'a:8:{s:8:"imagelib";s:1:"0";s:11:"imageimpath";s:0:"";s:15:"watermarkstatus";s:1:"0";s:17:"watermarkminwidth";s:3:"180";s:18:"watermarkminheight";s:3:"180";s:13:"watermarktype";s:1:"0";s:14:"watermarktrans";s:2:"60";s:16:"watermarkquality";s:3:"100";}'),
	('coin_unit', ''),
	('mail_config', 'a:10:{s:11:"maildefault";s:{$admin_email_len}:"{$admin_email}";s:8:"mailsend";s:1:"1";s:10:"mailserver";s:0:"";s:8:"mailport";s:2:"25";s:8:"mailauth";s:1:"0";s:8:"mailfrom";s:0:"";s:17:"mailauth_username";s:0:"";s:17:"mailauth_password";s:0:"";s:13:"maildelimiter";s:1:"0";s:12:"mailusername";s:1:"1";}'),
	('sitemap_config', 'a:5:{s:8:"use_gzip";s:1:"1";s:14:"idx_changefreq";s:5:"daily";s:14:"doc_changefreq";s:7:"monthly";s:10:"textcolumn";s:7:"summary";s:10:"updateperi";s:2:"30";}'),
	('auto_baiduxml', '0'),
	('random_open', '0'),
	('random_text', ''),
	('check_useragent', '0'),
	('ua_allow_first', '1'),
	('allow_ua_both', '1'),
	('ua_whitelist', ''),
	('ua_blacklist', ''),
	('check_visitrate', '0'),
	('visitrate', 'a:3:{s:8:"duration";i:60;s:5:"pages";i:30;s:8:"ban_time";i:1;}'),
	('compatible', '1'),
	('hdapi_bklm', '1'),
	('hdapi_sharetosns', '1'),
	('hdapi_autoshare_edit', '0'),
	('hdapi_autoshare_create', '0'),
	('hdapi_autoshare_comment', '0'),
	('hdapi_autoshare_ding', '0'),
	('base_isreferences', '1'),
	('doc_verification_reference_code', '0'),
	('noticemailtpl', 'a:3:{s:10:"doc_create";a:2:{s:7:"subject";s:38:"_USERNAME_ �����ˡ� _DOCTITLE_ ��";s:4:"body";s:793:"<style>cite, .build {background:none repeat scroll 0 0 #FFFFCC;border:1px solid #F39700;display:block;margin-bottom:8px;padding:10px;}table { font-size: 13px; }.firstimg { padding: 5px; border: 1px solid #EFEFEF; margin-right: 16px; }h4{ font-size: 16px; margin: 0; padding: 0; }hr { width: 100px; }div.sig { font-size: 12px; font-family: "Arial"  }.time { color: #CCCCCF; }.uns { color: green; }</style><table border="0"><tr><td valign="top">_FIRSTIMG_</td><td valign="top"><strong>_DOCTITLE_</strong><br /><a href="_URL_" target="_blank">_URL_</a><br /><br />����ʱ�䣺_TIME_<br /><br />ժҪ��_SUMMARY_<br /><br /><a href="_URL_" target="_blank">�鿴����</a></td></tr></table><br /><br /><div class="sig"><span class="time">_TIME_ </span><hr align="left" />_SITENAME_<br /></div>";}s:8:"doc_edit";a:2:{s:7:"subject";s:38:"_USERNAME_ �༭�ˡ� _DOCTITLE_ ��";s:4:"body";s:798:"<style>cite, .build {background:none repeat scroll 0 0 #FFFFCC;border:1px solid #F39700;display:block;margin-bottom:8px;padding:10px;}table { font-size: 13px; }.firstimg { padding: 5px; border: 1px solid #EFEFEF; margin-right: 16px; }h4{ font-size: 16px; margin: 0; padding: 0; }hr { width: 100px; }div.sig { font-size: 12px; font-family: "Arial"  }.time { color: #CCCCCF; }.uns { color: green; }</style><table border="0"><tr><td valign="top">_FIRSTIMG_</td><td valign="top"><strong>_DOCTITLE_</strong><br /><a href="_URL_" target="_blank">_URL_</a><br /><br />�༭ʱ�䣺_TIME_<br /><br />�༭ԭ��_REASON_<br /><br /><a href="_URL_" target="_blank">�鿴����</a></td></tr></table><br /><br /><div class="sig"><span class="time">_TIME_ </span><hr align="left" />_SITENAME_<br /></div>";}s:11:"comment_add";a:2:{s:7:"subject";s:38:"_USERNAME_������ �� _DOCTITLE_ ��";s:4:"body";s:782:"<style>cite, .build {background:none repeat scroll 0 0 #FFFFCC;border:1px solid #F39700;display:block;margin-bottom:8px;padding:10px;}table { font-size: 13px; }.firstimg { padding: 5px; border: 1px solid #EFEFEF; margin-right: 16px; }h4{ font-size: 16px; margin: 0; padding: 0; }hr { width: 100px; }div.sig { font-size: 12px; font-family: "Arial"  }.time { color: #CCCCCF; }.uns { color: green; }</style><table border="0"><tr><td valign="top">_FIRSTIMG_</td><td valign="top"><strong>_DOCTITLE_</strong><br /><a href="_URL_" target="_blank">_URL_</a><br /><br /><div >_REPLY_</div><br /><br />_COMMENT_<br /><br /><a href="_URL_" target="_blank">�鿴����</a></td></tr></table><br /><br /><div class="sig"><span class="time">_TIME_ </span><hr align="left" />_SITENAME_<br /></div>";}}'),
	('noticemail', 'a:3:{s:10:"doc-create";s:1:"4";s:8:"doc-edit";s:0:"";s:11:"comment_add";s:0:"";}'),
	('visitrate_ip_exception', '127.0.0.*');

INSERT INTO wiki_category(`name`,`navigation`,`docs`) VALUES ('Default','a:1:{i:0;a:2:{s:3:"cid";s:1:"1";s:4:"name";s:7:"Default";}}',0);
INSERT INTO wiki_user(email,username,`password`,`lastip`,groupid,credit1,credit2,creates,regtime) VALUES ('{$admin_email}', '{$admin_master}', '$adminpwd', '{$_SERVER[REMOTE_ADDR]}',4,20,20,32,'$regtime') ;
INSERT INTO wiki_creditdetail(`uid`,`operation`,`credit1`,`credit2`,`time`) VALUES (1,'user-register',20,20,$regtime);

INSERT INTO wiki_regular (`id`, `name`, `regular`, `type`, `regulargroupid`) VALUES
	(1, '��ҳ', 'index-default', 0, 18),
	(2, '���ķ��', 'index-settheme', 0, 18),
	(3, 'ͼƬ�ϴ�', 'attachment-uploadimg', 0, 20),
	(4, '�������أ�������', 'attachment-download', 0, 18),
	(5, 'ɾ��������������', 'attachment-remove', 0, 20),
	(6, '�������', 'category-default|category-ajax', 0, 18),
	(7, '����������', 'category-view', 0, 18),
	(8, '��������ĸ˳�����', 'category-letter', 0, 18),
	(9, '����ĸ˳����������а�', 'list-letter', 0, 18),
	(10, '������´��������а�', 'list-default|list-recentchange', 0, 18),
	(13, '�û����������б�(���а�)', 'list-popularity', 0, 18),
	(14, '�Ƽ������б�(���а�)', 'list-focus', 0, 18),
	(15, '�������', 'doc-view', 0, 18),
	(16, '��������', 'doc-create', 0, 20),
	(17, '��֤�����Ƿ����', 'doc-verify', 0, 20),
	(18, '�༭����', 'doc-edit', 0, 20),
	(19, '�ֶα༭����', 'doc-editsection', 0, 20),
	(20, 'ˢ�±༭��', 'doc-refresheditlock', 0, 20),
	(21, 'ȡ���༭��', 'doc-unseteditlock', 0, 20),
	(22, '�����������', 'doc-innerlink', 0, 18),
	(23, '�������ժҪ', 'doc-summary', 0, 18),
	(24, '�������������', 'doc-editor', 0, 18),
	(25, 'ɳ��', 'doc-sandbox', 0, 20),
	(26, '�����Ƽ�������ǰ̨��������', 'doc-setfocus', 0, 20),
	(27, '�ƶ��������ࣨǰ̨��������', 'doc-getcategroytree|doc-changecategory', 0, 20),
	(28, '���Ĵ�������ǰ̨��������', 'doc-changename', 0, 20),
	(29, '����������ǰ̨��������', 'doc-lock', 0, 20),
	(30, '�������������ǰ̨��������', 'doc-unlock', 0, 20),
	(31, '��˴�����ǰ̨��������', 'doc-audit', 0, 20),
	(32, 'ɾ��������ǰ̨��������', 'doc-remove', 0, 20),
	(33, '�鿴����', 'comment-view|comment-report|comment-oppose|comment-aegis', 0, 18),
	(34, 'ɾ�����ۣ�ǰ̨���۹���', 'comment-remove', 0, 20),
	(35, '������ۣ�ǰ̨���۹���', 'comment-add', 0, 20),
	(36, '�༭���ۣ�ǰ̨���۹���', 'comment-edit', 0, 20),
	(37, '�汾�б���ʷ�汾��', 'edition-list', 0, 18),
	(38, '����汾����ʷ�汾��', 'edition-view', 0, 18),
	(39, '�汾�Աȣ���ʷ�汾��', 'edition-compare', 0, 18),
	(40, 'ɾ���汾����ʷ�汾��', 'edition-remove', 0, 20),
	(41, '����汾����ʷ�汾��', 'edition-excellent', 0, 20),
	(42, 'ȡ�����㣨��ʷ�汾��', 'edition-unexcellent', 0, 20),
	(43, '���ư汾����ʷ�汾��', 'edition-copy', 0, 20),
	(44, '���������������', 'search-default', 0, 18),
	(45, 'ȫ��������������', 'search-fulltext|search-kw', 0, 18),
	(46, 'TAG������������', 'search-tag', 0, 18),
	(47, '�û�ע�ᣨ�û���', 'user-register', 0, 19),
	(48, '�û���¼���û���', 'user-login', 0, 19),
	(49, '����û����û���', 'user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail', 0, 19),
	(50, '�û�ע�����û���', 'user-logout', 0, 19),
	(51, '������Ϣ���û���', 'user-profile', 0, 19),
	(52, '������Ϣ���ã��û���', 'user-editprofile', 0, 19),
	(53, '�޸����루�û���', 'user-editpass', 0, 19),
	(54, '�޸�ͷ���û���', 'user-editimage|user-editimageifeam|user-cutimage', 0, 19),
	(55, '�һ����루�û���', 'user-getpass', 0, 19),
	(56, '��ʾ��֤�루�û���', 'user-code', 0, 19),
	(57, '���˿ռ䣨�û���', 'user-space', 0, 19),
	(58, '���cookies���û���', 'user-clearcookies', 0, 19),
	(59, 'IP��ֹ', 'admin_banned-default', 1, 21),
	(60, '��������б��������', 'admin_category-default|admin_category-list', 1, 25),
	(61, '��ӷ��ࣨ�������', 'admin_category-add', 1, 25),
	(62, '�༭���ࣨ�������', 'admin_category-edit', 1, 25),
	(63, 'ɾ�����ࣨ�������', 'admin_category-remove', 1, 25),
	(64, '�������򣨷������', 'admin_category-reorder', 1, 25),
	(65, '����ϲ����������', 'admin_category-merge', 1, 25),
	(66, '���ݿⱸ�ݣ����ݿ����', 'admin_db-backup', 1, 27),
	(67, '���ݿ⻹ԭ�����ݿ����', 'admin_db-import', 1, 27),
	(68, 'ɾ�������ļ������ݿ����', 'admin_db-remove', 1, 27),
	(69, '���ݿ��б����ݿ����', 'admin_db-tablelist', 1, 27),
	(70, '���ݿ��Ż������ݿ����', 'admin_db-optimize', 1, 27),
	(71, '���ݿ��޸������ݿ����', 'admin_db-repair', 1, 27),
	(72, '���������ļ������ݿ����', 'admin_db-downloadfile', 1, 27),
	(73, '�����б����������', 'admin_doc-default', 1, 23),
	(74, '�������������������', 'admin_doc-search', 1, 23),
	(75, '��˴��������������', 'admin_doc-audit', 1, 23),
	(76, '�Ƽ����������������', 'admin_doc-recommend', 1, 23),
	(77, '�������������������', 'admin_doc-lock', 1, 23),
	(78, '�������������������', 'admin_doc-unlock', 1, 23),
	(79, 'ɾ�����������������', 'admin_doc-remove', 1, 23),
	(80, '�ƶ����������������', 'admin_doc-move', 1, 23),
	(81, '���������������������', 'admin_doc-rename', 1, 23),
	(82, '�������ۣ���̨�������ۣ�', 'admin_comment-default|admin_comment-search', 1, 23),
	(83, 'ɾ�����ۣ���̨�������ۣ�', 'admin_comment-delete', 1, 23),
	(84, '������������̨��������', 'admin_attachment-default|admin_attachment-search', 1, 23),
	(85, 'ɾ����������̨��������', 'admin_attachment-remove', 1, 23),
	(86, '���ظ�������̨��������', 'admin_attachment-download', 1, 23),
	(87, '�Ƽ������б��Ƽ�������', 'admin_focus-focuslist', 1, 23),
	(88, 'ɾ���Ƽ��������Ƽ�������', 'admin_focus-remove', 1, 23),
	(89, '�����Ƽ�����˳���Ƽ�������', 'admin_focus-reorder', 1, 23),
	(90, '�༭�Ƽ��������Ƽ�������', 'admin_focus-edit', 1, 23),
	(91, '����ͼƬ���Ƽ�������', 'admin_focus-updateimg', 1, 23),
	(92, '������ʾ�������ã��Ƽ�������', 'admin_focus-numset', 1, 23),
	(93, '���������б��������ӣ�', 'admin_friendlink-default', 1, 21),
	(94, '����������ӣ��������ӣ�', 'admin_friendlink-add', 1, 21),
	(95, '�༭�������ӣ��������ӣ�', 'admin_friendlink-edit', 1, 21),
	(96, 'ɾ���������ӣ��������ӣ�', 'admin_friendlink-remove', 1, 21),
	(97, '������������˳���������ӣ�', 'admin_friendlink-updateorder', 1, 21),
	(98, '�����б����ԣ�', 'admin_language-default', 1, 26),
	(99, '������ԣ����ԣ�', 'admin_language-addlang', 1, 26),
	(100, '�༭���ԣ����ԣ�', 'admin_language-editlang', 1, 26),
	(101, '�������ԣ����ԣ�', 'admin_language-updatelanguage', 1, 26),
	(102, '����Ĭ�����ԣ����ԣ�', 'admin_language-setdefaultlanguage', 1, 26),
	(103, '����Ա��¼����̨��¼��', 'admin_main-login|admin_main-default', 1, 21),
	(104, '����Ա�˳�����̨��¼��', 'admin_main-logout', 1, 21),
	(105, '��̨��ܣ���̨��¼��', 'admin_main-mainframe', 1, 21),
	(106, '��̨�°汾��ʾ����̨��¼��', 'admin_main-update', 1, 21),
	(107, '����б��������', 'admin_plugin-list|admin_plugin-default|admin_plugin-manage|admin_plugin-will|admin_plugin-find|admin_plugin-share', 1, 22),
	(108, '��װ������������', 'admin_plugin-install', 1, 22),
	(109, 'ж�ز�����������', 'admin_plugin-uninstall', 1, 22),
	(110, '���ò�����������', 'admin_plugin-start', 1, 22),
	(111, 'ͣ�ò�����������', 'admin_plugin-stop', 1, 22),
	(112, '����������������', 'admin_plugin-setvar', 1, 22),
	(113, '������ӣ��������', 'admin_plugin-hook', 1, 22),
	(114, '�����б�(����Ȩ��)', 'admin_regular-list|admin_regular-default', 1, 24),
	(115, '��ӹ���(����Ȩ��)', 'admin_regular-add', 1, 24),
	(116, '�༭����(����Ȩ��)', 'admin_regular-edit', 1, 24),
	(117, 'ɾ������(����Ȩ��)', 'admin_regular-remove', 1, 24),
	(118, '��������(��վ����)', 'admin_setting-base', 1, 21),
	(119, '�ϴ�logo(��վ����)', 'admin_setting-logo', 1, 21),
	(120, '��������(��վ����)', 'admin_setting-credit', 1, 21),
	(121, 'seo����(��վ����)', 'admin_setting-seo', 1, 21),
	(122, '����ҳ��(��վ����)', 'admin_setting-cache', 1, 21),
	(123, '���»�������(��վ����)', 'admin_setting-renewcache', 1, 21),
	(124, '�������(��վ����)', 'admin_setting-removecache', 1, 21),
	(125, '��������(��վ����)', 'admin_setting-attachment', 1, 21),
	(126, '�ʼ�����(��վ����)', 'admin_setting-mail', 1, 21),
	(127, '����б����', 'admin_style-default', 1, 26),
	(128, '����ģ����ҳ�棨���', 'admin_style-create', 1, 26),
	(129, 'ɾ����񣨷��', 'admin_style-removestyle', 1, 26),
	(131, '����Ĭ�Ϸ�񣨷��', 'admin_style-setdefaultstyle', 1, 26),
	(132, '���ű�ǩ���ã����ű�ǩ��', 'admin_tag-hottag', 1, 23),
	(133, '�б�|���|ɾ������ʱ����', 'admin_task-default', 1, 21),
	(134, '����|ͣ�ã���ʱ����', 'admin_task-taskstatus', 1, 21),
	(135, '�༭��ʱ���񣨶�ʱ����', 'admin_task-edittask', 1, 21),
	(136, 'ִ�ж�ʱ���񣨶�ʱ����', 'admin_task-run', 1, 21),
	(137, '�û��б������û���', 'admin_user-default|admin_user-list', 1, 24),
	(138, '����û��������û���', 'admin_user-add', 1, 24),
	(139, '�༭�û��������û���', 'admin_user-edit', 1, 24),
	(140, 'ɾ���û��������û���', 'admin_user-remove', 1, 24),
	(141, '�û����б������û��飩', 'admin_usergroup-default|admin_usergroup-list', 1, 24),
	(142, '����û��飨�����û��飩', 'admin_usergroup-add', 1, 24),
	(143, '�༭�û��飨�����û��飩', 'admin_usergroup-edit', 1, 24),
	(144, 'ɾ���û��飨�����û��飩', 'admin_usergroup-remove', 1, 24),
	(145, '�ؼ��ʹ���(�������)', 'admin_word-default', 1, 23),
	(146, '�ü�ͼƬ', 'user-cutoutimage', 0, 19),
	(147, '���ܹ��װ�', 'list-weekuserlist', 0, 18),
	(148, '�ܹ��װ�', 'list-allcredit', 0, 18),
	(149, '�޸��û���(�����û���)', 'admin_usergroup-change', 1, 24),
	(150, 'Rss����', 'list-rss', 0, 18),
	(151, '��̨������¼(��վ����)', 'admin_log-default', 1, 21),
	(152, '���ն���Ϣ', 'pms-default|pms-box|pms-setread', 0, 19),
	(153, 'ɾ������Ϣ', 'pms-remove', 0, 19),
	(154, '���Ͷ���Ϣ', 'pms-sendmessage|pms-checkrecipient', 0, 19),
	(155, '�����б�', 'pms-blacklist', 0, 19),
	(156, 'վ�ڹ���(��վ����)', 'admin_setting-notice', 1, 21),
	(157, 'ɾ��ͬ���(ǰ̨ͬ��ʹ���)', 'synonym-removesynonym', 0, 20),
	(158, '�鿴ͬ���(ǰ̨ͬ��ʹ���)', 'synonym-view', 0, 20),
	(159, '�༭ͬ���(ǰ̨ͬ��ʹ���)', 'synonym-savesynonym', 0, 20),
	(160, 'ͬ����б�(��̨ͬ��ʹ���)', 'admin_synonym-default', 1, 23),
	(161, '����ͬ���(��̨ͬ��ʹ���)', 'admin_synonym-search', 1, 23),
	(162, 'ɾ��ͬ���(��̨ͬ��ʹ���)', 'admin_synonym-delete', 1, 23),
	(163, '�༭ͬ���(��̨ͬ��ʹ���)', 'admin_synonym-save', 1, 23),
	(164, '�����ſ�ͳ��(��̨ͳ��)', 'admin_statistics-stand', 1, 28),
	(165, '�������а�(��̨ͳ��)', 'admin_statistics-cat_toplist', 1, 28),
	(166, '�������а�(��̨ͳ��)', 'admin_statistics-doc_toplist', 1, 28),
	(167, '�༭���а�(��̨ͳ��)', 'admin_statistics-edit_toplist', 1, 28),
	(168, '�������а�(��̨ͳ��)', 'admin_statistics-credit_toplist', 1, 28),
	(169, '�����Ŷ�(��̨ͳ��)', 'admin_statistics-admin_team', 1, 28),
	(170, 'UC����һ�', 'exchange-default', 2, 19),
	(174, '�������', 'doc-immunity', 0, 20),
	(176, '�༭ģ���ļ������', 'admin_style-editxml', 1, 26),
	(177, '�༭ģ�������ļ������', 'admin_style-edit', 1, 26),
	(178, '��ȡģ���ļ������', 'admin_style-readfile', 1, 26),
	(179, '����ģ���ļ������', 'admin_style-savefile', 1, 26),
	(181, 'ж��ģ�棨���', 'admin_style-removestyle', 1, 26),
	(183, '�ɰ�װģ���б����', 'admin_style-list', 1, 26),
	(184, '��װģ�棨���', 'admin_style-install', 1, 26),
	(185, '��ʾ����б�', 'admin_adv-default', 0, 21),
	(186, '���ù����ط�ʽ', 'admin_adv-config', 0, 21),
	(187, '�������(��̨)', 'admin_adv-search', 0, 21),
	(188, '��ӹ��', 'admin_adv-add', 0, 21),
	(189, '�༭���', 'admin_adv-edit', 0, 21),
	(190, 'ɾ�����', 'admin_adv-remove', 0, 21),
	(191, '����û�', 'admin_user-checkup', 1, 24),
	(192, '������û��б�', 'admin_user-uncheckeduser', 1, 24),
	(193, 'ע�����', 'admin_setting-baseregister', 0, 21),
	(201, '��㿴��', 'doc-random', 0, 18),
	(202, '�˴�����������', 'doc-vote', 0, 18),
	(203, '�����·��ҳ��', 'admin_style-add', 0, 26),
	(204, '�����·��', 'admin_style-createstyle', 0, 26),
	(206, 'Ƶ���б�Ƶ����', 'admin_channel-default', 1, 21),
	(207, '���Ƶ����Ƶ����', 'admin_channel-add', 1, 21),
	(208, '�༭Ƶ����Ƶ����', 'admin_channel-edit', 1, 21),
	(209, 'ɾ��Ƶ����Ƶ����', 'admin_channel-remove', 1, 21),
	(210, '�޸�Ƶ����ʾ˳��Ƶ����', 'admin_channel-updateorder', 1, 21),
	(211, '����޸Ĳο�����', 'reference-add', 0, 20),
	(212, 'ɾ���ο�����', 'reference-remove', 0, 20),
	(213, '�ϴ�����', 'attachment-upload', 0, 20),
	(214, 'ȡ���������', 'doc-removefocus', 0, 20),
	(215, '�Զ�����', 'doc-autosave', 0, 20),
	(216, 'ɾ���Զ�����Ĵ���', 'doc-delsave', 0, 24),
	(217, '�Զ��������', 'doc-managesave', 0, 24),
	(218, 'ͨ��֤Ȩ��', 'passport_client-login|passport_client-logout', 0, 21),
	(219, 'ȡ���Ƽ����������������', 'admin_doc-cancelrecommend', 0, 23),
	(220, '��ش���Ȩ��', 'doc-getrelateddoc|doc-addrelatedoc', 0, 20),
	(251, '����վ����', 'admin_recycle-default|admin_recycle-search|admin_recycle-remove|admin_recycle-recover|admin_recycle-clear', 1, 23),
	(252, 'SQL��ѯ���ڣ����ݿ����', 'admin_db-sqlwindow', 1, 27),
	(253, '��������Ϣ', 'admin_log-phpinfo', 1, 21),
	(254, 'ģ��߼��༭', 'admin_style-advancededit', 1, 26),
	(255, '�����ƴ���', 'admin_cooperate-default', 1, 23),
	(256, '��������', 'admin_hotsearch-default', 1, 23),
	(257, 'ͼƬ�ٿ�', 'admin_image-default|admin_image-editimage|admin_image-remove', 1, 23),
	(258, '��ش���', 'admin_relation-default', 1, 23),
	(259, 'ǰ̨��ش���', 'doc-cooperate', 0, 18),
	(260, '�汾����', 'admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_edition-remove', 1, 23),
	(261, '��ӽ��(�����û�)', 'admin_user-addcoins', 1, 24),
	(262, '��Ʒ�̵�', 'admin_gift-default|admin_gift-view|admin_gift-search|admin_gift-add|admin_gift-edit|admin_gift-remove|admin_gift-available|admin_gift-price|admin_gift-notice|admin_gift-log|admin_gift-verify', 1, 23),
	(263, '��Ʒ�̵�', 'gift-default|gift-view|gift-search|gift-apply', 0, 18),
	(264, 'ͨ��֤����', 'admin_setting-passport', 1, 21),
	(265, 'ͼƬ�ٿ�', 'pic-piclist|pic-view|pic-ajax|pic-search', 0, 18),
	(266, 'Ⱥ������Ϣ', 'pms-publicmessage', 0, 19),
	(267, '���ɼ�����', 'admin_setting-anticopy', 1, 21),
	(268, 'ͼƬˮӡ', 'admin_setting-watermark|admin_setting-preview', 1, 21),
	(269, '��̨�б���ʾ', 'admin_setting-listdisplay', 1, 21),
	(270, '����ˮ����', 'admin_setting-sec', 1, 21),
	(271, '��֤������', 'admin_setting-code', 0, 21),
	(272, 'ʱ������', 'admin_setting-time', 0, 21),
	(273, 'COOKIE����', 'admin_setting-cookie', 0, 21),
	(274, '��������', 'admin_setting-docset', 0, 21),
	(275, '��ҳ����', 'admin_setting-index', 0, 21),
	(276, '��������', 'admin_setting-search', 0, 21),
	(277, '����JS����', 'datacall-js', 2, 18),
	(278, '�ղش���', 'user-addfavorite', 2, 20),
	(279, 'ɾ�������ղ�', 'user-removefavorite|user-exchange', 2, 20),
	(280, '��ӱ༭���ࣨ�������', 'admin_category-batchedit',1, 25),
	(281, '������-�����б�', 'archiver-default|archiver-list|archiver-view',2, 20),
	(282, '����ע��', 'user-invite', 0, 19),
	(283, '�ʼ���������(��վ����)', 'admin_setting-noticemail', 1, 21),
	(284, 'Sitemap����', 'admin_sitemap-default|admin_sitemap-setting|admin_sitemap-createdoc|admin_sitemap-updatedoc|admin_sitemap-submit|admin_sitemap-baiduxml', 1, 21),
	(285, '�������н�ҳ��', 'search-agent', 2, 18),
	(286, '�ٿ�����', 'admin_hdapi-set|admin_hdapi-nosynset|admin_hdapi-down|admin_hdapi-info|admin_hdapi-default|admin_hdapi-siteuserinfo|admin_hdapi-titles|admin_hdapi-import|admin_hdapi-rolldocs|admin_hdapi-registercheck|admin_hdapi-login|admin_hdapi-privatedoc', 1, 21),
	(287, '���ݿ��С', 'admin_main-datasize', 2, 27),
	(288, '��̨��ݲ˵�', 'admin_setting-shortcut', 2, 21),
	(289, '���ݿ�洢����', 'admin_db-storage|admin_db-convert', 2, 27),
	(290, '���ô�������ĸ', 'doc-editletter', 0, 20),
	(291, '�������', 'admin_share-default|admin_share-search|admin_share-share|admin_share-set', 2, 23),
	(292, 'ģ��༭', 'admin_theme-default|admin_theme-editxml|admin_theme-add|admin_theme-create|admin_theme-createstyle|admin_theme-edit|admin_theme-codeedit|admin_theme-delbak|admin_theme-baseedit|admin_theme-advancededit|admin_theme-readfile|admin_theme-savefile|admin_theme-removestyle|admin_theme-setdefaultstyle|admin_theme-list|admin_theme-install|admin_theme-saveblock|admin_theme-delblock|admin_theme-preview|admin_theme-addblock|admin_theme-getconfig|admin_theme-savetemp', 0, 21),
	(293, '���ݵ�����ز���', 'admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacal', 2, 23),
	(294, '��̨�˵�����', 'admin_actions-default', 1, 20),
	(295, 'ľ��ɨ��', 'admin_filecheck-docreate|admin_safe-default|admin_safe-setting|admin_safe-scanfile|admin_safe-validate|admin_safe-scanfuns|admin_safe-list|admin_safe-editcode|admin_safe-del', 2, 21),
	(296, '�Զ�����', 'admin_upgrade-default|admin_upgrade-check|admin_upgrade-initpage|admin_upgrade-install', 1, 21),
	(297, 'Map', 'admin_actions-map', 1, 20),
	(298, '����ģ��', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status', 1, 21);
	
INSERT INTO wiki_language (`name`, `available`, `path`, `copyright`) VALUES 
	('��������', 1, 'zh', 'baike.com');

INSERT INTO wiki_theme (`name`, `available`, `path`, `copyright`, `css`) VALUES
	('Ĭ�Ϸ��', 1, 'default', 'baike.com', 'a:18:{s:8:"bg_color";s:11:"transparent";s:14:"left_framcolor";s:7:"#e6e6e6";s:16:"leftitle_bgcolor";s:7:"#f7f7f8";s:18:"leftitle_framcolor";s:7:"#efefef";s:16:"middle_framcolor";s:7:"#eaf1f6";s:19:"middletitle_bgcolor";s:7:"#eaf6fd";s:21:"middletitle_framcolor";s:7:"#c4d2db";s:15:"right_framcolor";s:7:"#cef2e0";s:17:"rightitle_bgcolor";s:7:"#cef2e0";s:19:"rightitle_framcolor";s:7:"#a3bfb1";s:13:"nav_framcolor";s:7:"#e1e1e1";s:11:"nav_bgcolor";s:7:"#aaaeb1";s:13:"nav_linkcolor";s:4:"#fff";s:13:"nav_overcolor";s:4:"#ff0";s:8:"nav_size";s:4:"14px";s:10:"bg_imgname";s:11:"html_bg.jpg";s:13:"titbg_imgname";s:10:"col_bg.jpg";s:4:"path";s:7:"default";}');


INSERT INTO wiki_regulargroup (`id`, `title`, `size`, `type`) VALUES
	(18, 'ҳ�����', 0, 0),
	(19, '�û�����', 0, 0),
	(20, '��������', 0, 0),
	(21, '��վ����', 0, 1),
	(22, '�������', 0, 1),
	(23, '���ݹ���', 0, 1),
	(24, '�û�����', 0, 1),
	(25, '�������', 0, 1),
	(26, '����/���', 0, 1),
	(27, '���ݿ����', 0, 1),
	(28, 'վ��ͳ��', 0, 1);

INSERT INTO wiki_block (`id`, `theme`, `file`, `area`, `areaorder`, `block`, `fun`, `tpl`, `params`, `modified`) VALUES
(1, 'default', 'index', 'ctop1', 0, 'doc', 'hotdocs', 'hotdocs.htm', 'a:2:{s:3:"num";s:0:"";s:5:"style";s:0:"";}', NULL),
(2, 'default', 'index', 'ctop2', 0, 'doc', 'wonderdocs', 'wonderdocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(3, 'default', 'index', 'right', 0, 'user', 'login', 'login.htm', '', NULL),
(4, 'default', 'index', 'right', 1, 'news', 'recentnews', 'recentnews.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(5, 'default', 'index', 'right', 2, 'doc', 'cooperatedocs', 'cooperatedocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(6, 'default', 'index', 'cbottoml', 0, 'doc', 'recentdocs', 'recentdocs.htm', 'a:2:{s:3:"num";s:0:"";s:5:"style";s:0:"";}', NULL),
(7, 'default', 'index', 'cbottoml', 1, 'comment', 'recentcomment', 'recentcomment.htm', 'a:1:{s:3:"num";s:0:"";}', NULL),
(8, 'default', 'index', 'cbottomr', 0, 'pic', 'recentpics', 'recentpics.htm', 'a:1:{s:3:"num";s:0:"";}', NULL),
(9, 'default', 'index', 'cbottomr', 1, 'doc', 'commenddocs', 'commenddocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(10, 'default', 'index', 'bottom', 0, 'doc', 'hottags', 'hottags.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(11, 'default', 'giftlist', 'price', 0, 'gift', 'giftpricerange', 'giftpricerange.htm', '', NULL),
(12, 'default', 'giftlist', 'right', 0, 'gift', 'giftnotice', 'giftnotice.htm', '', NULL),
(13, 'default', 'categorylist', 'right', 0, 'doc', 'hottags', 'hottags.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(14, 'default', 'categorylist', 'right', 1, 'doc', 'getletterdocs', 'getletterdocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(15, 'default', 'viewcomment', 'right', 0, 'doc', 'hotcommentdocs', 'hotcommentdocs.htm', 'a:1:{s:3:"num";s:2:"10";}', NULL),
(16, 'default', 'index', 'bottom', 1, 'links', 'friendlinks', 'friendlinks.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(17, 'default', 'index', 'right', 3, 'doc', 'getletterdocs', 'getletterdocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL);

INSERT INTO wiki_datacall (`id`, `name`, `type`, `category`, `classname`, `function`, `desc`, `param`, `cachetime`, `available`) VALUES
(1, 'doc_most_visited', 'sql', 'doc', 'sql', 'sql', '����������', 'a:9:{s:7:"tplcode";s:175:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]</a></dt><dd>[summary][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd>	</dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:84:"select did,title,summary from {DB_TABLEPRE}doc where 1 order by views desc limit 10;";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(2, 'user_total_num', 'sql', 'user', 'sql', 'sql', 'ע���Ա��', 'a:9:{s:7:"tplcode";s:9:"[usernum]";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:49:"SELECT COUNT(*) AS usernum FROM {DB_TABLEPRE}user";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(3, 'doc_total_num', 'sql', 'doc', 'sql', 'sql', '��վ���д�����', 'a:9:{s:7:"tplcode";s:20:"doc total num: [num]";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:0:"";s:6:"select";s:18:"count(did) as num ";s:4:"from";s:16:"{DB_TABLEPRE}doc";s:5:"where";s:1:"1";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:3:"0,1";}', 1000, 1),
(4, 'doc_most_comment', 'sql', 'doc', 'sql', 'sql', '�����������б�', 'a:9:{s:7:"tplcode";s:181:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]([num])</a></dt><dd>[comment][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd></dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:0:"";s:6:"select";s:44:"d.did,d.title,c.comment, count(c.did) AS num";s:4:"from";s:68:"{DB_TABLEPRE}doc AS d LEFT JOIN {DB_TABLEPRE}comment AS c USING(did)";s:5:"where";s:0:"";s:5:"other";s:14:"GROUP BY c.did";s:7:"orderby";s:8:"num desc";s:5:"limit";s:4:"0,10";}', 1000, 1),
(5, 'doc_recommends', 'sql', 'doc', 'sql', 'sql', '�Ƽ�������Ϣ', 'a:9:{s:7:"tplcode";s:175:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]</a></dt><dd>[summary][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd>	</dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:91:"select did,title,summary from {DB_TABLEPRE}focus where `type`=1 order by did desc limit 10;";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(6, 'doc_wonderful', 'sql', 'doc', 'sql', 'sql', '���ʴ���', 'a:9:{s:7:"tplcode";s:175:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]</a></dt><dd>[summary][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd>	</dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:91:"select did,title,summary from {DB_TABLEPRE}focus where `type`=3 order by did desc limit 10;";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(7, 'user_new_register', 'sql', 'user', 'sql', 'sql', '����ע���û�', 'a:9:{s:7:"tplcode";s:104:"<dl class="col-dl "><dt><a href="index.php?user-space-[uid]"> [username]</a></dt><dd>[regtime]</dd></dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:109:"SELECT uid,username,from_unixtime( regtime ) as regtime  FROM {DB_TABLEPRE}user order by regtime desc limit 1";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(8, 'cat_doc', 'sql', 'doc', 'sql', 'sql', '����ĳ�������µĴ���,�������ӷ��ࣻ\r\nĬ���ǵ��÷���ID����10�Ĵ���,\r\n����������������´������޸ġ�SQL�������ʽ������cid=10��10��ֵ', 'a:9:{s:7:"tplcode";s:175:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]</a></dt><dd>[summary][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd>	</dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:116:"select did,title,summary from {DB_TABLEPRE}doc WHERE did IN (select did from {DB_TABLEPRE}categorylink where cid=10)";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(9, 'cat_subcat', 'sql', 'category', 'sql', 'sql', '����ĳ���������ӷ���,\r\nĬ���ǵ��÷���ID����3���ӷ���,\r\n������������������ӷ��࣬�޸ġ�SQL�������ʽ������pid=3��pidֵ', 'a:9:{s:7:"tplcode";s:101:"<dl class="col-dl "><dd><a title="[title]" href="index.php?category-view-[cid]"> [name]</a></dd></dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:57:"select  cid, name  from {DB_TABLEPRE}category where pid=3";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1);
INSERT INTO wiki_regular_relation (`idleft`, `idright`) VALUES
	(5, 3),
	(5, 18),
	(19, 18),
	(21, 20),
	(30, 29),
	(34, 36),
	(40, 43),
	(40, 41),
	(40, 42),
	(48, 50),
	(52, 51),
	(53, 51),
	(54, 51),
	(62, 60),
	(63, 64),
	(63, 65),
	(63, 60),
	(63, 61),
	(63, 62),
	(65, 64),
	(67, 70),
	(67, 69),
	(67, 71),
	(67, 72),
	(67, 68),
	(67, 66),
	(68, 72),
	(68, 71),
	(68, 70),
	(68, 69),
	(68, 66),
	(71, 69),
	(79, 162),
	(79, 83),
	(79, 80),
	(79, 78),
	(79, 77),
	(79, 75),
	(79, 85),
	(79, 81),
	(79, 73),
	(88, 87),
	(88, 89),
	(88, 90),
	(88, 92),
	(88, 91),
	(96, 95),
	(96, 93),
	(96, 94),
	(96, 97),
	(100, 98),
	(100, 99),
	(100, 101),
	(100, 102),
	(102, 101),
	(109, 108),
	(109, 113),
	(109, 112),
	(109, 111),
	(109, 110),
	(109, 107),
	(117, 114),
	(117, 116),
	(117, 115),
	(129, 127),
	(129, 131),
	(129, 130),
	(129, 128),
	(131, 130),
	(140, 137),
	(140, 138),
	(140, 139),
	(144, 143),
	(144, 142),
	(144, 141),
	(152, 51),
	(153, 51),
	(153, 154),
	(153, 155),
	(153, 152),
	(154, 51),
	(155, 51),
	(157, 159),
	(157, 158),
	(162, 161),
	(162, 160),
	(162, 163);
INSERT INTO wiki_navmodel VALUES (1, '����+���Ӽ���', '<p class="bor-ccc bg_g">�롰�����Ļ�����ԣ������������ʷʵ��������������ĸ��־����Ļ����������̵��ҹ������赸��</p>\r\n<table class="table">\r\n	<tr>\r\n		<td>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%9C%9D%E9%98%B3%E6%B0%91%E9%97%B4%E7%A7%A7%E6%AD%8C"  target="_blank" title="����������">\r\n		����������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%A4%A7%E6%A0%85%E6%A0%8F%E4%BA%94%E6%96%97%E6%96%8B%E9%AB%98%E8%B7%B7%E7%A7%A7%E6%AD%8C"  target="_blank"title="��դ���嶷ի�������">\r\n		��դ���嶷ի�������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%BA%AC%E8%A5%BF%E5%A4%AA%E5%B9%B3%E9%BC%93" target="_blank" title="����̫ƽ��">\r\n		����̫ƽ��</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%89%93%E9%94%A3%E9%95%B2" target="_blank" title="������">\r\n		������</a> <br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%9C%AC%E6%BA%AA%E7%A4%BE%E7%81%AB" target="_blank" title="��Ϫ���">\r\n		��Ϫ���</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%82%A3%E6%97%8F%E7%99%BD%E8%B1%A1%E8%88%9E" target="_blank" title="���������">\r\n		���������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%AF%86%E4%BA%91%E8%9D%B4%E8%9D%B6%E4%BC%9A" target="_blank" title="���ƺ�����">\r\n		���ƺ�����</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%BB%B6%E5%BA%86%E6%97%B1%E8%88%B9" target="_blank" title="���캵��">\r\n		���캵��</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%80%9A%E5%B7%9E%E8%BF%90%E6%B2%B3%E9%BE%99%E7%81%AF" target="_blank" title="ͨ���˺�����">\r\n		ͨ���˺�����</a> <br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%9D%BF%E5%87%B3%E9%BE%99" target="_blank" title="�����">\r\n		�����</a> </td>\r\n		<td>\r\n		<p>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%87%91%E5%B7%9E%E9%BE%99%E8%88%9E" target="_blank" title="��������">\r\n		��������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%9D%92%E9%BE%99%E5%9C%AA%E6%A0%8F%E6%A3%92" target="_blank" title="����������">\r\n		����������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E7%9B%96%E5%B7%9E%E9%AB%98%E8%B7%B7%E7%A7%A7%E6%AD%8C" target="_blank" title="���ݸ������">\r\n		���ݸ������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B8%8A%E5%8F%A3%E5%AD%90%E9%AB%98%E8%B7%B7%E7%A7%A7%E6%AD%8C" target="_blank" title="�Ͽ��Ӹ������">\r\n		�Ͽ��Ӹ������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%AE%89%E5%BA%B7%E5%B0%8F%E5%9C%BA%E5%AD%90" target="_blank" title="����С����">\r\n		����С����</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%80%9A%E5%9F%8E%E6%8B%8D%E6%89%93" target="_blank" title="ͨ���Ĵ�">\r\n		ͨ���Ĵ�</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E7%99%BD%E7%BA%B8%E5%9D%8A%E5%A4%AA%E7%8B%AE" target="_blank" title="��ֽ��̫ʨ">\r\n		��ֽ��̫ʨ</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B8%9C%E5%82%A8%E5%8F%8C%E9%BE%99%E4%BC%9A" target="_blank" title="����˫����">\r\n		����˫����</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%92%B5%E8%8A%B1" target="_blank" title="�컨">\r\n		�컨</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%98%93%E5%8E%BF%E6%91%86%E5%AD%97%E9%BE%99%E7%81%AF" target="_blank" title="���ذ�������">\r\n		���ذ�������</a></p>\r\n		</td>\r\n		<td>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%9E%91%E5%AD%90%E7%A7%A7%E6%AD%8C" target="_blank" title="�������">\r\n		�������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E8%B5%AB%E5%93%B2%E6%97%8F%E8%90%A8%E6%BB%A1%E8%88%9E" target="_blank" title="������������">\r\n		������������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E7%B1%B3%E7%B2%AE%E5%B1%AF%E9%AB%98%E8%B7%B7" target="_blank" title="�����͸���">\r\n		�����͸���</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E7%81%AB%E7%BB%AB%E5%AD%90%E4%BC%9E%E8%88%9E" target="_blank" title="�����ɡ��">\r\n		�����ɡ��</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%85%AB%E9%97%BD%E5%8D%83%E5%A7%BF%E8%88%9E" target="_blank" title="����ǧ����">\r\n		����ǧ����</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%BE%B7%E5%AE%89%E6%BD%98%E5%85%AC%E6%88%8F" target="_blank" title="�°��˹�Ϸ">\r\n		�°��˹�Ϸ</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B9%8C%E6%8B%89%E6%BB%A1%E6%97%8F%E7%A7%A7%E6%AD%8C" target="_blank" title="�����������">\r\n		�����������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%98%8C%E5%B9%B3%E5%90%8E%E7%89%9B%E5%9D%8A%E6%9D%91%E8%8A%B1%E9%92%B9%E5%A4%A7%E9%BC%93" target="_blank" title="��ƽ��ţ���廨����">\r\n		��ƽ��ţ���廨����</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E8%B5%9E%E7%9A%87%E9%93%81%E9%BE%99%E7%81%AF" target="_blank" title="�޻�������">\r\n		�޻�������</a>\r\n		</td>\r\n		<td>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%86%89%E9%BE%99%E8%88%9E" target="_blank" title="������">������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%9A%86%E5%B0%A7%E6%8B%9B%E5%AD%90%E9%BC%93" target="_blank" title="¡Ң���ӹ�">\r\n		¡Ң���ӹ�</a> <br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%99%8B%E5%B7%9E%E5%AE%98%E4%BC%9E" target="_blank" title="���ݹ�ɡ">\r\n		���ݹ�ɡ</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%A9%AC%E9%B9%BF%E8%88%9E" target="_blank" title="��¹��">\r\n		��¹��</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B8%B0%E5%AE%81%E8%9D%B4%E8%9D%B6%E8%88%9E" target="_blank" title="����������">\r\n		����������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%B4%87%E4%BB%81%E8%B7%B3%E5%85%AB%E4%BB%99" target="_blank" title="����������">\r\n		����������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B8%89%E8%8A%82%E9%BE%99%C2%B7%E8%B7%B3%E9%BC%93" target="_blank" title="������������">\r\n		������������</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%B5%B7%E6%B7%80%E6%89%91%E8%9D%B4%E8%9D%B6" target="_blank" title="�����˺���">\r\n		�����˺���</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E8%B4%AF%E6%BA%AA%E6%9D%91%E5%9C%B0%E5%9B%B4%E5%AD%90" target="_blank" title="��Ϫ���Χ��">\r\n		��Ϫ���Χ��</a>\r\n		</td>\r\n	</tr>\r\n</table>\r\n', 1),
								   (2, '���б��', '<table class="table">
	<tr>
		<td class="w-70">�������</td>
		<td>���˼��</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E7%AB%A0%E6%B3%BD%E5%A4%A9" target="_blank" title="������">������</a></td>
		<td>Ů�������Ͼ��ˣ��Ͼ������ѧУѧ����2009��12���������ߺ죬�����ѳ�Ϊ�̲���ü��2011��1��4�գ��廪��ѧ֤ʵ���������ѧ���������ȷ���廪��ѧ�϶�Ϊ�ĿƱ�������</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E5%8D%96%E8%8F%9C%E5%93%A5" target="_blank" title="���˸�">���˸�</a>
		</td>
		<td>����ʱ�ڣ��������ź��޷���С��������³ľ��һ������С�����⣬���۽��������۸߳���ëǮ�ĸ����߲ˣ�����֮��������ͷ�ܶ������ϰ������еس�Ϊ�����˸硱�� 
		<br />
		</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E8%8B%8F%E7%B4%AB%E7%B4%AB" target="_blank" title="������">
		������</a></td>
		<td>Ů������ʡ�˲����ˣ��й������ѧ����ϵ���꼶ѧ����Ҳ��һ������ģ�ء�2010��12�£�����ѧУ����˸���������˽��չ������ѧУ��ʦͬѧ�����ۣ����ܰ��ۡ�</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E6%8A%80%E6%9C%AF%E5%A5%B3" target="_blank" title="����Ů">
		����Ů</a></td>
		<td>ĳ����Ů��һλ����jack weppler �����ѷ���֮��������N�����ѵ�PS��Ƭ����Ҫ��PS���֣���Ȼ���ϴ������ϣ���ͨ��SEO����Google 
		ͼƬ����jack weppler ֮��������Щ��Ƭ��</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E6%B5%87%E6%B0%B4%E5%93%A5" target="_blank" title="��ˮ��">��ˮ��</a></td>
		<td>���³����ݻ��շ�������&quot;��ˮ��&quot;¥���Ȼ��ߺ죬�ϰ�󲻾ã�����ˮ�硱����Ƭ���˷����������������Ķ����������Ѹ�Ц�ĸ�����һ����ʹ���ڵ�λ�����ˡ�</td>
	</tr>
</table>
', 1),
								   (3, '��������', '<P class="bor-ccc bg_g">��վ������������Ϊ����Э������������վ�۵㡣�ɴ˲����ĺ�����ڱ�վ�޹ء�</P>', 1),
								   (4, '���б��_�б���', '<table class="table">
	<tr>
		<td class="bold" colspan="2"><strong>2010�����ʮ���������</strong></td>
	</tr>
	<tr>
		<td class="w-70">������� </td>
		<td>���˼��</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E7%AB%A0%E6%B3%BD%E5%A4%A9" target="_blank" title="������">������</a></td>
		<td>Ů�������Ͼ��ˣ��Ͼ������ѧУѧ����2009��12���������ߺ죬�����ѳ�Ϊ�̲���ü��2011��1��4�գ��廪��ѧ֤ʵ���������ѧ���������ȷ���廪��ѧ�϶�Ϊ�ĿƱ�������</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E5%8D%96%E8%8F%9C%E5%93%A5" target="_blank" title="���˸�">���˸�</a>
		</td>
		<td>����ʱ�ڣ��������ź��޷���С��������³ľ��һ������С�����⣬���۽��������۸߳���ëǮ�ĸ����߲ˣ�����֮��������ͷ�ܶ������ϰ������еس�Ϊ�����˸硱�� 
		</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E8%8B%8F%E7%B4%AB%E7%B4%AB" target="_blank" title="������">
		������</a></td>
		<td>Ů������ʡ�˲����ˣ��й������ѧ����ϵ���꼶ѧ����Ҳ��һ������ģ�ء�2010��12�£�����ѧУ����˸���������˽��չ������ѧУ��ʦͬѧ�����ۣ����ܰ��ۡ�</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E6%8A%80%E6%9C%AF%E5%A5%B3" target="_blank" title="����Ů">
		����Ů</a></td>
		<td>ĳ����Ů��һλ����jack weppler �����ѷ���֮��������N�����ѵ�PS��Ƭ����Ҫ��PS���֣���Ȼ���ϴ������ϣ���ͨ��SEO����Google 
		ͼƬ����jack weppler ֮��������Щ��Ƭ��</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E6%B5%87%E6%B0%B4%E5%93%A5" target="_blank" title="��ˮ��">��ˮ��</a></td>
		<td>���³����ݻ��շ�������&quot;��ˮ��&quot;¥���Ȼ��ߺ죬�ϰ�󲻾ã�����ˮ�硱����Ƭ���˷����������������Ķ����������Ѹ�Ц�ĸ�����һ����ʹ���ڵ�λ�����ˡ�</td>
	</tr>
</table>
', 1),
								   (5, 'ͼ�Ļ���', '<table class="table" vertical-align="top">\r\n	<tr>\r\n		<td rowspan="2">\r\n		<div class="img img_l" style="WIDTH: 140px">\r\n			<a href="style/default/zt.jpg" target="_blank" title="����ͼƬ">\r\n			<img alt="����ͼƬ" src="style/default/zt.jpg" title="����ͼƬ"/></a><br />\r\n			<strong>����ͼƬ</strong></div>\r\n		</td>\r\n		<td class="bold">ģ�����</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="p_a_m">\r\n		<p>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">\r\n		���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="���ݴ���">���ݴ���</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="ģ�����">\r\n		ģ�����</a> </p>\r\n		</td>\r\n	</tr>\r\n</table>', 1);
INSERT INTO wiki_channel VALUES 	(1, '��ҳ', '{$site_url}', 0, 1, 2),
									(2, '�ٿƷ���', '{$site_url}/index.php?category', 1, 1, 2),
									(3, '���а�', '{$site_url}/index.php?list', 2, 1, 2),
									(4, 'ͼƬ�ٿ�', '{$site_url}/index.php?pic-piclist-2', 3, 1, 2),
									(5, '��Ʒ�̵�', '{$site_url}/index.php?gift', 4, 1, 2);								   
EOT;

 	$strtip=runquery($installsql);

/* 
	$pluginbase = new pluginbase($db);
	$pluginbase->install('hdapi');
 	$pluginbase->install('ucenter');
*/	
					if (mysql_error()) {
						$str = "<SPAN class=err>" . $strtip . ' ' . mysql_error() . "</span>";
						$nextAccess = 0;
						$extend .= '{W}'.$strtip . ' ' . mysql_error()."\n";
					}
					if($nextAccess==1){
						$str = "<div id=\"tips\">{$lang['stepSetupDelInstallDirTip']}</div>";
						$str .="<div id=\"wrapper_1\"><div class=\"col\"><br />$strcretip $msg<br /></div></div>";
					}
					if ($nextAccess == 1) {
	                    @cleardir(HDWIKI_ROOT.'/data/view');
	                    @cleardir(HDWIKI_ROOT.'/data/cache');
	                    @forceMkdir(HDWIKI_ROOT.'/data/attachment');
	                    @forceMkdir(HDWIKI_ROOT.'/data/backup');
	                    @forceMkdir(HDWIKI_ROOT.'/data/cache');
						@forceMkdir(HDWIKI_ROOT.'/data/db_backup');
	                    @forceMkdir(HDWIKI_ROOT.'/data/logs');
						@forceMkdir(HDWIKI_ROOT.'/data/tmp');
	                    @forceMkdir(HDWIKI_ROOT.'/data/view');
	                    @forceMkdir(HDWIKI_ROOT.'/data/momo');
					}
				}
			}
			break;
			case 6:
				//������
				clode_register_install();
				$str ="<div id=\"wrapper\"><div class=\"col\">" .
	"<h3>��Ӱ����ĵ�</h3>
	<p><input name='istestdata' type='checkbox' value='1' checked='checked' />��Ӱ����ĵ�</p>
	</div></div>";
				break;
			case 7:
			//����������ݡ�
			if($_POST['istestdata']){
				require_once HDWIKI_ROOT.'/config.php';
				require_once HDWIKI_ROOT.'/lib/hddb.class.php';
				$db = new hddb(DB_HOST, DB_USER, DB_PW, DB_NAME, DB_CHARSET);
				$sqltestfile= HDWIKI_ROOT.'/install/testdata/hdwikitest.sql';
				$fp = fopen($sqltestfile, 'rb');
				$sql=fread($fp, filesize($sqltestfile));
				fclose($fp);
				$user=$db->result_first('select username from '.DB_TABLEPRE.'user where uid=1');
				if($user!='admin'){
					$sql=str_replace('admin',$user,$sql);
				}
				runquery($sql);
				
				$sql = "INSERT INTO wiki_setting (`variable`, `value`) VALUES ('hotsearch', '".serialize(array ( 0 => array ( 'name' => 'HDwiki', 'url' => 'index.php?doc-view-51', ), 1 => array ( 'name' => 'Э����', 'url' => 'index.php?doc-view-34', ), 2 => array ( 'name' => 'Wiki��BBS', 'url' => 'index.php?doc-view-22', ), 3 => array ( 'name' => 'Wiki', 'url' => 'index.php?doc-view-21', ), 4 => array ( 'name' => 'Wiki��Blog', 'url' => 'index.php?doc-view-23' )))."'),('cooperatedoc', '������;����;�����;ά������¹;������;��ԭȮ��;ָ����;��̡;�����'),('hottag', '".serialize(array ( 0 => array ( 'tagname' => '����', 'tagcolor' => '', ), 1 => array ( 'tagname' => '��', 'tagcolor' => '', ), 2 => array ( 'tagname' => 'HDwiki', 'tagcolor' => '', ), 3 => array ( 'tagname' => '��', 'tagcolor' => '', ), 4 => array ( 'tagname' => 'С��è', 'tagcolor' => '', ), 5 => array ( 'tagname' => '�����', 'tagcolor' => 'red', ), 6 => array ( 'tagname' => '������', 'tagcolor' => 'red', ), 7 => array ( 'tagname' => 'ѱ¹', 'tagcolor' => 'red', ), 8 => array ( 'tagname' => '٪���㹷', 'tagcolor' => 'red', ), 9 => array ( 'tagname' => '�߲�����', 'tagcolor' => '', ), 10 => array ( 'tagname' => '�������', 'tagcolor' => 'red', ), 11 => array ( 'tagname' => '������', 'tagcolor' => '', ), 12 => array ( 'tagname' => '��ȸ', 'tagcolor' => 'red', ), 13 => array ( 'tagname' => '�ɾӼ�', 'tagcolor' => 'red', ), 14 => array ( 'tagname' => '�������Ͻ������', 'tagcolor' => 'red', ), 15 => array ( 'tagname' => '���������', 'tagcolor' => '', ), 16 => array ( 'tagname' => '������έ����', 'tagcolor' => '', ), 17 => array ( 'tagname' => '������', 'tagcolor' => '')))."'),('advmode', '1');\n";
				$sql .= "REPLACE INTO wiki_category (`cid`, `pid`, `name`, `displayorder`, `docs`, `image`, `navigation`, `description`) VALUES
(1, 3, 'HDWIKI', 0, 18, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '1', 'name' => 'HDWIKI', )))."', ''),
(2, 3, '�����ٿ�', 2, 1, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '2', 'name' => '�����ٿ�' )))."', ''),
(3, 0, '�����ĵ�', 2, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), ))."', ''),
(4, 3, 'wik���', 1, 3, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '4', 'name' => 'wik���')))."', ''),
(5, 3, '�ʺ����', 3, 4, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '5', 'name' => '�ʺ����')))."', ''),
(6, 3, '�������', 4, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '6', 'name' => '�������' )))."', ''),
(7, 3, '�������', 5, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '7', 'name' => '�������' )))."', ''),
(8, 3, 'Ͷ�߽���', 6, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '8', 'name' => 'Ͷ�߽���' )))."', ''),
(9, 3, '�û����', 7, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '9', 'name' => '�û����' )))."', ''),
(10, 6, '������Ʒ���', 0, 8, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '6', 'name' => '�������' ), 2 => array ( 'cid' => '10', 'name' => '������Ʒ���' )))."', ''),
(11, 6, '���������淶', 1, 2, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '6', 'name' => '�������' ), 2 => array ( 'cid' => '11', 'name' => '���������淶' )))."', ''),
(12, 6, '�������ݱ�д�淶', 2, 12, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '�����ĵ�', ), 1 => array ( 'cid' => '6', 'name' => '�������' ), 2 => array ( 'cid' => '12', 'name' => '�������ݱ�д�淶' )))."', '');";
				runquery($sql);
				
				//get domain
				$server_name = $_SERVER['SERVER_NAME'];
				$domain = $server_name;
				if($domain) {
					$key = @util::hfopen('http://kaiyuan.hudong.com/count2/count.php?m=count&a=getkey&domain='.$domain, 0);
					if($key) {
						$sql = "INSERT INTO wiki_setting (`variable`, `value`) VALUES ('wk_count', '".serialize(array ('domain' => $domain, 'key' => $key))."');";		
						runquery($sql);
					}
				}
				
				copydir(HDWIKI_ROOT.'/install/testdata/201007',HDWIKI_ROOT.'/uploads/201007');
				copydir(HDWIKI_ROOT.'/install/testdata/201008',HDWIKI_ROOT.'/uploads/201008');
			}
			
			@touch(HDWIKI_ROOT.'/data/install.lock');
			$info['type']=1;
			$info['sitedomain']=$_SERVER['SERVER_NAME'];
			$info['siteaddress']=$site_url;
			$info['version']=HDWIKI_VERSION.HDWIKI_RELEASE.$lang['commonCharset'];
			$info = base64_encode(serialize($info));
			
			//install count
			require_once HDWIKI_ROOT.'/config.php';
			require_once HDWIKI_ROOT.'/lib/util.class.php';
			@util::hfopen('http://kaiyuan.hudong.com/count2/in.php?action=install', 0, 'info='.urlencode($info));
			
			$str = "<div id=\"wrapper1\"><span style=\"color:red\">{$lang['stepSetupSuccessTip']}</span></div>";
			//$str .= '<iframe id="count" name="count" src="http://kaiyuan.hudong.com/count2/interface.php?info='.$info.'" scrolling="no" width="455" style="height:370px" frameborder="0"></iframe>';
			$str .= '<br><br><a href="../">������ҳ</a>';
			break;
			/*
			case 8:
				require_once HDWIKI_ROOT.'/config.php';
				require_once HDWIKI_ROOT.'/lib/hddb.class.php';
				require_once HDWIKI_ROOT.'/lib/util.class.php';
				require_once HDWIKI_ROOT.'/lib/string.class.php';
				
				$db = new hddb(DB_HOST, DB_USER, DB_PW, DB_NAME, DB_CHARSET);
				//install 
				$setting=$db->result_first('select `value` from '.DB_TABLEPRE.'setting WHERE `variable` = \'site_appkey\'');
				if ($setting){
					echo "<span style='font-size:20px;'>�ٿ����˿�ͨ�ɹ�.</span><a href='../'>������ҳ</a>";
					break;
				}
				
				//update info
				$data = $_GET['info'];
				$data = str_replace(' ', '+', $data);
				$info = base64_decode($data);
				
				if ($info){
					$obj = unserialize($info);
					if(is_array($obj)){
						$url2 = 'http://localhost/count2/in.php?action=update&sitedomain='.$_SERVER['SERVER_NAME'].'&info='.$data;
						$data = util::hfopen($url2);
						//if gbk then toutf8
						if ($lang['commonCharset'] == 'GBK'){
							$obj['sitenick'] = string::hiconv($obj['sitenick'], 'gbk', 'utf-8');
						}
						
						$db->query("REPLACE INTO `".DB_TABLEPRE."setting` (`variable`, `value`)
							values ('user_nick', '{$obj['sitenick']}'), ('site_nick','{$obj['sitenick']}'), ('site_key','{$obj['sitekey']}'), ('site_appkey', '{$obj['appkey']}')");
							
						echo "<span style='font-size:20px;'>�ٿ����˿�ͨ�ɹ���</span><a href='../'>������ҳ</a>";
					}else{
						echo "<span style='font-size:20px;'>�ٿ����˿�ͨʧ�ܣ����¼�����̨��ͨ��</span><a href='../'>������ҳ</a>";
					}
				} else {
					echo "<span style='font-size:20px;'>�ٿ����˿�ͨʧ�ܣ����¼�����̨��ͨ��</span><a href='../'>������ҳ</a>";
				}
			break;
			*/
	}

	if ($nextAccess == 0) {
		$str .= "<br /><br /><input class=\"inbut\" type=\"button\" value=\"{$lang['commonPrevStep']}\" onclick=\"javascript: window.location=('$installfile?step=$prevStep');\">\n";
	}elseif($step>1&&$nextAccess&&$step<7){
		$str .= "<div id=\"wrapper2\"><input onclick=\"window.location='install.php?step=$prevStep';\" type=\"button\" value=\"{$lang['commonPrevStep']}\" class=\"inbut\"/>  <input type=\"submit\" value=\"{$lang['commonNextStep']}\" class=\"inbut1\"/ $alert></div>";
	}

	echo $str;
?>
<?php if($step!=7){?>
<INPUT type=hidden value=<?php echo $nextStep?> name="step">
</form><?php }?>
</div>
</div>
<div class="clear"></div>
<div id="footer">
<p>Powered by <a class="footlink" href="http://kaiyuan.hudong.com">HDWiki</a> V<?php echo HDWIKI_VERSION?>| &copy;2005-2010 <strong>Hudong</strong></p>
</div>
<?php 
	if($isone === false){
		$extend = urlencode($extend);
		$statistic = "<script src=\" {$apiurl}?domain={$site_url}&step={$step}&extend=$extend \"></script>";
		echo $statistic;
	}
?>
</div>
</body>
</html>