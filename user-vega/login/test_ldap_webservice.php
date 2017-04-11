<?php
//Ket noi dich vu WebService cua NuSoap
include('../isa-lib/isa-function/isa_public_function.php');
require_once('../isa-lib/nusoap/nusoap.php');
include "../db_const.php";
include "../ldap_functions.php";
include "../connect_ldap.php";
$ldapbind = ldap_bind($ldap_conn, $_ISA_LDAP_ISAUSER_SEARCH_DN, $_ISA_LDAP_SEARCH_PASSWORD);

echo get_all_ldap_user();
exit;
?>