<?
// Connecting to LDAP
$ldap_conn = @ldap_connect($_ISA_LDAP_HOST, $_ISA_LDAP_PORT ) or die( "Loi ket noi voi LDAP Server: {$_ISA_LDAP_HOST}" );
@ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
$bind_result  = @ldap_bind($ldap_conn, $_ISA_LDAP_ISAUSER_UPDATE_DN, $_ISA_LDAP_ISAUSER_UPDATE_PASSWORD);
?>
