-- Lenh them Cot C_DN vao bang T_USER_STAFF
if not exists (select * from syscolumns where id=object_id('T_USER_STAFF') and name='C_DN')
    ALTER TABLE T_USER_STAFF ADD C_DN varchar(1000)
go
-- Anh xa nguoi su dung cua ISA-USER voi nguoi su dung lay tu LDAP-SERVER
UPDATE T_USER_STAFF SET 
	C_DN = 'cn=admin,ou=Users,dc=yenbai,dc=gov,dc=vn',  --username dang nhap ldap
	C_ROLE = 1
WHERE C_USERNAME = 'admin'                              --usernam quan tri ISA-USER
