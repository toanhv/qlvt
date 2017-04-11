if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[USER_GetPersonalInfoOfAllEndUser]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[USER_GetPersonalInfoOfAllEndUser]
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetLastWord]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetLastWord]
GO


CREATE function f_GetLastWord(@pString nvarchar(1000))
Returns nvarchar(100)
As
Begin
	Declare 
		@i int,
		@current_pos int,
		@string_return nvarchar(100)
	set @i = 0
	while (@i<len(@pString))
	Begin
		if (substring(@pString,@i,1)=' ')
			set @current_pos = @i
		set @i=@i+1
	End

	set @string_return = substring(@pString,@current_pos+1,len(@pString))
Return  @string_return
End
GO
--select dbo.f_GetLastWord('le huy tue')
----------------------------------------------------------------------------------------------------------------
-- Thu tuc nay chi duoc su dung trong ham tuong ung trong WebService cua ISA-USER 
-- Thu tuc nay tra lai mot RecoredSet chua thong tin ca nhan cua tat ca can bo (staff)
-- la end-user cua mot phan mem ung dung
-- Created by:Nguyen Tuan Anh

Create Procedure USER_GetPersonalInfoOfAllEndUser
	@p_app_code nvarchar(30)
As
	Set Nocount ON
	Declare @app_id int
	-- Lay ID cua Application
	Exec SP_GetOtherValueFromKeyValue 'T_USER_APPLICATION', 'C_CODE', @p_app_code, 'PK_APPLICATION', @app_id OUTPUT
	
	Select A.PK_STAFF,
			A.C_NAME,
			A.C_CODE,
			A.FK_UNIT,
			B.C_CODE AS C_POSITION_CODE,
			B.C_NAME AS C_POSITION_NAME,
			C.C_CODE AS C_POSITION_GROUP_CODE,
			A.C_ADDRESS,
			A.C_EMAIL,
			A.C_TEL,
			A.C_ORDER
	From T_USER_STAFF A
		Left Join T_USER_POSITION B On A.FK_POSITION = B.PK_POSITION
		Left Join T_USER_POSITION_GROUP C On B.FK_POSITION_GROUP = C.PK_POSITION_GROUP
	Where A.C_STATUS=1
		And A.PK_STAFF In (Select FK_STAFF From T_USER_ENDUSER Where FK_APPLICATION=@app_id)
	Order by dbo.f_GetLastWord(A.C_NAME)
	Set Nocount Off

GO
