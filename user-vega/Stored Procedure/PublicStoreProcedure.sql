if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].SP_UpdateXMLColumn') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[SP_UpdateXMLColumn]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetValueOfXMLtag]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetValueOfXMLtag]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetNumericValueOfXMLtag]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetNumericValueOfXMLtag]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[SP_ReplaceSpecialChar]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[SP_ReplaceSpecialChar]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_ListGetAt]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_ListGetAt]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_InList]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_InList]
GO


if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_ListLen]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_ListLen]
GO

if exists (select * from sysobjects where id = object_id(N'[dbo].[Sp_ListToTable]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_ListToTable]
GO

if exists (select * from sysobjects where id = object_id(N'[dbo].[Sp_ReOrder]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_ReOrder]
GO

if exists (select * from sysobjects where id = object_id(N'[dbo].[Sp_GetFileContent]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_GetFileContent]
GO

if exists (select * from sysobjects where id = object_id(N'[dbo].[Sp_CopyFileContent]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_CopyFileContent]
GO


if exists (select * from sysobjects where id = object_id(N'[dbo].[Sp_GetText]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_GetText]
GO

if exists (select * from sysobjects where id = object_id(N'[dbo].[Sp_CopyText]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_CopyText]
GO


if exists (select * from sysobjects where id = object_id(N'[dbo].[SP_GetOtherValueFromKeyValue]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[SP_GetOtherValueFromKeyValue]
GO

if exists (select * from sysobjects where id = object_id(N'[dbo].[SP_MoveUpDown]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[SP_MoveUpDown]
GO

/**********************************************************************************************************************************/
CREATE Procedure SP_UpdateXMLColumn
	@p_table nvarchar(500),
	@p_xml_column nvarchar(500),
	@p_xml_tag nvarchar(500),
	@p_tag_value nvarchar(4000),
	@p_where_clause nvarchar(4000),
	@p_path_of_xml_tag nvarchar(4000) ='/root/data_list/'
AS
Begin
	Declare @sql nvarchar(4000)
	Set @sql = ' Declare @sql_xml_data nvarchar(4000), @old_xml_tag nvarchar(4000), @new_xml_tag nvarchar(4000); '
	Set @sql = @sql +	'Select @sql_xml_data = '+ @p_xml_column +' from ' + @p_table
	If @p_where_clause Is Not Null And @p_where_clause<>''
		Set @sql = @sql + @p_where_clause
	Set @sql = @sql + ';'
	Set @sql = @sql + 'set @old_xml_tag = ''<'+@p_xml_tag+'>''+dbo.f_GetValueOfXMLtag(@sql_xml_data,' +char(39)+@p_xml_tag+char(39)+')+''</'+@p_xml_tag+'>'';'
	Set @sql = @sql + 'set @new_xml_tag = ''<'+@p_xml_tag+'>''+'+char(39)+ @p_tag_value+char(39)+'+''</'+@p_xml_tag+'>'';'
	Set @sql = @sql + 'Update ' + @p_table + ' Set ' + @p_xml_column + ' = '
	Set @sql = @sql + 'replace(@sql_xml_data,@old_xml_tag,@new_xml_tag)'
	If @p_where_clause Is Not Null And @p_where_clause<>''
		Set @sql = @sql + @p_where_clause
	Exec (@sql)
End
GO
/*
Exec SP_UpdateXMLColumn 'T_temp_file', 'c_text', 'listtype_type','Dat',' where pk_temp_file = 146'
Select c_text from T_temp_file where pk_temp_file = 146;
*/

/**********************************************************************************************************************************
*   Function: f_GetValueOfXMLtag
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Lay gia tri cua mot tag trong 1 xau XML
**********************************************************************************************************************************/
CREATE Function f_GetValueOfXMLtag(@pXML ntext, @pTag nvarchar(500))
Returns nvarchar(4000)
As
Begin

Declare 
	@return_str nvarchar(4000),
	@begin_pos int,
	@end_pos int,
	@find_str nvarchar(500),
	@full_tag nvarchar(500)

	Set @full_tag = '<' + @pTag + '>'
	Set @find_str = '%<' + @pTag + '>%'
	Set @begin_pos = patindex(@find_str,@pXML)
	Set @find_str = '%' + '</' + @pTag + '>%'
	Set @end_pos = patindex(@find_str,@pXML)
	Set @return_str = ''
	If @begin_pos>0 And @end_pos>0
		Set @return_str = substring(@pXML,@begin_pos+len(@full_tag),@end_pos-@begin_pos-len(@full_tag))

	Return @return_str
End
GO

--Select dbo.f_GetValueOfXMLtag(c_received_record_xml_data, '<business_industry>') from t_onegate_record


/**********************************************************************************************************************************
*   Function: f_GetNumericValueOfXMLtag
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Lay gia tri cua mot tag trong 1 xau XML va tra lai gia tri duoi dang SO
**********************************************************************************************************************************/
CREATE Function f_GetNumericValueOfXMLtag(@pXML ntext, @pTag nvarchar(500))
Returns numeric
As
Begin

	Declare 
	@return_str nvarchar(4000),
	@return_numeric numeric

	Set @return_str = dbo.f_GetValueOfXMLtag(@pXML, @pTag)

	If rtrim(@return_str)=''
		Return 0

	Set @return_str = replace(@return_str,',','')
	Set @return_str = replace(@return_str,' ','')
	Set @return_numeric = convert(numeric,@return_str)

	Return @return_numeric

End
GO

-- Select Sum(dbo.f_GetNumericValueOfXMLtag(c_received_record_xml_data, '<business_capital>')) from t_onegate_record

/**********************************************************************************************************************************
*	Viet boi : Luong Thanh Binh
*	Ngay : 29/05/2002
*   Ngay sua : 
*	Chuc nang : Thay the cac ky tu dac biet tren Web
*	Tinh trang : 
*	Tham so : 	
*		Input:
*			+@HTML Dong text html can thay the
**********************************************************************************************************************************/
CREATE PROCEDURE dbo.SP_ReplaceSpecialChar
@HTML nvarchar(2000) OUT
AS
Set @HTML = Replace(@HTML,'<','&lt;')
Set @HTML = Replace(@HTML,'>','&gt;')
Set @HTML = Replace(@HTML,'"','&#34;')
Set @HTML = Replace(@HTML,char(39),'&#39;')
GO

/**********************************************************************************************************************************
*   Function: f_ListGetAt
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Lay gia tri tai vi tri @pPosition cua danh sach @pList
**********************************************************************************************************************************/
CREATE Function f_ListGetAt(@pList nvarchar(4000), @pPosition int, @pDelimiterIN nvarchar(10)=',')
Returns nvarchar(500)
As
Begin
Declare 
	@i numeric,
	@mList nvarchar(4000) ,
	@mPosition int,
	@DelimiterIN_len int


If @pList='' or @pList is null
	Return null

Set @DelimiterIN_len = len(@pDelimiterIN)

Select @mList = @pList

Select @mPosition = charindex(@pDelimiterIN,@mList)

If @mPosition=0 
    If @pPosition<>1
		Return null
    Else
		Return @mList	

Select @i = 1
While (@i<@pPosition)
Begin
	Select @mPosition = charindex(@pDelimiterIN,@mList)
	If @mPosition=0 
		Break
	Select @mList = substring(@mList,@mPosition+@DelimiterIN_len,Len(@mList)-@mPosition)
	Select @i = @i+1
End

If @i<>@pPosition
	Return null
Else
    Begin	
	Select @mPosition = charindex(@pDelimiterIN,@mList)
	If @mPosition = 0
		return @mList
	Else
		Return substring(@mList,1,@mPosition-1)
    End
Return 0
End
GO

/**********************************************************************************************************************************
*   Function: f_ListLen
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Lay so phan tu cua mot danh sach
**********************************************************************************************************************************/
CREATE function f_ListLen(@pList nvarchar(4000),  @pDelimiterIN nvarchar(10)=',')
Returns int
As
Begin
	Declare 
		@i int,
		@mList nvarchar(4000) ,
		@mPosition int
	If @pList='' or @pList is null
		Return 0
	Select @mList = @pList
	Select @i = 1
	While (@mList <>'')
	Begin
		Select @mPosition = charindex(@pDelimiterIN,@mList)
		If @mPosition=0 
			Break
		Select @mList = substring(@mList,@mPosition+1,Len(@mList)-@mPosition)
		Select @i = @i+1
	End
Return  @i
End
GO

/**********************************************************************************************************************************
*   Function: f_InList
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Kiem tra 1 phan tu co trong 1 danh sach hay khong
**********************************************************************************************************************************/
CREATE Function f_InList(@pList nvarchar(4000), @pValue nvarchar(500), @pDelimiterIN nvarchar(10)=',')
Returns int
As
Begin
Declare 
	@i numeric,
	@mList nvarchar(4000) ,
	@mValue nvarchar(500) ,
	@mPosition int,
	@DelimiterIN_len int


If @pList='' or @pList is null
	Return 0

Set @DelimiterIN_len = len(@pDelimiterIN)

Select @mList = @pList

Select @mPosition = charindex(@pDelimiterIN,@mList)

If @mPosition=0 
	Begin
		If @mList = @pValue
			Return 1
		Else
			Return 0
	End


While (@mPosition>0)
Begin
	Select @mPosition = charindex(@pDelimiterIN,@mList)
	If @mPosition=0 
		Break
	Select @mValue = substring(@mList,1,@mPosition-1)
	If @mValue = @pValue
		Return 1
	Select @mList = substring(@mList,@mPosition+@DelimiterIN_len,Len(@mList)-@mPosition)
End

If @mList = @pValue
	Return 1
Else
	Return 0

Return 0

End
GO

-- Select dbo.f_InList('001,002,003,','001',',')

/**********************************************************************************************************************************
*   Procedure: Sp_ListToTable
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Chuyen doi cac gia tri cua mot danh sach thanh gia tri cua cac row trong 1 table
**********************************************************************************************************************************/

CREATE PROCEDURE Sp_ListToTable(
@pList varchar(8000), 
@pFieldName nvarchar(200),
@pTablename nvarchar(200),
@pDelimiterIN nvarchar(10)=',')
AS

Declare 
	@i		numeric,
	@mListLen	numeric,
	@mSql	nvarchar(4000),
	@mItem	nvarchar(500)

Select @i=1
Select @mListLen=0
Set @mListLen = dbo.f_ListLen(@pList,@pDelimiterIN)
if @mListLen = 0
	Return -100

While @i<=@mListLen
Begin
	Set @mItem = dbo.f_ListGetAt(@pList, @i, @pDelimiterIN)
	Exec SP_ReplaceSpecialChar @mItem OUT
	Select @mSql = 'Insert into ' + @pTablename + ' ('+ @pFieldName +') values(' + char(39) + @mItem + char(39) + ')'
	Exec (@mSql)
	Select @i = @i+1
End

GO
/**********************************************************************************************************************************/
CREATE PROCEDURE dbo.SP_ReOrder
	@p_table nvarchar(500),
	@p_order_column nvarchar(500),
	@p_pk_column nvarchar(500),
	@p_object_id nvarchar(500),
	@p_object_order int,
	@p_other_clause nvarchar(500)
AS

	Declare @sql nvarchar(4000), @next_object_id nvarchar(200), @next_order int
	 
	Set @sql = 'Create Table #T_ORDER(PK_OBJ nvarchar(500), C_NEW_ORDER Int IDENTITY(' + Convert(varchar,@p_object_order+1) +',1));'

	Set @sql = @sql + 'Insert Into #T_ORDER(PK_OBJ) Select ' + @p_pk_column + ' From ' + @p_table
	Set @sql = @sql + ' Where (' + @p_pk_column + '<>' + char(39) + @p_object_id + char(39)
	Set @sql = @sql + ' And ' + @p_order_column + '>=' + convert(varchar,@p_object_order)
	Set @sql = @sql + ')'  

	If ltrim(@p_other_clause) Is Not Null And ltrim(@p_other_clause)<>''
		Set @sql = @sql + ' And ' + '(' + ltrim(@p_other_clause) + ')'

	Set @sql = @sql + ' Order By ' +  @p_order_column + ';'

	Set @sql = @sql + 'Update ' + @p_table + ' Set ' + @p_order_column + '=(Select C_NEW_ORDER From #T_ORDER Where PK_OBJ=' + @p_table + '.' + @p_pk_column + ')'
	Set @sql = @sql + ' Where ' + @p_pk_column + ' In (Select PK_OBJ From #T_ORDER);'

	--Select @sql 
	Exec (@sql)

GO

/* 
select * from T_REPORTLIB_TIME order by c_order
Exec SP_ReOrder 'T_REPORTLIB_TIME', 'C_ORDER', 'PK_TIME', 'T1', 2,' FK_TIME Is NULL'
select * from T_REPORTLIB_TIME order by c_order
*/

/**********************************************************************************************************************************/
CREATE PROCEDURE dbo.SP_GetFileContent
	@p_table nvarchar(500),
	@p_file_id_column nvarchar(500),
	@p_file_name_column nvarchar(500),
	@p_file_content_column nvarchar(500),
	@p_file_id int
AS

SET NOCOUNT ON
Declare @sql nvarchar(4000)
Set @sql = 'DECLARE @ptrval varbinary(16),@index int, @offset int, @size int, @length int, @filename nvarchar(500);'
Set @sql = @sql + ' SELECT @ptrval = TEXTPTR(' + rtrim(@p_file_content_column) + '), @length = Datalength(' + rtrim(@p_file_content_column) + ')' + ',@filename=' + rtrim(@p_file_name_column)
Set @sql = @sql + '	FROM ' + rtrim(@p_table) + ' WHERE ' + rtrim(@p_file_id_column) + '=' + convert(varchar,@p_file_id) + ';'
Set @sql = @sql + '	Set @offset = 0;Set @size = 4096;Set @index = 0;'
Set @sql = @sql + ' While (@offset<@length) '
Set @sql = @sql + ' 	Begin '
Set @sql = @sql + ' If @length<=(@offset+@size) '
Set @sql = @sql + ' Begin '
Set @sql = @sql + ' Set @size = @length - @offset '
Set @sql = @sql + ' READTEXT ' + rtrim(@p_table) + '.' + rtrim(@p_file_content_column) + ' @ptrval @offset @size '
Set @sql = @sql + ' Break '
Set @sql = @sql + ' End '
Set @sql = @sql + ' Else '
Set @sql = @sql + ' READTEXT ' + rtrim(@p_table) + '.' + rtrim(@p_file_content_column) + ' @ptrval @offset @size '
Set @sql = @sql + ' Set @index = @index + 1 '
Set @sql = @sql + ' Set @offset = @size*@index '
Set @sql = @sql + ' End; '
Exec (@sql)
GO
/* 
select * from T_WEB_DOC
Exec SP_GetFileContent 'T_WEB_DOC', 'PK_WEB_DOC', 'C_NAME', 'C_CONTENT', 196
select * from T_CPXD_BUILDING_FORM order by c_order
*/

/**********************************************************************************************************************************/
CREATE PROCEDURE dbo.SP_CopyFileContent
	@p_from_table nvarchar(500),
	@p_from_file_id_column nvarchar(500),
	@p_from_file_name_column nvarchar(500),
	@p_from_file_content_column nvarchar(500),
	@p_from_file_id int,
	@p_to_table nvarchar(500),
	@p_to_file_id_column nvarchar(500),
	@p_to_file_name_column nvarchar(500),
	@p_to_file_content_column nvarchar(500),
	@p_to_file_id int
AS
SET NOCOUNT ON
Declare @sql nvarchar(4000)
--Set @sql = 'Select ' + @p_from_file_name_column + ',' + @p_from_file_content_column + ' From ' + @p_table + ' Where ' + @p_from_file_id_column + '=' + convert(varchar, @p_from_file_id)
Set @sql = 'DECLARE @from_ptrval varbinary(16),@to_ptrval varbinary(16), @filename nvarchar(500);'
Set @sql = @sql + ' SELECT @from_ptrval = TEXTPTR(' + rtrim(@p_from_file_content_column) + ')' + ',@filename=' + rtrim(@p_from_file_name_column)
Set @sql = @sql + '	FROM ' + rtrim(@p_from_table) + ' WHERE ' + rtrim(@p_from_file_id_column) + '=' + convert(varchar,@p_from_file_id) + ';'
Set @sql = @sql + ' SELECT @to_ptrval = TEXTPTR(' + rtrim(@p_to_file_content_column) + ')'
Set @sql = @sql + '	FROM ' + rtrim(@p_to_table) + ' WHERE ' + rtrim(@p_to_file_id_column) + '=' + convert(varchar,@p_to_file_id) + ';'
Set @sql = @sql + ' UPDATETEXT ' + rtrim(@p_to_table) + '.' + rtrim(@p_to_file_content_column) + ' @to_ptrval 0 NULL ' + rtrim(@p_from_table) + '.' + rtrim(@p_from_file_content_column) + ' @from_ptrval;'
Set @sql = @sql + ' UPDATE ' + rtrim(@p_to_table) 
Set @sql = @sql + ' SET ' + rtrim(@p_to_file_name_column) + '= @filename' 
Set @sql = @sql + ' WHERE ' + rtrim(@p_to_file_id_column) + '=' + convert(varchar,@p_to_file_id) + ';'
--SELECT @sql
Exec (@sql)
Return
GO
/* 
select * from T_cpxd_temp_file
select * from T_cpxd_document_form
Exec SP_CopyFileContent 'T_CPXD_TEMP_FILE', 'PK_TEMP_FILE', 'C_FILE_NAME', 'C_FILE_CONTENT', 4, 'T_CPXD_DOCUMENT_FORM', 'PK_DOCUMENT_FORM', 'C_FILE_NAME', 'C_CONTENT', 10 
select * from T_CPXD_BUILDING_FORM order by c_order
*/

/**********************************************************************************************************************************/
-- Thu tuc GetText: doc du lieu kieu ntext tu CSDL
CREATE PROCEDURE dbo.SP_GetText
	@p_table nvarchar(500), -- ten bang 
	@p_id_column nvarchar(500), -- ten cot la Primary key cua bang
	@p_text_column nvarchar(500), -- ten cot chua doan VAN BAB can doc
	@p_id int -- ID
AS

SET NOCOUNT ON
Declare @sql nvarchar(4000)
Set @sql = 'DECLARE @ptrval binary(16),@index int, @offset int, @size int, @length int;'
Set @sql = @sql + ' SELECT @ptrval = TEXTPTR(' + rtrim(@p_text_column) + '), @length = Datalength(' + rtrim(@p_text_column) + ')' 
Set @sql = @sql + '	FROM ' + rtrim(@p_table) + ' WHERE ' + rtrim(@p_id_column) + '=' + convert(varchar,@p_id) + ';'
Set @sql = @sql + '	Select @length=@length/2;'

Set @sql = @sql + '	Set @offset = 0;Set @size = 4096;Set @index = 0;'
Set @sql = @sql + ' While (@offset<@length) '
Set @sql = @sql + ' 	Begin '
Set @sql = @sql + ' If @length<=(@offset+@size) '
Set @sql = @sql + ' Begin '
Set @sql = @sql + ' Set @size = @length - @offset '
Set @sql = @sql + ' READTEXT ' + rtrim(@p_table) + '.' + rtrim(@p_text_column) + ' @ptrval @offset @size '
Set @sql = @sql + ' Break '
Set @sql = @sql + ' End '
Set @sql = @sql + ' Else '
Set @sql = @sql + ' READTEXT ' + rtrim(@p_table) + '.' + rtrim(@p_text_column) + ' @ptrval @offset @size '
Set @sql = @sql + ' Set @index = @index + 1 '
Set @sql = @sql + ' Set @offset = @size*@index '
Set @sql = @sql + ' End; '
Exec (@sql)
GO
/* 
select * from T_DIC_TERM where c_term='administrator'
Exec SP_GetText 'T_DIC_TERM', 'PK_TERM', 'C_MEANING', 113
select 1621*
*/

/**********************************************************************************************************************************/
CREATE PROCEDURE dbo.SP_CopyText
	@p_from_table nvarchar(500),
	@p_from_text_column nvarchar(500),
	@p_from_id_column nvarchar(500),
	@p_from_id int,
	@p_to_table nvarchar(500),
	@p_to_text_column nvarchar(500),
	@p_to_id_column nvarchar(500),
	@p_to_id int
AS
SET NOCOUNT ON
Declare @sql nvarchar(4000)
--Set @sql = 'Select ' + @p_from_file_name_column + ',' + @p_from_file_content_column + ' From ' + @p_table + ' Where ' + @p_from_file_id_column + '=' + convert(varchar, @p_from_file_id)
Set @sql = 'DECLARE @from_ptrval varbinary(16),@to_ptrval varbinary(16)'
Set @sql = @sql + ' SELECT @from_ptrval = TEXTPTR(' + rtrim(@p_from_text_column) + ')' 
Set @sql = @sql + '	FROM ' + rtrim(@p_from_table) + ' WHERE ' + rtrim(@p_from_id_column) + '=' + convert(varchar,@p_from_id) + ';'
Set @sql = @sql + ' SELECT @to_ptrval = TEXTPTR(' + rtrim(@p_to_text_column) + ')'
Set @sql = @sql + '	FROM ' + rtrim(@p_to_table) + ' WHERE ' + rtrim(@p_to_id_column) + '=' + convert(varchar,@p_to_id) + ';'
Set @sql = @sql + ' UPDATETEXT ' + rtrim(@p_to_table) + '.' + rtrim(@p_to_text_column) + ' @to_ptrval 0 NULL ' + rtrim(@p_from_table) + '.' + rtrim(@p_from_text_column) + ' @from_ptrval;'
--SELECT @sql
Exec (@sql)
Return
GO
/* 
select * from T_temp_file
Exec SP_CopyText 'T_TEMP_FILE','C_TEXT', 'PK_TEMP_FILE', 18, 'T_DIC_TERM', 'C_MEANING', 'PK_TERM',  77 
select * from T_dic_term where pk_term=77
*/

/**********************************************************************************************************************************
*   Procedure: Sp_IsExistingFulltextCatalog
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Kiem tra xem mot Catalog da ton tai hay chua
**********************************************************************************************************************************/
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Sp_IsExistingFulltextCatalog]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_IsExistingFulltextCatalog]
GO

CREATE PROCEDURE dbo.Sp_IsExistingFulltextCatalog
	@pCatalogName nvarchar(200),
	@pRet tinyint OUT
AS

DECLARE 
	@CatID	smallint,
	@name	sysname,
	@path	nvarchar(260),
	@status	integer,
	@NUMBER_FULLTEXT_TABLES	integer

Set @pRet = 0

DECLARE @fulltext_cursor CURSOR
EXEC sp_help_fulltext_catalogs_cursor @fulltext_cursor OUTPUT
FETCH NEXT FROM @fulltext_cursor INTO @CatID,	@name,@path,@status,@NUMBER_FULLTEXT_TABLES
While @@FETCH_STATUS <> -1
Begin
	If @name=@pCatalogName
		Set @pRet = 1
	FETCH NEXT FROM @fulltext_cursor INTO 	@CatID,	@name,@path,@status,@NUMBER_FULLTEXT_TABLES
End
CLOSE @fulltext_cursor
DEALLOCATE @fulltext_cursor
GO

/**********************************************************************************************************************************
*   Procedure: Sp_IsFulltextTable
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Kiem tra xem mot table da duoc cau hinh cho phep full-text search hay chua
**********************************************************************************************************************************/
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Sp_IsFulltextTable]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_IsFulltextTable]
GO

CREATE PROCEDURE dbo.Sp_IsFulltextTable
	@pCatalogName nvarchar(200),
	@pTableName nvarchar(200),
	@pRet tinyint OUT
AS

DECLARE @fulltext_cursor CURSOR

EXEC sp_help_fulltext_tables_cursor @fulltext_cursor OUTPUT, @pCatalogName, @pTableName

FETCH NEXT FROM @fulltext_cursor
If @@FETCH_STATUS <> -1
	Set @pRet = 1
Else
	Set @pRet = 0

CLOSE @fulltext_cursor
DEALLOCATE @fulltext_cursor
GO

/**********************************************************************************************************************************
*   Procedure: Sp_SetupFulltext
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/10/2002
*	Chuc nang : Kiem tra xem dich vu full-text search co san sang khong. 
*				Neu co thi dat DB @pDbname o che do FTS, sau do tao catalog voi ten @pCatalogName
**********************************************************************************************************************************/
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Sp_SetupFulltext]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_SetupFulltext]
GO

CREATE PROCEDURE dbo.Sp_SetupFulltext
	@pDbname nvarchar(200),
	@pCatalogName nvarchar(200),
	@pRet tinyint OUT
AS
Declare 
	@IsExistingFulltextCatalog tinyint, 
	@IsFulltextTable tinyint, 
	@IsFulltextInstalled tinyint, 
	@IsFulltextEnabled tinyint, 
	@Status int,
	@cat_name nvarchar(200),
	@db_name nvarchar(200)

Set @cat_name = @pCatalogName
Set @db_name = @pDbname
Set @pRet = 1

SELECT @IsFulltextInstalled = fulltextserviceproperty('IsFulltextInstalled')
-- Neu dich vu full-text da san sang
If @IsFulltextInstalled=1
	Begin
		Select @IsFulltextEnabled = DATABASEPROPERTY(@db_name,'IsFulltextEnabled')
	
		If @IsFulltextEnabled = 0
			Exec @Status = sp_fulltext_database 'enable'
	
		

		If @IsExistingFulltextCatalog = 0
			Exec sp_fulltext_catalog @cat_name, 'Create'
		Else
			Exec sp_fulltext_catalog @cat_name, 'Rebuild'
	End
Else
	Set @pRet=-1
Return 0
GO
	
/**********************************************************************************************************************************
*   Procedure: Sp_SetupFullTextSearchOnTable
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 07/12/2003
*	Chuc nang : 
*	- Kiem tra dich vu FTS va dat che do FTS cho DB hien thoi, tao catalog
*	- Dat che do FTS cho table @pTableName
*	- Dat che do FTS cho cac column thuoc danh sach @pColumnNameList
* 	Chu y:
*	- Truoc khi goi thu tuc nay phai thu hien lenh: Exec sp_pkeys @pTableName 
*	de lay ten cua mot UniqueName (gia tri cua column PK_NAME
**********************************************************************************************************************************/
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Sp_SetupFullTextSearchOnTable]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_SetupFullTextSearchOnTable]
GO

CREATE PROCEDURE dbo.Sp_SetupFullTextSearchOnTable
	@pDbname nvarchar(200),
	@pCatalogName nvarchar(200),
	@pTableName nvarchar(200),
	@pUniqueIndexName nvarchar(200),
	@pColumnNameList nvarchar(1000),
	@pTypeColumnName nvarchar(1000)
AS
Declare 
	@IsFulltextTable tinyint, 
	@Status int,
	@column nvarchar(200),
	@cat_name nvarchar(200),
	@db_name nvarchar(200)

	Set @cat_name = @pCatalogName
	Set @db_name = @pDbname

	Exec Sp_SetupFulltext @db_name, @cat_name, @Status OUT
	If @Status<>1
		Begin
			Select 'Khong thiet lap duoc che do Full-text search'
			Return -100
		End

	-- Thiet lap thong so tim kiem toan van cho @pTableName
	Exec dbo.Sp_IsFulltextTable @cat_name, @pTableName, @IsFulltextTable OUT

	If @IsFulltextTable = 0
		EXEC sp_fulltext_table @pTableName, 'Create', @cat_name, @pUniqueIndexName

	-- 	Tao bang temp de chua cac cot can fulltext search
	Create Table #T_COLUMN(C_COLUMN nvarchar(200))
	Exec Sp_ListToTable	@pColumnNameList, 'C_COLUMN','#T_COLUMN',','

	DECLARE column_cursor CURSOR FOR SELECT * From #T_COLUMN
	OPEN column_cursor
	FETCH NEXT FROM column_cursor INTO @column
	While @@FETCH_STATUS <> -1
	Begin
		If @pTypeColumnName<>'' And @pTypeColumnName Is Not Null
			EXEC sp_fulltext_column @pTableName, @column, 'add',0, @pTypeColumnName
		Else
			EXEC sp_fulltext_column @pTableName, @column, 'add',0

		FETCH NEXT FROM column_cursor INTO @column
	End
	CLOSE column_cursor
	DEALLOCATE column_cursor
	-- Bat dau lap chi muc
	EXEC sp_fulltext_table @pTableName, 'start_full'
GO

/*

Exec sp_pkeys t_doclib_document
Exec dbo.Sp_SetupFullTextSearchOnTable
	'doclib',
	'doclib_cat_name',
	't_doclib_document',
	'PK_T_DOCLIB_DOCUMENT11',
	'c_note',null

Exec sp_pkeys t_doclib_document_content
Exec dbo.Sp_SetupFullTextSearchOnTable
	'doclib',
	'doclib_cat_name',
	't_doclib_document_content',
	'PK_T_DOCLIB_DOCUMENT_CONTENT12',
	'c_content'

*/

/**********************************************************************************************************************************
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 27/02/2004
*   Ngay sua : 
*	Chuc nang : 
*		- Thu tuc Sp_GetIdFromCode lam nhiem vu lay gia tri cua mot column bat ky dua vao 
*		- gia tri cua Column dong vai tro laf khoa (Primary key hoac Unique Key)
**********************************************************************************************************************************/
CREATE Procedure dbo.SP_GetOtherValueFromKeyValue(
	@p_table nvarchar(500),
	@p_key_column nvarchar(500),
	@p_key_value nvarchar(500),
	@p_other_column nvarchar(500),
	@p_other_value nvarchar(500) OUTPUT,
	@p_filter nvarchar(500)='')
AS
	Declare @sql nvarchar(4000), @id_value int
	Set @id_value = 0
	Set @sql = 'Declare id_cursor Cursor For '
	Set @sql = @sql + 'Select TOP 1 ' + @p_other_column + ' From ' + @p_table  
	Set @sql = @sql + ' Where (Rtrim(' + Rtrim(@p_key_column) + ')'+ '=' + char(39) + Rtrim(@p_key_value) + char(39) + ')'
	If (@p_filter<>'' and @p_filter is not null)
		Set @sql = @sql + ' And (' + @p_filter + ') '
	--select @sql
	--return
	Exec (@sql)
	Open id_cursor
	Fetch Next From id_cursor Into @id_value
	Close id_cursor
	Deallocate id_cursor
	Set @p_other_value = @id_value
	Return
GO
/*
Select * from T_USER_ENDUSER
Declare @enduser_id int
Exec SP_GetotherValueFromKeyValue 'T_USER_ENDUSER', 'FK_STAFF', 10, 'PK_ENDUSER', @enduser_id OUTPUT
Select @enduser_id
*/

/**********************************************************************************************************************************
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 01/12/2005
*   Ngay sua : 
*	Chuc nang : 
*		- Di chuyen 1 doi tuong toi VI TRI LIEN KE (TREN hoac DUOI)
**********************************************************************************************************************************/
CREATE PROCEDURE dbo.SP_MoveUpDown
	@p_direction varchar(50), 	-- huong di chuyen: UP, DOWN
	@p_table nvarchar(500),		-- Ten table
	@p_order_column nvarchar(500),	-- Ten column phan anh tu tu sap xep
	@p_pk_column nvarchar(500),		-- Ten column la PK cua bang
	@p_object_id nvarchar(500),		-- ID cua doi tuong can di chuyen
	@p_other_clause nvarchar(500)	-- Dieu kien loc
WITH ENCRYPTION
AS

	Declare @sql nvarchar(4000)

	Set @sql = 'Declare @v_current_order int, @v_other_order int, @v_other_id varchar(500);'
	Set @sql = @sql + '	Select @v_current_order = ' + @p_order_column + ' From ' + @p_table + '	Where ' + @p_pk_column + '=' + char(39) + @p_object_id + char(39) 

	If Ltrim(@p_other_clause) Is Not Null And Ltrim(@p_other_clause)<>''
		Set @sql = @sql + ' And ' + '(' + ltrim(@p_other_clause) + ')'

	Set @sql = @sql + ';'

	If @p_direction='UP'
		Begin
			Set @sql = @sql + 'Select TOP 1 @v_other_id= ' + @p_pk_column + ',@v_other_order= ' + @p_order_column + ' From ' + @p_table + ' Where ' + @p_order_column + '<@v_current_order'
			If Ltrim(@p_other_clause) Is Not Null And Ltrim(@p_other_clause)<>''
				Set @sql = @sql + ' And ' + '(' + ltrim(@p_other_clause) + ')'

			Set @sql = @sql + ' Order By ' + @p_order_column + ' DESC ;'
		End
	Else
		Begin
			Set @sql = @sql + 'Select TOP 1 @v_other_id= ' + @p_pk_column + ',@v_other_order= ' + @p_order_column + ' From ' + @p_table + '	Where ' + @p_order_column + '>@v_current_order'
			If Ltrim(@p_other_clause) Is Not Null And Ltrim(@p_other_clause)<>''
				Set @sql = @sql + ' And ' + '(' + ltrim(@p_other_clause) + ')'

			Set @sql = @sql + ' Order By ' + @p_order_column + ';'
		End

	Set @sql = @sql + 'UPDATE ' + @p_table + ' Set ' + @p_order_column + '=@v_other_order' + '	Where ' + @p_pk_column + '=' + char(39) + @p_object_id + char(39) 

	If Ltrim(@p_other_clause) Is Not Null And Ltrim(@p_other_clause)<>''
		Set @sql = @sql + ' And ' + '(' + ltrim(@p_other_clause) + ')'

	Set @sql = @sql + ';'

	Set @sql = @sql + 'UPDATE ' + @p_table + ' Set ' + @p_order_column + '=@v_current_order' + '	Where ' + @p_pk_column + '=@v_other_id'

	If Ltrim(@p_other_clause) Is Not Null And Ltrim(@p_other_clause)<>''
		Set @sql = @sql + ' And ' + '(' + ltrim(@p_other_clause) + ')'

	Set @sql = @sql + ';'

	--print @sql 
	Exec (@sql)

	
RETURN 0
Go

/*
select * from t_onegate_worktype order by c_order

Exec dbo.SP_MoveUpDown
	@p_direction='UP',
	@p_table='t_onegate_worktype',
	@p_order_column='c_order',
	@p_pk_column='pk_worktype',
	@p_object_id='XAC_MINH',
	@p_other_clause=''

select * from t_onegate_worktype order by c_order

Exec dbo.SP_MoveUpDown
	@p_direction='DOWN',
	@p_table='t_onegate_worktype',
	@p_order_column='c_order',
	@p_pk_column='pk_worktype',
	@p_object_id='PHAN_CONG',
	@p_other_clause=''

SP_MoveUpDown 'UP', 'T_ISALIB_LISTTYPE', 'C_ORDER', 'PK_LISTTYPE', '4', ''
select * from T_ISALIB_LISTTYPE order by c_order


SP_MoveUpDown 'DOWN', 'T_ISALIB_LIST', 'C_ORDER', 'PK_LIST', '15', 'FK_LISTTYPE = 2'
select * from T_ISALIB_LIST  order by c_order


*/

