if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListGenerateXMLBySingleListType]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListGenerateXMLBySingleListType]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[EfyLib_ListGenerateXMLByAllListType]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[EfyLib_ListGenerateXMLByAllListType]
GO

-------------------------------------------------------------------------------------------------------------------------------------------------
--Nguoi tao: Nguyen Tuan Anh
--Ngay tao:  30/5/2007
--Y nghia:   Tao file XML cho 1 loai danh muc
-------------------------------------------------------------------------------------------------------------------------------------------------

CREATE PROCEDURE dbo.[EfyLib_ListGenerateXMLBySingleListType]
	@p_status Nvarchar(100)			-- trang thai hoat dong( = HOAT_DONG : Hoat dong; NGUNG_HOAT_DONG : Ngung hoat dong ; = '' : tat ca)
	,@p_listtype_id int				-- Loai danh muc (0-tat ca)
	,@p_is_recordset bit = 1		-- 1-Tra lai recordset, 0- Khong tra lai recordset
WITH ENCRYPTION
AS
	Declare @v_str_sql nvarchar(max)
	SET NOCOUNT ON
	--Tao bang tam luu tat ca cac ban ghi thoa man
	If @p_is_recordset = 1
		Create Table #T_XML (C_CODE nvarchar(100), XML_DATA nvarchar(max));

	set @v_str_sql = 'Declare @v_xml XML, @listtype_code nvarchar(100), @v_text nvarchar(max); '

	Set @v_str_sql = @v_str_sql + 'Select @v_xml = ( 
					SELECT 
						1 as Tag, 
						NULL as parent,
						C_CODE As [item!1!c_code!element]
						,C_NAME As [item!1!c_name!element]
						,C_STATUS As [item!1!c_status!element]
						,PK_LIST As [item!1!id!element]
						From T_EFYLIB_LIST
						Where 1=1 '

	If @p_listtype_id Is Not Null And @p_listtype_id > 0 
		Set @v_str_sql = @v_str_sql + ' And FK_LISTTYPE = ' + convert(varchar,@p_listtype_id)

	If @p_status Is Not Null And @p_status <> '' 
		Set @v_str_sql = @v_str_sql + ' And C_STATUS = ' + Char(39) + @p_status + Char(39)

	Set @v_str_sql = @v_str_sql + ' Order by C_ORDER'
	Set @v_str_sql = @v_str_sql + ' For XML EXPLICIT);'

	Set @v_str_sql = @v_str_sql + ' Select @listtype_code = C_CODE From T_EFYLIB_LISTTYPE Where PK_LISTTYPE = '
	Set @v_str_sql = @v_str_sql + convert(varchar,@p_listtype_id) + ' ;'

	set @v_str_sql = @v_str_sql + '	Set @v_text = ' + char(39) + '<?xml version="1.0" encoding="UTF-8"?><root><data_list>' + char(39)
	set @v_str_sql = @v_str_sql + ' + Convert(nvarchar(max),@v_xml) + ' + char(39) + '</data_list></root>' + char(39) + ';'
	set @v_str_sql = @v_str_sql + ' Insert Into #T_XML(C_CODE,XML_DATA) Values(@listtype_code,@v_text);'

	--print(@v_str_sql)
	Exec (@v_str_sql)

	If @p_is_recordset=1
		Select C_CODE,XML_DATA From #T_XML

	SET NOCOUNT OFF
Return 0
Go
-- EfyLib_ListGenerateXMLBySingleListType 1,1,1

-------------------------------------------------------------------------------------------------------------------------------------------------
--Nguoi tao: Nguyen Tuan Anh
--Ngay tao:  30/5/2007
--Y nghia:   Tao file XML cho tat ca loai danh muc
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.[EfyLib_ListGenerateXMLByAllListType]
	@p_status Nvarchar(100)		-- trang thai hoat dong( = HOAT_DONG : Hoat dong; NGUNG_HOAT_DONG : Ngung hoat dong ; = '' : tat ca)
	,@p_listtype_id int			-- loai danh muc(0-tat ca)
WITH ENCRYPTION
AS
	SET NOCOUNT ON

	Create Table #T_XML (C_CODE nvarchar(100),XML_DATA nvarchar(max));
	Declare @listtype_id int

	If @p_listtype_id > 0
		If @p_status <> '' And @p_status Is Not Null
			DECLARE cur_listtype CURSOR For Select PK_LISTTYPE From T_EFYLIB_LISTTYPE Where C_STATUS = 'HOAT_DONG' And PK_LISTTYPE = @p_listtype_id
		Else
			DECLARE cur_listtype CURSOR For Select PK_LISTTYPE From T_EFYLIB_LISTTYPE Where PK_LISTTYPE = @p_listtype_id
	Else
		If @p_status <> '' And @p_status Is Not Null
			DECLARE cur_listtype CURSOR For Select PK_LISTTYPE From T_EFYLIB_LISTTYPE Where C_STATUS = 'HOAT_DONG'
		Else
			DECLARE cur_listtype CURSOR For Select PK_LISTTYPE From T_EFYLIB_LISTTYPE		

	Open cur_listtype
	FETCH NEXT FROM cur_listtype INTO @listtype_id
	WHILE @@FETCH_STATUS = 0
		BEGIN
			Exec EfyLib_ListGenerateXMLBySingleListType @p_status, @listtype_id, 0 -- Khong tra lai RecordSet
			FETCH NEXT FROM cur_listtype INTO @listtype_id
		END
	CLOSE cur_listtype
	DEALLOCATE cur_listtype
	
	Select C_CODE,XML_DATA From #T_XML Where XML_DATA Is Not Null

	Return
GO
-- Exec EfyLib_ListGenerateXMLByAllListType 1,1
