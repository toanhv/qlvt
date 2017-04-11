---------------------------------------------------------List procedures-------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListtypeGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListtypeGetAll]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListtypeGetAll_PerPage]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListtypeGetAll_PerPage]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListtypeGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListtypeGetSingle]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListtypeUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListtypeUpdate]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListtypeDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListtypeDelete]
GO

---------------------------------------------------------List procedures-------------------------------------------

-------------------------------------------------------------------------------------------------------------------

--------------------------------------------------------------------------------------------------------------------------
-- Nguoi tao:	Nguyen Huy Phuong
-- Ngay tao:	3/11/2005
-- Y nghia:	Lay thong tin cua tat ca cac danh muc
-- Hieu dinh: HUNGVM
-- Ngay: 01/05/2008
-- Y nghia: Lay danh sach loai danh muc cua don vi su dung @p_owner_code
CREATE PROCEDURE dbo.EfyLib_ListtypeGetAll
	@p_status Nvarchar(100)													-- Trang thai
	,@p_filter nvarchar(50)													-- Gia tri loc
	,@p_owner_code Nvarchar(100) = ''										-- Ma don vi su dung
WITH ENCRYPTION
AS	
	Declare @p_sql Nvarchar(4000)
	SET NOCOUNT ON
	Set @p_sql = 'Select 	PK_LISTTYPE, C_CODE, C_NAME, C_ORDER, C_XML_FILE_NAME, C_STATUS, C_OWNER_CODE_LIST From T_EFYLIB_LISTTYPE Where 1 = 1 '	

	If @p_status Is Null Or @p_status = ''
		Set @p_sql = @p_sql + ' And C_NAME like ' + char(39)  + '%' + @p_filter  + '%' + char(39)
	Else
		Set @p_sql = @p_sql + ' And C_NAME like '  + char(39)+ '%' + @p_filter + '%' + char(39) + ' And C_STATUS = ' + Char(39) + @p_status + Char(39)
	If @p_owner_code <> '' And @p_owner_code is not Null		
		Set @p_sql = @p_sql + ' And ( dbo.f_ListHaveElement(C_OWNER_CODE_LIST,'+char(39)+ @p_owner_code + char(39)+ ','+char(39)+','+char(39)+')=1 )'
	Set @p_sql = @p_sql + ' Order By C_ORDER'
	--Print @p_sql
	Exec (@p_sql)
	SET NOCOUNT OFF
Return 0
Go
/*
dbo.EfyLib_ListtypeGetAll 'HOAT_DONG','','BG'
Select 	PK_LISTTYPE, C_CODE, C_NAME, C_ORDER, C_XML_FILE_NAME, C_STATUS, C_OWNER_CODE_LIST 
From T_EFYLIB_LISTTYPE Where 1 = 1  And C_STATUS = 'HOAT_DONG' 
And ( dbo.f_ListHaveElement(C_OWNER_CODE_LIST,'BG',',')=1 ) Order By C_ORDER

Exec EfyLib_ListtypeGetAll '','','NQ'
*/
-------------------------------------------------------------------------------------------------------------------------------------
-- Y nghia:	lay tat ca cac danh muc theo trang
CREATE PROCEDURE dbo.EfyLib_ListtypeGetAll_PerPage
	@p_status Nvarchar(100) 
	,@p_listtype_id int	              -- ID cua loai danh muc
	,@p_filter nvarchar(200)	      -- Loc theo ten	
	,@p_page int                      --Lays trang thu bao nhieu
	,@p_number_record_per_page int    --So luong ban ghi can lay ra
WITH ENCRYPTION	
AS	
	SET NOCOUNT ON
	declare @total_record int
	--Tao bang tam luu tat ca cac ban ghi thoa man
	Create Table #T_ALL_LISTYPE(P_ID int IDENTITY (1,1), PK_LIST int)
	Insert into #T_ALL_LISTYPE(PK_LIST) Select PK_LISTTYPE From T_EFYLIB_LISTTYPE	
	Select @total_record = count(*)  From #T_ALL_LISTYPE
	If @p_status Is Null Or @p_status = ''
		Select  
			A.PK_LIST
			,B.C_CODE
			,B.C_NAME
			,B.C_ORDER
			,B.C_XML_FILE_NAME
			,B.C_STATUS
			,@total_record as TOTAL_RECORD
		From	#T_ALL_LISTYPE A				
 		Inner join T_EFYLIB_LISTTYPE B On A.PK_LIST = B.PK_LISTTYPE
		Where A.P_ID >((@p_page-1)*@p_number_record_per_page) and A.P_ID <= @p_page*@p_number_record_per_page and B.C_NAME like '%' + @p_filter + '%'
		Order by B.C_ORDER
	Else
		Select  
			A.PK_LIST
			,B.C_CODE
			,B.C_NAME
			,B.C_ORDER
			,B.C_XML_FILE_NAME
			,B.C_STATUS	
			,@total_record as TOTAL_RECORD	
		From	#T_ALL_LISTYPE A				
	 	Inner join T_EFYLIB_LISTTYPE B On A.PK_LIST = B.PK_LISTTYPE
		Where A.P_ID >((@p_page-1)*@p_number_record_per_page) and A.P_ID <= @p_page*@p_number_record_per_page and B.C_NAME like '%' + @p_filter + '%' and B.C_STATUS = @p_status
		Order by B.C_ORDER
	SET NOCOUNT OFF
Return 0
Go
/*
Exec dbo.EfyLib_ListtypeGetAll_PerPage 'HOAT_DONG',1,'',1,10
*/
-------------------------------------------------------------------------------------------------------------------------------------
-- Y nghia:	Tra ve thong tin cua mot danh muc
CREATE PROCEDURE dbo.EfyLib_ListtypeGetSingle
	@p_listtype_id			int											-- Id loai danh muc
	,@p_owner_code			Nvarchar(100) = ''							-- Ma don vi su dung
WITH ENCRYPTION
AS
	Declare @p_sql Nvarchar(4000)
	SET NOCOUNT ON
	Set @p_sql = 'Select 	PK_LISTTYPE, C_CODE, C_NAME, C_ORDER, C_XML_FILE_NAME, C_STATUS, C_OWNER_CODE_LIST From T_EFYLIB_LISTTYPE Where 1 =1 '
	-- Id loai danh muc
	If @p_listtype_id <> 0
		Set @p_sql = @p_sql + ' And PK_LISTTYPE = ' + Convert(Nvarchar(100),@p_listtype_id)
	-- Ma don vi su dung
	If @p_owner_code <> '' And @p_owner_code is not Null		
		Set @p_sql = @p_sql + ' And ( dbo.f_ListHaveElement(C_OWNER_CODE_LIST,'+char(39)+ @p_owner_code + char(39)+ ','+char(39)+','+char(39)+')=1 )'
	Exec(@p_sql)
	SET NOCOUNT OFF
Return 0
Go
-- dbo.EfyLib_ListtypeGetSingle 1
-------------------------------------------------------------------------------------------------------------------------------------
-- Y nghia thuc hien cap nhat mot danh muc
CREATE PROCEDURE dbo.EfyLib_ListtypeUpdate
	@p_listtype_id			int
	,@p_code				nvarchar(100)
	,@p_name				nvarchar(1000)
	,@p_order				int
	,@p_xml_file_name		nvarchar(2000)
	,@p_status				Nvarchar(100)
	,@p_owner_code_list		Nvarchar(100) = ''
WITH ENCRYPTION
AS
	SET NOCOUNT ON
	Declare @count1 int,@count2 int, @listtype_id int, @status int
	Select @count1= Count(*) From T_EFYLIB_LISTTYPE Where C_CODE = @p_code and PK_LISTTYPE<>@p_listtype_id
	If @count1 >0 
	Begin
		Select 'Ma loai danh muc nay da ton tai' RET_ERROR
		Return -100
	End
	--Dat che do tu dong rollback lai toan bo Transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION	
	If @p_listtype_id = 0					
	Begin
		Insert into T_EFYLIB_LISTTYPE (C_CODE,C_NAME,C_ORDER,C_XML_FILE_NAME,C_STATUS,C_OWNER_CODE_LIST)
		Values(@p_code,@p_name,@p_order,@p_xml_file_name,@p_status,@p_owner_code_list)
		Set @listtype_id = @@IDENTITY
	End	
	Else
	Begin
		Update T_EFYLIB_LISTTYPE Set
			C_CODE				= @p_code
			,C_NAME				= @p_name
			,C_ORDER			= @p_order
			,C_XML_FILE_NAME	= @p_xml_file_name
			,C_STATUS			= @p_status
			,C_OWNER_CODE_LIST	= @p_owner_code_list
		Where PK_LISTTYPE = @p_listtype_id 
		Set @listtype_id = @p_listtype_id
	End
	If @p_order Is Not Null And @p_order>0
		Exec SP_ReOrder 'T_EFYLIB_LISTTYPE', 'C_ORDER', 'PK_LISTTYPE', @listtype_id, @p_order,''
	Select @listtype_id  NEW_ID
	COMMIT TRANSACTION
	SET NOCOUNT OFF
Return 0
Go
/*
Exec dbo.EfyLib_ListtypeUpdate 1,'DMGD_BCKTKT','Bao cao KT-KT',1,'','HOAT_DONG','NQ'
Select * From T_EFYLIB_LISTTYPE 
*/
-------------------------------------------------------------------------------------------------------------------------------------
-- Y nghia:	 Xoa mot hay nhieu loai danh muc
CREATE PROCEDURE dbo.EfyLib_ListtypeDelete
	@p_listtype_id_list nvarchar(2000) -- Chua danh sach ID cua cac loai danh muc can xoa
WITH ENCRYPTION
AS
	SET NOCOUNT ON
	Declare @listtype_id int
	-- Tao bang trung gian
	Create Table #T_EFYLIB_LISTTYPE(PK_LISTTYPE int)
	Exec Sp_ListToTable @p_listtype_id_list, 'PK_LISTTYPE', '#T_EFYLIB_LISTTYPE', ','

	--Kiem tra rang buoc kieu khoa ngoai.Trong truong hop da duoc chon ->khong xoa duoc
	Select TOP 1 @listtype_id = FK_LISTTYPE From T_EFYLIB_LIST Where FK_LISTTYPE In (Select PK_LISTTYPE From #T_EFYLIB_LISTTYPE)
	If (@listtype_id Is Not Null)  And (@listtype_id > 0) 
	Begin
		Select 'Khong the xoa duoc cac doi tuong da chon' RET_ERROR
		Return -100
	End 
	Delete T_EFYLIB_LISTTYPE Where PK_LISTTYPE In (Select PK_LISTTYPE From #T_EFYLIB_LISTTYPE) 
	SET NOCOUNT OFF
Return 0
GO
--dbo.EfyLib_ListtypeDelete '5,2'
-- Select * From T_EFYLIB_LISTTYPE 
