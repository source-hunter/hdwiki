<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<div class="w-950 hd_map"> <a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt;&gt;�������� &gt;&gt;<span id="catenavi">
  <?php foreach((array)$navigation as $key=>$category) {?>
  <a href="index.php?category-view-<?php echo $category['cid']?>"><?php echo $category['name']?> </a>&nbsp;&nbsp;
  <?php }?>
  </span> </div>
<div class="l w-710 o-v">
  <h1 class="title_thema"> <span id='doctitle'><?php echo $doc['doctitle']?></span>
    <label id='auditmsg'>
      <?php if($doc['visible']=='0') { ?>
      --�ô���δ�����
      <?php } ?>
    </label>
    <label id='lockimage'>
      <?php if($doc['locked']) { ?>
      <image src="style/default/lock.gif"/>
      <?php } ?>
    </label>
  </h1>
  <div class="subordinate">
    <p class="cate_open"> <span class="bold">��ǩ:</span>
      <?php if(count($doc['tag'] )>0) { ?>
      <?php foreach((array)$doc['tag'] as $key=>$tag) {?>
      <a href="index.php?search-tag-<?php echo $tag?>" name="tag"><?php echo $tag?></a>
      <?php }?>
      <?php } else { ?>
      <span name="nonetag">���ޱ�ǩ</span>
      <?php } ?>
      <?php if($doc_edit) { ?>
      <span class="w-110" onclick="Tag.box(<?php echo $doc['did']?>,this)"><a href="javascript:void(0);">�༭/��ӱ�ǩ</a></span>
      <?php } ?>
      <?php if($doc_editletter) { ?>
      <span class="w-110" onclick="Letter.box(<?php echo $doc['did']?>,this)"><a href="javascript:void(0);">���ô�������ĸ</a>
      <input type='hidden' id='fletter' value='<?php echo $docletter?>'>
      </span>
      <?php } ?>
    </p>
    <span class="editteam"> <a href="javascript:void(0)" id="ding" onclick="vote(this)">��[<span><?php echo $doc['votes']?></span>]</a> <a class="share_link" id="share_link">����</a> <a href="index.php?comment-view-<?php echo $doc['did']?>">��������(<?php echo $doc['comments']?>)</a> <a id="editImage" href="index.php?doc-edit-<?php echo $doc['did']?>"  class="edit_ct" onclick="return doc_is_locked()">�༭����</a>
    <label class="share_btn" id="share_btn" style="display:none">
      <input id="sitename" name="sitename" value="<?php echo $setting['site_name']?>" type="hidden">
      <input id="firstimg" name="firstimg" value="<?php echo $firstimg?>" type="hidden">
      
 <a href="javascript:void(0)" onclick="postToWb();return false;" style="background:url(http://v.t.qq.com/share/images/s/weiboicon16.png) no-repeat left 0px;">��Ѷ΢��</a><script type="text/javascript">
	function postToWb(){
		var _t = encodeURI("<?php echo $doc['qq_title']?>");
		var _url = encodeURIComponent(document.location);
		var _appkey = encodeURI("aa6cb794b12c41c29d6490f4624b77a9");//�����Ѷ��õ�appkey
		var _pic = encodeURI("<?php echo $doc['pic_str']?>");//�����磺var _pic='ͼƬurl1|ͼƬurl2|ͼƬurl3....��
		var _site = '';//�����վ��ַ
		var _u = 'http://v.t.qq.com/share/share.php?url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic+'&title='+_t;
		window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
	}
</script>

      
      
      
      <a href="#" class="kaixin001">����001</a> <a href="#" class="renren">������</a> <a href="#" class="sina_blog">����΢��</a> </label>
    <script language="javascript"src='js/share.js'></script>
    </span> </div>
<div class="nav_model">
  <?php if(count($nav[1] )>0) { ?>
  <?php foreach((array)$nav[1] as $key=>$val) {?>
  <?php echo $val['code']?>
  <?php }?>
  <?php } ?>
</div>
  <?php if($editlock['locked']) { ?>
  <p id="lock" class="red bor-ccc lock_word">������ʾ�����ڱ�<a href="index.php?user-space-<?php echo $editlock['user']['uid']?>"><?php echo $editlock['user']['username']?></a>�����༭��!</p>
  <?php } ?>
  <?php if($synonymdoc) { ?>
  <p class="red bor-ccc lock_word">"<?php echo $synonymdoc?>"��"<?php echo $doc['doctitle']?>"��ͬ���</p>
  <?php } ?>
  <?php if(isset($advlist[3][1]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
  <div class="ad" id="advlist_3_1"> <?php echo $advlist[3][1][code]?> </div>
  <?php } elseif(isset($advlist[3][1]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
  <div class="ad" id="advlist_3_1"> </div>
  <?php } ?>
  <div class="content_1 wordcut">
    <?php foreach((array)$doc['sectionlist'] as $key=>$section) {?>
    <?php if($section['flag'] == 1) { ?>
    <?php if(($key==1)&&!empty($sectionlist)) { ?>
    <?php if(isset($advlist[3][2]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
    <div class="ad" > <span class="r" id="advlist_3_2"> <?php echo $advlist[3][2][code]?> </span> </div>
    <?php } elseif(isset($advlist[3][2]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
    <div class="ad" > <span class="r" id="advlist_3_2"></span> </div>
    <?php } ?>
    <fieldset id="catalog">
      <legend><a name='section'>Ŀ¼</a></legend>
      <ul id="hidesection">
        <?php foreach((array)$sectionlist as $k=>$sec) {?>
        <li 
        <?php if($k>=4) { ?>
        style="display:none"
        <?php } ?>
        >&#8226; <a href="index.php?doc-view-<?php echo $doc['did']?>#<?php echo $sec['key']?>"><?php echo $sec['value']?></a>
        </li>
        <?php }?>
      </ul>
      <?php if(count($sectionlist) > 4) { ?>
      <p><a href="javascript:void(0);" onclick="partsection();"  id="partsection" style="display:none">[��ʾ����]</a><a href="javascript:void(0);" onclick="fullsection();" id="fullsection">[��ʾȫ��]</a></p>
      <?php } ?>
    </fieldset>
    <?php } ?>
    <h3><span class="texts"><?php echo $section['value']?></span><a name="<?php echo $key?>" href="index.php?doc-editsection-<?php echo $doc['did']?>-<?php echo $key?>" >�༭����</a><a href="index.php?doc-view-<?php echo $doc['did']?>#section">��Ŀ¼</a></h3>
    <?php } else { ?>
    <div class="content_topp"> <?php echo $section['value']?> </div>
    <?php } ?>
    <?php }?>
  </div>
  <div class="nav_model">
  <?php if(count($nav[2] )>0) { ?>
  <?php foreach((array)$nav[2] as $key=>$val) {?>
  <?php echo $val['code']?>
  <?php }?>
  <?php } ?>
  </div>
  <?php if(count($referencelist)>0) { ?>
  <div>
    <dl class="reference" id="reference_view">
      <dt><?php if($reference_add) { ?><a class="r h3"  href="javascript:reference_edit();">[�༭]</a><?php } ?>�ο�����</dt>
      <?php foreach((array)$referencelist as $i=>$ref) {?>
      <dd> <span>[<?php echo ($i+1)?>].</span>&nbsp;&nbsp;<?php echo $ref['name']?> &nbsp;&nbsp;<span style="color:#666666"><?php echo $ref['url']?></span> </dd>
      <?php }?>
    </dl>
    

    <div id="reference" class="hd-box editor_left" style="display:none;" >
     <dl class="reference">
     <dt><!--<a class="r h3"  href="javascript:reference_view();">[���]</a>�ο�����--></dt>
     </dl>
	<dl class="f8 reference" id="0" style="display:none;">
    <dd><span name="order">[0]</span>&nbsp;&nbsp;<span name='refrencename'></span> <span name="url" style="color:#666666"></span> <span name="edit" > <a href="javascript:;" onclick="docReference.edit(this);return false;">�༭</a> 
			| <a name="remove" href="javascript:;" onclick="docReference.remove(this);return false;">ɾ��</a></span> </dd>
	</dl>
	<?php foreach((array)$referencelist as $i=>$ref) {?>
	<dl class="f8 reference" id="<?php echo $ref['id']?>">
		<dd><span name="order">[<?php echo ($i+1)?>]</span>&nbsp;&nbsp;<span name='refrencename'><?php echo $ref['name']?></span> <span name="url" style="color:#666666"><?php echo $ref['url']?></span> <span name="edit" > <a href="javascript:;" onclick="docReference.edit(this);return false;">�༭</a>
			| <a name="remove" href="javascript:;" onclick="docReference.remove(this);return false;">ɾ��</a></span> </dd>
	</dl>
	<?php }?>
	
	<ul id="edit_reference" class="ul_l_s add_reference" style="display:none;">
		<li class="mar-bottom-10"><strong>����:</strong>
			<input id="editrefrencename" type="text" class="inp_txt" size="60"/>
			<label class="red" id="refrencenamespan"></label>
		</li>
		<li class="size black mar-bottom-10"><strong>��ַ:</strong>
			<input id="editrefrenceurl" type="text" class="inp_txt" size="60"/>
			<label class="red" id="refrenceurlspan"></label>
		</li>
		
		<li name="verifycode" class="size black mar-bottom-10" style="display:none"><strong>��֤��:</strong>
			<input name="code" id="editrefrencecode" type="text" class="inp_txt" size="10" maxlength="4"/>
			<label name="img" style="display:none"><img id="verifycode2" src="./js/hdeditor/skins/spacer.gif"/> <a href="javascript:docReference.updateVerifyCode();">������ͼƬ</a></label>
			<label name="tip"></label>
			<label class="red" id="refrencecodespan"></label>
		</li>
		<li>
			<div id="edit_reference1" class="ul_l_s" style="display:none;">
				
					<input type="button" class="btn_inp" onclick="docReference.save();return false;" value="����" id="save_1"  name="save_1"  />
				<!--<input type="button"  class="btn_inp" value="����" name="save_0" id="save_0"  style="display:none" />
					<a id="save_1" href="javascript:void(0);" onclick="docReference.save();return false;" style="border:1px red solid;">����</a>
					<span id="save_0" style="display:none">����</span>
					<a href="javascript:;" onclick="docReference.reset();return false;">����</a>-->
					<input type="button" class="btn_inp" onclick="docReference.reset();return false;" name="reset" value="����" />
			
			</div>
		</li>
	</ul>
  </div>
</div>
<?php } ?>
<div class="fj_list m-t10"> <h3 
  <?php if(count($attachment)==0) { ?>
  style="display:none"
  <?php } ?>
  >�����б�
  </h3>
  <dl style="display: none;">
    <dt><img class="fj_img"/><a></a><br/>
      <span class="l">
      <label> ���ش�����0</label>
      </span></dt>
    <dd></dd>
  </dl>
  <?php if(count($attachment)>0) { ?>
  <?php if($attach_download) { ?>
  <?php foreach((array)$attachment as $attach) {?>
  <dl id="<?php echo $attach['id']?>">
    <dt><img class="fj_img" src="style/default/attachicons/<?php echo $attach['icon']?>.gif"/><a href="index.php?attachment-download-<?php echo $attach['id']?>" coin_down="<?php echo $attach['coindown']?>" attach_id = "<?php echo $attach['uid']?>" uid = "<?php echo $userid?>"  class="file_download"><?php echo $attach['filename']?></a><br/>
      <span class="l">
      <label class="mar-r8">(<?php echo sprintf('%.2f',$attach['filesize']/1024)?>k)</label>
      <label>���ش�����<?php echo $attach['downloads']?></label>
      &nbsp;
      <label>���������ң�<?php echo $attach['coindown']?></label>
      </span>
      <?php if($attachment_remove && ($attach['uid']==$userid || $groupid==4) ) { ?>
      [<a href="javascript:;" onclick="Attachment.remove(this, <?php echo $attach['id']?>)">ɾ��</a>]
      <?php } ?>
    </dt>
    <dd><?php echo $attach['description']?></dd>
  </dl>
  <?php } ?>
  <?php } else { ?>
  <p class="m-lr8 m-t8">�����ڵ��û����޷����ػ�鿴����</p>
  <?php } ?>
  <?php } ?>
  <?php if($setting['attachment_open'] && $attachment_upload) { ?>
  <div>
    <form id="attachment_upload" action="index.php?attachment-upload" enctype="multipart/form-data" 
			method="post" target="upload" style="display:none" onsubmit="return Attachment.submit(this)">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $setting['attachment_size']*1024 ?>" />
      <div>
        <input name="attachment[]" type="file" onkeydown="return false" onpaste="return false" autocomplete="off" onchange="Attachment.add(this)">
        �������ۼ۸�
        <input name="coin_download[]" class="coin_download" type="text" value="0" size="2" onchange="check_coin($(this))" />
        (0-
        <?php echo $coin_download?>
        ���֮��)
        ����������
        <input name="attachmentdesc[]" type="text" class="attachmentdesc" size="20" maxlength="50" autocomplete="off"/>
        <a href="javascript:;" onclick="Attachment.unadd(this)" style="display:none">ɾ��</a> </div>
      <br/>
      <input type="submit" value="�ϴ�" />
      <input type="hidden" value="<?php echo $doc['did']?>" name="did"/>
      <span>[�ϴ������ļ��ߴ�:С��  <?php echo $setting['attachment_size']?> KB]</span>
    </form>
    <a href="javascript:;" onclick="Attachment.upload(this)">�ϴ�����</a> <span id="attachment_error" style="color:red"></span> </div>
  <iframe name="upload" id="upload" style="display:none;" ></iframe>
  <?php } ?>
  <input type="hidden" name="coin_hidden" id="coin_hidden" value="<?php echo $coin?>"  />
</div>
<div class="bor_b-ccc m-t10 notes">
  <p class="l">���������Ϊ���������д����ƣ��� <a class="font14" href="index.php?doc-edit-<?php echo $doc['did']?>">�༭����</a></p>
  <p class="r">
    <?php if($neighbor['predoc']) { ?>
    ��һƪ<a href="index.php?doc-view-<?php echo $neighbor['predoc']['did']?>"  class="m-lr8"><?php echo $neighbor['predoc']['title']?></a>
    <?php } ?>
    <?php if($neighbor['nextdoc']) { ?>
    ��һƪ<a href="index.php?doc-view-<?php echo $neighbor['nextdoc']['did']?>"  class="m-lr8"><?php echo $neighbor['nextdoc']['title']?></a>
    <?php } ?>
  </p>
</div>
<p class="useful_for_me"> <span>�������ݽ����ο����������Ҫ�����������<br/>�������ڷ��ɡ�ҽѧ�����򣩣���������ѯ�������רҵ��ʿ��</span> <a href="javascript:void(0)" onclick="vote(this)">
  <label id="votemsg">�����������а���</label>
  <b><?php echo $doc['votes']?></b></a> </p>
<div class="bor-ccc m-t10 bg-gray notes add">
  <p class="add_synonym">
    <label class="l w-550"><b>ͬ���</b>��
      <?php if(!empty($synonyms)) { ?>
      <span id="str">
      <?php foreach((array)$synonyms as $key=>$synonym) {?>
      <a href="index.php?doc-innerlink-<?php echo urlencode($synonym['srctitle'])?>" name='synonym'><?php echo $synonym['srctitle']?></a>
      <?php }?>
      </span>
      <?php } else { ?>
      <span name="nonesynonym">����ͬ���</span>
      <?php } ?>
    </label>
    <?php if($synonym_audit) { ?>
    <span class="r w-110 cursor" onclick="Synonym.box(<?php echo $doc['did']?>,this)"><img src="style/default/add_synonym.gif"/><a href="javascript:void(0)" >�༭/���ͬ���</a></span>
    <?php } ?>
  </p>
</div>
<div class="bor-ccc m-t10 notes bg-gray bookmark">
  <p><span class="bold">�ղص�: </span> <a title="Favorites" onclick="addfav();"><img src='style/default/bookmark/ie.gif' border='0' style="cursor:pointer;"></a> &nbsp;
    <script language="javascript"src='js/bookmark.js'></script>
    <?php if(!empty($userid)) { ?>
    <img id="doc_favorite" did="<?php echo $doc['did']?>" title="�ղص����˿ռ�" alt="�ղص����˿ռ�" src="style/default/bookmark/hudong.gif" style="cursor:pointer;">
    <?php } ?>
  </p>
  <?php if(isset($doclink)) { ?>
  <label class="m-t10 l" id="uniontitle"><?php echo $uniontitle?></label>
  <script type="text/javascript">
		$('#uniontitle').hide();
		$(document).ready(function(){
			$.get("index.php?hdapi-hduniontitle-"+<?php echo $doc['did']?>, function(data){
				if (data && data.indexOf('<html>')<0 && data.indexOf('href="null"')<0){
					$('#uniontitle').html(data).show();
					var a=$('#uniontitle').find("a[href*=innerlink]");
					if(a.size()){
						var href=a.attr("href");
						href = href.split("innerlink");
						a.attr("href", href[0]+"innerlink-"+encodeURI(a.text()));
					}
				}else{
					$('#uniontitle').hide();
				}
			});
		});
		</script>
  <?php } ?>
</div>
<?php if($comment_add) { ?>
<?php if(isset($advlist[3][3]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
<br>
<div class="ad" id="advlist_3_3"> <?php echo $advlist[3][3][code]?> </div>
<?php } elseif(isset($advlist[3][3]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
<div class="ad" id="advlist_3_3"></div>
<?php } ?>
<div class="columns comment">
  <h2 class="col-h2">�������</h2>
  <a href="index.php?comment-view-<?php echo $doc['did']?>" target="_blank" class="more">�鿴����&gt;&gt;</a>
  <form method="post" action="index.php?comment-add-<?php echo $doc['did']?>">
    <ul class="col-ul">
      <li>
        <textarea id="comment" name="comment" cols="95" rows="10" class="area"></textarea>
        <input id='anonymity' name="anonymity" type="checkbox" />
        ����</li>
      <li class="yzm">
        <?php if($setting['checkcode'] != "3") { ?>
        <span>��֤��: </span>
        <input name="code2" type="text" />
        <label class="m-lr8"><img id="verifycode" src="index.php?user-code" onclick="updateverifycode();" /></label>
        <a href="javascript:updateverifycode();">��һ��</a>
        <?php } ?>
        &nbsp;&nbsp;&nbsp;&nbsp;ע:���۳������Ϊ200���ַ���</li>
      <li>
        <input name="submit" type="submit" value="��������" class="btn_inp"/>
      </li>
    </ul>
  </form>
</div>
<?php } ?>
</div>
<div class="r w-230">
<div class="nav_model">
  <?php if(count($nav[3] )>0) { ?>
  <?php foreach((array)$nav[3] as $key=>$val) {?>
  <?php echo $val['code']?>
  <?php }?>
  <?php } ?>
</div>
  <?php if(isset($advlist[4][1]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
  <div class="ad" id="advlist_4_1"> <?php echo $advlist[4][1][code]?> </div>
  <?php } elseif(isset($advlist[4][1]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
  <div class="ad" id="advlist_4_1"> </div>
  <?php } ?>
  <?php if($audit) { ?>
  <div class="columns ctgl">
    <h2 class="col-h2">��������</h2>
    <form method="post">
      <dl>
        <dt>����</dt>
        <dd class="a-c">
          <input name="Button2" type="button" value="������" class="m-lr8 btn_inp" onclick="doc_rename();" />
          <input id="editcategory" name="Button3" type="button" value="�༭����" class="m-lr8 btn_inp" onClick="javascript:catevalue.ajax(0,this);"/>
        </dd>
        <dt>״̬</dt>
        <dd>
          <label class="l" ><a href='javascript:void(0);' onclick="lock('lock');">����</a></label>
          <label class="r" ><a href='javascript:void(0);' onclick="lock('unlock');">����</a></label>
        </dd>
        <dd>
          <label class="l" ><a href='javascript:void(0);' onclick="recommend();">�Ƽ�</a></label>
          <label class="r" ><a href='javascript:void(0);' onclick="updatastatus(0);"> ȡ���Ƽ�</a></label>
        </dd>
        <dd>
          <label class="l" ><a href='javascript:void(0);' onclick="audit();">���</a></label>
          <label class="r" ><a href='index.php?doc-remove-<?php echo $doc['did']?>' onclick="return remove()">ɾ��</a></label>
        </dd>
      </dl>
    </form>
  </div>
  <?php } ?>
  <div class="columns ctxx">
    <h2 class="col-h2">������Ϣ</h2>
    <?php if($author) { ?>
    <?php if(!isset($lasteditor) || (isset($lasteditor) && $lasteditor['uid']!=$author['uid'])) { ?>
    <dl class="col-dl twhp2">
      <dd><a href="index.php?user-space-<?php echo $author['uid']?>" target="_blank"  class="a-img1"> <img alt="<?php echo $author['username']?>" title="<?php echo $author['username']?>" src="<?php if($author['image']) { ?><?php echo $author['image']?><?php } else { ?>style/default/user_l.jpg<?php } ?>" width="38px" height="38px" /> </a></dd>
      <dt><a href="index.php?user-space-<?php echo $author['uid']?>" target="_blank"><?php echo $author['username']?></a></dt>
      <dd><span style="color:<?php echo $author['color']?>" class="l m-r8"><?php echo $author['grouptitle']?></span> <span title="������ <?php echo $author['stars']?>" class="l">
        <?php for($i=0; $i<$author['userstars'][3]; $i++) {?>
        <img src="style/default/star_level3.gif"/>
        <?php } ?>
        <?php for($i=0; $i<$author['userstars'][2]; $i++) {?>
        <img src="style/default/star_level2.gif"/>
        <?php } ?>
        <?php for($i=0; $i<$author['userstars'][1]; $i++) {?>
        <img src="style/default/star_level1.gif"/>
        <?php } ?>
        </span> </dd>
      <dd>���������� <a onclick="javascript:Message.box('<?php echo $author['username']?>')"   href="javascript:void(0)">������Ϣ</a> &nbsp;&nbsp;<img src="style/default/jb.gif" title="<?php echo $author['credit1']?>���"></dd>
    </dl>
    <?php } ?>
    <?php } ?>
    <?php if($author_removed) { ?>
    <dl class="col-dl twhp2">
      <dd><a class="a-img1"> <img alt="��ɾ��" src="style/default/user_l.jpg" width="38px" height="38px" /></a></dd>
      <dt>���û���ɾ��</dt>
      <dd>����������</dd>
    </dl>
    <?php } ?>
    <?php if(isset($lasteditor) ) { ?>
    <dl class="col-dl twhp2">
      <dd><a href="index.php?user-space-<?php echo $lasteditor['uid']?>" target="_blank"  class="a-img1"> <img alt="<?php echo $lasteditor['username']?>" title="<?php echo $lasteditor['username']?>" src="<?php if($lasteditor['image']) { ?><?php echo $lasteditor['image']?><?php } else { ?>style/default/user_l.jpg<?php } ?>" width="38px" height="38px" /> </a></dd>
      <dt><a href="index.php?user-space-<?php echo $lasteditor['uid']?>" target="_blank"><?php echo $lasteditor['username']?></a></dt>
      <dd><span class="l m-r8" style="color:<?php echo $lasteditor['color']?>" ><?php echo $lasteditor['grouptitle']?></span> <span title="������ <?php echo $lasteditor['stars']?>" class="l">
        <?php for($i=0; $i<$lasteditor['userstars'][3]; $i++) {?>
        <img src="style/default/star_level3.gif"/>
        <?php } ?>
        <?php for($i=0; $i<$lasteditor['userstars'][2]; $i++) {?>
        <img src="style/default/star_level2.gif"/>
        <?php } ?>
        <?php for($i=0; $i<$lasteditor['userstars'][1]; $i++) {?>
        <img src="style/default/star_level1.gif"/>
        <?php } ?>
        </span> </dd>
      <dd>����༭�� <a onclick="javascript:Message.box('<?php echo $lasteditor['username']?>')"   href="javascript:void(0)">������Ϣ</a> &nbsp;&nbsp;<img src="style/default/jb.gif" title="<?php echo $lasteditor['credit1']?>���"></dd>
    </dl>
    <?php } ?>
    <?php if($lasteditor_removed) { ?>
    <dl class="col-dl twhp2">
      <dd><a class="a-img1"> <img alt="��ɾ��" src="style/default/user_l.jpg" width="38px" height="38px" /></a></dd>
      <dt>���û���ɾ��</dt>
      <dd>����༭��</dd>
    </dl>
    <?php } ?>
    <ul class="col-ul bor-ccc">
      <li>�������: <?php echo $doc['views']?> ��</li>
      <?php if($doc['editions'] ) { ?>
      <li>�༭����: <?php echo $doc['editions']?>�� <a href="index.php?edition-list-<?php echo $doc['did']?>" target="_blank" class="m-l8">��ʷ�汾</a></li>
      <?php } ?>
      <li>����ʱ��: <?php echo $doc['lastedit']?></li>
    </ul>
  </div>
  <div class="columns">
    <h2 class="col-h2">��ش���</h2>
    <?php if($relate) { ?>
    <a href="javascript:void(0)" onclick="relateddoc('block')" class="more">���</a>
    <?php } ?>
    <ul class="col-ul" id='related_doc' 
    <?php if(empty($relatelist)) { ?>
    style="display:none"
    <?php } ?>
    >
    <?php foreach((array)$relatelist as $key=>$relate) {?>
    <li><a href="index.php?doc-innerlink-<?php echo urlencode($relate)?>" target="_blank" title="<?php echo $relate?>"><?php echo $relate?></a></li>
    <?php }?>
    </ul>
  </div>
  <div id="block_right"></div>
  <?php if(isset($advlist[4][2]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
  <div class="ad" id="advlist_4_2"> <?php echo $advlist[4][2][code]?> </div>
  <?php } elseif(isset($advlist[4][2]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
  <div class="ad" id="advlist_4_2"> </div>
  <?php } ?>
</div>
<div class="nav_model">
<?php if(count($nav[4] )>0) { ?>
<?php foreach((array)$nav[4] as $key=>$val) {?>
<?php echo $val['code']?>
<?php }?>
<?php } ?>
</div>
<?php if($setting['checkcode'] != "3") { ?>
<script type="text/javascript">
function updateverifycode() {
	var img = "index.php?user-code-"+Math.random();
	$('#verifycode').attr("src",img);
}
</script>
<?php } ?>
<?php if($audit) { ?>

<!--�ο�������֤��-->
<script type="text/javascript">
function updatereferenceverifycode() {
	var img = "index.php?user-code-"+Math.random();
	$('#verifycode2').attr("src",img);
}
</script>


<script type="text/javascript">
var timeout_pop=0;
function doc_rename(){
	var title=$.trim($('#doctitle').html()).replace(/\s/g,'&nbsp;');
	var html="�������� :  <form action='' onsubmit='update_docname();return false;'><input id='newname' type='text' value='"+title+"' maxlength='80' height='40'><br><br>"+
	"<input name='renamesbumit' type='button' onclick='update_docname()' value='�ύ'>"+
	"<input name='cancel' type='button' onclick='closepop(\"rename\")' value='ȡ��'><br><br><label id='updatetitlenotice' style='height:20px;color:red'>&nbsp;</label></form>";
	$.dialog.box('rename', '������', html);
}
function update_docname(){
	clearTimeout(timeout_pop);
	if($.trim($('#newname').val())==''){
		$("#updatetitlenotice").html('���Ʋ���Ϊ��');
		return;
	}
	$.post(
		"index.php?doc-changename",{did:<?php echo $doc['did']?>,newname:$('#newname').val()},
		function(xml){
			var message=xml.lastChild.firstChild.nodeValue;
			if(message=='1'){
				$('#doctitle').html($('#newname').val());
				$.dialog.close('rename');
				return;
			}else if(message=='-2'){
				$("#updatetitlenotice").html('�������Ѵ���!����������');
			}else if(message=='-3'){
				$("#updatetitlenotice").html('����Σ�մ���');
			}else if(message=='-4'){
				$("#updatetitlenotice").html('���Ʋ���Ϊ��');
			}else{
				$("#updatetitlenotice").html('����ʧ��');
			}
		}
	);
}

function lock(type){
	clearTimeout(timeout_pop);
	$.post(
		"index.php?doc-"+type,{did:<?php echo $doc['did']?>},
		function(xml){
			var	message=xml.lastChild.firstChild.nodeValue;
			if(message=='1'){
				if(type=='lock'){
					$('#lockimage').html(" &nbsp;<image src='style/default/lock.gif'>");
				}else{
					$('#lockimage').html("");
				}
				$.dialog.box('lock', '��������', '�Ѿ��ɹ�����/�����ô���');
			}else{
				$.dialog.box('lock', '��������', '����ʧ��');
			}
			timeout_pop=setTimeout("closepop('lock')",3000);
		}
	);
}

function recommend(){
	var html="����״̬ :  <select id='recommend_type' ><option value='1'>�Ƽ�����</option><option value='2'>���Ŵ���</option><option value='3'>���ʴ���</option></select><br><br>"+
	"<input name='renamesbumit' type='button' onclick='updatastatus($(\"#recommend_type\").val())' value='�ύ'>"+
	"<input name='cancel' type='button' onclick='closepop(\"recommend\")' value='ȡ��'>";
	$.dialog.box('recommend', '�Ƽ�����', html);
}

function updatastatus(type){
	clearTimeout(timeout_pop);
	$.post(
		"index.php?doc-setfocus",{did:<?php echo $doc['did']?>,visible:<?php echo $doc['visible']?>,doctype:type},
		function(xml){
			var	message=xml.lastChild.firstChild.nodeValue;
			if(message=='1'){
				$.dialog.box('recommend', '�Ƽ�����', '�Ѿ��ɹ����ô���״̬');
			}else{
				$.dialog.box('recommend', '�Ƽ�����', '����ʧ��');
			}
			timeout_pop=setTimeout("closepop('recommend')",3000);
		}
	);
}

function audit(){
	clearTimeout(timeout_pop);
	$.post(
		"index.php?doc-audit",{did:<?php echo $doc['did']?>},
		function(xml){
			var	message=xml.lastChild.firstChild.nodeValue;
			if(message=='1'){
				$('#auditmsg').html("");
				$.dialog.box('audit', '��˴���', '�Ѿ��ɹ�����˴���');
			}else{
				$.dialog.box('audit', '��˴���', '����ʧ��');
			}
			timeout_pop=setTimeout("closepop('audit')",3000);
		}
	);
}

function remove(){
	var url="index.php?doc-remove-<?php echo $doc['did']?>";
	return confirm("ɾ���汾�󲻿ɻָ�,ȷ��ɾ����")
}

function closepop(name){
	$.dialog.close(name);
}

	var catevalue = {
		input:null,
		scids:new Array(),
		scnames:new Array(),
		ajax:function(cateid, E){
			if(arguments.length==2){
				this.clear();
				$.ajax({
					url: 'index.php?doc-hdgetcat',data: {did:<?php echo $doc['did']?>},cache: false,dataType: "xml",type:"post",async:false, 
					success: function(xml){
						var message=xml.lastChild.firstChild.nodeValue;
						if(message!=''){
							eval(message);
						}
					}
				});
			}
			if(!cateid)cateid=0;
			$.ajax({
				url: 'index.php?category-ajax-'+cateid,cache: false,dataType: "xml",type:"get",
				success: function(xml){
					var message=xml.lastChild.firstChild.nodeValue;
					
					if($('#dialog_category:visible').size()){
						$.dialog.content('flsx', '<div id="flsx" class="chose_cate">'+message+'</div>');
						catevalue.selectCategory();
					}else{
						$.dialog({
							id:'flsx',
							title:'�༭����',
							content: '<div id="flsx" class="chose_cate">'+message+'</div>',
							height:450,
							width:680,
							position:'c',
							resizable:0,
							resetTime:0,
							onOk:function(){
								catevalue.ok();
							},
							callback:function(){
								catevalue.selectCategory();
							},
							styleContent:{'text-align':'left', 'overflow-y':'scroll', 'padding-right':'0', height:'380px'},
							styleOk:{'font-size':'14px','line-height':'20px', 'padding':'2px 6px 1px','margin-right':'3px'}
						});
					}
				}
			});
		},
		
		cateOk:function(id,title,handle){
			var point;
			if(handle){
				this.scids.push(id);
				this.scnames.push(title);				
			}else{
				for(i=0;i<this.scids.length;i++){
					if(this.scids[i]==id){
						point=i;
					}
				}
				this.scids.splice(point,1);
				this.scnames.splice(point,1);
			}
			catevalue.pushCategory()
		},
		
		pushCategory:function(){
			$('#category').val(this.scids.toString());
			$('#scnames').text(this.scnames.toString());
		},
		
		getCatUrl:function(){
			var catstring='';
			for(i=0;i<this.scids.length;i++){
				catstring=catstring+'<a target="_blank" href="<?php echo $setting['seo_prefix']?>category-view-'+this.scids[i]+'">'+this.scnames[i]+'</a>,';
			}
			catstring=catstring.substring(0, catstring.length-1);   
			return catstring;
		},
		
		selectCategory:function(){
			var cb=$(":checkbox");
			catevalue.pushCategory();
			for(i=0;i<cb.length;i++){
				if(catevalue.inArray(cb[i].id, this.scids)){
					cb[i].checked = true; 
				}
			}		
		},
		
		inArray:function(stringToSearch, arrayToSearch) {
			for (s = 0; s <arrayToSearch.length; s++) {
				if (stringToSearch == arrayToSearch[s]) {			 
					 return true;
				}
			}
			return false;
		},
		
		removeCateTree:function(){
			closepop('flsx');
			this.clear();
		},
		
		ok:function(){
			if(changecategory(this.scids.toString())){
				closepop('flsx')
			}
		},
		
		init:function(){
			if('<?php echo $category['cid']?>'!=''){
				this.scids.push(<?php echo $category['cid']?>);
				this.scnames.push('<?php htmlspecialchars(string::haddslashes($category['name']),1)?>');
			}
		},
		
		clear:function(){
			this.scids.length=0;
			this.scnames.length=0;	
		}
		
	}
	
	function changecategory(cats){
		if(!cats){
			$('#scnames').fadeOut();
			$('#scnames').html('&nbsp;&nbsp;���಻����Ϊ��').fadeIn();
			return false;
		}
		$.ajax({
			url: "index.php?doc-changecategory",data: {did:<?php echo $doc['did']?>,newcategory:cats},cache: false,dataType: "xml",type:"post",async:false,
			success: function(xml){
				var	message=xml.lastChild.firstChild.nodeValue;
				if(message!='0'){
					$('#catenavi').html(message);
				}
			}
		});
		return true;
	}
	function openclose(obj){
		var patrn=/close.gif$/;
		var s=obj.src;
		var id=obj.id;
		if(patrn.test(s)){
			obj.src='style/default/open.gif';
			var t=$('#'+id).find("dd");
			t.show();
		}else{
			obj.src='style/default/close.gif';
			var t=$('#'+id).find("dd");
			t.hide();
		}
	}
</script>
<?php } ?>
<?php if($synonym_audit) { ?>
<script type="text/javascript">
	
	var Synonym = {
		E:null,
		did: 0,
		srctitles: '',
		tags:null,
		box : function(did, E){
			this.E = $(E).parent();
			this.did = did;
			var html = '<form onsubmit="Synonym.send(this);return false;"><table border="0" width="300" class="send_massage" style="margin-left:18px">'
			+'<tr><td height="25"><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td><td align=left><input  name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td></tr>'
			+'<tr><td height="25"><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td><td align=left><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td></tr>'
			+'<tr><td height="25"><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td><td align=left><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td></tr>'
			+'<tr><td height="25"><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td><td align=left><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td></tr>'
			+'<tr><td height="25"><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td><td align=left><input name="srctitles[]" type="text" class="inp_txt" maxlength="100" size="20"/></td></tr>'
			+'<tr><td height="25" colspan=2><span id="synonymTip"></span><br /><input type="hidden" name="desttitle" value="<?php echo $doc['title']?>" /><input type="hidden" name="destdid" value="<?php echo $doc['did']?>" />'
			+'<input id="synonymSubmit" type="submit" value="'+Lang.Submit+'" /></td></tr></table></form>';
			$.dialog.box('synonym','�༭ͬ���', html);
			var srctitles='',srctitless='',a = $(E).parent().find("a[name=synonym]");
			this.tags = a;
			a.each(function(i){
				srctitles += $(this).text();
			});
			this.srctitles = $.trim(srctitles);
			var synonymInputs = $("input[name='srctitles[]']");
			a.each(function(i){
				synonymInputs[i].value=$(this).text();
			});
			$("#synonymSubmit").attr('disabled', false).val(Lang.Submit);
			return false;
		},
		
		send: function(form){
			var title=$("#doctitle").text();
			var formData = $(form).serialize();
			formData += "&submit=ajax";
			var synonymInputs = $("input[name='srctitles[]']");
			var inputsrc='';
			synonymInputs.each(function(i){
				if($.trim($(this).val())!='')
					inputsrc += $(this).val();
			});
			this.inputsrc = $.trim(inputsrc);
			//����Ƿ����޸�
			if (this.srctitles == this.inputsrc){
				$.dialog.close('synonym');
				return;
			}

			$("#synonymSubmit").attr('disabled', true).val(Lang.Submiting);
			$.post("index.php?synonym-savesynonym", formData, function(data, status){

				$("#synonymSubmit").attr('disabled', false).val(Lang.Submit);
				if (status == 'success'){
					if(data==0){
						$("#synonymTip").html('û������κ�ͬ��ʣ�');
					}else if(data==-1){
						$("#synonymTip").html("��������");
					}else if(data==-2){
						$("#synonymTip").html('ͬ��ʱ�����ָ���Լ�');
					}else if(data==-3){
						$("#synonymTip").html('�в����������ַ�');
					}else if(data==-4){
						$("#synonymTip").html('�Ѿ������ͬ���ָ��');
					}else if(data==-5){
						$("#synonymTip").html('�Ѿ�ָ������ͬ���,�����ظ�ָ��');
					}else if(data==-6){
						$("#synonymTip").html('�Ѿ������ͬ���ָ��');
					}else if(data=='empty'){ //������� empty,��ʾ���������ͬ���
						Synonym.change('');
						$.dialog.close('synonym');
					} else { //���򣬰����ص����ݸ���ҳ������ʾ��ͬ���
						Synonym.change(data);
						$("#synonymTip").html('');
						$.dialog.close('synonym');
						return;
					}
				}
			});
		},
		
		change: function(newData){
			var html='';
			html = newData+html;
			if(html){
				if (this.tags.size() > 0){
					$(this.tags[0]).before(html);
					this.tags.remove();
				}else{
					this.E.find("span[name=nonesynonym]").before(html);
					this.E.find("span[name=nonesynonym]").remove();
				}
			}else{
				html='<span name="nonesynonym">����ͬ���</span>';
				this.E.find("a[name=synonym]:first").before(html);
				this.E.find("a[name=synonym]").remove();
			}
			$.dialog.close('synonym');
		}
	}
</script>
<?php } ?>
<?php if($doc_edit) { ?>
<script type="text/javascript">
var Tag = {
	E:null,
	did: 0,
	tagtext: '',
	tags:null,
	box : function(did, E){
		this.E = $(E).parent();
		this.did = did;
		var html = '<form onsubmit="Tag.send();return false;"><table border="0" width="400" class="send_massage">'
		+'<tr><td height="40"><input id="tagSubject" type="text" class="inp_txt" maxlength="200" size="50"/></td></tr>'
		+'<tr><td height="40">'+Lang.EditTagTip+'</td></tr>'
		+'<tr><td height="40"><input id="tagSubmit" type="submit" value="'+Lang.Submit+'" />'
		+'<span id="tagTip"></span></td></tr></table></form>';
		
		$.dialog.box('tag', Lang.EditTag, html);
		
		var tagtext='',a = $(E).parent().find("a[name=tag]");
		this.tags = a;
		a.each(function(i){
			tagtext += $(this).text() + '; ';
		});
		this.tagtext = $.trim(tagtext);
		$("#tagSubject").val(tagtext);
		$("#tagSubject").focus();
		$("#tagSubmit").attr('disabled', false).val(Lang.Submit);

		return false;
	},
	
	send: function(){
		var params = {'submit':'ajax', 'tagtext':'', 'did':this.did};
		params.tagtext = $.trim($("#tagSubject").val());
		if (this.tagtext == params.tagtext){
			$.dialog.close('tag');
			return;
		}
		params.tagtext = params.tagtext.replace(new RegExp(Lang.Fenhao+'|,|'+Lang.Douhao, "g"), ";").replace(/ /g,';').replace(/;;+/g,';');
		var taglist=hdunique(params.tagtext.split(';'));
		var tags='';
		for(var i=0;i<taglist.length;i++){
			if($.trim(taglist[i])){
				tags +=taglist[i]+';'
			}
		}
		params.tagtext=tags;
		this.tagtext = tags;
		$("#tagSubmit").attr('disabled', true).val(Lang.Submiting);
		$.post("index.php?doc-edit-"+Math.random(), params, function(data, status){
			$("#tagSubmit").attr('disabled', false).val(Lang.Submit);
			if (status == 'success'){
				if (data == 'OK'){
					Tag.change();
					$("#tagTip").html('');
					$.dialog.close('tag');
				} else {
					alert(Lang.EditTagError);
				}
			} else {
				alert(Lang.EditTagError);
			}
		});
	},
	
	change: function(){
		var html='', taglist = this.tagtext.split(';');
		taglist=hdunique(taglist);
		for (var i=taglist.length-1; i>=0; i--){
			if ('' === taglist[i]) continue;
			html = '<a href="index.php?search-tag-'+encodeURI(taglist[i])+'" name="tag">'+taglist[i]+'</a> '+html;
		}
		if(html){
			if (this.tags.size() > 0){
				$(this.tags[0]).before(html);
				this.tags.remove();
			}else{
				this.E.find("span[name=nonetag]").before(html);
				this.E.find("span[name=nonetag]").remove();
			}
		}else{
				html='<span name="nonetag">���ޱ�ǩ</span>';
				this.E.find("a[name=tag]:first").before(html);
				this.E.find("a[name=tag]").remove();
		}
	}
}
</script>
<?php } ?>
<?php if($relate) { ?>
<script type="text/javascript">
	function relateddoc(display){
		for(i=0;i<10;i++){
			$("#related_"+(i+1)).val('');
		}
		var html ='<form onsubmit="addrelatedoc();return false;"><ul class="p-ul" style="line-height:25px">'
		+'<li><input name="Text2" type="text" class="inp_txt" id="related_1"/>&nbsp;&nbsp;<input name="Text2" type="text"  class="inp_txt" id="related_2"/></li>'
		+'<li><input name="Text2" type="text" class="inp_txt" id="related_3"/>&nbsp;&nbsp;<input name="Text2" type="text"  class="inp_txt" id="related_4"/></li>'
		+'<li><input name="Text2" type="text" class="inp_txt" id="related_5"/>&nbsp;&nbsp;<input name="Text2" type="text"  class="inp_txt" id="related_6"/></li>'
		+'<li><input name="Text2" type="text" class="inp_txt" id="related_7"/>&nbsp;&nbsp;<input name="Text2" type="text"  class="inp_txt" id="related_8"/></li>'
		+'<li><input name="Text2" type="text" class="inp_txt" id="related_9"/>&nbsp;&nbsp;<input name="Text2" type="text"  class="inp_txt" id="related_10"/></li>'
		+'<li onclick="addrelatedoc();"><input name="Button1" type="submit" value="����"  class="btn_inp" /></li></ul></form>';
		$.dialog.box('relatedoc','�༭��ش���', html);
		var dialog=$.dialog.get('relatedoc');
		dialog.find(':text').attr('maxlength', 80);
		$("#related_doc a").each(function(i){
			$("#related_"+(i+1)).val($(this).text());
		});
	}
	
	function addrelatedoc(){
		var relatedata = '';
		var relatedhtml = '';
		var arraydoc=[];
		for(i=0;i<10;i++){
			if($.trim($("#related_"+(i+1)).val())){
				relatedata+=$.trim($("#related_"+(i+1)).val())+';';
			}
		}
		arraydoc=relatedata.split(";");
		var unique_doc=hdunique(arraydoc);
		for(i=0;i<unique_doc.length;i++){
			if($.trim(unique_doc[i])){
				relatedhtml+='<li><a href="index.php?doc-innerlink-'+encodeURI(unique_doc[i])+'" target="_blank" title="<?php echo $relate?>">'+(unique_doc[i])+'</a></li>';
			}
		}
		var title=$("#doctitle").text();
		title=$.trim(title).replace(/\s/g,'&nbsp;');
		$.ajax({
			url: "index.php?doc-addrelatedoc",data: {did:<?php echo $doc['did']?>,title:title,relatename:relatedata},cache: false,dataType: "xml",type:"post",async:false, 
			success: function(xml){
				var	message=xml.lastChild.firstChild.nodeValue;
				if(message=='1'){
					$('#related_doc').html(relatedhtml);
					$('#related_doc').css("display",'block');
					$.dialog.close('relatedoc');return;
				}else if(message=='2'){
					alert('����Υ����,��������ӣ�');
				}else{
					alert('����ʧ��');
				}
			}
		});
	}
</script>
<?php } ?>
<?php if($setting['attachment_open'] && $attachment_upload) { ?>
<script type="text/javascript">
String.prototype.Len=function(){
	var j = 0;
	var charset = 'UTF-8'=='<?php echo WIKI_CHARSET?>' ? 3 :2;
	for(var i=0;i<this.length;i++){
		if(this.charCodeAt(i) > 255) {
			j = j + charset;
		}else{
			j++
		}
	}
	return j;
}
<?php if($attachment_type == '*') { ?>
var attachment_type = '';
<?php } else { ?>
var attachment_type = /\.(<?php echo $attachment_type?>)$/i;
<?php } ?>
var Attachment = {
	upload: function(el){
		$(el).hide();
		$(el).parent().find('form').show();
		$("div.fj_list").find("h3").show();
	},
	
	add: function(el){
		var value = $(el).val();
		if (!value) return false;
		if(value.Len() > 100){
			alert('��������̫��!�������������ϴ�!');
		    $(el).before('<input name="attachment[]" type="file" onkeydown="return false" onpaste="return false" autocomplete="off" onchange="Attachment.add(this)">').remove();
			return false;
		}
		if (attachment_type && !attachment_type.test(value)){
			alert("�����͸������������ϴ�");
		    $(el).before('<input name="attachment[]" type="file" onkeydown="return false" onpaste="return false" autocomplete="off" onchange="Attachment.add(this)">').remove();
			return false;
		}
		var div = $(el).parent('div');
		/*var isSelect = div.parent('form').find("input[type=file][value='"+value+"']").size();
		var isUpload = div.parents("div.fj_list").find("a:contains('"+this.getname(value)+"')").size();
		if (isSelect > 1 || isUpload>=1) {
			alert("����ѡ���ظ���");
			$(el).before('<input name="attachment[]" type="file" onkeydown="return false" onpaste="return false" autocomplete="off" onchange="Attachment.add(this)">').remove();
			return false;
		}*/
		
		if (div.parent('form').find("input[type=file]:last").val() == ''){
			return false;
		}
		
		var ndiv = div.clone();
		ndiv.find("input").val('');
		ndiv.find("input.coin_download").val(0);
		div.after(ndiv).find('a').show();
		$("#attachment_error").hide();
	},
	
	getname: function(filename){
		var re = /[^\/\\]+$/i;
		var pos = filename.search(re);
		return (pos > -1) ? filename.substr(pos) : false;
	},
	
	addok: function(upload_success_files){
		var form = $("#attachment_upload");
		var files = form.find("input[type=file]");
		var descs = form.find(".attachmentdesc");
		var len= files.size();
		var dl, name, desc, icon;
		var div = form.parent("div");
		for(i=0; i<len; i++){
			name = this.getname(files.get(i).value);
			
			if (/\.(doc|docx|xls|xlsx|ppt|pptx|mdb|accdb)$/i.test(name)){
				icon = 'msoffice';
			}else if (/\.(jpg|jpeg|bmp|gif|ico|png)$/i.test(name)){
				icon = 'image';
			}else if (/\.(pdf|rar|zip|swf|txt|wav)$/i.test(name)){
				icon = name.substr(name.length-3);
			}else {
				icon = 'common';
			}
			desc = descs.get(i).value;
			if (!name) continue;
			dl = form.parents("div.fj_list").find("dl:first").clone();
			dl.find("a").text(name);
			dl.find("img").attr("src","style/default/attachicons/"+icon+".gif");
			dl.find("dd").text(desc);
			if (upload_success_files.indexOf(name)==-1){
				$(files.get(i)).parent("div").remove();
				continue;
			}
			dl.show();
			div.before(dl);
		}
		//$("#attachment_error").hide();
	},
	
	error: function(err){
		$("#attachment_error").show().append('<br>'+err);
	},
	
	unadd: function(el){
		$(el).parent('div').remove();
	},
	
	remove: function(el, id){
		$.get("index.php?attachment-remove-"+id, function(data, state){
			if (state == 'success'){
				data = $.trim(data);
				if (data == 'OK'){
					var dl = $(el).parents("dl[id='"+id+"']");
					dl.remove();
				}else{
					alert("ɾ��ʧ��");
				}
			}else{
				alert("ɾ��ʧ��");
			}
		})
	},
	
	submit: function(form){
		var file = $(form).find("input[type=file]:first");
		if (file.val() == ''){
			alert("��ѡ����Ҫ�ϴ����ļ���");
			return false;
		}
		$("#attachment_error").hide().html('');
	}
}
</script>
<?php } ?>
<script type="text/javascript">
	function partsection(){
		$('#fullsection').css('display','block');
		$('#partsection').css('display','none');
		$("#hidesection > li:gt(3)").css('display','none');
	}
	function fullsection(){
		$('#fullsection').css('display','none');
		$('#partsection').css('display','block');
		$("#hidesection > li:gt(3)").css('display','block');
	}
	function addfav(){
		var title=$("#doctitle").text();
		if (window.ActiveXObject){
			 window.external.AddFavorite('<?php echo WIKI_URL?>/index.php?doc-view-<?php echo $doc['did']?>', title+'-<?php echo $setting['site_name']?>')
		} else {
			window.sidebar.addPanel(title+'-<?php echo $setting['site_name']?>', '<?php echo WIKI_URL?>/index.php?doc-view-<?php echo $doc['did']?>' , "");
		}
	}
	function vote(el){
		$.ajax({
			url: "index.php?doc-vote",
			data: {did:"<?php echo $doc['did']?>"},
			cache: false,
			dataType: "xml",
			type:"post",
			success: function(xml){
				var	message=xml.lastChild.firstChild.nodeValue;
				if(message=='1'){
					var votes=parseInt($("#votemsg + b").html())+1;
					$("#votemsg + b").html(votes);
					$('#votemsg').html('�����������а���');
					$("#ding span").html(votes);
					
					$.get("index.php?hdapi-hdautosns-ding-<?php echo $doc['did']?>");
				}else{
					$('#votemsg').html('�������ۣ�лл��');
					if($(el).attr('id') == 'ding'){
						$.dialog.box('jqdialogtip', '��ʾ', '�������ۣ�лл��');
					}
				}
			}
		});
	}
	
	function hdunique(arrayName){
		var newArray=new Array();
		label:for(var i=0; i<arrayName.length;i++ ){  
			for(var j=0; j<newArray.length;j++ ){
				if(newArray[j]==arrayName[i]) 
					continue label;
				}
				newArray[newArray.length] = arrayName[i];
			}
		return newArray;
	}
	
	function scrollToTop(){
		var body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body');
		body.animate({scrollTop:0},500);
	}
	
	$(window).ready(function(){
		$.dialog({
			id:'scrolltotop',
			skin:"noborder",
			position:'rb',
			move:false,
			effects:'',
			fixed:1,
			height:100,
			width:50,
			closeImg:0,
			minScrollTop:100,
			overlay:0,
			content:'<img title="�ض���" style="cursor:pointer" src="<?php echo WIKI_URL?>/style/default/up.png" style="width:23px; height:66px" onclick="scrollToTop()"/>'
		});
	});

	var clock_doc_locked=0;
	function doc_is_locked(){
		if($.trim($('#lockimage').html())!=""){
			$.dialog.box('fobbiden', '��ֹ�༭', '<b>�˴�������ֹ�༭!</b>');
			clearTimeout(clock_doc_locked);
			clock_doc_locked=setTimeout(function(){
				$.dialog.close('fobbiden');
			},1500);
			return false;
		}
	}
	
	function innerlink(title){
		location.href='<?php echo WIKI_URL?>/index.php?doc-innerlink-'+encodeURI(title);
	}
	
	//����������ʱ����ɫ������ʹ��ͻ���ĺ�ɫ red �����ͨ�ı�һ���ĺ�ɫ #000000������������ϲ������ɫ�ȵȡ�Ĭ��Ϊ��ɫ��
	var innerlink_no_exist_color='red';
	$("a[href^='javascript:innerlink']").each(function(){
		var a=$(this), title=a.text();
		if(title.indexOf('"') > -1){
			title = title.replace('"', '\\"');
		}
		a.attr('title', '������'+title+'�������ڣ�����ɴ���')
			.addClass('link_doc_no').attr('href', 'javascript:innerlink("'+title+'")').removeAttr("target");
	});
	
	$(document).ready(function(){
		$("#doc_favorite").click(function(){
			var did = $(this).attr('did');
			var result = '';
			$.post("index.php?user-addfavorite",  {did:did},function(data){
				switch (data) {
					case '1' :
						result = '�����ɹ����ղ����������ģ�';
						break;
					case '2' :
						result = '�˴����Ѿ����ղأ�';
						break;
					case '3' :
						result = 'ָ�����������ڻ����Ѿ���ɾ����';
						break;
					default :
						result = '��������!';
						break;
				}
				$.dialog.box('user_addfavorite', '�ղش���', result);
			});
		})
		
		$(".file_download").click(function(){
			var coin_down = $(this).attr("coin_down");
			var attach_id = $(this).attr("attach_id");
			var uid = $(this).attr("uid");
			var coin_hidden = $("#coin_hidden").val();
			coin = coin_hidden - coin_down;
			if(attach_id != uid && coin < 0) {
				$.dialog.box('coin_down', '��������', '��Ҳ��㣡');
				return false;
			} else {
				$("#coin_hidden").val(coin);
			}
		})
	})
	
	function check_coin(coinObj){
		var coin = coinObj.val();
		var preg =/^[0-9_]*$/;
		var coin_down = <?php echo $coin_download?>;
		coin = $.trim(coin);
		if(preg.test(coin)){
			if(coin < 0) {
				coin = 0;
			}
			if(coin > coin_down ) {
				coin = coin_down;
			}
		} else {
			coin = 0;
		}
		
		coinObj.val(coin);
	}
</script>
<script type="text/javascript">
	var Letter = {
		E:null,
		did: 0,
		letters:'',
		box : function(did, E){
			this.E = $(E).parent();
			this.did = did;
			var html = '<form onsubmit="Letter.send();return false;"><table border="0" width="400" class="send_massage">'
			+'<tr><td height="40">��������ĸ��<input id="first_letter" type="text" class="inp_txt" maxlength="1" size="10"/></td></tr>'
			+'<tr><td height="40"><input id="letterSubmit" type="submit" value="'+Lang.Submit+'" />'
			+'<span id="tagTip"></span></td></tr></table></form>';
			$.dialog.box('firstletter', '���ô�������ĸ', html);
			$("#letterSubmit").attr('disabled', false).val(Lang.Submit);
			letters=$("#fletter").val();
			document.getElementById("first_letter").value=letters;//document.getElementById("fletter").value;
			return false;
		},						
		send: function(){
			$.post(
				"index.php?doc-editletter",{did:<?php echo $doc['did']?>,first_letter:$('#first_letter').val()},
				function(xml){
					var message=xml.lastChild.firstChild.nodeValue;
					if(message=='1'){
						alert('���óɹ�');
						newletter=$('#first_letter').val();
						document.getElementById("fletter").value = newletter;
						$.dialog.close('firstletter');
					}
					if(message=='-1'){
						alert('����������a-z��Ӣ����ĸ,�����ִ�Сд');
					}
				}
			);
		}
	}
</script>
<!--�ο��������� ��ʼ-->
<script type="text/javascript">
var g_docid = "<?php echo $doc['did']?>";
var docReference = {
	editid:0,
	verify_code:0,
	text_name:"������ο����ϵ����ƣ��������鼮�����ף�����վ�����ơ������",
	text_url:"����д��ϸ��ַ���� http:// ��ͷ",
	
	init: function(){
		var self = this;
		$('div#reference dd span[name=edit]').css('visibility', 'hidden');
		
		$("#editrefrencename").focus(function(){
			if(this.value == self.text_name){
				this.value='';
				this.style.color='#333';
			}
		});
		
		$("#editrefrenceurl").focus(function(){
			if(this.value == self.text_url){
				this.value='';
				this.style.color='#333';
			}
		});
		
		$.get('index.php?reference-add-checkable-'+Math.random(), function(data, state){
			if ('OK' == data || 'CODE' == data){
				$("#edit_reference").show();
				$("#edit_reference1").show();
				$("div#reference dd").mouseover(function(){
					$(this).find('span[name=edit]').css('visibility', '');
				});
				
				$("div#reference dd").mouseout(function(){
					$(this).find('span[name=edit]').css('visibility', 'hidden');
				});
				
				if('CODE' == data){
					self.setVerifyCode();
					self.verify_code = 1;
					$("div#reference li[name=verifycode]").show();
				}
			}else{
				if( !$("div#reference dl.f8:visible").size() ){
					$("div#reference").hide();
				}
			}
		});
		return this;
	},
	
	reset: function(){
		var self = this;
		$("#editrefrencename").val(this.text_name).css('color', '#999');
		$("#editrefrenceurl").val(this.text_url).css('color', '#999');
		self.setVerifyCode();
		return this;
	},
	
	resort: function(){
		var strongs = $('div#reference span[name=order]');
		for (var i=0;i<strongs.length; i++){
			$(strongs.get(i)).html("["+(i)+"]");
		}
	},
	
	check: function(){
		var self=this, name,url, code="";
		$("#refrencenamespan").html('');
		$("#refrenceurlspan").html('');
		$("#refrencecodespan").html('');
		
		name = $.trim($("#editrefrencename").val());
		url = $.trim($("#editrefrenceurl").val());
		code = $.trim($("#editrefrencecode").val());
		
		if ('' == name || this.text_name == name){
			$("#refrencenamespan").html('�ο���������Ϊ������');
			return false;
		}
		
		if (url == this.text_url){
			url = '';
		}
		if (url && !/^https?:\/\//i.test(url)){
			$("#refrenceurlspan").html('�ο�����URL����Ϊ�� http:// �� https:// ��ͷ����ַ');
			return false;
		}
		
		if(self.verify_code && !code){
			$("#refrencecodespan").html('��������֤��');
			return false;
		}
		
		if(self.verify_code && code.length != 4){
			$("#refrencecodespan").html('��֤����Ҫ����4���ַ�');
			return false;
		}
		
		return {name:name, url:url, code:code};
	},
	
	save: function(){
		var self=this, value = this.check();
		if (value == false) return;
			
		if (this.editid == 0){
			this.add(value);
		}else{
			var name = value.name, url = value.url, code=value.code;
			
			//$("#save_1").hide();
			//$("#save_0").show();
			$("#save_1").show();
			$.ajax({
				url:'index.php?reference-add',
				data:{'data[id]':self.editid, 'data[name]':name, 'data[url]':url, 'data[code]':code},
				type:'POST',
				success:function(text, state){
					//alert(1);return;
					if ($.trim(text) == '1'){
						var dl = $('div#reference dl[id='+self.editid+']');
						dl.find('span[name=refrencename]').html(name);
						dl.find('span[name=url]').html(url);
						self.editid = 0;
						self.resort();
						self.reset();
					}else if( 'code.error' == text ){
						$("#refrencecodespan").html('��֤�����');
					}else{
						alert('��ʾ���ο������޸�ʧ�ܣ�');
					}
				},
				complete:function(XMLHttpRequest, state){
					if (state != 'success'){
						alert('��ʾ���ο������޸�ʧ�ܣ�');
					}
					//$("#save_0").hide();
					$("#save_1").show();
				}
			});
		}
	},
	
	add: function(value){
		var name = value.name, url = value.url, code=value.code, self=this;
		
		//$("#save_1").hide();
		//$("#save_0").show();
		$("#save_1").show();
		$.ajax({
			url:'index.php?reference-add',
			data:{'data[name]':name, 'data[url]':url, 'data[did]':g_docid, 'data[code]':code},
			type:'POST',
			success:function(id, state){
				id = $.trim(id);
				if (/[1-9]+/.test(id)){
					var dl = $('div#reference dl[id=0]').clone(true);
					//dl.attr('id', id).show();
					//dl.find('span[name=refrencename]').html(name);
					//dl.find('span[name=url]').html(url);
					if(state=='success'){
						alert('��ӳɹ�!');
						location.reload(true);
					}
					$('div#reference ul').before(dl);
					self.resort();
					self.reset();
				}else if( 'code.error' == id ){
					$("#refrencecodespan").html('��֤�����');
				}else{
					alert('��ʾ���ο��������ʧ�ܣ�');
				}
			},
			complete:function(XMLHttpRequest, state){
				if (state != 'success'){
					alert('��ʾ���ο��������ʧ�ܣ�');
				}
				//$("#save_0").hide();
				$("#save_1").show();
			}
		});
	},
	
	edit: function(el){
		if (typeof el != 'object') return;
		var dl = $(el).parents('dl');
		this.editid = dl.attr('id');
		var name, url;
		name = $(dl).find('span[name=refrencename]').html();
		url = $(dl).find('span[name=url]').html();
		
		$("#editrefrencename").val(name).css('color', '#333');
		$("#editrefrenceurl").val(url).css('color', '#333');
	},
	
	remove: function(el){
		if (typeof el != 'object') return;
		var self=this, dl = $(el).parents('dl');
		$(el).attr('onclick', '');
		var id = dl.attr('id');
		$.ajax({
			url:'index.php?reference-remove-'+id,
			success:function(text, state){
				text = $.trim(text);
				if (text != '0'){
					
					$(dl).remove();
					self.resort();
					self.reset();
				}else{
					alert('��ʾ���ο�����ɾ��ʧ�ܣ�');
					$(el).attr('onclick', 'docReference.remove(this)');
				}
			},
			complete:function(XMLHttpRequest, state){
				if (state != 'success'){
					alert('��ʾ���ο�����ɾ��ʧ�ܣ�');
					$(el).attr('onclick', 'docReference.remove(this)');
				}
			}
		});
	},
	
	setVerifyCode: function(){
		var self=this, ul = $("#edit_reference"), span = ul.find("label[name=img]");
		ul.find("label[name=tip]").html("[����������ʾ��֤��]").show();
		span.hide();
		$("#editrefrencecode").val('');
		ul.find("input[name=code]").one('focus', function(){
			self.updateVerifyCode();
			span.show();
			ul.find("label[name=tip]").hide();
		});
	},
	
	updateVerifyCode: function(){
		$('#verifycode2').attr('src', "index.php?user-code-"+Math.random());
	}
}

function reference_edit(){ 
	$("#reference_view").hide();
	$("#reference").show();
    docReference.init().reset();
}

function reference_view(){ 
    $("#reference").hide();
	$("#reference_view").show();
}
</script>
<!--�ο��������� ����-->
<script type="text/javascript" src="js/openremoveimage.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//�����������д���ģ����������js��css
	if($('pre.prettyprint').length) {
		$.getScript("js/prettify.js",function(){
			if(typeof prettyPrint == 'function') {
				$('head').append('<link href="style/default/prettify.css" type="text/css" rel="stylesheet" media="all"/>');
				prettyPrint();
			}
		});
	}
});
</script>
<?php include $this->gettpl('footer');?>