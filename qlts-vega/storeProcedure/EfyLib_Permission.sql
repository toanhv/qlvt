if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_PermissionGroupGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_PermissionGroupGetAll]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_StaffPermissionUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_StaffPermissionUpdate]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_StaffPermissionGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_StaffPermissionGetAll]
GO

--------------------------------------------------------------------------------------------------------------- 
--Nguoi tao: HUNGVM
--Ngay tao:  18/09/2009
--Y nghia:   Tra ve danh sach cac quyen
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.EfyLib_PermissionGroupGetAll
	@p_code						nvarchar(100) = 'DM_ NHOM_QUYEN'
	,@p_xml_tag_in_db			Nvarchar(100)
WITH ENCRYPTION
AS
Declare @v_str_sql nvarchar(500),@list_id varchar(50)
	SET NOCOUNT ON
	Set @v_str_sql = ' Select a.C_CODE, a.C_NAME, ' + 'dbo.f_GetValueOfXMLtag(C_XML_DATA, ' + char(39) + @p_xml_tag_in_db + char(39) + ' ) as XML_TAG_IN_DB '
	Set @v_str_sql = @v_str_sql + ' From  T_EFYLIB_LIST a, T_EFYLIB_LISTTYPE b '
	Set @v_str_sql = @v_str_sql + ' Where a.FK_LISTTYPE = b.PK_LISTTYPE '
	If @p_code Is Not Null And @p_code <>''
		Begin
			Set @v_str_sql = @v_str_sql + ' And  b.C_CODE = ' + char(39) + @p_code + char(39)
		End
	Set @v_str_sql = @v_str_sql + ' And a.C_STATUS =  '+char(39) + 'HOAT_DONG' + char(39)
	--Order by C_ORDER
	Set @v_str_sql = @v_str_sql + ' Order By a.C_ORDER '
	Exec (@v_str_sql)
	SET NOCOUNT OFF
Return 0
Go
/*
Exec dbo.EfyLib_PermissionGetAll
	 @p_code = 'DM_NHOM_QUYEN'
	,@p_xml_tag_in_db = 'quyen_thuocnhom'
*/
---------------------------------------------------------------------------------------------------
--Nguoi tao: Vu Manh Hung
--Ngay tao: 20/09/2009
--Chuc nang: Cap nhat quyen cho NSD
CREATE PROCEDURE dbo.EfyLib_StaffPermissionUpdate
	@p_permission_id	Varchar(50) 						-- Khoa
	,@p_staff_id_list	Nvarchar(4000) = ''					-- Danh sach ID NSD
	,@p_permission_list Nvarchar(4000) = ''					-- Danh sach quyen
	,@psDelimitor		Varchar(10)							-- Ky tu phan tach giua cac phan tu
WITH ENCRYPTION --Ma hoa SP
AS
	Declare @Index Int, @count Int, @countStaff Int, @p_new_id Uniqueidentifier, @staffId Int
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	--Ban giao ho so (Insert new row in T_QLDT_PROCESS)
	Set @count = dbo.f_ListLen(@p_staff_id_list,@psDelimitor)	
		Set @Index = 1
		While @Index <= @count
		Begin
			-- Id staff
			Set @staffId = Convert(Int,dbo.f_ListGetAt(@p_staff_id_list,@Index,@psDelimitor))
			--Kiem tra ID Staff @staffId da ton tai chua
			Select @countStaff = Count(*) From T_DOC_PERMISSION Where FK_STAFF_ID = @staffId
			--Da phan quyen
			If @countStaff > 0
				Begin
					Update T_DOC_PERMISSION Set C_PERMISSION_LIST = @p_permission_list From T_DOC_PERMISSION Where FK_STAFF_ID = @staffId
				End
			Else
				Begin
					Set @p_new_id = newid()
					Insert Into T_DOC_PERMISSION (PK_PERMISSION,FK_STAFF_ID,C_PERMISSION_LIST) Values (@p_new_id,@staffId,@p_permission_list)
				End
			Set @Index = @Index + 1
		End			
	COMMIT TRANSACTION 	
	SET NOCOUNT OFF
	Return 0
GO
/*
Exec dbo.Doc_StaffPermissionUpdate
	@p_permission_id	= ''
	,@p_staff_id_list	= '123!~~!2!~~!33!~~!44!~~!55'
	,@p_permission_list = 'a!~~!b!~~!c!~~!d!~~!e'
	,@psDelimitor		= '!~~!'

Select * From T_DOC_PERMISSION
*/
-------------------------------------------------------------------------------------------------------------------------------------------------
--Nguoi tao: HUNGVM
--Ngay tao:  20/09/2009
--Y nghia:   Tra ve danh sach cac quyen cua mot hoac nhieu NSD
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.EfyLib_StaffPermissionGetAll
	@p_staff_id_list	Nvarchar(4000) = ''					-- Danh sach ID NSD
	,@psDelimitor		Varchar(10)							-- Ky tu phan tach giua cac phan tu
WITH ENCRYPTION
AS
Declare @v_str_sql nvarchar(500),@list_id varchar(50)
	SET NOCOUNT ON
	-- Tao bang trung gian
	Create Table #T_STAFF(FK_STAFF Int)
	Exec Sp_ListToTable @p_staff_id_list, 'FK_STAFF', '#T_STAFF', @psDelimitor	
	-- Lay quyen cua NSD
	Select PK_PERMISSION,FK_STAFF_ID,C_PERMISSION_LIST From T_DOC_PERMISSION Where FK_STAFF_ID In (Select FK_STAFF From #T_STAFF)
	SET NOCOUNT OFF
Return 0
Go
/*
Exec dbo.Doc_StaffPermissionGetAll
	@p_staff_id_list	= '123!~~!2!~~!33!~~!44!~~!55'
	,@psDelimitor		= '!~~!'

Doc_StaffPermissionGetAll '2054!~~!2055!~~!2056!~~!2057!~~!2059'
*/