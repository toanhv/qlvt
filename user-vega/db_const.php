<?php
//Dat che do dang nhap 1 lan 1-cho phep dang nhap 1 lan, 0-khong cho phep dang nhap 1 lan
$_ISA_ALLOW_SSO = 1;

// Xac dinh loai CSDL
// $_ISA_DB_TYPE = "ORACLE";
// $_ISA_DB_TYPE = "POSTGRES";
$_ISA_DB_TYPE = "SQL SEVER";

// Xac dinh ten user. password dang nhap DB
$_ISA_DB_USER = "phongtd";
//$_ISA_DB_USER = "sa";
$_ISA_DB_PASSWORD = "tph_123312##";
//$_ISA_DB_PASSWORD = "Vega123312";

// Xac dinh ten CSDL 
$_ISA_DB_NAME = "[user]";

// Xac dinh ten server chua CSDL 
//$_ISA_SERVER_NAME = "PHONGTD\SQLEXPRESS";
$_ISA_SERVER_NAME = "localhost";
//////////////////////////////// CSDL LDAP ///////////////////////////////
//Ket noi toi loai server (open ldap hay active directory)
//$_ISA_HOST_TYPE = "LDAP";
//$_ISA_HOST_TYPE = "AD";
$_ISA_HOST_TYPE = "DOMINO";

$_ISA_LDAP_HOST = "ldap.isavn.com";			 // Dia chi ldap servers
$_ISA_LDAP_PORT = 389;

//$_ISA_LDAP_DOMAIN = "isa.com.vn";
$_ISA_LDAP_DOMAIN = "";

$_ISA_LDAP_ROOT_DN = "o=ISA,c=VN";
//$_ISA_LDAP_ROOT_DN = "dc=haiphong,dc=gov,dc=vn";

// DN chua danh sach NSD
//$_ISA_LDAP_USER_DN = "ou=People,o=Thanh pho Hai Phong,".$_ISA_LDAP_ROOT_DN;
//$_ISA_LDAP_USER_DN = "cn=Users,".$_ISA_LDAP_ROOT_DN;
$_ISA_LDAP_USER_DN = "".$_ISA_LDAP_ROOT_DN;

// DN cua user ket noi voi LDAP de lay cac thong tin cho cac ham trong WS cua ISA-USER
$_ISA_LDAP_ISAUSER_SEARCH_DN = "admin@".$_ISA_LDAP_DOMAIN;
//$_ISA_LDAP_ISAUSER_SEARCH_DN = "cn=ldapadmin".$_ISA_LDAP_ROOT_DN;
$_ISA_LDAP_SEARCH_PASSWORD = "password123!";

// DN cua user ket noi voi LDAP de cap nhat cac thong tin 
$_ISA_LDAP_ISAUSER_UPDATE_DN = "tuyendzung".$_ISA_LDAP_DOMAIN;
//$_ISA_LDAP_ISAUSER_UPDATE_DN = "cn=ldapadmin,".$_ISA_LDAP_ROOT_DN;
$_ISA_LDAP_ISAUSER_UPDATE_PASSWORD = "password123!";

// Dieu kien loc de lay danh sach nguoi du dung
//Doi vo LDAP
//$_ISA_LDAP_USER_OBJECTCLASS = "objectClass=cpvnPerson";
//Doi voi AD
//$_ISA_LDAP_USER_OBJECTCLASS = "objectcategory=CN=Person,CN=Schema,CN=Configuration,DC=isa,DC=com,DC=vn";
//$_ISA_LDAP_USER_OBJECTCLASS = "objectcategory=*";
//Doi vo DOMINO
$_ISA_LDAP_USER_OBJECTCLASS = "objectClass=dominoPerson";

// Ten cua thuoc tinh chua Username dung de tim kiem thong tin cua nguoi dang nhap
//$_ISA_LDAP_USERNAME_ATTRIBUTE = "cn";
//Doi voi AD
//$_ISA_LDAP_USERNAME_ATTRIBUTE = "samaccountname";


// Ten cua thuoc tinh chua Password
$_ISA_LDAP_PASSWORD_ATTRIBUTE = "userPassword";

?>
