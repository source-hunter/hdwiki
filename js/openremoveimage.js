/*��
����ͼƬ����ҳ���תΪ�ڵ�����DIV�����д�
*/
function openremoveimage(E){
	var url = E.href, title, img=$(E).find('img');
	title = img.attr('title');
	if(!title||title == 'null') title = img.attr('alt') || Lang.BigImage;
	if (url.match(/(\w+_){4}/i) && url.match(/(\w+)\.html/i)){
		//�ٿ��������ص����ݰ��������ٿƵ�ͼƬ��ַ������ַ����ת��
		url = url.match(/(\w+)\.html/i);
		var a = url[1].split("_");
		url = "http://"+a[0]+".att.hudong.com/"+a[1]+"/"+a[2]+"/"+a[3]+"."+a[4];
	}else if(url.match(/\.(jpg|gif|png)$/i) == null){
		//������Ӳ���һ��ͼƬ��ַ
		return true;
	}
	
	//ʹ��DIV�����д�
	$.dialog.box("image", title, 'img:'+url, E);
	return false;
}

$(document).ready(function(){
	var a, imgs = document.images, url;
	//��������ͼƬ
	for(i=0; i<imgs.length; i++){
		url = $(imgs[i]).attr('src');//�õ�ͼƬ�� src ����
		if(!url) continue;
		a = $(imgs[i]).parent("a");
		
		//�����http��ͷ���Ұ������� uploads/201002/****.jpg ��������������Ҫ�����޸����ӵ�href��ַ
		if (url.indexOf('http:') == 0 && /uploads\/(?:\d{6}|hdpic)\/\w+\.(?:jpg|gif|png)$/i.test(url)){
			url = 'uploads'+url.split('uploads')[1];
			
			//�������Ӧ����ͼƬʹ������·�����棬���Ǹ���������
			//ͼƬӦ���޷���ʾ����ͼƬ��ַ�����޸�
			if (imgs[i].src != url) imgs[i].src = url;
			
			//���ͼƬ������
			if (a.size() == 1){
				url = a.attr('href');
				if (url.indexOf('http:') == 0 && url.indexOf('uploads') > 0){
					url = 'uploads'+url.split('uploads')[1];
					a.attr('href', url);
				}
			}
		}
		
		if (a.size() == 1){
			a.click(function(){
				return openremoveimage(this);
			});
		}
	}
});