-----------------------------------------------------List procedures----------------------------------------------

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Sp_SetupFullTextSearchOnTable]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_SetupFullTextSearchOnTable]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Sp_SetupFulltext]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_SetupFulltext]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Sp_IsExistingFulltextCatalog]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_IsExistingFulltextCatalog]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Sp_IsFulltextTable]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Sp_IsFulltextTable]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[SP_ReplaceSpecialChar]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[SP_ReplaceSpecialChar]
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

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Efylib_ListUpdateXMLValue]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Efylib_ListUpdateXMLValue]
GO

if exists (select * from sysobjects where id = object_id(N'[dbo].[SP_GetOtherValueFromKeyValue]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[SP_GetOtherValueFromKeyValue]
GO

if exists (select * from sysobjects where id = object_id(N'[dbo].[SP_MoveUpDown]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[SP_MoveUpDown]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Efylib_RecordDocumentGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Efylib_RecordDocumentGetAll]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Efylib_AttachFileUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Efylib_AttachFileUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Doc_AttachFileUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Doc_AttachFileUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Doc_GetAllDocumentFileAttach]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[Doc_GetAllDocumentFileAttach]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[ECS_GetFileNameInDoc]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[ECS_GetFileNameInDoc]
GO

-----------------------------------------------------List funstions----------------------------------------------

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_ReplaceValueOfXMLtag]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_ReplaceValueOfXMLtag]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_AddXMLtag]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_AddXMLtag]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_ListHaveElement]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_ListHaveElement]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_InList]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_InList]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_ListLen]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_ListLen]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[Lower2Upper]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[Lower2Upper]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetValueOfXMLtag]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetValueOfXMLtag]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_ListGetAt]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_ListGetAt]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetAttachFileName]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetAttachFileName]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_getAllAttachFile]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_getAllAttachFile]
GO

/*Your test code GOES HERE
Exec Doc_GetAllDocumentFileAttach '70688062-09d3-4b47-85cc-118a025edda0','DMGD_BCKTKT','T_Doc_RECORD',1,'!#~$|*'
Exec Doc_RecordDelete '{745FF83A-CFCD-454E-BD8D-A98370474B3A}','DMGD_BCKTKT','T_Doc_RECORD'
*/
/**********************************************************************************************************************************/
--	Function	:	f_ReplaceValueOfXMLtag
--	Nguoi tao	:	Phuongtt
--	Ngay tao	:	25/10/2010
--	Chuc nang	:	Thay the gia tri trong mot xau XML boi 1 gia tri khac
/**********************************************************************************************************************************/
CREATE Function f_ReplaceValueOfXMLtag(@xmldoc Xml, @tag varchar(150),@value nvarchar(500))
Returns xml
WITH ENCRYPTION
As
Begin
	Set @xmldoc.modify('replace value of (/root/data_list/*[local-name()=sql:variable("@tag")]/text())[1] with sql:variable("@value")')
	Return @xmldoc
End
GO
/*
declare	@books	xml
set	@books = '<root>
	<data_list TitleId="BU1032" PubDate="1991-06-12T00:00:00Z">
		<Title>The Busy Executive''s Database Guide</Title>
		<Type>business</Type>
		<PubId>1389</PubId>
		<Price>19.9900</Price>
		<Royalty>10</Royalty>
		<YtdSales>4095</YtdSales>
		<Notes>An overview of available database systems with emphasis on common business applications. Illustrated.</Notes>
	</data_list>
	</root>'
select dbo.f_ReplaceValueOfXMLtag(@books,'Price','aa')
*/
/**********************************************************************************************************************************
	Function	:	f_AddXMLtag
	Nguoi tao	:	Phuongtt
	Ngay tao	:	26/10/2010
	Chuc nang	:	Them the vao xau xml
	@xmldoc		:	Chuoi xml can them gia tri
	@tag		:	The can them
	@value		:	Gia tri cua the can them
*********************************************************************************************************************************/
CREATE Function f_AddXMLtag(@xmldoc xml, @tag nvarchar(500), @value nvarchar(500))
Returns xml
WITH ENCRYPTION
As
Begin
	Set @xmldoc = convert(xml, convert(nvarchar(max),@xmldoc) + '<' + @tag + '>' + @value + '</' + @tag + '>')
	Set @xmldoc.modify('insert /*[2] as last into (/root/data_list)[1]')
	Set @xmldoc.modify('delete /*[2]')
	Return @xmldoc
End
GO
/*
declare	@books	xml
set	@books = '<root>
	<data_list>
	</data_list>
	</root>'
select dbo.f_AddXMLtag(@books,'Price1','aa')
*/

/**********************************************************************************************************************************
*   Function	: f_GetValueOfXMLtag
*	Creator		: Phuongtt 
*	Date		: 26/10/2010
*	Description	: Lay gia tri cua mot tag trong 1 xau XML
**********************************************************************************************************************************/
CREATE Function f_GetValueOfXMLtag(@xmldoc xml, @tag nvarchar(500))
Returns nvarchar(2000)
WITH ENCRYPTION
As
Begin
	Declare @return_str Nvarchar(2000)
	Set @return_str = @xmldoc.value('(/root/data_list/*[local-name()=sql:variable("@tag")])[1]', 'nvarchar(2000)')
	Return @return_str
End
GO
/*
declare	@books	xml
set	@books = '<root>
	<data_list TitleId="BU1032" PubDate="1991-06-12T00:00:00Z">
		<Title>The Busy Executive''s Database Guide</Title>
		<Type>business</Type>
		<PubId>1389</PubId>
		<Price>19.9900</Price>
		<Royalty>10</Royalty>
		<YtdSales>4095</YtdSales>
		<Notes>An overview of available database systems with emphasis on common business applications. Illustrated.</Notes>
	</data_list>
	</root>'
select dbo.f_GetValueOfXMLtag(@books,'Royalty')
*/


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
WITH ENCRYPTION
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
WITH ENCRYPTION
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
WITH ENCRYPTION
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
WITH ENCRYPTION
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
WITH ENCRYPTION
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
WITH ENCRYPTION
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
WITH ENCRYPTION
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
WITH ENCRYPTION
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
WITH ENCRYPTION
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

CREATE PROCEDURE dbo.Sp_IsExistingFulltextCatalog
	@pCatalogName nvarchar(200),
	@pRet tinyint OUT
WITH ENCRYPTION
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

CREATE PROCEDURE dbo.Sp_IsFulltextTable
	@pCatalogName nvarchar(200),
	@pTableName nvarchar(200),
	@pRet tinyint OUT
WITH ENCRYPTION
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

CREATE PROCEDURE dbo.Sp_SetupFulltext
	@pDbname nvarchar(200),
	@pCatalogName nvarchar(200),
	@pRet tinyint OUT
WITH ENCRYPTION
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

CREATE PROCEDURE dbo.Sp_SetupFullTextSearchOnTable
	@pDbname nvarchar(200),
	@pCatalogName nvarchar(200),
	@pTableName nvarchar(200),
	@pUniqueIndexName nvarchar(200),
	@pColumnNameList nvarchar(1000),
	@pTypeColumnName nvarchar(1000)
WITH ENCRYPTION
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
WITH ENCRYPTION
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

SP_MoveUpDown 'UP', 'T_EFYLIB_LISTTYPE', 'C_ORDER', 'PK_LISTTYPE', '4', ''
select * from T_EFYLIB_LISTTYPE order by c_order


SP_MoveUpDown 'DOWN', 'T_EFYLIB_LIST', 'C_ORDER', 'PK_LIST', '15', 'FK_LISTTYPE = 2'
select * from T_EFYLIB_LIST  order by c_order


*/

CREATE function dbo.f_ListHaveElement(@pList varchar(4000), @pElement varchar(1000), @pDelimiterIN varchar(10)=',')
Returns int
WITH ENCRYPTION
As
Begin
	Declare @index numeric, @element varchar(1000), @mListLen	numeric
	Select @mListLen=0
	Select @mListLen = dbo.f_ListLen(@pList,@pDelimiterIN)
	if @mListLen = 0
		Return -100
	Select @index = 0
	While @index <= @mListLen
	Begin
		Select @element = dbo.f_ListGetAt(@pList,@index,@pDelimiterIN)
		If @element = @pElement
			Return 1
		Select @index = @index + 1
	End
Return 0
End
GO
/**********************************************************************************************************************************
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 24/3/2006
*   Hieu dinh: Vu Manh Hung
*   Ngay sua : 17/10/2007
*	Chuc nang : Chuyen doi UTF8 chu thuong thanh chu hoa
**********************************************************************************************************************************/

-- Drop Function Lower2Upper

CREATE Function Lower2Upper(@pText nvarchar(4000))
Returns nvarchar(4000)
WITH ENCRYPTION
As
Begin

Declare @lc nvarchar(1000),  @uc nvarchar(1000), @ret nvarchar(4000), @i int, @mListLen int, @mFind nvarchar(10), @mReplace nvarchar(10), @position int
-- Ky tu chu thuong áº¡, áº 
Set @lc = 'áº¡/Ã¡/Ã /Ã£/áº£/Ä‘/Ã¢/áº§/áº¥/áº©/áº«/áº­/áº¹/Ã©/Ã¨/áº½/áº»/á»‡/Ãª/á»/áº¿/á»ƒ/á»…/á»‹/Ã¬/Ã­/á»‰/Ä©/á»™/á»“/á»‘/á»—/á»•/á»µ/á»³/Ã½/á»¹/á»·/Æ¡/á»/á»›/á»Ÿ/á»¡/á»£/Ã²/Ã³/Ãµ/á»/á»/Æ°/á»«/á»©/á»¯/á»­/á»±/Äƒ/áº±/áº¯/áºµ/áº³/áº·/Ã¹/Ãº/á»§/Å©/á»¥/áº¡/Ã´'
-- Ky tu chu hoa tuong ung
Set @uc = 'áº /Ã/Ã€/Ãƒ/áº¢/Ä/Ã‚/áº¦/áº¤/áº¨/áºª/áº¬/áº¸/Ã‰/Ãˆ/áº¼/áºº/á»†/ÃŠ/á»€/áº¾/á»‚/á»„/á»Š/ÃŒ/Ã/á»ˆ/Ä¨/á»˜/á»’/á»/á»–/á»”/á»´/á»²/Ã/á»¸/á»¶/Æ /á»œ/á»š/á»ž/á» /á»¢/Ã’/Ã“/Ã•/á»Ž/á»Œ/Æ¯/á»ª/á»¨/á»®/á»¬/á»°/Ä‚/áº°/áº®/áº´/áº²/Ã¡ÂºÂ¶/Ã™/Ãš/á»¦/Å¨/á»¤/áº/Ã”'

Set @ret = @pText

Select @i=1
Select @mListLen=0
Set @mListLen = dbo.f_ListLen(@lc,'/')

While @i<=@mListLen
Begin
	Set @mFind = dbo.f_ListGetAt(@lc, @i, '/')
	Set @mReplace = dbo.f_ListGetAt(@uc, @i, '/')
	Set @Ret = Replace(@Ret,@mFind,@mReplace)
	Set @i=@i+1
End
Return @ret
End
GO
/*
select dbo.Lower2Upper('hÃ¹ng')
select dbo.Lower2Upper('hÃ¹ng')
select dbo.Lower2Upper('nguyen van a')
*/
/*
Creater : HUNGVM
Date : 05/06/2010
Idea : Tao SP xu ly viec update cac file dinh kem cua mot doi tuong @psFkFileObject
*/
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE [dbo].[Efylib_AttachFileUpdate]
	@sFkFileObject				Nvarchar(50)			-- FK doi tuong chua file dinh kem
	,@sTableObject				Varchar(100)			-- Bang chua FK @psFkFileObject
	,@sDocType					Varchar(30)				-- File dinh kem thuoc modul (VB_DEN/VB_DI/...)
	,@sNewFileNameList			Nvarchar(1000)			-- Danh sach TEN VB them moi
	,@sDelimitor				Nvarchar(20) = ','		-- Ky ty phan tach giua cac ky tu
WITH ENCRYPTION
AS
	Declare @p_new_id varchar(50),@count int, @Index int
	SET NOCOUNT ON
	BEGIN TRANSACTION
	-- Update file dinh kem moi vao DB
	If @sNewFileNameList <> '' And @sNewFileNameList Is Not Null
		Begin
				Set @count = dbo.f_ListLen(@sNewFileNameList,@sDelimitor)
				Set @Index = 1
				While @Index <= @count
					Begin
						Set @p_new_id = NewID()
						Insert Into T_Doc_FILE (PK_DOC_FILE, FK_DOC, C_TABLE_OBJECT, C_FILE_NAME, C_DOC_TYPE)
						Select @p_new_id, @sFkFileObject, @sTableObject, dbo.f_ListGetAt(@sNewFileNameList,@Index,@sDelimitor), @sDocType
						Set @Index = @Index + 1
					End
		End			
	COMMIT TRANSACTION 
	SET NOCOUNT OFF
Return 0
Go

/*
	Exec [dbo].[Doc_AttachFileUpdate]
	@sFkFileObject	= 'E306CCE2-6C53-4D8A-A831-E5F3B20E8B90'
	,@sTableObject	= 'T_DOC_RECEIVED_DOCUMENT'
	,@sDocType	= 'VB_DEN'
	,@sNewFileNameList = '21052009100537!~!To-trinh-tham-dinh-du-an-Ke-hoach-Dau-thau.doc!#~$|*21052009100537!~!Mh danh sach ho so cho phan cong thu ly.doc'
	,@sDelimitor = '!#~$|*'
*/
--	Function	:	Doc_GetAllDocumentFileAttach
--	Nguoi tao	:	HUNGVM
--	Ngay tao	:	21/05/2009
--	Chuc nang	:	Lay thong tin cua cac File dinh kem ho so
/**********************************************************************************************************************************/
CREATE FUNCTION dbo.f_GetAttachFileName(
	@p_file_id					Varchar(50)				-- Id
	,@p_doc_type				Varchar(50) = ''		-- Bien xac dinh VB thuoc modul nao (VB_DEN/VB_DI/...)
	,@p_table_object			Nvarchar(100) = ''		-- Ben xac dinh can file trong bang nao? 
	,@psDelimitor				Nvarchar(20) = ','		-- Ky ty phan tach giua cac phan tu
)
Returns nvarchar(4000)
WITH ENCRYPTION
As
Begin
	Declare @p_sql Nvarchar(4000)	
	Set @p_sql = ''
	Select @p_sql = @p_sql + C_FILE_NAME + @psDelimitor From T_EFYLIB_FILE Where FK_DOC = @p_file_id And C_DOC_TYPE = @p_doc_type And C_TABLE_OBJECT = @p_table_object
	If @p_sql <> ''
		Set @p_sql = substring(@p_sql,1,len(@p_sql)-6)				
	Return @p_sql
End	
Go
/*
Select dbo.f_GetAttachFileName ('E306CCE2-6C53-4D8A-A831-E5F3B20E8B90','VB_DEN','T_DOC_RECEIVED_DOCUMENT','!#~$|*')
*/
------------------------------------------------------------------------------------------------------------
/*
Nguoi tao: TOANHV
Ngay tao: 15/07/2009
Y nghia: lay danh sach cac file dinh kem
*/
CREATE FUNCTION dbo.f_getAllAttachFile(
@pDocId varchar(50) -- Id van ban
--,@table varchar(100) = ''
)
Returns nvarchar(4000)
WITH ENCRYPTION
As
BEGIN
Declare @sql nvarchar(4000)
Set @sql = ''
Select @sql = @sql + convert(nvarchar(200), C_FILE_NAME) + '!#~$|*'
From T_DOC_FILE
Where FK_DOC = @pDocId
Return @sql
END
Go
/*
select dbo.f_getAllAttachFile('E2DC4175-329E-4FB9-ACDA-000C17B73193')
*//*
Creater : HUNGVM
Date : 05/06/2010
Idea : Tao SP xu ly viec update cac file dinh kem cua mot doi tuong @psFkFileObject
*/
-------------------------------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE [dbo].[Doc_AttachFileUpdate]
	@sFkFileObject				Nvarchar(50)			-- FK doi tuong chua file dinh kem
	,@sTableObject				Varchar(100)			-- Bang chua FK @psFkFileObject
	,@sDocType					Varchar(30)				-- File dinh kem thuoc modul (VB_DEN/VB_DI/...)
	,@sNewFileNameList			Nvarchar(1000)			-- Danh sach TEN VB them moi
	,@sDelimitor				Nvarchar(20) = ','		-- Ky ty phan tach giua cac ky tu
WITH ENCRYPTION
AS
	Declare @p_new_id varchar(50),@count int, @Index int
	SET NOCOUNT ON
	BEGIN TRANSACTION
	-- Update file dinh kem moi vao DB
	If @sNewFileNameList <> '' And @sNewFileNameList Is Not Null
		Begin
				Set @count = dbo.f_ListLen(@sNewFileNameList,@sDelimitor)
				Set @Index = 1
				While @Index <= @count
					Begin
						Set @p_new_id = NewID()
						Insert Into T_EFYLIB_FILE (PK_DOC_FILE, FK_DOC, C_TABLE_OBJECT, C_FILE_NAME, C_DOC_TYPE)
						Select @p_new_id, @sFkFileObject, @sTableObject, dbo.f_ListGetAt(@sNewFileNameList,@Index,@sDelimitor), @sDocType
						Set @Index = @Index + 1
					End
		End			
	COMMIT TRANSACTION 
	SET NOCOUNT OFF
Return 0
Go
/********************************************************************************************************************/
--	Function	:	Doc_GetAllDocumentFileAttach
--	Nguoi tao	:	HUNGVM
--	Ngay tao	:	21/05/2009
--	Chuc nang	:	Lay thong tin cua cac File dinh kem
/**********************************************************************************************************************************/
CREATE PROCEDURE dbo.Doc_GetAllDocumentFileAttach
	@p_file_id					varchar(50)				-- Id
	,@p_doc_type				Nvarchar(50) = ''		-- Loai VB thuoc modul (VB_DEN/...)
	,@p_table_object			Nvarchar(100) = ''		-- Ben xac dinh can file trong bang nao? 
	,@p_option					Int		     = 0		--
	,@psDelimitor				Nvarchar(20) = ','		-- Ky ty phan tach giua cac phan tu
WITH ENCRYPTION
AS
	Declare @p_sql Nvarchar(4000)
	SET NOCOUNT ON
	If @p_option = 0 
		Begin
			If @p_doc_type <> ''
				Select 	PK_DOC_FILE, C_FILE_NAME	From T_EFYLIB_FILE Where FK_DOC = @p_file_id And C_DOC_TYPE = @p_doc_type And C_TABLE_OBJECT = @p_table_object
			Else
				Select 	PK_DOC_FILE, C_FILE_NAME	From T_EFYLIB_FILE Where FK_DOC = @p_file_id And C_TABLE_OBJECT = @p_table_object
		End

	--
	If @p_option = 1
		Begin
			Set @p_sql = ''
			Select @p_sql = @p_sql + C_FILE_NAME + @psDelimitor From T_EFYLIB_FILE Where FK_DOC = @p_file_id And C_DOC_TYPE = @p_doc_type And C_TABLE_OBJECT = @p_table_object
			If @p_sql <> ''
				Set @p_sql = substring(@p_sql,1,len(@p_sql)-6)
			select @p_sql As C_FILE_LIST
		End 
	SET NOCOUNT OFF
Return 0
Go
/*Your test code GOES HERE
Exec Doc_GetAllDocumentFileAttach '70688062-09d3-4b47-85cc-118a025edda0','DMGD_BCKTKT','T_Doc_RECORD',1,'!#~$|*'
Exec Doc_RecordDelete '{745FF83A-CFCD-454E-BD8D-A98370474B3A}','DMGD_BCKTKT','T_Doc_RECORD'
*/
/*
	Nguoi tao: Phuongtt
	Ngay tao: 13/07/2010
	Y nghia: Lay danh sach ten file dinh kem trong danh sach ho so
*/
CREATE PROCEDURE [dbo].[ECS_GetFileNameInDoc]
	@sListRecordId 				Ntext						-- Id cua ho so luu tru
	,@sDocType					Varchar(30)					-- Loai van ban Vd:VB_DEN, VB_DI, VB_HSLT
	,@sTableName				Varchar(100)				-- Ten bang 
	,@sdelimitor				Varchar(10)					-- Ky tu phan tach
WITH ENCRYPTION
AS
	SET NOCOUNT ON
		Declare  @icount					Int				-- Bien luu so phan tu cua list	
				,@iIndex					Int				-- Bien duyet phan tu tren list
				,@sIndexId					Varchar(50)		-- Luu Id cua chi so 
		Create Table #T_ALL_FILE(C_FILE_NAME Varchar(4000))
		Set @icount = 0
		Set @iIndex = 0
		Set @sIndexId	= ''
		Set @icount = dbo.f_ListLen(@sListRecordId,',')
		Set @iIndex = 1
			While @iIndex <= @icount
				Begin
					Set @sIndexId = dbo.f_ListGetAt(@sListRecordId,@iIndex,',')
					Insert into #T_ALL_FILE
					Select dbo.f_GetAttachFileName(@sIndexId,@sDocType,@sTableName,@sdelimitor)
					Set @iIndex = @iIndex + 1						
				End
		Select *
		From #T_ALL_FILE
	SET NOCOUNT OFF
Return 0
GO
/*

Exec ECS_GetFileNameInDoc 'C67E8088-EA62-42E2-BD2A-63199EC11CA2','HO_SO','T_eCS_RECORD','!#~$|*'
*/