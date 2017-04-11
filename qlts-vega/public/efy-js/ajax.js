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

/////////////////////////////////////////////////////////////////////
function postAJAXHTTPText(url, key, objView, objLoading){
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
                //alert("Trạng thái: " + AJAXhttp.statusText);
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
