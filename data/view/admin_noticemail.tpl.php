<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('admin_header');?>
<p class="map">ȫ�֣���չ����</p>
<p class="sec_nav">��չ���ã�
    <a href="index.php?admin_setting-sec" > <span>����ˮ����</span></a>
    <a href="index.php?admin_setting-anticopy"><span>���ɼ�����</span></a>
    <a href="index.php?admin_setting-mail"><span>�ʼ�����</span></a>
    <a href="index.php?admin_setting-noticemail" class="on"><span>�ʼ���������</span></a>
    <a href="index.php?admin_banned" ><span>IP��ֹ</span></a>
    <a href="index.php?admin_setting-passport" ><span>ͨ��֤����</span></a>
    <a href="index.php?admin_setting-ucenter"><span>UCenter����</span></a>
    <a href="index.php?admin_setting-ldap"><span>LDAP����</span></a>
</p>
<h3 class="col-h3">�ʼ���������</h3>
<ul class="col-ul tips">
	<li class="bold">��ʾ:</li>
	<li>ѡ�е��û�����ڷ������������¼�ʱ�յ�Email֪ͨ��</li>
	<li>��������ʼ����ѵ��û����࣬���ϵͳ��ɽϴ󸺵�����ֻѡ���б�Ҫ�������ѵ��û����û��顣</li>
	<li>����ģ�洴��������<a href="http://kaiyuan.hudong.com/bbs" target="_blank">�������</a></li>
</ul>
<form method="POST" action="index.php?admin_setting-noticemail" >
  <table class="table">
		<tr><td width="160px"><span>���������ʼ��������ã�</span></td><td></td></tr>
		<tr>
			<td>�ʼ�����ģ�壺</td>
			<td><label><input type="text"  class="inp_txt" name="noticemailtpl[doc_create][subject]" value="<?php echo htmlspecialchars($noticemailtpl['doc_create']['subject'])?>" /></label>
			</td>
		</tr>
				
		<tr>
			<td>�ʼ�����ģ�壺</td>
			<td>
				<label><textarea name="noticemailtpl[doc_create][body]" class="textarea" rows="6" style="width: 400px;"><?php echo htmlspecialchars($noticemailtpl['doc_create']['body'])?></textarea></label>
			</td>
		</tr>
				
		<tr>
			<td>�ʼ������ߣ�</td>
			<td>
				<?php foreach((array)$groups as $key=>$group) {?> <?php if($group['groupid']>1) { ?>
				<label><input type="checkbox" name="doc-create[]" value="<?php echo $group['groupid']?>" <?php if(in_array($group['groupid'], $doc_create)) { ?>checked="checked"<?php } ?> /> <?php echo $group['grouptitle']?></label> &nbsp;&nbsp;
				<?php } ?> <?php }?>
			</td>
		</tr>
		<tr><td colspan="2" style="border: none;"></td></tr>
		
		<tr><td width="160px"><span>�����༭�ʼ��������ã�</span></td><td></td></tr>
		<tr>
			<td>�ʼ�����ģ�壺</td>
			<td><label><input type="text"  class="inp_txt" name="noticemailtpl[doc_edit][subject]" value="<?php echo htmlspecialchars($noticemailtpl['doc_edit']['subject'])?>" /></label>
			</td>
		</tr>
				
		<tr>
			<td>�ʼ�����ģ�壺</td>
			<td>
				<label><textarea name="noticemailtpl[doc_edit][body]" class="textarea" rows="6" style="width: 400px;"><?php echo htmlspecialchars($noticemailtpl['doc_edit']['body'])?></textarea></label>
			</td>
		</tr>
				
		<tr>
			<td>�ʼ������ߣ�</td>
			<td>
				<label><input type="checkbox" name="doc-edit[]" value="CREATOR" <?php if(in_array('CREATOR', $doc_edit)) { ?>checked="checked"<?php } ?> /> ����������</label> &nbsp;&nbsp;
				<label><input type="checkbox" name="doc-edit[]" value="EDITORS" <?php if(in_array('EDITORS', $doc_edit)) { ?>checked="checked"<?php } ?> /> �����༭��</label> <br />
				<?php foreach((array)$groups as $key=>$group) {?> <?php if($group['groupid']>1) { ?>
				<label><input type="checkbox" name="doc-edit[]" value="<?php echo $group['groupid']?>" <?php if(in_array($group['groupid'], $doc_edit)) { ?>checked="checked"<?php } ?> /> <?php echo $group['grouptitle']?></label> &nbsp;&nbsp;
				<?php } ?><?php }?>
				
			</td>
		</tr>
		<tr><td colspan="2" style="border: none;"></td></tr>
		
		<tr><td><span>���������ʼ���������:</span></td><td></td></tr>
		
		<tr>
			<td>�ʼ�����ģ�壺</td>
			<td><label><input type="text"  class="inp_txt" name="noticemailtpl[comment_add][subject]" value="<?php echo htmlspecialchars($noticemailtpl['comment_add']['subject'])?>" /></label>
			</td>
		</tr>
				
		<tr>
			<td>�ʼ�����ģ�壺</td>
			<td>
				<label><textarea name="noticemailtpl[comment_add][body]" class="textarea" rows="6" style="width: 400px;"><?php echo htmlspecialchars($noticemailtpl['comment_add']['body'])?></textarea></label>
			</td>
		</tr>		
		
		<tr>
			<td>�ʼ������ߣ�</td>
			<td>
				<label><input type="checkbox" name="comment_add[]" value="CREATOR" <?php if(in_array('CREATOR', $comment_add)) { ?>checked="checked"<?php } ?> /> ����������</label> &nbsp;&nbsp;
				<label><input type="checkbox" name="comment_add[]" value="EDITORS" <?php if(in_array('EDITORS', $comment_add)) { ?>checked="checked"<?php } ?> /> �����༭��</label> <br />
				<label><input type="checkbox" name="comment_add[]" value="REVIEWERS" <?php if(in_array('REVIEWERS', $comment_add)) { ?>checked="checked"<?php } ?> /> ��ǰ����������</label> <br />
				<?php foreach((array)$groups as $key=>$group) {?> <?php if($group['groupid']>1) { ?>
				<label><input type="checkbox" name="comment_add[]" value="<?php echo $group['groupid']?>" <?php if(in_array($group['groupid'], $comment_add)) { ?>checked="checked"<?php } ?> /> <?php echo $group['grouptitle']?></label> &nbsp;&nbsp;
				<?php } ?><?php }?>
				
			</td>
		</tr>
		

		
		<tr>
			<td colspan="2"><input class="inp_btn" type="submit" value="�� ��" name="submit" /></td>
		</tr>
	</table>
</form>



<?php include $this->gettpl('admin_footer');?>