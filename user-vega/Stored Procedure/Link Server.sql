if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[SP_CreateDBLink]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[SP_CreateDBLink]
GO
/***********************************************************************
* procedure: SP_CreateDBLink
* Objective: Create a database link to remote server
* Created date: 02/08/2007
* Creator: phongtd
* Updated history:
***********************************************************************/
Create Procedure SP_CreateDBLink
@pLinkServer	nvarchar(500),
@pRemoteServer	nvarchar(500)
As
	Declare @mSql nvarchar(1000)--, @v_Provider Nvarchar(1000)

	Select @mSql = 'sp_dropserver  @server =' + char(39) + @pLinkServer + char(39)
	Exec (@mSql)


	--Set @v_Provider = 'Provider=SQLOLEDB; Data Source=HUNGVM;Initial Catalog=efy-user-ngoquyen; User ID=sa; Password=sa'

	Select @mSql = 'sp_addlinkedserver @server = ' + char(39) + @pLinkServer + char(39) 
	Select @mSql = @mSql + ',@srvproduct = ' + char(39) + char(39) 
	Select @mSql = @mSql + ',@provider = ' + char(39) + 'SQLOLEDB' + char(39)
	Select @mSql = @mSql + ',@datasrc = ' + char(39) + @pRemoteServer + char(39)

	Exec (@mSql)
Return 
GO
/**

-- Link user
Exec SP_CreateDBLink
@pLinkServer='DBLink',
@pRemoteServer='localhost'
EXEC sp_addlinkedsrvlogin 'DBLink', 'false', NULL, 'phongtd', 'tph_123312##'
EXEC sp_serveroption 'DBLink', 'RPC', 'ON' 
EXEC sp_serveroption 'DBLink', 'RPC OUT', 'ON' 

Select * from DBLink.[user].dbo.T_USER_STAFF

*/

