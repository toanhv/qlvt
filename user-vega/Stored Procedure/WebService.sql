if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_CheckLogin]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_CheckLogin]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_CheckUserFromApp]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_CheckUserFromApp]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_IsLogged]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_IsLogged]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_IsLoggedFromApp]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_IsLoggedFromApp]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UpdateLastTime]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_UpdateLastTime]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_EndUserCheckBelongToApp]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_EndUserCheckBelongToApp]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_Logout]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_Logout]
Go
----------------------

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_SetPermisionOnFunction]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_SetPermisionOnFunction]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_CheckPermissionOnModul]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_CheckPermissionOnModul]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_CheckPermissionOnFunction]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_CheckPermissionOnFunction]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[User_GetAllModulGrantedForUser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[User_GetAllModulGrantedForUser]
Go
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[User_GetAllPublicModul]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[User_GetAllPublicModul]
Go
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[User_GetAllFunctionGrantedForUser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[User_GetAllFunctionGrantedForUser]
Go
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[User_GetAllPublicFunction]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[User_GetAllPublicFunction]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetPersonalInfoOfAllStaff]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetPersonalInfoOfAllStaff]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetPersonalInfoOfAllEndUser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetPersonalInfoOfAllEndUser]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetNewEndUserOfSingleApp]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetNewEndUserOfSingleApp]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetDetailInfoOfAllUnit]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetDetailInfoOfAllUnit]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetAllGroupOfApplication]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetAllGroupOfApplication]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GroupGetAllByMember]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GroupGetAllByMember]
Go

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetAllPersonalHaveBirthdayOnCurrenday]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetAllPersonalHaveBirthdayOnCurrenday]
GO

--------------------------------------------------------------------------------------------------------------------------------------------
-- Thu tuc USER_CheckLogin kiem tra U/P do NSD xac dinh tai man hinh dang nhap co ton tai hay khong 
-- Neu co tra lai mot record set voi cac column PK_STAFF, PK_ENDUSER, C_NAME, C_ISA_USER_ADMIN, C_ISA_APP_ADMIN

Create Procedure USER_CheckLogin
	@p_username nvarchar(100),
	@p_password nvarchar(100),
	@p_app_code nvarchar(100)
As
	Declare @staff_id int, @enduser_id int, @staff_name nvarchar(100), @is_isa_user_admin tinyint, @is_isa_app_admin tinyint
	SET NOCOUNT ON
	Set @staff_id = 0 
	Set @is_isa_app_admin = 0
	If Rtrim(@p_app_code) = 'EFY-USER'
		-- Xac minh xem Ten/Mat khau dang nhap co hop le khong
		Select  @staff_id = a.PK_STAFF,
				@enduser_id = 0,
				@staff_name = a.C_NAME,
				@is_isa_user_admin = a.C_ROLE 
			From T_USER_STAFF a
			Where  a.C_USERNAME = @p_username 
				 And a.C_PASSWORD = @p_password
	Else
		-- Xac minh xem Ten/Mat khau dang nhap co hop le khong
		Select  @staff_id = a.PK_STAFF,
				@enduser_id = b.PK_ENDUSER,
				@staff_name = a.C_NAME,
				@is_isa_user_admin = a.C_ROLE 
			From T_USER_STAFF a, T_USER_ENDUSER b
			Where a.PK_STAFF = b.FK_STAFF And 
				 a.C_USERNAME = @p_username 
				 And a.C_PASSWORD = @p_password
				 And b.FK_APPLICATION = (Select PK_APPLICATION From T_USER_APPLICATION Where C_CODE=@p_app_code)

	-- Neu TEN/MAT KHAU hop le thi xac dinh xem nguoi dang nhap co phai la quan tri cua it nhap mot APP hay khong
	-- Va tra lai Recordset
	If @staff_id>0
		Begin
			Select TOP 1 @is_isa_app_admin = 1 
				From T_USER_APPLICATION_ADMIN 
				Where FK_STAFF = @staff_id
			Select @staff_id PK_STAFF, @enduser_id PK_ENDUSER, @staff_name C_NAME, @is_isa_user_admin C_ISA_USER_ADMIN, @is_isa_app_admin C_ISA_APP_ADMIN
			Return 0
		End
Go
/*
USER_CheckLogin  'bant','bant', 'EFY-USER'
select * from t_user_staff
select * from t_user_enduser
*/

--------------------------------------------------------------------------------------------------------------------------------------------
-- Thu tuc USER_CheckUserFromApp kiem tra U/P va tra lai U/P de vao 1 ung dung khac

Create Procedure USER_CheckUserFromApp
	@p_username nvarchar(100),
	@p_password nvarchar(100),
	@p_app_code nvarchar(100)
As
	SET NOCOUNT ON
	-- Xac minh xem Ten/Mat khau dang nhap co hop le khong
	Select  a.PK_STAFF,
			b.PK_ENDUSER,
			a.C_NAME,
			b.C_APP_USERNAME,
			b.C_APP_PASSWORD
		From T_USER_STAFF a, T_USER_ENDUSER b
		Where a.PK_STAFF = b.FK_STAFF And 
			 a.C_USERNAME = @p_username 
			 And a.C_PASSWORD = @p_password
			 And b.FK_APPLICATION = (Select PK_APPLICATION From T_USER_APPLICATION Where C_CODE=@p_app_code)
	SET NOCOUNT OFF
	Return
Go
/*
USER_CheckUserFromApp  'bant','bant', 'EFY-USER'
select * from t_user_staff
select * from t_user_enduser
*/


--------------------------------------------------------------------------------------------------------------------------------------------
-- Kiem tra tinh trang dang nhap dua vao dia chi IP va ma ISA-APPLICATION
Create Procedure USER_IsLogged
	@p_ip_address 	nvarchar(50),  	-- Dia chi IP cua may chay ISA-APP
	@p_app_code 	nvarchar(15),	-- Ma cua ISA-APP	
	@p_timeout		int				-- Khoang thoi gian (tinh theo giay) de xac dinh xem da vuot qua timeout hay khong
As
	Declare 
		@v_user_id int,
		@v_count int,
		@v_xml_string nvarchar(4000)
	
	SET NOCOUNT ON
	Select @v_count=Count(*)
		From T_USER_LOGON a
		Where C_IP_ADDRESS = rtrim(@p_ip_address) AND dateadd(s,@p_timeout,C_LAST_TIME)>=getdate() AND FK_STAFF<>'0'
	-- Neu khong co row nao thoa man thi co nghia la chua dang nhap (se ket thuc va tra lai gia tri -1)
	If @v_count=0
		Begin
			Set @v_xml_string = '-1'
			Select @v_xml_string FK_STAFF, 'xxx' C_NAME
			Return 0
		End 
	-- Doan ma duoi day nham muc dich xac dinh USER_ID
	-- Neu chi co 01 row thoa man thi lay luon USER_ID cua row do
	If @v_count=1
		Begin
			Select a.FK_STAFF, b.C_NAME
				From T_USER_LOGON a, T_USER_STAFF b
				Where C_IP_ADDRESS = rtrim(@p_ip_address) 
					AND dateadd(s,@p_timeout,C_LAST_TIME)>=getdate()
					AND FK_STAFF<>'0'
					And a.FK_STAFF = b.PK_STAFF 
			Return 0
		End 

	-- Neu co nhieu hon 1 row thoa man thi kiem tra them dieu kien C_APP_CODE=p_app_code
	Select @v_count=Count(*)
		From T_USER_LOGON a
		Where Rtrim(C_IP_ADDRESS) = rtrim(@p_ip_address) 
			AND dateadd(s,@p_timeout,C_LAST_TIME)>=getdate() 
			And Rtrim(C_APP_CODE)=rtrim(@p_app_code)
			AND FK_STAFF<>'0'

	-- Neu chi co 01 row thoa man them dieu kien C_APP_CODE=p_app_code  thi lay luon USER_ID cua row do
	/*If @v_count=1
		Begin
			Select @v_user_id = FK_STAFF
				From T_USER_LOGON 
				Where C_IP_ADDRESS = rtrim(@p_ip_address) AND dateadd(s,@p_timeout,C_LAST_TIME)>=getdate() And C_APP_CODE=rtrim(@p_app_code)
			Set @v_xml_string = @v_user_id
			Select @v_xml_string FK_STAFF
			Return 0
		End 
	*/

	-- Neu co hon 1 row thoa man them dieu kien C_APP_CODE=p_app_code  thi tra lai mot recorde set chua danh sach USER_ID dang dang nhap
	-- vao p_app_code
	If @v_count>=1
		Begin
			Select TOP 1 a.FK_STAFF, b.C_NAME
				From T_USER_LOGON a, T_USER_STAFF b
				Where a.C_IP_ADDRESS = rtrim(@p_ip_address) AND dateadd(s,@p_timeout,a.C_LAST_TIME)>=getdate() 
					  And a.C_APP_CODE=rtrim(@p_app_code) And a.FK_STAFF = b.PK_STAFF 
						AND a.FK_STAFF<>'0'
				Order By a.C_LAST_TIME DESC
			Return 0
		End 
	-- Neu khong co row nao thoa man them dieu kien C_APP_CODE=p_app_code thi tra lai mot recorde set chua danh sach cac
	-- USER_ID dang dang nhap tai may p_ip_address
	If @v_count=0
		Begin
			Select TOP 1 a.FK_STAFF,b.C_NAME
				From T_USER_LOGON a, T_USER_STAFF b
				Where a.C_IP_ADDRESS = rtrim(@p_ip_address) AND dateadd(s,@p_timeout,a.C_LAST_TIME)>=getdate() 
					  And a.FK_STAFF = b.PK_STAFF AND a.FK_STAFF<>'0'
				Order By a.C_LAST_TIME DESC
			Return 0
		End 
	SET NOCOUNT OFF
Go
--------------------------------------------------------------------------------------------------------------------------------------------
/*
Exec USER_IsLogged  '192.9.168.101','ISA-WEB',1800
*/

--------------------------------------------------------------------------------------------------------------------------------------------
-- Kiem tra tinh trang dang nhap dua vao dia chi IP va ma ISA-APPLICATION
Create Procedure USER_IsLoggedFromApp
	@p_ip_address 	nvarchar(50),  	-- Dia chi IP cua may chay ISA-APP
	@p_timeout		int				-- Khoang thoi gian (tinh theo giay) de xac dinh xem da vuot qua timeout hay khong
As
	SET NOCOUNT ON
	Select  A.FK_STAFF,
			A.C_APP_CODE,
			B.C_NAME,
			B.C_USERNAME,
			B.C_PASSWORD
		From T_USER_LOGON A
			LEFT JOIN T_USER_STAFF B ON A.FK_STAFF = B.PK_STAFF
		Where A.C_IP_ADDRESS = rtrim(@p_ip_address) AND dateadd(s,@p_timeout,A.C_LAST_TIME)>=getdate()
	Return 0
	SET NOCOUNT OFF
Go
--------------------------------------------------------------------------------------------------------------------------------------------
/*
Exec USER_IsLoggedFromApp  '192.168.9.200',1800
*/

--------------------------------------------------------------------------------------------------------------------------------------------
-- Cap nhat lan truy cap cuoi cung cua mot USER_ID vao mot ISA-App tai mot may tinh nhat dinh
Create Procedure USER_UpdateLastTime
	@p_ip_address 	nvarchar(50),  	-- Dia chi IP cua may chay ISA-APP
	@p_app_code 	nvarchar(15),	-- Ma cua ISA-APP	
	@p_staff_id		int				-- Ma cua Staff (gia tri cua cot T_USER_STAFF.PK_STAFF)
As
	Declare 
		@v_user_id int,
		@v_count int,
		@v_xml_string nvarchar(4000)
	SET NOCOUNT ON
	Update T_USER_LOGON Set C_LAST_TIME=getdate()
		Where C_IP_ADDRESS = @p_ip_address And C_APP_CODE=@p_app_code And FK_STAFF=@p_staff_id

	If @@ROWCOUNT<=0
		Insert T_USER_LOGON(C_IP_ADDRESS,C_APP_CODE,FK_STAFF, C_LAST_TIME) Values (@p_ip_address, @p_app_code, @p_staff_id, getdate())
	SET NOCOUNT OFF
	Return 0	
Go
--------------------------------------------------------------------------------------------------------------------------------------------
/*
Exec USER_UpdateLastTime '05D6E696-CB10-4699-AE2E-E1D7B1B263FE','EFY-USER',1
Select * From T_USER_LOGON Where C_IP_ADDRESS = '05D6E696-CB10-4699-AE2E-E1D7B1B263FE'

Exec USER_UpdateLastTime  '192.168.9.175','ISA-DOCLIB',9
Exec USER_UpdateLastTime  '192.168.9.175','ISA-WEB',9
Exec USER_UpdateLastTime  '192.168.9.175','ISA-TASK',9
Exec USER_UpdateLastTime  '192.168.9.175','ISA-DOCLIB',10
select * from t_user_logon
delete t_user_logon where FK_STAFF=10
Exec USER_IsLogged  '192.168.9.175','ISA-WEB',36000
*/

--------------------------------------------------------------------------------------------------------------------------------------------
-- Thu tuc USER_EndUserCheckBelongToApp kiem tra xem @p_enduser_id co phai la end-user cua  
-- Neu co tra lai mot record set voi cac column PK_STAFF, PK_ENDUSER, C_NAME, C_ISA_USER_ADMIN, C_ISA_APP_ADMIN

Create Procedure USER_EndUserCheckBelongToApp
	@p_staff_id int,
	@p_app_code nvarchar(100)
As
	Declare @staff_id int, @enduser_id int, @staff_name nvarchar(100), @is_isa_user_admin tinyint, @is_isa_app_admin tinyint
	SET NOCOUNT ON
	Set @staff_id = 0 
	Set @is_isa_app_admin = 0

	If Rtrim(@p_app_code) = 'EFY-USER'
		-- Xac minh co staff nao co ID la @p_staff_id hay khong
		Select  @staff_id = a.PK_STAFF,
				@enduser_id = 0,
				@staff_name = a.C_NAME,
				@is_isa_user_admin = a.C_ROLE 
			From T_USER_STAFF a
			Where  a.PK_STAFF = @p_staff_id  
	Else
		-- xac minh xem @p_staff_id co phai la end-user cua @p_app_code hay khong
		Select  @staff_id = a.PK_STAFF,
				@enduser_id = b.PK_ENDUSER,
				@staff_name = a.C_NAME,
				@is_isa_user_admin = a.C_ROLE 
			From T_USER_STAFF a, T_USER_ENDUSER b
			Where a.PK_STAFF = b.FK_STAFF And 
				 a.PK_STAFF = @p_staff_id 
				 And b.FK_APPLICATION = (Select PK_APPLICATION From T_USER_APPLICATION Where C_CODE=@p_app_code)
	-- Neu OK thi xac dinh xem nguoi dang nhap co phai la quan tri cua it nhap mot APP hay khong
	-- Va tra lai Recordset
	If @staff_id>0
		Begin
			Select TOP 1 @is_isa_app_admin = 1 
				From T_USER_APPLICATION_ADMIN 
				Where FK_STAFF = @staff_id
			Select @staff_id PK_STAFF, @enduser_id PK_ENDUSER, @staff_name C_NAME, @is_isa_user_admin C_ISA_USER_ADMIN, @is_isa_app_admin C_ISA_APP_ADMIN
			Return 0
		End
Go
/*
Exec USER_EndUserCheckBelongToApp 9,'EFY-USER'
*/

--------------------------------------------------------------------------------------------------------------------------------------------
-- Xoa thong tin dang nhap trong T_USER_LOGON
Create Procedure USER_Logout
	@p_ip_address 	nvarchar(50),  	-- Dia chi IP cua may chay ISA-APP
	@p_app_code 	nvarchar(15),	-- Ma cua ISA-APP	
	@p_staff_id		int				-- Ma cua Staff (gia tri cua cot T_USER_STAFF.PK_STAFF)
As
	SET NOCOUNT ON
	Delete T_USER_LOGON 
		Where rtrim(C_IP_ADDRESS) = rtrim(@p_ip_address) And rtrim(C_APP_CODE)=rtrim(@p_app_code) And FK_STAFF=@p_staff_id
Go
-- Exec USER_Logout '192.168.9.175','EFY-USER',10

--------------------------------------------------------------------------------------------------------------------------
--Dat quyen cua nguoi su dung tren mot FUNCTION, MODUL
--+ Created by Nguyen Van Khanh

Create  Procedure  dbo.USER_SetPermisionOnFunction
	@p_staff_id int,  -- ID cua nguoi su dung (PK_STAFF)
	@p_app_code nvarchar(100), -- ma cua application
	@p_modul_code nvarchar(100), -- ma cua modul
	@p_function_code nvarchar(100) -- ma chuc nang
As
	Declare @app_id int,
			@module_id int,
			@function_id int,
			@user_id int,
			@permision_on_modul int,
			@permision_on_function int
	SET NOCOUNT ON
	Set @app_id = (Select PK_APPLICATION From T_USER_APPLICATION Where C_CODE = @p_app_code)
	Set @module_id = (Select PK_MODUL From T_USER_MODUL Where FK_APPLICATION = @app_id And C_CODE = @p_modul_code)
	Set @function_id = (Select PK_FUNCTION From T_USER_FUNCTION Where FK_APPLICATION = @app_id And FK_MODUL = @module_id And C_CODE = @p_function_code)
	Set @user_id = (Select PK_ENDUSER From T_USER_ENDUSER Where FK_APPLICATION = @app_id And FK_STAFF = @p_staff_id)	
	
	Set @permision_on_modul = (Select Count(*) From T_USER_MODUL_BY_ENDUSER Where FK_MODUL = @module_id And FK_ENDUSER = @user_id)
	Set @permision_on_function = (Select Count(*) From T_USER_FUNCTION_BY_ENDUSER Where FK_FUNCTION = @function_id And FK_ENDUSER = @user_id)

	-- Kiem tra neu chua co quyen thi them quyen cho nguoi su dung hien thoi
	If @permision_on_modul = 0 And @module_id Is Not Null 
		Insert Into T_USER_MODUL_BY_ENDUSER(FK_ENDUSER,FK_MODUL) Values(@user_id,@module_id)
	If @permision_on_function = 0  And @function_id Is Not Null
		Insert Into T_USER_FUNCTION_BY_ENDUSER(FK_FUNCTION,FK_ENDUSER) Values(@function_id,@user_id)

	Return 0
GO
-- USER_SetPermisionOnFunction 1858,'ISA-KTXH','aaa','aaa'

--------------------------------------------------------------------------------------------------------------------------
--Kiem tra quyen cua nguoi su dung tren mot MODUL
--+ Created by Nguyen Tuan Anh

Create  Procedure  dbo.USER_CheckPermissionOnModul
	@p_staff_id int,  -- ID cua nguoi dang nhap hien thoi (PK_STAFF)
	@p_app_code nvarchar(100), -- ma cua application
	@p_modul_code nvarchar(100) -- ma cua modul
As
	Declare @app_id int, @enduser_id int, @modul_id int, @count int, @filter nvarchar(500)
	SET NOCOUNT ON
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	-- @p_staff_id la ID cua STAFF, doan ma duoi day se xac dinh ID cua ENDUSER trong Application
	Set @filter = ' FK_APPLICATION=' + convert(varchar,@app_id)
	Exec SP_GetOtherValueFromKeyValue 'T_USER_ENDUSER', 'FK_STAFF', @p_staff_id, 'PK_ENDUSER', @enduser_id OUTPUT, @filter
	-- Lay ID cua modul
	Exec SP_GetOtherValueFromKeyValue 'T_USER_MODUL', 'C_CODE', @p_modul_code, 'PK_MODUL', @modul_id OUTPUT

	-- Neu la modul cong cong thi OK
	Select @count= count(*) From T_USER_MODUL Where PK_MODUL=@modul_id And C_PUBLIC=1
	If @count>0
		Begin
			Select 1 PERMISION_ON_MODUL
			Return 0
		End

	-- Neu da duoc ban quyen truc tiep tren modul thi OK
	Select @count= count(*) From T_USER_MODUL_BY_ENDUSER Where FK_ENDUSER = @enduser_id And FK_MODUL=@modul_id
	If @count>0
		Begin
			Select 1 PERMISION_ON_MODUL
			Return 0
		End

	-- Neu da duoc ban quyen gian tiep qua cac nhom ma @p_staff_id la thanh vien thi OK
	Select @count= count(*) From T_USER_MODUL_BY_GROUP 
		Where FK_GROUP In (Select FK_GROUP From T_USER_GROUP_BY_ENDUSER Where FK_ENDUSER = @enduser_id)
				And FK_MODUL=@modul_id
	If @count>0
		Begin
			Select 1 PERMISION_ON_MODUL
			Return 0
		End
	-- Khong co quyen
	Select -1 PERMISION_ON_MODUL
	Return
Go
--exec USER_CheckPermissionOnModul 9,'ISA-DOCLIB','DOC_SEARCH'

----------------------------------------------------------------------------------------
--Kiem tra quen cua nguoi su dung tren mot chuc nang
--Created by Nguyen Tuan Anh
--Last Update: Hoang Khac Duc: 17h00 - 20/07/2004
--Note: Hieu chinh lai khi co hai chuc nang trung nhau ve ma

Create Procedure dbo.USER_CheckPermissionOnFunction
	@p_staff_id int,
	@p_app_code nvarchar(100),
	@p_function_code nvarchar(100)
As
	Declare @app_id int, @enduser_id int, @function_id int, @count int, @filter nvarchar(500)
	SET NOCOUNT ON
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	-- @p_staff_id la ID cua STAFF, doan ma duoi day se xac dinh ID cua ENDUSER trong Application
	Set @filter = ' FK_APPLICATION=' + convert(varchar,@app_id)
	Exec SP_GetOtherValueFromKeyValue 'T_USER_ENDUSER', 'FK_STAFF', @p_staff_id, 'PK_ENDUSER', @enduser_id OUTPUT, @filter
	-- Lay ID cua function
	Exec SP_GetOtherValueFromKeyValue 'T_USER_FUNCTION', 'C_CODE', @p_function_code, 'PK_FUNCTION', @function_id OUTPUT, @filter

	-- Neu la modul cong cong thi OK
	Select @count= count(*) From T_USER_FUNCTION Where PK_FUNCTION=@function_id And C_PUBLIC=1
	If @count>0
		Begin
			Select 1 PERMISION_ON_FUNCTION
			Return 0
		End

	-- Neu da duoc ban quyen truc tiep tren modul thi OK
	Select @count= count(*) From T_USER_FUNCTION_BY_ENDUSER Where FK_ENDUSER = @enduser_id And FK_FUNCTION=@function_id
	If @count>0
		Begin
			Select 1 PERMISION_ON_FUNCTION
			Return 0
		End

	-- Neu da duoc ban quyen gian tiep qua cac nhom ma @p_staff_id la thanh vien thi OK
	Select @count= count(*) From T_USER_FUNCTION_BY_GROUP 
		Where FK_GROUP In (Select FK_GROUP From T_USER_GROUP_BY_ENDUSER Where FK_ENDUSER = @enduser_id)
				And FK_FUNCTION=@function_id
	If @count>0
		Begin
			Select 1 PERMISION_ON_FUNCTION
			Return 0
		End
	-- Khong co quyen
	Select -1 PERMISION_ON_FUNCTION
	Return 0

Go
--Execute USER_CheckPermissionOnFunction 38,'ISA-REPORTLIB','LIST_MANAGER_DELETE'
-- Execute USER_CheckPermissionOnFunction 9,'ISA-DOCLIB','DOC_MANAGE_ADD'
----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua MA cua tat cac cac MODUL ma NSD @p_user_id co quyen truy nhap
-- Created by:Nguyen Tuan Anh

Create procedure User_GetAllModulGrantedForUser
	@p_app_code nvarchar(50),	-- Ma cua phan mem ung dung goi Webservice
	@p_staff_id int				-- Ma cua NSD, duoc su dung trong cac dieu kien so sanh PK_STAFF=, FK_STAFF=
AS	
	Declare @app_id int, @enduser_id int, @filter nvarchar(500)
	SET NOCOUNT ON
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	-- @p_staff_id la ID cua STAFF, doan ma duoi day se xac dinh ID cua ENDUSER trong Application
	Set @filter = ' FK_APPLICATION=' + convert(varchar,@app_id)
	Exec SP_GetOtherValueFromKeyValue 'T_USER_ENDUSER', 'FK_STAFF', @p_staff_id, 'PK_ENDUSER', @enduser_id OUTPUT, @filter

	Create table #T_MODUL(C_CODE nvarchar(100))

	-- Lay danh sach cac MODUL ma @p_staff_id duoc ban quyen truc tiep
	Insert Into #T_MODUL 
		Select C_CODE 
			From T_USER_MODUL
			Where PK_MODUL In (Select FK_MODUL From T_USER_MODUL_BY_ENDUSER Where FK_ENDUSER = @enduser_id)
				And FK_APPLICATION = @app_id
				And C_STATUS = 1

	-- Lay danh sach cac MODUL ma @p_staff_id duoc ban quyen gian tiep qua cac nhom ma @p_staff_id la thanh vien
	Insert Into #T_MODUL 
		Select C_CODE 
			From T_USER_MODUL
			Where PK_MODUL In (Select FK_MODUL From T_USER_MODUL_BY_GROUP Where FK_GROUP In (Select FK_GROUP From T_USER_GROUP_BY_ENDUSER Where FK_ENDUSER = @enduser_id))
				And FK_APPLICATION = @app_id
				And C_STATUS = 1

	-- Tra lai RecordeSet (da loai bo cac MA trung lap)
	Select Distinct C_CODE From #T_MODUL
	Return
GO
/*
	Select * From T_USER_APPLICATION
	Select * From T_USER_MODUL
	Select * From T_USER_MODUL_BY_ENDUSER
	Select * From T_USER_ENDUSER
	Exec User_GetAllModulGrantedForUser 'ISA-DOCLIB',9
*/

----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua MA cua tat cac cac MODUL cong cong cua mot ung dung
-- Created by:Nguyen Tuan Anh

Create procedure User_GetAllPublicModul
	@p_app_code nvarchar(50)	-- Ma cua phan mem ung dung goi Webservice
AS	
	Declare @app_id int, @enduser_id int
	SET NOCOUNT ON
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	Select C_CODE From T_USER_MODUL Where FK_APPLICATION = @app_id And C_PUBLIC=1 ORDER BY C_ORDER
	Return
GO

----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua MA cua tat cac cac FUNCTION ma NSD @p_staff_id co quyen truy nhap
-- Created by:Nguyen Tuan Anh

Create procedure User_GetAllFunctionGrantedForUser
	@p_app_code nvarchar(50),	-- Ma cua phan mem ung dung goi Webservice
	@p_staff_id int				-- Ma cua NSD, duoc su dung trong cac dieu kien so sanh PK_STAFF=, FK_STAFF=
AS	
	Declare @app_id int, @enduser_id int, @filter nvarchar(500)
	SET NOCOUNT ON
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	-- @p_staff_id la ID cua STAFF, doan ma duoi day se xac dinh ID cua ENDUSER trong Application
	Set @filter = ' FK_APPLICATION=' + convert(varchar,@app_id)
	Exec SP_GetOtherValueFromKeyValue 'T_USER_ENDUSER', 'FK_STAFF', @p_staff_id, 'PK_ENDUSER', @enduser_id OUTPUT, @filter

	Create table #T_FUNCTION(C_CODE nvarchar(100))

	-- Lay danh sach cac FUNCTION ma @p_staff_id duoc ban quyen truc tiep
	Insert Into #T_FUNCTION 
		Select C_CODE 
			From T_USER_FUNCTION
			Where PK_FUNCTION In (Select FK_FUNCTION From T_USER_FUNCTION_BY_ENDUSER Where FK_ENDUSER = @enduser_id)
				And FK_APPLICATION = @app_id
				And C_STATUS = 1

	-- Lay danh sach cac FUNCTION ma @p_staff_id duoc ban quyen gian tiep qua cac nhom ma @p_staff_id la thanh vien
	Insert Into #T_FUNCTION 
		Select C_CODE 
			From T_USER_FUNCTION
			Where PK_FUNCTION In (Select FK_FUNCTION From T_USER_FUNCTION_BY_GROUP Where FK_GROUP In (Select FK_GROUP From T_USER_GROUP_BY_ENDUSER Where FK_ENDUSER = @enduser_id))
				And FK_APPLICATION = @app_id
				And C_STATUS = 1

	-- Tra lai RecordeSet (da loai bo cac MA trung lap)
	Select Distinct C_CODE From #T_FUNCTION
	Return
GO
/*
	Select * From T_USER_APPLICATION
	Select * From T_USER_MODUL
	Select * From T_USER_MODUL_BY_ENDUSER
	Select * From T_USER_ENDUSER
	Exec User_GetAllFunctionGrantedForUser 'ISA-DOCLIB',9
*/

----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua MA cua tat cac cac Function cong cong cua mot ung dung
-- Created by:Nguyen Tuan Anh

Create procedure User_GetAllPublicFunction
	@p_app_code nvarchar(50)	-- Ma cua phan mem ung dung goi Webservice
AS	
	Declare @app_id int, @enduser_id int
	SET NOCOUNT ON
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	Select C_CODE From T_USER_FUNCTION Where FK_APPLICATION = @app_id And C_PUBLIC=1 ORDER BY C_ORDER
	Return
GO

----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua thong tin ca nhan cua tat ca can bo (staff)
-- Created by:Nguyen Tuan Anh

Create Procedure USER_GetPersonalInfoOfAllStaff
As
	SET NOCOUNT ON
	Select A.PK_STAFF,
			A.C_NAME,
			A.C_CODE,
			A.FK_UNIT,
			B.C_CODE AS C_POSITION_CODE,
			B.C_NAME AS C_POSITION_NAME,
			C.C_CODE AS C_POSITION_GROUP_CODE,
			A.C_ADDRESS,
			A.C_EMAIL,
			A.C_TEL,
			A.C_TEL_MOBILE,
			A.C_ORDER
		From T_USER_STAFF A
			Left Join T_USER_POSITION B On A.FK_POSITION = B.PK_POSITION
			Left Join T_USER_POSITION_GROUP C On B.FK_POSITION_GROUP = C.PK_POSITION_GROUP
			Left Join T_USER_UNIT D On A.FK_UNIT = D.PK_UNIT
		Where A.C_STATUS=1
		Order by D.C_ORDER,A.C_ORDER
	SET NOCOUNT OFF
GO
-- Exec USER_GetPersonalInfoOfAllStaff

----------------------------------------------------------------------------------------------------------------
-- SP: USER_GetNewEndUserOfSingleApp
-- Thu tuc nay tra lai mot RecoredSet chua thong tin ca nhan cua cac enduser moi (khong thuoc danh sach @p_current_enduser_id_list)
-- Ngay tao: 13/10/2004
-- Created by:Nguyen Tuan Anh

Create Procedure USER_GetNewEndUserOfSingleApp
	@p_app_code nvarchar(30),  -- Ma cua ung dung
	@p_current_staff_id_list nvarchar(4000) -- danh sach ID cua cac can bo la enduser hien thoi cua ung dung
As
	SET NOCOUNT ON
	Declare @app_id int
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	-- Tao temporary table de chua ID cua cac can bo la enduser hien thoi cua ung dung
	Create Table #T_CURRENT_ENDUSER(PK_STAFF Int)
	Exec SP_ListToTable @p_current_staff_id_list, 'PK_STAFF', '#T_CURRENT_ENDUSER', ','

	Select A.PK_STAFF,
			(Select PK_ENDUSER From T_USER_ENDUSER Where FK_APPLICATION=@app_id And FK_STAFF=A.PK_STAFF) AS PK_ENDUSER,
			(Select C_APP_USERNAME From T_USER_ENDUSER Where FK_APPLICATION=@app_id And FK_STAFF=A.PK_STAFF) AS C_APP_USERNAME,
			(Select C_APP_PASSWORD From T_USER_ENDUSER Where FK_APPLICATION=@app_id And FK_STAFF=A.PK_STAFF) AS C_APP_PASSWORD,
			A.C_USERNAME,
			A.C_PASSWORD,
			A.C_NAME,
			A.C_CODE,
			A.FK_UNIT,
			D.C_NAME AS C_UNIT_NAME,
			B.C_CODE AS C_POSITION_CODE,
			B.C_NAME AS C_POSITION_NAME,
			C.C_CODE AS C_POSITION_GROUP_CODE,
			C.C_NAME AS C_POSITION_GROUP_NAME,
			A.C_ADDRESS,
			A.C_EMAIL,
			A.C_TEL,
			A.C_TEL_LOCAL,
			A.C_TEL_MOBILE,
			A.C_TEL_HOME,
			A.C_FAX,
			A.C_SEX,
			A.C_BIRTHDAY,
			A.C_ORDER,
			A.C_STATUS
	From T_USER_STAFF A
		Left Join T_USER_POSITION B On A.FK_POSITION = B.PK_POSITION
		Left Join T_USER_POSITION_GROUP C On B.FK_POSITION_GROUP = C.PK_POSITION_GROUP
		Left Join T_USER_UNIT D On A.FK_UNIT = D.PK_UNIT
	Where A.PK_STAFF In (Select FK_STAFF From T_USER_ENDUSER Where FK_APPLICATION=@app_id)
		And A.PK_STAFF Not In (Select PK_STAFF From #T_CURRENT_ENDUSER)
	SET NOCOUNT OFF
GO
-- Exec USER_GetNewEndUserOfSingleApp 'ISA-RESOURCE','1825,1826'


----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua thong tin ca nhan cua tat ca can bo (staff)
-- la end-user cua mot phan mem ung dung
-- Created by:Nguyen Tuan Anh

Create Procedure USER_GetPersonalInfoOfAllEndUser
	@p_app_code nvarchar(30)
As
	Declare @app_id int
	SET NOCOUNT ON
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	
	Select A.PK_STAFF,
			A.C_NAME,
			A.C_CODE,
			A.FK_UNIT,
			B.C_CODE AS C_POSITION_CODE,
			B.C_NAME AS C_POSITION_NAME,
			C.C_CODE AS C_POSITION_GROUP_CODE,
			A.C_ADDRESS,
			A.C_EMAIL,
			A.C_TEL,
			A.C_ORDER
	From T_USER_STAFF A
		Left Join T_USER_POSITION B On A.FK_POSITION = B.PK_POSITION
		Left Join T_USER_POSITION_GROUP C On B.FK_POSITION_GROUP = C.PK_POSITION_GROUP
	Where A.C_STATUS=1
		And A.PK_STAFF In (Select FK_STAFF From T_USER_ENDUSER Where FK_APPLICATION=@app_id)
	SET NOCOUNT OFF
GO
-- Exec USER_GetPersonalInfoOfAllEndUser 'ISA-RESOURCE'

----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua thong tin chi tiet cua tat ca phong ban (unit)
-- Created by:Nguyen Tuan Anh

Create Procedure USER_GetDetailInfoOfAllUnit
As
	SET NOCOUNT ON
	Select PK_UNIT, FK_UNIT, C_CODE, C_NAME, C_ADDRESS, C_TEL, C_EMAIL, C_ORDER
		From T_USER_UNIT 
		Where C_STATUS = 1
		Order By C_ORDER,C_NAME
	SET NOCOUNT OFF
GO
----------------------------------------------------------------------------------------------------------------
-- USER_GetAllGroupOfApplication
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua tat ca cac NHOM end-user cua mot ung dung
-- Created by:Nguyen Tuan Anh

Create Procedure USER_GetAllGroupOfApplication
	@p_app_code nvarchar(100)
As
	Declare @v_app_id int
	SET NOCOUNT ON
	Set @v_app_id = (Select PK_APPLICATION From T_USER_APPLICATION Where C_CODE = @p_app_code)
	Select PK_GROUP,C_CODE,C_NAME, C_ORDER From T_USER_GROUP Where FK_APPLICATION = @v_app_id And C_STATUS = 1
Go

-- USER_GetAllGroupOfApplication 'ISA-KTXH'


----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai danh sach cac nhom NSD ma nguoi dang nhap hien thoi la thanh vien
-- Created by: Nguyen Van Khanh
Create Procedure USER_GroupGetAllByMember
	@p_app_code nvarchar(100),
	@p_staff_id int -- ID cua staff la thanh vien cua cac nhom can lay
As
	Declare @v_enduser_id int, @v_app_id int
	SET NOCOUNT ON
	Set @v_app_id = (Select PK_APPLICATION From T_USER_APPLICATION Where C_CODE = @p_app_code)

	Select @v_enduser_id = PK_ENDUSER From T_USER_ENDUSER 
		Where FK_APPLICATION = @v_app_id And FK_STAFF = @p_staff_id

	Select 
		PK_GROUP,
		C_CODE,
		C_NAME,
		C_ORDER
	From T_USER_GROUP 
	Where PK_GROUP In (Select FK_GROUP From T_USER_GROUP_BY_ENDUSER Where FK_ENDUSER=@v_enduser_id)
		And FK_APPLICATION = @v_app_id
		And C_STATUS = 1
	Order By C_ORDER 

	SET NOCOUNT OFF
	Return 0
GO

----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua EFY-USER 
-- Thu tuc nay tra lai mot RecoredSet chua danh sach nguoi sinh nhat trong ngay hien thoi
-- Created by: Hoang Khac Duc

Create Procedure USER_GetAllPersonalHaveBirthdayOnCurrenday
As
	SET NOCOUNT ON
	Select A.C_NAME,
			B.C_CODE AS C_POSITION_CODE,
			B.C_NAME AS C_POSITION_NAME,
			C.C_NAME AS C_UNIT_NAME
		From T_USER_STAFF A
			Left Join T_USER_POSITION B On A.FK_POSITION = B.PK_POSITION
			Left Join T_USER_UNIT C On A.FK_UNIT = C.PK_UNIT
		Where datepart(day,A.C_BIRTHDAY)=datepart(day,getdate()) And datepart(month,A.C_BIRTHDAY)=datepart(month,getdate()) And A.C_STATUS = 1
		Order by A.FK_UNIT,A.C_ORDER
	SET NOCOUNT OFF
GO
-- Exec USER_GetAllPersonalHaveBirthdayOnCurrenday
