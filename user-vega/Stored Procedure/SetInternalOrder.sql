--Use [isa-user]
--Alter Table T_USER_UNIT Add C_INTERNAL_ORDER varchar(500)
--Go
--Alter Table T_USER_STAFF Add C_INTERNAL_ORDER varchar(500)
--Go

--------------------------------------------------------------------------------------------------------------------
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_FormatOrder]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_FormatOrder]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_UnitSetInternalOrder]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_UnitSetInternalOrder]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_StaffSetInternalOrder]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_StaffSetInternalOrder]
GO

/**********************************************************************************************************************************
*   Function: f_FormatOrder
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 12/10/2004
*	Chuc nang : Them so "0" vao ben trai cua 1 thu tu sao cho co do dai la 5
**********************************************************************************************************************************/
CREATE Function f_FormatOrder(@p_order int, @p_len int)
Returns varchar(500)
As
Begin
	Declare @ret_value varchar(500)
	Set @ret_value = Convert(varchar,@p_order)
	If Len(@ret_value)>=@p_len
		Return @ret_value
	While (Len(@ret_value)<@p_len)
		Set @ret_value = '0'+@ret_value
		
	Return @ret_value
End
GO
-- Select dbo.f_FormatOrder(123456,5)

/**********************************************************************************************************************************
*   SP: USER_UnitSetInternalOrder
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 15/10/2004
*	Chuc nang : Dat gia tri cho column C_INTERNAL_ORDER
**********************************************************************************************************************************/
CREATE PROCEDURE USER_UnitSetInternalOrder
	@p_parent_unit_id int
AS
	Declare @v_first_order varchar(500), @v_min_order int

	Create Table #T_UNIT1 (PK_UNIT int, C_INTERNAL_ORDER varchar(500), C_NEW_ORDER Int IDENTITY(1,1), C_ORDER Int) 
	Create Table #T_UNIT2 (PK_UNIT int, C_INTERNAL_ORDER varchar(500), C_ORDER Int) 

	-- Xu ly cac doi tuong goc (khong co FK den bat ky doi tuong nao)
	If @p_parent_unit_id Is Null
		Begin
			Insert Into #T_UNIT1(PK_UNIT,C_INTERNAL_ORDER, C_ORDER) 
				Select PK_UNIT, C_INTERNAL_ORDER, C_ORDER From T_USER_UNIT Where FK_UNIT Is Null ORDER BY C_ORDER

			Update #T_UNIT1 Set C_ORDER = C_NEW_ORDER

			Update T_USER_UNIT Set C_INTERNAL_ORDER = (Select dbo.f_FormatOrder(C_ORDER,5) From #T_UNIT1 Where PK_UNIT=T_USER_UNIT.PK_UNIT) 
				Where PK_UNIT In (Select PK_UNIT From #T_UNIT1)	

			Insert Into #T_UNIT2(PK_UNIT,C_INTERNAL_ORDER, C_ORDER) 
				Select a.PK_UNIT, b.C_INTERNAL_ORDER, a.C_ORDER 
					From T_USER_UNIT a, T_USER_UNIT b
					Where a.FK_UNIT = b.PK_UNIT And
						a.FK_UNIT In (Select PK_UNIT From #T_UNIT1) ORDER BY a.C_ORDER

			Delete #T_UNIT1

			Insert Into #T_UNIT1(PK_UNIT,C_INTERNAL_ORDER, C_ORDER) 
				Select PK_UNIT, C_INTERNAL_ORDER, C_ORDER From #T_UNIT2

			Select @v_min_order = isnull(min(C_NEW_ORDER),0) From #T_UNIT1

			Delete #T_UNIT2
		End
	Else
		Insert Into #T_UNIT1(PK_UNIT,C_INTERNAL_ORDER, C_ORDER) 
			Select PK_UNIT, C_INTERNAL_ORDER, C_ORDER From T_USER_UNIT Where FK_UNIT=@p_parent_unit_id

	If (Select Count(*) From #T_UNIT1)=0
		Return

	While (1=1)
		Begin
			Update T_USER_UNIT Set 
				C_INTERNAL_ORDER = (Select C_INTERNAL_ORDER From T_USER_UNIT a Where a.PK_UNIT = T_USER_UNIT.FK_UNIT)
										+ dbo.f_FormatOrder(C_ORDER,5) 
				Where PK_UNIT In (Select PK_UNIT From #T_UNIT1)	

			Insert Into #T_UNIT2(PK_UNIT,C_INTERNAL_ORDER, C_ORDER) 
				Select a.PK_UNIT, b.C_INTERNAL_ORDER, a.C_ORDER 
					From T_USER_UNIT a, T_USER_UNIT b
					Where a.FK_UNIT = b.PK_UNIT And
						a.FK_UNIT In (Select PK_UNIT From #T_UNIT1) ORDER BY a.C_ORDER

			Delete #T_UNIT1

			Insert Into #T_UNIT1(PK_UNIT,C_INTERNAL_ORDER, C_ORDER) 
				Select PK_UNIT, C_INTERNAL_ORDER, C_ORDER From #T_UNIT2

			Select @v_min_order = isnull(min(C_NEW_ORDER),0) From #T_UNIT1

			Delete #T_UNIT2


			If (Select Count(*) From #T_UNIT1)=0
				Break
		End
GO


/**********************************************************************************************************************************
*   SP: USER_StaffSetInternalOrder
*	Viet boi : Nguyen Tuan Anh
*	Ngay : 15/10/2004
*	Chuc nang : Dat gia tri cho column C_INTERNAL_ORDER cua table T_USER_STAFF
**********************************************************************************************************************************/
CREATE PROCEDURE USER_StaffSetInternalOrder
AS
	SET NOCOUNT ON
	Update T_USER_STAFF Set C_INTERNAL_ORDER = (Select C_INTERNAL_ORDER From T_USER_UNIT Where PK_UNIT=T_USER_STAFF.FK_UNIT) + dbo.f_FormatOrder(Isnull(T_USER_STAFF.C_ORDER,0),5)
Return 0
GO

------------------------------------------------------------------------------------------------------
-- Thuc hien viec sap xep lai thu tu
/*
Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', 183, 1, 'FK_UNIT=175'

Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', 199, 3, 'FK_UNIT=148'

Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', 248, 3, 'FK_UNIT=149'

Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', 262, 2, 'FK_UNIT=134'

Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', 273, 8, 'FK_UNIT=161'

Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', 291, 3, 'FK_UNIT=164'

Exec SP_ReOrder 'T_USER_UNIT', 'C_ORDER', 'PK_UNIT', 361, 3, 'FK_UNIT=160'


Update T_USER_UNIT Set C_INTERNAL_ORDER=''

Exec USER_UnitSetInternalOrder

--Select pk_unit,c_name,c_order,c_internal_order From T_USER_UNIT Order by C_INTERNAL_ORDER

Exec USER_StaffSetInternalOrder

Select * From T_USER_STAFF Order by C_INTERNAL_ORDER

*/
--select * From t_user_staff