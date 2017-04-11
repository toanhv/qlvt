 var numericExpr=new RegExp("^[0-9]+( [0-9]+( [0-9]+( [0-9]+)?)?)?([.][0-9]+)?$")
 var numericNegExpr=new RegExp("^(-)?[0-9]+( [0-9]+( [0-9]+( [0-9]+)?)?)?([.][0-9]+)?$")
 function DoCal(elTarget) {
  if (window.showModalDialog) {
    var sRtn;
    sRtn = showModalDialog("../MasterPage/calenderPicker.html","_calPick","center=yes;dialogWidth=200pt;dialogHeight=180pt");
    if (sRtn != "")
      elTarget.value = sRtn;
  //else
      //alert("Please disable popop blocker for this application.")
  } else
        alert("Please disable popop blocker for this application\nInternet Explorer 4.i or later required.")
 }

 function DoCalTime(elTarget) {
  if (window.showModalDialog) {
    var sRtn;
    sRtn = showModalDialog("../MasterPage/calenderPicker.html","_calPick","center=yes;dialogWidth=200pt;dialogHeight=180pt");
    if (sRtn != "") {
      var cday = new Date();
      elTarget.value = sRtn + ' ' + pad0(cday.getHours()) + ':' + pad0(cday.getMinutes());
   }
  //else
      //alert("Please disable popop blocker for this application.")
  } else
        alert("Please disable popop blocker for this application\nInternet Explorer 4.i or later required.")
 }

function pad0(d) {
	if (d<10)
		return '0' + d;
	else
		return d;
	
}

function insertSepr(d) {
var i=0;
var d2='';
var ic=0;
var ofs=d.length-1;
var decimalpoint = d.indexOf('.');
if (decimalpoint>=0) ofs = decimalpoint-1;
for (i=ofs;i>=0;i--) {
	if (d.charAt(i)!=' ') {
		if (ic++ % 3 ==0 && i!=ofs && d.charAt(i)!='-') d2=' ' + d2;
		d2=d.charAt(i) + d2;
	}
}

if (decimalpoint>=0) {
	for (i=decimalpoint;i<d.length;i++)
		d2=d2+d.charAt(i);
}
return d2;
}

function removeSepr(d) {
	var r='';
	for (var i=0;i<d.length;i++)
		if (d.charAt(i)!=' ')
			 r = r + d.charAt(i);
	return r;
}

 function DoTime(elTarget) {
	sRtn = showModalDialog("../MasterPage/clockPicker.html","_calPick","status=no;center=yes;dialogWidth=126pt;dialogHeight=304pt");
	if (sRtn != "")
      	elTarget.value = sRtn;
 }

function DatePrompt(v) {
var v1 = v.value;
var o1 = '';
var monthdays=0;
var r1=true;

var vietDateExpr = new RegExp('^[0-3]?[0-9]/[0-1]?[0-9]/[0-9][0-9][0-9][0-9]$');
var dateexpr1 = new RegExp('^[0-3][0-9][0-1][0-9]$');
var dateexpr2 = new RegExp('^[0-3][0-9][0-1][0-9][0-9][0-9]$');
var dateexpr3 = new RegExp('^[0-3]?[0-9]/[0-1]?[0-9]/[0-9][0-9]$');
var dateexpr4 = new RegExp('^[0-3]?[0-9]/[0-1]?[0-9]$');

if (v1.match(vietDateExpr))
	o1=v1;
else {
	if (v1.match(dateexpr1)) {
		var d = new Date();
		o1= v1.substring(0,2) + '/' + v1.substring(2, 4) + '/' + d.getFullYear();
	}
	else {
		if (v1.match(dateexpr2))
			o1= v1.substring(0,2) + '/' + v1.substring(2, 4) + '/20' + v1.substring(4,6);
		else {
			if (v1.match(dateexpr3)) {
				var i1= v1.lastIndexOf('/');
				o1 = v1.substring(0, i1) + '/20' + v1.substring(i1+1, v1.length);
			}
			else {
				if (v1.match(dateexpr4)) {
					var d = new Date();
					o1= v1 + '/' + d.getFullYear();
				}
			}
		}
	}
}
if (o1=='' && v1!='') {
	alert('Ngay thang sai!');
	r1 = false;
}

// Now check date validity
strDate1 = o1.split("/");
if(strDate1[0].substring(0,1)=='0'){
	strDate1[0] = strDate1[0].substring(1,1);
}

if(strDate1[1].substring(0,1)=='0'){
	strDate1[1] = strDate1[1].substring(1,1);
}

if (parseInt(strDate1[1])<1 || parseInt(strDate1[1])>12) {
	alert('Thang: 1-12');
	r1 = false;
}

switch(parseInt(strDate1[1])) {
case 2: 
	if (parseInt(strDate1[2]) % 4 == 0) 
		monthdays=29; 
	else 
		monthdays=28;
	break;
case 4:
case 6:
case 9:
case 11:
	monthdays=30;
	break;
default:
	monthdays=31;
}

if (parseInt(strDate1[0])<1 || parseInt(strDate1[0])>monthdays) {
	alert('Ngay: 1-' + monthdays);
	r1 = false;
}

if (r1)
	return o1;
else
	return v1;

}

function ExportExcel(obj) { 
//Open IE -> Tools ->Internet Options -> Security -> Custom Level -> ActiveX controls and plug-ins ->Enable "Initialize and script ActiveX controls not marked as safe for scripting"
    window.clipboardData.setData("Text",document.getElementById(obj).outerHTML); 
    try{ 
        var ExApp = new ActiveXObject("Excel.Application");
        var ExWBk = ExApp.workbooks.add();
        ExWBk.worksheets("Sheet1").activate;

        var ExWSh = ExWBk.activeSheet;

        ExApp.DisplayAlerts = false;
        ExApp.visible = true;
    }catch(e){ 
        alert("Máy tính không cài Excel!!!\n" + e.description);
        return false;
    } 
    ExWBk.worksheets(1).Paste;;
    ExWSh.columns.autofit;
} 



function getAJAXHTTPText(url, objView, objLoading) {
    var AJAXhttp;
    if (window.ActiveXObject){
            AJAXhttp = new ActiveXObject("Microsoft.XMLHTTP");}
    else if (window.XMLHttpRequest){
            AJAXhttp = new XMLHttpRequest();}

    if (!AJAXhttp){
            alert("Không thể khởi tạo được AJAX object!!!");
            return;
        }

    try{
        if (objLoading != null){
            objLoading.style.display = "block";
        }
        AJAXhttp.onreadystatechange = function(){
	        if (AJAXhttp.readyState == 4) { // Complete
                if (AJAXhttp.status == 200) { // OK response
                    objView.innerHTML = AJAXhttp.responseText;
                    if (objLoading != null){
                        objLoading.style.display = "none";
                    }
                } else {
                    if (objLoading != null){
                        objLoading.style.display = "block";
                    }
                    alert("Trạng thái: " + AJAXhttp.statusText);
                }
            }
        };
        AJAXhttp.open("GET", url);
        AJAXhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        AJAXhttp.send();

    }
    catch(e){
        if (objLoading != null){
            objLoading.style.display = "none";
        }
        alert("Lỗi: " + e.description + "\n" + url);
    }
    
}

function postAJAXHTTPText(url, key, objView, objLoading)
{
    var AJAXhttp;
    if (window.ActiveXObject)
    {
            AJAXhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else if (window.XMLHttpRequest)
    {
            AJAXhttp = new XMLHttpRequest();
    }

    if (!AJAXhttp)
    {
            alert("Không thể khởi tạo được AJAX object!!!");
            return;
    }
    try
    {
        if (objLoading != null)
        {
            objLoading.style.display = "block";
        }
        AJAXhttp.onreadystatechange = function(){
	        if (AJAXhttp.readyState == 4) 
	        { // Complete
                if (AJAXhttp.status == 200) 
                { // OK response
                    objView.innerHTML = AJAXhttp.responseText;
                    if (objLoading != null)
                    {
                        objLoading.style.display = "none";
                    }
                }
                 else 
                 {
                    if (objLoading != null)
                    {
                        objLoading.style.display = "block";
                    }
                alert("Trạng thái: " + AJAXhttp.statusText);
                }
            }
        };
        AJAXhttp.open("POST", url);
        AJAXhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        AJAXhttp.send(key);

    }
    catch(e){
        if (objLoading != null){
            objLoading.style.display = "none";
        }
        alert("Lỗi: " + e.description + "\n" + url);
    }    
}

function jsAlert(txt){
    alert(txt);
}