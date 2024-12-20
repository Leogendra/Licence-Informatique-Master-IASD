USE [OPENBEE]
GO
/****** Object:  UserDefinedFunction [dbo].[GetAllAcountingProvidor]    Script Date: 26/08/2022 16:45:10 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Gatien HADDAD
-- Create date: 19/08/2022
-- Description:	Renvoie la liste de tous
--				les fournisseurs présent dans
--				la base quadra
-- =============================================
ALTER FUNCTION [dbo].[GetAllAcountingProvidor] ()
RETURNS TABLE 
AS
RETURN 
(
	SELECT numero, intitule, ville
	FROM dbo.FOURNISSEURS_COMPTA
	WHERE dossier = 'SBARBA'
	AND collectif = '40100000'
	AND type = 'F'
)
