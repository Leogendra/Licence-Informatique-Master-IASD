USE [OPENBEE]
GO
/****** Object:  UserDefinedFunction [dbo].[GetArticleDesignationByBatchNumber]    Script Date: 26/08/2022 16:45:25 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Gatien Haddad
-- Create date: 2022-06-22
-- Description:	lecture du libellé de l'article 
--              en fonction du numéro de lot
-- =============================================
ALTER FUNCTION [dbo].[GetArticleDesignationByBatchNumber] (	
	@lot_number varchar(50)
)
RETURNS TABLE 
AS
RETURN (
	SELECT (CASE (SELECT COUNT(*) FROM AchatsEtCommandes WHERE lot = dbo.TrimBatchNumber(@lot_number))
			WHEN 0 THEN (SELECT 'Numéro de lot INVALIDE')
			ELSE (SELECT ART_LIB1 FROM AchatsEtCommandes WHERE lot = dbo.TrimBatchNumber(@lot_number))
			END) designation
)
