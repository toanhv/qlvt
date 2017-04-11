if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ModulGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ModulGetAll]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ModulGetAllByApp]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ModulGetAllByApp]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ModulGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ModulGetSingle]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ModulUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ModulUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ModulDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ModulDelete]
GO
---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_ModulGetAll
	@p_status smallint
As
	SET NOCOUNT ON
	If @p_status<0
		Select PK_MODUL,FK_APPLICATION,C_CODE,C_NAME,'0' AS C_TYPE, 1 As C_LEVEL, C_ORDER,C_STATUS 
		From T_USER_MODUL Order by C_ORDER,C_NAME
	Else
		Select PK_MODUL,FK_APPLICATION,C_CODE,C_NAME,'0' AS C_TYPE, 1 As C_LEVEL, C_ORDER,C_STATUS From T_USER_MODUL
		Where C_STATUS = @p_status
		Order by C_ORDER,C_NAME
Return 0
Go
---------------------------------------------------------------------------------------------------------------------------------------
-- Procedure USER_ModulGetAll
-- Writen by: Nguyen Tai Ba
-- Tao ngay: 17/12/2003
-- Cap nhat ngay: 5/2/2004
-- Lay tat ca cac Modul co trong CSDL
Create Procedure USER_ModulGetAllByApp
	@p_status smallint,
	@p_application_id int
As
	SET NOCOUNT ON
	if @p_application_id>0
		Begin
			If @p_status<0
				Select PK_MODUL,FK_APPLICATION,C_CODE,C_NAME,'0' AS C_TYPE, 1 As C_LEVEL, C_ORDER,C_STATUS From T_USER_MODUL
				WHERE FK_APPLICATION=@p_application_id Order by C_ORDER,C_NAME
			Else
				Select PK_MODUL,FK_APPLICATION,C_CODE,C_NAME,'0' AS C_TYPE, 1 As C_LEVEL, C_ORDER,C_STATUS From T_USER_MODUL
				Where C_STATUS = @p_status AND FK_APPLICATION=@p_application_id
				Order by C_ORDER,C_NAME
		End
	Else
		Begin
			If @p_status<0
				Select PK_MODUL,FK_APPLICATION,C_CODE,C_NAME,'0' AS C_TYPE, 1 As C_LEVEL, C_ORDER,C_STATUS From T_USER_MODUL
				Order by C_ORDER,C_NAME
			Else
				Select PK_MODUL,FK_APPLICATION,C_CODE,C_NAME,'0' AS C_TYPE, 1 As C_LEVEL, C_ORDER,C_STATUS From T_USER_MODUL
				Where C_STATUS = @p_status
				Order by C_ORDER,C_NAME
		End
Return 0
Go
--USER_ModulGetAll -1,0
---------------------------------------------------------------------------------------------------------------------------------------
--Lay cac thuoc tinh cua mot Modul khi hieu chinh
Create Procedure USER_ModulGetSingle
	@p_item_id int
As
	SET NOCOUNT ON
	If @p_item_id > 0
		Begin
			Select A.PK_MODUL,
				A.FK_APPLICATION,
				A.C_CODE,
				A.C_NAME,
				A.C_ORDER,
				A.C_STATUS,
				A.C_PUBLIC,
				B.C_CODE As C_APPLICATION_CODE ,
				B.C_NAME As C_APPLICATION_NAME
	 		From T_USER_MODUL As A Left Join T_USER_APPLICATION As B On A.FK_APPLICATION = B.PK_APPLICATION
			Where A.PK_MODUL = @p_item_id
		End
Return 0
Go
--USER_ModulGetSingle 112
----------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.USER_ModulUpdate
	@p_item_id int,
	@p_application_id int,
	@p_item_code nvarchar(100),
	@p_item_name nvarchar(400),
	@p_order smallint,
	@p_status tinyint,
	@p_public tinyint
AS
	Declare	@count_code int, @count_name int, @modul_id int,@status int
	SET NOCOUNT ON

	If @p_item_id=0
		Begin
			SELECT @count_code = COUNT(*) from T_USER_MODUL where C_CODE = @p_item_code And FK_APPLICATION = @p_application_id
			SELECT @count_name = COUNT(*) from T_USER_MODUL where C_NAME = @p_item_name And FK_APPLICATION = @p_application_id
		End
	Else
		Begin
			SELECT @count_code = COUNT(*) from T_USER_MODUL where PK_MODUL <> @p_item_id And C_CODE = @p_item_code And FK_APPLICATION = @p_application_id
			SELECT @count_name = COUNT(*) from T_USER_MODUL where PK_MODUL <> @p_item_id And C_NAME = @p_item_name And FK_APPLICATION = @p_application_id
		End
	If @Count_code>0
		Begin
			Select 'Ma modul da ton tai trong danh sach' RET_ERROR
			return -100
		End
	If @Count_name>0
		Begin
			Select 'Ten modul da ton tai trong danh sach' RET_ERROR
			return -100
		End
	-- Dat che do tu dong rollback toan bo transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION

	If @p_item_id >0
		Begin
			Update T_USER_MODUL SET 
				FK_APPLICATION = @p_application_id,
				C_CODE = @p_item_code,
				C_NAME = @p_item_name, 		
				C_ORDER = @p_order,
				C_STATUS = @p_status, 
				C_PUBLIC = @p_public 
				WHERE PK_MODUL = @p_item_id
			Set @modul_id = @p_item_id
		End
	Else 
		Begin
			Insert into T_USER_MODUL(FK_APPLICATION,C_CODE,C_NAME,C_ORDER,C_STATUS, C_PUBLIC) 
			Values(@p_application_id,@p_item_code,@p_item_name,@p_order,@p_status, @p_public)
			Set @modul_id = @@IDENTITY
		End

	/*
	If @p_order Is Not Null And @p_order>0
		Exec SP_ReOrder 'T_USER_MODUL', 'C_ORDER', 'PK_MODUL', @modul_id, @p_order,''
	*/
	Select @modul_id NEW_ID

	COMMIT TRANSACTION

RETURN 0
GO
--USER_ModulUpdate 0,14,'TK','Tim Kiem',17,1
----------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.USER_ModulDelete
	@p_id_list nvarchar(4000)
AS
	Declare @count int
	SET NOCOUNT ON
	-- Tao bang trung gian
	Create Table #T_USER_MODUL(PK_MODUL int)
	Exec Sp_ListToTable @p_id_list, 'PK_MODUL', '#T_USER_MODUL', ','	
	-- Neu modul co chua function thi khong xoa duoc
	Set @count=0
	Select TOP 1 @count = FK_MODUL From T_USER_FUNCTION Where FK_MODUL In (Select PK_MODUL From #T_USER_MODUL)
	If (@count>0)
		Begin
			Select 'Khong the xoa duoc modul da chon vi co chua danh sach cac CHUC NANG' RET_ERROR
			Return -100
		End 
	-- Dat che do tu dong Rollback neu co loi xay ra
	SET XACT_ABORT ON
	BEGIN TRANSACTION	
	-- Xoa quyen truy nhap modul da ban cho mot enduser
	Delete T_USER_MODUL_BY_ENDUSER Where FK_MODUL in (Select PK_MODUL From #T_USER_MODUL) 
	-- Xoa quyen truy nhap modul da ban cho mot group
	Delete T_USER_MODUL_BY_GROUP Where FK_MODUL in (Select PK_MODUL From #T_USER_MODUL) 
	-- Xoa modul
	Delete T_USER_MODUL Where PK_MODUL in (Select PK_MODUL From #T_USER_MODUL) 
	COMMIT TRANSACTION
Return 0
GO
--Select * From T_USER_FUNCTION Where FK_MODUL= 27