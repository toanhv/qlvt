//Ham btn_sent_onclick duoc goi khi NSD bam vao nut 'gui'
function btn_approve_onclick(p_checkbox_obj, p_url){
	record_id_list = checkbox_value_to_list(p_checkbox_obj,",");
	if (!record_id_list){
		alert("Chưa có đối tượng nào được chọn");
	}else{
		document.getElementById('hdn_object_id_list').value = record_id_list;
		actionUrl(p_url);	
	}
}
