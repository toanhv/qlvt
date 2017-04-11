if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_EndUserGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_EndUserGetAll]
Go
----------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_EndUserGetsingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_EndUserGetsingle]
Go
----------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_EnduserUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_EnduserUpdate]
Go
----------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_EnduserInsert]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_EnduserInsert]
Go
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_EnduserDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_EnduserDelete]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ModulGetAllForEnduser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ModulGetAllForEnduser]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_FunctionGetAllForEnduser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_FunctionGetAllForEnduser]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GroupGetAllForEnduser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GroupGetAllForEnduser]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_StaffGetAllIsNotUserOfApplication]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_StaffGetAllIsNotUserOfApplication]
GO
----------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_BirthdayOfEndUserGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_BirthdayOfEndUserGetAll]
GO
----------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_FunctionGetAllBelongGroup]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_FunctionGetAllBelongGroup]
GO

--------------------------------------------------------------------------------------------------------------------------------------------
Create procedure USER_EndUserGetAll
	@p_filter nvarchar(100),
	@p_application_id int,
	@p_status int
As
	SET NOCOUNT ON
	if @p_status < 0
		Select a.PK_ENDUSER,
			   b.C_NAME,
			   a.C_APP_STATUS,
			   dbo.f_GetUnitLevelOneID(a.FK_STAFF) AS C_UNIT_LEVEL1_ID,
			   dbo.f_GetUnitLevelOneName(a.FK_STAFF) AS C_UNIT_LEVEL1_NAME,
			   dbo.f_GetUnitName(a.FK_STAFF) AS C_UNIT_NAME,
     		  (Select Top 1 C_NAME from T_USER_POSITION p where p. PK_POSITION = b.FK_POSITION)as C_POSITION

		From T_USER_ENDUSER a left Join T_USER_STAFF b On a.FK_STAFF = b.PK_STAFF
		Where b.C_NAME Like @p_filter And a.FK_APPLICATION = @p_application_id 
		Order by b.C_INTERNAL_ORDER,b.C_NAME
	Else
		Select a.PK_ENDUSER,
			   b.C_NAME,
			   a.C_APP_STATUS,
			   dbo.f_GetUnitLevelOneID(a.FK_STAFF) AS C_UNIT_LEVEL1_ID,
			   dbo.f_GetUnitLevelOneName(a.FK_STAFF) AS C_UNIT_LEVEL1_NAME,
			   dbo.f_GetUnitName(a.FK_STAFF) AS C_UNIT_NAME,
			  (Select Top 1 C_NAME from T_USER_POSITION p where p. PK_POSITION = b.FK_POSITION)as C_POSITION
		From T_USER_ENDUSER a left Join T_USER_STAFF b On a.FK_STAFF = b.PK_STAFF
		Where b.C_NAME Like @p_filter And a.FK_APPLICATION = @p_application_id And a.C_APP_STATUS=@p_status 
		Order by b.C_INTERNAL_ORDER,b.C_NAME
	Return 0
Go
--USER_EndUserGetAll '%%',36,-1

----------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_EndUserGetsingle
	@p_enduser_id int
As
	SET NOCOUNT ON
	Select a.PK_ENDUSER,
		a.FK_STAFF,
		a.FK_APPLICATION,
		b.C_NAME,
		a.C_APP_USERNAME,
		a.C_APP_PASSWORD,
		a.C_APP_STATUS,
		c.C_NAME As C_APPLICATION_NAME, 
		c.C_AUTHENTICATE_BY_ISAUSER 
	From T_USER_ENDUSER a 
	Inner Join T_USER_STAFF b On a.FK_STAFF = b.PK_STAFF 
	Inner Join T_USER_APPLICATION c On a.FK_APPLICATION = c.PK_APPLICATION
	Where a.PK_ENDUSER = @p_enduser_id
	Return 0
Go
-- Exec USER_EndUserGetsingle 31189
----------------------------------------------------------------------------------------------------------------------------------------
--Thuc hien them nguoi su dung cho mot ung dung tu danh sach can bo
Create Procedure dbo.USER_EnduserInsert
	@p_application_id int,
	@p_staff_id_list nvarchar(4000)
As
	SET NOCOUNT ON
	-- Tao bang trung gian chua id cua NSD can xoa
	Create Table #T_STAFF(PK_STAFF int)
	Exec Sp_ListToTable @p_staff_id_list, 'PK_STAFF', '#T_STAFF', ','	
	-- Them vao T_USER_ENDUSER
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	Insert Into T_USER_ENDUSER(FK_STAFF,FK_APPLICATION,C_APP_STATUS) Select PK_STAFF, @p_application_id, 1 From #T_STAFF
	COMMIT TRANSACTION
	Return 0
Go

----------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE USER_EnduserUpdate
	@p_item_id int,
	@p_app_username nvarchar(30),
	@p_app_password nvarchar(30),
	@p_app_status tinyint,
	@p_list_group_id nvarchar(4000),
	@p_list_modul_id nvarchar(4000),
	@p_list_function_id nvarchar(4000)
AS
	Declare	@count int
	SET NOCOUNT ON
	-- Tao bang trung gian chua id cua cac nhom ma NSD la thanh vien
	Create Table #T_GROUP(PK_GROUP int)
	Exec Sp_ListToTable @p_list_group_id, 'PK_GROUP', '#T_GROUP', ','	
	-- Tao bang trung gian chua id cua cac modul ma NSD co quyen truy nhap
	Create Table #T_MODUL(PK_MODUL int)
	Exec Sp_ListToTable @p_list_modul_id, 'PK_MODUL', '#T_MODUL', ','	
	-- Tao bang trung gian chua id cua cac function ma NSD co quyen truy nhap
	Create Table #T_FUNCTION(PK_FUNCTION int)
	Exec Sp_ListToTable @p_list_function_id, 'PK_FUNCTION', '#T_FUNCTION', ','	

	SET NOCOUNT ON
	SET XACT_ABORT ON
	BEGIN TRANSACTION

	Update T_USER_ENDUSER 
		Set C_APP_USERNAME = @p_app_username,
			C_APP_PASSWORD = @p_app_password,
			C_APP_STATUS = @p_app_status
		Where PK_ENDUSER = @p_item_id

	--Cap nhat nhom
	Delete From T_USER_GROUP_BY_ENDUSER Where FK_ENDUSER = @p_item_id
	Insert Into T_USER_GROUP_BY_ENDUSER(FK_GROUP,FK_ENDUSER) Select PK_GROUP, @p_item_id From #T_GROUP

	--Cap nhat modul
	Delete From T_USER_MODUL_BY_ENDUSER Where FK_ENDUSER = @p_item_id
	Insert Into T_USER_MODUL_BY_ENDUSER(FK_MODUL,FK_ENDUSER) Select PK_MODUL, @p_item_id From #T_MODUL

	--Cap nhat function
	Delete From T_USER_FUNCTION_BY_ENDUSER Where FK_ENDUSER = @p_item_id
	Insert Into T_USER_FUNCTION_BY_ENDUSER(FK_FUNCTION,FK_ENDUSER) Select PK_FUNCTION, @p_item_id From #T_FUNCTION

	COMMIT TRANSACTION
	Return 0
GO

----------------------------------------------------------------------------------------------------------------------
--Xoa mot nguoi su dung
CREATE PROCEDURE USER_EnduserDelete
	@p_list_id nvarchar(4000)
AS
	SET NOCOUNT ON
	-- Tao bang trung gian chua id cua NSD can xoa
	Create Table #T_USER_ENDUSER(PK_ENDUSER int)
	Exec Sp_ListToTable @p_list_id, 'PK_ENDUSER', '#T_USER_ENDUSER', ','	
	-- Dat che do tu dong Rollback neu co loi xay ra
	SET XACT_ABORT ON
	BEGIN TRANSACTION	
	-- Xoa khoi danh sach thanh vien cua cac nhom
	Delete T_USER_GROUP_BY_ENDUSER Where FK_ENDUSER In (Select PK_ENDUSER From #T_USER_ENDUSER)
	-- Xoa quyen thuc hien chuc nang
	Delete T_USER_FUNCTION_BY_ENDUSER Where FK_ENDUSER In (Select PK_ENDUSER From #T_USER_ENDUSER)	
	-- Xoa quyen thuc hien modul
	Delete T_USER_MODUL_BY_ENDUSER Where FK_ENDUSER In (Select PK_ENDUSER From #T_USER_ENDUSER)	
	-- Xoa chinh no
	Delete T_USER_ENDUSER Where PK_ENDUSER in (Select PK_ENDUSER From #T_USER_ENDUSER) 
    COMMIT TRANSACTION
	Return 0
Go
-- USER_EnduserDelete '9,10'
--------------------------------------------------------------------------------------------------------------------------------------
--Lay tat ca modul cua ung dung cho enduser duoc chon
--va cac modul ma nhom hien thoi co quyen thuc hien
Create Procedure USER_ModulGetAllForEnduser
	@p_application_id int,
	@p_enduser_id int
As
	SET NOCOUNT ON
	Select 	a.PK_MODUL,
			a.C_NAME,
			(Select Count(*) From T_USER_MODUL_BY_ENDUSER Where a.PK_MODUL = FK_MODUL And FK_ENDUSER = @p_enduser_id) As C_CHECK
	From T_USER_MODUL a Where a.FK_APPLICATION = @p_application_id And a.C_STATUS = 1 Order By a.C_ORDER, a.C_NAME
	Return 0
Go
--------------------------------------------------------------------------------------------------------------------------------------
--Lay tat ca chuc nang cua ung dung cho enduser duoc chon
--va cac chuc nang ma nhom hien thoi co quyen thuc hien
Create Procedure USER_FunctionGetAllForEnduser
	@p_application_id int,
	@p_enduser_id int
As
	Select a.FK_MODUL,
			a.PK_FUNCTION,
			a.C_NAME,
			(Select Count(*) From T_USER_FUNCTION_BY_ENDUSER Where a.PK_FUNCTION = FK_FUNCTION And FK_ENDUSER = @p_enduser_id) As C_CHECK
	From T_USER_FUNCTION a Where a.FK_APPLICATION = @p_application_id And a.C_STATUS = 1 Order By a.C_ORDER, a.C_NAME
	Return 0
Go
--USER_FunctionGetAllForEnduser 94,31641

----------------------------------------------------------------------------------------------------------------------------------------
--Lay tat ca group cua mot ung dung va group ma enduser co trong group do
Create Procedure USER_GroupGetAllForEnduser
	@p_application_id int,
	@p_enduser_id int
As
	SET NOCOUNT ON
	Select 
		a.PK_GROUP,
		a.C_NAME,
		(Select Count(*) From T_USER_GROUP_BY_ENDUSER Where a.PK_GROUP = FK_GROUP AND FK_ENDUSER = @p_enduser_id) As C_CHECK
	From T_USER_GROUP a
	Where a.FK_APPLICATION = @p_application_id And a.C_STATUS = 1
	Order By a.C_ORDER, a.C_NAME
	Return 0
Go
----------------------------------------------------------------------------------------------------------------------
--Lay danh sach can bo de dua vao danh sach nguoi su dung cua mot ung dung
--Chi lay nhung can bo chua duoc dua vao la NSD cua ung dung va da duoc cap nhat username
Create Procedure dbo.USER_StaffGetAllIsNotUserOfApplication
	@p_application_id int
As
	SET NOCOUNT ON
	Select PK_STAFF,
			C_NAME,
			C_STATUS,
			dbo.f_GetUnitLevelOneID(PK_STAFF) AS C_UNIT_LEVEL1_ID,
			dbo.f_GetUnitLevelOneName(PK_STAFF) AS C_UNIT_LEVEL1_NAME,
			dbo.f_GetUnitName(PK_STAFF) AS C_UNIT_NAME
		From T_USER_STAFF
		Where PK_STAFF Not In (Select FK_STAFF From T_USER_ENDUSER Where FK_APPLICATION = @p_application_id) And C_STATUS = 1
			And C_USERNAME Is Not Null
		Order by C_INTERNAL_ORDER,C_NAME
	Return 0
Go
--dbo.USER_StaffGetAllIsNotUserOfApplication 35

--------------------------------------------------------------------------------------------------------------------------------------------
Create procedure USER_BirthdayOfEndUserGetAll
As
	SET NOCOUNT ON
	Select PK_STAFF,
		   C_NAME,
		   C_BIRTHDAY,
		   dbo.f_GetUnitLevelOneID(PK_STAFF) AS C_UNIT_LEVEL1_ID,
		   dbo.f_GetUnitLevelOneName(PK_STAFF) AS C_UNIT_LEVEL1_NAME
	From T_USER_STAFF 
	Where C_BIRTHDAY is not NULL
	Order by C_INTERNAL_ORDER,C_NAME
	Return 0
Go
--USER_BirthdayOfEndUserGetAll

/*
Thu tuc lay tat ca cac function thuoc ve cac group trong mot ung dung
Muc dich: Phuc vu cho viec hien thi cac chuc nang cua nhom khi EndUser duoc ban quyen theo nhom
Viet boi: Hoang Khac Duc
*/

CREATE PROCEDURE dbo.USER_FunctionGetAllBelongGroup
	@p_application_id int
AS
	SET NOCOUNT ON
	Select A.FK_FUNCTION,
			C.C_NAME,
			A.FK_GROUP,
			B.C_NAME
		From T_USER_FUNCTION_BY_GROUP A 
			LEFT JOIN T_USER_GROUP B ON A.FK_GROUP = B.PK_GROUP
			LEFT JOIN T_USER_FUNCTION C ON A.FK_FUNCTION = C.PK_FUNCTION
		Where C.FK_APPLICATION = @p_application_id And B.C_STATUS = 1
		Order by A.FK_FUNCTION 
	Return 0
GO

--USER_FunctionGetAllBelongGroup 35