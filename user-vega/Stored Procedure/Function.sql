if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_FunctionGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_FunctionGetAll]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_FunctionGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_FunctionGetSingle]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_FunctionUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_FunctionUpdate]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_FunctionDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_FunctionDelete]
GO
---------------------------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_FunctionGetAllForUser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_FunctionGetAllForUser]
GO
---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_FunctionGetAll	
	@p_status int
As
	SET NOCOUNT ON
	If @p_status < 0
		Select PK_FUNCTION,FK_MODUL,C_CODE,C_NAME,'1' AS C_TYPE,2 As C_LEVEL, C_ORDER,C_STATUS From T_USER_FUNCTION
			Order by C_ORDER,C_NAME
	Else
		Select PK_FUNCTION,FK_MODUL,C_CODE,C_NAME,'1' AS C_TYPE,2 As C_LEVEL, C_ORDER,C_STATUS From T_USER_FUNCTION 
			Where C_STATUS = @p_status
			Order by C_ORDER,C_NAME
	Return 0
GO
--USER_FunctionGetAll 1
--Select * from t_user_application
--Select * from t_user_modul
--Select * from t_user_function
---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_FunctionGetSingle
	@p_item_id int	
As
	SET NOCOUNT ON
	if @p_item_id>0
		Begin
			Select A.PK_FUNCTION,
					A.C_CODE,
					A.C_NAME,
					A.C_ORDER,
					A.C_STATUS,
					A.C_PUBLIC,
					A.FK_APPLICATION,
					A.FK_MODUL,
					B.C_NAME As C_APPLICATION_NAME,
					C.C_NAME As C_MODUL_NAME	
			From T_USER_FUNCTION A
			Left Join T_USER_APPLICATION B On B.PK_APPLICATION = A.FK_APPLICATION
			Left Join T_USER_MODUL C On C.PK_MODUL = A.FK_MODUL
			Where A.PK_FUNCTION = @p_item_id 
		End
	Return 0
Go
--USER_FunctionGetSingle 246
----------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.USER_FunctionUpdate
	@p_item_id int,
	@p_application_id int,
	@p_modul_id int,
	@p_code nvarchar(100),
	@p_name nvarchar(400),
	@p_order smallint,
	@p_status tinyint,
	@p_public tinyint
AS
	Declare
		@count_code int,
		@count_name int,
		@function_id int,
		@status int,
		@filter_clause nvarchar(1000)

	SET NOCOUNT ON
	SELECT @count_code = COUNT(*) from T_USER_FUNCTION where PK_FUNCTION <> @p_item_id And C_CODE=@p_code And FK_APPLICATION=@p_application_id
	SELECT @count_name = COUNT(*) from T_USER_FUNCTION where PK_FUNCTION <> @p_item_id And C_NAME=@p_name And FK_APPLICATION=@p_application_id

	If @Count_code>0
		Begin
			Select 'Ma chuc nang da co trong danh muc' RET_ERROR
			return -100
		End
	If @Count_name>0
		Begin
			Select 'Ten chuc nang da co trong danh muc' RET_ERROR
			return -100
		End
	-- Dat che do tu dong rollback toan bo transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	If @p_item_id >0
		Begin
			Update T_USER_FUNCTION SET 
				FK_APPLICATION = @p_application_id,
				FK_MODUL = @p_modul_id,
				C_CODE = @p_code,
				C_NAME = @p_name, 		
				C_ORDER = @p_order,
				C_STATUS = @p_status,
				C_PUBLIC = @p_public
				WHERE PK_FUNCTION = @p_item_id
			Set @function_id = @p_item_id
		End
	Else 
		Begin
			Insert into T_USER_FUNCTION(FK_APPLICATION,FK_MODUL,C_CODE,C_NAME,C_ORDER,C_STATUS, C_PUBLIC) 
			Values(@p_application_id,@p_modul_id,@p_code,@p_name,@p_order,@p_status, @p_public)
			Set @function_id = @@IDENTITY
		End

	If @p_order Is Not Null And @p_order>0
		Begin
			Set @filter_clause = 'FK_MODUL=' + convert(nvarchar,@p_modul_id)
			--Exec SP_ReOrder 'T_USER_FUNCTION', 'C_ORDER', 'PK_FUNCTION', @function_id, @p_order,@filter_clause
		End

	Select @function_id NEW_ID

	COMMIT TRANSACTION
RETURN 0
GO


/*CREATE PROCEDURE dbo.USER_FunctionDelete
	@p_id_list nvarchar(1000)
AS
	Declare @id1 int,@id2 int
	SET NOCOUNT ON
	-- Tao bang trung gian
	Create Table #T_USER_FUNCTION(PK_FUNCTION int)
	Exec Sp_ListToTable @p_id_list, 'PK_FUNCTION', '#T_USER_FUNCTION', ','	
	-- Kiem tra rang buoc kieu khoa ngoai
	Select TOP 1 @id1 = FK_FUNCTION From T_USER_FUNCTION_BY_ENDUSER Where FK_FUNCTION In (Select PK_FUNCTION From #T_USER_FUNCTION)
	Select TOP 1 @id2 = FK_FUNCTION From T_USER_FUNCTION_BY_GROUP Where FK_FUNCTION In (Select PK_FUNCTION From #T_USER_FUNCTION)	
	If (@id1>0 or @id2>0)
		Begin
			Select 'Khong the xoa duoc chuc nang nay vi da co it nhat mot nguoi su dung duoc ban quen thuc hien chuc nang nay ' RET_ERROR
			Return -100
		End 
	-- Dat che do tu dong Rollback neu co loi xay ra
	SET XACT_ABORT ON
	BEGIN TRANSACTION	
		Delete T_USER_FUNCTION Where PK_FUNCTION in (Select PK_FUNCTION From #T_USER_FUNCTION) 
	COMMIT TRANSACTION
	Return 0
GO*/
CREATE PROCEDURE dbo.USER_FunctionDelete
	@p_list_item_id nvarchar(4000)
AS
	Declare 
			@p_item_id int,
			@mPosition int,
			@start int,
			@id1 int,
			@id2 int,
			@id int,
			@p_function_name nvarchar(30),
			@p_return_name nvarchar(4000),
			@p_return_id nvarchar(100)
	SET NOCOUNT ON
	--+Vi tri bat dau lay
	select @p_return_name=''
	select @p_return_id=''
	select @start = 1 
		While (@p_list_item_id <>'')
			Begin
				select @id1=0		
				select @id2=0		
				select @id=0			
				Select @mPosition = charindex(',',@p_list_item_id)
				if @mPosition >0
					Begin
						Select @p_item_id = substring(@p_list_item_id,@start,@mPosition-@start)
						Select @p_list_item_id = substring(@p_list_item_id,@mPosition+1,Len(@p_list_item_id)-@mPosition)
					End
				Else --Trong truong hop chi con mot nguoi
					Begin
						select @p_item_id =@p_list_item_id
						select @p_list_item_id =''
					End
				-- Kiem tra rang buoc kieu khoa ngoai
				Select TOP 1 @id1 = FK_FUNCTION From T_USER_FUNCTION_BY_ENDUSER Where FK_FUNCTION = @p_item_id
				Select TOP 1 @id2 = FK_FUNCTION From T_USER_FUNCTION_BY_GROUP Where FK_FUNCTION = @p_item_id
				set @id = @id1 + @id2
				If (@id = 0)							
					Begin
						SET XACT_ABORT ON
						BEGIN TRANSACTION		
						Delete T_USER_FUNCTION Where PK_FUNCTION = @p_item_id
						COMMIT TRANSACTION
					End
				else
					Begin
						--Ten Ham khong xoa duoc
						Select @p_function_name = C_NAME From T_USER_FUNCTION Where PK_FUNCTION = @p_item_id
						select @p_return_name = @p_return_name + @p_function_name + ','
						Select @p_return_id = @p_return_id + convert(char(10),@p_item_id) + ','
					End
			End
	select @p_return_name RET_ERROR_NAME,@p_return_id RET_ERROR_ID
	return 0
GO
----------------------------------------------------------------------------------------------------------------------
--Lay cac chuc nang cua Application hien thoi 
--va chuc nang ma nguoi su dung hien thoi duoc quyen thuc hien
---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_FunctionGetAllForUser
	@p_application_id int,	
	@p_enduser_id int
As
	SET NOCOUNT ON
	Select 
		A.PK_FUNCTION As PK_FUNCTION,
		A.C_NAME As C_NAME,
		(Select Count(*) From T_USER_FUNCTION_BY_ENDUSER Where A.PK_FUNCTION = FK_FUNCTION And FK_ENDUSER = @p_enduser_id) As C_CHECK
	From T_USER_FUNCTION A Where A.FK_APPLICATION = @p_application_id
	return 0
GO
