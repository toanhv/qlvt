use [qlts]
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Manager_Update') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Manager_Update
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Asset_Component_Update') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Asset_Component_Update
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Manager_GetAll') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Manager_GetAll
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Manager_GetSingle') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Manager_GetSingle
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[qlts_Delete]') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[qlts_Delete]
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[qlts_Componet_GetAll]') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[qlts_Componet_GetAll]
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[qlts_Asset_Fluctuations]') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[qlts_Asset_Fluctuations]
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[qlts_getAll_Asset]') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[qlts_getAll_Asset]
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[qlts_Fluctuations_getAll]') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[qlts_Fluctuations_getAll]
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[qlts_shopping_update]') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[qlts_shopping_update]
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Shopping_staff_register') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Shopping_staff_register
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Shopping_progress_getall') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Shopping_progress_getall
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_getAllAttachFile_profile]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_getAllAttachFile_profile]
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_getAllUserForAsset]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_getAllUserForAsset]
GO
---******************************* STORE PROCEDURE PROGRAMMING ******************************************** 
/*
	Creater: Toanhv
	Date: 22/11/2010
	Hieu chinh: cuongnh{
		Ngay hieu chinh: 15/02/2011
		Noi dung hieu chinh: bat loi trung ma tai san.
	}
*/
CREATE PROCEDURE [dbo].qlts_Manager_Update
	@assetId					Varchar(50)				-- ID tai san
	,@code						Varchar(100)			-- Ma tai san
	,@name						Nvarchar(500)			-- Ten tai san
	,@type						Varchar(100)			-- Loai tai san
	,@group						Varchar(100)			-- nhom tai san
	,@info						Nvarchar(2000)			-- Thong tin tai san
	,@value						Nvarchar(200)			-- gia tri tai san
	,@datebegin					Varchar(30)				-- Ngay bat dau su dung
	,@dateend					Varchar(30)				-- ngay het khau hao
	,@status					Varchar(30)				-- Trang thai hien tai tai san
	,@staffId					Varchar(10)				-- ID NSD dung ten tai san
	,@staffName					nvarchar(200)			-- name NSD dung ten tai san
	,@listId					Varchar(500)			-- danh sach NSD, Phong ban su dung tai san
	,@listName					nvarchar(3000)			-- danh sach NSD, Phong ban su dung tai sanv
	,@listImage					Varchar(300)			-- danh sach anh cua tai san	
	,@xmldate					xml						-- danh sach anh cua tai san	
	,@assetsStatus				varchar(50) = ''		-- Trang thai tai san DE_NGHI_MUA || DANG_SU_DUNG || MUON_TRA
	,@detailStatus				int = 0
	,@workCode					varchar(50) = 'CAP_NHAT_TAI_SAN'
	,@approve_id				int = 0
	,@approve_name				nvarchar(200) = ''
	,@register_date				varchar(30) = 'NULL'
	,@register_id				int = 0
	,@register_name				nvarchar(200) = ''
	,@numbers					varchar(10) = ''
WITH ENCRYPTION
AS
	Declare @sNewWorkId	Varchar(50)
			,@sNewId Varchar(50)
			,@iIndex smallint
			,@sRecordId Varchar(50)
			,@iname nvarchar(50)
			,@iCount smallint
			,@count int
	SET NOCOUNT ON
	Select @count = count(*) From T_FIXED_ASSETS Where C_CODE = @code and (@assetId is null or @assetId = '') and C_CODE <> '' 
	If @count > 0 
		Begin
			Select 'Ma tai san da ton tai!' RET_ERROR
			Return -100
		End
	BEGIN TRANSACTION
	
	If(@assetId is not null and @assetId <> '')
		Begin
			Update T_ASSETS_STATUS
			Set C_STATUS = 'DANG_SU_DUNG'
			WHERE FK_ASSETS = @assetId AND substring(C_STATUS,1,2) <> 'MT'
			Delete from T_VARIABLE_ASSETS where FK_ASSETS = @assetId and C_REGISTER_USERID in (select C_REGISTER_USERID from T_FIXED_ASSETS where PK_FIXED_ASSETS = @assetId)
			INSERT INTO [T_VARIABLE_ASSETS]
				   ([PK_VARIABLE_ASSETS]
				   ,[FK_ASSETS]
				   ,[C_DATE]
				   ,[C_WORK]
				   ,[C_CONTENT]
				   ,[C_VALUE]
				   ,[C_BEGIN_DATE]
				   ,[C_DEPRECIATION_DATE]
				   ,[C_STATUS]
				   ,[C_REGISTER_USERID]
				   ,[C_REGISTER_USERNAME]
			)
			 VALUES(
				   newid()
				   ,@assetId
				   ,getdate()
				   ,@workCode
				   ,''
				   ,@value
				   ,@datebegin
				   ,@dateend
				   ,@status
				   ,@staffId
				   ,@staffName
			)
			Update T_FIXED_ASSETS Set
				C_CODE = @code
				,C_NAME = @name
				,C_TYPE = @type
				,C_GROUP = @group
				,C_INFO = @info
				,C_VALUE = @value
				,C_BEGIN_DATE = @datebegin
				,C_DEPRECIATION_DATE = @dateend
				,C_STATUS = @status
				,C_REGISTER_USERID = @staffId
				,C_REGISTER_USERNAME = @staffName
				,C_USER_NAME_LIST = @listName
				,C_XML_DATA = @xmldate
				,C_NUMBER	= @numbers
				,C_TEMP		= dbo.Lower2Upper(@code + ' ' + @name + ' ' + @info + ' ' + @staffName + ' ' + @listName)
			Where PK_FIXED_ASSETS = @assetId
			If(@listId is not null and @listId <> '')
				Begin
					Delete from T_USER_ASSETS where FK_ASSETS = @assetId
					Delete from T_VARIABLE_ASSETS where FK_ASSETS = @assetId
					Set @listId = @listId
					Set @listName  = @listName
					Set @iCount = dbo.f_ListLen(@listId,',')
					Set @iIndex = 1 
					While @iIndex <= @iCount
						Begin
							Set @sRecordId = dbo.f_ListGetAt(@listId,@iIndex,',')							
							Set @iname	   = dbo.f_ListGetAt(@listName,@iIndex,';')	
							If @sRecordId is not null And @sRecordId <> ''
								Begin
									Insert into T_USER_ASSETS(
										PK_USER_ASSETS
										,FK_ASSETS
										,C_ID
										,C_NAME
										,C_STATUS
										,C_TEMP
									)
									values(
										newid()
										,@assetId
										,@sRecordId
										,@iname
										,'DANG_SU_DUNG'
										,dbo.Lower2Upper(@iname)
									)
									--------------------------------------------
									INSERT INTO [T_VARIABLE_ASSETS]
										   ([PK_VARIABLE_ASSETS]
										   ,[FK_ASSETS]
										   ,[C_DATE]
										   ,[C_WORK]
										   ,[C_CONTENT]
										   ,[C_VALUE]
										   ,[C_BEGIN_DATE]
										   ,[C_DEPRECIATION_DATE]
										   ,[C_STATUS]
										   ,[C_REGISTER_USERID]
										   ,[C_REGISTER_USERNAME]
									)
									 VALUES(
										   newid()
										   ,@assetId
										   ,getdate()
										   ,@workCode
										   ,''
										   ,@value
										   ,@datebegin
										   ,@dateend
										   ,@status
										   ,@sRecordId
										   ,@iname
									)							
								End
							Set @iIndex = @iIndex + 1
						End
				End
			Set @sNewId = @assetId
		End
	Else
		Begin
			Set @sNewId = newid()
			INSERT INTO [T_FIXED_ASSETS](
				[PK_FIXED_ASSETS]
			   ,[C_CODE]
			   ,[C_NAME]
			   ,[C_TYPE]
			   ,[C_GROUP]
			   ,[C_INFO]
			   ,[C_VALUE]
			   ,[C_BEGIN_DATE]
			   ,[C_DEPRECIATION_DATE]
			   ,[C_STATUS]
			   ,[C_REGISTER_USERID]
			   ,[C_REGISTER_USERNAME]
			   ,[C_XML_DATA]
			   ,[C_UPDATED_TIME]
			   ,[C_TEMP]
			   ,C_USER_NAME_LIST
			   ,C_REGISTER_DATE
			   ,C_NUMBER
				)
			 VALUES(
			   @sNewId
			   ,@code
			   ,@name
			   ,@type
			   ,@group
			   ,@info
			   ,@value
			   ,@datebegin
			   ,@dateend
			   ,@status
			   ,@staffId
			   ,@staffName
			   ,@xmldate
			   ,getdate()
			   ,dbo.Lower2Upper(@code + ' ' + @name + ' ' + @info + ' ' + @staffName + ' ' + @listName)
			   ,@listName
			   ,@register_date
			   ,@numbers
				)
			Set @listId = @listId + ',' + @staffId
			Set @listName  = @listName + ';' + @staffName
			If(@listId is not null and @listId <> '')
				Begin
					Delete from T_USER_ASSETS where FK_ASSETS = @sNewId	
					Delete from T_VARIABLE_ASSETS where FK_ASSETS = @sNewId
					Set @iCount = dbo.f_ListLen(@listId,',')
					Set @iIndex = 1 
					While @iIndex <= @iCount
						Begin
							Set @sRecordId = dbo.f_ListGetAt(@listId,@iIndex,',')							
							Set @iname	   = dbo.f_ListGetAt(@listName,@iIndex,';')	
							If @sRecordId is not null And @sRecordId <> ''
								Begin
									Insert into T_USER_ASSETS(
										PK_USER_ASSETS
										,FK_ASSETS
										,C_ID
										,C_NAME
										,C_STATUS
										,C_TEMP
									)
									values(
										newid()
										,@sNewId
										,@sRecordId
										,@iname
										,'DANG_SU_DUNG'
										,dbo.Lower2Upper(@iname)
									)	
									-----------------------------------------------------------------------------------
									INSERT INTO [T_VARIABLE_ASSETS]
										   ([PK_VARIABLE_ASSETS]
										   ,[FK_ASSETS]
										   ,[C_DATE]
										   ,[C_WORK]
										   ,[C_CONTENT]
										   ,[C_VALUE]
										   ,[C_BEGIN_DATE]
										   ,[C_DEPRECIATION_DATE]
										   ,[C_STATUS]
										   ,[C_REGISTER_USERID]
										   ,[C_REGISTER_USERNAME]
									)
									 VALUES(
										   newid()
										   ,@sNewId
										   ,getdate()
										   ,@workCode
										   ,''
										   ,@value
										   ,@datebegin
										   ,@dateend
										   ,@status
										   ,@sRecordId
										   ,@iname
									)						
								End
							Set @iIndex = @iIndex + 1
						End
				End
		End	
		-- Xu ly file dinh kem
		If @listImage <> ''
			Begin
				Delete from T_EFYLIB_FILE where FK_DOC = @sNewId and C_TABLE_OBJECT = 'T_FIXED_ASSETS'
				Exec [dbo].[Doc_AttachFileUpdate] @sNewId, 'T_FIXED_ASSETS', '', @listImage, '@!~!@'
			End	
		If @assetsStatus <> '' and @assetsStatus is not null
			Begin
				Delete from T_ASSETS_STATUS where FK_STAFF = @approve_id and FK_ASSETS = @sNewId
				Insert into T_ASSETS_STATUS(
					PK_ASSETS_STATUS
					,FK_ASSETS
					,FK_STAFF
					,C_STAFF_NAME
					,C_STATUS
					,C_DETAIL_STATUS
					,C_REGISTER_DATE
				)Values(
					newid()
					,@sNewId
					,@approve_id
					,@approve_name
					,@assetsStatus
					,@detailStatus
					,getdate()
				)
				Delete from [T_WORK_PROCESS] where FK_STAFF = @staffId and FK_ASSETS = @sNewId
				INSERT INTO [T_WORK_PROCESS]
				   ([PK_WORK_PROCESS]
				   ,[FK_ASSETS]
				   ,[FK_STAFF]
				   ,[C_STAFF_NAME]
				   ,[C_WORK_TYPE]
				   ,[C_CONTENT]
				   ,[C_DATE]
				   ,[C_CREATE_TIME]
				)
				VALUES(
				   newid()
				   ,@sNewId
				   ,@register_id
				   ,@register_name
				   ,@workCode
				   ,dbo.f_GetValueOfXMLtag(@xmldate, 'ghi_chu')
				   ,@register_date
				   ,getdate()
				)
			End
	COMMIT TRANSACTION 
	SET NOCOUNT OFF
Return 0
GO
/*	
	Exec qlts_Manager_Update '','','Dell Vostro 1014','THIET_BI_LAM_VIEC','NHOM_CCDC','Dell Vostro 1014','0','','','','','','','','','Dell Vostro 1014','DE_NGHI_MUA','0','TTP','2286','Lê Bá Minh','2011/5/3 01:02:09','2289','Hoàng Văn Toàn'	

	Exec qlts_Manager_Update '','Dell Vostro 1014','1014','THIET_BI_LAM_VIEC','NHOM_CCDC','Dell Vostro 1014','9900000','2011/5/2 09:48:55','2012/5/2 09:48:55','TOT','2285','Nguyá»…n Thá»‹ Háº¡nh ','1,2289,','Admin; HoÃ ng VÄƒn ToÃ n; ','','Admin; HoÃ ng VÄƒn ToÃ n; !@~!1,2289,!@~!Dell Vostro 1014','','','','','','','',''
*/
/*
	Creater: Toanhv
	Date: 22/11/2010
*/
CREATE PROCEDURE [dbo].qlts_Asset_Component_Update
	@assetId					Varchar(50)				-- ID thanh phan tai san
	,@code						Varchar(100)			-- Ma tai san
	,@name						Nvarchar(500)			-- Ten tai san
	,@type						Varchar(100)			-- Loai tai san
	,@group						Varchar(100)			-- nhom tai san
	,@info						Nvarchar(2000)			-- Thong tin tai san
	,@value						Nvarchar(200)			-- gia tri tai san
	,@datebegin					Varchar(30)				-- Ngay bat dau su dung
	,@dateend					Varchar(30)				-- ngay het khau hao
	,@status					Varchar(30)				-- Trang thai hien tai tai san
	,@staffId					Varchar(10)				-- ID NSD dung ten tai san
	,@staffName					nvarchar(200)			-- name NSD dung ten tai san
	,@assetFk					Varchar(50)				-- ID tai san
	,@listImage					nvarchar(2000)			-- danh sach anh tai san
	,@xmldate					xml						-- danh sach anh cua tai san	
	,@numbers					varchar(10)
AS
	Declare @sNewWorkId	Varchar(50),@sNewId Varchar(50), @iIndex smallint, @sRecordId Varchar(50), @sRecordTransitionId Varchar(50), @iCount smallint
	SET NOCOUNT ON
	BEGIN TRANSACTION
	
	If(@assetId is not null and @assetId <> '')
		Begin
			Update T_SUB_ASSETS Set
				C_CODE = @code
				,C_NAME = @name
				,C_TYPE = @type
				,C_GROUP = @group
				,C_INFO = @info
				,C_VALUE = @value
				,C_BEGIN_DATE = @datebegin
				,C_DEPRECIATION_DATE = @dateend
				,C_STATUS = @status
				,FK_FIXED_ASSETS = @assetFk
				,C_XML_DATA = @xmldate
				,C_NUMBER	= @numbers
				,C_TEMP		= dbo.Lower2Upper(@code + ' ' + @name + ' ' + @info + ' ' + @staffName)
			Where [PK_SUB_ASSETS] = @assetId
			Delete from T_USER_ASSETS where FK_ASSETS = @assetId
			Insert into T_USER_ASSETS(
				PK_USER_ASSETS
				,FK_ASSETS
				,C_ID
				,C_NAME
				,C_TEMP
			)
			values(
				newid()
				,@assetId
				,@staffId
				,@staffName
				,dbo.Lower2Upper(@staffName)
			)	
			Delete from T_VARIABLE_ASSETS where FK_ASSETS = @assetId
			INSERT INTO [T_VARIABLE_ASSETS]
				   ([PK_VARIABLE_ASSETS]
				   ,[FK_ASSETS]
				   ,[C_DATE]
				   ,[C_WORK]
				   ,[C_CONTENT]
				   ,[C_VALUE]
				   ,[C_BEGIN_DATE]
				   ,[C_DEPRECIATION_DATE]
				   ,[C_STATUS]
				   ,[C_REGISTER_USERID]
				   ,[C_REGISTER_USERNAME]
			)
			 VALUES(
				   newid()
				   ,@assetId
				   ,getdate()
				   ,'CAP_NHAT_TAI_SAN'
				   ,'C&#7853;p nh&#7853;t t&#224;i s&#7843;n'
				   ,@value
				   ,@datebegin
				   ,@dateend
				   ,@status
				   ,@staffId
				   ,@staffName
			)
			Set @sNewId = @assetId				
		End
	Else
		Begin
			Set @sNewId = newid()
			INSERT INTO T_SUB_ASSETS(
				[PK_SUB_ASSETS]
			   ,[FK_FIXED_ASSETS]
			   ,[C_CODE]
			   ,[C_NAME]
			   ,[C_TYPE]
			   ,[C_GROUP]
			   ,[C_INFO]
			   ,[C_VALUE]
			   ,[C_BEGIN_DATE]
			   ,[C_DEPRECIATION_DATE]
			   ,[C_STATUS]
			   ,[C_XML_DATA]
			   ,[C_UPDATED_TIME]
			   ,[C_TEMP]
			   ,C_NUMBER
				)
			 VALUES(
			   @sNewId
			   ,@assetFk
			   ,@code
			   ,@name
			   ,@type
			   ,@group
			   ,@info
			   ,@value
			   ,@datebegin
			   ,@dateend
			   ,@status
			   ,@xmldate
			   ,getdate()
			   ,dbo.Lower2Upper(@code + ' ' + @name + ' ' + @info + ' ' + @staffName)
			   ,@numbers
				)
			If(@staffId is not null and @staffId <> '')
				Begin					
					Insert into T_USER_ASSETS(
						PK_USER_ASSETS
						,FK_ASSETS
						,C_ID
						,C_NAME
						,C_TEMP
					)
					values(
						newid()
						,@sNewId
						,@staffId
						,@staffName
						,dbo.Lower2Upper(@staffName)
					)	
					Delete from T_VARIABLE_ASSETS where FK_ASSETS = @sNewId
					INSERT INTO [T_VARIABLE_ASSETS]
						   ([PK_VARIABLE_ASSETS]
						   ,[FK_ASSETS]
						   ,[C_DATE]
						   ,[C_WORK]
						   ,[C_CONTENT]
						   ,[C_VALUE]
						   ,[C_BEGIN_DATE]
						   ,[C_DEPRECIATION_DATE]
						   ,[C_STATUS]
						   ,[C_REGISTER_USERID]
						   ,[C_REGISTER_USERNAME]
					)
					 VALUES(
						   newid()
						   ,@sNewId
						   ,getdate()
						   ,'CAP_NHAT_TAI_SAN'
						   ,'C&#7853;p nh&#7853;t t&#224;i s&#7843;n'
						   ,@value
						   ,@datebegin
						   ,@dateend
						   ,@status
						   ,@staffId
						   ,@staffName
					)
				End
		End	
		-- Xu ly file dinh kem
		If @listImage <> '' and @listImage is not null
			Begin
				Delete from T_EFYLIB_FILE where FK_DOC = @sNewId and C_TABLE_OBJECT = 'T_SUB_ASSETS'
				Exec [dbo].[Doc_AttachFileUpdate] @sNewId, 'T_SUB_ASSETS', '', @listImage, '@!~!@'
			End		
	COMMIT TRANSACTION 
	SET NOCOUNT OFF
Return 0
GO
/*
	Exec qlts_Asset_Component_Update '','ádfádf','ádfádf','','','','','','','','1','Admin','{40ABB68E-022E-451D-A86D-070CFEEF47B1}'
*/
/**===============================================================================================================================================*/
CREATE PROCEDURE dbo.qlts_Manager_GetAll
	@staffId						varchar(20)		
	,@departmentId					varchar(20)		
	,@unitId						varchar(20)		
	,@textsearch					nvarchar(200)	
	,@assetId						varchar(50)		
	,@p_page						int = 1		
	,@p_number_record_per_page		int = 15	
	,@status						varchar(50) = ''
	,@leftMenu						varchar(50) = ''
AS
SET NOCOUNT ON
	Declare @sql nvarchar(500), @total varchar(10) 
	Create Table #T_TABLE_TEMP(P_ID int IDENTITY (1,1), FK_ASSETS uniqueidentifier)
	Set @sql = ' insert into #T_TABLE_TEMP(FK_ASSETS) Select distinct a.PK_FIXED_ASSETS From T_FIXED_ASSETS a '
	Set @sql = @sql + ' inner join T_USER_ASSETS u on a.PK_FIXED_ASSETS = u.FK_ASSETS '
	if(@status <> '' and @status is not null)
		begin
			Set @sql = @sql + ' inner join T_ASSETS_STATUS b on a.PK_FIXED_ASSETS = b.FK_ASSETS '	
			Set @sql = @sql + ' and b.C_STATUS = ' + char(39) + @status + char(39)
			if(@staffId is not null and @staffId <> '')
				Set @sql = @sql + ' and b.FK_STAFF = ' + @staffId
		end
	if(@leftMenu = 'register')
		begin
			if(@staffId is not null and @staffId <> '')
				Set @sql = @sql + ' and u.C_ID = ' + @staffId
		end
	Set @sql = @sql + ' where 1=1 '	
	if(@textsearch is not null and @textsearch <> '')
		begin
			Set @sql = @sql + ' and a.C_TEMP like ' + char(39) + '%' + dbo.Lower2Upper(@textsearch) + '%' + char(39)
		end
	if(@staffId is not null and @staffId <> '')
		begin
			Set @sql = @sql + ' and (a.C_CREATER_ID = ' + @staffId
			Set @sql = @sql + ' or a.C_REGISTER_USERID = ' + @staffId
			if(@status <> '' and @status is not null)
				Set @sql = @sql + ' or b.FK_STAFF = ' + @staffId
			Set @sql = @sql + ' or u.C_ID = ' + @staffId + ')'
		end
	--Set @sql = @sql + ' Order By a.C_UPDATED_TIME DESC '
	Print(@sql)
	Exec (@sql)
	select @total = count(*) from #T_TABLE_TEMP
	SELECT b.[PK_FIXED_ASSETS]
      ,b.[C_CODE]
	  ,@total TOTAL_RECORD
	  --,c.C_STATUS
      ,b.[C_NAME]
      ,b.[C_TYPE]
      ,b.[C_GROUP]
	  ,dbo.[f_getAllAttachFile_profile](b.[PK_FIXED_ASSETS], 'T_FIXED_ASSETS') C_FILE_NAME 
      ,b.[C_INFO]
      ,b.[C_VALUE]
	  ,case
			when year(b.[C_BEGIN_DATE]) > 2000 then convert(varchar(30), b.[C_BEGIN_DATE], 103)
			else '' 
      end C_BEGIN_DATE
	  ,case 
			when year(b.C_REGISTER_DATE) > 2000 then convert(varchar(30), b.C_REGISTER_DATE, 103)
	  end C_REGISTER_DATE
	  ,case
			when year(b.[C_DEPRECIATION_DATE]) > 1900 then convert(varchar(30), b.[C_DEPRECIATION_DATE], 103) 
			else 'Ch&#432;a x&#225;c &#273;&#7883;nh'
		end C_DEPRECIATION_DATE
      ,b.[C_STATUS]
	  ,b.C_NUMBER
      ,b.[C_REGISTER_USERID]
      ,b.[C_REGISTER_USERNAME]
      ,b.[C_XML_DATA]
      ,b.[C_UPDATED_TIME]
      ,b.[C_TEMP] 
	  ,d.C_STATUS status_assets
	From T_FIXED_ASSETS b
	Inner join #T_TABLE_TEMP a on b.PK_FIXED_ASSETS = a.FK_ASSETS
	Left join T_USER_ASSETS d on d.FK_ASSETS = a.FK_ASSETS and C_ID = @staffId
	Where a.P_ID >((@p_page-1) * @p_number_record_per_page) 
			and a.P_ID <= @p_page * @p_number_record_per_page
	Order By b.C_UPDATED_TIME DESC
SET NOCOUNT OFF
Return 0
Go
/**
	Exec qlts_Manager_GetAll '2284','','254','','',1,15,'','register' 
*/
CREATE PROCEDURE dbo.qlts_Manager_GetSingle
@assetid varchar(50)
,@table  varchar(50)
,@key	 varchar(50)
AS
SET NOCOUNT ON
	Declare @sql nvarchar(500)
	Set @sql = 'Select a.*,convert(varchar(30), a.[C_BEGIN_DATE], 103) [C_BEGIN_DATE], convert(varchar(30), a.[C_DEPRECIATION_DATE], 103) [C_DEPRECIATION_DATE], dbo.f_GetValueOfXMLtag(a.C_XML_DATA, ' + char(39) + 'ghi_chu' + char(39) + ') GHI_CHU '
	if @table = 'T_FIXED_ASSETS'
		Set @sql = @sql + ' ,convert(varchar(30), a.C_REGISTER_DATE, 103) C_REGISTER_DATE'
	Set @sql = @sql + '	,dbo.f_getAllUserForAsset(a.' + @key + ') USERS '
	Set @sql = @sql + ' From ' + @table + ' a '
	Set @sql = @sql + ' Where a.' + @key + ' = ' + char(39) + @assetid + char(39)
	Print(@sql)
	Exec (@sql)
SET NOCOUNT OFF
Return 0
GO
/**
	Exec qlts_Manager_GetSingle '{4713077F-3262-4FA2-ADCA-999B7CABEC03}','T_FIXED_ASSETS','PK_FIXED_ASSETS'
*/
/*
Nguoi tao: TOANHV
Ngay tao : 16/09/2009
Y nghia : Xoa mot hoac nhieu bao cao
*/
CREATE PROCEDURE [dbo].[qlts_Delete]
	@pSentIdList      				Nvarchar(4000)	-- Danh sach id van ban can xoa
	,@table							varchar(30)		-- bang can xoa du lieu
	,@id							varchar(30)		-- id cot can xoa
	WITH ENCRYPTION
AS		
	Declare @sql varchar(1000)
	SET NOCOUNT ON	
	If @pSentIdList <> '' And @pSentIdList is not null
		Begin
			Create Table #T_ALL(FK_ASSETS Uniqueidentifier)
			Exec Sp_ListToTable @pSentIdList, 'FK_ASSETS', '#T_ALL', ','
			SET XACT_ABORT ON -- Dat che do tu dong Rollback neu co loi xay ra
			BEGIN TRANSACTION
				Begin 
					if(@id = 'PK_FIXED_ASSETS')
						begin
							Delete from T_SUB_ASSETS where FK_FIXED_ASSETS in (select FK_ASSETS from #T_ALL)
						end
					Set @sql = 'Delete from ' + @table + ' Where ' + @id + ' in (select FK_ASSETS from #T_ALL)'
					Exec(@sql)
					delete from T_USER_ASSETS where FK_ASSETS in (select FK_ASSETS from #T_ALL)
					delete from T_VARIABLE_ASSETS where FK_ASSETS in (select FK_ASSETS from #T_ALL)
					delete from T_EFYLIB_FILE where FK_DOC in (select FK_ASSETS from #T_ALL)
					delete from T_WORK_PROCESS where FK_ASSETS in (select FK_ASSETS from #T_ALL)
					delete from T_ASSETS_STATUS where FK_ASSETS in (select FK_ASSETS from #T_ALL)
				End
			COMMIT TRANSACTION
		End
	SET NOCOUNT OFF
	Return 0
GO	
/**
	Exec [qlts_Delete] '{40ABB68E-022E-451D-A86D-070CFEEF47B1},{40ABB68E-022E-451D-A86D-070CFEEF47B1}','T_FIXED_ASSETS','PK_FIXED_ASSETS'
*/
CREATE PROCEDURE dbo.[qlts_Componet_GetAll]
	@assetid	varchar(50)				-- id tai san cha
AS
SET NOCOUNT ON
	Select *
			,convert(varchar(30), [C_BEGIN_DATE], 103) [C_BEGIN_DATE]
			,convert(varchar(30), [C_DEPRECIATION_DATE], 103) [C_DEPRECIATION_DATE]
			,dbo.[f_getAllAttachFile_profile](PK_SUB_ASSETS, 'T_SUB_ASSETS') C_FILE_NAME 
	From T_SUB_ASSETS
	Where FK_FIXED_ASSETS = @assetid
SET NOCOUNT OFF
Return 0
GO
/*
Nguoi tao: TOANHV
Ngay tao: 15/07/2009
Y nghia: lay danh sach cac file dinh kem
*/
CREATE FUNCTION dbo.[f_getAllAttachFile_profile](
	@pDocId	varchar(50)			-- Id van ban
	,@table	varchar(100) 
)
Returns nvarchar(4000)
WITH ENCRYPTION
As
BEGIN
	Declare @insert nvarchar(4000)
		Set @insert = ''
		Select @insert = @insert + convert(nvarchar(200), C_FILE_NAME) + '@!~!@'
		From T_EFYLIB_FILE
		Where FK_DOC = @pDocId and C_TABLE_OBJECT = @table
	Return @insert
END
GO
/**------------------------------------------------------------------------------------------------------------------------------------------------*/
CREATE PROCEDURE [dbo].[qlts_Asset_Fluctuations]
	@table				varchar(50)				-- ten bang du lieu
	,@pkTable			varchar(50)				-- PK bang du lieu
	,@assetId			varchar(50)				-- ID tai sang
	,@assetIdMaster		varchar(50)				-- ID Tai san cha
	,@value				varchar(50)				-- Gia tri tai san
	,@status			varchar(50)				-- Trang thai tai san
	,@assetInfo			nvarchar(200)			-- name nguoi dung ten tai san
	,@dateBegin			varchar(50)				-- Ngay bat dau su dung
	,@dateEnd			varchar(50)				-- Ngay het khau hao
	,@userIdList		varchar(500)			-- danh sach nguoi su dung
	,@userNameList		nvarchar(2000)			-- danh sach ten nguoi su dung
	,@userMasterId		varchar(50)				-- ID nguoi dung ten tai san
	,@userMasterName	nvarchar(200)			-- name nguoi dung ten tai san
	,@work				varchar(50)				-- dau muc cong viec
	,@content			nvarchar(500)			-- dau muc cong viec
	,@listImage			nvarchar(1000)			-- file dinh kem
AS
SET NOCOUNT ON
BEGIN TRANSACTION
Declare @sql nvarchar(1000), @iIndex int, @iCount int, @sRecordId varchar(50), @iname nvarchar(200), @valueList nvarchar(1000), @newid	varchar(50)
Begin
	If(@userMasterId is not null and @userMasterId <> '')
		Begin
			Delete from T_USER_ASSETS where FK_ASSETS = @assetId and C_ID in (select C_REGISTER_USERID from T_FIXED_ASSETS where PK_FIXED_ASSETS = @assetId)
			Insert into T_USER_ASSETS(
				PK_USER_ASSETS
				,FK_ASSETS
				,C_ID
				,C_NAME
				,C_TEMP
			)
			values(
				newid()
				,@assetId
				,@userMasterId
				,@userMasterName
				,dbo.Lower2Upper(@userMasterName)
			)
			Set @newid = newid()
			INSERT INTO [T_VARIABLE_ASSETS]
				   ([PK_VARIABLE_ASSETS]
				   ,[FK_ASSETS]
				   ,[C_DATE]
				   ,[C_WORK]
				   ,[C_CONTENT]
				   ,[C_VALUE]
				   ,[C_BEGIN_DATE]
				   ,[C_DEPRECIATION_DATE]
				   ,[C_STATUS]
				   ,[C_REGISTER_USERID]
				   ,[C_REGISTER_USERNAME]
			)
			 VALUES(
				   @newid
				   ,@assetId
				   ,getdate()
				   ,@work
				   ,@content
				   ,@value
				   ,@dateBegin
				   ,@dateEnd
				   ,@status
				   ,@userMasterId
				   ,@userMasterName
			)
			If @listImage <> ''
				Begin
					Delete from T_EFYLIB_FILE where FK_DOC = @newid and C_TABLE_OBJECT = 'T_VARIABLE_ASSETS'
					Exec [dbo].[Doc_AttachFileUpdate] @newid, 'T_VARIABLE_ASSETS', '', @listImage, '@!~!@'
				End	
			Update T_FIXED_ASSETS Set C_REGISTER_USERID = @userMasterId, C_REGISTER_USERNAME = @userMasterName where PK_FIXED_ASSETS = @assetId
		End
	If(@userIdList <> '' and @userIdList is not null and @userIdList <> ',')
		Begin
			Delete from T_USER_ASSETS where FK_ASSETS = @assetId
			Set @userIdList = @userIdList --+ @userMasterId
			Set @userNameList = @userNameList --+ @userMasterName
			Set @iCount = dbo.f_ListLen(@userIdList,',')
			Set @iIndex = 1 
			While @iIndex <= @iCount
				Begin
					Set @sRecordId = dbo.f_ListGetAt(@userIdList,@iIndex,',')					
					Set @iname	   = dbo.f_ListGetAt(@userNameList,@iIndex,';')	
					If @sRecordId is not null And @sRecordId <> '' and @iname <> '' and @iname is not null
						Begin
							Insert into T_USER_ASSETS(
								PK_USER_ASSETS
								,FK_ASSETS
								,C_ID
								,C_NAME
								,C_TEMP
							)
							values(
								newid()
								,@assetId
								,@sRecordId
								,@iname
								,dbo.Lower2Upper(@iname)
							)
							Set @newid = newid()
							INSERT INTO [T_VARIABLE_ASSETS]
								   ([PK_VARIABLE_ASSETS]
								   ,[FK_ASSETS]
								   ,[C_DATE]
								   ,[C_WORK]
								   ,[C_CONTENT]
								   ,[C_VALUE]
								   ,[C_BEGIN_DATE]
								   ,[C_DEPRECIATION_DATE]
								   ,[C_STATUS]
								   ,[C_REGISTER_USERID]
								   ,[C_REGISTER_USERNAME]
							)VALUES(
								   @newid
								   ,@assetId
								   ,getdate()
								   ,@work
								   ,@content
								   ,@value
								   ,@dateBegin
								   ,@dateEnd
								   ,@status
								   ,@sRecordId
								   ,@iname
							)
							If @listImage <> ''
								Begin
									Delete from T_EFYLIB_FILE where FK_DOC = @newid and C_TABLE_OBJECT = 'T_VARIABLE_ASSETS'
									Exec [dbo].[Doc_AttachFileUpdate] @newid, 'T_VARIABLE_ASSETS', '', @listImage, '@!~!@'
								End								
						End
					Set @iIndex = @iIndex + 1
				End
				Update T_FIXED_ASSETS Set C_USER_NAME_LIST = @userNameList where PK_FIXED_ASSETS = @assetId
		End	
	if((@userIdList is null Or @userIdList = '') and (@userMasterId is null or @userMasterId = ''))
		begin
			Set @newid = newid()
			INSERT INTO [T_VARIABLE_ASSETS]
				   ([PK_VARIABLE_ASSETS]
				   ,[FK_ASSETS]
				   ,[C_DATE]
				   ,[C_WORK]
				   ,[C_CONTENT]
				   ,[C_VALUE]
				   ,[C_BEGIN_DATE]
				   ,[C_DEPRECIATION_DATE]
				   ,[C_STATUS]				   
			)VALUES(
				   @newid
				   ,@assetId
				   ,getdate()
				   ,@work
				   ,@content
				   ,@value
				   ,@dateBegin
				   ,@dateEnd
				   ,@status
			)
		end	
	Set @sql = 'Update ' + @table + ' Set C_UPDATED_TIME = getdate() '
	If(@assetIdMaster <> '')
		Set @sql = @sql + ' ,FK_FIXED_ASSETS = ' + char(39) + @assetIdMaster + char(39)
	If(@value <> '')
		Set @sql = @sql + ' ,C_VALUE = ' + char(39) + @value + char(39)
	If(@status <> '')
		Set @sql = @sql + ' ,C_STATUS = ' + char(39) + @status + char(39)
	If(@dateBegin <> '')
		Set @sql = @sql + ' ,C_BEGIN_DATE = ' + char(39) + @dateBegin + char(39)
	If(@dateEnd <> '')
		Set @sql = @sql + ' ,C_DEPRECIATION_DATE = ' + char(39) + @dateEnd + char(39)
	If(@assetInfo <> '')
		Set @sql = @sql + ' ,C_INFO = ' + char(39) + @assetInfo + char(39)
	
	Set @sql = @sql + ' Where ' + @pkTable + ' = ' + char(39) + @assetId + char(39) 
	--Print(@sql)
	Exec(@sql)
End
COMMIT TRANSACTION
SET NOCOUNT OFF
Return 0
GO
/**
	Exec [qlts_Asset_Fluctuations] 'T_FIXED_ASSETS','PK_FIXED_ASSETS','{B78C3609-1EF3-4C2D-B156-79B9F9422601}','','10$','TOT','','2011/4/26 08:16:41','2012/4/26 08:16:41','2236,','LÃª Quá»‘c Há»“ng; ','1','Admin','DIEU_CHUYEN','fsÃ ','2011_04_26_0216000000505220!~!hot tp vinht.txt'
*/
/**=================================================================================================================================*/
CREATE PROCEDURE dbo.[qlts_getAll_Asset]
AS
SET NOCOUNT ON
	select distinct a.PK_FIXED_ASSETS C_CODE, (a.C_CODE + ' - ' + a.C_NAME) C_NAME
	from T_FIXED_ASSETS a, T_VARIABLE_ASSETS b
	where a.PK_FIXED_ASSETS = b.FK_ASSETS and b.C_WORK not in ('THANH_LY,THU_HOI,BAN_TAISAN,TIEU_HUY')
SET NOCOUNT OFF
Return 0
GO
/**
	Exec [qlts_getAll_Asset]
*/
CREATE PROCEDURE dbo.[qlts_Fluctuations_getAll]
@assetId		varchar(50)				-- ID tai san
AS
SET NOCOUNT ON
	Select * 
			,convert(varchar, C_DATE, 103) DATE
			,convert(varchar, C_BEGIN_DATE, 103) C_BEGIN_DATE
			,convert(varchar, C_DEPRECIATION_DATE, 103) C_DEPRECIATION_DATE
			,dbo.[f_getAllAttachFile_profile](PK_VARIABLE_ASSETS, 'T_VARIABLE_ASSETS') C_FILE_NAME 
	From T_VARIABLE_ASSETS
	Where FK_ASSETS = @assetId and C_STATUS <> 'CAP_NHAT_TAI_SAN'
	Order By C_DATE Desc
SET NOCOUNT OFF
Return 0
Go
/**
	Exec [qlts_Fluctuations_getAll] '{D9EB7A84-05AC-4343-8ACB-20A2430BE6C4}', 1, 31, 184 
*/
/**
* CREATE: Toanhv
* DATE: 8/5/2011
*/
CREATE PROCEDURE dbo.[qlts_shopping_update]
	@staff_id_list			varchar(100)	-- NSD hien thoi
	,@staff_name_list		nvarchar(2000)	-- Name NSD
	,@asset_id				varchar(50)		-- ID tai san
	,@status_list			varchar(300)	-- Trang thai cap nhat cho ng gui
	,@detail_list			varchar(100)
	,@workType				varchar(30)
	,@content				nvarchar(2000)	-- Y kien duyet
	,@staffId				int	
	,@staffName				nvarchar(200)
	,@date					varchar(30)
	,@dilimiter				varchar(20)
AS
SET NOCOUNT ON
BEGIN TRANSACTION
DECLARE @count int
		,@i int
		,@staff_id	varchar(10)
		,@status	varchar(30)
		,@detail	varchar(10)
		,@name		nvarchar(200)
SET @count = dbo.f_ListLen(@staff_id_list, @dilimiter)
SET @i = 1
WHILE @i <= @count
	BEGIN
		SET @staff_id = dbo.f_ListGetAt(@staff_id_list, @i, @dilimiter)
		SET @status = dbo.f_ListGetAt(@status_list, @i, @dilimiter)
		SET @name = dbo.f_ListGetAt(@staff_name_list, @i, @dilimiter)
		SET @detail = dbo.f_ListGetAt(@detail_list, @i, @dilimiter)
		IF EXISTS (SELECT * FROM T_ASSETS_STATUS WHERE FK_STAFF = @staff_id AND FK_ASSETS = @asset_id)
			BEGIN
				UPDATE T_ASSETS_STATUS 
				SET C_STATUS = @status
					,C_DETAIL_STATUS = @detail
				WHERE FK_STAFF = @staff_id AND FK_ASSETS = @asset_id
			END
		ELSE
			IF @staff_id*1 > 0
				BEGIN 
					INSERT INTO T_ASSETS_STATUS(
						FK_ASSETS
						,FK_STAFF
						,C_STAFF_NAME
						,C_STATUS
						,C_DETAIL_STATUS
						,C_REGISTER_DATE
					)VALUES(
						@asset_id
						,@staff_id
						,@name
						,@status
						,@detail
						,getdate()
					)
				END
		SET @i = @i + 1
	END
INSERT INTO T_WORK_PROCESS(
	FK_ASSETS
	,FK_STAFF
	,C_STAFF_NAME
	,C_WORK_TYPE
	,C_CONTENT
	,C_DATE
	,C_CREATE_TIME
)VALUES(
	@asset_id				
	,@staffId
	,@staffName		
	,@workType
	,@content		
	,@date	
	,getdate()				
)
COMMIT TRANSACTION
SET NOCOUNT OFF
SELECT @asset_id
RETURN 0
GO
/**
	Exec [qlts_shopping_update] '2285!#!2286','Nguyá»…n Thá»‹ Hiá»n!#!LÃª BÃ¡ Minh','{4713077F-3262-4FA2-ADCA-999B7CABEC03}','MS_CHO_DUYET!#!MS_DA_DUYET','0!#!0','CTK','ok',2286,'LÃª BÃ¡ Minh','2011/05/15 11:44:51','!#!'
*/
CREATE PROCEDURE dbo.qlts_Shopping_staff_register
@asset_id varchar(50)
AS
SET NOCOUNT ON
	SELECT * FROM T_USER_ASSETS WHERE FK_ASSETS = @asset_id
SET NOCOUNT OFF
RETURN 0
GO
------------------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.qlts_Shopping_progress_getall
@asset_id varchar(50)
AS
SET NOCOUNT ON
	SELECT p.*
			,convert(varchar, p.C_DATE, 103) DATE
	FROM T_WORK_PROCESS p
	WHERE FK_ASSETS = @asset_id
	ORDER BY C_DATE DESC
SET NOCOUNT OFF
RETURN 0
GO
------------------------------------------------------------------------------------------------------------------------------------------------------------
CREATE FUNCTION dbo.[f_getAllUserForAsset](
	@asset_id		varchar(50)
)
Returns varchar(1000)
WITH ENCRYPTION
As
BEGIN
	Declare @result varchar(1000)
		Set @result = ''
		Select @result = @result + convert(varchar(10), C_ID) + ','
		From T_USER_ASSETS
		Where FK_ASSETS = @asset_id and substring(C_STATUS,1,2) <> 'MT'
	if len(@result) < 1
		Set @result = ','
	Return substring(@result,1,len(@result)-1)
END
GO
/**
 *  SELECT dbo.[f_getAllUserForAsset]('{21DDF66C-4DE4-49D0-94ED-E1FA372CC70E}')
 */