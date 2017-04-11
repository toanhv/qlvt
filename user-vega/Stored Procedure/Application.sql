if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ApplicationGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ApplicationGetAll]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ApplicationGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ApplicationGetSingle]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ApplicationUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ApplicationUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ApplicationDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ApplicationDelete]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetAllAdminForApplication]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetAllAdminForApplication]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[User_IsAppAdmin]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[User_IsAppAdmin]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_ApplicationGetSingleByCode]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_ApplicationGetSingleByCode]
GO
---------------------------------------------------------------------------------------------------------------------------------------
-- Writen by: Nguyen Tai ba
-- Tra ve tat ca cac ung dung neu nguoi su dung la quan tri isa-user
-- Tra ve cac ung dung ma nguoi dang nhap duoc quyen quan tri va su dung
Create Procedure USER_ApplicationGetAll
	@p_status smallint,
	@p_user_id int
As
	SET NOCOUNT ON
	Declare @is_isa_user_admin int
	--Kiem tra xem nguoi nay co phai la QUAN TRI ISA-USER khong?
	Select @is_isa_user_admin = C_ROLE From T_USER_STAFF Where  PK_STAFF = @p_user_id
	if @is_isa_user_admin >0
		Begin
			If @p_status<0
				Select PK_APPLICATION,NULL As FK_VIRTUAL, C_CODE,C_NAME,'0' As C_TYPE, 0 As C_LEVEL,C_ORDER,C_STATUS,C_DESCRIPTION,C_WEB_BASED_APP,C_AUTHENTICATE_BY_ISAUSER,C_START_PATH From T_USER_APPLICATION Order by C_ORDER,C_NAME
			Else
				Select PK_APPLICATION,NULL As FK_VIRTUAL, C_CODE,C_NAME,'0' As C_TYPE, 0 As C_LEVEL,C_ORDER,C_STATUS,C_DESCRIPTION,C_WEB_BASED_APP,C_AUTHENTICATE_BY_ISAUSER,C_START_PATH From T_USER_APPLICATION Where C_STATUS=@p_status Order by C_ORDER,C_NAME
		End
	Else --Chi lay cac ung dung ma user_id co quyen quan tri va su dung (nhung khong duoc cap nhat mot ung dung nao)
		If @p_status<0
			Select PK_APPLICATION,NULL As FK_VIRTUAL, C_CODE,C_NAME,'0' As C_TYPE,'0' As C_LEVEL,C_ORDER,C_STATUS,C_DESCRIPTION,C_WEB_BASED_APP,C_AUTHENTICATE_BY_ISAUSER,C_START_PATH From T_USER_APPLICATION
				Where PK_APPLICATION In (select FK_APPLICATION FROM T_USER_APPLICATION_ADMIN WHERE FK_STAFF=@p_user_id)--quyen quan tri
				Order by C_ORDER,C_NAME
				--PK_APPLICATION in(select FK_APPLICATION FROM T_USER_ENDUSER WHERE FK_STAFF=@p_user_id)--quyen su dung
		Else
			Select PK_APPLICATION,NULL As FK_VIRTUAL, C_CODE,C_NAME,'0' As C_TYPE,'0' As C_LEVEL,C_ORDER,C_STATUS,C_DESCRIPTION,C_WEB_BASED_APP,C_AUTHENTICATE_BY_ISAUSER,C_START_PATH From T_USER_APPLICATION
				Where PK_APPLICATION IN(select FK_APPLICATION FROM T_USER_APPLICATION_ADMIN WHERE FK_STAFF=@p_user_id)
				And C_STATUS = @p_status Order by C_ORDER,C_NAME
				--PK_APPLICATION in(select FK_APPLICATION FROM T_USER_ENDUSER WHERE FK_STAFF=@p_user_id)
	SET NOCOUNT OFF
Go

--USER_ApplicationGetAll -1,10

---------------------------------------------------------------------------------------------------------------------------------------
-- Procedure: USER_ApplicationGetSingle
-- Createn by :Nguyen Tai Ba
-- Nhiem vu : Lay cacs thuoc tinh cua mot ung dung
Create Procedure USER_ApplicationGetSingle
	@p_application_id int -- ID cua ung dung
As
	SET NOCOUNT ON
	Select PK_APPLICATION,
			C_CODE,
			C_NAME,
			C_ORDER,
			C_STATUS,
			C_DESCRIPTION,
			C_WEB_BASED_APP,
			C_AUTHENTICATE_BY_ISAUSER,
			C_START_PATH,
			C_USERNAME_VAR,
			C_PASSWORD_VAR,
			C_VAR_NAME_LIST,
			C_VAR_VALUE_LIST
	From T_USER_APPLICATION
	Where PK_APPLICATION = @p_application_id
	SET NOCOUNT OFF
Go
--USER_ApplicationGetSingle 36
----------------------------------------------------------------------------------------------------------------------
-- PROCEDURE: dbo.USER_ApplicationUpdate
-- Createn by :Nguyen Tai Ba
-- Nhiem vu: Cap nhat mot ung dung
CREATE PROCEDURE dbo.USER_ApplicationUpdate
	@p_item_id int, -- ID cua ung dung: 0 - neu la them moi, >0 la hieu chinh
	@p_code nvarchar(100), -- ma ung dung
	@p_name nvarchar(400), -- ten dung dung
	@p_order smallint, -- thu tu trong danh sach
	@p_status tinyint,
	@p_description nvarchar(1000),
	@p_web_based_app bit,
	@p_authenticate_by_isauser bit,
	@p_url_path nvarchar(500),
	@p_app_admin_id_list nvarchar(4000), -- danh sach ID cua nguoi quan tri ung dung
	@p_username_var varchar(100),
	@p_password_var varchar(100),
	@p_varible_name_list nvarchar(4000),
	@p_varible_value_list nvarchar(4000)
AS
	Declare	@count_code int,@count_name int, @application_id int,@status int,@len_list int,@p_admin_id int
	SET NOCOUNT ON

	SELECT @count_code = COUNT(*) from T_USER_APPLICATION where PK_APPLICATION <> @p_item_id And C_CODE=@p_code
	SELECT @count_name = COUNT(*) from T_USER_APPLICATION where PK_APPLICATION <> @p_item_id And C_NAME=@p_name

	If @Count_code>0
		Begin
			Select 'Ma phan mem ung dung da co trong danh muc' RET_ERROR
			return -100
		End
	If @Count_code>0
		Begin
			Select 'Ten phan mem ung dung da co trong danh muc' RET_ERROR
			return -100
		End

	-- 	Tao bang temp de chua danh muc ID quan tri ung dung
	Create Table #T_APP_ADMIN(PK_STAFF int)
	Exec Sp_ListToTable	@p_app_admin_id_list, 'PK_STAFF','#T_APP_ADMIN',','

	-- Dat che do tu dong rollback toan bo transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION

	If @p_item_id >0
		Begin
			Update T_USER_APPLICATION SET 
				C_CODE = @p_code,
				C_NAME = @p_name, 		
				C_ORDER = @p_order,
				C_STATUS = @p_status, 
				C_DESCRIPTION = @p_description,
				C_WEB_BASED_APP = @p_web_based_app,
				C_AUTHENTICATE_BY_ISAUSER = @p_authenticate_by_isauser,
				C_START_PATH = @p_url_path,
				C_USERNAME_VAR = @p_username_var,
				C_PASSWORD_VAR = @p_password_var,
				C_VAR_NAME_LIST = @p_varible_name_list,
				C_VAR_VALUE_LIST = @p_varible_value_list
				WHERE PK_APPLICATION = @p_item_id
			Set @application_id = @p_item_id
		End
	Else 
		Begin
			Insert into T_USER_APPLICATION(C_CODE,C_NAME,C_ORDER,C_STATUS,C_DESCRIPTION,C_WEB_BASED_APP,C_AUTHENTICATE_BY_ISAUSER,C_START_PATH,C_USERNAME_VAR,C_PASSWORD_VAR,C_VAR_NAME_LIST,C_VAR_VALUE_LIST)
			 Values(@p_code,@p_name,@p_order,@p_status,@p_description,@p_web_based_app,@p_authenticate_by_isauser,@p_url_path,@p_username_var,@p_password_var,@p_varible_name_list,@p_varible_value_list)
			Set @application_id = @@IDENTITY
		End

	-- Xoa danh sach nguoi quan tri ung dung hien thoi
	Delete From T_USER_APPLICATION_ADMIN Where FK_APPLICATION = @application_id
	-- Cap nhat lai danh sach nguoi quan tri ung dung
	Insert Into T_USER_APPLICATION_ADMIN (FK_APPLICATION, FK_STAFF) Select @application_id, PK_STAFF From #T_APP_ADMIN

	-- Doan code duoi day se xu ly: "Moi nguoi quan tri ung dung cung se la mot enduser cua ung dung do"
	-- Lay danh sach enduser hien thoi va chuyen vao bang temp
	Select FK_STAFF Into #T_CURRENT_ENDUSER From T_USER_ENDUSER Where FK_APPLICATION=@application_id
	Insert Into T_USER_ENDUSER(FK_APPLICATION, FK_STAFF, C_APP_STATUS) 
		Select @application_id, PK_STAFF,1 From #T_APP_ADMIN 
			Where PK_STAFF Not in (Select FK_STAFF From #T_CURRENT_ENDUSER)
	/*
	If @p_order Is Not Null And @p_order>0
		Exec SP_ReOrder 'T_USER_APPLICATION', 'C_ORDER', 'PK_APPLICATION', @application_id, @p_order,''
	*/
	Select @application_id NEW_ID

	COMMIT TRANSACTION

	SET NOCOUNT OFF

	RETURN 0
GO
--USER_ApplicationUpdate 31,'ISA-DOCLIB','nguyen tai ba',1,1,'SDFLASFJ',1,1,'','9'
--select * from T_USER_APPLICATION

----------------------------------------------------------------------------------------------------------------------
-- PROCEDURE dbo.USER_ApplicationDelete
-- Createn by :Nguyen Tai Ba
-- Nhiem vu la xoa mot hoac nhieu ung dung

CREATE PROCEDURE dbo.USER_ApplicationDelete
	@p_id_list nvarchar(4000)
AS
	Declare @count int
	SET NOCOUNT ON
	-- Tao bang trung gian
	Create Table #T_USER_APPLICATION(PK_APPLICATION int)
	Exec Sp_ListToTable @p_id_list, 'PK_APPLICATION', '#T_USER_APPLICATION', ','	
	-- Kiem tra rang buoc kieu khoa ngoai
	Select TOP 1 @count = FK_APPLICATION From T_USER_ENDUSER Where FK_APPLICATION In (Select PK_APPLICATION From #T_USER_APPLICATION)
	If @count>0 
		Begin
			Select 'Khong the xoa cac ung dung da chon vi co chua danh muc NSD' RET_ERROR
			Return -100
		End 
	Set @count=0
	Select TOP 1 @count = FK_APPLICATION From T_USER_GROUP Where FK_APPLICATION In (Select PK_APPLICATION From #T_USER_APPLICATION)
	If @count>0 
		Begin
			Select 'Khong the xoa cac ung dung da chon vi co chua danh muc cac nhom NSD' RET_ERROR
			Return -100
		End 
	Set @count=0
	Select TOP 1 @count = FK_APPLICATION From T_USER_FUNCTION Where FK_APPLICATION In (Select PK_APPLICATION From #T_USER_APPLICATION)
	If @count>0 
		Begin
			Select 'Khong the xoa cac ung dung da chon vi co chua danh muc cac chuc nang' RET_ERROR
			Return -100
		End 
	Set @count=0
	Select TOP 1 @count = FK_APPLICATION From T_USER_MODUL Where FK_APPLICATION In (Select PK_APPLICATION From #T_USER_APPLICATION)	
	If @count>0 
		Begin
			Select 'Khong the xoa cac ung dung da chon vi co chua danh muc cac Modul' RET_ERROR
			Return -100
		End 
	
	-- Dat che do tu dong Rollback neu co loi xay ra
	SET XACT_ABORT ON
	BEGIN TRANSACTION	
	-- Xoa danh sach nguoi quan tri ung dung
	Delete T_USER_APPLICATION_ADMIN Where FK_APPLICATION in (Select PK_APPLICATION From #T_USER_APPLICATION) 
	-- Xoa danh sach ung dung
	Delete T_USER_APPLICATION Where PK_APPLICATION in (Select PK_APPLICATION From #T_USER_APPLICATION) 
	COMMIT TRANSACTION
	Return 0
GO
--USER_ApplicationDelete 16

--------------------------------------------------------------------------------------------------------------------------
-- Procedure: USER_GetAllAdminForApplication
-- Createn by :Nguyen Tai Ba
-- Muc dich: Lay tat ca Nguoi quan tri cua mot ung dung
-- Tra lai: mot RecordSet chua danh sach nguoi quan tri cua mot ung dung, voi cac column sau: PK_STAFF, C_NAME
Create Procedure dbo.USER_GetAllAdminForApplication
	@p_application_id int -- ID cua ung dung
As
	SET NOCOUNT ON
	Select
		A.PK_STAFF,
		A.C_NAME,
		(Select Count(*) From T_USER_APPLICATION_ADMIN Where A.PK_STAFF = FK_STAFF AND FK_APPLICATION = @p_application_id) As C_CHECK
	From T_USER_STAFF A 
	Where C_STATUS = 1
	ORDER BY C_ORDER, C_NAME
	Return 0
Go

--------------------------------------------------------------------------------------------------------------------------
-- Procedure User_IsAppAdmin
-- Kiem tra xem nguoi dang nhap @p_staff_id co phai la QUAN TRI cua ung dung @p_application_id hay khong
-- Createn by Nguyen Tuan Anh
Create Procedure User_IsAppAdmin
	@p_application_id int,
	@p_staff_id int
As	
	Declare @is_app_admin int
	Set @is_app_admin=0
	Select @is_app_admin=1 FROM T_USER_APPLICATION_ADMIN WHERE FK_STAFF = @p_staff_id And FK_APPLICATION=@p_application_id
	Select @is_app_admin IS_APP_ADMIN
Return 0
Go
--------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_ApplicationGetSingleByCode
	@p_app_code nvarchar(100) -- ID cua ung dung
As
	SET NOCOUNT ON
	Select PK_APPLICATION,
			C_CODE,
			C_NAME,
			C_ORDER,
			C_STATUS,
			C_DESCRIPTION,
			C_WEB_BASED_APP,
			C_AUTHENTICATE_BY_ISAUSER,
			C_START_PATH,
			C_USERNAME_VAR,
			C_PASSWORD_VAR,
			C_VAR_NAME_LIST,
			C_VAR_VALUE_LIST
	From T_USER_APPLICATION 
	Where C_AUTHENTICATE_BY_ISAUSER = 1 And C_CODE = @p_app_code
Return 0
GO
-- USER_ApplicationGetSingleByCode 'ISA-MAIL'