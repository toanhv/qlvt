use [qlts-vega]
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Shopping_Update') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Shopping_Update
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Shopping_GetAll') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Shopping_GetAll
GO
---******************************** LIST PROCEDURE *********************************************************
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].qlts_Shopping_GetSingle') And OBJECTPROPERTY(id, N'IsProcedure') = 1)
drop procedure [dbo].qlts_Shopping_GetSingle
GO
---******************************* STORE PROCEDURE PROGRAMMING ********************************************
