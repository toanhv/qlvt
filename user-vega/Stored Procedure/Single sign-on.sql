-- Lenh them Cot C_DN vao bang T_USER_STAFF
IF not exists (select * from syscolumns where id=object_id('T_USER_APPLICATION') And name='C_USERNAME_VAR')
    ALTER TABLE T_USER_APPLICATION ADD C_USERNAME_VAR varchar(100)
GO
IF not exists (select * from syscolumns where id=object_id('T_USER_APPLICATION') And name='C_PASSWORD_VAR')
	ALTER TABLE T_USER_APPLICATION ADD C_PASSWORD_VAR varchar(100)
GO
IF not exists (select * from syscolumns where id=object_id('T_USER_APPLICATION') And name='C_VAR_NAME_LIST')
	ALTER TABLE T_USER_APPLICATION ADD C_VAR_NAME_LIST nvarchar(4000)
GO
IF not exists (select * from syscolumns where id=object_id('T_USER_APPLICATION') And name='C_VAR_VALUE_LIST')
	ALTER TABLE T_USER_APPLICATION ADD C_VAR_VALUE_LIST nvarchar(4000)
GO
IF exists (select * from syscolumns where id=object_id('T_USER_LOGON') And name='C_IP_ADDRESS')
	ALTER TABLE T_USER_LOGON ALTER COLUMN C_IP_ADDRESS nvarchar(100)
GO