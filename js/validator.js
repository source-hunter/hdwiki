/**
 * ��֤��
 * @param {string} formid ��id
 * @param {bool} isalert �Ƿ�Ҫ��alert��ʽ��ʾ����
 * @param {string} errorcss ������Ϣ��ʽ��
 */
var Validator = function(formid, isalert, errorcss){
    var base = this;
    var form = document.getElementById(formid);
    var validArr = new Array();
    var validObjTag = "v_";
    var issubmit = false; /*�Ƿ�ͨ��*/
    /*
    //����֤�ؼ���Class����
    if (!isalert) {
        var spans = document.getElementsByTagName("span");
        for (var i = 0; i < spans.length; i++) {
            if (spans[i].id.indexOf(validObjTag) != -1) {
                if (errorcss == null || errorcss == '') 
                    spans[i].style.color = 'red';
                else 
                    spans[i].className = errorcss;
            }
        }
    }
    */
	
    /**
     * ���ύ�¼�
     */
    form.onsubmit = function(){
        return base.valid();
    }
    
    /**
     * ��ʾ��֤��Ϣ��Ӧ������ID
     * @param {Object} id
     */
    function validObjID(id){
        return validObjTag + id;
    }
    
    /**
     * ����֤�¼�
     * @param {string} id ��֤�ؼ�ID
     * @param {Array} ��֤��������
     */
    this.bind = function(id, eventArr){
        validArr.push(new Array(id, eventArr));
        
        if (!isalert) {
            if (!document.getElementById(validObjID(id))) {
				alert("��֤�ؼ���id=" + validObjID(id) + "�������ڣ�");
				return;
			}
            
            if (window.document.all) {
                document.getElementById(id).attachEvent("onblur", function(){
                    var result = true;
                    for (var i = 0; i < eventArr.length; i++) {
                        result = base.doValid(id, eventArr[i]);
                        if (!result) 
                            return;
                    }
                });
            }
            else {
                document.getElementById(id).addEventListener("blur", function(){
                    var result = true;
                    for (var i = 0; i < eventArr.length; i++) {
                        result = base.doValid(id, eventArr[i]);
                        if (!result) 
                            return;
                    }
                }, false);
            }
        }//end if(!isalert)
    }
    
    /**
     * �������ж������֤�¼�
     */
    this.valid = function(){
        this.issubmit = true;
        var focusid = null;
        for (var i = 0; i < validArr.length; i++) {
            if (isalert && !this.issubmit) 
                break;
            for (var j = 0; j < validArr[i][1].length; j++) {
                if (!base.doValid(validArr[i][0], validArr[i][1][j])) {
                    this.issubmit = false;
                    if (focusid == null) 
                        focusid = validArr[i][0];
                    break;
                }
            }
        }
        if (focusid != null){
			try{document.getElementById(focusid).focus();} catch(e){}
		}
        return this.issubmit;
    }
    
    /**
     * һ���������֤�¼�
     * @param {string} id
     * @param {Array} ��֤��������
     */
    this.doValid = function(id, arr){
        var val = document.getElementById(id).value;
        var result = true;
        switch (arr.length) {
            case 2:
                var type = arr[0];
                var msg = arr[1];
                
                switch (type) {
                    case "empty":
                        result = writeMsg(id, msg, (trim(val) == ''));
                        break;
                    case "number":
                        /* result = writeMsg(id, msg, (isNaN(val))); �Ƿ�������*/
                        var patrn = /^[0-9]+$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "double":
                        var patrn = /^[0-9.]+$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "date":
                        var patrn = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/; /*YYYY-MM-DD*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "time":
                        var patrn = /^((20|21|22|23|[0-1]\d)\:[0-5][0-9])(\:[0-5][0-9])?$/; /*hh:mm:ss*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "datetime":
                        var patrn = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/; /*YYYY-MM-DD hh:mm:ss*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "url":
                        var patrn = /^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/; /*��ַ*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "email":
                        var patrn = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; /*�ʼ�*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "identity":
                        var patrn = /^\d{17}[\d|X]|\d{15}$/; /*���֤��*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "ip":
                        var patrn = /^(((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))\.){3}((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "zip":
                        var patrn = /^\d{6}$/; /*�ʱ�*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "qq":
                        var patrn = /^[1-9][0-9]{4,}$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "phone":
                        var patrn = /^\d{3}-\d{8}|\d{4}-\d{7,8}|\d{11}|\d{12}$/; /*�绰*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "mobile":
                        var patrn = /^(13|15|18)\d{9}$/; /*�ֻ�*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "string":
                        var patrn = /^[a-zA-Z0-9_]+$/; /*a-z,A-Z,0-9 */
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "image":
                        var patrn = /(.jpg|.gif|.bmp|.png|.img|.swf)$/i; /*ͼƬ��չ��*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "html":
                        var patrn = /(.htm|.html|.shtml)$/;
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "chinese":
                        var patrn = /^[\u0391-\uFFE5]+$/; /*����*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                    case "userorpwd":
                        var patrn = /^[A-Za-z0-9]{6,20}$/; /*6-20λ;ֻ������(0-9)��Ӣ��(a-z),�����ִ�Сд*/
                        result = writeMsg(id, msg, (regular(val, patrn)));
                        break;
                }
                break;
            case 3:
                var type = arr[0];
                var element = arr[1];
                var msg = arr[2];
                
                switch (type) {
                    case "compare_eq": /*���*/
                        result = writeMsg(id, msg, !(val == document.getElementById(element).value));
                        break;
                    case "compare_neq": /*�����*/
                        result = writeMsg(id, msg, !(val != document.getElementById(element).value));
                        break;
                    case "compare_gt": /*����*/
                        result = writeMsg(id, msg, !(val > document.getElementById(element).value));
                        break;
                    case "compare_gte": /*���ڵ���*/
                        result = writeMsg(id, msg, !(val >= document.getElementById(element).value));
                        break;
                    case "compare_lt": /*С��*/
                        result = writeMsg(id, msg, !(val < document.getElementById(element).value));
                        break;
                    case "compare_lte": /*С�ڵ���*/
                        result = writeMsg(id, msg, !(val <= document.getElementById(element).value));
                        break;
                    case "regular": /*����*/
                        result = writeMsg(id, msg, (regular(val, element)));
                        break;
                    case "custom": /*�Զ���*/
                        result = writeMsg(id, msg, !(eval(element)));
                        break;
                }
                break;
        }
        return result;
    }
	
	/**
	 *ȥ���ո�
	 */
	function trim(str){  
		return str.replace(/(^\s*)|(\s*$)/g, "");   
	}
    /**
     * ����ƥ��
     * @param {string} val Ҫƥ����ַ�
     * @param {RegExp} patrn �������
     */
    function regular(val, patrn){
        var result = false;
        if (val != '') 
            result = !patrn.test(val);
        return result;
    }
    
    /**
     * д��������Ϣ
     * @param {string} id ��ʾ������Ϣ����ID
     * @param {string} msg ������Ϣ
     * @param {bool} result ��֤��ƥ����
     * @return {bool} �Ƿ�ͨ��֤
     */
    function writeMsg(id, msg, result){
        if (!isalert) {
            if (result) {
				var obj=document.getElementById(validObjID(id));
                obj.innerHTML = msg;
                if (errorcss == null || errorcss == '') 
                    obj.style.color = 'red';
                else 
                    obj.className = errorcss;
                return false;
            }
            else {
                document.getElementById(validObjID(id)).innerHTML = "";
                return true;
            }
        }
        else {
            if (result) {
                alert(msg);
                return false;
            }
            else {
                return true;
            }
        }
    }
}
