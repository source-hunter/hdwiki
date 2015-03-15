<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<p class="map">全局：扩展设置</p>
<p class="sec_nav">扩展设置：
    <a href="index.php?admin_setting-sec" > <span>防灌水设置</span></a>
    <a href="index.php?admin_setting-anticopy"><span>防采集设置</span></a>
    <a href="index.php?admin_setting-mail"><span>邮件设置</span></a>
    <a href="index.php?admin_setting-noticemail" class="on"><span>邮件提醒设置</span></a>
    <a href="index.php?admin_banned" ><span>IP禁止</span></a>
    <a href="index.php?admin_setting-passport" ><span>通行证设置</span></a>
    <a href="index.php?admin_setting-ucenter"><span>UCenter设置</span></a>
    <a href="index.php?admin_setting-ldap"><span>LDAP设置</span></a>
</p>
<h3 class="col-h3">邮件提醒设置</h3>
<ul class="col-ul tips">
	<li class="bold">提示:</li>
	<li>选中的用户组会在发生词条操作事件时收到Email通知。</li>
	<li>如果接收邮件提醒的用户过多，会对系统造成较大负担，请只选择有必要接收提醒的用户或用户组。</li>
	<li>关于模版创建规则，请<a href="http://kaiyuan.hudong.com/bbs" target="_blank">点击这里</a></li>
</ul>
<form method="POST" action="index.php?admin_setting-noticemail" >
  <table class="table">
		<tr><td width="160px"><span>词条创建邮件提醒设置：</span></td><td></td></tr>
		<tr>
			<td>邮件主题模板：</td>
			<td><label><input type="text"  class="inp_txt" name="noticemailtpl[doc_create][subject]" value="<?php echo htmlspecialchars($noticemailtpl['doc_create']['subject'])?>" /></label>
			</td>
		</tr>
				
		<tr>
			<td>邮件正文模板：</td>
			<td>
				<label><textarea name="noticemailtpl[doc_create][body]" class="textarea" rows="6" style="width: 400px;"><?php echo htmlspecialchars($noticemailtpl['doc_create']['body'])?></textarea></label>
			</td>
		</tr>
				
		<tr>
			<td>邮件接收者：</td>
			<td>
				<?php foreach((array)$groups as $key=>$group) {?> <?php if($group['groupid']>1) { ?>
				<label><input type="checkbox" name="doc-create[]" value="<?php echo $group['groupid']?>" <?php if(in_array($group['groupid'], $doc_create)) { ?>checked="checked"<?php } ?> /> <?php echo $group['grouptitle']?></label> &nbsp;&nbsp;
				<?php } ?> <?php }?>
			</td>
		</tr>
		<tr><td colspan="2" style="border: none;"></td></tr>
		
		<tr><td width="160px"><span>词条编辑邮件提醒设置：</span></td><td></td></tr>
		<tr>
			<td>邮件主题模板：</td>
			<td><label><input type="text"  class="inp_txt" name="noticemailtpl[doc_edit][subject]" value="<?php echo htmlspecialchars($noticemailtpl['doc_edit']['subject'])?>" /></label>
			</td>
		</tr>
				
		<tr>
			<td>邮件正文模板：</td>
			<td>
				<label><textarea name="noticemailtpl[doc_edit][body]" class="textarea" rows="6" style="width: 400px;"><?php echo htmlspecialchars($noticemailtpl['doc_edit']['body'])?></textarea></label>
			</td>
		</tr>
				
		<tr>
			<td>邮件接收者：</td>
			<td>
				<label><input type="checkbox" name="doc-edit[]" value="CREATOR" <?php if(in_array('CREATOR', $doc_edit)) { ?>checked="checked"<?php } ?> /> 词条创建者</label> &nbsp;&nbsp;
				<label><input type="checkbox" name="doc-edit[]" value="EDITORS" <?php if(in_array('EDITORS', $doc_edit)) { ?>checked="checked"<?php } ?> /> 词条编辑者</label> <br />
				<?php foreach((array)$groups as $key=>$group) {?> <?php if($group['groupid']>1) { ?>
				<label><input type="checkbox" name="doc-edit[]" value="<?php echo $group['groupid']?>" <?php if(in_array($group['groupid'], $doc_edit)) { ?>checked="checked"<?php } ?> /> <?php echo $group['grouptitle']?></label> &nbsp;&nbsp;
				<?php } ?><?php }?>
				
			</td>
		</tr>
		<tr><td colspan="2" style="border: none;"></td></tr>
		
		<tr><td><span>词条评论邮件提醒设置:</span></td><td></td></tr>
		
		<tr>
			<td>邮件主题模板：</td>
			<td><label><input type="text"  class="inp_txt" name="noticemailtpl[comment_add][subject]" value="<?php echo htmlspecialchars($noticemailtpl['comment_add']['subject'])?>" /></label>
			</td>
		</tr>
				
		<tr>
			<td>邮件正文模板：</td>
			<td>
				<label><textarea name="noticemailtpl[comment_add][body]" class="textarea" rows="6" style="width: 400px;"><?php echo htmlspecialchars($noticemailtpl['comment_add']['body'])?></textarea></label>
			</td>
		</tr>		
		
		<tr>
			<td>邮件接收者：</td>
			<td>
				<label><input type="checkbox" name="comment_add[]" value="CREATOR" <?php if(in_array('CREATOR', $comment_add)) { ?>checked="checked"<?php } ?> /> 词条创建者</label> &nbsp;&nbsp;
				<label><input type="checkbox" name="comment_add[]" value="EDITORS" <?php if(in_array('EDITORS', $comment_add)) { ?>checked="checked"<?php } ?> /> 词条编辑者</label> <br />
				<label><input type="checkbox" name="comment_add[]" value="REVIEWERS" <?php if(in_array('REVIEWERS', $comment_add)) { ?>checked="checked"<?php } ?> /> 当前词条评论者</label> <br />
				<?php foreach((array)$groups as $key=>$group) {?> <?php if($group['groupid']>1) { ?>
				<label><input type="checkbox" name="comment_add[]" value="<?php echo $group['groupid']?>" <?php if(in_array($group['groupid'], $comment_add)) { ?>checked="checked"<?php } ?> /> <?php echo $group['grouptitle']?></label> &nbsp;&nbsp;
				<?php } ?><?php }?>
				
			</td>
		</tr>
		

		
		<tr>
			<td colspan="2"><input class="inp_btn" type="submit" value="保 存" name="submit" /></td>
		</tr>
	</table>
</form>



<?php include $this->gettpl('admin_footer');?>