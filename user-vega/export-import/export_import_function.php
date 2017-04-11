<?php
function user_export_application($p_application_id){
    global $ado_conn;
    if (_is_sqlserver()) {
        
        $sql = "Exec USER_BackupModulGetAllByApp ".$p_application_id;
    	$ado_conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$arr_all_modul = $ado_conn->GetAll($sql);
    	//var_dump($arr_all_modul);       
		$sql = "Exec USER_BackupFunctionGetAllByApp ".$p_application_id;
    	$ado_conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$arr_all_function = $ado_conn->GetAll($sql);	
    }  
    $XML_string  =  '<?xml version="1.0" encoding="UTF-8"?>';
	$XML_string .= '<root>';
	$XML_string .= '<table>';
	
	for ($i=0;$i<sizeof($arr_all_modul);$i++){
	  $XML_string .= '<T_USER_MODUL>';
	  $XML_string .= '<PK_MODUL>'.$arr_all_modul[$i]['PK_MODUL'].'</PK_MODUL>'; 
	  $XML_string .= '<FK_APPLICATION>'.$arr_all_modul[$i]['FK_APPLICATION'].'</FK_APPLICATION>';
	  $XML_string .= '<C_CODE>'.htmlspecialchars($arr_all_modul[$i]['C_CODE']).'</C_CODE>';
	  $XML_string .= '<C_NAME>'.htmlspecialchars($arr_all_modul[$i]['C_NAME']).'</C_NAME>';
	  $XML_string .= '<C_ORDER>'.$arr_all_modul[$i]['C_ORDER'].'</C_ORDER>';
	  $XML_string .= '<C_STATUS>'.$arr_all_modul[$i]['C_STATUS'].'</C_STATUS>';    
	  $XML_string .= '<C_PUBLIC>'.$arr_all_modul[$i]['C_PUBLIC'].'</C_PUBLIC>'; 
	  $XML_string .= '</T_USER_MODUL>';
	}

	for ($i=0;$i<sizeof($arr_all_function);$i++){
	  $XML_string .= '<T_USER_FUNCTION>';
	  $XML_string .= '<PK_FUNCTION>'.$arr_all_function[$i]['PK_FUNCTION'].'</PK_FUNCTION>'; 
	  $XML_string .= '<FK_APPLICATION>'.$arr_all_function[$i]['FK_APPLICATION'].'</FK_APPLICATION>';
	  $XML_string .= '<FK_MODUL>'.$arr_all_function[$i]['FK_MODUL'].'</FK_MODUL>';
	  $XML_string .= '<C_CODE>'.htmlspecialchars($arr_all_function[$i]['C_CODE']).'</C_CODE>';
	  $XML_string .= '<C_NAME>'.htmlspecialchars($arr_all_function[$i]['C_NAME']).'</C_NAME>';
	  $XML_string .= '<C_ORDER>'.$arr_all_function[$i]['C_ORDER'].'</C_ORDER>';
	  $XML_string .= '<C_STATUS>'.$arr_all_function[$i]['C_STATUS'].'</C_STATUS>';    
	  $XML_string .= '<C_PUBLIC>'.$arr_all_function[$i]['C_PUBLIC'].'</C_PUBLIC>'; 
	  $XML_string .= '</T_USER_FUNCTION>';
	}
	
	$XML_string .= '</table>';
	$XML_string .= '</root>';
	return $XML_string;
}
function user_import_application($v_str_xml_in_file,$p_application_id){
    global $ado_conn;
	$i = 0;
	$rax_table = new RAX(); 
	$rec_table = new RAX(); 
	$rax_table->open($v_str_xml_in_file);
	$rax_table->record_delim = 'T_USER_FUNCTION';
	$rax_table->parse();
	$rec_table = $rax_table->readRecord(); 
	while ($rec_table) { 
		$row_table = $rec_table->getRow();
		$arr_all_function[$i] = $row_table;
		$arr_all_function[$i]['FK_APPLICATION'] = $p_application_id;
		$i++;
		$rec_table = $rax_table->readRecord();
	}

	$i = 0;
	$rax_table = new RAX(); 
	$rec_table = new RAX(); 
	$rax_table->open($v_str_xml_in_file);
	$rax_table->record_delim = 'T_USER_MODUL';
	$rax_table->parse();
	$rec_table = $rax_table->readRecord(); 
	while ($rec_table) { 
		$row_table = $rec_table->getRow();
		$arr_all_modul[$i] = $row_table;
		$arr_all_modul[$i]['FK_APPLICATION'] = $p_application_id;
		
		if (_is_sqlserver()) {
            $sql = "Exec USER_BackupModulUpdate ";
            $sql.= $p_application_id;     
            $sql.= ",'".$arr_all_modul[$i]['C_CODE']."'";
            $sql.= ",'".$arr_all_modul[$i]['C_NAME']."'";
            $sql.= ",".intval($arr_all_modul[$i]['C_ORDER']);
            $sql.= ",".intval($arr_all_modul[$i]['C_STATUS']);
            $sql.= ",".intval($arr_all_modul[$i]['C_PUBLIC']);
        	$ado_conn->SetFetchMode(ADODB_FETCH_ASSOC);
    		$rs = $ado_conn->GetRow($sql);	
    		$v_new_id = $rs['NEW_ID'];
        }
        //Cap nhat lai ID cua modul moi
        for ($j=0;$j<sizeof($arr_all_function);$j++){
            if ($arr_all_function[$j]['FK_MODUL'] == $arr_all_modul[$i]['PK_MODUL']){
               $arr_all_function[$j]['FK_MODUL'] = $v_new_id;
            }
        }
		$i++;
		$rec_table = $rax_table->readRecord();
	}

	for ($i=0;$i<sizeof($arr_all_function);$i++){
	    if (_is_sqlserver()) {
            $sql = "Exec USER_BackupFunctionUpdate ";
            $sql.= $p_application_id;     
            $sql.= ",".intval($arr_all_function[$i]['FK_MODUL']);   
            $sql.= ",'".$arr_all_function[$i]['C_CODE']."'";
            $sql.= ",'".$arr_all_function[$i]['C_NAME']."'";
            $sql.= ",".intval($arr_all_function[$i]['C_ORDER']);
            $sql.= ",".intval($arr_all_function[$i]['C_STATUS']);
            $sql.= ",".intval($arr_all_function[$i]['C_PUBLIC']);
        	$ado_conn->SetFetchMode(ADODB_FETCH_ASSOC);
    		$rs = $ado_conn->GetRow($sql);	
    		$v_new_id = $rs['NEW_ID'];
        }
	}
}
?>