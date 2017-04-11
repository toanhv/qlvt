<?php
define("CONST_TEL_LOCAL_LABEL","&#272;i&#7879;n tho&#7841;i N&#7897;i b&#7897;");
define("CONST_TEL_OFFICE_LABEL","&#272;T C&#417; quan");
define("CONST_TEL_MOBILE_LABEL","&#272;i&#7879;n tho&#7841;i Di &#273;&#7897;ng");
define("CONST_TEL_HOME_LABEL","&#272;T Nh&#224; ri&#234;ng");
define("CONST_FAX_LABEL","S&#7889; m&#225;y Fax");
//***********************BUTTON***********************************
define("CONST_ADD_UNIT_BUTTON", "Th&#234;m &#273;&#417;n v&#7883;");
define("CONST_ADD_STAFF_BUTTON", "Th&#234;m c&#225;n b&#7897;");
//***********************CHUC DANH***********************************
define("CONST_UNIT_LIST_TITLE","Danh s&#225;ch c&#225;c ph&#242;ng ban");
define("CONST_POSITION_LIST_TITLE","Danh m&#7909;c ch&#7913;c danh c&#225;n b&#7897;");
define("CONST_POSITION_LIST_FILTER_LABEL","L&#7885;c theo t&#234;n ch&#7913;c danh");
define("CONST_POSITION_CODE_LABEL","M&#227; vi&#7871;t t&#7855;t");
define("CONST_POSITION_NAME_LABEL","T&#234;n ch&#7913;c danh");
define("CONST_POSITION_GROUP_LABEL","Thu&#7897;c nh&#243;m");
define("CONST_POSITION_ORDER_LABEL","Th&#7913; t&#7921;");
define("CONST_POSITION_UPDATE_TITLE","C&#7853;p nh&#7853;t m&#7897;t ch&#7913;c danh c&#225;n b&#7897;");
//***********************PHONG BAN***********************************
define("CONST_UNIT_TITLE","Danh s&#225;ch c&#225;c ph&#242;ng ban");
define("CONST_UNIT_UPDATE_TITLE","C&#7853;p nh&#7853;t m&#7897;t ph&#242;ng ban");
define("CONST_SHOW_ALL_BUTTON","Hi&#7875;n t&#7845;t c&#7843;");
define("CONST_UNIT_CODE_LABEL","M&#227; vi&#7871;t t&#7855;t");
define("CONST_UNIT_NAME_LABEL","T&#234;n ph&#242;ng ban");
define("CONST_WITHIN_UNIT_LABEL","Thu&#7897;c ph&#242;ng, ban");
define("CONST_UNIT_TEL_LABEL","&#272;T c&#7889; &#273;&#7883;nh");
define("CONST_UNIT_TEL_LOCAL_LABEL","&#272;T n&#7897;i b&#7897;");
define("CONST_UNIT_FAX_LABEL","FAX");

define("CONST_UNIT_ADDRESS_LABEL","&#272;&#7883;a ch&#7881;");
define("CONST_UNIT_EMAIL_LABEL","Email");
define("CONST_WITHIN_UNIT","Ph&#242;ng ban");
define("CONST_UNIT_ORDER_LABEL","Th&#7913; t&#7921;");
//Ten
define("CONST_UNIT_NAME_MAXLENGTH",100);
define("CONST_UNIT_NAME_MESSAGE","Phai xac dinh ten phong ban");
define("CONST_UNIT_NAME_OPTIONAL",false);
//Ma viet tat
define("CONST_UNIT_CODE_OPTIONAL",true);
//Vi tri hien thi cua phong ban
define("CONST_HIDE_ALL_BUTTON","D&#7845;u t&#7845;t c&#7843;");
define("CONST_UNIT_ORDER_OPTIONAL",true);
define("CONST_UNIT_ORDER_ISNUMERIC",true);
define("CONST_UNIT_ORDER_MIN",0);
define("CONST_UNIT_ORDER_MAX",999);
define("CONST_UNIT_ORDER_MAXLENGTH",3);
define("CONST_UNIT_ORDER_MESSAGE","Ph&#7843;i x&#225;c &#273;&#7883;nh v&#7883; tr&#237; c&#7911;a ph&#242;ng ban trong danh s&#225;ch");
//Email
define("CONST_UNIT_EMAIL_MAXLENGTH",200);
define("CONST_IS_EMAIL",true);
define("CONST_IS_EMAIL_MESSAGE","Dinh dang Email khong dung, VD: jonh@vnn.vn");
define("CONST_UNIT_EMAIL_OPTIONAL",true);
//Tel
define("CONST_UNIT_TEL_MAXLENGTH",50);
define("CONST_UNIT_TEL_OPTIONAL",true);

define("CONST_UNIT_TEL_LOCAL_OPTIONAL",true);
define("CONST_UNIT_FAX_OPTIONAL",true);
//Address
define("CONST_UNIT_ADDRESS_MAXLENGTH",200);
define("CONST_UNIT_ADDRESS_OPTIONAL",true);
//Phong ban cha
define("CONST_WITHIN_UNIT_OPTIONAL",true);
//************* CHUC DANH*********************
// Khong chap nhan gia tri rong
define("CONST_POSITION_CODE_OPTIONAL",false);
// Do dia toi da cua ma la 10 ki tu
define("CONST_POSITION_CODE_MAXLENGTH",15);
// Thong bao loi neu nhap du lieu sai
define("CONST_POSITION_CODE_MESSAGE","Ma viet tat khong hop le (phai khac trong va dai toi da 15 ki tu)");

// Khong chap nhan gia tri rong
define("CONST_POSITION_NAME_OPTIONAL",false);
// Do dia toi da cua ma la 10 ki tu
define("CONST_POSITION_NAME_MAXLENGTH",100);
// Thong bao loi neu nhap du lieu sai
define("CONST_POSITION_NAME_MESSAGE","Ten chuc danh khong hop le (phai khac trong va dai toi da 100 ki tu)");

//***************** Nhom chuc danh can bo
define("CONST_POSITION_GROUP_OPTIONAL",true);
define("CONST_POSITION_GROUP_MESSAGE","Phai xac dinh nhom chuc danh");

// Chap nhan gia tri rong
define("CONST_POSITION_ORDER_OPTIONAL",true);
// Kieu gia tri la so, gia tri nho nhat la 0, gia tri lon nhat la 999
define("CONST_POSITION_ORDER_ISNUMERIC",true);
define("CONST_POSITION_ORDER_MIN",0);
define("CONST_POSITION_ORDER_MAX",999);
// Do dia toi da cua ma la 3 ki tu
define("CONST_POSITION_ORDER_MAXLENGTH",3);
// Thong bao loi neu nhap du lieu sai
define("CONST_POSITION_ORDER_MESSAGE","Thu tu hien thi trong danh sach khong hop le (Phai la so va nhan gia tri tu 1 den 999)");

//**************************CAN BO(STAFF)*********************************
define("CONST_POSITION_OF_STAFF","Ch&#7913;c danh");
define("CONST_UNIT_OF_STAFF","Ph&#242;ng ban");
// Tieu de cua danh sach
define("CONST_STAFF_LIST_TITLE","Danh s&#225;ch c&#225;n b&#7897;");
// text hien thi truoc o loc theo ten
define("CONST_STAFF_LIST_FILTER_LABEL","L&#7885;c theo t&#234;n c&#225;n b&#7897;");
define("CONST_STAFF_UPDATE_TITLE","C&#7853;p nh&#7853;t m&#7897;t c&#225;n b&#7897;");
//Ma can bo
define("CONST_STAFF_CODE_LABEL","M&#227; c&#225;n b&#7897;");
define("CONST_STAFF_CODE_OPTIONAL",true);
define("CONST_STAFF_CODE_MESSAGE","Phai xac dinh ma viet tat");
//Ten can bo
define("CONST_STAFF_NAME_LABEL","T&#234;n c&#225;n b&#7897;");
define("CONST_STAFF_NAME_OPTIONAL",false);
define("CONST_STAFF_NAME_MAXLENGTH",100);
define("CONST_STAFF_NAME_MESSAGE","Phai xac dinh ten can bo");
//Ngay sinh can bo
define("CONST_STAFF_BIRTHDAY_LABEL","Ng&#224;y sinh (ng&#224;y/th&#225;ng/n&#259;m): ");
define("CONST_STAFF_BIRTHDAY_OPTIONAL",true);
define("CONST_STAFF_BIRTHDAY_MAXLENGTH",10);
define("CONST_STAFF_BIRTHDAY_MESSAGE","Ngay sinh cua can bo khong hop le (dinh dang ngay/thang/nam)");
//Chuc danh can bo
define("CONST_STAFF_POSITION_LABEL","Ch&#7913;c danh");
define("CONST_STAFF_POSITION_OPTIONAL",true);
//Phong ban
define("CONST_STAFF_OF_UNIT_LABEL","Ph&#242;ng, ban");
define("CONST_STAFF_OF_UNIT_OPTIONAL",true);
//Dia chi
define("CONST_STAFF_ADDRESS_LABEL","&#272;&#7883;a ch&#7881;");
define("CONST_STAFF_ADDRESS_OPTIONAL",true);
//So dien thoai
define("CONST_STAFF_TEL_LABEL","&#272;i&#7879;n tho&#7841;i");
define("CONST_STAFF_TEL_OPTIONAL",true);
//Email
define("CONST_STAFF_EMAIL_LABEL","Email");
define("CONST_STAFF_EMAIL_OPTIONAL",true);
//Ten dang nhap
define("CONST_STAFF_USERNAME_MAXLENGTH",100);
define("CONST_STAFF_USERNAME_LABEL","T&#234;n &#273;&#462;ng nh&#7853;p");
define("CONST_STAFF_USERNAME_OPTIONAL",true);
define("CONST_STAFF_USERNAME_MESSAGE","Phai xac dinh ten dang nhap");
//Ma dang nhap
define("CONST_STAFF_PASSWORD_MAXLENGTH",100);
define("CONST_STAFF_PASSWORD_LABEL","M&#7853;t kh&#7849;u m&#7899;i");
define("CONST_STAFF_RE_PASSWORD_LABEL","X&#225;c &#273;&#7883;nh l&#7841;i");
define("CONST_STAFF_PASSWORD_OPTIONAL",true);
define("CONST_STAFF_PASSWORD_MESSAGE","M&#7853;t kh&#7849;u ng&#432;&#7901;i d&#249;ng kh&#244;ng &#273;&#432;&#7907;c &#273;&#7875; tr&#7889;ng");

define("CONST_STAFF_OLD_PASSWORD_LABEL","M&#7853;t kh&#7849;u &#273;ang d&#249;ng");

//Vi tri hien thi cua can bo
define("CONST_STAFF_ORDER_LABEL","Th&#7913; t&#7921;");
define("CONST_STAFF_ORDER_OPTIONAL",false);
define("CONST_STAFF_ORDER_ISNUMERIC",true);
define("CONST_STAFF_ORDER_MIN",0);
define("CONST_STAFF_ORDER_MAX",9999);
define("CONST_STAFF_ORDER_MAXLENGTH",4);
define("CONST_STAFF_ORDER_MESSAGE","Phai xac dinh vi tri cua can bo trong danh sach");
//Chuc danh can bo
define("CONST_POSITION_OF_STAFF_OPTIONAL",true);
define("CONST_IS_ADMIN_LABEL","Qu&#7843;n tr&#7883; ISA-USER");
define("CONST_ROLE_LABEL","Vai tr&#242;");
//Chuc danh can bo
define("CONST_SELECT_LDAPUSER_LABEL","&#272;/c trong LDAP");
define("CONST_SELECT_LDAPUSER_OPTIONAL",false);
define("CONST_SELECT_LDAPUSER_SELECTBOX","H&#227;y ch&#7885;n NSD t&#432;&#417;ng &#7913;ng trong CSDL LDAP");
define("CONST_SELECT_LDAPUSER_MESSAGE","Phai xac dinh mot nguoi su dung tuong ung trong LDAP");
//Mo Them
define("CONST_ENDUSER_OPTIONAL",false);
define("CONST_ENDUSER_MESSAGE","T&#234;n ng&#432;&#7901;i d&#249;ng kh&#244;ng &#273;&#432;&#7907;c &#273;&#7875; tr&#7889;ng");
define("CONST_NAME_LOGIN_MESSAGE","T&#234;n &#273;&#462;ng nh&#7853;p kh&#244;ng &#273;&#432;&#7907;c &#273;&#7875; tr&#7889;ng");
define("CONST_APPLICATION_NAME_LABEL","T&#234;n &#7913;ng d&#7909;ng");
define("CONST_APPLICATION","&#7913;ng d&#7909;ng");
define("CONST_ENDUSER_NAME_LABEL","T&#234;n ng&#432;&#7901;i d&#249;ng");
define("CONST_ENDUSER_USERNAME_LABEL","T&#234;n &#273;&#462;ng nh&#7853;p");
//Cac hang so lien quan toi nguoi su dung
define("CONST_ENDUSER_CHANGE_INFO_TITLE","Thay &#273;&#7893;i m&#7853;t kh&#7849;u Ng&#432;&#7901;i s&#7917; d&#7909;ng");
define("CONST_ENDUSER_NOCHANGE_INFO_TITLE","Ch&#7913;c n&#259;ng n&#224;y kh&#244;ng cho ph&#233;p ng&#432;&#7901;i s&#7917; d&#7909;ng thay &#273;&#7893;i m&#7853;t kh&#7849;u.<br>Mu&#7889;n thay &#273;&#7893;i m&#7853;t kh&#7849;u h&#227;y li&#234;n h&#7879; v&#7899;i ng&#432;&#7901;i qu&#7843;n tr&#7883; h&#7879; th&#7889;ng");
define("CONST_ENDUSER_UPDATE_TITLE","C&#7853;p nh&#7853;t m&#7897;t ng&#432;&#7901;i s&#7917; d&#7909;ng");
define("CONST_USER_LIST","Thu&#7897;c nh&#243;m");
define("CONST_FUNCTION_LIST","Ch&#7913;c n&#462;ng");
define("CONST_DISPLAY_ALL_GROUP_LIST","Hi&#7875;n th&#7883; t&#7845;t c&#7843; nh&#243;m");
define("CONST_GROUP_USER_DISPLAY","Hi&#7875;n th&#7883; c&#225;c nh&#243;m m&#224; NSD l&#224; th&#224;nh vi&#234;n");
define("CONST_GROUP_ALL_FUNCTION_DISPLAY","Hi&#7875;n th&#7883; t&#7845;t c&#7843; c&#225;c ch&#7913;c n&#462;ng");
define("CONST_GROUP_FUNCTION_DISPLAY","Ch&#7881; hi&#7875;n th&#7883; c&#225;c ch&#7913;c n&#462;ng c&#7911;a nh&#243;m");

define("CONST_CONFIR_BUTTON","X&#225;c nh&#7853;n");
define("CONST_CANCEL_BUTTON","Hu&#7927;");
?>

 