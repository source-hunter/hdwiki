	//������ʾ
	var partakehover = null;
	$("#share_link").hover(function(){
		clearTimeout(partakehover);
		$("#share_btn").show();
	}, function(){
		clearTimeout(partakehover);
		partakehover = setTimeout(function(){
			$("#share_btn").fadeOut("normal");
		}, 500);
	});
	$("#share_btn").hover(function(){
		clearTimeout(partakehover);
		$("#share_btn").show();
	}, function(){
		partakehover = setTimeout(function(){
			$("#share_btn").fadeOut("normal");
		}, 500);
	});
	//���ת����Kaixin001
		$url = "http://www.kaixin001.com/repaste/share.php?rtitle="+encodeURI($.trim($("#doctitle").html()))+"&rurl="+encodeURI(document.location.href)+"&rcontent="+encodeURI($("meta[name='description']").attr("content"));
		$('.kaixin001').attr('href',$url).attr('target','_blank');

	//������˷���
	$(".renren").bind("click", function(){
		$("body").append("<div id=\"renren_repaste_div\" style=\"display:none;\"><form name=\"renren_repaste\" id=\"renren_repaste\" action=\"http://share.renren.com/share/buttonshare.do\" method=\"get\" target=\"_blank\"><input type=\"hidden\" name=\"link\" value=\"" + location.href + "\"></form></div>");
		$("#renren_repaste").submit();
		$("#renren_repaste").remove();	
		return false;
	});
	
	//����΢��
	/*
	����΢�������Ĭ����ʾ���ԡ����������ݷ����������޸����� *** ��Ϣ��
	���޸�������뵱�� <input type="hidden" name="appkey" value=""> �� value ֵ���ɡ�
	
	����1������΢�� �����ߡ� -> ������ť����ȡappkey���������ʾ���ԡ�����ť����
	����2��http://open.t.sina.com.cn/ ����appkey����������֤������֤֮ǰ��ʾ���ԡ�΢������ƽ̨�ӿڡ���
	����3������appkey��valueֵΪ�գ���ô����ʾ���ԡ����������ݷ���
	*/
	$(".sina_blog").bind("click", function(){
		var sinaminblogurl = location.href;
		var maxlength = 140;
		var ablelength = maxlength - sinaminblogurl.length / 2 - $("#doctitle").html().length - 7;
		var summary = $.trim($("meta[name='description']").attr("content"));
		if (ablelength > 50) {
			ablelength = 50;
		}
		if (summary.length > ablelength) {
			summary = summary.substr(0, ablelength - 2) + "����";
		}
		var content = $("#doctitle").val() + "  " + summary;
		var html = '<form name="sinaminblogform" id="sinaminblogform" action="http://v.t.sina.com.cn/share/share.php" method="get" target="_blank">' +'<input type="hidden" name="title" value="' +content +'"><input type="hidden" name="url" value="' +sinaminblogurl +'"><input type="hidden" name="content" value="utf-8"><input type="hidden" name="appkey" value=""></form>';
		$("body").append(html);
		$("#sinaminblogform").submit();
		$("#sinaminblogform").remove();
		return false;
	});