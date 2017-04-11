IF exists (select * from syscolumns where id=object_id('T_USER_STAFF') And name='C_CODE')
	ALTER TABLE T_USER_STAFF ALTER COLUMN C_CODE nvarchar(1000)
GO
IF exists (select * from syscolumns where id=object_id('T_USER_STAFF') And name='C_NAME')
	ALTER TABLE T_USER_STAFF ALTER COLUMN C_NAME nvarchar(1000)
GO
IF exists (select * from syscolumns where id=object_id('T_USER_STAFF') And name='C_USERNAME')
	ALTER TABLE T_USER_STAFF ALTER COLUMN C_USERNAME nvarchar(1000)
GO
IF exists (select * from syscolumns where id=object_id('T_USER_STAFF') And name='C_PASSWORD')
	ALTER TABLE T_USER_STAFF ALTER COLUMN C_PASSWORD nvarchar(1000)
GO
