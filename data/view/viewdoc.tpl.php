<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<div class="w-950 hd_map"> <a href="<?php echo WIKI_URL?>"><?php echo $setting['site_name']?></a> &gt;&gt;所属分类 &gt;&gt;<span id="catenavi">
  <?php foreach((array)$navigation as $key=>$category) {?>
  <a href="index.php?category-view-<?php echo $category['cid']?>"><?php echo $category['name']?> </a>&nbsp;&nbsp;
  <?php }?>
  </span> </div>
<div class="l w-710 o-v">
  <h1 class="title_thema"> <span id='doctitle'><?php echo $doc['doctitle']?></span>
    <label id='auditmsg'>
      <?php if($doc['visible']=='0') { ?>
      --该词条未被审核
      <?php } ?>
    </label>
    <label id='lockimage'>
      <?php if($doc['locked']) { ?>
      <image src="style/default/lock.gif"/>
      <?php } ?>
    </label>
  </h1>
  <div class="subordinate">
    <p class="cate_open"> <span class="bold">标签:</span>
      <?php if(count($doc['tag'] )>0) { ?>
      <?php foreach((array)$doc['tag'] as $key=>$tag) {?>
      <a href="index.php?search-tag-<?php echo $tag?>" name="tag"><?php echo $tag?></a>
      <?php }?>
      <?php } else { ?>
      <span name="nonetag">暂无标签</span>
      <?php } ?>
      <?php if($doc_edit) { ?>
      <span class="w-110" onclick="Tag.box(<?php echo $doc['did']?>,this)"><a href="javascript:void(0);">编辑/添加标签</a></span>
      <?php } ?>
      <?php if($doc_editletter) { ?>
      <span class="w-110" onclick="Letter.box(<?php echo $doc['did']?>,this)"><a href="javascript:void(0);">设置词条首字母</a>
      <input type='hidden' id='fletter' value='<?php echo $docletter?>'>
      </span>
      <?php } ?>
    </p>
    <span class="editteam"> <a href="javascript:void(0)" id="ding" onclick="vote(this)">顶[<span><?php echo $doc['votes']?></span>]</a> <a class="share_link" id="share_link">分享到</a> <a href="index.php?comment-view-<?php echo $doc['did']?>">发表评论(<?php echo $doc['comments']?>)</a> <a id="editImage" href="index.php?doc-edit-<?php echo $doc['did']?>"  class="edit_ct" onclick="return doc_is_locked()">编辑词条</a>
    <label class="share_btn" id="share_btn" style="display:none">
      <input id="sitename" name="sitename" value="<?php echo $setting['site_name']?>" type="hidden">
      <input id="firstimg" name="firstimg" value="<?php echo $firstimg?>" type="hidden">
      
 <a href="javascript:void(0)" onclick="postToWb();return false;" style="background:url(http://v.t.qq.com/share/images/s/weiboicon16.png) no-repeat left 0px;">腾讯微博</a><script type="text/javascript">
	function postToWb(){
		var _t = encodeURI("<?php echo $doc['qq_title']?>");
		var _url = encodeURIComponent(document.location);
		var _appkey = encodeURI("aa6cb794b12c41c29d6490f4624b77a9");//你从腾讯获得的appkey
		var _pic = encodeURI("<?php echo $doc['pic_str']?>");//（例如：var _pic='图片url1|图片url2|图片url3....）
		var _site = '';//你的网站地址
		var _u = 'http://v.t.qq.com/share/share.php?url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic+'&title='+_t;
		window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
	}
</script>

      
      
      
      <a href="#" class="kaixin001">开心001</a> <a href="#" class="renren">人人网</a> <a href="#" class="sina_blog">新浪微博</a> </label>
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
  <p id="lock" class="red bor-ccc lock_word">友情提示：正在被<a href="index.php?user-space-<?php echo $editlock['user']['uid']?>"><?php echo $editlock['user']['username']?></a>锁定编辑中!</p>
  <?php } ?>
  <?php if($synonymdoc) { ?>
  <p class="red bor-ccc lock_word">"<?php echo $synonymdoc?>"是"<?php echo $doc['doctitle']?>"的同义词</p>
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
      <legend><a name='section'>目录</a></legend>
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
      <p><a href="javascript:void(0);" onclick="partsection();"  id="partsection" style="display:none">[显示部分]</a><a href="javascript:void(0);" onclick="fullsection();" id="fullsection">[显示全部]</a></p>
      <?php } ?>
    </fieldset>
    <?php } ?>
    <h3><span class="texts"><?php echo $section['value']?></span><a name="<?php echo $key?>" href="index.php?doc-editsection-<?php echo $doc['did']?>-<?php echo $key?>" >编辑本段</a><a href="index.php?doc-view-<?php echo $doc['did']?>#section">回目录</a></h3>
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
      <dt><?php if($reference_add) { ?><a class="r h3"  href="javascript:reference_edit();">[编辑]</a><?php } ?>参考资料</dt>
      <?php foreach((array)$referencelist as $i=>$ref) {?>
      <dd> <span>[<?php echo ($i+1)?>].</span>&nbsp;&nbsp;<?php echo $ref['name']?> &nbsp;&nbsp;<span style="color:#666666"><?php echo $ref['url']?></span> </dd>
      <?php }?>
    </dl>
    

    <div id="reference" class="hd-box editor_left" style="display:none;" >
     <dl class="reference">
     <dt><!--<a class="r h3"  href="javascript:reference_view();">[完成]</a>参考资料--></dt>
     </dl>
	<dl class="f8 reference" id="0" style="display:none;">
    <dd><span name="order">[0]</span>&nbsp;&nbsp;<span name='refrencename'></span> <span name="url" style="color:#666666"></span> <span name="edit" > <a href="javascript:;" onclick="docReference.edit(this);return false;">编辑</a> 
			| <a name="remove" href="javascript:;" onclick="docReference.remove(this);return false;">删除</a></span> </dd>
	</dl>
	<?php foreach((array)$referencelist as $i=>$ref) {?>
	<dl class="f8 reference" id="<?php echo $ref['id']?>">
		<dd><span name="order">[<?php echo ($i+1)?>]</span>&nbsp;&nbsp;<span name='refrencename'><?php echo $ref['name']?></span> <span name="url" style="color:#666666"><?php echo $ref['url']?></span> <span name="edit" > <a href="javascript:;" onclick="docReference.edit(this);return false;">编辑</a>
			| <a name="remove" href="javascript:;" onclick="docReference.remove(this);return false;">删除</a></span> </dd>
	</dl>
	<?php }?>
	
	<ul id="edit_reference" class="ul_l_s add_reference" style="display:none;">
		<li class="mar-bottom-10"><strong>名称:</strong>
			<input id="editrefrencename" type="text" class="inp_txt" size="60"/>
			<label class="red" id="refrencenamespan"></label>
		</li>
		<li class="size black mar-bottom-10"><strong>网址:</strong>
			<input id="editrefrenceurl" type="text" class="inp_txt" size="60"/>
			<label class="red" id="refrenceurlspan"></label>
		</li>
		
		<li name="verifycode" class="size black mar-bottom-10" style="display:none"><strong>验证码:</strong>
			<input name="code" id="editrefrencecode" type="text" class="inp_txt" size="10" maxlength="4"/>
			<label name="img" style="display:none"><img id="verifycode2" src="./js/hdeditor/skins/spacer.gif"/> <a href="javascript:docReference.updateVerifyCode();">看不清图片</a></label>
			<label name="tip"></label>
			<label class="red" id="refrencecodespan"></label>
		</li>
		<li>
			<div id="edit_reference1" class="ul_l_s" style="display:none;">
				
					<input type="button" class="btn_inp" onclick="docReference.save();return false;" value="保存" id="save_1"  name="save_1"  />
				<!--<input type="button"  class="btn_inp" value="保存" name="save_0" id="save_0"  style="display:none" />
					<a id="save_1" href="javascript:void(0);" onclick="docReference.save();return false;" style="border:1px red solid;">保存</a>
					<span id="save_0" style="display:none">保存</span>
					<a href="javascript:;" onclick="docReference.reset();return false;">重置</a>-->
					<input type="button" class="btn_inp" onclick="docReference.reset();return false;" name="reset" value="重置" />
			
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
  >附件列表
  </h3>
  <dl style="display: none;">
    <dt><img class="fj_img"/><a></a><br/>
      <span class="l">
      <label> 下载次数：0</label>
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
      <label>下载次数：<?php echo $attach['downloads']?></label>
      &nbsp;
      <label>下载所需金币：<?php echo $attach['coindown']?></label>
      </span>
      <?php if($attachment_remove && ($attach['uid']==$userid || $groupid==4) ) { ?>
      [<a href="javascript:;" onclick="Attachment.remove(this, <?php echo $attach['id']?>)">删除</a>]
      <?php } ?>
    </dt>
    <dd><?php echo $attach['description']?></dd>
  </dl>
  <?php } ?>
  <?php } else { ?>
  <p class="m-lr8 m-t8">您所在的用户组无法下载或查看附件</p>
  <?php } ?>
  <?php } ?>
  <?php if($setting['attachment_open'] && $attachment_upload) { ?>
  <div>
    <form id="attachment_upload" action="index.php?attachment-upload" enctype="multipart/form-data" 
			method="post" target="upload" style="display:none" onsubmit="return Attachment.submit(this)">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $setting['attachment_size']*1024 ?>" />
      <div>
        <input name="attachment[]" type="file" onkeydown="return false" onpaste="return false" autocomplete="off" onchange="Attachment.add(this)">
        附件出售价格：
        <input name="coin_download[]" class="coin_download" type="text" value="0" size="2" onchange="check_coin($(this))" />
        (0-
        <?php echo $coin_download?>
        金币之间)
        附件描述：
        <input name="attachmentdesc[]" type="text" class="attachmentdesc" size="20" maxlength="50" autocomplete="off"/>
        <a href="javascript:;" onclick="Attachment.unadd(this)" style="display:none">删除</a> </div>
      <br/>
      <input type="submit" value="上传" />
      <input type="hidden" value="<?php echo $doc['did']?>" name="did"/>
      <span>[上传附件文件尺寸:小于  <?php echo $setting['attachment_size']?> KB]</span>
    </form>
    <a href="javascript:;" onclick="Attachment.upload(this)">上传附件</a> <span id="attachment_error" style="color:red"></span> </div>
  <iframe name="upload" id="upload" style="display:none;" ></iframe>
  <?php } ?>
  <input type="hidden" name="coin_hidden" id="coin_hidden" value="<?php echo $coin?>"  />
</div>
<div class="bor_b-ccc m-t10 notes">
  <p class="l">→如果您认为本词条还有待完善，请 <a class="font14" href="index.php?doc-edit-<?php echo $doc['did']?>">编辑词条</a></p>
  <p class="r">
    <?php if($neighbor['predoc']) { ?>
    上一篇<a href="index.php?doc-view-<?php echo $neighbor['predoc']['did']?>"  class="m-lr8"><?php echo $neighbor['predoc']['title']?></a>
    <?php } ?>
    <?php if($neighbor['nextdoc']) { ?>
    下一篇<a href="index.php?doc-view-<?php echo $neighbor['nextdoc']['did']?>"  class="m-lr8"><?php echo $neighbor['nextdoc']['title']?></a>
    <?php } ?>
  </p>
</div>
<p class="useful_for_me"> <span>词条内容仅供参考，如果您需要解决具体问题<br/>（尤其在法律、医学等领域），建议您咨询相关领域专业人士。</span> <a href="javascript:void(0)" onclick="vote(this)">
  <label id="votemsg">本词条对我有帮助</label>
  <b><?php echo $doc['votes']?></b></a> </p>
<div class="bor-ccc m-t10 bg-gray notes add">
  <p class="add_synonym">
    <label class="l w-550"><b>同义词</b>：
      <?php if(!empty($synonyms)) { ?>
      <span id="str">
      <?php foreach((array)$synonyms as $key=>$synonym) {?>
      <a href="index.php?doc-innerlink-<?php echo urlencode($synonym['srctitle'])?>" name='synonym'><?php echo $synonym['srctitle']?></a>
      <?php }?>
      </span>
      <?php } else { ?>
      <span name="nonesynonym">暂无同义词</span>
      <?php } ?>
    </label>
    <?php if($synonym_audit) { ?>
    <span class="r w-110 cursor" onclick="Synonym.box(<?php echo $doc['did']?>,this)"><img src="style/default/add_synonym.gif"/><a href="javascript:void(0)" >编辑/添加同义词</a></span>
    <?php } ?>
  </p>
</div>
<div class="bor-ccc m-t10 notes bg-gray bookmark">
  <p><span class="bold">收藏到: </span> <a title="Favorites" onclick="addfav();"><img src='style/default/bookmark/ie.gif' border='0' style="cursor:pointer;"></a> &nbsp;
    <script language="javascript"src='js/bookmark.js'></script>
    <?php if(!empty($userid)) { ?>
    <img id="doc_favorite" did="<?php echo $doc['did']?>" title="收藏到个人空间" alt="收藏到个人空间" src="style/default/bookmark/hudong.gif" style="cursor:pointer;">
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
  <h2 class="col-h2">相关评论</h2>
  <a href="index.php?comment-view-<?php echo $doc['did']?>" target="_blank" class="more">查看更多&gt;&gt;</a>
  <form method="post" action="index.php?comment-add-<?php echo $doc['did']?>">
    <ul class="col-ul">
      <li>
        <textarea id="comment" name="comment" cols="95" rows="10" class="area"></textarea>
        <input id='anonymity' name="anonymity" type="checkbox" />
        匿名</li>
      <li class="yzm">
        <?php if($setting['checkcode'] != "3") { ?>
        <span>验证码: </span>
        <input name="code2" type="text" />
        <label class="m-lr8"><img id="verifycode" src="index.php?user-code" onclick="updateverifycode();" /></label>
        <a href="javascript:updateverifycode();">换一个</a>
        <?php } ?>
        &nbsp;&nbsp;&nbsp;&nbsp;注:评论长度最大为200个字符。</li>
      <li>
        <input name="submit" type="submit" value="发表评论" class="btn_inp"/>
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
    <h2 class="col-h2">词条管理</h2>
    <form method="post">
      <dl>
        <dt>操作</dt>
        <dd class="a-c">
          <input name="Button2" type="button" value="重命名" class="m-lr8 btn_inp" onclick="doc_rename();" />
          <input id="editcategory" name="Button3" type="button" value="编辑分类" class="m-lr8 btn_inp" onClick="javascript:catevalue.ajax(0,this);"/>
        </dd>
        <dt>状态</dt>
        <dd>
          <label class="l" ><a href='javascript:void(0);' onclick="lock('lock');">锁定</a></label>
          <label class="r" ><a href='javascript:void(0);' onclick="lock('unlock');">解锁</a></label>
        </dd>
        <dd>
          <label class="l" ><a href='javascript:void(0);' onclick="recommend();">推荐</a></label>
          <label class="r" ><a href='javascript:void(0);' onclick="updatastatus(0);"> 取消推荐</a></label>
        </dd>
        <dd>
          <label class="l" ><a href='javascript:void(0);' onclick="audit();">审核</a></label>
          <label class="r" ><a href='index.php?doc-remove-<?php echo $doc['did']?>' onclick="return remove()">删除</a></label>
        </dd>
      </dl>
    </form>
  </div>
  <?php } ?>
  <div class="columns ctxx">
    <h2 class="col-h2">词条信息</h2>
    <?php if($author) { ?>
    <?php if(!isset($lasteditor) || (isset($lasteditor) && $lasteditor['uid']!=$author['uid'])) { ?>
    <dl class="col-dl twhp2">
      <dd><a href="index.php?user-space-<?php echo $author['uid']?>" target="_blank"  class="a-img1"> <img alt="<?php echo $author['username']?>" title="<?php echo $author['username']?>" src="<?php if($author['image']) { ?><?php echo $author['image']?><?php } else { ?>style/default/user_l.jpg<?php } ?>" width="38px" height="38px" /> </a></dd>
      <dt><a href="index.php?user-space-<?php echo $author['uid']?>" target="_blank"><?php echo $author['username']?></a></dt>
      <dd><span style="color:<?php echo $author['color']?>" class="l m-r8"><?php echo $author['grouptitle']?></span> <span title="星星数 <?php echo $author['stars']?>" class="l">
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
      <dd>词条创建者 <a onclick="javascript:Message.box('<?php echo $author['username']?>')"   href="javascript:void(0)">发短消息</a> &nbsp;&nbsp;<img src="style/default/jb.gif" title="<?php echo $author['credit1']?>金币"></dd>
    </dl>
    <?php } ?>
    <?php } ?>
    <?php if($author_removed) { ?>
    <dl class="col-dl twhp2">
      <dd><a class="a-img1"> <img alt="已删除" src="style/default/user_l.jpg" width="38px" height="38px" /></a></dd>
      <dt>此用户已删除</dt>
      <dd>词条创建者</dd>
    </dl>
    <?php } ?>
    <?php if(isset($lasteditor) ) { ?>
    <dl class="col-dl twhp2">
      <dd><a href="index.php?user-space-<?php echo $lasteditor['uid']?>" target="_blank"  class="a-img1"> <img alt="<?php echo $lasteditor['username']?>" title="<?php echo $lasteditor['username']?>" src="<?php if($lasteditor['image']) { ?><?php echo $lasteditor['image']?><?php } else { ?>style/default/user_l.jpg<?php } ?>" width="38px" height="38px" /> </a></dd>
      <dt><a href="index.php?user-space-<?php echo $lasteditor['uid']?>" target="_blank"><?php echo $lasteditor['username']?></a></dt>
      <dd><span class="l m-r8" style="color:<?php echo $lasteditor['color']?>" ><?php echo $lasteditor['grouptitle']?></span> <span title="星星数 <?php echo $lasteditor['stars']?>" class="l">
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
      <dd>最近编辑者 <a onclick="javascript:Message.box('<?php echo $lasteditor['username']?>')"   href="javascript:void(0)">发短消息</a> &nbsp;&nbsp;<img src="style/default/jb.gif" title="<?php echo $lasteditor['credit1']?>金币"></dd>
    </dl>
    <?php } ?>
    <?php if($lasteditor_removed) { ?>
    <dl class="col-dl twhp2">
      <dd><a class="a-img1"> <img alt="已删除" src="style/default/user_l.jpg" width="38px" height="38px" /></a></dd>
      <dt>此用户已删除</dt>
      <dd>最近编辑者</dd>
    </dl>
    <?php } ?>
    <ul class="col-ul bor-ccc">
      <li>浏览次数: <?php echo $doc['views']?> 次</li>
      <?php if($doc['editions'] ) { ?>
      <li>编辑次数: <?php echo $doc['editions']?>次 <a href="index.php?edition-list-<?php echo $doc['did']?>" target="_blank" class="m-l8">历史版本</a></li>
      <?php } ?>
      <li>更新时间: <?php echo $doc['lastedit']?></li>
    </ul>
  </div>
  <div class="columns">
    <h2 class="col-h2">相关词条</h2>
    <?php if($relate) { ?>
    <a href="javascript:void(0)" onclick="relateddoc('block')" class="more">添加</a>
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

<!--参考资料验证码-->
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
	var html="词条名称 :  <form action='' onsubmit='update_docname();return false;'><input id='newname' type='text' value='"+title+"' maxlength='80' height='40'><br><br>"+
	"<input name='renamesbumit' type='button' onclick='update_docname()' value='提交'>"+
	"<input name='cancel' type='button' onclick='closepop(\"rename\")' value='取消'><br><br><label id='updatetitlenotice' style='height:20px;color:red'>&nbsp;</label></form>";
	$.dialog.box('rename', '重命名', html);
}
function update_docname(){
	clearTimeout(timeout_pop);
	if($.trim($('#newname').val())==''){
		$("#updatetitlenotice").html('名称不能为空');
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
				$("#updatetitlenotice").html('新名称已存在!请重新命名');
			}else if(message=='-3'){
				$("#updatetitlenotice").html('含有危险代码');
			}else if(message=='-4'){
				$("#updatetitlenotice").html('名称不能为空');
			}else{
				$("#updatetitlenotice").html('操作失败');
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
				$.dialog.box('lock', '词条锁定', '已经成功锁定/解锁该词条');
			}else{
				$.dialog.box('lock', '词条锁定', '操作失败');
			}
			timeout_pop=setTimeout("closepop('lock')",3000);
		}
	);
}

function recommend(){
	var html="词条状态 :  <select id='recommend_type' ><option value='1'>推荐词条</option><option value='2'>热门词条</option><option value='3'>精彩词条</option></select><br><br>"+
	"<input name='renamesbumit' type='button' onclick='updatastatus($(\"#recommend_type\").val())' value='提交'>"+
	"<input name='cancel' type='button' onclick='closepop(\"recommend\")' value='取消'>";
	$.dialog.box('recommend', '推荐词条', html);
}

function updatastatus(type){
	clearTimeout(timeout_pop);
	$.post(
		"index.php?doc-setfocus",{did:<?php echo $doc['did']?>,visible:<?php echo $doc['visible']?>,doctype:type},
		function(xml){
			var	message=xml.lastChild.firstChild.nodeValue;
			if(message=='1'){
				$.dialog.box('recommend', '推荐词条', '已经成功设置词条状态');
			}else{
				$.dialog.box('recommend', '推荐词条', '操作失败');
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
				$.dialog.box('audit', '审核词条', '已经成功审核了词条');
			}else{
				$.dialog.box('audit', '审核词条', '操作失败');
			}
			timeout_pop=setTimeout("closepop('audit')",3000);
		}
	);
}

function remove(){
	var url="index.php?doc-remove-<?php echo $doc['did']?>";
	return confirm("删除版本后不可恢复,确定删除吗")
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
							title:'编辑分类',
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
			$('#scnames').html('&nbsp;&nbsp;分类不允许为空').fadeIn();
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
			$.dialog.box('synonym','编辑同义词', html);
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
			//检查是否已修改
			if (this.srctitles == this.inputsrc){
				$.dialog.close('synonym');
				return;
			}

			$("#synonymSubmit").attr('disabled', true).val(Lang.Submiting);
			$.post("index.php?synonym-savesynonym", formData, function(data, status){

				$("#synonymSubmit").attr('disabled', false).val(Lang.Submit);
				if (status == 'success'){
					if(data==0){
						$("#synonymTip").html('没有添加任何同义词！');
					}else if(data==-1){
						$("#synonymTip").html("参数错误");
					}else if(data==-2){
						$("#synonymTip").html('同义词本身不能指向自己');
					}else if(data==-3){
						$("#synonymTip").html('有不允许创建的字符');
					}else if(data==-4){
						$("#synonymTip").html('已经被别的同义词指向。');
					}else if(data==-5){
						$("#synonymTip").html('已经指向其他同义词,请勿重复指向');
					}else if(data==-6){
						$("#synonymTip").html('已经被别的同义词指向。');
					}else if(data=='empty'){ //如果返回 empty,表示已清空所有同义词
						Synonym.change('');
						$.dialog.close('synonym');
					} else { //否则，按返回的数据更新页面上显示的同义词
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
				html='<span name="nonesynonym">暂无同义词</span>';
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
				html='<span name="nonetag">暂无标签</span>';
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
		+'<li onclick="addrelatedoc();"><input name="Button1" type="submit" value="保存"  class="btn_inp" /></li></ul></form>';
		$.dialog.box('relatedoc','编辑相关词条', html);
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
					alert('含有违禁词,请重新添加！');
				}else{
					alert('操作失败');
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
			alert('附件名称太长!请重命名后再上传!');
		    $(el).before('<input name="attachment[]" type="file" onkeydown="return false" onpaste="return false" autocomplete="off" onchange="Attachment.add(this)">').remove();
			return false;
		}
		if (attachment_type && !attachment_type.test(value)){
			alert("该类型附件不被允许上传");
		    $(el).before('<input name="attachment[]" type="file" onkeydown="return false" onpaste="return false" autocomplete="off" onchange="Attachment.add(this)">').remove();
			return false;
		}
		var div = $(el).parent('div');
		/*var isSelect = div.parent('form').find("input[type=file][value='"+value+"']").size();
		var isUpload = div.parents("div.fj_list").find("a:contains('"+this.getname(value)+"')").size();
		if (isSelect > 1 || isUpload>=1) {
			alert("附件选择重复！");
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
					alert("删除失败");
				}
			}else{
				alert("删除失败");
			}
		})
	},
	
	submit: function(form){
		var file = $(form).find("input[type=file]:first");
		if (file.val() == ''){
			alert("请选择需要上传的文件！");
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
					$('#votemsg').html('本词条对我有帮助');
					$("#ding span").html(votes);
					
					$.get("index.php?hdapi-hdautosns-ding-<?php echo $doc['did']?>");
				}else{
					$('#votemsg').html('您已评价，谢谢！');
					if($(el).attr('id') == 'ding'){
						$.dialog.box('jqdialogtip', '提示', '您已评价，谢谢！');
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
			content:'<img title="回顶部" style="cursor:pointer" src="<?php echo WIKI_URL?>/style/default/up.png" style="width:23px; height:66px" onclick="scrollToTop()"/>'
		});
	});

	var clock_doc_locked=0;
	function doc_is_locked(){
		if($.trim($('#lockimage').html())!=""){
			$.dialog.box('fobbiden', '禁止编辑', '<b>此词条被禁止编辑!</b>');
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
	
	//内链不存在时的颜色，可以使用突出的红色 red 或和普通文本一样的黑色 #000000，或者其他您喜欢的颜色等等。默认为红色。
	var innerlink_no_exist_color='red';
	$("a[href^='javascript:innerlink']").each(function(){
		var a=$(this), title=a.text();
		if(title.indexOf('"') > -1){
			title = title.replace('"', '\\"');
		}
		a.attr('title', '词条“'+title+'”不存在，点击可创建')
			.addClass('link_doc_no').attr('href', 'javascript:innerlink("'+title+'")').removeAttr("target");
	});
	
	$(document).ready(function(){
		$("#doc_favorite").click(function(){
			var did = $(this).attr('did');
			var result = '';
			$.post("index.php?user-addfavorite",  {did:did},function(data){
				switch (data) {
					case '1' :
						result = '词条成功被收藏至个人中心！';
						break;
					case '2' :
						result = '此词条已经被收藏！';
						break;
					case '3' :
						result = '指定词条不存在或者已经被删除！';
						break;
					default :
						result = '参数错误!';
						break;
				}
				$.dialog.box('user_addfavorite', '收藏词条', result);
			});
		})
		
		$(".file_download").click(function(){
			var coin_down = $(this).attr("coin_down");
			var attach_id = $(this).attr("attach_id");
			var uid = $(this).attr("uid");
			var coin_hidden = $("#coin_hidden").val();
			coin = coin_hidden - coin_down;
			if(attach_id != uid && coin < 0) {
				$.dialog.box('coin_down', '附件下载', '金币不足！');
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
			+'<tr><td height="40">词条首字母：<input id="first_letter" type="text" class="inp_txt" maxlength="1" size="10"/></td></tr>'
			+'<tr><td height="40"><input id="letterSubmit" type="submit" value="'+Lang.Submit+'" />'
			+'<span id="tagTip"></span></td></tr></table></form>';
			$.dialog.box('firstletter', '设置词条首字母', html);
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
						alert('设置成功');
						newletter=$('#first_letter').val();
						document.getElementById("fletter").value = newletter;
						$.dialog.close('firstletter');
					}
					if(message=='-1'){
						alert('您必须输入a-z的英文字母,不区分大小写');
					}
				}
			);
		}
	}
</script>
<!--参考资料设置 开始-->
<script type="text/javascript">
var g_docid = "<?php echo $doc['did']?>";
var docReference = {
	editid:0,
	verify_code:0,
	text_name:"请填入参考资料的名称，可以是书籍、文献，或网站的名称。（必填）",
	text_url:"请填写详细网址，以 http:// 开头",
	
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
			$("#refrencenamespan").html('参考资料名称为必填项');
			return false;
		}
		
		if (url == this.text_url){
			url = '';
		}
		if (url && !/^https?:\/\//i.test(url)){
			$("#refrenceurlspan").html('参考资料URL必需为以 http:// 或 https:// 开头的网址');
			return false;
		}
		
		if(self.verify_code && !code){
			$("#refrencecodespan").html('请输入验证码');
			return false;
		}
		
		if(self.verify_code && code.length != 4){
			$("#refrencecodespan").html('验证码需要输入4个字符');
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
						$("#refrencecodespan").html('验证码错误');
					}else{
						alert('提示：参考资料修改失败！');
					}
				},
				complete:function(XMLHttpRequest, state){
					if (state != 'success'){
						alert('提示：参考资料修改失败！');
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
						alert('添加成功!');
						location.reload(true);
					}
					$('div#reference ul').before(dl);
					self.resort();
					self.reset();
				}else if( 'code.error' == id ){
					$("#refrencecodespan").html('验证码错误');
				}else{
					alert('提示：参考资料添加失败！');
				}
			},
			complete:function(XMLHttpRequest, state){
				if (state != 'success'){
					alert('提示：参考资料添加失败！');
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
					alert('提示：参考资料删除失败！');
					$(el).attr('onclick', 'docReference.remove(this)');
				}
			},
			complete:function(XMLHttpRequest, state){
				if (state != 'success'){
					alert('提示：参考资料删除失败！');
					$(el).attr('onclick', 'docReference.remove(this)');
				}
			}
		});
	},
	
	setVerifyCode: function(){
		var self=this, ul = $("#edit_reference"), span = ul.find("label[name=img]");
		ul.find("label[name=tip]").html("[点击输入框显示验证码]").show();
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
<!--参考资料设置 结束-->
<script type="text/javascript" src="js/openremoveimage.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//文字内容中有代码模块则加载相关js和css
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