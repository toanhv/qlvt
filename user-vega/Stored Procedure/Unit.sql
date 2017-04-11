if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UnitHaveChildren]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[USER_UnitHaveChildren]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetUnitLevelOneID]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetUnitLevelOneID]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetUnitLevelOneName]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetUnitLevelOneName]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetUnitName]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetUnitName]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UnitGetNextInternalOrder]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[USER_UnitGetNextInternalOrder]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UnitGetAll]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_UnitGetAll]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UnitGetSingle]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_UnitGetSingle]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UnitUpdate]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_UnitUpdate]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UnitDelete]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_UnitDelete]
GO

/*********************************************************************************
* Function USER_UnitGetNextInternalOrder
* Viet boi: Nguyen Tuan Anh
* Ngay viet : 13/10/2004
* Chuc nang : Lay Gia tri tiep theo cua C_INTERNAL_ORDER trong 1 nhanh UNIT
*********************************************************************************/
CREATE FUNCTION dbo.USER_UnitGetNextInternalOrder(
	@p_parent_unit_id int,
	@p_order int)
Returns varchar(500)
AS
Begin
	Declare @v_unit_id int, @v_next_internal_order varchar(500)

	If @p_parent_unit_id Is Not Null
		Begin
			Select @v_next_internal_order = C_INTERNAL_ORDER
				From T_USER_UNIT
				Where PK_UNIT=@p_parent_unit_id
	
			Set @v_next_internal_order = @v_next_internal_order + dbo.f_FormatOrder(@p_order,5)
		End
	Else
		Set @v_next_internal_order = dbo.f_FormatOrder(@p_order,5)

	Return @v_next_internal_order
End
GO
-- Select dbo.USER_UnitGetNextInternalOrder(1,1)
-- Select * from T_user_unit where fk_unit=44 order by c_internal_order 

---------------------------------------------------------------------------------------------------------------------------------------
--+Thu tuc USER_UnitGetAll:
--+Createn by Nguyen Tai Ba
--+Lay danh sach tat ca cac phong ban + loai (0) de ap dung trong ham xay dung cay
--+Chu y C_code phai de vi tri thu 2 tinh tu 0
Create procedure USER_UnitGetAll
	@p_status int
As
	SET NOCOUNT ON

	If @p_status=-1
		Select 	PK_UNIT AS PK_OBJECT,
				FK_UNIT AS FK_OBJECT,
				C_CODE,
				C_NAME, 
				'0' As C_TYPE, 
				'0' As C_LEVEL,
				1 AS C_HAVE_CHILDREN,
				C_INTERNAL_ORDER
			From T_USER_UNIT
			Order By C_INTERNAL_ORDER
	Else
		Select 	PK_UNIT AS PK_OBJECT,
				FK_UNIT AS FK_OBJECT,
				C_CODE,
				C_NAME, 
				'0' As C_TYPE, 
				'0' As C_LEVEL,
				1 AS C_HAVE_CHILDREN,
				C_INTERNAL_ORDER
			From T_USER_UNIT
			Where C_STATUS=@p_status 
			Order By C_INTERNAL_ORDER

	SET NOCOUNT OFF
Go
-- EXEC USER_UnitGetAll -1
-- Select * from t_user_unit
--------------------------------------------------------------------------------------------------------------------------------------
--+Thu tuc USER_UnitGetSingle:
--+Createn by Nguyen Tai Ba
--+Lay thong tin chi tiet mot phong ban
Create procedure USER_UnitGetSingle
	@p_unit_id int
As
	SET NOCOUNT ON
	Select A.PK_UNIT,
			A.FK_UNIT,
			B.C_CODE AS C_PARENT_CODE,
			B.C_NAME AS C_PARENT_NAME,
			A.C_CODE,
			A.C_NAME,
			A.C_ADDRESS,
			A.C_TEL,
			A.C_LOCAL,
			A.C_FAX,
			A.C_EMAIL,
			A.C_ORDER,
			A.C_STATUS
	From T_USER_UNIT A LEFT JOIN T_USER_UNIT B ON A.FK_UNIT=B.PK_UNIT
	Where A.PK_UNIT = @p_unit_id
	SET NOCOUNT OFF
Go
--EXEC USER_UnitGetSingle 26
----------------------------------------------------------------------------------------------------------------------
--+Thu tuc USER_UnitUpdate:
--+Createn by Nguyen Tai Ba
--+Cap nhat thong tin cua mot phong ban
CREATE PROCEDURE dbo.USER_UnitUpdate
	@p_unit_id int,
	@p_parent_id int,
	@p_code nvarchar(100),
	@p_name nvarchar(1000),	
	@p_address nvarchar(2000),
	@p_tel nvarchar(50),
	@p_local nvarchar(50),
	@p_fax nvarchar(50),
	@p_email nvarchar(50),
	@p_order smallint,
	@p_status tinyint
AS
	Declare	@count int, @unit_id int,@status int, @v_filter nvarchar(500), @v_internal_order varchar(500), @v_current_max_order int,@v_mode nvarchar(50)
	SET NOCOUNT ON

	If @p_unit_id=0
		SELECT @count = COUNT(*) from T_USER_UNIT where C_CODE=@p_code
	Else
		SELECT @count = COUNT(*) from T_USER_UNIT where PK_UNIT <> @p_unit_id And C_CODE = @p_code
	If @Count>0
		Begin
			Select 'Ma don vi da ton tai' RET_ERROR
			return -100
		End
	--neu ID cua nut cha la 0 thi chung to nut con la muc ngoai cung nen ID=NULL
	if @p_parent_id <= 0
		Begin
			Set @p_parent_id = NULL
		End

	If @p_unit_id=0
		Set @v_mode = 'ADD'
	Else
		Set @v_mode = 'EDIT'

	-- Dat che do tu dong rollback toan bo transaction khi co loi
	SET XACT_ABORT ON
	BEGIN TRANSACTION
	If @p_unit_id > 0
		Begin
			Update T_USER_UNIT Set 
				FK_UNIT = @p_parent_id,
				C_CODE = @p_code,
				C_NAME = @p_name, 
				C_ADDRESS = @p_address,
				C_TEL = @p_tel,
				C_LOCAL = @p_local,
				C_FAX = @p_fax,
				C_EMAIL = @p_email,	
				C_ORDER = @p_order,
				C_STATUS = @p_status 
			Where PK_UNIT = @p_unit_id
			Set @unit_id = @p_unit_id
		End
	Else 
		Begin
			Insert into T_USER_UNIT(FK_UNIT,C_CODE,C_NAME,C_ADDRESS,C_TEL,C_LOCAL,C_FAX,C_EMAIL,C_ORDER,C_STATUS) 
			Values(@p_parent_id,@p_code,@p_name,@p_address,@p_tel,@p_local,@p_fax,@p_email,@p_order,@p_status)
			Set @unit_id = @@IDENTITY
		End

	-- Dieu kien loc khi sap xep lai thu tu
	If @p_parent_id Is Null
		Set @v_filter = ' FK_UNIT IS NULL '
	Else
		Set @v_filter = ' FK_UNIT=' + Convert(varchar,@p_parent_id)

	If @p_order Is Not Null And @p_order > 0
		Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', @UNIT_id, @p_order,@v_filter

	Select @v_internal_order = isnull(C_INTERNAL_ORDER,'') From T_USER_UNIT Where PK_UNIT=@p_parent_id

	Update T_USER_UNIT Set C_INTERNAL_ORDER = @v_internal_order + dbo.f_FormatOrder(C_ORDER,5) 
		Where FK_UNIT = @p_parent_id

	Select @unit_id NEW_ID

	--

	-- Dieu kien loc khi sap xep lai thu tu
	If @p_parent_id Is Null
		Begin
			Set @v_filter = ' FK_UNIT IS NULL '
			Select @v_current_max_order = Isnull(Max(C_ORDER),0) From T_USER_UNIT Where FK_UNIT IS NULL
		End 
	Else
		Begin
			Set @v_filter = ' FK_UNIT=' + char(39) + Convert(varchar(500),@p_parent_id) + char(39)
			Select @v_current_max_order = Isnull(Max(C_ORDER),0) From T_USER_UNIT Where FK_UNIT=@p_parent_id
		End

	-- Danh so thu tu Neu la HIEU CHINH TK hoac THEM MOI TK voi So thu tu chen ngang
	If (@p_order Is Not Null And @p_order > 0 And @p_order<@v_current_max_order) Or @v_mode = 'EDIT' 
		Begin
			Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', @p_unit_id, @p_order,@v_filter
			Exec USER_UnitSetInternalOrder @p_parent_id
		End
	Else
		Begin
			Update T_USER_UNIT Set
				C_INTERNAL_ORDER = dbo.USER_UnitGetNextInternalOrder(@p_parent_id, @p_order)
				Where PK_UNIT=@p_unit_id
		End


	COMMIT TRANSACTION

RETURN 0
GO

----------------------------------------------------------------------------------------------------------------------
--+Thu tuc USER_UnitDelete:
--+Createn by Nguyen Tai Ba
--+Xoa mot phong ban khoi danh sach
CREATE PROCEDURE dbo.USER_UnitDelete
	@p_item_id int
AS
	Declare @id int
	SET NOCOUNT ON			
	-- Kiem tra rang buoc kieu khoa ngoai
	Select TOP 1 @id = FK_UNIT From T_USER_UNIT Where FK_UNIT = @p_item_id 
	If (@id is not null and @id>0)
			Begin
				Select 'Khong the xoa duoc phong da chon vi co chua phong ban khac' RET_ERROR
				Return -100
			End
	Select TOP 1 @id = FK_UNIT From T_USER_STAFF Where FK_UNIT =@p_item_id
	If (@id is not null and @id>0)
		Begin
			Select 'Khong the xoa duoc phong da chon vi co chua cac can bo' RET_ERROR
			Return -100
		End
	Delete T_USER_UNIT Where PK_UNIT = @p_item_id
	Return 0
GO

/*********************************************************************************
* Function USER_UnitHaveChildren
* Viet boi: Nguyen Tuan Anh
* Ngay viet : 13/10/2004
* Chuc nang : Tra lai 0 neu co don vi con
*********************************************************************************/
CREATE FUNCTION dbo.USER_UnitHaveChildren(
	@p_unit_id int)
Returns int
AS
Begin
	Declare @v_unit_id int, @v_ret_value int

	Select TOP 1 @v_unit_id = PK_UNIT
		From T_USER_UNIT
		Where FK_UNIT Is Not Null And FK_UNIT=@p_unit_id And PK_UNIT<>@p_unit_id

	Set @v_ret_value = @@RowCount

	Return @v_ret_value
End
GO

/*********************************************************************************
* Function f_GetUnitLevelOneID
* Viet boi: NTA
* Ngay viet : 04/7/2007 (sua lai khong dung LOOP)
* Chuc nang : Lay ID don vi cap 1 cua NSD
*********************************************************************************/
CREATE Function f_GetUnitLevelOneID(@pStaff_id int)
Returns int
As
Begin
	Declare 
		@level1_unit_id int,
		@unit_id int,
		@v_internal_order nvarchar(1000)

	--Lay don vi cua NSD hien thoi
	Select TOP 1 @unit_id = FK_UNIT, @v_internal_order = C_INTERNAL_ORDER
		From T_USER_STAFF Where PK_STAFF = @pStaff_id

	-- Dua vao C_INTERNAL_ORDER de tim don vi cap 1
	Select TOP 1 @level1_unit_id = PK_UNIT
		From T_USER_UNIT  
		Where FK_UNIT in (Select PK_UNIT From T_USER_UNIT  Where FK_UNIT Is Null )
			And C_INTERNAL_ORDER = Left(@v_internal_order,Len(C_INTERNAL_ORDER))
		
	Return @level1_unit_id
End
GO
-- Select dbo.[f_GetUnitLevelOneID](212)
/*********************************************************************************
* Function f_GetUnitLevelOneName
* Viet boi: Noname
* Ngay viet : N/A
* Chuc nang : Lay Ten don vi cap 1 cua NSD
*********************************************************************************/
CREATE Function f_GetUnitLevelOneName(@pStaff_id int)
Returns nvarchar(500)
As
Begin

	Declare 
		@level1_unit_id int,
		@level1_unit_name nvarchar(500),
		@unit_id int,
		@v_internal_order nvarchar(1000)

	--Lay don vi cua NSD hien thoi
	Select TOP 1 @unit_id = FK_UNIT, @v_internal_order = C_INTERNAL_ORDER
		From T_USER_STAFF Where PK_STAFF = @pStaff_id

	-- Dua vao C_INTERNAL_ORDER de tim don vi cap 1
	Select TOP 1 @level1_unit_name = C_NAME
		From T_USER_UNIT  
		Where FK_UNIT in (Select PK_UNIT From T_USER_UNIT  Where FK_UNIT Is Null )
			And C_INTERNAL_ORDER = Left(@v_internal_order,Len(C_INTERNAL_ORDER))
		
	Return @level1_unit_name

End
GO
-- Select dbo.f_GetUnitLevelOneName(212);Select dbo.f_GetUnitLevelOneName(211)

/*********************************************************************************
* Function f_GetUnitName
* Viet boi: Noname
* Ngay viet : N/A
* Chuc nang : Lay Ten don vi (truc tiep) cua NSD
*********************************************************************************/
CREATE Function f_GetUnitName(@pStaff_id int)
Returns nvarchar(500)
As
Begin
	Declare 
		@unit_id int,
		@unit_name nvarchar(500)
	
	--Lay don vi cua NSD hien thoi
	Select TOP 1 @unit_id = FK_UNIT From T_USER_STAFF Where PK_STAFF = @pStaff_id
	--Lay ten don vi cua don vi chua NSD hien thoi
	Select TOP 1 @unit_name = C_NAME From T_USER_UNIT Where PK_UNIT = @unit_id
	Return @unit_name
End
GO
--select dbo.f_GetUnitName(1741)