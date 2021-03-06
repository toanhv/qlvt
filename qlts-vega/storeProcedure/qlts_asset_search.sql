use [qlts-vega]
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Search_GetAll') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Search_GetAll
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Search_Component_GetAll') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Search_Component_GetAll
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Search_User_GetAll') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Search_User_GetAll
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_getBeginDate]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_getBeginDate]
GO
/**===============================================================================================================================================*/
CREATE PROCEDURE dbo.qlts_Search_GetAll
	@userId		varchar(20)		-- ID NSD tai san
	,@group		varchar(50)		-- nhom tai san
	,@type		varchar(50)		-- loai tai san
	,@textsearch	nvarchar(2000)	-- Chuoi tim kiem
	,@p_page						int = 1		--so trang
	,@p_number_record_per_page		int = 15	-- so ban ghi tren trang	
	,@permission					int = 0	-- so ban ghi tren trang	
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
			,C_UPDATED_TIME datetime
			,C_NUMBER int
		)
	
		Set @sql = ' insert into #T_TABLE_TEMP(FK_ASSETS,C_CODE,C_NAME,C_TYPE,C_GROUP,C_INFO,C_VALUE,C_BEGIN_DATE,C_DEPRECIATION_DATE,C_STATUS,C_FILE_NAME,C_UPDATED_TIME,C_NUMBER) 
						Select a.PK_FIXED_ASSETS, a.C_CODE, a.C_NAME, a.C_TYPE, a.C_GROUP, a.C_INFO, a.C_VALUE, a.C_BEGIN_DATE, a.C_DEPRECIATION_DATE, a.C_STATUS,dbo.[f_getAllAttachFile_profile](a.[PK_FIXED_ASSETS], ' + char(39) + 'T_FIXED_ASSETS' + char(39) + '), a.C_UPDATED_TIME, a.C_NUMBER '
		Set @sql = @sql + ' From T_FIXED_ASSETS a, T_USER_ASSETS b where a.PK_FIXED_ASSETS = b.FK_ASSETS And a.C_CODE <>'+ char(39)+''+ char(39)+' And a.C_CODE is not null'
		if(@userId is not null and @userId <> '')
			begin
				Set @sql = @sql + ' and b.C_ID = ' + char(39) + @userId + char(39) 
			end
		if(@group is not null and @group <> '')
			begin
				Set @sql = @sql + ' and a.C_GROUP = ' + char(39)  + @group + char(39) 
			end
		if(@type is not null and @type <> '')
			begin
				Set @sql = @sql + ' and a.C_TYPE = ' + char(39)  + @type + char(39) 
			end
		if(@textsearch is not null and @textsearch <> '')
			begin
				Set @sql = @sql + ' and (a.C_TEMP like ' + char(39) + '%' + dbo.Lower2Upper(@textsearch) + '%' + char(39) 
				Set @sql = @sql + ' or b.C_TEMP like ' + char(39) + '%' + dbo.Lower2Upper(@textsearch) + '%'+ char(39) + ')'
			end
		if(@permission = 0)
			begin
				Set @sql = @sql + ' and a.C_TYPE <> ' + char(39)  + 'SERVER' + char(39) 
			end
		Set @sql = @sql + ' Order by a.C_UPDATED_TIME DESC '
		--Print(@sql)
		Exec (@sql)
--		If @textsearch <> '' and @textsearch is not null
--			Begin
--				Set @sql = ' insert into #T_TABLE_TEMP(FK_ASSETS,C_CODE,C_NAME,C_TYPE,C_GROUP,C_INFO,C_VALUE,C_BEGIN_DATE,C_DEPRECIATION_DATE,C_STATUS,C_FILE_NAME,C_UPDATED_TIME,C_NUMBER) 
--						Select a.PK_SUB_ASSETS, a.C_CODE, a.C_NAME, a.C_TYPE, a.C_GROUP, a.C_INFO, a.C_VALUE, a.C_BEGIN_DATE, a.C_DEPRECIATION_DATE, a.C_STATUS,dbo.[f_getAllAttachFile_profile](a.PK_SUB_ASSETS, ' + char(39) + 'T_SUB_ASSETS' + char(39) + '), a.C_UPDATED_TIME, a.C_NUMBER '
--				Set @sql = @sql + ' From T_SUB_ASSETS a, T_USER_ASSETS b where a.PK_SUB_ASSETS = b.FK_ASSETS And a.C_CODE <>'+ char(39)+''+ char(39)+' And a.C_CODE is not null'
--				if(@userId is not null and @userId <> '')
--					begin
--						Set @sql = @sql + ' and b.C_ID = ' + char(39)  + @userId + char(39) 
--					end
--				if(@group is not null and @group <> '')
--					begin
--						Set @sql = @sql + ' and a.C_GROUP = ' + char(39)  + @group + char(39) 
--					end
--				if(@type is not null and @type <> '')
--					begin
--						Set @sql = @sql + ' and a.C_TYPE = ' + char(39)  + @type + char(39) 
--					end
--				if(@textsearch is not null and @textsearch <> '')
--					begin
--						Set @sql = @sql + ' and (a.C_TEMP like ' + char(39) + '%' + dbo.Lower2Upper(@textsearch) + '%' + char(39) 
--						Set @sql = @sql + ' or b.C_TEMP like ' + char(39)  + '%' + dbo.Lower2Upper(@textsearch) + '%' + char(39) + ')'
--					end
--				--Print(@sql)
--				Set @sql = @sql + ' Order by a.C_UPDATED_TIME DESC  '
--				Exec (@sql)
--			End
		
	select @total = count(*) from #T_TABLE_TEMP
	SELECT *
			,@total TOTAL_RECORD
			--,convert(varchar(30), A.C_BEGIN_DATE, 103) C_BEGIN_DATE
			,case
				when year(A.C_BEGIN_DATE) > 1900 then convert(varchar(30), A.C_BEGIN_DATE, 103) 
				else ''
			end C_BEGIN_DATE	
			,case
				when year(A.C_DEPRECIATION_DATE) > 1900 then convert(varchar(30), A.C_DEPRECIATION_DATE, 103) 
				else 'Ch&#432;a x&#225;c &#273;&#7883;nh'
			end C_DEPRECIATION_DATE
	From #T_TABLE_TEMP A
	Where A.P_ID >((@p_page-1) * @p_number_record_per_page) 
			and A.P_ID <= @p_page * @p_number_record_per_page
		Order By A.C_UPDATED_TIME DESC
SET NOCOUNT OFF
Return 0
Go
/**
	Exec qlts_Search_GetAll '','','','PIN',1,15 
	Exec qlts_Search_GetAll '','','','',1,15 
	Exec qlts_Search_GetAll '184','NHOM_CCDC','MAY_MOC_THIET_BI','',1,15 
*/
CREATE PROCEDURE dbo.qlts_Search_Component_GetAll
	@assetid			varchar(50)
AS
SET NOCOUNT ON
	Declare @sql nvarchar(500)
	Set @sql = ' Select  PK_SUB_ASSETS C_CODE, (C_CODE + ' + char(39) + ' - ' + char(39) + '+ C_NAME) C_NAME'
	Set @sql = @sql + ' From T_SUB_ASSETS '
	If(@assetid <> '')
		Set @sql = @sql + ' Where FK_FIXED_ASSETS = ' + char(39) + @assetid + char(39)
	Set @sql = @sql + ' Order By C_NAME '
	--Print(@sql)
	Exec(@sql)
SET NOCOUNT OFF
Return 0
GO
/**
	Exec qlts_Search_Component_GetAll 'd9eb7a84-05ac-4343-8acb-20a2430be6c4'
*/
CREATE PROCEDURE dbo.qlts_Search_User_GetAll
	@assetid		varchar(50)
As
SET NOCOUNT ON
	Select PK_USER_ASSETS
			,C_ID C_REGISTER_USERID
			,dbo.[f_getBeginDate](C_ID, @assetid) C_BEGIN_DATE
			,C_NAME
			,C_STATUS
			,convert(varchar(30),C_DATE_USE, 103) C_DATE_USE
			,convert(varchar(30),C_DATE_REGISTER, 103) C_DATE_REGISTER
			,(select distinct FK_STAFF from T_ASSETS_STATUS Where FK_ASSETS = @assetid and C_TYPE = 'MT') As C_STAFF_APPROVED 	
	From T_USER_ASSETS 
	Where FK_ASSETS = @assetid	
SET NOCOUNT OFF
Return 0
GO
/**
	Exec [qlts_Search_User_GetAll] '{D96C9671-C2E8-4184-B836-E48B2BB3565C}'
*/
/*
Nguoi tao: TOANHV
Ngay tao: 09/12/2010
Y nghia: lay danh sach cac file dinh kem
*/
CREATE FUNCTION dbo.[f_getBeginDate](
	@userid		varchar(20)			-- ID NSD
	,@assetid	varchar(60)			-- ID Tai san
)
Returns varchar(500)
WITH ENCRYPTION
As
BEGIN
	Declare @sql varchar(500)
		Set @sql = ''
		Select top 1 @sql = @sql + convert(varchar, C_BEGIN_DATE, 103)
		From T_VARIABLE_ASSETS
		Where FK_ASSETS = @assetid and C_REGISTER_USERID = @userid --and C_WORK = 'CAP_NHAT_TAI_SAN'
		Order By C_BEGIN_DATE
	Return @sql
END
GO
/**
	select dbo.[f_getBeginDate]('1','{1bb62702-73dc-439e-a455-f4559f5dc162}')
*/