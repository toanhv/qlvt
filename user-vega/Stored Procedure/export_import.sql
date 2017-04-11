if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_BackupFunctionGetAllByApp]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_BackupFunctionGetAllByApp]
GO
----------------------------------------------------------------------------------------------------------------------------
Create Procedure dbo.USER_BackupFunctionGetAllByApp	
	@p_application_id int
As
	Select * From T_USER_FUNCTION
	Where FK_APPLICATION = @p_application_id
	Order by C_ORDER,C_NAME
GO
-- USER_BackupFunctionGetAllByApp 113
/*************************************************************************************************************************/
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_BackupFunctionUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_BackupFunctionUpdate]
GO
----------------------------------------------------------------------------------------------------------------------------
Create Procedure dbo.USER_BackupFunctionUpdate	
	@p_application_id int,
	@p_modul_id int,
	@p_code nvarchar(100),
	@p_name nvarchar(500),
	@p_order int,
	@p_status bit,
	@p_public bit
As
	Declare @modul_id int
	SET NOCOUNT ON
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	Insert Into T_USER_FUNCTION(
			  FK_APPLICATION,
			  FK_MODUL,
			  C_CODE,
			  C_NAME,
			  C_ORDER,
			  C_STATUS,
			  C_PUBLIC)
	   Values(@p_application_id,
			  @p_modul_id,
			  @p_code,
			  @p_name,
			  @p_order,
			  @p_status,
		      @p_public)

	Set @modul_id = @@IDENTITY
	Select @modul_id NEW_ID
	COMMIT TRANSACTION
	SET NOCOUNT OFF
RETURN 0
GO
/*************************************************************************************************************************/
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_BackupModulGetAllByApp]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_BackupModulGetAllByApp]
GO
----------------------------------------------------------------------------------------------------------------------------
Create Procedure dbo.USER_BackupModulGetAllByApp	
	@p_application_id int
As
	Select * From T_USER_MODUL
	Where FK_APPLICATION = @p_application_id
	Order by C_ORDER,C_NAME
GO
-- USER_BackupModulGetAllByApp 113
/*************************************************************************************************************************/
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_BackupModulUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_BackupModulUpdate]
GO
----------------------------------------------------------------------------------------------------------------------------
Create Procedure dbo.USER_BackupModulUpdate	
	@p_application_id int,
	@p_code nvarchar(100),
	@p_name nvarchar(500),
	@p_order int,
	@p_status bit,
	@p_public bit
As
	Declare @function_id int
	SET NOCOUNT ON
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	Insert Into T_USER_MODUL(
			  FK_APPLICATION,
			  C_CODE,
			  C_NAME,
			  C_ORDER,
			  C_STATUS,
			  C_PUBLIC)
	   Values(@p_application_id,
			  @p_code,
			  @p_name,
			  @p_order,
			  @p_status,
		      @p_public)

	Set @function_id = @@IDENTITY
	Select @function_id NEW_ID
	COMMIT TRANSACTION
	SET NOCOUNT OFF
RETURN 0
GO
/*************************************************************************************************************************/
-- Select * From T_USER_MODUL Where FK_APPLICATION = 114
-- Select * From T_USER_FUNCTION Where FK_APPLICATION = 114
-- USER_BackupFunctionUpdate 114,0,'LIST_AGENCY_DELETE','Xóa co quan hành chính',4,1,0