if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_PositionGroupGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_PositionGroupGetAll]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_PositionGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_PositionGetAll]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_PositionGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_PositionGetSingle]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_PositionUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_PositionUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_PositionDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_PositionDelete]
GO

---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_PositionGroupGetAll
	@p_filter nvarchar(100),
	@p_status smallint
As
	SET NOCOUNT ON
	If @p_status<0
		Select PK_POSITION_GROUP,C_NAME,C_CODE,C_ORDER,C_STATUS 
		From T_USER_POSITION_GROUP 
		Where C_NAME like @p_filter 
		Order by C_ORDER,C_NAME
	Else
		Select PK_POSITION_GROUP,C_NAME,C_CODE,C_ORDER,C_STATUS 
		From T_USER_POSITION_GROUP 
		Where C_NAME like @p_filter And C_STATUS = @p_status
		Order by C_ORDER,C_NAME
	SET NOCOUNT OFF
Go
--USER_PositionGroupGetAll '%%',-1

---------------------------------------------------------------------------------------------------------------------------------------
--Chu y: vi ket qua cua store nay co lien quan den phan staff nen C_CODE phai de vi tri thu 2(tinh tu 0)
Create Procedure USER_PositionGetAll
	@p_filter nvarchar(100),
	@p_status smallint
As
	SET NOCOUNT ON
	If @p_status<0
		Select PK_POSITION,C_NAME,C_CODE,C_ORDER,C_STATUS 
		From T_USER_POSITION 
		Where C_NAME like @p_filter 
		Order by C_ORDER,C_NAME
	Else
		Select PK_POSITION,C_NAME,C_CODE,C_ORDER,C_STATUS 
		From T_USER_POSITION 
		Where C_NAME like @p_filter And C_STATUS = @p_status
		Order by C_ORDER,C_NAME
Go
--USER_PositionGetAll '%%',-1
---------------------------------------------------------------------------------------------------------------------------------------
Create Procedure USER_PositionGetSingle
	@p_item_id int
As
	SET NOCOUNT ON	
	Select PK_POSITION,C_CODE,C_NAME,FK_POSITION_GROUP,C_ORDER,C_STATUS 
	From T_USER_POSITION 
	Where PK_POSITION = @p_item_id
Go
----------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.USER_PositionUpdate
	@p_item_id int,
	@p_code nvarchar(15),
	@p_name nvarchar(100),
	@p_position_group int,
	@p_order smallint,
	@p_status tinyint
AS
	Declare	@count int, @position_id int,@status int
	SET NOCOUNT ON

	If @p_item_id=0
		SELECT @count = COUNT(*) from T_USER_POSITION where C_CODE = @p_code
	Else
		SELECT @count = COUNT(*) from T_USER_POSITION where PK_POSITION <> @p_item_id And C_CODE = @p_code
	If @Count>0
		Begin
			Select 'Ma chuc danh can bo da ton tai' RET_ERROR
			return -100
		End

	-- Dat che do tu dong rollback toan bo transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION

	If @p_item_id >0
		Begin
			Update T_USER_POSITION Set
				FK_POSITION_GROUP = @p_position_group,
				C_CODE = @p_code,
				C_NAME = @p_name, 		
				C_ORDER = @p_order,
				C_STATUS = @p_status 
			Where PK_POSITION = @p_item_id
			Set @position_id = @p_item_id
		End
	Else 
		Begin
			Insert into T_USER_POSITION(FK_POSITION_GROUP,C_CODE,C_NAME,C_ORDER,C_STATUS) Values(@p_position_group,@p_code,@p_name,@p_order,@p_status)
			Set @position_id = @@IDENTITY
		End

	If @p_order Is Not Null And @p_order>0
		Exec SP_ReOrder 'T_USER_POSITION', 'C_ORDER', 'PK_POSITION', @position_id, @p_order,''
	Select @position_id NEW_ID
	COMMIT TRANSACTION
	RETURN 0
GO

----------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE dbo.USER_PositionDelete
	@p_id_list nvarchar(4000)
AS
	Declare @id int
	SET NOCOUNT ON
	-- Tao bang trung gian
	Create Table #T_USER_POSITION(PK_POSITION int)
	Exec Sp_ListToTable @p_id_list, 'PK_POSITION', '#T_USER_POSITION', ','	
	-- Kiem tra rang buoc kieu khoa ngoai
	Select TOP 1 @id = FK_POSITION From T_USER_STAFF Where FK_POSITION In (Select PK_POSITION From #T_USER_POSITION)
	If (@id Is Not Null And @id>0)
		Begin
			Select 'Khong the xoa duoc chuc danh da chon' RET_ERROR
			Return -100
		End 
	-- Dat che do tu dong Rollback neu co loi xay ra
	SET XACT_ABORT ON
	BEGIN TRANSACTION	
	Delete T_USER_POSITION Where PK_POSITION in (Select PK_POSITION From #T_USER_POSITION) 
	COMMIT TRANSACTION
	Return 0
GO