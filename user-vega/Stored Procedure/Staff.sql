if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_StaffGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_StaffGetAll]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetPersonalInfoOfAllStaffForEveryStatus]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetPersonalInfoOfAllStaffForEveryStatus]
Go
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetAllStaffByCondition]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetAllStaffByCondition]
Go
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_StaffGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_StaffGetSingle]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_StaffUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_StaffUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_StaffDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_StaffDelete]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UserGetDetail]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_UserGetDetail]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UserUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_UserUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_BirthdayStaffGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_BirthdayStaffGetAll]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetCN]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetCN]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_StaffAddFromLDAPUser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_StaffAddFromLDAPUser]
GO

---------------------------------------------------------------------------------------------------------------------------------------
-- Lay CN
CREATE Function f_GetCN(@pDN varchar(1000))
Returns varchar(500)
As
Begin
	Declare @v_value varchar(500), @v_position int
	Set @v_position = charindex(',',@pDN)
	If @v_position=0 Return '' 
	Set @v_value = Left(@pDN,@v_position-1)

	Set @v_position = charindex('=',@v_value)
	If @v_position=0 Return '' 
	Set @v_value = Substring(@v_value,@v_position+1,Len(@v_value)-@v_position)
	Return @v_value
End
Go

----------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.USER_StaffAddFromLDAPUser
	@p_ldap_user_list varchar(8000),
	@p_unit_id int,
	@p_list_delimitor varchar(50)
AS
	SET NOCOUNT ON
	Declare	@v_unit_id int
	If @p_unit_id = 0 Or @p_unit_id Is Null
		Select @v_unit_id = PK_UNIT From T_USER_UNIT Where FK_UNIT Is Null
	Else
		Set @v_unit_id = @p_unit_id

	Create Table #T_STAFF(C_DN varchar(1000))
	Exec Sp_ListToTable	@p_ldap_user_list, 'C_DN','#T_STAFF',@p_list_delimitor
	-- Dat che do tu dong rollback toan bo transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION
		Insert into T_USER_STAFF(C_DN,FK_UNIT,C_CODE,C_NAME,C_USERNAME,C_STATUS) Select C_DN, @v_unit_id, dbo.f_GetCN(C_DN), dbo.f_GetCN(C_DN), dbo.f_GetCN(C_DN),1 From #T_STAFF
	COMMIT TRANSACTION

RETURN 0
GO

--USER_StaffAddFromLDAPUser 'cn=contact,ou=Users,dc=yenbai,dc=gov,dc=vn!&~$*cn=dangthitien,ou=Users,dc=yenbai,dc=gov,dc=vn!&~$*cn=dangvanhuynh,ou=Users,dc=yenbai,dc=gov,dc=vn!&~$*cn=hoangxuanloc,ou=Users,dc=yenbai,dc=gov,dc=vn!&~$*cn=nguyenvanngoc,ou=Users,dc=yenbai,dc=gov,dc=vn','!&~$*'
--select * from t_user_staff

---------------------------------------------------------------------------------------------------------------------------------------
--+Thu tuc USER_StaffGetAll
--+Createn by :Nguyen Tai Ba
--+Lay danh sach cac can bo
Create Procedure USER_StaffGetAll
	@p_status int,  -- Tinh trang (-1: lay tat ca, 1-Hoat dong; 9-Khong hoat dong)
	@p_unit_id int  -- ID cua don vi can lay danh sach can bo
As
	Declare @v_internal_order varchar(500)
	SET NOCOUNT ON
	If @p_unit_id=0
		Select TOP 1 @p_unit_id = PK_UNIT From T_USER_UNIT Where FK_UNIT Is Null

	Select @v_internal_order=C_INTERNAL_ORDER From T_USER_UNIT Where PK_UNIT=@p_unit_id

	-- Dua ID cua tat cac cac UNIT CHA, ONG, CU, KY vao temp table
	Select PK_UNIT Into #T_UNIT From T_USER_UNIT Where C_INTERNAL_ORDER = Left(@v_internal_order,Len(C_INTERNAL_ORDER)) 

	If @p_status<0
		Begin
			Select 	PK_UNIT AS PK_OBJECT,
					FK_UNIT AS FK_OBJECT,
					C_CODE,
					C_NAME, 
					'0' As C_TYPE, 
					'0' As C_LEVEL,
					dbo.USER_UnitHaveChildren(PK_UNIT) AS C_HAVE_CHILDREN,
					C_INTERNAL_ORDER
				From T_USER_UNIT
				Where FK_UNIT In (Select PK_UNIT From #T_UNIT) Or FK_UNIT Is Null
			Union
				Select 	PK_STAFF AS PK_OBJECT,
						FK_UNIT AS FK_OBJECT,
						C_CODE,
						C_NAME, 
						'1' As C_TYPE, 
						'1' As C_LEVEL,
						0 AS C_HAVE_CHILDREN,
						dbo.USER_UnitGetNextInternalOrder(FK_UNIT,C_ORDER) AS C_INTERNAL_ORDER
					From T_USER_STAFF 
					Where FK_UNIT=@p_unit_id Or FK_UNIT In (Select PK_UNIT From #T_UNIT)
			Order By C_INTERNAL_ORDER		
		End
	Else
		Begin
			Select 	PK_UNIT AS PK_OBJECT,
					FK_UNIT AS FK_OBJECT,
					C_CODE,
					C_NAME, 
					'0' As C_TYPE, 
					'0' As C_LEVEL,
					dbo.USER_UnitHaveChildren(PK_UNIT) AS C_HAVE_CHILDREN,
					C_INTERNAL_ORDER
				From T_USER_UNIT
				Where FK_UNIT In (Select PK_UNIT From #T_UNIT) Or FK_UNIT Is Null
					And C_STATUS=@p_status 
			Union
				Select 	PK_STAFF AS PK_OBJECT,
						FK_UNIT AS FK_OBJECT,
						C_CODE,
						C_NAME, 
						'1' As C_TYPE, 
						'1' As C_LEVEL,
						0 AS C_HAVE_CHILDREN,
						dbo.USER_UnitGetNextInternalOrder(FK_UNIT,C_ORDER)+ dbo.f_FormatOrder(Isnull(C_ORDER,0),5) AS C_INTERNAL_ORDER
					From T_USER_STAFF 
					Where FK_UNIT=@p_unit_id Or FK_UNIT In (Select PK_UNIT From #T_UNIT)
						And C_STATUS=@p_status 
			Order By C_INTERNAL_ORDER	

		End
	Return 0
GO
/*
Exec USER_StaffGetAll 1,80
USER_StaffGetAll -1,197
Select pk_unit,fk_unit,c_name,c_order,c_internal_order From T_USER_UNIT  where fk_unit=148 Order by C_INTERNAL_ORDER
Select * from T_user_staff where fk_unit=148

Update T_USER_STAFF Set C_INTERNAL_ORDER = (Select rtrim(C_INTERNAL_ORDER) From T_USER_UNIT Where PK_UNIT=T_USER_STAFF.FK_UNIT) + dbo.f_FormatOrder(Isnull(T_USER_STAFF.C_ORDER,0),5)
	where fk_unit=148	

*/
---------------------------------------------------------------------------------------------------------------------------------------
-- Thu tuc nay tra lai mot RecoredSet chua thong tin ca nhan cua tat ca can bo (staff) voi moi trang thai
-- Created by:Nguyen Tai Ba
---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_GetPersonalInfoOfAllStaffForEveryStatus
	@p_unit_id int
As
	SET NOCOUNT ON
	Select A.PK_STAFF,
			A.C_NAME,				
			D.C_NAME,--Ten cua don vi chua can bo
			B.C_NAME AS C_POSITION_NAME,
			A.C_TEL_LOCAL,
			A.C_TEL,
			A.C_TEL_MOBILE,
			A.C_TEL_HOME,
			A.C_FAX,	
			A.C_EMAIL,
			A.C_ORDER,
			A.C_ADDRESS	
		From T_USER_STAFF A
			Left Join T_USER_POSITION B On A.FK_POSITION = B.PK_POSITION
			Left Join T_USER_UNIT D On A.FK_UNIT = D.PK_UNIT		
		Where A.FK_UNIT = @p_unit_id
		Order BY D.FK_UNIT DESC, A.C_ORDER

	SET NOCOUNT OFF
GO
-- Exec USER_GetPersonalInfoOfAllStaffForEveryStatus
---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_GetAllStaffByCondition
	@p_staff_name nvarchar(200),
	@p_unit_id int,
	@p_tel_local nvarchar(100),
	@p_tel_office nvarchar(100),
	@p_tel_mobile nvarchar(100),
	@p_tel_home nvarchar(100)
As
	Declare @str_sql nvarchar(4000)
	SET NOCOUNT ON
	SET @str_sql = ''
	set @str_sql=	'Select A.PK_STAFF,
				A.C_NAME,				
				D.C_NAME,
				B.C_NAME AS C_POSITION_NAME,
				A.C_TEL_LOCAL,
				A.C_TEL,
				A.C_TEL_MOBILE,
				A.C_TEL_HOME,
				A.C_FAX,	
				A.C_EMAIL,
				A.C_ORDER,
				A.FK_UNIT		
			From T_USER_STAFF A
				Left Join T_USER_POSITION B On A.FK_POSITION = B.PK_POSITION
				Left Join T_USER_UNIT D On A.FK_UNIT = D.PK_UNIT '		
			if @p_unit_id = 0
				Begin
					set @str_sql = @str_sql + ' Where A.C_NAME Like ' + char(39)+ @p_staff_name + char(39)
				End
			Else
				Begin
					set @str_sql = @str_sql + ' Where A.C_NAME Like ' + char(39) + @p_staff_name + char(39) + ' And A.FK_UNIT = ' + Convert(varchar, @p_unit_id)
				End
			set @str_sql = @str_sql + ' Order BY D.C_ORDER, A.FK_UNIT, A.C_ORDER'
	EXEC(@str_sql)
	RETURN 0
GO

--USER_GetAllStaffByCondition '%A%',0,'','','',''
		--Where A.C_NAME like @p_staff_name or A.C_TEL_LOCAL= @p_tel_local OR A.C_TEL = @p_tel_office OR A.C_TEL_MOBILE =@p_tel_mobile OR A.C_TEL_HOME = @p_tel_home
---------------------------------------------------------------------------------------------------------------------------------------
--+Thu tuc USER_StaffGetSingle
--+Createn by :Nguyen Tai Ba
--+Lay thong tin cua mot can bo
Create Procedure USER_StaffGetSingle
	@p_item_id int
As
	SET NOCOUNT ON
	Select A.PK_STAFF,
			A.C_NAME,
			A.C_CODE,
			A.FK_UNIT,
			B.FK_UNIT AS C_UNIT_LEVEL1,
			Case 
			When (Select Top 1 C_CODE From T_USER_UNIT where PK_UNIT = B.FK_UNIT And FK_UNIT <> '') is null Then (Select Top 1 C_CODE From T_USER_UNIT where PK_UNIT = A.FK_UNIT)
			Else  (Select Top 1 C_CODE From T_USER_UNIT where PK_UNIT = B.FK_UNIT And FK_UNIT <> '')
 			End as C_UNIT_CODE_LEVEL1,
			B.C_NAME AS C_UNIT_NAME,
			B.C_CODE AS C_UNIT_CODE,
			A.FK_POSITION,
			C.C_CODE AS C_POSITION_CODE,
			C.C_NAME AS C_POSITION_NAME,
			A.C_ADDRESS,
			A.C_EMAIL,
			A.C_TEL_LOCAL,
			A.C_TEL,
			A.C_TEL_MOBILE,
			A.C_TEL_HOME,
			A.C_FAX,
			A.C_USERNAME,
			A.C_PASSWORD,
			A.C_ORDER,
			A.C_STATUS,
			A.C_ROLE,
			rtrim(CONVERT(CHAR(2),DATEPART(d,A.C_BIRTHDAY)))+'/'+rtrim(CONVERT(CHAR(2),DATEPART(m,A.C_BIRTHDAY)))+'/'+rtrim(CONVERT(CHAR(4),DATEPART(yy,A.C_BIRTHDAY))) As C_BIRTHDAY,
			A.C_DN
	From T_USER_STAFF A
		Left Join T_USER_UNIT B On A.FK_UNIT = B.PK_UNIT
		Left Join T_USER_POSITION C On A.FK_POSITION = C.PK_POSITION
	Where PK_STAFF = @p_item_id
Go
-- Exec USER_StaffGetSingle 2299
--select * fROM T_USER_STAFF WHERE PK_STAFF=37--C_TEL_HOME='5520769'
----------------------------------------------------------------------------------------------------------------------
--+Thu tuc USER_StaffUpdate
--+Createn by :Nguyen Tai Ba
--+Cap nhat thong tin cua mot can bo
CREATE PROCEDURE dbo.USER_StaffUpdate
	@p_item_id int,
	@p_unit_id int,
	@p_position_id int,
	@p_code nvarchar(100),
	@p_name nvarchar(1000),
	@p_birthday nvarchar(100),
	@p_address nvarchar(1000),
	@p_tel_local nvarchar(100),
	@p_tel_office nvarchar(100),
	@p_tel_mobile nvarchar(100),
	@p_tel_home nvarchar(100),
	@p_fax nvarchar(100),
	@p_email nvarchar(1000),
	@p_username nvarchar(100),
	@p_password nvarchar(100),	
	@p_order smallint,
	@p_status tinyint,
	@p_role tinyint,
	@p_ldap_dn varchar(1000)
AS
	Declare	@count int, @item_id int,@status int, @v_filter nvarchar(500), @v_internal_order varchar(500)
	SET NOCOUNT ON

	If @p_birthday = '' Set @p_birthday = NULL

	If @p_code<>'' And @p_code Is Not Null
		Begin
			If @p_item_id = 0
				SELECT @count = COUNT(*) from T_USER_STAFF where C_CODE = @p_code
			Else
				SELECT @count = COUNT(*) from T_USER_STAFF where PK_STAFF <> @p_item_id And C_CODE = @p_code
			
			If @Count > 0
				Begin
					Select 'Ma can bo da ton tai' RET_ERROR
					return -100
				End
		End

	-- Kiem tra trung ten dang nhap
	If @p_username<>'' And @p_username Is Not Null
		Begin
			Set @count = 0
			If @p_item_id = 0
				SELECT @count = COUNT(*) from T_USER_STAFF where C_USERNAME = @p_username
			Else
				SELECT @count = COUNT(*) from T_USER_STAFF where PK_STAFF <> @p_item_id And C_USERNAME = @p_username
			
			If @Count > 0
				Begin
					Select 'TEN DANG NHAP da ton tai' RET_ERROR
					return -100
				End
		End


	-- Dat che do tu dong rollback toan bo transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	if @p_unit_id = ''
		Begin
			set @p_unit_id = NULL
		End
	if @p_position_id = ''
		Begin
			set @p_position_id = NULL
		End
	If @p_item_id > 0
		Begin
			Update T_USER_STAFF SET 
				FK_UNIT = @p_unit_id,
				FK_POSITION = @p_position_id,
				C_CODE = @p_code,
				C_NAME = @p_name, 
				C_BIRTHDAY = @p_birthday, 
				C_ADDRESS = @p_address,
				C_TEL_LOCAL = @p_tel_local,
				C_TEL = @p_tel_office,
				C_TEL_MOBILE = @p_tel_mobile,
				C_TEL_HOME = @p_tel_home,
				C_FAX = @p_fax,
				C_EMAIL = @p_email,
				C_USERNAME = @p_username,
				C_PASSWORD = @p_password,			
				C_ORDER = @p_order,
				C_STATUS = @p_status,
				C_ROLE = @p_role,
				C_DN = @p_ldap_dn
				WHERE PK_STAFF = @p_item_id
			Set @item_id = @p_item_id
		End
	Else 
		Begin
			Insert into T_USER_STAFF(FK_UNIT,FK_POSITION,C_CODE,C_NAME,C_BIRTHDAY,C_ADDRESS,C_EMAIL,C_TEL_LOCAL,C_TEL,C_TEL_MOBILE,C_TEL_HOME,C_FAX,C_USERNAME,C_PASSWORD,C_ORDER,C_STATUS,C_ROLE, C_DN) 
			Values(@p_unit_id,@p_position_id,@p_code,@p_name,@p_birthday,@p_address,@p_email,@p_tel_local,@p_tel_office,@p_tel_mobile,@p_tel_home,@p_fax,@p_username,@p_password,@p_order,@p_status,@p_role,@p_ldap_dn)
			Set @item_id = @@IDENTITY
		End

	-- Dieu kien loc khi sap xep lai thu tu
	If @p_unit_id Is Null
		Set @v_filter = ' FK_UNIT IS NULL '
	Else
		Set @v_filter = ' FK_UNIT=' + Convert(varchar,@p_unit_id)

	If @p_order Is Not Null And @p_order>0
		Exec SP_ReOrder 'T_USER_STAFF', 'C_ORDER', 'PK_STAFF', @item_id, @p_order,@v_filter

	Select @v_internal_order = isnull(C_INTERNAL_ORDER,'') From T_USER_UNIT Where PK_UNIT=@p_unit_id

	Update T_USER_STAFF Set C_INTERNAL_ORDER = @v_internal_order + dbo.f_FormatOrder(C_ORDER,5) 
		Where FK_UNIT = @p_unit_id


	Select @item_id NEW_ID

	COMMIT TRANSACTION
RETURN 0
GO
---------------------------------------------------------
--+Thu tuc USER_StaffDelete
--+Createn by :Nguyen Tai Ba
--+Xoa mot/nhieu can bo khoi danh sach 
--+(Xoa d/s cac can bo thoa man dk xoa va tra lai ten, id cua can can bo khong xoa duoc)
--+ Dieu kien xoa.
---+ 1. Can bo khong la quan tri cua mot APP.
---+ 2. Can bo khong co quyen tren mot Modul, function cua APP nao
---+ 3. Can bo khi dang dang nhap khong the xoa chinh minh.
CREATE PROCEDURE dbo.USER_StaffDelete
	@p_list_item_id nvarchar(4000)
AS
	Declare 
			@p_item_id int,
			@mPosition int,
			@start int,
			@id1 int,
			@id2 int,
			@id int,
			@p_user_name nvarchar(30),
			@p_return_name nvarchar(4000),
			@p_return_id nvarchar(100)
	SET NOCOUNT ON
	--+Vi tri bat dau lay
	select @p_return_name=''
	select @p_return_id=''
	select @start = 1 
		While (@p_list_item_id <>'')
			Begin
				select @id1=0		
				select @id2=0		
				select @id=0			
				Select @mPosition = charindex(',',@p_list_item_id)
				if @mPosition >0
					Begin
						Select @p_item_id = substring(@p_list_item_id,@start,@mPosition-@start)
						Select @p_list_item_id = substring(@p_list_item_id,@mPosition+1,Len(@p_list_item_id)-@mPosition)
					End
				Else --Trong truong hop chi con mot nguoi
					Begin
						select @p_item_id =@p_list_item_id
						select @p_list_item_id =''
					End
				-- Kiem tra rang buoc kieu khoa ngoai
				Select TOP 1 @id1 = FK_STAFF From T_USER_ENDUSER Where FK_STAFF = @p_item_id
				Select TOP 1 @id2 = FK_STAFF From T_USER_APPLICATION_ADMIN Where FK_STAFF = @p_item_id
				set @id = @id1 + @id2
				If (@id = 0)							
					Begin
						SET XACT_ABORT ON
						BEGIN TRANSACTION		
						Delete T_USER_STAFF Where PK_STAFF = @p_item_id
						COMMIT TRANSACTION
					End
				else
					Begin
						--Ten nguoi khong xoa duoc
						Select @p_user_name = C_NAME From T_USER_STAFF Where PK_STAFF = @p_item_id
						select @p_return_name = @p_return_name + @p_user_name + ','
						Select @p_return_id = @p_return_id + convert(char(10),@p_item_id) + ','
					End
			End
	select @p_return_name RET_ERROR_NAME, @p_return_id RET_ERROR_ID
	return 0
GO
--USER_StaffDelete '9,37,78'
--select * from t_user_staff
/*Begin
	Select @p_user_name = C_NAME From T_USER_STAFF Where PK_STAFF = @p_item_id
	select @p_return_value = @p_return_value + @p_user_name + ','
End*/
---------------------------------------------------------------------------------
--+Thu tuc USER_UserGetDetail
--+Createn by :Nguyen Tai Ba
--+Lay thong tin chi tiet cua mot nguoi su dung
Create Procedure dbo.USER_UserGetDetail
	@p_user_id int
As
	SET NOCOUNT ON
	Select 	
			C_NAME,
			C_USERNAME,
			C_PASSWORD,
			C_ADDRESS,
			C_TEL,
			C_EMAIL,
			C_STATUS,
			C_DN
	From T_USER_STAFF 
	Where PK_STAFF =@p_user_id
Go

---------------------------------------------------
--+Thu tuc USER_UserUpdate
--+Createn by :Nguyen Tai Ba
--Cap nhat thong tin mot nguoi su dung
CREATE PROCEDURE dbo.USER_UserUpdate
	@p_user_id int,
	@p_name nvarchar(1000),
	@p_address nvarchar(1000),
	@p_tel nvarchar(100),
	@p_email nvarchar(1000),
	@p_username nvarchar(100),
	@p_password nvarchar(100)	
AS
	Declare	@count int

	SET NOCOUNT ON
	-- Dat che do tu dong rollback toan bo transaction khi co loi
	Select @count =count(*) From T_USER_STAFF Where PK_STAFF<> @p_user_id And C_USERNAME = @p_username
	If @Count > 0
		Begin
			Select 'Ten dang nhap da ton tai trong danh sach' RET_ERROR
			return -100
		End
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	Update T_USER_STAFF SET
		C_NAME = @p_name, 
		C_ADDRESS = @p_address,
		C_TEL_LOCAL = @p_tel,
		C_EMAIL = @p_email,
		C_USERNAME = @p_username,
		C_PASSWORD = @p_password
	WHERE PK_STAFF = @p_user_id
	COMMIT TRANSACTION
	Return 0
GO
--USER_UserUpdate 9,'Nguyen Tai Ba','509 HHT','8329036','ntba80@yahoo.com','dqtu','dqtu'
--Select count(*) From T_USER_STAFF Where PK_STAFF<> 9 And C_USERNAME = 'dqtu'
--Select rtrim(CONVERT(CHAR(2),DATEPART(dd,C_BIRTHDAY)))+'/'+rtrim(CONVERT(CHAR(2),DATEPART(mm,C_BIRTHDAY)))+'/'+rtrim(CONVERT(CHAR(4),DATEPART(yy,C_BIRTHDAY))) From T_USER_STAFF
--UPDATE T_USER_UNIT SET C_CODE = 'BTTGT' WHERE C_ORDER =15

---------------------------------------------------------------------------------------------------------------------------------------
--+Thu tuc USER_BirthdayStaffGetAll
--+Createn by :Hoang Khac Duc
--+Lay danh sach ngay sinh cua cac can bo theo tung don vi cap 1
Create Procedure USER_BirthdayStaffGetAll
	@p_status int  -- Tinh trang (-1: lay tat ca, 1-Hoat dong; 9-Khong hoat dong)
As
	SET NOCOUNT ON
	if @p_status < 0
		Select a.PK_STAFF,
			   a.C_NAME,
			   rtrim(CONVERT(CHAR(2),DATEPART(d,a.C_BIRTHDAY)))+'/'+rtrim(CONVERT(CHAR(2),DATEPART(m,a.C_BIRTHDAY)))+'/'+rtrim(CONVERT(CHAR(4),DATEPART(yy,a.C_BIRTHDAY))) As C_BIRTHDAY,
			   dbo.f_GetUnitLevelOneID(a.PK_STAFF) AS C_UNIT_LEVEL1_ID,
			   dbo.f_GetUnitLevelOneName(a.PK_STAFF) AS C_UNIT_LEVEL1_NAME,
			   dbo.f_GetUnitName(a.PK_STAFF) AS C_UNIT_NAME
			From T_USER_STAFF a
			Order by a.C_INTERNAL_ORDER,a.C_NAME
	else
		Select a.PK_STAFF,
			   a.C_NAME,
			   rtrim(CONVERT(CHAR(2),DATEPART(d,a.C_BIRTHDAY)))+'/'+rtrim(CONVERT(CHAR(2),DATEPART(m,a.C_BIRTHDAY)))+'/'+rtrim(CONVERT(CHAR(4),DATEPART(yy,a.C_BIRTHDAY))) As C_BIRTHDAY,
			   dbo.f_GetUnitLevelOneID(a.PK_STAFF) AS C_UNIT_LEVEL1_ID,
			   dbo.f_GetUnitLevelOneName(a.PK_STAFF) AS C_UNIT_LEVEL1_NAME,
			   dbo.f_GetUnitName(a.PK_STAFF) AS C_UNIT_NAME
			From T_USER_STAFF a
			Where a.C_GROUP is null Or a.C_GROUP = 0
			Order by a.C_INTERNAL_ORDER,a.C_NAME

	Return 0
GO
--update T_user_staff set c_birthday = null where PK_STAFF = 28
--select * from T_user_staff where PK_STAFF = 28
--USER_BirthdayStaffGetAll 1