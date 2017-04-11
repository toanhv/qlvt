if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListGetSingle]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListDelete]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListFileAttack]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListFileAttack]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_CheckExistInXMLTag]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_CheckExistInXMLTag]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetListNamebyCode]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetListNamebyCode]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListGetAll]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetAllReportByReporttype]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[f_GetAllReportByReporttype]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[_deleteFileUpload]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[_deleteFileUpload]
GO 
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListGetAllbyListtypeCode]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListGetAllbyListtypeCode]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListGetAllbyCode]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListGetAllbyCode]
GO

--------------------------------------------------------------------------------------------------------------- 
--Nguoi tao: Ngo Tien Dat
--Ngay tao:  3/11/2005
--Y nghia:   Tra ve danh sach cac doi tuong cua 1 loai thong tin danh muc
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.EfyLib_ListGetAll
	--@p_listtype_id int,					-- ID cua loai danh muc
	@p_page int,  							--Lay trang thu bao nhieu
	@p_number_record_per_page int,			--So luong ban ghi can lay ra
	@p_filter_detail nvarchar(4000),		-- Chuoi cac bieu thuc so sanh theo tieu thuc loc
	@p_owner_code	Nvarchar(100) = ''		-- Ma don vi su dung
WITH ENCRYPTION
AS
	Declare @v_str_sql nvarchar(4000), @p_total_record int
	SET NOCOUNT ON
	--Tao bang tam luu tat ca cac ban ghi thoa man
	Create Table #T_ALL_LIST(P_ID int IDENTITY (1,1), PK_LIST int)
	Set @v_str_sql = ' Insert into #T_ALL_LIST(PK_LIST) Select PK_LIST From T_EFYLIB_LIST '
	Set @p_filter_detail = replace(@p_filter_detail,'##',char(39))
	If @p_filter_detail <>'' And @p_filter_detail Is Not Null
		Set @v_str_sql = @v_str_sql + ' ' + @p_filter_detail
	-- Don vi su dung
	If @p_owner_code <>'' And @p_owner_code Is Not Null
		Set @v_str_sql = @v_str_sql + ' And ( dbo.f_ListHaveElement(C_OWNER_CODE_LIST,'+char(39)+ @p_owner_code + char(39)+ ','+char(39)+','+char(39)+')=1 )'

	Set @v_str_sql = @v_str_sql + ' Order by C_ORDER'
	--print (@v_str_sql)
	--return
	Exec (@v_str_sql)
	--Lay ra cac ban ghi theo so trang
	Select @p_total_record = count(*)  From #T_ALL_LIST
	Select  A.PK_LIST,  -- 0
		B.C_ORDER,
		B.C_STATUS,
		B.C_XML_DATA,
		dbo.f_GetValueOfXMLtag(B.C_XML_DATA,'staff_process') as C_STAFF_ID ,
		B.C_CODE,
		B.C_NAME,
		@p_total_record as TOTAL_RECORD
	From	#T_ALL_LIST A				
 	Inner join T_EFYLIB_LIST B On A.PK_LIST = B.PK_LIST
	Where A.P_ID >((@p_page-1)*@p_number_record_per_page) and A.P_ID <= @p_page*@p_number_record_per_page
	Order by B.C_ORDER

	SET NOCOUNT OFF
Return 0
Go
/*
Exec EfyLib_ListGetAll 1, 10,' where FK_LISTTYPE = 1 And C_NAME like ##%%## And dbo.f_GetValueOfXMLtag(C_XML_DATA, ##xml_file_name##) like ##%%##'
Exec EfyLib_ListGetAll 1, 15, 'where FK_LISTTYPE = 2 And C_NAME like ''%%'' ,'' 

*/
-------------------------------------------------------------------------------------------------------------------------------------------------
-- Exec ISALIB_ListGetAll -1,1,'','And dbo.f_GetValueOfXMLtag(C_XML_DATA, ####xml_file_name####) like ####%%####',1,10 
--	Select * from T_EFYLIB_LIST
-------------------------------------------------------------------------------------------------------------------------------------------------
--Nguoi tao: Ngo Tien Dat
--Ngay tao:  3/11/2005
--Y nghia:   Tra ve thong tin cua doi tuong cua 1 loai danh muc
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.EfyLib_ListGetSingle
	@p_list_id				int
	,@p_owner_code			Nvarchar(100) = ''
WITH ENCRYPTION
AS
	Declare @v_sql Nvarchar(4000)
	SET NOCOUNT ON
	Set @v_sql = 'Select l.PK_LIST, l.FK_LISTTYPE, l.C_ORDER, l.C_STATUS, l.C_XML_DATA, l.C_CODE, l.C_NAME, l.C_OWNER_CODE_LIST, t.C_XML_FILE_NAME'
	Set @v_sql = @v_sql + ' From T_EFYLIB_LIST l, T_EFYLIB_LISTTYPE t Where 1 = 1 and l.FK_LISTTYPE = t.PK_LISTTYPE '
	Set @v_sql = @v_sql + ' And l.PK_LIST = ' + convert(Nvarchar(50),@p_list_id)
	-- Ma don vi su dung
	If @p_owner_code <> '' And @p_owner_code is not Null		
		Set @v_sql = @v_sql + ' And ( dbo.f_ListHaveElement(l.C_OWNER_CODE_LIST,'+char(39)+ @p_owner_code + char(39)+ ','+char(39)+','+char(39)+')=1 )'
	Exec (@v_sql)
	SET NOCOUNT OFF
Return 0
Go
-------------------------------------------------------------------------------------------------------------------------------------------------
-- Exec EfyLib_ListGetSingle 484
-------------------------------------------------------------------------------------------------------------------------------------------------
--Nguoi tao: Ngo Tien Dat
--Ngay tao:  3/11/2005
--Y nghia:   Xoa mot hoac nhieu loai doi tuong cua 1 loai danh muc
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.EfyLib_ListDelete
	@p_list_id_list nvarchar(2000) -- Chua danh sach ID can xoa
WITH ENCRYPTION
AS
	SET NOCOUNT ON
	--Declare @id_list int

	-- Tao bang trung gian
	Create Table #T_EFYLIB_LIST(PK_LIST int)
	Exec Sp_ListToTable @p_list_id_list, 'PK_LIST', '#T_EFYLIB_LIST', ','
	Delete T_EFYLIB_LIST Where PK_LIST In (Select PK_LIST From #T_EFYLIB_LIST) 
	SET NOCOUNT OFF	
Return 0
GO
-------------------------------------------------------------------------------------------------------------------------------------------------
-- Exec EfyLib_ListDelete '2,4'
-- Select * From T_EFYLIB_LIST
-------------------------------------------------------------------------------------------------------------------------------------------------
-- Exec Onegate_RecordHandoverUpdate '5','DANG_THU_LY',1
-- Select * From T_ONEGATE_RECORD
-------------------------------------------------------------------------------------------------------------------
--Nguoi tao: Vu Manh Hung
--Ngay tao: 14/01/2009
--Chuc nang: Cap nhat danh muc doi tuong
CREATE PROCEDURE dbo.EfyLib_ListUpdate
	@p_list_id int 									--ID cua doi tuong
	,@p_listtype_id int								--ID cua loai danh muc
	,@p_code nvarchar(200)
	,@p_name nvarchar(500)	
	,@p_order int									--Thu tu hien thi
	,@p_status Nvarchar(100)						--Trang thai
	,@p_list_xml_data Xml							--Chuoi XML luu trong Bang T_TEMP_FILE
	,@p_deleted_file_id_list nvarchar(2000)			--Danh sach ID cua cac file dinh kem da duoc danh dau xoa
	,@p_new_file_id_list nvarchar(2000)				--Danh sach ID cua cac file dinh kem da them moi
	,@p_owner_code_list Nvarchar(4000) = ''
WITH ENCRYPTION --Ma hoa SP
AS
	Declare @list_id int,@count int,@file_id int,@file_content_id int,@status smallint, @v_filter nvarchar(4000)
	SET NOCOUNT ON
	Select @count= Count(*) From T_EFYLIB_LIST Where PK_LIST <> @p_list_id And FK_LISTTYPE = @p_listtype_id And C_CODE = @p_code
	If @count >0 
		Begin
			Select 'Ma doi tuong nay da ton tai! ' RET_ERROR
			Return -100
		End

	Select @count= Count(*) From T_EFYLIB_LIST Where PK_LIST <> @p_list_id And FK_LISTTYPE = @p_listtype_id And C_NAME = @p_name
	If @count >0 
		Begin
			Select 'Ten doi tuong nay da ton tai! ' RET_ERROR
			Return -100
		End

	SET XACT_ABORT ON
	BEGIN TRANSACTION
	If @p_list_id > 0 
		Begin
			Update T_EFYLIB_LIST Set
				FK_LISTTYPE = @p_listtype_id,
				C_CODE = @p_code ,
				C_NAME = @p_name,
				C_ORDER = @p_order,
				C_STATUS = @p_status,
				C_XML_DATA = @p_list_xml_data,
				C_OWNER_CODE_LIST = @p_owner_code_list
			Where PK_LIST  = @p_list_id
			Set @list_id = @p_list_id
		End
	Else
		Begin
			Insert Into T_EFYLIB_LIST(
					FK_LISTTYPE,
					C_CODE,
					C_NAME,
					C_ORDER,
					C_STATUS,
					C_XML_DATA,
					C_OWNER_CODE_LIST)
			Values(	@p_listtype_id,
					@p_code,
					@p_name,
					@p_order,
					@p_status,
					@p_list_xml_data,
					@p_owner_code_list)						
			Set @list_id = @@IDENTITY
		End

	Set @v_filter = 'FK_LISTTYPE = ' + Convert(varchar,@p_listtype_id)
	If @p_order Is Not Null And @p_order>0
	Exec SP_ReOrder 'T_EFYLIB_LIST', 'C_ORDER', 'PK_LIST', @list_id, @p_order,@v_filter

	-- Neu co file dinh kem thi tien hanh Cap nhat file dinh kem
	-- Neu @p_deleted_file_id_list khac rong thi xoa cac file tuong ung trong T_ISALIB_LIST_FILE
	If rtrim(@p_deleted_file_id_list)<>'' And @p_deleted_file_id_list Is Not Null
		Begin
			-- Tao bang trung gian
			Create Table #T_DELETED_FILE(PK_FILE int)
			Exec Sp_ListToTable @p_deleted_file_id_list, 'PK_FILE', '#T_DELETED_FILE', ','
			Delete T_ISALIB_LIST_FILE Where PK_LIST_FILE  In (Select PK_FILE From #T_DELETED_FILE)
		End
	-- Neu @p_new_file_id_list khac rong thi them cac file tuong ung tu T_TEMP_FILE sang T_ISALIB_LIST_FILE

	If rtrim(@p_new_file_id_list)<>'' And @p_new_file_id_list Is Not Null
		Begin
			-- Tao bang trung gian
			Create Table #T_NEW_FILE(PK_FILE int)
			Exec Sp_ListToTable @p_new_file_id_list, 'PK_FILE', '#T_NEW_FILE', ','
			Insert Into T_ISALIB_LIST_FILE(FK_LIST, C_FILE_NAME, C_FILE_CONTENT ) 
				Select @list_id, convert(varchar,PK_FILE), 0x From #T_NEW_FILE
			DECLARE new_file CURSOR For Select PK_FILE From #T_NEW_FILE
			Open new_file
			FETCH NEXT FROM new_file INTO @file_id
			WHILE @@FETCH_STATUS = 0
				BEGIN
					Select @file_content_id = PK_LIST_FILE From T_ISALIB_LIST_FILE Where C_FILE_NAME = convert(varchar,@file_id)
					Exec @status=SP_CopyFileContent 'T_TEMP_FILE', 'PK_TEMP_FILE', 'C_FILE_NAME', 'C_FILE_CONTENT', @file_id, 'T_ISALIB_LIST_FILE', 'PK_LIST_FILE', 'C_FILE_NAME', 'C_FILE_CONTENT', @file_content_id
					If @@error<>0
						Begin
							Select 'Loi khi thuc hien thu tuc SP_CopyFileContent' RET_ERROR
							CLOSE new_file
							DEALLOCATE new_file
							Rollback Transaction
							Return -100
						End 
					FETCH NEXT FROM new_file INTO @file_id
				END
			CLOSE new_file
			DEALLOCATE new_file
			Delete T_TEMP_FILE Where PK_TEMP_FILE In (Select PK_FILE From #T_NEW_FILE)
		End	
	Select @list_id NEW_ID
	COMMIT TRANSACTION 
	
	SET NOCOUNT OFF
	Return 0
GO

/*
Exec EfyLib_ListUpdate 0, 91, 'DEF', 'DEF', 1, 'HOAT_DONG', '<root><data_list><note_list></note_list><xml_file_name></xml_file_name><test>ngan</test></data_list></root>', '', '','NQ'

	Exec EfyLib_ListUpdate 0, 21, 'DM1', 'Danh 1', 1, 1, '<root><data_list><note_list>Ghi chu</note_list><xml_file_name></xml_file_name></data_list></root>', '', '','NQ,PMT,PLKT'
select * from T_EFYLIB_LIST where PK_LIST = 686
*/

-------------------------------------------------------------------------------------------------------------------
-- Nguoi tao:	Nguyen Duy Hieu
-- Ngay tao:	22/04/2009
-- Y nghia:	Lay ten cua doi tuong danh muc dua vao code
CREATE FUNCTION dbo.f_GetListNamebyCode(@p_listtype_code nvarchar(100),@p_list_code nvarchar(100))
Returns nvarchar(4000)
WITH ENCRYPTION
AS
BEGIN
	Declare @result nvarchar(4000)
	Select 	@result = a.C_NAME
	From T_EFYLIB_LIST a, T_EFYLIB_LISTTYPE b
	Where a.FK_LISTTYPE = b.PK_LISTTYPE  
		And	b.C_CODE = @p_listtype_code
		And a.C_CODE = @p_list_code
		And a.C_STATUS = 'HOAT_DONG' 
	Order By a.C_ORDER	
	--Neu gia tri la null thi gan = ''	
	If @result Is Null
		Set @result = ''
	Return @result
END
Go
-- Select dbo.f_GetListNamebyCode('DM_CAP_NOI_GUI_VAN_BAN','TWD')
----------------------------------------------------------------------------------------------
/*
Tam thoi an xu ly du lieu XML
*/
Create PROCEDURE [dbo].[f_GetAllReportByReporttype]
	@p_listtype_code nvarchar(3000)  -- Ma Cua listtype 	
AS
	SET NOCOUNT ON
		Select a.C_CODE, a.C_NAME,a.C_XML_DATA
		From T_EFYLIB_LIST a, T_EFYLIB_LISTTYPE b 
		Where a.FK_LISTTYPE = b.PK_LISTTYPE 
			And b.C_CODE = @p_listtype_code 
			And a.C_STATUS = 'HOAT_DONG' 
		Order By a.C_ORDER	

	SET NOCOUNT OFF
Return 0
Go
/*
	exec QLDT_GetAllReportByReporttype 'DM_BAO_CAO'
*/
--------------------------------------------------------------------------------------------------------------------
/*
	Creater: Hoang Van Toan
	Date: 10/01/2010
	Discription: SP xoa file dinh kem
*/
CREATE PROCEDURE dbo.[_deleteFileUpload]
	@fileNameList      		varchar(2000)	-- Danh sach file dinh kem can xoa
WITH ENCRYPTION
AS	
	SET NOCOUNT ON	
	If @fileNameList <> '' And @fileNameList is not null
	-- Tao bang trung gian
	Create Table #T_DELETE_FILE(C_FILE_NAME nvarchar(280))
	Exec Sp_ListToTable @fileNameList, 'C_FILE_NAME', '#T_DELETE_FILE', '!#~$|*'

 	SET XACT_ABORT ON -- Dat che do tu dong Rollback neu co loi xay ra
	BEGIN TRANSACTION
	-------------------------Xoa du lieu bang quan he lien quan------------------
	-- Xoa du lieu tu bang T_QLDT_FILE
	Delete From T_EFYLIB_FILE Where C_FILE_NAME In (Select C_FILE_NAME From #T_DELETE_FILE)
	------------------------------------------------------------------------------
	COMMIT TRANSACTION
	SET NOCOUNT OFF
	Return 0
GO	
/*
	Exec [_deleteFileUpload] '2010_06_091139000000194266!~!Tai lieu mo ta yeu cau can nang cap QLVB version 3.0.doc!#~$|*2010_06_091139000000194266!~!Tai lieu mo ta yeu cau can nang cap QLVB version 3.0.doc'
	Select * from T_DOC_FILE Where C_FILE_NAME = '2010_06_091139000000194266!~!Tai lieu mo ta yeu cau can nang cap QLVB version 3.0.doc'
*/

--Nguoi tao: HUNGVM
--Ngay tao:  13/12/2005
--Y nghia: Lay ra danh sach cac doi tuong thuoc loai danh muc
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.EfyLib_ListGetAllbyListtypeCode
	@p_owner_code varchar(30) = '',		-- Ten cua don vi su dung phan mem
	@p_listtype_code varchar(30),	-- Ma loai danh muc
	@p_str_order nvarchar(1000)=''		-- Xau mo ta dieu kien sap xem
WITH ENCRYPTION
AS
	Declare @v_str_sql nvarchar(4000)
	SET NOCOUNT ON
	--Tao bang tam luu tat ca cac ban ghi thoa man
	Create Table #T_ALL_LIST(P_ID int IDENTITY (1,1), PK_LIST int)
	Set @v_str_sql = ' Insert into #T_ALL_LIST(PK_LIST) Select A.PK_LIST From T_EFYLIB_LIST A, T_EFYLIB_LISTTYPE B Where 1= 1 '

	If @p_owner_code <> '' And @p_owner_code is not null
		Begin
			Set @v_str_sql = @v_str_sql + ' And dbo.f_InList(A.C_OWNER_CODE_LIST,'+char(39)+@p_owner_code+char(39)+','+char(39)+','+char(39)+')=1'	
		End
	Set @v_str_sql = @v_str_sql + ' And a.FK_LISTTYPE = b.PK_LISTTYPE '

	-- test existing @p_listtype_code
	If @p_listtype_code Is Not Null And @p_listtype_code <>''
		Set @v_str_sql = @v_str_sql + ' And B.C_CODE = ' + char(39) + @p_listtype_code + char(39)

	-- Order data
	If(@p_str_order is null or @p_str_order = '')
		Set @v_str_sql = @v_str_sql + ' Order by B.C_ORDER'
	Else
		Set @v_str_sql = @v_str_sql + @p_str_order
	--print @v_str_sql

	Exec (@v_str_sql)
	Select 
		  B.C_CODE
		, B.C_NAME
		, B.C_XML_DATA
		, B.C_ORDER
		From #T_ALL_LIST A inner join T_EFYLIB_LIST B
		On A.PK_LIST = B.PK_LIST
		Where B.C_STATUS = 'HOAT_DONG'
		Order by B.C_ORDER ASC
	SET NOCOUNT OFF
Return 0
Go
---------------------------------------------------------------------------
CREATE PROCEDURE dbo.EfyLib_ListGetAllbyCode
	@p_code nvarchar(200)
	,@p_xml_tag_in_db nvarchar(200)
	,@p_list_is_not_return nvarchar(200) = ''-- : danh sach danh muc khong can lay ra
	,@p_getdate_xml smallint = 0			-- > 0 : Lay them cot XML; = 0 thi khong lay
WITH ENCRYPTION
AS
Declare @v_str_sql nvarchar(500),@list_id varchar(50), @sysbol nvarchar(500)
	SET NOCOUNT ON
	Set @v_str_sql = ' Select a.C_CODE, a.C_NAME, ' + 'dbo.f_GetValueOfXMLtag(a.C_XML_DATA, '+ char(39) + @p_xml_tag_in_db + char(39) + ' ) as XML_TAG_IN_DB, a.PK_LIST '
	Set @v_str_sql = @v_str_sql + ', dbo.f_GetValueOfXMLtag(a.C_XML_DATA, '+ char(39) + 'staff_process' + char(39) + ' ) as staff_process '
	Set @v_str_sql = @v_str_sql + ', dbo.f_GetValueOfXMLtag(a.C_XML_DATA, '+ char(39) + 'position_group' + char(39) + ' ) as position_group '
	-- Lay cot du lieu XML
	If @p_getdate_xml > 0 
		Begin
			Set @v_str_sql = @v_str_sql + ' ,a.C_XML_DATA '
		End
	Set @v_str_sql = @v_str_sql + ' From  T_EFYLIB_LIST a, T_EFYLIB_LISTTYPE b '
	Set @v_str_sql = @v_str_sql + ' Where a.FK_LISTTYPE = b.PK_LISTTYPE '
	--print @v_str_sql
	If @p_code Is Not Null And @p_code <>''
		Begin
			Set @v_str_sql = @v_str_sql + ' And  b.C_CODE = ' + char(39) + @p_code + char(39)
		End
	Set @v_str_sql = @v_str_sql + ' And a.C_STATUS =  '+char(39) + 'HOAT_DONG' + char(39)

	If @p_list_is_not_return Is Not Null And @p_list_is_not_return <> ''
	Begin
		Create Table #T_TMP_LIST(PK_LIST varchar(50))
		Exec Sp_ListToTable @p_list_is_not_return, 'PK_LIST', '#T_TMP_LIST', ','
		DECLARE list_id CURSOR For Select PK_LIST From #T_TMP_LIST
		Open list_id
		FETCH NEXT FROM list_id INTO @list_id
		WHILE @@FETCH_STATUS = 0
			BEGIN
				Set @v_str_sql = @v_str_sql + ' And a.C_CODE <> ' + char(39) + @list_id + char(39)
			FETCH NEXT FROM list_id INTO @list_id
			END
		CLOSE list_id
		DEALLOCATE list_id
	End
	Set @v_str_sql = @v_str_sql + ' Order By a.C_ORDER '
	print @v_str_sql
	Exec (@v_str_sql)
	SET NOCOUNT OFF
Return 0
Go
/*
EfyLib_ListGetAllbyListtypeCode 'NQ','DM_GD_GQ'
*/