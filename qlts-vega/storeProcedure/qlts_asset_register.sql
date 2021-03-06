use [qlts-vega]
/******************************** LIST PROCEDURE *********************************************************/
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_register_GetAll') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_register_GetAll
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_register_update') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_register_update
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_approved_update') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_approved_update
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_User_Approved_Getsing') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_User_Approved_Getsing
GO
/**===============================================================================================================================================*/
/*
	Creater: phongtd
	Date: 01/05/2011
	idea: Lay danh sach tai san

*/

CREATE PROCEDURE dbo.qlts_register_GetAll
	@sStaffId						Varchar(10)			-- Id can bo muon tai san 
	,@sStatus						varchar(50)			-- Trang thai 
	,@textsearch					nvarchar(2000)		-- Chuoi tim kiem
	,@p_page						int = 1				-- so trang
	,@p_number_record_per_page		int = 15			-- so ban ghi tren trang	
WITH ENCRYPTION
AS
SET NOCOUNT ON
	Declare @sql nvarchar(4000), @total varchar(10) 
	Create Table #T_TABLE_TEMP(
			P_ID int IDENTITY (1,1) 
			,FK_ASSETS uniqueidentifier
			,C_CODE varchar(100) 
			,C_NAME nvarchar(2000)
			,C_TYPE varchar(50)
			,C_GROUP varchar(50) 
			,C_INFO nvarchar(2000) 
			,C_VALUE varchar(50) 
			,C_BEGIN_DATE datetime 
			,C_DEPRECIATION_DATE datetime
			,C_STATUS varchar(50) 
			,C_FILE_NAME nvarchar(300)
		)	
		Set @sql = ' insert into #T_TABLE_TEMP(FK_ASSETS,C_CODE,C_NAME,C_TYPE,C_GROUP,C_INFO,C_VALUE,C_BEGIN_DATE,C_DEPRECIATION_DATE,C_STATUS,C_FILE_NAME) Select distinct a.PK_FIXED_ASSETS, a.C_CODE, a.C_NAME, a.C_TYPE, a.C_GROUP, a.C_INFO, a.C_VALUE, a.C_BEGIN_DATE, a.C_DEPRECIATION_DATE, a.C_STATUS,dbo.[f_getAllAttachFile_profile](a.[PK_FIXED_ASSETS], ' + char(39) + 'T_FIXED_ASSETS' + char(39) + ') '
		if(@sStaffId is not null and @sStaffId <> '' And @sStatus is not null and @sStatus <> '')
			begin
				Set @sql = @sql + ' From T_FIXED_ASSETS a, T_USER_ASSETS b, T_ASSETS_STATUS c where a.PK_FIXED_ASSETS = b.FK_ASSETS And a.PK_FIXED_ASSETS = c.FK_ASSETS And a.C_CODE <>'+ char(39)+''+ char(39)+' And a.C_CODE is not null'
				Set @sql = @sql + ' And ((b.C_ID = ' +char(39)+ @sStaffId + char(39)
				Set @sql = @sql + ' and b.C_STATUS = ' +char(39)+ @sStatus + char(39) + ')'
				Set @sql = @sql + ' or (c.FK_STAFF = ' +char(39)+ @sStaffId + char(39)
				Set @sql = @sql + ' and c.C_STATUS = ' +char(39)+ @sStatus + char(39) + '))'
			end
		else 
			Begin 
				Set @sql = @sql + ' From T_FIXED_ASSETS a, T_USER_ASSETS b where a.PK_FIXED_ASSETS = b.FK_ASSETS And a.C_CODE <>'+ char(39)+''+ char(39)+' And a.C_CODE is not null'
			End 
		if(@textsearch is not null and @textsearch <> '')
			begin
				Set @sql = @sql + ' and (a.C_TEMP like ' + char(39) + '%' + dbo.Lower2Upper(@textsearch) + '%' + char(39) 
				Set @sql = @sql + ' or b.C_TEMP like ' + char(39) + '%' + dbo.Lower2Upper(@textsearch) + '%'+ char(39) + ')'
			end
		Set @sql = @sql + ' and  a.C_GROUP = '+char(39)+ 'KHO_TAI_SAN ' + char(39)
		Print(@sql)
		Exec (@sql)	
	select @total = count(*) from #T_TABLE_TEMP
	SELECT *
			,@total TOTAL_RECORD
			,convert(varchar(30), A.C_BEGIN_DATE, 103) C_BEGIN_DATE
			,case
				when year(A.C_DEPRECIATION_DATE) > 1900 then convert(varchar(30), A.C_DEPRECIATION_DATE, 103) 
				else 'Ch&#432;a x&#225;c &#273;&#7883;nh'
			end C_DEPRECIATION_DATE
	From #T_TABLE_TEMP A
	Where A.P_ID >((@p_page-1) * @p_number_record_per_page) 
			and A.P_ID <= @p_page * @p_number_record_per_page
		Order By A.[C_BEGIN_DATE] DESC, A.[C_DEPRECIATION_DATE]
SET NOCOUNT OFF
Return 0
Go
/**
	Exec qlts_register_GetAll '','','',1,15 
*/
----------------------------------------------------------------------------
/*
	Creater: phongtd
	Date: 03/05/2011
	idea: Cap nhat thong tin dang ky muon tai san

*/
CREATE PROCEDURE [dbo].qlts_register_update
	@sAssetId					Varchar(50)				-- ID tai san
	,@sStaffId					Varchar(10)				-- ID NSD muon tai san
	,@sStaffName				nvarchar(200)			-- Ten NSD muon tai san
	,@sApproveId				Varchar(10)				-- ID nguoi phe duyet 
	,@sApprovePositionName		nvarchar(200)			-- Ten NSD phe duyet tai san
	,@sStatus					Varchar(30)				-- Trang thai 
	,@iDetailStatus				int = 1
	,@sWorkType					Varchar(30)				-- Ma cong viec
	,@sContent					nvarchar(4000)			-- Noi dung
	,@sDate						varchar(50)				-- Ngay thuc hien
WITH ENCRYPTION
AS
	SET NOCOUNT ON
	BEGIN TRANSACTION
		If(@sAssetId is not null and @sAssetId <> ''And @sStaffId is not null and @sStaffId <> '' And @sApproveId is not null and @sApproveId <> '')
		Begin
			Delete from T_USER_ASSETS where FK_ASSETS = @sAssetId and C_ID = @sStaffId
			Insert into T_USER_ASSETS(
				FK_ASSETS
				,C_ID
				,C_NAME
				,C_STATUS
				,C_DATE_REGISTER
			)
			values(
				@sAssetId
				,@sStaffId
				,@sStaffName
				,@sStatus
				,@sDate
			)
			Delete from T_ASSETS_STATUS where FK_ASSETS = @sAssetId and FK_STAFF = @sApproveId And C_TYPE = 'MT'
			Insert into T_ASSETS_STATUS(
				FK_ASSETS
				,FK_STAFF
				,C_STAFF_NAME
				,C_TYPE
				,C_STATUS
				,C_DETAIL_STATUS
			)
			values(
				@sAssetId
				,@sApproveId
				,@sApprovePositionName
				,'MT'
				,@sStatus
				,@iDetailStatus
			)
			Insert into T_WORK_PROCESS(
				FK_ASSETS
				,FK_STAFF
				,C_STAFF_NAME
				,C_WORK_TYPE
				,C_CONTENT
				,C_DATE
			)
			values(
				@sAssetId
				,@sStaffId
				,@sStaffName
				,@sWorkType
				,@sContent
				,@sDate
			)
		End
	COMMIT TRANSACTION 
	SET NOCOUNT OFF
Return 0
GO
/**

Exec qlts_register_update '{A68F969E-40A1-4923-AC24-15616D366E8E}','1','QTHT - Admin','2284','QTTS - Hoàng Mỹ Hạnh','MT_CHO_DUYET','1','','yhdhdfhdfh','2011/5/31 00:21:49'

*/
--------------------------------------
/*
	Creater: phongtd
	Date: 10/05/2011
	idea: Duyet dang ky muon tai san

*/
CREATE PROCEDURE [dbo].[qlts_approved_update]
	@pObjectId      				varchar(50)		-- Id doi tuong 
	,@pAssetId						varchar(50)		-- Id tai san
	,@pStaffId						varchar(50)		-- Id nguoi duyet 
	,@sApprovePositionName			nvarchar(200)	-- Ten NSD phe duyet tai san
	,@pApprovedStatus				varchar(50)		-- Trang thai ( 1: DONG_Y; 0: TU_CHOI)
	WITH ENCRYPTION
AS		
	Declare @sql varchar(1000), @sContent nvarchar(4000)
	SET NOCOUNT ON	
	If @pObjectId <> '' And @pObjectId is not null
		Begin
			SET XACT_ABORT ON -- Dat che do tu dong Rollback neu co loi xay ra
			BEGIN TRANSACTION
				if(@pApprovedStatus = 1)	-- Dong y cho muon 
					Begin 
						set @sContent = '&#272;&#7891;ng &#253; cho m&#432;&#7907;n'
						update T_USER_ASSETS set C_STATUS = 'MT_DA_DUYET',C_DATE_USE = getdate() where PK_USER_ASSETS = @pObjectId And substring(C_STATUS,1,2)= 'MT'
						update T_USER_ASSETS set C_STATUS = 'TRA_LAI' where FK_ASSETS = @pAssetId And C_STATUS = 'DANG_SU_DUNG'
						update T_USER_ASSETS set C_STATUS = 'MT_BI_TU_CHOI' where FK_ASSETS = @pAssetId And C_STATUS = 'MT_CHO_DUYET'
						update T_ASSETS_STATUS set C_STATUS = 'MT_DA_DUYET' where FK_ASSETS = @pAssetId And FK_STAFF = @pStaffId And C_TYPE = 'MT'
					End
				if(@pApprovedStatus = 0)	-- Tu choi 
					Begin
						 set @sContent = 'T&#7915; ch&#7889;i'	
						 update T_USER_ASSETS set C_STATUS = 'MT_BI_TU_CHOI' where PK_USER_ASSETS = @pObjectId  And substring(C_STATUS,1,2)= 'MT'
						 update T_ASSETS_STATUS set C_STATUS = 'MT_BI_TU_CHOI' where FK_ASSETS = @pAssetId And FK_STAFF = @pStaffId And C_TYPE = 'MT'
					End  
				
				Insert into T_WORK_PROCESS(
				FK_ASSETS
				,FK_STAFF
				,C_STAFF_NAME
				,C_WORK_TYPE
				,C_CONTENT
				,C_DATE
				)
				values(
					@pAssetId
					,@pStaffId
					,@sApprovePositionName
					,'Ph&#234; duy&#7879;t'
					,@sContent
					,getdate()
				)
				
			COMMIT TRANSACTION
		End
	SET NOCOUNT OFF
	Return 0
GO


------------------------------------------------------------------------
/*
	Creater: phongtd
	Date: 15/05/2011
	idea: Lay thong tin nguoi phe duyet

*/
CREATE PROCEDURE dbo.qlts_User_Approved_Getsing
	@pAssetId						varchar(50)		-- Id tai san
WITH ENCRYPTION
As
SET NOCOUNT ON
	Select PK_ASSETS_STATUS
			,FK_STAFF				 	
	From T_ASSETS_STATUS 
	Where FK_ASSETS = @pAssetId And C_TYPE = 'MT'	
SET NOCOUNT OFF
Return 0
GO
/*
Exec qlts_User_Approved_Getsing '{61BA2202-4BA2-434A-8387-E7EA1ECDE7CB}'
*/
-----------------------------------------------------------------------------------------