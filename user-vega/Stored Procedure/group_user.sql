if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GroupGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GroupGetAll]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GroupGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GroupGetSingle]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GroupUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GroupUpdate]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GroupDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GroupDelete]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ModulGetAllForGroup]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ModulGetAllForGroup]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_FunctionGetAllForGroup]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_FunctionGetAllForGroup]
GO
----------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_EndUserGetAllForGroup]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_EndUserGetAllForGroup]
Go
---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_GroupGetAll
	@p_application_id int,
	@p_filter nvarchar(100),
	@p_status smallint
As
	SET NOCOUNT ON
	If @p_status < 0
		Select PK_GROUP,
			FK_APPLICATION,
			C_CODE,
			C_NAME,
			C_ORDER,
			C_STATUS 
		From T_USER_GROUP 
		Where FK_APPLICATION = @p_application_id And C_NAME like @p_filter 
		Order by C_ORDER,C_NAME 
	Else
		Select PK_GROUP,
			FK_APPLICATION,
			C_CODE,C_NAME,
			C_ORDER,
			C_STATUS 
			From T_USER_GROUP 
			Where FK_APPLICATION = @p_application_id And C_NAME like @p_filter And C_STATUS = @p_status 
			Order by C_ORDER,C_NAME 
Return 0
GO

---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_GroupGetSingle
	@p_item_id int,
	@p_application_id int
As
	SET NOCOUNT ON
	If @p_item_id>0
		Select a.FK_APPLICATION,
			a.C_CODE,
			a.C_NAME,
			a.C_ORDER,
			a.C_STATUS,
			(Select C_NAME From T_USER_APPLICATION Where PK_APPLICATION = a.FK_APPLICATION) As C_APPLICATION_NAME
		From T_USER_GROUP a 
		Where a.PK_GROUP = @p_item_id
	Else
		Select PK_APPLICATION,
			'' As C_CODE,
			'' As C_NAME,
			0 as C_ORDER,
			1 As C_STATUS,
			C_NAME As C_APPLICATION_NAME
		From T_USER_APPLICATION  
		Where PK_APPLICATION = @p_application_id

Return 0
Go

----------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE USER_GroupUpdate
	@p_item_id int,
	@p_application_id int,
	@p_code nvarchar(50),
	@p_name nvarchar(100),
	@p_order smallint,
	@p_status tinyint,
	@p_list_enduser_id nvarchar(4000),
	@p_list_modul_id nvarchar(4000),
	@p_list_function_id nvarchar(4000)
AS
	Declare
		@p_count int,
		@group_id int,
		@status int,
		@list_len int,
		@p_enduser_id int,
		@p_modul_id int,
		@p_function_id int

	SET NOCOUNT ON
	If @p_item_id = 0
		Select @p_count = Count(*) From T_USER_GROUP Where C_CODE = @p_code 
	Else
		Select @p_count = Count(*) From T_USER_GROUP Where PK_GROUP <> @p_item_id And C_CODE = @p_code
	If @p_count > 0
		Begin
			Select 'Ma nhom nguoi su dung da ton tai' RET_ERROR
			Return -100
		End

	-- Tao bang trung gian chua id cua cac nhom ma NSD la thanh vien
	Create Table #T_ENDUSER(PK_ENDUSER int)
	Exec Sp_ListToTable @p_list_enduser_id, 'PK_ENDUSER', '#T_ENDUSER', ','	
	-- Tao bang trung gian chua id cua cac modul ma NSD co quyen truy nhap
	Create Table #T_MODUL(PK_MODUL int)
	Exec Sp_ListToTable @p_list_modul_id, 'PK_MODUL', '#T_MODUL', ','	
	-- Tao bang trung gian chua id cua cac function ma NSD co quyen truy nhap
	Create Table #T_FUNCTION(PK_FUNCTION int)
	Exec Sp_ListToTable @p_list_function_id, 'PK_FUNCTION', '#T_FUNCTION', ','	

	-- Dat che do tu dong rollback toan bo transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	If @p_item_id > 0
		Begin
			--Cap nhat vao bang T_USER_GROUP
			Update T_USER_GROUP SET 
				FK_APPLICATION = @p_application_id,
				C_CODE = @p_code,
				C_NAME = @p_name, 		
				C_ORDER = @p_order,
				C_STATUS = @p_status 
			Where PK_GROUP = @p_item_id
			Set @group_id = @p_item_id
		End
	Else
		Begin
			Insert into T_USER_GROUP(FK_APPLICATION,C_CODE,C_NAME,C_ORDER,C_STATUS) 
			Values(@p_application_id,@p_code,@p_name,@p_order,@p_status)
			Set @group_id = @@IDENTITY
		End
	
	--Cap nhat nhom
	Delete From T_USER_GROUP_BY_ENDUSER Where FK_GROUP = @group_id
	Insert Into T_USER_GROUP_BY_ENDUSER(FK_GROUP,FK_ENDUSER) Select @group_id, PK_ENDUSER From #T_ENDUSER

	--Cap nhat modul
	Delete From T_USER_MODUL_BY_GROUP Where FK_GROUP = @group_id
	Insert Into T_USER_MODUL_BY_GROUP(FK_MODUL,FK_GROUP) Select PK_MODUL, @group_id From #T_MODUL

	--Cap nhat function
	Delete From T_USER_FUNCTION_BY_GROUP Where FK_GROUP = @group_id
	Insert Into T_USER_FUNCTION_BY_GROUP(FK_FUNCTION,FK_GROUP) Select PK_FUNCTION, @group_id From #T_FUNCTION

	--Sap xep lai thi tu trong bang T_USER_GROUP
	If @p_order Is Not Null And @p_order>0
		Exec SP_ReOrder 'T_USER_GROUP', 'C_ORDER', 'PK_GROUP', @group_id, @p_order,''

	Select @group_id NEW_ID

	COMMIT TRANSACTION

	Return 0
GO

----------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE USER_GroupDelete
	@p_list_id nvarchar(4000)
AS
	Declare @id1 int,@id2 int,@id3 int
	SET NOCOUNT ON
	-- Tao bang trung gian chua id cua cac nhom can xoa
	Create Table #T_USER_GROUP(PK_GROUP int)
	Exec Sp_ListToTable @p_list_id, 'PK_GROUP', '#T_USER_GROUP', ','

	-- Kiem tra rang buoc kieu khoa ngoai
	Select TOP 1 @id1 = FK_GROUP From T_USER_GROUP_BY_ENDUSER Where FK_GROUP In (Select PK_GROUP From #T_USER_GROUP)
	If (@id1 >0)
		Begin
			Select 'Khong the xoa duoc cac nhom da chon (vi co chua cac thanh vien)' RET_ERROR
			Return -100
		End 
	-- Dat che do tu dong Rollback neu co loi xay ra
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	-- Xoa quyen thuc hien cac modul
	Delete T_USER_MODUL_BY_GROUP Where FK_GROUP in (Select PK_GROUP From #T_USER_GROUP)
	-- Xoa quyen thuc hien cac chuc nang
	Delete T_USER_FUNCTION_BY_GROUP Where FK_GROUP in (Select PK_GROUP From #T_USER_GROUP)
	-- Xoa nhom
	Delete T_USER_GROUP Where PK_GROUP in (Select PK_GROUP From #T_USER_GROUP)

    COMMIT TRANSACTION
Return 0
GO

--------------------------------------------------------------------------------------------------------------------------------------
--Lay tat ca modul cua ung dung cho group duoc chon
--va cac modul ma nhom hien thoi co quyen thuc hien
Create Procedure USER_ModulGetAllForGroup
	@p_application_id int,
	@p_group_id int
As
	SET NOCOUNT ON
	Select A.PK_MODUL,
		   A.C_NAME,
		   (Select Count(*) From T_USER_MODUL_BY_GROUP Where A.PK_MODUL = FK_MODUL And FK_GROUP = @p_group_id) As C_CHECK
	From T_USER_MODUL A 
	Where A.FK_APPLICATION = @p_application_id And A.C_STATUS = 1 
	Order By A.C_ORDER, A.C_NAME
Return 0
Go
--------------------------------------------------------------------------------------------------------------------------------------
--Lay tat ca chuc nang cua ung dung cho group duoc chon
--va cac chuc nang ma nhom hien thoi co quyen thuc hien
Create Procedure USER_FunctionGetAllForGroup
	@p_application_id int,
	@p_group_id int
As
	SET NOCOUNT ON
	Select A.FK_MODUL,
			A.PK_FUNCTION,
			A.C_NAME,
			(Select Count(*) From T_USER_FUNCTION_BY_GROUP Where A.PK_FUNCTION = FK_FUNCTION And FK_GROUP = @p_group_id) As C_CHECK
	From T_USER_FUNCTION A 
	Where A.FK_APPLICATION = @p_application_id And A.C_STATUS = 1 
	Order By A.C_ORDER, A.C_NAME
Return 0
Go

----------------------------------------------------------------------------------------------------------------------------------------
--Lay tat ca nguoi dung cua mot ung dung va nhung NSD co trong nhom duoc chon
Create Procedure USER_EndUserGetAllForGroup
	@p_application_id int,
	@p_group_id int
As
	SET NOCOUNT ON
	Select 
		a.PK_ENDUSER AS PK_ENDUSER,
		b.C_NAME AS C_NAME,
		b.C_STATUS,
		dbo.f_GetUnitLevelOneID(a.FK_STAFF) AS C_UNIT_LEVEL1_ID,
		dbo.f_GetUnitLevelOneName(a.FK_STAFF) AS C_UNIT_LEVEL1_NAME,
		dbo.f_GetUnitName(a.FK_STAFF) AS C_UNIT_NAME,
		(Select Count(*) From T_USER_GROUP_BY_ENDUSER Where A.PK_ENDUSER = FK_ENDUSER AND FK_GROUP = @p_group_id) As C_CHECK,
		(Select Top 1 C_NAME from T_USER_POSITION p where p. PK_POSITION = b.FK_POSITION)as C_POSITION
	From T_USER_ENDUSER a Inner Join T_USER_STAFF b On a.FK_STAFF = b.PK_STAFF 
	Where a.FK_APPLICATION = @p_application_id 
	Order By b.C_INTERNAL_ORDER,b.C_NAME
Return 0
Go

--USER_EndUserGetAllForGroup 46,64