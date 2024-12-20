USE [OPENBEE]
GO
/****** Object:  UserDefinedFunction [dbo].[GetTracaSAVByBLNumber]    Script Date: 26/08/2022 16:45:37 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Gatien Haddad
-- Create date: 2022-06-27
-- Description:	réupération du transporteur,
--				date, désignation article,
--				numéro de lot et nom client
--				à partir du numéro de BL
-- =============================================
ALTER FUNCTION [dbo].[GetTracaSAVByBLNumber] (	
	@BL_number varchar(50)
)
RETURNS TABLE 
AS
RETURN (
	SELECT TOP 1 date_bl, CLI_NOM, tour_libelle
	FROM (
		SELECT 0 id, 
				cast(v.ven_bl as varchar(50)) num_bl, 
				cast(v.ven_date_bl as varchar(50)) date_bl,
				v.CLI_NOM,
				t.tour_libelle
		FROM erp_ventes v, erp_tournees t
		WHERE cast(v.ven_bl as varchar(50)) = @BL_number
		AND t.tour_code = v.TOURNEE

		UNION
		SELECT 1 , @BL_number,
		'',
		'Numéro de lot INVALIDE',
		'Numéro de lot INVALIDE'
	) t1
	ORDER BY id
)