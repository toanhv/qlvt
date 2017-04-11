function btn_continous_onclick(rad_ojb,file_name_export,p_fuseation){
    if (verify(document.forms(0))){
        if (rad_ojb[0].checked){
            document.forms(0).action = file_name_export;
        }else{  
            document.forms(0).action = "index.php";
            document.forms(0).fuseaction.value = p_fuseation;
            if (document.forms(0).file_attach.value==""){
                alert("Hay xac dinh duong dan den file dinh kem");
                return;
            }
        }
        document.forms(0).submit();
    }
}
function rad_onclick(rad_ojb){
    if (rad_ojb[0].checked){
        document.all.tr_file_attach.style.display = "none";
    }else{  
        document.all.tr_file_attach.style.display = "block";
    }
}