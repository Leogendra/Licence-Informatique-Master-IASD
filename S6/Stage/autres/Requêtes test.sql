execute as login='openbee'

declare @BL_number varchar(50) = 577548
declare @lot_number varchar(50) = '146815'


SELECT * FROM GetComplementTracaAgreageByBatchNumber(@lot_number)

SELECT TOP 100 *
FROM lot_complements
where no_lot LIKE '14681[5-8]'
order by dh_modif desc


SELECT TOP 100 *
FROM AchatsEtCommandes
where lot LIKE '14681[5-8]'















/*
declare @num_dossier varchar(50) = ''
declare @num_lot varchar(50) = ''

 UPDATE [INFOCENTER].[euclide].[rplMVW_ACHATS]
 SET com_dossier = @num_dossier
 FROM [INFOCENTER].[euclide].[rplMVW_ACHATS]
 WHERE lot LIKE @num_lot
*/