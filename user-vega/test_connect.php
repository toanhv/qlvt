<?
set_time_limit(6000);
$_ISA_LDAP_HOST = "ldap.isavn.com";			 // Dia chi ldap servers
$_ISA_LDAP_PORT = 389;
$ldap_conn = ldap_connect($_ISA_LDAP_HOST,$_ISA_LDAP_PORT) or die( "Loi ket noi voi LDAP Server: " );
ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
$bind_result  = ldap_bind($ldap_conn, "chuongnx", "chuongnxisa");

$base_dn = "o=isa,c=vn"; 

$filter="objectClass=dominoPerson";
$sr=ldap_search($ldap_conn,$base_dn,$filter);
$ldapResults = ldap_get_entries($ldap_conn, $sr);

echo "Tong cong co:".$ldapResults['count']."<br>";
echo "<table cellpadding='0' cellspacing='0' border='1'>";
echo "<col width='20%'><col width='80%'>";
for ($item = 0; $item < $ldapResults['count']; $item++)
{
  for ($attribute = 0; $attribute < $ldapResults[$item]['count']; $attribute++)
  {
   $data = $ldapResults[$item][$attribute];
   	for ($j=0;$j<$ldapResults[$item][$data]['count'];$j++){
   		echo "<tr><td>".$data."</td><td>".$ldapResults[$item][$data][$j]."</td></tr>";
	}
	
  }
  echo "<tr><td colspan='2' style='background:#FF0000'>&nbsp;</td></tr>";
}
echo "</table>";
?>
 