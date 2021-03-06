use [qlts-vega]
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_asset_report_001') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_asset_report_001
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_asset_report_002') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_asset_report_002
GO
/**===============================================================================================================================================*/
CREATE PROCEDURE dbo.qlts_asset_report_001
	@status					varchar(500)	-- Tinh trang tai san
	,@begin_from_date		varchar(50)		-- ID tai san
	,@begin_to_date			varchar(50)		-- ID thanh phan tai san
	,@type					varchar(50) = ''
	,@group					varchar(50) = ''
AS
SET NOCOUNT ON
BEGIN
	Declare @sql nvarchar(4000)
	Set @sql = ''
	Set @sql = 'Select *
					,convert(varchar(30), [C_BEGIN_DATE], 103) BEGIN_DATE
					,case
						when year([C_DEPRECIATION_DATE]) > 1900 then convert(varchar(30), [C_DEPRECIATION_DATE], 103) 
						else ' + char(39) + 'Ch&#432;a x&#225;c &#273;&#7883;nh' + char(39)
					+ ' end DEPRECIATION_DATE
					,case
						when C_USER_NAME_LIST <> ' + char(39) + ';' + char(39) + ' and C_USER_NAME_LIST <> ' + char(39) + char(39) + ' and C_USER_NAME_LIST is not null then 
							(' + char(39) + 'Ng&#432;&#7901;i &#273;&#7913;ng t&#234;n t&#224;i s&#7843;n: ' + char(39) + ' + C_REGISTER_USERNAME + ' + char(39) + ' <br>' + char(39) + ' + C_USER_NAME_LIST) else (' + char(39) + 'Ng&#432;&#7901;i &#273;&#7913;ng t&#234;n t&#224;i s&#7843;n: ' + char(39) + ' + C_REGISTER_USERNAME)
						end C_USER
					,dbo.f_GetListNamebyCode(' + char(39) + 'DM_TINHTRANG' + char(39) + ',C_STATUS) TINH_TRANG
					,(convert(varchar,convert(datetime,' + char(39) + @begin_from_date + char(39) + '),103)) fromdate
					,(convert(varchar,convert(datetime,' + char(39) + @begin_to_date + char(39) + '),103)) todate
				
			From T_FIXED_ASSETS
			Where 1=1 '
			if(@type <> '' and @type is not null)
				Begin
					Set @sql = @sql + ' AND C_TYPE = ' + char(39) + @type + char(39)
				End
			if(@group <> '' and @group is not null)
				Begin
					Set @sql = @sql + ' AND C_GROUP = ' + char(39) + @group + char(39)
				End
			if(@status <> '' and @status is not null)
				Begin
					Set @sql = @sql + ' AND C_STATUS = ' + char(39) + @status + char(39)
				End
			Set @sql = @sql + ' and datediff(d, C_BEGIN_DATE,' + char(39) + @begin_from_date + char(39) + ') <= 0
							and datediff(d, C_BEGIN_DATE,' + char(39) + @begin_to_date + char(39) + ') >= 0
						Order By C_BEGIN_DATE, C_STATUS '
	Print(@sql)
		Exec(@sql)
END
SET NOCOUNT OFF
Return 0
Go
/**
	 Exec qlts_asset_report_001 'TOT','01/01/2010','01/01/2011', '', ''
*/
CREATE PROCEDURE dbo.qlts_asset_report_002
	@unitid		varchar(10)
	,@fromdate	varchar(30)
	,@todate	varchar(30)
	,@type					varchar(50) = ''
	,@group					varchar(50) = ''
AS
SET NOCOUNT ON
Declare @sql nvarchar(2000)
set @sql = 'select a.C_CODE, a.C_NAME, a.C_INFO, a.C_VALUE, convert(varchar, a.C_BEGIN_DATE, 103) BEGIN_DATE, case when year([C_DEPRECIATION_DATE]) > 1900 then convert(varchar(50), a.C_DEPRECIATION_DATE, 103) else' + char(39) + '' + char(39) + ' end DEPRECIATION_DATE, dbo.f_GetListNamebyCode(' + char(39) + 'DM_TINHTRANG' + char(39) + ',a.C_STATUS) C_STATUS, a.C_REGISTER_USERID, a.C_REGISTER_USERNAME, a.C_USER_NAME_LIST, b.C_NAME UNIT_NAME '
set @sql = @sql + ',(convert(varchar,convert(datetime,' + char(39) + @fromdate + char(39)  + '),103)) fromdate, (convert(varchar,convert(datetime,' + char(39) + @todate + char(39)  + '),103)) todate '
set @sql = @sql + ' From T_FIXED_ASSETS a, T_USER_ASSETS b '	
set @sql = @sql + ' Where a.PK_FIXED_ASSETS = b.FK_ASSETS '
set @sql = @sql + ' and datediff(d, a.C_BEGIN_DATE,' + char(39) + @fromdate + char(39)  + ') <= 0 and datediff(d, a.C_BEGIN_DATE,' + char(39) + @todate + char(39)  + ') >= 0 '
if(@unitid is not null and @unitid <> '')
	set @sql = @sql + ' and b.C_ID = ' + @unitid
if(@type <> '' and @type is not null)
	Begin
		Set @sql = @sql + ' AND a.C_TYPE = ' + char(39) + @type + char(39)
	End
if(@group <> '' and @group is not null)
	Begin
		Set @sql = @sql + ' AND a.C_GROUP = ' + char(39) + @group + char(39)
	End
--Print(@sql)
set @sql = @sql + ' Order By UNIT_NAME '
Exec(@sql)

SET NOCOUNT OFF
Return 0
GO
/**
	Exec qlts_asset_report_002 '31','01/01/2010','01/01/2011', '', ''
*/