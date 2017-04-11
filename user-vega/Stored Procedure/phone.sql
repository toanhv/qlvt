if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[GetUserInUnit]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[GetUserInUnit]
GO
------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE GetUserInUnit
	@p_unit_id int 
WITH ENCRYPTION
AS
	SET NOCOUNT ON
	SELECT C_NAME,
			C_TEL_LOCAL,
			C_TEL,
			C_TEL_MOBILE,
			C_TEL_HOME,
			C_FAX
	FROM T_USER_STAFF

	WHERE FK_UNIT = @p_unit_id
RETURN 0
Go
--GetUserInUnit 159
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[GetUnitInUnit]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[GetUnitInUnit]
GO
------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE GetUnitInUnit
	@p_unit_id int -- 
	WITH ENCRYPTION
AS
	SELECT PK_UNIT
			
	FROM T_USER_UNIT

	WHERE FK_UNIT = @p_unit_id

	ORDER BY PK_UNIT DESC
RETURN 0
Go
--GetUserInUnit 159
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[GetUnit]') and OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].[GetUnit]
GO
------------------------------------------------------------------------------------------------------------------------
CREATE PROCEDURE GetUnit
	@p_unit_id int -- 
WITH ENCRYPTION
AS
	SET NOCOUNT ON
	SELECT C_NAME
			
	FROM T_USER_UNIT

	WHERE PK_UNIT = @p_unit_id
RETURN 0
Go
--GetUnit 44