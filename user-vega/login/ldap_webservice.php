<?php
//Ket noi dich vu WebService cua NuSoap
include('../isa-lib/isa-function/isa_public_function.php');
require_once('../isa-lib/nusoap/nusoap.php');
include "../db_const.php";
include "../ldap_functions.php";
include "../connect_ldap.php";
$ldapbind = ldap_bind($ldap_conn, $_ISA_LDAP_ISAUSER_SEARCH_DN, $_ISA_LDAP_SEARCH_PASSWORD);

//echo get_all_ldap_user();
//exit;
//Lay thong tin ca nhan cua tat ca can bo (staff)
function get_all_ldap_user(){
	global $conn;
	global $_ISA_LDAP_USERNAME_ATTRIBUTE;
	$result = LDAP_GetAllUser("");
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<staff_list>" . "\n";
	for ($i=0; $i < $result['count']; $i++) {
		$rs = $result[$i];
		$v_name = $rs[$_ISA_LDAP_USERNAME_ATTRIBUTE][0];
		if ($v_name==""){
			$v_name=_LDAP_GetCN($rs['dn']);
		}
		if ($rs[$_ISA_LDAP_USERNAME_ATTRIBUTE][0]!=""){
			$strXML.= "<staff>" . "\n";
			$strXML.= "<id>" . htmlspecialchars($rs['dn']) . "</id>" . "\n";
			$strXML.= "<uid>" . htmlspecialchars($rs[$_ISA_LDAP_USERNAME_ATTRIBUTE][0]) . "</uid>" . "\n";
			$strXML.= "<name>" . htmlspecialchars($v_name) . "</name>" . "\n";
			$strXML.= "<cn>".htmlspecialchars($rs[$_ISA_LDAP_USERNAME_ATTRIBUTE][0])."</cn>";
			$strXML.= "<cn_list>";
			$temp_list = "";
			for ($j=0;$j<$rs['cn']['count'];$j++){
				 $temp_list = _list_add($temp_list,$rs['cn'][$j],",");
			}
			$strXML.= htmlspecialchars($temp_list);
			$strXML.= "</cn_list>". "\n";
			$strXML.= "</staff>" . "\n";
		}
	}	
	$strXML.="</staff_list>" . "\n";
	return  $strXML;  
}

$s = new soap_server;
$s->register('get_all_ldap_user');
$s->service($HTTP_RAW_POST_DATA);
?>