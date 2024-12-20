USE [OPENBEE]
GO
-- =============================================
-- Author:		Gatien HADDAD
-- Create date: 2022-06-21
-- Description:	Récupère les compléments de 
--				traça d'un lot avec le n° SAGE
-- =============================================
ALTER FUNCTION [dbo].[GetComplementTracaAgreageByBatchNumber] (
	@lot_number varchar(50)
)
RETURNS TABLE 
AS	
RETURN (
	SELECT TOP 1 r9.numero_dossier, r9.presentation, r0.origine, r0.DLUO, r0.agrement, r0.lot_fournisseur, r0.traca tracabilite, 
		r0.fournisseur, r0.engin_peche, r0.date_congelation, r0.nom denomination_produit, r0.nom_scientifique, r9.calibre, r9.container numero_conteneur,
		(CASE (SELECT COUNT(*) FROM lot_complements WHERE no_lot = dbo.TrimBatchNumber(@lot_number))
			WHEN 0 then (select 'Numéro de lot INVALIDE')
			ELSE (SELECT TOP 1 code_zone 
					FROM dic_zone_peche d, lot_complements l 
					WHERE d.libelle = l.valeur 
					AND l.numero = 1 
					AND l.no_lot = dbo.TrimBatchNumber(@lot_number)
					GROUP BY code_zone
					ORDER BY Len(code_zone)) -- pour éviter les doublons tels que "70" et "70.0"
			END) FAO, 
			r0.no_lot code,
			(SELECT abreviation
			FROM ListeEnginsPeche liste
			JOIN lot_complements lot ON lot.valeur = liste.engin
			WHERE lot.numero = 29
			AND lot.no_lot = @lot_number
			AND lower(liste.engin) = lower(lot.valeur)) abreviation_engin
	FROM (SELECT TOP 1 
				0 id, 
				no_lot,
				CONCAT((CASE r1.libelle
						WHEN 'TH' THEN 'THON '
						WHEN 'REQ' THEN 'REQUIN '
						ELSE '' END), r2.libelle) nom, 
				r2.libelle2 nom_scientifique,
				MAX(case when numero = 2 then valeur end) origine,
				MAX(case when numero = 4 and dbo.RegexMatch(valeur,'([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})') = 1 --On vient regarder si la date est au bon format
												THEN convert(varchar, cast(valeur as date), 23) end) DLUO,
				MAX(case when numero = 10 then valeur end) agrement,
				MAX(case when numero = 11 then valeur end) lot_fournisseur,
				MAX(case when numero = 17 then substring(valeur, 8, 7) end) traca,
				MAX(case when numero = 22 then valeur end) fournisseur,
				MAX(case when numero = 29 then valeur end) engin_peche,
				MAX(case when numero = 30 and dbo.RegexMatch(valeur,'([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})') = 1
												THEN convert(varchar, cast(valeur as date), 23) end) date_congelation
			FROM lot_complements, art_poissons art --lecture de la rubrique 1
			left join art_rubriques r1 on r1.num = 1 and r1.code = art.art_rub1
			-- lecture de la rubrique 2
			left join art_rubriques r2 on r2.num = 2 and r2.code = art.art_rub2
			-- recherche des achats de moins d'un an
			left join AchatsEtCommandes ad on ad.ART_CODE = art.art_code and ad.DATE_REC > dateadd(year, -1,getdate())
			WHERE no_lot = ad.lot AND no_lot = dbo.TrimBatchNumber(@lot_number) -- jointure pour 
			GROUP BY no_lot, r1.libelle, r2.libelle, r2.libelle2
				
			-- Union dans le cas où le numéro de lot n'exsite pas, pour générer une ligne d'erreur
			union
			SELECT 1 , dbo.TrimBatchNumber(@lot_number),
			'Numéro de lot INVALIDE',
			'Numéro de lot INVALIDE',
			'Numéro de lot INVALIDE',
			'',
			'Numéro de lot INVALIDE',
			'Numéro de lot INVALIDE',
			'Numéro de lot INVALIDE',
			'Numéro de lot INVALIDE',
			'Numéro de lot INVALIDE',
			''
	) r0,
	(SELECT 0 id, 
			(CASE WHEN com_dossier LIKE 'D[0-9]%' THEN com_dossier ELSE '' END) numero_dossier, --Comme le numéro de dossier est dans le champs "commentaire", on vérifie que le champs contient bien un numéro de dossier (regex)
			(CASE art_stat3
			-- on renomme les abréviations
			WHEN 'FIL' THEN 'Filet'
			WHEN 'LGE' THEN 'Longe'
			WHEN 'STEA' THEN 'Steak'
			WHEN 'DAR' THEN 'Darne'
			WHEN 'ENT' THEN 'Entier' 
			ELSE art_stat3 END) AS presentation, 
			isnull(rub5_lib, '') AS calibre, 
			container
	FROM dbo.AchatsEtCommandes -- vue conteant les achats + les commandes
	WHERE lot = dbo.TrimBatchNumber(@lot_number)

	UNION 

	SELECT 1,
	'Numéro de lot INVALIDE',
	'Numéro de lot INVALIDE',
	'Numéro de lot INVALIDE',
	'Numéro de lot INVALIDE'
	) r9
	ORDER BY r0.id
)
